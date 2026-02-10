<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 11/12/22
 * Time: 09:00 ص
 */

class Update_emp_data extends MY_Controller{

    var $MODEL_NAME= 'Update_emp_data_model';
    var $PAGE_URL= 'payroll_data/Update_emp_data_model/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('Update_emp_data_model');
        $this->load->model('hr_attendance/hr_attendance_model');

        $this->branch_id = $this->input->post('branch_id');
        $this->emp_no = $this->input->post('emp_no');
        $this->emp_id = $this->input->post('emp_id');

    }

    function index()
    {
        $data['content']='update_emp_data_index';
        $data['title']='تحديث بيانات الموظفين';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->branch_id!= null)? "AND EMP_PKG.GET_EMP_BRANCH (M.EMP_NO) = '{$this->branch_id}' " : '';
        $where_sql.= ($this->emp_no!= null)? " AND M.EMP_NO= '{$this->emp_no}' " : '';
        $where_sql.= ($this->emp_id!= null)? " AND M.EMP_ID= '{$this->emp_id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('EMP_INFO_UPDATE_TB  M'.$where_sql);
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
        $this->load->view('update_emp_data_page',$data);
    }


    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance_model');

        $data['branches']= $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no ,'hr_admin');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
