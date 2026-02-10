<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/03/18
 * Time: 11:24 ص
 */

// ser, emp_no, vac_type, vac_date, vac_end_date, vac_duration, add_in_vac, the_reason, acting_officer, ret_date, notes, adopt, entry_user, branch_id, vac_year

class vacation_request extends MY_Controller{

    var $MODEL_NAME= 'vacation_request_model';
    var $PAGE_URL= 'hr_attendance/vacation_request/get_page';
    var $PAGE_ACT;

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->page_act= $this->input->post('page_act');
        $this->ser= $this->input->post('ser');
        $this->emp_no= $this->input->post('emp_no');
        $this->direct_emps= $this->input->post('direct_emps');
        $this->vac_type= $this->input->post('vac_type');
        $this->vac_date= $this->input->post('vac_date');
        $this->vac_end_date= $this->input->post('vac_end_date');
        $this->vac_duration= $this->input->post('vac_duration');
        $this->add_in_vac= $this->input->post('add_in_vac');
        $this->the_reason= $this->input->post('the_reason');
        $this->acting_officer= $this->input->post('acting_officer');
        $this->ret_date= $this->input->post('ret_date');
        $this->notes= $this->input->post('notes');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        $this->branch_id= $this->input->post('branch_id');
        $this->vac_year= $this->input->post('vac_year');
        $this->refusal_note= $this->input->post('refusal_note');
        $this->not_closed= $this->input->post('not_closed');

        /*
        my - شاشة اجازات الموظف
        manager - شاشة المدير المباشر او الاعلى للاعتماد
        move_admin - شاشة الشؤون الادارية حسب المقر
        hr_admin - شاشة للشؤون الادارية تظهر كل الموظفين
        */

        $this->PAGE_ACT= $this->uri->segment(5);

        if(isset($this->PAGE_ACT) and $this->PAGE_ACT!='' and ($this->uri->segment(3)== 'index' or $this->uri->segment(3)== 'get') ){

            if(!HaveAccess(base_url("hr_attendance/vacation_request/index/1/".$this->PAGE_ACT))){
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

    function index($page= 1, $page_act= -1, $ser= -1, $emp_no= -1, $direct_emps= -1, $vac_type= -1, $vac_date= -1, $vac_end_date= -1, $vac_duration= -1, $ret_date= -1, $notes= -1, $adopt= null, $entry_user= -1, $branch_id= -1, $vac_year= -1, $not_closed= -1 ){

        if(isset($this->PAGE_ACT)){
            if($this->PAGE_ACT=='my'){
                $data['title']='الاجازات - الموظف';
                $vac_year=($vac_year!=-1)?$vac_year:date('Y');
            }elseif($this->PAGE_ACT=='manager'){
                $data['title']='الاجازات - المدير';
            }elseif($this->PAGE_ACT=='move_admin'){
                $data['title']='الاجازات - الشؤون الادارية - مسؤول الحركة في المقر';
            }elseif($this->PAGE_ACT=='hr_admin'){
                $data['title']='الاجازات - الشؤون الادارية';
            }else{
                $data['title']=' اجازات؟؟';
            }
        }

        $data['content']='vacation_request_index';

        $data['entry_user_all'] = $this->get_entry_users('VACATION_REQUEST_TB');

        $data['page']=$page;
        $data['page_act']= $page_act;
        $data['ser']= $ser;
        $data['emp_no']= $emp_no;
        $data['direct_emps']= $direct_emps;
        $data['vac_type']= $vac_type;
        $data['vac_date']= $vac_date;
        $data['vac_end_date']= $vac_end_date;
        $data['vac_duration']= $vac_duration;
        $data['ret_date']= $ret_date;
        $data['notes']= $notes;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;
        $data['branch_id']= $branch_id;
        $data['vac_year']= $vac_year;
        $data['not_closed']= $not_closed;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $page_act= -1, $ser= -1, $emp_no= -1, $direct_emps= -1, $vac_type= -1, $vac_date= -1, $vac_end_date= -1, $vac_duration= -1, $ret_date= -1, $notes= -1, $adopt= null, $entry_user= -1, $branch_id= -1, $vac_year= -1, $not_closed= -1 ){
        $this->load->library('pagination');

        $structure_tb= ' GFC_HR.HR_EMPS_STRUCTURE_TB ';

        $page_act= $this->check_vars($page_act,'page_act');
        $ser= $this->check_vars($ser,'ser');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $direct_emps= $this->check_vars($direct_emps,'direct_emps');
        $vac_type= $this->check_vars($vac_type,'vac_type');
        $vac_date= $this->check_vars($vac_date,'vac_date');
        $vac_end_date= $this->check_vars($vac_end_date,'vac_end_date');
        $vac_duration= $this->check_vars($vac_duration,'vac_duration');
        $ret_date= $this->check_vars($ret_date,'ret_date');
        $notes= $this->check_vars($notes,'notes');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $vac_year= $this->check_vars($vac_year,'vac_year');
        $not_closed= $this->check_vars($not_closed,'not_closed');

        $where_sql= " where 1=1 ";

        $this->PAGE_ACT =$page_act;
        $data['page_act']= $page_act;

        $vac_arr = ' (1,2,17) '; // عادية او طارئة   او غياب بدون اذن

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
                // OLD  $where_sql.= " and branch_id= {$this->user->branch} and ( ( adopt >= 10 or ( adopt=1 and entry_user = {$this->user->id} ) ) or vac_type=3 ) ";
                // تم دمج مقر الصيانة مع مقر غزة 202210
                $where_sql.= " and DECODE(branch_id,8,2,branch_id)= {$this->user->branch} 
                and (
                    ( adopt >= 10 and vac_type in {$vac_arr} )
                 or ( adopt >= 1 and vac_type not in {$vac_arr} )
                 or ( adopt = 1 and entry_user = {$this->user->id} )
                ) ";
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
                    // OLD  $where_sql.= " and ( adopt=30 or (vac_type=3 and adopt=1) ) ";
                    $where_sql.= " and ( adopt=1 or (vac_type in {$vac_arr} and adopt=30) ) ";
                }
            }
        }

        $where_sql.= ($direct_emps!= null)? " and emp_no in
                ( SELECT A.EMPLOYEE_NO
                FROM $structure_tb A
                WHERE A.MANAGER_NO = {$this->user->emp_no} ) " : '';

        $where_sql.= ($not_closed!= null and $this->PAGE_ACT=='move_admin')? " and adopt between 1 and 30 " : '';

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= in_special_emps($this->user->emp_no, 'emp_no');
        $where_sql.= ($vac_type!= null)? " and vac_type= '{$vac_type}' " : '';
        $where_sql.= ($vac_date!= null or $vac_end_date!= null)? "
            and   ( vac_date between nvl('{$vac_date}','01/01/1000') and nvl('{$vac_end_date}','01/01/3000')
            or  vac_end_date between nvl('{$vac_date}','01/01/1000') and nvl('{$vac_end_date}','01/01/3000') )
         " : '';
        $where_sql.= ($vac_duration!= null)? " and vac_duration= '{$vac_duration}' " : '';
        $where_sql.= ($ret_date!= null)? " and ret_date= '{$ret_date}' " : '';
        $where_sql.= ($notes!= null)? " and notes like '".add_percent_sign($notes)."' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';
        $where_sql.= ($branch_id!= null)? " and branch_id= '{$branch_id}' " : '';
        $where_sql.= ($vac_year!= null)? " and vac_year= '{$vac_year}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' VACATION_REQUEST_TB '.$where_sql);
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

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('vacation_request_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        if($c_var=='adopt')
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        if($c_var=='adopt')
            $var= $var;
        else
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
            if($page_act=='move_admin' and !HaveAccess(base_url("hr_attendance/vacation_request/adopt_40"))){
                die('Error: No Permission to create');
            }
            $data['content']='vacation_request_show';
            $data['title']='ادخال طلب اجازة ';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['page_act'] = $page_act;
            $this->PAGE_ACT= $page_act;
            $data['emp_no_selected'] = $this->user->emp_no;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( $this->emp_no=='' or $this->vac_date=='' or $this->vac_type=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif( $this->page_act!='move_admin' and char_to_time(date('d/m/Y'))  >  strtotime('+3 day', char_to_time($this->vac_date)) ){
            $this->print_error('لقد تجاوزت الوقت المحدد لادخال الاجازة..');
        }elseif($this->page_act!='move_admin' and $this->vac_type > 2){
            $this->print_error('يرجى اختيار نوع الاجازة عادية او طارئة');
        }elseif( char_to_time($this->vac_date) > char_to_time($this->vac_end_date) ){
            $this->print_error('يجب ان يكون تاريخ نهاية الاجازة بعد تاريخ البداية');
        }elseif( date("Y", char_to_time($this->vac_date)) != date("Y", char_to_time($this->vac_end_date)) ){
            $this->print_error('يجب ان يكون تاريخ بداية ونهاية الاجازة في نفس العام');
        }

        if($this->page_act!='move_admin'){
            $this->ret_date='';
        }
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
        $data['content']='vacation_request_show';
        $data['title']='بيانات الاجازة ';
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

    function public_get_balance($emp_no= 0, $vac_year=''){
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $vac_year= $this->check_vars($vac_year,'vac_year');
        $emp_balance= $this->{$this->MODEL_NAME}->get_balance($emp_no, $vac_year);
        $data['emp_balance']= $emp_balance[0];
        $this->load->view('vacation_request_balance',$data);
    }

    private function adopt($case, $note=''){
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case, $note);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){ // اعتماد المدخل
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_20(){ // اعتماد المدير
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(20);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_30(){ // اعتماد العودة
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(30);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_40(){ // اعتماد الشؤون الادارية
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(40);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_0(){ // الغاء الاجازة من المدخل
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(0);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt__10(){ // الغاء اعتماد المدخل
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(-10);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt__20(){ // الغاء الاجازة من المدير
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(-20);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt__30(){ // الغاء الاجازة من الشؤون الادارية
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(-30);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt__40(){ // الغاء اعتماد الشؤون الادارية
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(-40);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt__1(){ // رفض الاجازة من المدير
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(-1, $this->refusal_note);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['vac_type_cons'] = $this->constant_details_model->get_list(214);
        $data['adopt_cons'] = $this->constant_details_model->get_list(215);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( $this->user->emp_no , $this->PAGE_ACT );
        $data['acting_officer_cons'] = $this->hr_attendance_model->get_child( $this->user->emp_no , 'hr_admin' );

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
            array('name'=>'VAC_TYPE','value'=>$this->vac_type ,'type'=>'','length'=>-1),
            array('name'=>'VAC_DATE','value'=>$this->vac_date ,'type'=>'','length'=>-1),
            array('name'=>'VAC_END_DATE','value'=>$this->vac_end_date ,'type'=>'','length'=>-1),
            array('name'=>'VAC_DURATION','value'=>$this->vac_duration ,'type'=>'','length'=>-1),
            array('name'=>'ADD_IN_VAC','value'=>$this->add_in_vac ,'type'=>'','length'=>-1),
            array('name'=>'THE_REASON','value'=>$this->the_reason ,'type'=>'','length'=>-1),
            array('name'=>'ACTING_OFFICER','value'=>$this->acting_officer ,'type'=>'','length'=>-1),
            array('name'=>'RET_DATE','value'=>$this->ret_date ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
            array('name'=>'VAC_YEAR','value'=>$this->vac_year ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}
