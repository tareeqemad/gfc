<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 10:16 ص
 */

// rental_activity_id,contractor_file_id,activity_type,activity_date,new_start_date,new_end_date,new_note,new_insurance_num,new_insurance_company,new_insurance_type,  old_note,old_start_date,old_end_date,old_insurance_num,old_insurance_company,adopt
// عند اعتماد السجل يتم تخزين البيانات القديمة في السجل ثم تحديث ملف المتعاقد حسب البيانات الجديده

class Rental_activity extends MY_Controller{

    var $MODEL_NAME= 'rental_activity_model';
    var $PAGE_URL= 'rental/rental_activity/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->rental_activity_id= $this->input->post('rental_activity_id');
        $this->contractor_file_id= $this->input->post('contractor_file_id');
        $this->activity_type= $this->input->post('activity_type');
        $this->activity_date= $this->input->post('activity_date');
        $this->new_start_date= $this->input->post('new_start_date');
        $this->new_end_date= $this->input->post('new_end_date');
        $this->new_note= $this->input->post('new_note');
        $this->new_car_num= $this->input->post('new_car_num');
        $this->new_insurance_num= $this->input->post('new_insurance_num');
        $this->new_insurance_company= $this->input->post('new_insurance_company');
        $this->new_insurance_type= $this->input->post('new_insurance_type');
        $this->new_iban= $this->input->post('new_iban');
        $this->new_subscriber_id= $this->input->post('new_subscriber_id');
        $this->new_bank_id= $this->input->post('new_bank_id');
        $this->new_bank_branch= $this->input->post('new_bank_branch');
        $this->new_account_id= $this->input->post('new_account_id');
        $this->new_contractor_name_to_bank= $this->input->post('new_contractor_name_to_bank');
        $this->new_contractor_to_bank_id= $this->input->post('new_contractor_to_bank_id');
        $this->new_mobile_no= $this->input->post('new_mobile_no');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');


        if( HaveAccess(base_url("rental/rental_activity/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;
    }

    function index($page= 1, $rental_activity_id= -1, $contractor_file_id= -1, $activity_type= -1, $activity_date= -1, $new_start_date= -1, $new_end_date= -1, $new_note= -1, $adopt= -1, $entry_user= -1 ){

        $data['title']='الحركات';
        $data['content']='rental_activity_index';

        $data['entry_user_all'] = $this->get_entry_users('RENTAL_ACTIVITY_TB');

        $data['page']=$page;
        $data['rental_activity_id']= $rental_activity_id;
        $data['contractor_file_id']= $contractor_file_id;
        $data['activity_type']= $activity_type;
        $data['activity_date']= $activity_date;
        $data['new_start_date']= $new_start_date;
        $data['new_end_date']= $new_end_date;
        $data['new_note']= $new_note;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $rental_activity_id= -1, $contractor_file_id= -1, $activity_type= -1, $activity_date= -1, $new_start_date= -1, $new_end_date= -1, $new_note= -1, $adopt= -1, $entry_user= -1 ){
        $this->load->library('pagination');

        $rental_activity_id= $this->check_vars($rental_activity_id,'rental_activity_id');
        $contractor_file_id= $this->check_vars($contractor_file_id,'contractor_file_id');
        $activity_type= $this->check_vars($activity_type,'activity_type');
        $activity_date= $this->check_vars($activity_date,'activity_date');
        $new_start_date= $this->check_vars($new_start_date,'new_start_date');
        $new_end_date= $this->check_vars($new_end_date,'new_end_date');
        $new_note= $this->check_vars($new_note,'new_note');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";
        if(!$this->all_branches)
            $where_sql.= " and SERV_RENT_PKG.GET_CONTRACTOR_BRANCH(contractor_file_id)= ".(  ($this->user->branch ==8)? 2:$this->user->branch  );

        $where_sql.= ($rental_activity_id!= null)? " and rental_activity_id= '{$rental_activity_id}' " : '';
        $where_sql.= ($contractor_file_id!= null)? " and contractor_file_id= '{$contractor_file_id}' " : '';
        $where_sql.= ($activity_type!= null)? " and activity_type= '{$activity_type}' " : '';
        $where_sql.= ($activity_date!= null)? " and activity_date= '{$activity_date}' " : '';
        $where_sql.= ($new_start_date!= null)? " and new_start_date= '{$new_start_date}' " : '';
        $where_sql.= ($new_end_date!= null)? " and new_end_date= '{$new_end_date}' " : '';
        $where_sql.= ($new_note!= null)? " and new_note like '".add_percent_sign($new_note)."' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' RENTAL_ACTIVITY_TB '.$where_sql);
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

        $this->load->view('rental_activity_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            if ($this->activity_type == 9){
                $this->_post_validation();
            }

            $this->rental_activity_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->rental_activity_id) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->rental_activity_id);
            }else{
                echo intval($this->rental_activity_id);
            }
        }else{
            $data['content']='rental_activity_show';
            $data['title']='اضافة حركة ';
            $data['action'] = 'index';
            $data['isCreate']= true;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){ //////////////////////////////////
        //$this->print_error('يجب ادخال جميع البيانات');
        if( $this->new_mobile_no==''){
            $this->print_error('ادخل رقم الجوال');
        }else if( strlen( $this->new_mobile_no ) != 10 ){
            $this->print_error('الرجاء كتابة رقم الجوال بشكل صحيح');
        }else if( substr($this->new_mobile_no, 0, 3) != '059' and substr($this->new_mobile_no, 0, 3) != '056'){
            $this->print_error('يجب ان يبدأ رقم الجوال ب059 أو 056');
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='rental_activity_show';
        $data['title']='بيانات الحركة ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            if ($this->activity_type == 9){
                $this->_post_validation();
            }
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    private function adopt($case){
        $res = $this->{$this->MODEL_NAME}->adopt($this->rental_activity_id, $case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_2(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->rental_activity_id!=''){
            echo $this->adopt(2);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_3(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->rental_activity_id!=''){
            echo $this->adopt(3);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['activity_type_cons'] = $this->constant_details_model->get_list(187);
        $data['adopt_cons'] = $this->constant_details_model->get_list(188);
        $data['insurance_company_cons'] = $this->constant_details_model->get_list(177);
        $data['insurance_type_cons'] = $this->constant_details_model->get_list(178);
        $data['bank_id_cons'] = $this->constant_details_model->get_list(9);
        $data['bank_branch_cons'] = $this->constant_details_model->get_list(196);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'RENTAL_ACTIVITY_ID','value'=>$this->rental_activity_id ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_FILE_ID','value'=>$this->contractor_file_id ,'type'=>'','length'=>-1),
            array('name'=>'ACTIVITY_TYPE','value'=>$this->activity_type ,'type'=>'','length'=>-1),
            array('name'=>'ACTIVITY_DATE','value'=>$this->activity_date ,'type'=>'','length'=>-1),
            array('name'=>'NEW_START_DATE','value'=>$this->new_start_date ,'type'=>'','length'=>-1),
            array('name'=>'NEW_END_DATE','value'=>$this->new_end_date ,'type'=>'','length'=>-1),
            array('name'=>'NEW_INSURANCE_NUM','value'=>$this->new_insurance_num ,'type'=>'','length'=>-1),
            array('name'=>'NEW_INSURANCE_COMPANY','value'=>$this->new_insurance_company ,'type'=>'','length'=>-1),
            array('name'=>'NEW_NOTE','value'=>$this->new_note ,'type'=>'','length'=>-1),
            array('name'=>'NEW_INSURANCE_TYPE','value'=>$this->new_insurance_type ,'type'=>'','length'=>-1),
            array('name'=>'NEW_IBAN','value'=>$this->new_iban ,'type'=>'','length'=>-1),
            array('name'=>'NEW_CAR_NUM','value'=>$this->new_car_num ,'type'=>'','length'=>-1),
            array('name'=>'NEW_SUBSCRIBER_ID','value'=>$this->new_subscriber_id ,'type'=>'','length'=>-1),
            array('name'=>'NEW_BANK_ID','value'=>$this->new_bank_id ,'type'=>'','length'=>-1),
            array('name'=>'NEW_BANK_BRANCH','value'=>$this->new_bank_branch ,'type'=>'','length'=>-1),
            array('name'=>'NEW_ACCOUNT_ID','value'=>$this->new_account_id ,'type'=>'','length'=>-1),
            array('name'=>'NEW_CONTRACTOR_NAME_TO_BANK','value'=>$this->new_contractor_name_to_bank ,'type'=>'','length'=>-1),
            array('name'=>'NEW_CONTRACTOR_TO_BANK_ID','value'=>$this->new_contractor_to_bank_id ,'type'=>'','length'=>-1),
            array('name'=>'NEW_MOBILE_NO','value'=>$this->new_mobile_no ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}