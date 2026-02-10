<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 03/04/23
 * Time: 11:00 ص
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Company_drive extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/New_rmodel');
        $this->load->model('root/rmodel');
        $this->PKG = 'CAMPAIGN_PKG';

        $this->date_1 = $this->input->post('date_1');
        $this->date_2 = $this->input->post('date_2');
        $this->campaign_type = $this->input->post('campaign_type');
        $this->discount_type = $this->input->post('discount_type');
    }

    function index()
    {
        $data['title']='حملات رمضان';
        $data['content']='company_drive_reports';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function public_first_stage()
    {
        $data['title'] = 'الخصومات';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'CAMPAIGN', $this->_postedData());
            $this->load->view('company_drive_first', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }

    }

    function  public_first_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $chart_data = $this->New_rmodel->general_get($this->PKG,'CAMPAIGN', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'BRANCH_NAME' => $row["BRANCH_NAME"],
                    'CNT_SUB' => $row["CNT_SUB"],
                    'INSTANT_PAY' => $row["INSTANT_PAY"],
                    'ACT_PAID' => $row["ACT_PAID"],
                    'QEST_VAL' => $row["QEST_VAL"],
                    'SUM_ALL_QEST1' => $row["SUM_ALL_QEST1"],
                    'SUM_ALL_QEST' => $row["SUM_ALL_QEST"]
                );
            }
            echo json_encode($output);
        }
    }

    function public_second_stage()
    {
        $data['title'] = 'تجزئة الاشتراكات العائلية';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'SPLIT_FAMILY_SUPSCRIBER', $this->_postedData());
            $this->load->view('company_drive_second', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }

    }

    function  public_second_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $chart_data = $this->New_rmodel->general_get($this->PKG,'SPLIT_FAMILY_SUPSCRIBER', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'BRANCH_NAME' => $row["BRANCH_NAME"],
                    'REQUEST_NO' => $row["REQUEST_NO"],
                    'EXC' => $row["EXC"]
                );
            }
            echo json_encode($output);
        }
    }


    function public_third_stage()
    {
        $data['title'] = 'التحول للقسط الثابت';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'CONVERT_STATIC_INSTALL', $this->_postedData());
            $this->load->view('company_drive_third', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }

    }

    function  public_third_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $chart_data = $this->New_rmodel->general_get($this->PKG,'CONVERT_STATIC_INSTALL', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'BRANCH_NAME' => $row["BRANCH_NAME"],
                    'COUNT_SUB' => $row["COUNT_SUB"],
                    'INSTALL_VALUE' => $row["INSTALL_VALUE"],
                    'DISCOUNT_VALUE' => $row["DISCOUNT_VALUE"] ,
                    'SUM_PAID' => $row["SUM_PAID"]
                );
            }
            echo json_encode($output);
        }
    }

    function public_fourth_stage()
    {
        $data['title'] = 'أهلنا ومانسيناكم';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'OUR_FAMILY_GET', $this->_postedData());
            $this->load->view('company_drive_fourth', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }

    }

    function  public_fourth_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $chart_data = $this->New_rmodel->general_get($this->PKG,'OUR_FAMILY_GET', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'BRANCH_NAME' => $row["BRANCH_NAME"],
                    'COUNT_SUB' => $row["COUNT_SUB"]
                );
            }
            echo json_encode($output);
        }
    }


    function public_fifth_stage()
    {
        $data['title'] = 'الانتساب عبر التطبيق';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $data['result'] = $this->New_rmodel->general_get($this->PKG,'JOIN_WITH_APP_TOTAL', $this->_postedData());
            $this->load->view('company_drive_fifth', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }

    }

    function  public_fifth_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->rmodel->package = 'CAMPAIGN_PKG';
            $chart_data = $this->New_rmodel->general_get($this->PKG,'JOIN_WITH_APP_TOTAL', $this->_postedData());

            foreach ($chart_data as $row) {
                $output[] = array(
                    'BRANCH_NAME' => $row["BRANCH_NAME"],
                    'COUNT_SUB' => $row["COUNT_SUB"]
                );
            }
            echo json_encode($output);
        }
    }

    function _look_ups(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('root/Rmodel');

        $data['campaign_type'] = $this->Rmodel->getAll('CAMPAIGN_PKG', 'APP_DETAILS_GET');
        $data['branches'] = $this->constant_details_model->get_list(429);
        $data['discount_type'] = $this->rmodel->getTwoColum('TSG_SETTING_PKG', 'CONSTANTS_DETAIL_BRANCH_GET',515,0);
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
            array(),
            array(),
        );

        return $result;
    }

    function _postedData_first()
    {
        $result = array(
            array('name' => ':FROM_DAY_IN', 'value' => $this->date_1, 'type' => '', 'length' => -1),
            array('name' => ':TO_DAY_IN', 'value' => $this->date_2, 'type' => '', 'length' => -1),
            array('name' => ':DISCOUNT_TYPE', 'value' => $this->discount_type, 'type' => '', 'length' => -1),
            array(),
            array(),
        );

        return $result;
    }

    function _postedData_fifth()
    {
        $result = array(
            array('name' => ':FROM_DAY_IN', 'value' => $this->date_1, 'type' => '', 'length' => -1),
            array('name' => ':TO_DAY_IN', 'value' => $this->date_2, 'type' => '', 'length' => -1),
            array('name' => ':CAMPAIGN_TYPE', 'value' => $this->campaign_type, 'type' => '', 'length' => -1),
            array('name' => ':DISCOUNT_TYPE', 'value' => $this->discount_type, 'type' => '', 'length' => -1),
            array(),
            array(),
        );

        return $result;
    }



}