<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/10/18
 * Time: 09:39 ص
 */

// emp_no, branch_id, dt1, dt2

class emps_absence extends MY_Controller{

    var $MODEL_NAME= 'emps_absence_model';
    var $PAGE_URL= 'hr_attendance/emps_absence/get_page';
    var $PAGE_ACT;

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->page_act= $this->input->post('page_act');
        $this->emp_no= $this->input->post('emp_no');
        $this->branch_id= $this->input->post('branch_id');
        $this->dt1= $this->input->post('dt1');
        $this->dt2= $this->input->post('dt2');
        $this->is_note= $this->input->post('is_note');
        $this->emp_type= $this->input->post('emp_type');
        $this->reason_no= $this->input->post('reason_no');
        $this->reason_ser= $this->input->post('reason_ser');

        /*
        my - شاشة حضور الموظف
        manager - شاشة المدير المباشر او الاعلى للاعتماد
        move_admin - شاشة الشؤون الادارية حسب المقر
        hr_admin - شاشة للشؤون الادارية تظهر كل الموظفين
        */

        $this->PAGE_ACT= $this->uri->segment(5);

        if(isset($this->PAGE_ACT) and $this->PAGE_ACT!='' and ($this->uri->segment(3)== 'index' or $this->uri->segment(3)== 'get') ){

            if(!HaveAccess(base_url("hr_attendance/emps_absence/index/1/".$this->PAGE_ACT))){
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

    function index($page= 1, $page_act= -1, $emp_no= -1, $branch_id= -1, $dt1= -1, $dt2= -1, $is_note= -1, $emp_type= -1 ){

        if(isset($this->PAGE_ACT)){
            if($this->PAGE_ACT=='my'){
                $data['title']='ايام الغياب - الموظف';
                $dt1=($dt1!=-1)?$dt1:date('1/m/Y');
            }elseif($this->PAGE_ACT=='manager'){
                $data['title']='ايام الغياب - المدير';
            }elseif($this->PAGE_ACT=='move_admin'){
                $data['title']='ايام الغياب - الشؤون الادارية - مسؤول الحركة في المقر';
            }elseif($this->PAGE_ACT=='hr_admin'){
                $data['title']='ايام الغياب - الشؤون الادارية';
            }else{
                $data['title']=' ايام الغياب؟؟';
            }
        }

        $data['content']='emps_absence_index';

        $data['page']=$page;
        $data['page_act']= $page_act;
        $data['emp_no']= $emp_no;
        $data['branch_id']= $branch_id;
        $data['dt1']= $dt1;
        $data['dt2']= $dt2;
        $data['is_note']= $is_note;
        $data['emp_type']= $emp_type;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);

    }

    function get_page($page= 1, $page_act= -1, $emp_no= -1, $branch_id= -1, $dt1= -1, $dt2= -1, $is_note= -1, $emp_type= -1){
        $this->load->library('pagination');

        $structure_tb= ' GFC_HR.HR_EMPS_STRUCTURE_TB ';

        $page_act= $this->check_vars($page_act,'page_act');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $dt1= $this->check_vars($dt1,'dt1');
        $dt2= $this->check_vars($dt2,'dt2');
        $is_note= $this->check_vars($is_note,'is_note');
        $emp_type= $this->check_vars($emp_type,'emp_type');

        $dt1= $dt1?$dt1:date('d/m/Y');
        $dt2= $dt2?$dt2:date('d/m/Y');

        $where_sql= " ";

        $this->PAGE_ACT =$page_act;

        if(isset($this->PAGE_ACT)){
            if($this->PAGE_ACT=='my'){
                $where_sql.= " and e.no= {$this->user->emp_no} ";
            }elseif($this->PAGE_ACT=='manager'){
                $where_sql.= " and e.no in
                ( SELECT A.EMPLOYEE_NO
                FROM $structure_tb A
                WHERE A.EMPLOYEE_NO     != {$this->user->emp_no}
                START WITH A.EMPLOYEE_NO = {$this->user->emp_no}
                CONNECT BY PRIOR  A.EMPLOYEE_NO =A.MANAGER_NO ) ";
            }elseif($this->PAGE_ACT=='move_admin'){
                $where_sql.= " and EMP_PKG.GET_EMP_BRANCH(e.no)= {$this->user->branch} ";
            }elseif($this->PAGE_ACT=='hr_admin'){
                $where_sql.= " and 1= 1 ";
            }else{
                $where_sql.= " and 1= 2 ";
            }
        }else{
            $where_sql.= " and 1= 2 ";
        }

        $where_sql.= ($emp_type== 2)? " and e.no >= 70000 " : " and e.no < 70000 ";

        $where_sql.= ($emp_no!= null)? " and e.no= '{$emp_no}' " : '';
        $where_sql.= ($branch_id!= null)? " and EMP_PKG.GET_EMP_BRANCH(e.no)= '{$branch_id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        //$count_rs = $this->get_table_count(' emps_absence '.$where_sql);
        $count_rs = $this->{$this->MODEL_NAME}->get_list($where_sql, 0, 0, $dt1, $dt2, 'COUNT', $is_note);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row, $dt1, $dt2, 'DATA', $is_note);

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->model('settings/constant_details_model');
        $data['reason_con'] = $this->constant_details_model->get_list(369);

        if( $this->PAGE_ACT=='move_admin' and HaveAccess(base_url("hr_attendance/emps_absence/save_reason")) ){
            $data['CAN_SAVE_REASON']= 1;
        }else{
            $data['CAN_SAVE_REASON']= 0;
        }

        $this->load->view('emps_absence_page',$data);

    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    // ادخال وتعديل سبب الغياب
    function save_reason(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' ){

            if($this->reason_ser=='' and $this->emp_no!='' and $this->dt1!='' and $this->reason_no!=''){
                // insert
                $res= $this->{$this->MODEL_NAME}->reason_insert($this->emp_no, $this->dt1, $this->reason_no);
                if(intval($res) <= 0){
                    $this->print_error('لم يتم الادخال'.'<br>'.$res);
                }else{
                    echo $res;
                }

            }elseif($this->reason_ser!='' and $this->emp_no!=''){
                // update
                $res= $this->{$this->MODEL_NAME}->reason_update($this->reason_ser, $this->emp_no, $this->reason_no);
                if(intval($res) <= 0){
                    $this->print_error('لم يتم التعديل'.'<br>'.$res);
                }else{
                    echo 1;
                }

            }else{
                $this->print_error('خطأ');
            }

        } // IF POST
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        //$data['function_key_cons'] = $this->constant_details_model->get_list(225);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( $this->user->emp_no , $this->PAGE_ACT );

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}
