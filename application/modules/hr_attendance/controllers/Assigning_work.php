<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/06/18
 * Time: 01:08 م
 */

// ser, emp_no,  ass_start_time, ass_end_time, expected_duration, ass_duration, work_required, notes, adopt, entry_user, branch_id, calc_duration, food_no

class assigning_work extends MY_Controller{

    var $MODEL_NAME= 'assigning_work_model';
    var $PAGE_URL= 'hr_attendance/assigning_work/get_page';
    var $PAGE_ACT;

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);


        $this->page_act= $this->input->post('page_act');
        $this->ser= $this->input->post('ser');
        $this->emp_no= $this->input->post('emp_no');
        $this->ass_start_time= $this->input->post('ass_start_time');
        $this->ass_end_time= $this->input->post('ass_end_time');
        $this->ass_start_date= $this->input->post('ass_start_date');
        $this->ass_end_date= $this->input->post('ass_end_date');
        $this->expected_duration= $this->input->post('expected_duration');
        $this->ass_duration= $this->input->post('ass_duration');
        $this->calc_duration= $this->input->post('calc_duration');
        $this->work_required= $this->input->post('work_required');
        $this->notes= $this->input->post('notes');
        $this->food_no= $this->input->post('food_no');

        $this->start_gps= $this->input->post('start_gps');

        $this->expected_leave_time= $this->input->post('expected_leave_time');
        $this->expected_arrival_time= $this->input->post('expected_arrival_time');
        $this->from_address= $this->input->post('from_address');
        $this->car_request= $this->input->post('car_request');

        $this->task_type= $this->input->post('task_type');
        $this->task_desc= $this->input->post('task_desc');
        $this->governorate_id= $this->input->post('governorate_id');
        $this->directions_no= $this->input->post('directions_no');
        $this->destination_type= $this->input->post('destination_type');
        $this->passengers_no= $this->input->post('passengers_no');

        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        $this->branch_id= $this->input->post('branch_id');
        $this->not_closed= $this->input->post('not_closed');
        $this->emp_type= $this->input->post('emp_type');


        $this->destination_address_1= $this->input->post('destination_address_1');
        $this->destination_gps_1= $this->input->post('destination_gps_1');
        $this->destination_address_2= $this->input->post('destination_address_2');
        $this->destination_gps_2= $this->input->post('destination_gps_2');
        $this->destination_address_3= $this->input->post('destination_address_3');
        $this->destination_gps_3= $this->input->post('destination_gps_3');
        $this->destination_address_4= $this->input->post('destination_address_4');
        $this->destination_gps_4= $this->input->post('destination_gps_4');
        $this->destination_address_5= $this->input->post('destination_address_5');
        $this->destination_gps_5= $this->input->post('destination_gps_5');



        /*
        my - شاشة اذونات الموظف
        manager - شاشة المدير المباشر او الاعلى للاعتماد
        move_admin - شاشة الشؤون الادارية حسب المقر
        hr_admin - شاشة للشؤون الادارية تظهر كل الموظفين
        */

        $this->PAGE_ACT= $this->uri->segment(5);

        if(isset($this->PAGE_ACT) and $this->PAGE_ACT!='' and ($this->uri->segment(3)== 'index' or $this->uri->segment(3)== 'get') ){

            if(!HaveAccess(base_url("hr_attendance/assigning_work/index/1/".$this->PAGE_ACT))){
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

    function index($page= 1, $page_act= -1, $ser= -1, $emp_no= -1, $work_required= -1, $notes= -1, $food_no= -1, $ass_start_date= -1, $ass_end_date= -1, $adopt= -1, $entry_user= -1, $branch_id= -1, $not_closed= -1, $emp_type= -1 ){

        if(isset($this->PAGE_ACT)){
            if($this->PAGE_ACT=='my'){
                $data['title']='تكاليف العمل - الموظف';
            }elseif($this->PAGE_ACT=='manager'){
                $data['title']='تكاليف العمل - المدير';
            }elseif($this->PAGE_ACT=='move_admin'){
                $data['title']='تكاليف العمل - الشؤون الادارية - مسؤول الحركة في المقر';
            }elseif($this->PAGE_ACT=='hr_admin'){
                $data['title']='تكاليف العمل - الشؤون الادارية';
            }else{
                $data['title']=' تكاليف العمل؟؟';
            }
        }

        $data['content']='assigning_work_index';

        $data['entry_user_all'] = $this->get_entry_users('ASSIGNING_WORK_TB');

        $data['page']=$page;
        $data['page_act']= $page_act;
        $data['ser']= $ser;
        $data['emp_no']= $emp_no;
        $data['work_required']= $work_required;
        $data['notes']= $notes;
        $data['food_no']= $food_no;
        $data['ass_start_date']= $ass_start_date;
        $data['ass_end_date']= $ass_end_date;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;
        $data['branch_id']= $branch_id;
        $data['not_closed']= $not_closed;
        $data['emp_type']= $emp_type;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $page_act= -1, $ser= -1, $emp_no= -1, $work_required= -1, $notes= -1, $food_no= -1, $ass_start_date= -1, $ass_end_date= -1, $adopt= -1, $entry_user= -1, $branch_id= -1, $not_closed= -1, $emp_type= -1 ){
        $this->load->library('pagination');

        $structure_tb= ' GFC_HR.HR_EMPS_STRUCTURE_TB ';

        $page_act= $this->check_vars($page_act,'page_act');
        $ser= $this->check_vars($ser,'ser');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $work_required= $this->check_vars($work_required,'work_required');
        $notes= $this->check_vars($notes,'notes');
        $food_no= $this->check_vars($food_no,'food_no');
        $ass_start_date= $this->check_vars($ass_start_date,'ass_start_date');
        $ass_end_date= $this->check_vars($ass_end_date,'ass_end_date');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $not_closed= $this->check_vars($not_closed,'not_closed');
        $emp_type= $this->check_vars($emp_type,'emp_type');

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

        $where_sql.= ($emp_type== 2)? " and emp_no >= 70000 " : " and emp_no < 70000 ";
        $where_sql.= ($not_closed!= null and $this->PAGE_ACT=='move_admin')? " and adopt between 10 and 30 " : '';

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= in_special_emps($this->user->emp_no, 'emp_no');
        $where_sql.= ($work_required!= null)? " and work_required like '".add_percent_sign($work_required)."' " : '';
        $where_sql.= ($notes!= null)? " and notes like '".add_percent_sign($notes)."' " : '';
        $where_sql.= ($food_no!= null and $food_no!= 10)? " and food_no= '{$food_no}' " : '';
        $where_sql.= ($food_no!= null and $food_no== 10)? " and food_no > 0 " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';
        $where_sql.= ($branch_id!= null)? " and branch_id= '{$branch_id}' " : '';
        $where_sql.= ($ass_start_date!= null or $ass_end_date!= null)? " and TRUNC(ass_start_time) between nvl('{$ass_start_date}','01/01/1000') and nvl('{$ass_end_date}','01/01/3000') " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' ASSIGNING_WORK_TB '.$where_sql);
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

        $this->load->view('assigning_work_page',$data);

    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        if($c_var=='adopt' or $c_var=='food_no')
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
            if($page_act=='move_admin' and !HaveAccess(base_url("hr_attendance/assigning_work/adopt_40"))){
                die('Error: No Permission to create');
            }

            $data['content']='assigning_work_show';
            $data['title']='اضافة تكليف عمل';
            $data['isCreate']= true;
            $data['hidden']= 'hidden';
            $data['checked']= '';
            $data['action'] = 'index';
            $data['page_act'] = $page_act;
            $this->PAGE_ACT= $page_act;
            $data['emp_no_selected'] = $this->user->emp_no;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){ //////////////////////////////////
        if( $this->emp_no=='' or $this->ass_start_date=='' or $this->ass_start_time==''    ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif( $this->page_act!='move_admin' and char_to_time(date('d/m/Y'))  >  strtotime('+0 day', char_to_time($this->ass_start_date)) ){
            $this->print_error('لقد تجاوزت الوقت المحدد لادخال التكليف..');
        }else if($this->page_act!='move_admin' and $this->emp_no== $this->user->emp_no ){
            //$this->print_error('ادخال تكليفك يكون من خلال مسؤولك المباشر');
        }else if( $this->ass_start_time=='' ){
            $this->print_error('ادخل ساعة بدء التكليف');
        }else if( $this->ass_start_time!='' and !$this->check_time($this->ass_start_time) ){
            $this->print_error('ادخل ساعة البداية بشكل صحيح');
        }else if( $this->ass_end_time!='' and !$this->check_time($this->ass_end_time) ){
            $this->print_error('ادخل ساعة النهاية بشكل صحيح');
        }else if($this->ass_start_date!='' and $this->ass_end_date!='' and char_to_time($this->ass_start_date) > char_to_time($this->ass_end_date) ){
            $this->print_error('يجب ان يكون تاريخ النهاية بعد تاريخ البداية');
        }else if($this->ass_start_date!='' and $this->ass_end_date!='' and char_to_time($this->ass_start_date) == char_to_time($this->ass_end_date) ){
            if( $this->ass_end_time!='' and intval(str_replace(':','',$this->ass_start_time)) >= intval(str_replace(':','',$this->ass_end_time))  ){
                $this->print_error('يجب ان تكون ساعة النهاية بعد ساعة البداية');
            }
        }else if($this->car_request==1 and ( $this->directions_no=='' or $this->directions_no < 1 ) ){
            $this->print_error('ادخال عدد الاتجاهات');
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
        $data['content']='assigning_work_show';
        $data['move_emails']= $this->get_emails_by_code(9, $result[0]['BRANCH_ID']);
        $data['hidden']= 'hidden';
        $data['checked']= '';
        $data['title']='بيانات التكليف ';
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

    private function adopt($case){
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);
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
            echo $this->adopt(20);
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
        $data['adopt_cons'] = $this->constant_details_model->get_list(222);
        $data['food_no_cons'] = $this->constant_details_model->get_list(241);

        $data['task_type'] = $this->constant_details_model->get_list(383);
        $data['governorate'] = $this->constant_details_model->get_list(339);
        $data['destination_type'] = $this->constant_details_model->get_list(384);



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
            array('name'=>'ASS_START_TIME','value'=>$this->ass_start_time ,'type'=>'','length'=>-1),
            array('name'=>'ASS_END_TIME','value'=>$this->ass_end_time ,'type'=>'','length'=>-1),
            array('name'=>'ASS_START_DATE','value'=>$this->ass_start_date ,'type'=>'','length'=>-1),
            array('name'=>'ASS_END_DATE','value'=>$this->ass_end_date ,'type'=>'','length'=>-1),
            array('name'=>'EXPECTED_DURATION','value'=>$this->expected_duration ,'type'=>'','length'=>-1),
            array('name'=>'CALC_DURATION','value'=>$this->calc_duration ,'type'=>'','length'=>-1),
            array('name'=>'WORK_REQUIRED','value'=>$this->work_required ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
            array('name'=>'FOOD_NO','value'=>$this->food_no ,'type'=>'','length'=>-1),

            array('name'=>'START_GPS', 'value' => $this->start_gps, 'type' => '', 'length' => -1),
            array('name'=>'EXPECTED_LEAVE_TIME', 'value' => $this->expected_leave_time, 'type' => '', 'length' => -1),
            array('name'=>'EXPECTED_ARRIVAL_TIME', 'value' => $this->expected_arrival_time, 'type' => '', 'length' => -1),
            array('name'=>'FROM_ADDRESS', 'value' => $this->from_address, 'type' => '', 'length' => -1),
            array('name'=>'CAR_REQUEST', 'value' => $this->car_request, 'type' => '', 'length' => -1),

            array('name'=>'TASK_TYPE', 'value' => $this->task_type, 'type' => '', 'length' => -1),
            array('name'=>'TASK_DESC', 'value' => $this->task_desc, 'type' => '', 'length' => -1),
            array('name'=>'GOVERNORATE_ID', 'value' => $this->governorate_id, 'type' => '', 'length' => -1),
            array('name'=>'DIRECTIONS_NO', 'value' => $this->directions_no, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_TYPE', 'value' => $this->destination_type, 'type' => '', 'length' => -1),
            array('name'=>'PASSENGERS_NO', 'value' => $this->passengers_no, 'type' => '', 'length' => -1),


            array('name'=>'DESTINATION_ADDRESS_1', 'value' => $this->destination_address_1, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_GPS_1', 'value' => $this->destination_gps_1, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_ADDRESS_2', 'value' => $this->destination_address_2, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_GPS_2', 'value' => $this->destination_gps_2, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_ADDRESS_3', 'value' => $this->destination_address_3, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_GPS_3', 'value' => $this->destination_gps_3, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_ADDRESS_4', 'value' => $this->destination_address_4, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_GPS_4', 'value' => $this->destination_gps_4, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_ADDRESS_5', 'value' => $this->destination_address_5, 'type' => '', 'length' => -1),
            array('name'=>'DESTINATION_GPS_5', 'value' => $this->destination_gps_5, 'type' => '', 'length' => -1),


        );

        if($typ=='create')
            unset($result[0]);
        return $result;
    }


}
