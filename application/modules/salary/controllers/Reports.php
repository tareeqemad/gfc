<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2022-05-17
 * Time: 09:46 AM
 */

class Reports extends MY_Controller{


    function  __construct(){
        parent::__construct();
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('employees/employees_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('constants_sal_model');
    }

    function index(){
        $data['title']='التقارير';
        $data['content']='reports_index';

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
        $data['vac_type_cons'] = $this->constant_details_model->get_list(214);
        $data['curr'] = $this->constant_details_model->get_list(364);
        $data['emp_type'] = $this->constants_sal_model->get_list(21);
        $data['extras_discounts'] = $this->constants_sal_model->get_list(25);
    }

}