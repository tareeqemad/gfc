<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: ashikhali & telbawab
 * Date: 15/02/22
 * Time: 09:08 ص
 */

class Gedco_totals extends MY_Controller{

    function  __construct(){
        parent::__construct();
        $this->load->model('root/New_rmodel');
        $this->PKG = 'GEDCO_STATISTICS_PKG';
        $this->date_1 = $this->input->post('date_1');
    }

    function index()
    {
        $data['title']='احصائيات عامة';
        $data['content']='gedco_totals_index';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function _look_ups(&$data)
    {
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('morris.css');
        add_js('morris.min.js');
        add_js('raphael.min.js');
    }

    function public_first_stage()
    {
        $data['title'] = 'الاحمال';
        $data['desc'] = 'الاحمال المتوفرة والاحمال المطلوبة';
        $this->load->view('gedco_totals_first_stage', $data);
    }

    function public_second_stage()
    {
        $data['title'] = ' اشارات الصيانة الطارئة';
        $data['desc'] = 'اعداد وحالة الاشارات الطارئة الواردة من المواطنين ';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'DAY_SIGNAL_COUNT_BRANCH', $this->_postedData());
            $this->load->view('gedco_totals_second_stage', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }
    }
    //signal data between two date in chart Emergency
    function  public_second_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $chart_data = $this->New_rmodel->general_get($this->PKG, 'DAY_SIGNAL_COUNT_BRANCH', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'BRANCH_NAME' => $row["BRANCH_NAME"],
                    'NO_SIGNAL' => $row["NO_SIGNAL"],
                    'PROCESSING' => $row["PROCESSING"],
                    'REPEATED' => $row["REPEATED"],
                    'ENTRY' => $row["ENTRY"]
                );
            }
            echo json_encode($output);
        }
    }

    function public_third_stage()
    {
        $data['title'] = 'مركز الاتصالات';
        $data['desc'] = 'اعداد الاتصالات  والمراجعات الواردة الى مركز الاتصالات';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'CALL_CENTER_STATISTICS', $this->_postedData());
            $this->load->view('gedco_totals_third_stage', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }
    }

    function  public_third_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $chart_data = $this->New_rmodel->general_get($this->PKG, 'CALL_CENTER_STATISTICS', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'TYPE_Q_NAME' => $row["TYPE_Q_NAME"],
                    'ALL_BRANCH' => $row["30"],
                );
            }
            echo json_encode($output);
        }
    }

    function public_fourth_stage()
    {
        $data['title'] = 'معاملات التجاري والتفتيش';
        $data['desc'] = 'اعداد معاملات الادارة التجارية المنجزة (خدمات وتفتيش)';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'DAY_TRADING_STATISTICS_BRANCH', $this->_postedData());
            $this->load->view('gedco_totals_fourth_stage', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }
    }

    function  public_fourth_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $chart_data = $this->New_rmodel->general_get($this->PKG, 'DAY_TRADING_STATISTICS_BRANCH', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'TYPE_NAME' => $row["TYPE_NAME"],
                    'GAZA' => $row["2"],
                    'NORTH' => $row["3"],
                    'MIDDLE' => $row["4"],
                    'KHAN' => $row["6"],
                    'RAFAH' => $row["7"],
                );
            }
            echo json_encode($output);
        }
    }

    function public_fifth_stage()
    {
        $data['title'] = 'تحصيلات التجاري والتفتيش';
        $data['desc'] = 'التحصيلات النقدية للمعاملات التجارية وقضايا التفتيش';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'DAY_TRADING_STATISTICS2_BRANCH', $this->_postedData());
            $this->load->view('gedco_totals_fifth_stage', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }
    }

    function  public_fifth_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $chart_data = $this->New_rmodel->general_get($this->PKG, 'DAY_TRADING_STATISTICS2_BRANCH', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'TYPE_NAME' => $row["TYPE_NAME"],
                    'GAZA' => $row["2"],
                    'NORTH' => $row["3"],
                    'MIDDLE' => $row["4"],
                    'KHAN' => $row["6"],
                    'RAFAH' => $row["7"],
                );
            }
            echo json_encode($output);
        }
    }

    function public_sixth_stage()
    {
        $data['title'] = 'تحصيلات فوترة وشحنات';
        $data['desc'] = 'تحصيلات سداد الفواتير وشحنات مسبق الدفع (هولي ودكسن)';
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'DAY_COLLECTION_BRANCH', $this->_postedData());
            $this->load->view('gedco_totals_sixth_stage', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال التاريخ');
        }

    }

    function  public_sixth_stage_chart()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $chart_data = $this->New_rmodel->general_get($this->PKG, 'DAY_COLLECTION_BRANCH', $this->_postedData());
            foreach ($chart_data as $row) {
                $output[] = array(
                    'BRANCH_NAME' => $row["BRANCH_NAME"],
                    'CASH_COUNT' => $row["CASH_COUNT"],
                    'CASH_VAL' => $row["CASH_VAL"],
                    'HOLLEY_COUNT' => $row["HOLLEY_COUNT"],
                    'HOLLEY_VAL' => $row["HOLLEY_VAL"],
                    'DEXEN_COUNT' => $row["DEXEN_COUNT"],
                    'DEXEN_VAL' => $row["DEXEN_VAL"],
                );
            }
            echo json_encode($output);
        }
    }

    function public_seventh_stage()
    {
        $data['title'] = 'tab7';
        $data['result'] =$this->rmodel->get('SUBSCRIBER_INFO_GET', 10101012);
        $this->load->view('gedco_totals_seventh_stage', $data);
    }

    function public_eighth_stage()
    {
        $data['title'] = 'tab8';
        $data['result'] =$this->rmodel->get('SUBSCRIBER_INFO_GET', 10101012);
        $this->load->view('gedco_totals_eighth_stage', $data);
    }




    function _postedData()
    {
        $result = array(
            array('name' => ':DATE_1', 'value' => $this->date_1, 'type' => '', 'length' => -1),
            array('name' => ':DATE_2', 'value' => $this->date_1, 'type' => '', 'length' => -1),
            array(),
            array(),
        );
        return $result;
    }
}
