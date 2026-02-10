<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/03/18
 * Time: 12:11 م
 */

// ser, emp_no, p_date, p_exit_time, p_ret_time, notes_emp, notes_observer, adopt, permi_type, perm_year, entry_user, branch_id

class exit_permission extends MY_Controller{

    var $MODEL_NAME= 'exit_permission_model';
    var $PAGE_URL= 'hr_attendance/exit_permission/get_page';
    var $PAGE_ACT;

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->page_act= $this->input->post('page_act');
        $this->ser= $this->input->post('ser');
        $this->emp_no= $this->input->post('emp_no');
        $this->p_date= $this->input->post('p_date');
        $this->p_date_2= $this->input->post('p_date_2');
        $this->p_exit_time= $this->input->post('p_exit_time');
        $this->p_ret_time= $this->input->post('p_ret_time');
        $this->notes_emp= $this->input->post('notes_emp');
        $this->notes_observer= $this->input->post('notes_observer');
        $this->adopt= $this->input->post('adopt');
        $this->permi_type= $this->input->post('permi_type');
        $this->perm_year= $this->input->post('perm_year');
        $this->entry_user= $this->input->post('entry_user');
        $this->branch_id= $this->input->post('branch_id');
        $this->destination= $this->input->post('destination');
        $this->not_closed= $this->input->post('not_closed');

        /*
        my - شاشة اذونات الموظف
        manager - شاشة المدير المباشر او الاعلى للاعتماد
        move_admin - شاشة الشؤون الادارية حسب المقر
        hr_admin - شاشة للشؤون الادارية تظهر كل الموظفين
        */

        $this->PAGE_ACT= $this->uri->segment(5);

        if(isset($this->PAGE_ACT) and $this->PAGE_ACT!='' and ($this->uri->segment(3)== 'index' or $this->uri->segment(3)== 'get') ){

            if(!HaveAccess(base_url("hr_attendance/exit_permission/index/1/".$this->PAGE_ACT))){
                die('Error: No Permission '.$this->PAGE_ACT);
            }

            if($this->PAGE_ACT=='my'){

            }elseif($this->PAGE_ACT=='manager'){

            }elseif($this->PAGE_ACT=='move_admin'){

            }elseif($this->PAGE_ACT=='hr_admin'){

            }else{
                die('PAGE_ACT');
            }
        }elseif($this->uri->segment(3)== 'index'){
            die('index');
        }

    }

    function index($page= 1, $page_act= -1, $ser= -1, $emp_no= -1, $p_date= -1, $p_date_2= -1, $notes_emp= -1, $notes_observer= -1, $adopt= -1, $permi_type= -1, $perm_year= -1, $entry_user= -1, $branch_id= -1, $not_closed= -1 ){

        if(isset($this->PAGE_ACT)){
            if($this->PAGE_ACT=='my'){
                $data['title']='اذونات الخروج - الموظف';
                $perm_year=($perm_year!=-1)?$perm_year:date('Y');
            }elseif($this->PAGE_ACT=='manager'){
                $data['title']='اذونات الخروج - المدير';
            }elseif($this->PAGE_ACT=='move_admin'){
                $data['title']='اذونات الخروج - الشؤون الادارية - مسؤول الحركة في المقر';
            }elseif($this->PAGE_ACT=='hr_admin'){
                $data['title']='اذونات الخروج - الشؤون الادارية';
            }else{
                $data['title']=' اذونات الخروج؟؟';
            }
        }

        $data['content']='exit_permission_index';

        $data['entry_user_all'] = $this->get_entry_users('EXIT_PERMISSION_TB');

        $data['page']=$page;
        $data['page_act']= $page_act;
        $data['ser']= $ser;
        $data['emp_no']= $emp_no;
        $data['p_date']= $p_date;
        $data['p_date_2']= $p_date_2;
        $data['notes_emp']= $notes_emp;
        $data['notes_observer']= $notes_observer;
        $data['adopt']= $adopt;
        $data['permi_type']= $permi_type;
        $data['perm_year']= $perm_year;
        $data['entry_user']= $entry_user;
        $data['branch_id']= $branch_id;
        $data['not_closed']= $not_closed;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $page_act= -1, $ser= -1, $emp_no= -1, $p_date= -1, $p_date_2= -1, $notes_emp= -1, $notes_observer= -1, $adopt= -1, $permi_type= -1, $perm_year= -1, $entry_user= -1, $branch_id= -1, $not_closed= -1 ){
        $this->load->library('pagination');

        $structure_tb= ' GFC_HR.HR_EMPS_STRUCTURE_TB ';

        $page_act= $this->check_vars($page_act,'page_act');
        $ser= $this->check_vars($ser,'ser');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $p_date= $this->check_vars($p_date,'p_date');
        $p_date_2= $this->check_vars($p_date_2,'p_date_2');
        $notes_emp= $this->check_vars($notes_emp,'notes_emp');
        $notes_observer= $this->check_vars($notes_observer,'notes_observer');
        $adopt= $this->check_vars($adopt,'adopt');
        $permi_type= $this->check_vars($permi_type,'permi_type');
        $perm_year= $this->check_vars($perm_year,'perm_year');
        $entry_user= $this->check_vars($entry_user,'entry_user');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $not_closed= $this->check_vars($not_closed,'not_closed');

        $where_sql= " where 1=1 ";

        $this->PAGE_ACT =$page_act;
        $data['page_act']= $page_act;

        if(isset($this->PAGE_ACT)){
            if($this->PAGE_ACT=='my'){
                $where_sql.= " and emp_no= {$this->user->emp_no} ";
            }elseif($this->PAGE_ACT=='manager'){
                $where_sql.= " and ((
                emp_no in
                ( SELECT A.EMPLOYEE_NO
                FROM $structure_tb A
                WHERE A.EMPLOYEE_NO     != {$this->user->emp_no}
                START WITH A.EMPLOYEE_NO = {$this->user->emp_no}
                CONNECT BY PRIOR  A.EMPLOYEE_NO =A.MANAGER_NO )
                OR
                emp_no in
                ( SELECT A.EMPLOYEE_NO
                FROM $structure_tb A
                WHERE A.EMPLOYEE_NO  not in ( {$this->user->emp_no} , ( select s.employee_no  from $structure_tb s  where s.alternative_manager_no = {$this->user->emp_no} ) )
                START WITH A.EMPLOYEE_NO = ( select s.employee_no  from $structure_tb s  where s.alternative_manager_no = {$this->user->emp_no} )
                CONNECT BY PRIOR  A.EMPLOYEE_NO =A.MANAGER_NO )
                ))
                and ( adopt >= 10 or ( adopt=1 and entry_user = {$this->user->id} ) ) ";
            }elseif($this->PAGE_ACT=='move_admin'){
                // تم دمج مقر الصيانة مع مقر غزة 202210
                $where_sql.= " and DECODE(branch_id,8,2,branch_id)= {$this->user->branch} and ( adopt >= 10 or ( adopt=1 and entry_user = {$this->user->id} ) ) ";
            }elseif($this->PAGE_ACT=='hr_admin'){
                $where_sql.= " and 1= 1 ";
            }else{
                $where_sql.= " and 1= 2 ";
            }
        }else{
            $where_sql.= " and 1= 2 ";
        }

        if(!$this->input->is_ajax_request()){
            if(isset($this->PAGE_ACT)){
                if($this->PAGE_ACT=='manager'){
                    $where_sql.= " and adopt= 10 and
                        (( emp_no in
                        ( SELECT A.EMPLOYEE_NO
                        FROM $structure_tb A
                        WHERE A.MANAGER_NO = {$this->user->emp_no} )
                        OR
                        emp_no in
                        ( SELECT A.EMPLOYEE_NO
                        FROM $structure_tb A
                        WHERE A.MANAGER_NO = HR_ATTENDANCE_PKG.GET_MANAGER_NO_BY_ALTERNATIVE({$this->user->emp_no}) )
                        ))
                        ";

                }elseif($this->PAGE_ACT=='move_admin'){
                    $where_sql.= " and adopt= 30 ";
                }
            }
        }

        $where_sql.= ($not_closed!= null and $this->PAGE_ACT=='move_admin')? " and adopt between 10 and 30 " : '';

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= in_special_emps($this->user->emp_no, 'emp_no');
        $where_sql.= ($p_date!= null or $p_date_2!= null)? " and TRUNC(p_date) between nvl('{$p_date}','01/01/1000') and nvl('{$p_date_2}','01/01/3000') " : '';
        $where_sql.= ($notes_emp!= null)? " and notes_emp like '".add_percent_sign($notes_emp)."' " : '';
        $where_sql.= ($notes_observer!= null)? " and notes_observer like '".add_percent_sign($notes_observer)."' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($permi_type!= null)? " and permi_type= '{$permi_type}' " : '';
        $where_sql.= ($perm_year!= null)? " and perm_year= '{$perm_year}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';
        $where_sql.= ($branch_id!= null)? " and branch_id= '{$branch_id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' EXIT_PERMISSION_TB '.$where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );

        $data['show_sum']= ($this->PAGE_ACT=='my' or $emp_no!= null)?1:0;

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('exit_permission_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        if($c_var=='adopt')
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create($page_act= -1){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->ser) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->ser);
            }else{
                echo intval($this->ser);
            }
        }else{
            // في حالة ان الموظف معه صلاحيات استعلام بدون اعتماد يتم اغلاق شاشة الادخال
            if($page_act=='move_admin' and !HaveAccess(base_url("hr_attendance/exit_permission/adopt_40"))){
                die('Error: No Permission to create');
            }
            $data['content']='exit_permission_show';
            $data['title']='اضافة اذن خروج';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['page_act'] = $page_act;
            $this->PAGE_ACT= $page_act;
            $data['emp_no_selected'] = $this->user->emp_no;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){ //////////////////////////////////
        if( $this->emp_no=='' or $this->p_date=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif( $this->page_act!='move_admin' and char_to_time(date('d/m/Y'))  >  strtotime('+0 day', char_to_time($this->p_date)) ){
            $this->print_error('لقد تجاوزت الوقت المحدد لادخال الاذن..');
        }else if( $this->p_exit_time=='' ){
            $this->print_error('ادخل ساعة الخروج');
        }else if( $this->p_exit_time!='' and !$this->check_time($this->p_exit_time) ){
            $this->print_error('ادخل ساعة الخروج بشكل صحيح');
        }else if( $this->p_ret_time!='' and !$this->check_time($this->p_ret_time) ){
            $this->print_error('ادخل ساعة العودة بشكل صحيح');
        }else if( $this->p_ret_time!='' and intval(str_replace(':','',$this->p_exit_time)) >= intval(str_replace(':','',$this->p_ret_time))  ){
            $this->print_error('يجب ان تكون ساعة العودة بعد ساعة الخروج');
        }
    }

    function check_time($time){ // 21:30
        return preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time);
    }

    function get($id, $page_act= -1){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        //$data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        if($page_act=='' or $page_act==-1) die('get:page_act') ;
        $data['page_act'] = $page_act;
        $data['content']='exit_permission_show';
        $data['title']='بيانات الاذن ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function public_get_balance($emp_no= 0, $p_date= ''){
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $p_date= $this->check_vars($p_date,'p_date');
        $emp_balance= $this->{$this->MODEL_NAME}->get_balance($emp_no,$p_date);
        $data['emp_balance']= $emp_balance[0];
        $this->load->view('exit_permission_balance',$data);
    }

    private function adopt($case, $type=null){
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case, $type);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_20(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(20, $this->permi_type);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_30(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(30);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_40(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(40);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_0(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(0);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt__20(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(-20);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt__30(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(-30);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt__40(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(-40);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['permi_type_cons'] = $this->constant_details_model->get_list(211);
        $data['adopt_cons'] = $this->constant_details_model->get_list(212);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( $this->user->emp_no , $this->PAGE_ACT );

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$this->emp_no ,'type'=>'','length'=>-1),
            array('name'=>'P_DATE','value'=>$this->p_date ,'type'=>'','length'=>-1),
            array('name'=>'P_EXIT_TIME','value'=>$this->p_exit_time ,'type'=>'','length'=>-1),
            array('name'=>'P_RET_TIME','value'=>$this->p_ret_time ,'type'=>'','length'=>-1),
            array('name'=>'NOTES_EMP','value'=>$this->notes_emp ,'type'=>'','length'=>-1),
            array('name'=>'NOTES_OBSERVER','value'=>$this->notes_observer ,'type'=>'','length'=>-1),
            array('name'=>'PERMI_TYPE','value'=>$this->permi_type ,'type'=>'','length'=>-1),
            array('name'=>'PERM_YEAR','value'=>$this->perm_year ,'type'=>'','length'=>-1),
            array('name'=>'DESTINATION','value'=>$this->destination ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}