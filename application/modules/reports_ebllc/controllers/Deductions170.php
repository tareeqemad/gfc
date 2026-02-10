<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 07/12/22
 * Time: 10:00 ص
 */

class Deductions170 extends MY_Controller{

    var $MODEL_NAME= 'Deductions170_model';
    var $PAGE_URL= 'reports_ebllc/Deductions170/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('Deductions170_model');
        $this->load->model('root/rmodel');

        $this->subscriber_no = $this->input->post('subscriber_no');
        $this->subscriber_name = $this->input->post('subscriber_name');
        $this->emp_id = $this->input->post('emp_id');
        $this->emp_name = $this->input->post('emp_name');
        $this->government = $this->input->post('government');
        $this->for_month = $this->input->post('for_month');
    }

    function index()
    {
        $data['content']='deductions170_index';
        $data['title']='استقطاعات الموظفين الحكوميين';
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
        $where_sql.= ($this->emp_id!= null)? " AND M.ID= '{$this->emp_id}' " : '';
        $where_sql .= isset($this->emp_name) && $this->emp_name !=null ? " AND  M.E_NAME like '%{$this->emp_name}%' " :"" ;
        $where_sql.= ($this->government!= null)? " AND M.GOV_TYPE= '{$this->government}' " : '';
        $where_sql.= ($this->for_month!= null)? " AND M.FOR_MONTH= '{$this->for_month}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count_bills('SUB_170_DISCOUN_V  M '.$where_sql);
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
        $this->load->view('deductions170_page',$data);
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
