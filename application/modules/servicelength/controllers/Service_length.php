<?php

class Service_length extends MY_Controller
{
    var $MODEL_NAME = 'Service_model';
    var $PAGE_URL = 'servicelength/service_length/get_page';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->load->model('Root/New_rmodel');

        $this->rmodel->package = 'HR_VACANCY_PKG';
        // vars
        $this->id_vacancy = $this->input->post('id_vacancy');
        $this->emp_no = $this->input->post('emp_no');
        $this->emp_name = $this->input->post('emp_name');
        $this->emp_id = $this->input->post('emp_id');
        $this->emp_job = $this->input->post('emp_job');
        $this->branch = $this->input->post('branch');
        $this->v_note = $this->input->post('v_note');
        $this->adopt_note = $this->input->post('adopt_note');
        $this->emp_end_date = $this->input->post('emp_end_date');
        $this->emp_end_reason = $this->input->post('emp_end_reason');
        $this->load->model('settings/constant_details_model');

    }

    /**************  ////استعلام عن بيانات موظف*************/
    function index()
    {
        $data['content'] = 'service_length_index';
        $data['title'] = 'استمارة تقاعد الشيخوخة';
          $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }


    function CreateFi($emp_no=0)
{
    ////استعلام عن بيانات طلب شهادة خلو طرف
     $result = $this->rmodel->get('RETIREMENT_EMP_CALC_TB_GET', $emp_no);
    $salary_36_month = $this->{$this->MODEL_NAME}->getTwoColum('HR_VACANCY_PKG','EMP_SALARY_NEXT_TB_GET', $emp_no,1);
    $salary_5_years = $this->{$this->MODEL_NAME}->getTwoColum('HR_VACANCY_PKG','EMP_SALARY_NEXT_TB_GET', $emp_no,2);

     $rs = $this->{$this->MODEL_NAME}->get($emp_no);
    if (!(count($rs) == 1))
        die('get');
    $data['get_data'] = $rs;
    $data['result'] = $result;
    $data['month_36'] = $salary_36_month;
    $data['years5'] = $salary_5_years;
    $data['title'] = ' اضافة شهادة خلو طرف ';
    $data['content'] = 'service_length_show_fi';
    $this->_look_ups($data);
    $this->load->view('template/template1', $data);
}
    function get($emp_no = 0)
    {

        $result = $this->rmodel->get('VACATION_REQUEST_TB_GET', $emp_no);
        $salary_36_month = $this->{$this->MODEL_NAME}->getTwoColum('HR_VACANCY_PKG','EMP_SALARY_NEXT_TB_GET', $emp_no,1);
        $salary_5_years = $this->{$this->MODEL_NAME}->getTwoColum('HR_VACANCY_PKG','EMP_SALARY_NEXT_TB_GET', $emp_no,2);

        $result_fi = $this->rmodel->get('RETIREMENT_EMP_CALC_TB_GET', $emp_no);


        $rs = $this->{$this->MODEL_NAME}->get($emp_no);
            if (!(count($rs) == 1))
                die('get');
            $data['get_data'] = $rs;
            $data['VACATION'] = $result;
        $data['result_fi'] = $result_fi;

        $data['month_36'] = $salary_36_month;
        $data['years5'] = $salary_5_years;
            $data['title'] = ' اضافة شهادة خلو طرف ';
            $data['content'] = 'service_length_show';
            $this->_look_ups($data);
            $this->load->view('template/template1', $data);
     }
    /************************جلب بيانات الموظف************************************/
    function get_page()
    {
        $emp_no = $this->input->post('emp_no');
        $data['page_rows'] = $this->rmodel->get('RETIREMENT_EMP_V_GET', $emp_no);
        $this->load->view('service_length_page', $data);
    }
    function get_list($end_date=0)
    {

         $result = $this->rmodel->get('EMP_INFO_LIST', $end_date);
           $this->return_json($result);

        //     $this->load->view('service_length_index', $data);
    }

    function _look_ups(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
         $this->load->model('settings/constant_details_model');
         $data['is_active_cons'] = $this->constant_details_model->get_list(335);
        $this->load->model('hr_attendance/hr_attendance_model');
         $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['end_service_reason'] = $this->constant_details_model->get_list(518);
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js'); }

    function _postedData_1($typ = null)
    {
        $result = array(
            array('name' => 'ID_VACANCY', 'value' => $this->id_vacancy, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NAME', 'value' => $this->emp_name, 'type' => '', 'length' => -1),
            array('name' => 'EMP_ID', 'value' => $this->emp_id, 'type' => '', 'length' => -1),
            array('name' => 'EMP_JOB', 'value' => $this->emp_job, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->branch, 'type' => '', 'length' => -1),
            array('name' => 'V_NOTE', 'value' => $this->v_note, 'type' => '', 'length' => -1),
            array('name' => 'EMP_END_DATE', 'value' => $this->emp_end_date, 'type' => '', 'length' => -1),
            array('name' => 'EMP_END_REASON', 'value' => $this->emp_end_reason, 'type' => '', 'length' => -1),
        );
        if ($typ == 'create')
            unset($result[0]);
        return $result;
    }

    function _postedData_f($typ = null)
    {
        $result = array(
            array('name' => 'ID_VACANCY', 'value' => $this->id_vacancy, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NAME', 'value' => $this->emp_name, 'type' => '', 'length' => -1),
            array('name' => 'EMP_ID', 'value' => $this->emp_id, 'type' => '', 'length' => -1),
            array('name' => 'EMP_JOB', 'value' => $this->emp_job, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->branch, 'type' => '', 'length' => -1),
            array('name' => 'V_NOTE', 'value' => $this->v_note, 'type' => '', 'length' => -1),
            //array('name' => 'EMP_END_DATE', 'value' => $this->emp_end_date, 'type' => '', 'length' => -1),
            //array('name' => 'EMP_END_REASON', 'value' => $this->emp_end_reason, 'type' => '', 'length' => -1),
        );
        if ($typ == 'create')
            unset($result[0]);
        return $result;
    }
    function calculate_($from_date,$to_date ){
        $from_date= str_replace("-", "/", $from_date);
        $to_date= str_replace("-", "/", $to_date);
        $params = array(
            array('name'=>':FROM_DATE','value'=>$from_date ,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>$to_date ,'type'=>'','length'=>-1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)

        );

        $rs =  $this->New_rmodel->general_get('HR_VACANCY_PKG', 'DATE_DIFF', $params);
        if (intval($rs) > 0) {
            $this->return_json(array('status' => 1));
        } else {
            $this->return_json(array('status' => 0, 'error' => $rs));
        }


        $this->return_json($rs);

    }

    function calculate_1($from_date,$to_date ){
        $from_date= str_replace("-", "/", $from_date);
        $to_date= str_replace("-", "/", $to_date);
        $params = array(
            array('name'=>':FROM_DATE','value'=>$from_date ,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>$to_date ,'type'=>'','length'=>-1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)

        );

        $rs =  $this->New_rmodel->general_get('HR_VACANCY_PKG', 'DATE_DIFF_1', $params);
        if (intval($rs) > 0) {
            $this->return_json(array('status' => 1));
        } else {
            $this->return_json(array('status' => 0, 'error' => $rs));
        }


        $this->return_json($rs);

    }
function Create_Form(){

     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $result = array(
            array('name' => 'NO_IN', 'value' => $this->p_emp_no, 'type' => '', 'length' => -1),
             array('name' => 'TO_DATE_IN', 'value' => $this->p_txt_end_date, 'type' => '', 'length' => -1),
            array('name' => 'WORK_FEX_RESON_IN', 'value' => $this->p_end_service_reason, 'type' => '', 'length' => -1),
            array('name' => 'FEX_DATE_PERIOD_IN', 'value' => $this->p_FEX_DATE_DAY.'/'.$this->p_FEX_DATE_MONTH.'/'.$this->p_FEX_DATE_YEAR, 'type' => '', 'length' => -1),
            array('name' => 'SERVICE_TOTAL_PERIOD_IN', 'value' => $this->p_SERVICE_TOTAL_PERIOD_DAY.'/'.$this->p_SERVICE_TOTAL_PERIOD_MONTH.'/'.$this->p_SERVICE_TOTAL_PERIOD_YEAR, 'type' => '', 'length' => -1),
            array('name' => 'WORK_PERIOD_IN', 'value' => $this->p_WORK_PERIOD_DAY.'/'.$this->p_WORK_PERIOD_MONTH.'/'.$this->p_WORK_PERIOD_YEAR, 'type' => '', 'length' => -1),
            array('name' => 'EXCLUDED_TERM_IN', 'value' => $this->p_EXCLUDED_TERM_DAY.'/'.$this->p_EXCLUDED_TERM_MONTH.'/'.$this->p_EXCLUDED_TERM_YEAR, 'type' => '', 'length' => -1),
            array('name' => 'NOTES_IN', 'value' => $this->p_notes, 'type' => '', 'length' => -1)
         );

if($this->p_ser>0){

     $ret = $this->rmodel->insert('SERVICE_LIMITATION_TB_UPDATE', $result);


}else{

    $ret = $this->rmodel->insert('SERVICE_LIMITATION_TB_INSERT', $result);
    $result = array(
        array('name' => 'NO_IN', 'value' => $this->p_emp_no, 'type' => '', 'length' => -1),
        array('name' => 'from_date_in_IN', 'value' => $this->p_hire_date, 'type' => '', 'length' => -1),
        array('name' => 'TO_DATE_IN', 'value' => $this->p_txt_end_date, 'type' => '', 'length' => -1)
    );

   // $ret = $this->rmodel->insert('retirement_emp_calc_tb_calc', $result);

}


         if (intval($ret) > 0) {
             $this->return_json(array('status' => 1));
         } else {
             $this->return_json(array('status' => 0, 'error' => $ret));
         }

    }

echo $ret;




}
function update_data(){

if(isset($this->p_MONTHS_36)){

/*
    $data_arr = array(
        // array('name' => 'SER_IN', 'value' => $this->p_SER[$x], 'type' => '', 'length' => -1),
        array('name' => 'EMP_NO_IN', 'value' => $this->p_EMP_NO, 'type' => '', 'length' => -1),
        array('name' => 'FOR_MONTH_IN', 'value' => $this->p_FOR_MONTH, 'type' => '', 'length' => -1),
        array('name' => 'BASIC_SAL_IN', 'value' => $this->p_BASIC_SAL, 'type' => '', 'length' => -1),
        array('name' => 'PROFESSION_BONUS', 'value' => $this->p_PROFESSION_BONUS, 'type' => '', 'length' => -1),
        array('name' => 'COST_LIVING', 'value' => $this->p_COST_LIVING, 'type' => '', 'length' => -1),
        array('name' => 'PROMOTION_BONUS', 'value' => $this->p_PROMOTION_BONUS, 'type' => '', 'length' => -1),
    );

    $res = $this->rmodel->update('EMP_SALARY_NEXT_TB_UPDATE_36', $data_arr);*/


    $data_arr = array(
        // array('name' => 'SER_IN', 'value' => $this->p_SER[$x], 'type' => '', 'length' => -1),
        array('name' => 'EMP_NO_IN', 'value' => $this->p_EMP_NO, 'type' => '', 'length' => -1),
        array('name' => 'FOR_MONTH_IN', 'value' => $this->p_FOR_MONTH, 'type' => '', 'length' => -1),
        array('name' => 'NEW_DEGREE_IN', 'value' => $this->p_NEW_DEGREE, 'type' => '', 'length' => -1),
        array('name' => 'PER_ALLOW_IN', 'value' => $this->p_PER_ALLOW_IN, 'type' => '', 'length' => -1),
    );
    $res = $this->rmodel->update('EMP_SALARY_NEXT_UPDATE_36_NEW', $data_arr);
    if (intval($res) > 0) {
        $this->return_json(array('status' => 1));
    } else {
        $this->return_json(array('status' => 0, 'error' => $res));


    }




}
else{

    $data_arr = array(
        // array('name' => 'SER_IN', 'value' => $this->p_SER[$x], 'type' => '', 'length' => -1),
        array('name' => 'EMP_NO_IN', 'value' => $this->p_EMP_NO, 'type' => '', 'length' => -1),
        array('name' => 'FOR_MONTH_IN', 'value' => $this->p_FOR_MONTH, 'type' => '', 'length' => -1),
        array('name' => 'NEW_DEGREE_IN', 'value' => $this->p_NEW_DEGREE, 'type' => '', 'length' => -1),
        array('name' => 'PER_ALLOW_IN', 'value' => $this->p_PER_ALLOW_IN, 'type' => '', 'length' => -1),
    );
       $res = $this->rmodel->update('EMP_SALARY_NEXT_TB_UPDATE', $data_arr);
     if (intval($res) > 0) {
        $this->return_json(array('status' => 1));
    } else {
        $this->return_json(array('status' => 0, 'error' => $res));


    }




}  /*
      if(isset($this->p_checkbox[$x])){






          $data_arr = array(
             // array('name' => 'SER_IN', 'value' => $this->p_SER[$x], 'type' => '', 'length' => -1),
             array('name' => 'EMP_NO_IN', 'value' => $this->p_EMP_NO_, 'type' => '', 'length' => -1),
             array('name' => 'FOR_MONTH_IN', 'value' => $this->p_checkbox[$x], 'type' => '', 'length' => -1),
             array('name' => 'NEW_DEGREE_IN', 'value' => $this->p_NEW_DEGREE[$x], 'type' => '', 'length' => -1),
             array('name' => 'PER_ALLOW_IN', 'value' => $this->p_PER_ALLOW_IN[$x], 'type' => '', 'length' => -1),
         );
         $res = $this->rmodel->update('EMP_SALARY_NEXT_TB_UPDATE', $data_arr);
      }

}
    if (intval($res) > 0) {
        $this->return_json(array('status' => 1));
    } else {
        $this->return_json(array('status' => 0, 'error' => $res));
    */

    }
function Adopt(){

if($_POST){

    $data_arr = array(
        // array('name' => 'SER_IN', 'value' => $this->p_SER[$x], 'type' => '', 'length' => -1),
        array('name' => 'EMP_NO_IN', 'value' => $this->p_EMP_NO, 'type' => '', 'length' => -1),
        array('name' => 'ADOPT', 'value' => $this->p_ADOPT, 'type' => '', 'length' => -1)
         );
    $this->rmodel->package = 'HR_VACANCY_PKG';

    $res = $this->rmodel->update('RETIREMENT_EMP_CALC_TB_ADOPT', $data_arr);
    if (intval($res) > 0) {
        $this->return_json(array('status' => 1));
    } else {
        $this->return_json(array('status' => 0, 'error' => $res));


    }




}
else{


}
    }










}
