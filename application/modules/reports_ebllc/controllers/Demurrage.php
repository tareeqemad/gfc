<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 11/07/23
 * Time: 13:00 م
 */
class Demurrage extends MY_Controller {

    var $MODEL_NAME= 'Demurrage_model';
    var $PAGE_URL= 'reports_ebllc/Demurrage/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->load->model('Root/New_rmodel');
        $this->rmodel->package = 'REPORT_PKG';

        $this->branch_id = $this->input->post('branch_id');
        $this->subscriber_no = $this->input->post('subscriber_no');
        $this->bill_type = $this->input->post('bill_type');
        $this->ratio = $this->input->post('ratio');
        $this->remainder = $this->input->post('remainder');
        $this->delay = $this->input->post('delay');
        $this->operation = $this->input->post('operation');
        $this->op_net_to_pay = $this->input->post('op_net_to_pay');

    }

    function index()
    {
        $data['content']='demurrage_index';
        $data['title']='غرامة التأخير - اشتراكات مسبق الدفع';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " AND 1=1 ";

        $where_sql.= ($this->branch_id!= null)? " AND B.BRANCH= '{$this->branch_id}' " : '';
        $where_sql.= ($this->subscriber_no!= null)? " AND B.SUBSCRIBER= '{$this->subscriber_no}' " : '';
        $where_sql.= ($this->bill_type!= null)? " AND B.TYPE= '{$this->bill_type}' " : '';
        $where_sql .= ($this->remainder!= null )? " AND  B.REMAINDER $this->ratio {$this->remainder}  " : "";
        $where_sql .= ($this->delay!= null )? " AND  D.DELAY $this->ratio {$this->delay}  " : "";
        $where_sql .= ($this->operation!= null )? " AND  B.REMAINDER $this->operation D.DELAY  " : "";
        $where_sql .= ($this->op_net_to_pay!= null )? " AND  B.NET_TO_PAY $this->op_net_to_pay D.DELAY  " : "";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('G_USER.BILLS202306 B, G_USER.DELAY202306 D
        WHERE (B.SUBSCRIBER = D.SUBSCRIBER)
        AND  B.TYPE  IN(200,205,210,211,212,213,214,215,216,217,218,219,220,221,222,301,311,321,331,341,343, 351,353  )
        AND D.DELAY >0 '.$where_sql);

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('demurrage_page',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['bill_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',11,1);
        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}