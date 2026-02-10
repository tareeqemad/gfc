<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 16/03/22
 * Time: 09:45 ص
 */
class Finger_attendance extends MY_Controller
{
    var $MODEL_NAME= 'finger_attendance_model';
    var $PAGE_URL= 'hr_attendance/Finger_attendance/get_page';
    var $PAGE_ACT;

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->emp_no = $this->input->post('emp_no');
        $this->status = $this->input->post('status');
        $this->tdate = $this->input->post('tdate');
        $this->ttime = $this->input->post('ttime');
        $this->reason = $this->input->post('reason');

    }

    function index_company_employees(){
        $data['title'] = 'استعلام | بصمات موظفين الشركة';
        $data['content'] = 'finger_attendance_index';
        $data['action'] = 'company';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }


    function index_project_technician(){
        $data['title'] = 'استعلام | فني مشروع الفاقد';
        $data['content'] = 'finger_attendance_index';
        $data['action'] = 'technician';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function get_page($page = 1){
        $this->load->library('pagination');

        $where_sql = " WHERE  M.ENTRY_USER = {$this->user->id} AND M.ENTRY_DATE >= TRUNC(SYSDATE - 5) " ;
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no!= null ? " AND  M.EMPLOYEENO ={$this->p_emp_no}  " : "";
        $where_sql .= isset($this->p_status) && $this->p_status!= null ? " AND  M.STATUS ={$this->p_status}  " : "";
        $where_sql .= isset($this->p_tdate) && $this->p_tdate!= null ? " AND  M.TDATE = '{$this->p_tdate}'  " : "";
        $where_sql .= isset($this->p_ttime) && $this->p_ttime!= null ? " AND  TO_CHAR(M.TTIME, 'HH24:MI') = '{$this->p_ttime}'  " : "";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.ATTENDANCE M '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = 200;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('finger_attendance_page', $data);
    }

    function create($page_act){
       if ($page_act == 'company') {
           $data['title'] = 'اضافة بصمة | موظفين الشركة ';
       } else if ($page_act = 'technician') {
           $data['title'] = 'اضافة بصمة | فني مشروع الفاقد ';
       }
       $data['action'] = $page_act;
       $data['isCreate'] = true;
       $data['content'] = 'finger_attendance_show';
       $this->_lookup($data);
       $this->load->view('template/template', $data);
    }

    function insertFinger(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $result = $this->{$this->MODEL_NAME}->create($this->_postedData());
            if(intval($result) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$result);
            }else{
                echo 1;
            }
        } //end if post
    }

    function deleteFinger(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $ser = $this->input->post('ser');
            $employeeNo = $this->input->post('employeeNo');
            $res = $this->{$this->MODEL_NAME}->delete($ser,$employeeNo);
            if(intval($res) <= 0){
                $this->print_error('error'.'<br>'.$res);
            }
            echo 1;
        }
    }

    function _post_validation($isEdit = false){ //////////////////////////////////
        if( $this->emp_no==''){
            $this->print_error('يجب ادخال رقم الموظف');
        }else if ($this->status==''){
            $this->print_error('يجب ادخال نوع البصمة');
        }else if ($this->tdate==''){
            $this->print_error('يجب ادخال تاريخ البصمة');
        }else if ($this->ttime==''){
            $this->print_error('يجب ادخال وقت البصمة');
        }else if( $this->ttime!='' and !$this->check_time($this->ttime) ){
            $this->print_error('ادخل ساعة البصمة بشكل صحيح');
        }else if($this->reason == '' ){
            $this->print_error('يجب ادخال ملاحظة');
        }else if(strlen($this->reason) <= 10 ){
            $this->print_error('يجب أن يكون النص المدخل بالملاحظة أكثر من 10 حروف');
        }
    }



    function check_time($time){
        return preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time);
    }

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->load->model('hr_attendance_model');

        if ($this->user->branch == 1){
            $data['emp_no_cons'] = $this->hr_attendance_model->get_child( null , 'hr_admin' );
        }else{
            $data['emp_no_cons'] = $this->hr_attendance_model->get_child( null , 'move_admin' );
        }

    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'EMPLOYEENO','value'=>$this->emp_no,'type'=>'','length'=>-1),
            array('name'=>'STATUS','value'=>$this->status ,'type'=>'','length'=>-1),
            array('name'=>'TDATE','value'=>$this->tdate ,'type'=>'','length'=>-1),
            array('name'=>'TTIME','value'=>$this->ttime ,'type'=>'','length'=>-1),
            array('name'=>'REASON','value'=>$this->reason ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }


    function autoFinger(){
        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            show_404();
            return;
        }

        $emp_no = isset($this->user->emp_no) ? $this->user->emp_no : null;
        $emp_no = trim((string)$emp_no);

        if($emp_no === ''){
            $this->print_error('يجب ادخال رقم الموظف');
            return;
        }

        $status = trim((string)$this->input->post('status'));
        if($status === ''){
            $this->print_error('يجب تحديد نوع العملية (1 حضور / 4 انصراف)');
            return;
        }

        $status = (int)$status;
        if(!in_array($status, [1,4], true)){
            $this->print_error('نوع العملية غير صحيح');
            return;
        }

        $result = $this->{$this->MODEL_NAME}->auto_attendance($emp_no, $status);

        if($result === '1'){
            echo 1;
        }else{
            $this->print_error($result);
        }
    }

    function getLastStatus(){
        if($_SERVER['REQUEST_METHOD'] != 'POST'){ show_404(); return; }

        $emp_no = trim((string)$this->user->emp_no);
        if($emp_no === ''){
            echo json_encode(['ok'=>false,'msg'=>'رقم الموظف غير موجود']);
            return;
        }

        $status = $this->{$this->MODEL_NAME}->get_last_status($emp_no);
        echo json_encode(['ok'=>true,'status'=>$status]);
    }

    public function serverTime(){
        $days = ['Sunday'=>'الأحد','Monday'=>'الإثنين','Tuesday'=>'الثلاثاء','Wednesday'=>'الأربعاء','Thursday'=>'الخميس','Friday'=>'الجمعة','Saturday'=>'السبت'];
        $dayName = $days[date('l')];
        $time = $dayName . ' - ' . date('Y-m-d H:i');
        echo json_encode(['ok'=>true,'time'=>$time]);
    }

    function getLastInOut(){
        if($_SERVER['REQUEST_METHOD'] != 'POST'){ show_404(); return; }

        $emp_no = trim((string)($this->user->emp_no ?? ''));
        if($emp_no === ''){
            echo json_encode(['ok'=>false,'msg'=>'رقم الموظف غير موجود']);
            return;
        }

        $data = $this->{$this->MODEL_NAME}->get_last_in_out_24h($emp_no);

        echo json_encode([
            'ok' => true,
            'last_in' => $data['last_in'],
            'last_out' => $data['last_out']
        ]);
    }

}