<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/12/14
 * Time: 12:15 م
 */

class Reports extends MY_Controller{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/report_branch_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('budget/budget_chapter_model');
    }

    function index(){
        add_css('combotree.css');

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['currency'] = $this->currency_model->get_list();
        $data['branch_dep'] = $this->report_branch_model->get_all();
        $data['type'] = $this->constant_details_model->get_list('4');
        $data['customer_type'] = $this->constant_details_model->get_list('28');
        $data['type_list'] = $this->constant_details_model->get_all('12');
        $data['types_lists'] = $this->constant_details_model->get_all('38');
        $data['title']='تقارير الحسابات';
        $data['content']='reports_index';
        $data['fin_sources'] = $this->constant_details_model->get_list(13);
        $data['banks'] = $this->constant_details_model->get_list(9);
		$data['class_acount_type_all'] = $this->constant_details_model->get_list(36);
        $data['class_type_all'] = $this->constant_details_model->get_list(41);
        $data['compares'] = $this->constant_details_model->get_list(323);
        $data['database_name'] = $this->database_name;

        $data['chapters'] = $this->budget_chapter_model->get_all();

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template',$data);
    }

}