<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2018-10-04
 * Time: 10:53 AM
 */

class As_reports extends MY_Controller{

    function  __construct(){
        parent::__construct();
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('employees/employees_model');
        $this->load->model('settings/constant_details_model');
    }

    function index(){
        $data['title']='التقارير';
        $data['content']='reports_assignment_index';

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->_lookUps_data($data);
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->load->view('template/template',$data);
    }

    function _lookUps_data(&$data){
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['all_employees'] = $this->employees_model->get_all();
        $data['adopt'] = $this->constant_details_model->get_list(222);
        $data['meal'] = $this->constant_details_model->get_list(241);
        $data['user_branch'] = $this->user->branch;
    }

}