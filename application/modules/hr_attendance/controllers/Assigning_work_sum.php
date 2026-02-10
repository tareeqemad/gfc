<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 24/03/19
 * Time: 09:45 ص
 */

class assigning_work_sum extends MY_Controller{

    var $MODEL_NAME= 'assigning_work_model';
    var $PAGE_URL= 'hr_attendance/assigning_work_sum/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->emp_no= $this->input->post('emp_no');
        $this->branch_id= $this->input->post('branch_id');
        $this->date_from= $this->input->post('date_from');
        $this->date_to= $this->input->post('date_to');

        if( HaveAccess(base_url("hr_attendance/assigning_work_sum/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;
    }

    function index($emp_no= -1, $branch_id= -1, $date_from= -1, $date_to= -1 ){

        $data['title']=' تجميع تكاليف العمل - الساعات الاضافية';
        $data['content']='assigning_work_sum_index';

        $date_from=($date_from!=-1)?$date_from:date('01/m/Y');
        $date_to=($date_to!=-1)?$date_to:date('d/m/Y');

        $data['emp_no']= $emp_no;
        $data['branch_id']= $branch_id;
        $data['date_from']= $date_from;
        $data['date_to']= $date_to;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($emp_no= -1, $branch_id= -1, $date_from= -1, $date_to= -1 ){

        $emp_no= $this->check_vars($emp_no,'emp_no');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $date_from= $this->check_vars($date_from,'date_from');
        $date_to= $this->check_vars($date_to,'date_to');

        if(!$this->all_branches)
            $branch_id=  $this->user->branch;

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_sum($emp_no, $branch_id, $date_from , $date_to );

        $this->load->view('assigning_work_sum_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( $this->user->emp_no , 'hr_admin' );

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
