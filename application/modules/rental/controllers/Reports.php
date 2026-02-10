<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 29/11/17
 * Time: 11:26 ص
 */
class reports extends MY_Controller{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
    }

    function index(){
        $data['title']='التقارير';
        $data['content']='reports_index';

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['deduction_bill_id_cons'] = $this->constant_details_model->get_list(182);
        $data['bank'] = $this->constant_details_model->get_list(9);
        $data['bank_branch'] = $this->constant_details_model->get_list(196);
        $data['contract_type'] = $this->constant_details_model->get_list(216);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template',$data);
    }

}