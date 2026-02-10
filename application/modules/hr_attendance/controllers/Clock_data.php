<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/08/18
 * Time: 09:22 ص
 */

// ser, emp_no, year, entry_date, branch_id, function_key

class clock_data extends MY_Controller{

    var $MODEL_NAME= 'clock_data_model';
    var $PAGE_URL= 'hr_attendance/clock_data/get_page';
    var $PAGE_ACT;

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->page_act= $this->input->post('page_act');
        $this->ser= $this->input->post('ser');
        $this->emp_no= $this->input->post('emp_no');
        $this->year= $this->input->post('year');
        $this->entry_date= $this->input->post('entry_date');
        $this->entry_date_2= $this->input->post('entry_date_2');
        $this->function_key= $this->input->post('function_key');
        $this->branch_id= $this->input->post('branch_id');
        $this->emp_type= $this->input->post('emp_type');
        $this->delay= $this->input->post('delay');
        $this->no_leave= $this->input->post('no_leave');
        $this->no_entry= $this->input->post('no_entry');

        /*
        my - شاشة حضور الموظف
        manager - شاشة المدير المباشر او الاعلى للاعتماد
        move_admin - شاشة الشؤون الادارية حسب المقر
        hr_admin - شاشة للشؤون الادارية تظهر كل الموظفين
        */

        $this->PAGE_ACT= $this->uri->segment(5);

        if(isset($this->PAGE_ACT) and $this->PAGE_ACT!='' and ($this->uri->segment(3)== 'index' or $this->uri->segment(3)== 'get') ){

            if(!HaveAccess(base_url("hr_attendance/clock_data/index/1/".$this->PAGE_ACT))){
                die('Error: No Permission '.$this->PAGE_ACT);
            }

            if($this->PAGE_ACT=='my'){

            }elseif($this->PAGE_ACT=='manager'){
                die('Error01');
            }elseif($this->PAGE_ACT=='move_admin'){

            }elseif($this->PAGE_ACT=='hr_admin'){

            }else{
                die('PAGE_ACT');
            }
        }elseif($this->uri->segment(3)== 'index'){
            die('index');
        }
    }

    function index($page= 1, $page_act= -1, $delay= -1, $no_leave= -1, $no_entry= -1, $ser= -1, $emp_no= -1, $year= -1, $entry_date= -1, $entry_date_2= -1, $branch_id= -1, $function_key= -1, $emp_type= -1 ){

        $yesterday = new DateTime('-1 day');
        $yesterday= $yesterday->format('d/m/Y');

        if(isset($this->PAGE_ACT)){
            if($this->PAGE_ACT=='my'){
                $data['title']='حضور وانصراف - الموظف';
                $entry_date=($entry_date!=-1)?$entry_date:date('1/m/Y');
            }elseif($this->PAGE_ACT=='manager'){
                $data['title']='حضور وانصراف - المدير';
            }elseif($this->PAGE_ACT=='move_admin'){
                $data['title']='حضور وانصراف - الشؤون الادارية - مسؤول الحركة في المقر';
                if($delay==1){
                    $data['title']='التأخير الصباحي - الشؤون الادارية - مسؤول الحركة في المقر';
                }elseif($no_leave==1){
                    $entry_date=($entry_date!=-1)?$entry_date:$yesterday;
                    $entry_date_2=($entry_date_2!=-1)?$entry_date_2:$entry_date;
                    $data['title']='بدون بصمة انصراف - الشؤون الادارية - مسؤول الحركة في المقر';
                }elseif($no_entry==1){
                    $entry_date=($entry_date!=-1)?$entry_date:$yesterday;
                    $entry_date_2=($entry_date_2!=-1)?$entry_date_2:$entry_date;
                    $data['title']='بدون بصمة حضور - الشؤون الادارية - مسؤول الحركة في المقر';
                }
                $entry_date=($entry_date!=-1)?$entry_date:date('d/m/Y');

            }elseif($this->PAGE_ACT=='hr_admin'){
                $data['title']='حضور وانصراف - الشؤون الادارية';
                if($delay==1){
                    $data['title']='التأخير الصباحي - الشؤون الادارية';
                }elseif($no_leave==1){
                    $entry_date=($entry_date!=-1)?$entry_date:$yesterday;
                    $entry_date_2=($entry_date_2!=-1)?$entry_date_2:$entry_date;
                    $data['title']='بدون بصمة انصراف - الشؤون الادارية';
                }elseif($no_entry==1){
                    $entry_date=($entry_date!=-1)?$entry_date:$yesterday;
                    $entry_date_2=($entry_date_2!=-1)?$entry_date_2:$entry_date;
                    $data['title']='بدون بصمة حضور - الشؤون الادارية';
                }
                $entry_date=($entry_date!=-1)?$entry_date:date('d/m/Y');

            }else{
                $data['title']=' حضور وانصراف؟؟';
            }
        }

        $data['content']='clock_data_index';

        //$data['entry_user_all'] = $this->get_entry_users('CLOCK_DATA_VW');

        $data['page']=$page;
        $data['page_act']= $page_act;
        $data['ser']= $ser;
        $data['emp_no']= $emp_no;
        $data['year']= $year;
        $data['entry_date']= $entry_date;
        $data['entry_date_2']= $entry_date_2;
        $data['branch_id']= $branch_id;
        $data['function_key']= $function_key;
        $data['emp_type']= $emp_type;
        $data['delay']= $delay;
        $data['no_leave']= $no_leave;
        $data['no_entry']= $no_entry;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);

    }

    function get_page($page= 1, $page_act= -1, $delay= -1, $no_leave= -1, $no_entry= -1, $ser= -1, $emp_no= -1, $year= -1, $entry_date= -1, $entry_date_2= -1, $branch_id= -1, $function_key= -1, $emp_type= -1 ){
        $this->load->library('pagination');

        $structure_tb= ' GFC_HR.HR_EMPS_STRUCTURE_TB ';

        $page_act= $this->check_vars($page_act,'page_act');
        $ser= $this->check_vars($ser,'ser');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $year= $this->check_vars($year,'year');
        $entry_date= $this->check_vars($entry_date,'entry_date');
        $entry_date_2= $this->check_vars($entry_date_2,'entry_date_2');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $function_key= $this->check_vars($function_key,'function_key');
        $emp_type= $this->check_vars($emp_type,'emp_type');
        $delay= $this->check_vars($delay,'delay');
        $no_leave= $this->check_vars($no_leave,'no_leave');
        $no_entry= $this->check_vars($no_entry,'no_entry');

        $where_sql= " where 1=1 ";
        $where_sql2= " ";

        $this->PAGE_ACT =$page_act;

        if(isset($this->PAGE_ACT)){
            if($this->PAGE_ACT=='my'){
                $where_sql.= " and emp_no= {$this->user->emp_no} ";
            }elseif($this->PAGE_ACT=='manager'){
                $where_sql.= " and emp_no in
                ( SELECT A.EMPLOYEE_NO
                FROM $structure_tb A
                WHERE A.EMPLOYEE_NO     != {$this->user->emp_no}
                START WITH A.EMPLOYEE_NO = {$this->user->emp_no}
                CONNECT BY PRIOR  A.EMPLOYEE_NO =A.MANAGER_NO )
                and adopt >= 10 ";
            }elseif($this->PAGE_ACT=='move_admin'){
                // تم دمج مقر الصيانة مع مقر غزة 202210
                $where_sql.= " and DECODE(branch_id,8,2,branch_id)= {$this->user->branch} and adopt >= 10 ";
            }elseif($this->PAGE_ACT=='hr_admin'){
                $where_sql.= " and 1= 1 ";
            }else{
                $where_sql.= " and 1= 2 ";
            }
        }else{
            $where_sql.= " and 1= 2 ";
        }

        $where_sql.= ($emp_type== 2)? " and emp_no >= 70000 " : " and emp_no < 70000 ";

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= in_special_emps($this->user->emp_no, 'emp_no');
        $where_sql.= ($year!= null)? " and year= '{$year}' " : '';
        $where_sql.= ($entry_date!= null or $entry_date_2!= null)? " and TRUNC(entry_date) between nvl('{$entry_date}','01/01/1000') and nvl('{$entry_date_2}','01/01/3000') " : '';
        $where_sql.= ($branch_id!= null)? " and branch_id= '{$branch_id}' " : '';
        $where_sql.= ($function_key!= null)? " and function_key= '{$function_key}' " : '';

        // عرض المتأخرين عن الحضور
        $where_sql.= ($delay==1)? " and function_key= 1  and  hr_attendance_pkg.OFF_WORK_HOURS_DELAY_START( hr_attendance_pkg.OFF_WORK_HOURS_DETECT_ROW(1, entry_date) , entry_date ) = 1 " : '';
        // عرض اول سجل حضور فقط بدون تكرار السجلات الاخرى
        $where_sql2.= ($delay==1)? " and CNT_DUPLICATE= 1 " : '';

        // عرض من له بصمة حضور بدون بصمة انصراف
        $where_sql.= ($no_leave==1)? " and function_key= 1 and NOT EXISTS ( SELECT 1 from CLOCK_DATA_VW c where  m.emp_no= c.emp_no and TRUNC(m.entry_date) = TRUNC(c.entry_date) and c.function_key = 4 ) " : '';
        // استثناء من له اذن خروج حتى نهاية الدوام
        //$where_sql.= ($no_leave==1)? " and ( select count(0) from gfc_hr.exit_permission_tb e  where e.adopt >= 10 and TRUNC(e.p_ret_time)= e.p_date and ( TO_CHAR(e.p_ret_time,'HH24MI') = 1400 or ( TO_CHAR(e.p_ret_time,'HH24MI') = 1300 and qf_pkg.get_day_name_en(e.p_date)= 'THURSDAY' ) )  and e.emp_no= m.emp_no and e.p_date= TRUNC(m.entry_date) ) != 1 " : '';
        $where_sql.= ($no_leave==1)? " and ( select count(0) from gfc_hr.exit_permission_tb e  where e.adopt >= 10 and TRUNC(e.p_ret_time)= e.p_date and TO_CHAR(e.p_ret_time,'HH24MI') =  REPLACE( HR_ATTENDANCE_PKG.OFF_WORK_HOURS_END_TIME( HR_ATTENDANCE_PKG.OFF_WORK_HOURS_DETECT_ROW(1, e.p_date ) )  , ':', '' )   and e.emp_no= m.emp_no and e.p_date= TRUNC(m.entry_date) ) != 1 " : '';
        // عرض اول سجل حضور فقط بدون تكرار السجلات الاخرى
        $where_sql2.= ($no_leave==1)? " and CNT_DUPLICATE= 1 " : '';

        // عرض من له بصمة انصراف بدون بصمة حضور
        $where_sql.= ($no_entry==1)? " and function_key= 4 and NOT EXISTS ( SELECT 1 from CLOCK_DATA_VW c where  m.emp_no= c.emp_no and TRUNC(m.entry_date) = TRUNC(c.entry_date) and c.function_key = 1 ) " : '';
        // عرض اول سجل حضور فقط بدون تكرار السجلات الاخرى
        $where_sql2.= ($no_entry==1)? " and CNT_DUPLICATE= 1 " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' CLOCK_DATA_VW m '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row, $where_sql2 );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $data['no_entry']= $no_entry;
        $data['no_leave']= $no_leave;

        $this->load->model('settings/constant_details_model');
        $data['function_key_edit'] = $this->constant_details_model->get_list(225);

        if( $this->PAGE_ACT=='move_admin' and HaveAccess(base_url("hr_attendance/clock_data/edit_status")) ){
            $data['CAN_EDIT_STATUS']= 1;
        }else{
            $data['CAN_EDIT_STATUS']= 0;
        }

        if($this->PAGE_ACT=='hr_admin' and $delay==1 and $emp_no!= null){
            $data['show_delay_month']= 1;
        }else{
            $data['show_delay_month']= 0;
        }

        $this->load->view('clock_data_page',$data);

    }

    // ترحيل سجل بدون بصمة حضور لموظف - طارق
    function trans_no_entry(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $res= $this->{$this->MODEL_NAME}->trans_no_entry_leave($this->ser, $this->emp_no, $this->entry_date, 4);
            if(intval($res) == 1 ){
                echo 1;
            }else {
                $this->print_error('Error_'.$res);
            }
        }
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    // تعديل نوع حركة الساعة
    function edit_status(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'  and  $this->ser!='' and $this->emp_no!='' and $this->function_key!='' ){
            $res= $this->{$this->MODEL_NAME}->edit_status($this->ser, $this->emp_no, $this->function_key);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['function_key_cons'] = $this->constant_details_model->get_list(225);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( $this->user->emp_no , $this->PAGE_ACT );

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
