<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 12/07/16
 * Time: 09:18 ص
 */
class Reports extends MY_Controller
{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('Requests_model');
    }


    function index()
    {

        $data['title'] = 'تقارير الفنية';
        $data['content'] = 'reports_index';

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['REQUESTS_TYPE'] = $this->constant_details_model->get_list(105);
        $data['project_tec_type'] = $this->constant_details_model->get_list(51);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template', $data);

    }

    function project()
    {

        $data['title'] = 'تقرير المشروع';
        $data['content'] = 'project_index';

        $data['project_tec'] = isset($this->p_project_tec) ? $this->p_project_tec : '';
        $data['request_id'] = isset($this->p_request_id) ? $this->p_request_id : '';
        $data['work_order_id'] = isset($this->p_work_order_id) ? $this->p_work_order_id : '';
        $data['ass_id'] = isset($this->p_ass_id) ? $this->p_ass_id : '';

        $sql = isset($this->p_project_tec) && $this->p_project_tec != null ? " and lower(PROJECT_SERIAL) = lower('$this->p_project_tec') " : '';
        $sql .= isset($this->p_request_id) && $this->p_request_id != null ? " and lower(REQUEST_CODE) = lower('$this->p_request_id') " : '';
        $sql .= isset($this->p_work_order_id) && $this->p_work_order_id != null ? " and lower(WORK_ORDER_CODE) = lower('$this->p_work_order_id') " : '';
        $sql .= isset($this->p_ass_id) && $this->p_ass_id != null ? " and lower(WORK_ASSIGNMENT_CODE) = lower('$this->p_ass_id') " : '';

        $data["rows"] = $this->Requests_model->get_project_list($sql, 0, 2000);

        $this->load->view('template/template', $data);
    }
}