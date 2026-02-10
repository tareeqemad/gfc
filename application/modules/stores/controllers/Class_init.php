<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/03/20
 */

class class_init extends MY_Controller
{
    var $MODEL_NAME = 'class_init_model';
    var $PAGE_URL = 'stores/class_init/get_page';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);


        /* level 10
         الجهة الطالبة
        class_name_ar
        class_name_en
        class_description
        class_type
        class_unit
        class_unit_sub
        class_unit_count
        class_min
        class_max
        class_min_request
        class_parent_id
        responsible_in_out


        /* level 50
        المالية
        class_acount_type
        curr_id
        account_type
        account_id
        exp_account_cust
        is_budget
        section_no
        destruction_type
        destruction_percent
        destruction_account_id
        average_life_span


        /* level 30
        اللوازم
        custody_type
        personal_custody_type


        /* level 40
        التسعير
        buy_price
        open_price
        used_percent


        /* level 20
        المخازن
        class_status

        */

        $this->ser= $this->input->post('ser');
        $this->adopt= $this->input->post('adopt');
        $this->rev_adopt= $this->input->post('rev_adopt');
        $this->rev_note= $this->input->post('rev_note');
        $this->entry_user= $this->input->post('entry_user');

        //cnt 12
        $this->class_parent_id= $this->input->post('class_parent_id');
        $this->class_name_ar= $this->input->post('class_name_ar');
        $this->class_name_en= $this->input->post('class_name_en');
        $this->class_description= $this->input->post('class_description');
        $this->class_type= $this->input->post('class_type');
        $this->class_unit= $this->input->post('class_unit');
        $this->class_unit_sub= $this->input->post('class_unit_sub');
        $this->class_unit_count= $this->input->post('class_unit_count');
        $this->class_min= $this->input->post('class_min');
        $this->class_max= $this->input->post('class_max');
        $this->class_min_request= $this->input->post('class_min_request');
        $this->responsible_in_out= $this->input->post('responsible_in_out');


        //cnt 11
        $this->class_acount_type= $this->input->post('class_acount_type');
        $this->curr_id= $this->input->post('curr_id');
        $this->account_type= $this->input->post('account_type');
        $this->account_id= $this->input->post('account_id');
        $this->exp_account_cust= $this->input->post('exp_account_cust');
        $this->is_budget= $this->input->post('is_budget');
        $this->section_no= $this->input->post('section_no');
        $this->destruction_type= $this->input->post('destruction_type');
        $this->destruction_percent= $this->input->post('destruction_percent');
        $this->destruction_account_id= $this->input->post('destruction_account_id');
        $this->average_life_span= $this->input->post('average_life_span');


        //cnt 2
        $this->custody_type= $this->input->post('custody_type');
        $this->personal_custody_type= $this->input->post('personal_custody_type');

        //cnt 3
        $this->buy_price= $this->input->post('buy_price');
        $this->open_price= $this->input->post('open_price');
        $this->used_percent= $this->input->post('used_percent');

        //cnt 1
        $this->class_status= $this->input->post('class_status');

    }

    function index($page= 1, $ser= -1, $class_parent_id= -1, $class_name_ar= -1, $custody_type= -1, $buy_price= -1, $adopt= -1, $entry_user= -1){
        $data['title']='طلب ادخال اصناف';
        $data['content']='class_init_index';
        //$data['entry_user_all'] = $this->get_entry_users('CLASS_INIT_TB');  // entry_user_10

        $data['page']=$page;
        $data['ser']= $ser;
        $data['class_parent_id']= $class_parent_id;
        $data['class_name_ar']= $class_name_ar;
        $data['custody_type']= $custody_type;
        $data['buy_price']= $buy_price;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        //$data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $ser= -1, $class_parent_id= -1, $class_name_ar= -1, $custody_type= -1, $buy_price= -1, $adopt= -1, $entry_user= -1){
        $this->load->library('pagination');

        $ser= $this->check_vars($ser,'ser');
        $class_parent_id= $this->check_vars($class_parent_id,'class_parent_id');
        $class_name_ar= $this->check_vars($class_name_ar,'class_name_ar');
        $custody_type= $this->check_vars($custody_type,'custody_type');
        $buy_price= $this->check_vars($buy_price,'buy_price');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($class_parent_id!= null)? " and class_parent_id= '{$class_parent_id}' " : '';
        $where_sql.= ($class_name_ar!= null)? " and class_name_ar like '".add_percent_sign($class_name_ar)."' " : '';
        $where_sql.= ($custody_type!= null)? " and custody_type= '{$custody_type}' " : '';
        $where_sql.= ($buy_price!= null)? " and buy_price= '{$buy_price}' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and ENTRY_USER_10= '{$this->user->id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' CLASS_INIT_TB '.$where_sql);
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

        $this->load->view('class_init_page',$data);
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

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        //$data['can_edit'] = 1;
        //$data['action'] = 'edit';
        $data['content']='class_init_show';
        $data['title']='بيانات الصنف ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function public_get_class_child(){
        $res= $this->{$this->MODEL_NAME}->get_class_child($this->class_parent_id);
        $this->return_json($res);
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(10);
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData(10, 'create'));
            if(intval($this->ser) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->ser);
            }else{
                echo intval($this->ser);
            }
        }else{
            $data['content']='class_init_show';
            $data['title']='اضافة ';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function edit_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(10);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData(10), 10);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function edit_20(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(20);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData(20), 20);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function edit_30(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(30);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData(30), 30);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function edit_40(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(40);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData(40), 40);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function edit_50(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(50);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData(50), 50);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    private function adopt($case){
        $this->_post_validation($case-10);
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
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

    function adopt_50(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(50);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_60(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(60);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function adopt_0(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(0);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function rev_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            $res = $this->{$this->MODEL_NAME}->rev_adopt($this->ser, $this->adopt, $this->rev_adopt, $this->rev_note);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }


    function _post_validation($level){
        if( $level==10 and ($this->class_parent_id=='' or $this->class_name_ar=='' or $this->class_type=='' or $this->class_unit=='' or $this->class_unit_sub=='' or $this->class_unit_count=='' or $this->class_min=='' or $this->class_max=='' or $this->class_min_request=='' or $this->responsible_in_out=='') ) {
            $this->print_error('يرجى ادخال البيانات المحددة بنجمة');

        }else if( $level==50 and ($this->class_acount_type=='' or $this->curr_id=='' or $this->account_type=='' or $this->account_id=='')  ){
            $this->print_error('يرجى ادخال البيانات المحددة بنجمة');

        }else if( $level==50 and $this->is_budget==1 and $this->section_no=='' ){
            $this->print_error('يرجى ادخال الفصل');

        }else if( $level==30 and ($this->custody_type=='' or ( $this->custody_type!=5 and $this->personal_custody_type=='' ) )  ){
            $this->print_error('يرجى ادخال البيانات المحددة بنجمة');

        }else if( $level==40 and ($this->buy_price=='' or $this->used_percent=='')  ){
            $this->print_error('يرجى ادخال البيانات المحددة بنجمة');

        }else if( $level==20 and ($this->class_status=='')  ){
            $this->print_error('يرجى ادخال البيانات المحددة بنجمة');
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('budget/budget_section_model');

        $data['section_no_cons']= $this->budget_section_model->get_all();
        $data['curr_id_cons'] = $this->currency_model->get_all();
        $data['class_parent_id_cons'] = $this->{$this->MODEL_NAME}->get_parents();

        $data['adopt_cons'] = $this->constant_details_model->get_list(329);
        $data['class_type_cons'] = $this->constant_details_model->get_list(21);
        $data['class_unit_cons'] = $this->constant_details_model->get_list(22);
        $data['class_unit_sub_cons'] = $this->constant_details_model->get_list(29);
        $data['responsible_in_out_cons'] = $this->constant_details_model->get_list(24);
        $data['class_acount_type_cons'] = $this->constant_details_model->get_list(36);
        $data['account_type_cons'] = array( array('CON_NO'=>'1','CON_NAME'=>'رئيسي'), array('CON_NO'=>'2','CON_NAME'=>'فرعي') );
        $data['is_budget_cons'] = $this->constant_details_model->get_list(95);
        $data['destruction_type_cons'] = $this->constant_details_model->get_list(82);
        $data['average_life_span_cons'] = $this->constant_details_model->get_list(129);
        $data['custody_type_cons'] = $this->constant_details_model->get_list(305);
        $data['personal_custody_type_cons'] = $this->constant_details_model->get_list(304);
        $data['class_status_cons'] = $this->constant_details_model->get_list(111);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($level=0, $typ= null){
        if($level==10){

            $result = array(
                array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_PARENT_ID','value'=>$this->class_parent_id ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_NAME_AR','value'=>$this->class_name_ar ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_NAME_EN','value'=>$this->class_name_en ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_DESCRIPTION','value'=>$this->class_description ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_TYPE','value'=>$this->class_type ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_UNIT','value'=>$this->class_unit ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_UNIT_SUB','value'=>$this->class_unit_sub ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_UNIT_COUNT','value'=>$this->class_unit_count ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_MIN','value'=>$this->class_min ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_MAX','value'=>$this->class_max ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_MIN_REQUEST','value'=>$this->class_min_request ,'type'=>'','length'=>-1),
                array('name'=>'RESPONSIBLE_IN_OUT','value'=>$this->responsible_in_out ,'type'=>'','length'=>-1),
            );

        }elseif($level==50){

            $result = array(
                array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_ACOUNT_TYPE','value'=>$this->class_acount_type ,'type'=>'','length'=>-1),
                array('name'=>'CURR_ID','value'=>$this->curr_id ,'type'=>'','length'=>-1),
                array('name'=>'ACCOUNT_TYPE','value'=>$this->account_type ,'type'=>'','length'=>-1),
                array('name'=>'ACCOUNT_ID','value'=>$this->account_id ,'type'=>'','length'=>-1),
                array('name'=>'EXP_ACCOUNT_CUST','value'=>$this->exp_account_cust ,'type'=>'','length'=>-1),
                array('name'=>'IS_BUDGET','value'=>$this->is_budget ,'type'=>'','length'=>-1),
                array('name'=>'SECTION_NO','value'=>$this->section_no ,'type'=>'','length'=>-1),
                array('name'=>'DESTRUCTION_TYPE','value'=>$this->destruction_type ,'type'=>'','length'=>-1),
                array('name'=>'DESTRUCTION_PERCENT','value'=>$this->destruction_percent ,'type'=>'','length'=>-1),
                array('name'=>'DESTRUCTION_ACCOUNT_ID','value'=>$this->destruction_account_id ,'type'=>'','length'=>-1),
                array('name'=>'AVERAGE_LIFE_SPAN','value'=>$this->average_life_span ,'type'=>'','length'=>-1),
            );

        }elseif($level==30){

            $result = array(
                array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
                array('name'=>'CUSTODY_TYPE','value'=>$this->custody_type ,'type'=>'','length'=>-1),
                array('name'=>'PERSONAL_CUSTODY_TYPE','value'=>$this->personal_custody_type ,'type'=>'','length'=>-1),
            );

        }elseif($level==40){

            $result = array(
                array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
                array('name'=>'BUY_PRICE','value'=>$this->buy_price ,'type'=>'','length'=>-1),
                array('name'=>'OPEN_PRICE','value'=>'' ,'type'=>'','length'=>-1),
                array('name'=>'USED_PERCENT','value'=>$this->used_percent ,'type'=>'','length'=>-1),
            );

        }elseif($level==20){

            $result = array(
                array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
                array('name'=>'CLASS_STATUS','value'=>$this->class_status ,'type'=>'','length'=>-1),
            );

        }

        if($typ=='create')
            unset($result[0]);
        return $result;

    }

}
