<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2023-07-19
 * Time: 1:16 PM
 */

class Commitment_reports extends MY_Controller{

    function  __construct(){
        parent::__construct();
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('employees/employees_model');
    }

    function index(){
        $data['title']='تقارير الإقرارات';
        $data['content']='commitment_reports_index';

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
    }

}