<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 01/02/22
 * Time: 10:00 ص
 */

class Ev_emp_order_deleted extends MY_Controller {

    var $MODEL_NAME = 'Ev_emp_order_deleted_model';

    function __construct() {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function index() {
        $data['content']='ev_emp_order_deleted_index';
        $data['title']='حذف تقييمات الموظفين';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function check_ev_order(){
        $emp_no = $this->input->post('emp_no');
        $res = $this->{$this->MODEL_NAME}->check_emp_order($emp_no);
        $cnt = intval($res);

        if($cnt <= 0){
            return $cnt;
        }else if ($cnt == 1 or $cnt == 2 ){
            echo $cnt;
        }else{
            $this->print_error(' خطأ في تحديد التقييمات '.$res);
        }
    }

    function delete() {
        $emp_no = $this->input->post('emp_no');
        $notes = $this->input->post('notes');

        $msg = $this->{$this->MODEL_NAME}->insert_delete($emp_no, $notes);

        if(intval($msg) <= 0){
            $this->print_error($msg);
        }else{
            echo 1;
        }
    }

    function _look_ups(&$data){

        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( '', 'hr_admin' );

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

    }

}