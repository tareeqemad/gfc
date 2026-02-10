<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 26/12/22
 * Time: 12:00 ص
 */

class Dues_emp_gaza extends MY_Controller{

    var $MODEL_NAME= 'Dues_emp_gaza_model';
    var $PAGE_URL= 'reports_ebllc/Dues_emp_gaza/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('Dues_emp_gaza_model');
        $this->load->model('root/rmodel');

        $this->branch_id = $this->input->post('branch_id');
        $this->subscriber_no = $this->input->post('subscriber_no');
        $this->subscriber_name = $this->input->post('subscriber_name');
        $this->emp_id = $this->input->post('emp_id');
        $this->type_job = $this->input->post('type_job');
        $this->payment_status = $this->input->post('payment_status');
        $this->pposting_month = $this->input->post('pposting_month');
        $this->dues_type = $this->input->post('dues_type');

    }

    function index()
    {
        $data['content']='dues_emp_gaza_index';
        $data['title']='تحويل مستحقات موظفين غزة';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= "where 1 = 1";
        $where_sql.= ($this->subscriber_no!= null)? " AND M.SUBSCRIBER= '{$this->subscriber_no}' " : '';
        $where_sql .= isset($this->subscriber_name) && $this->subscriber_name !=null ? " AND  M.NAME like '%{$this->subscriber_name}%' " :"" ;
        $where_sql.= ($this->branch_id!= null)? " AND M.BRANCH= '{$this->branch_id}' " : '';
        $where_sql.= ($this->emp_id!= null)? " AND M.ID= '{$this->emp_id}' " : '';
        $where_sql.= ($this->pposting_month!= null)? " AND M.PPOSTING_MONTH= '{$this->pposting_month}' " : '';
        $where_sql.= ($this->payment_status!= null)? " AND M.PAID= '{$this->payment_status}' " : '';
        $where_sql.= ($this->type_job!= null)? " AND M.EMP_TYPE= '{$this->type_job}' " : '';
        $where_sql.= ($this->dues_type!= null)? " AND M.TYPE= '{$this->dues_type}' " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('GAZA_ENTITLEMENTS_TB  M '.$where_sql);
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
        $this->load->view('dues_emp_gaza_page',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['payment_status'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',489,0);
        $data['type_job'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',181,0);
        $data['branches'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',405,0);
        $data['dues_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',493,0);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
