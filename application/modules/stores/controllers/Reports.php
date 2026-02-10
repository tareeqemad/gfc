<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 14/12/14
 * Time: 12:15 م
 */

class Reports extends MY_Controller{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('class_amount_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/report_branch_model');
        $this->load->model('settings/constant_details_model');
    }

    function index(){
        add_css('combotree.css');
        $this->load->model('stores_model');
        $data['stores'] = $this->stores_model->get_all();
        $data['items_type'] = $this->constant_details_model->get_list(41);
        $data['account_type'] = $this->constant_details_model->get_list(36);
        $data['request_side_all'] = $this->constant_details_model->get_list(15);
        $data['title']='ارصدة الاصناف';
        $data['content']='reports_index';
        add_css('select2_metro_rtl.css');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_js('select2.min.js');
        add_js('jquery.hotkeys.js');
        $this->load->view('template/template',$data);
    }


}