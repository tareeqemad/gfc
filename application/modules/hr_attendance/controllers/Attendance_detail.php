<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 26/04/2022
 * Time: 12:34 pm
 */
class Attendance_detail extends MY_Controller{

    var $PAGE_URL= 'hr_attendance/attendance_detail/get_page';
    var $PAGE_ACT;

    function  __construct(){
        parent::__construct();

        $this->load->model('root/New_rmodel');
        $this->PKG = 'HR_ATTENDANCE_PKG';

        $this->page_act= $this->input->post('page_act');

        $this->emp_no= $this->input->post('emp_no');
        $this->month= $this->input->post('month');
        /*
        my - شاشة حضور الموظف
        move_admin - شاشة الشؤون الادارية حسب المقر
        hr_admin - شاشة للشؤون الادارية تظهر كل الموظفين
        */

        $this->PAGE_ACT= $this->uri->segment(5);

        if(isset($this->PAGE_ACT) and $this->PAGE_ACT!='' and ($this->uri->segment(3)== 'index' or $this->uri->segment(3)== 'get') ){

            if(!HaveAccess(base_url("hr_attendance/attendance_detail/index/1/".$this->PAGE_ACT))){
                die('Error: No Permission '.$this->PAGE_ACT);
            }

            if($this->PAGE_ACT=='my'){

            }elseif($this->PAGE_ACT=='move_admin'){

            }elseif($this->PAGE_ACT=='hr_admin'){

            }else{
                die('PAGE_ACT');
            }
        }elseif($this->uri->segment(3)== 'index'){
            die('index');
        }
    }

    function index($page= 1, $page_act= -1){

        if(isset($this->PAGE_ACT)){
            if($this->PAGE_ACT=='my'){
                $data['title']='حضور وانصراف - الموظف';
            }elseif($this->PAGE_ACT=='move_admin'){
                $data['title']='حضور وانصراف - الشؤون الادارية - مسؤول الحركة في المقر';
            }elseif($this->PAGE_ACT=='hr_admin'){
                $data['title']='حضور وانصراف - الشؤون الادارية';
            }else{
                $data['title']=' حضور وانصراف؟؟';
            }
        }
        $data['page_act']= $page_act;
        $data['content']='attendance_detail_index';
        $data['page']=$page;
        $data['action'] = 'edit';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function  get_page(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->page_act=='my'){
                $this->emp_no = $this->user->emp_no;
            }
            $data['result'] = $this->New_rmodel->general_get($this->PKG, 'ATTENDANCE_DETAIL_GET', $this->_postedData());
            $this->load->view('attendance_detail_page', $data);
        }else{
            $this->print_error('يرجى التأكد من ادخال البيانات');
        }
    }

    function return_first_month($date){
        $year = substr($date,0,4);
        $month = substr($date,-2);
        $first_day_this_month = date('01/'.$month.'/'.$year);
        return $first_day_this_month;
    }

    function return_last_month($date){
        $year = substr($date,0,4);
        $month = substr($date,-2);

        $result = strtotime("01-{$month}-{$year}");
        $result = strtotime('-1 second', strtotime('+1 month', $result));

        return date('t/m/Y', $result);
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( $this->user->emp_no , $this->PAGE_ACT );

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData(){
        $result = array(
            array('name' => ':EMP_NO_IN', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
            array('name' => ':FROM_DATE_IN', 'value' => $this->return_first_month($this->month), 'type' => '', 'length' => -1),
            array('name' => ':TO_DATE_IN', 'value' =>  $this->return_last_month($this->month), 'type' => '', 'length' => -1),
            array(),
            array(),
        );
        return $result;
    }
}