<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 06/12/22
 * Time: 10:00 ص
 */


class Districts_bills extends MY_Controller{

    var $MODEL_NAME= 'Districts_bills_model';
    var $PAGE_URL= 'reports_ebllc/Districts_bills/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('Districts_bills_model');
        $this->load->model('root/rmodel');

        $this->for_month = $this->input->post('for_month');
        $this->branch_id = $this->input->post('branch_id');

    }

    function index()
    {
        $data['content']='districts_bills_index';
        $data['title']='البلديات';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= "AND 1 = 1";

        $where_sql.= ($this->for_month!= null)? "AND B.FOR_MONTH = '{$this->for_month}' " : '';
        $where_sql.= ($this->branch_id!= null)? "AND B.BRANCH = '{$this->branch_id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('EBLLC.BILLS B,  EBLLC.BRANCHES BR,DISTRICTS D 
        WHERE ORG IN ( 1100,1101,1102,1103) 
        AND B.BRANCH = BR.BR_NO 
        AND B.DISTRICT = D.NO '.$where_sql);

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
        $this->load->view('districts_bills_page',$data);
    }

    function _look_ups(&$data){

        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
