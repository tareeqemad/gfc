<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/03/22
 * Time: 09:00 ص
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Company_campaigns extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/New_rmodel');
        $this->PKG = 'GEDCO_STATISTICS_PKG';
        $this->date_1 = $this->input->post('date_1');
        $this->date_2 = $this->input->post('date_2');
        $this->campaign_type = $this->input->post('campaign_type');
    }

    function index()
    {
        $data['title']='حملات الشركة';
        $data['content']='Company_campaigns_reports';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function public_first_stage()
    {
        $data['title'] = 'حملات رمضان 2022';
        $this->_look_ups($data);
        $this->load->view('Company_campaigns_second', $data);
    }

    function public_second_stage()
    {
        $data['title'] = 'سدد شهري واكسب فوري';
        $this->_look_ups($data);
        $this->load->view('Company_campaigns_first', $data);
    }

    function public_third_stage()
    {
        $data['title'] = 'رسم بياني';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'DAY_OFFER_COUNT_BRANCH', $this->_postedData());
            $this->load->view('Company_campaigns_third', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }

    }

    function public_fourth_stage()
    {
        $data['title'] = 'حملة نقاطي';
        $this->_look_ups($data);
        $this->load->view('Company_campaigns_fourth', $data);
    }

    function public_fifth_stage()
    {
        $data['title'] = 'كشف حساب';
        $this->_look_ups($data);
        $this->load->view('Company_campaigns_fifth', $data);
    }

    function  public_third_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $chart_data = $this->New_rmodel->general_get($this->PKG,'DAY_OFFER_COUNT_BRANCH', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'BRANCH_NAME' => $row["BRANCH_NAME"],
                    'COUNT_SUBSCRIBER' => $row["COUNT_SUBSCRIBER"],
                    'SUM_PAID_VALUE' => $row["SUM_PAID_VALUE"]
                );
            }
            echo json_encode($output);
        }
    }

    function _look_ups(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');

        $data['campaign_type'] = $this->constant_details_model->get_list(415);
        $data['repayment_type'] = $this->constant_details_model->get_list(416);
        $data['finish_status'] = $this->constant_details_model->get_list(424);
        $data['posting_status'] = $this->constant_details_model->get_list(426);
        $data['conditions_type'] = $this->constant_details_model->get_list(427);
        $data['payment_method'] = $this->constant_details_model->get_list(428);
        $data['branches'] = $this->constant_details_model->get_list(429);
        $data['sadad_type'] = $this->constant_details_model->get_list(432);
        $data['has_rem'] = $this->constant_details_model->get_list(433);
        $data['point_type'] = $this->constant_details_model->get_list(434);
        $data['type'] = $this->constant_details_model->get_list(435);
        $data['billc_type'] = $this->constant_details_model->get_list(436);
        $data['subsciber_status'] = $this->constant_details_model->get_list(437);
        $data['status'] = $this->constant_details_model->get_list(438);
        $data['repayment'] = $this->constant_details_model->get_list(439);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('morris.css');
        add_js('morris.min.js');
        add_js('raphael.min.js');
    }

    function _postedData()
    {
        $result = array(
            array('name' => ':FROM_DAY_IN', 'value' => $this->date_1, 'type' => '', 'length' => -1),
            array('name' => ':TO_DAY_IN', 'value' => $this->date_2, 'type' => '', 'length' => -1),
            array('name' => ':CAMPAIGN_TYPE', 'value' => $this->campaign_type, 'type' => '', 'length' => -1),
            array(),
            array(),
        );

        return $result;
    }
}