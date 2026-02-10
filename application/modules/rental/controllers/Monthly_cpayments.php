<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 12:52 م
 */

// monthly_cpayments_id,branch_id,payroll_date,adopt

class Monthly_cpayments extends MY_Controller{

    var $MODEL_NAME= 'monthly_cpayments_model';
    var $PAGE_URL= 'rental/monthly_cpayments/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->monthly_cpayments_id= $this->input->post('monthly_cpayments_id');
        $this->branch_id= $this->input->post('branch_id');
        $this->payroll_date= $this->input->post('payroll_date');
        $this->date_from= $this->input->post('date_from');
        $this->date_to= $this->input->post('date_to');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        $this->NOTE_4= $this->input->post('NOTE_4');

        if( HaveAccess(base_url("rental/monthly_cpayments/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;

    }

    function index($page= 1, $monthly_cpayments_id= -1, $branch_id= -1, $payroll_date= -1, $adopt= -1, $entry_user= -1 ){

        $data['title']='المطالبات الشهرية';
        $data['content']='monthly_cpayments_index';

        $data['entry_user_all'] = $this->get_entry_users('MONTHLY_CPAYMENTS_TB');

        $data['page']=$page;
        $data['monthly_cpayments_id']= $monthly_cpayments_id;
        $data['branch_id']= $branch_id;
        $data['payroll_date']= $payroll_date;
        $data['adopt']= $adopt;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $monthly_cpayments_id= -1, $branch_id= -1, $payroll_date= -1, $adopt= -1, $entry_user= -1 ){
        $this->load->library('pagination');

        $monthly_cpayments_id= $this->check_vars($monthly_cpayments_id,'monthly_cpayments_id');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $payroll_date= $this->check_vars($payroll_date,'payroll_date');
        $adopt= $this->check_vars($adopt,'adopt');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";

        if(!$this->all_branches)
            $where_sql.= " and branch_id= ".(  ($this->user->branch ==8)? 2:$this->user->branch  );

        $where_sql.= ($monthly_cpayments_id!= null)? " and monthly_cpayments_id= '{$monthly_cpayments_id}' " : '';
        $where_sql.= ($branch_id!= null)? " and branch_id= '{$branch_id}' " : '';
        $where_sql.= ($payroll_date!= null)? " and payroll_date= '01/{$payroll_date}' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' MONTHLY_CPAYMENTS_TB '.$where_sql);
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

        $this->load->view('monthly_cpayments_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function public_get_all_for_select($val=0){
        $all= $this->{$this->MODEL_NAME}->get_all();
        $select= '<option value="">_________</option>';
        foreach($all as $row){
            $select.= '<option '. (($val?($val==$row['MONTHLY_CPAYMENTS_ID']?'selected':''):'')) .' value="'.$row['MONTHLY_CPAYMENTS_ID'].'" >'. (($row['MONTHLY_CPAYMENTS_ID'].': '.$row['BRANCH_ID_NAME'].' '.$row['PAYROLL_DATE'])) .'</option>';
        }
        return $select;
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->monthly_cpayments_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->monthly_cpayments_id) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->monthly_cpayments_id);
            }else{
                echo intval($this->monthly_cpayments_id);
            }
        }else{
            $data['content']='monthly_cpayments_show';
            $data['title']='اضافة مطالبة شهرية';
            $data['action'] = 'index';
            $data['isCreate']= true;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( $this->branch_id=='' or $this->payroll_date=='' or $this->date_from=='' or $this->date_to=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif( char_to_time($this->date_from) > char_to_time($this->date_to) ){
            $this->print_error('يجب ان تكون الفترة صحيحة');
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='monthly_cpayments_show';
        $data['title']='بيانات المطالبة ';
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
        $res = $this->{$this->MODEL_NAME}->adopt($this->monthly_cpayments_id, $case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_2(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->monthly_cpayments_id!=''){
            echo $this->adopt(2);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_3(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->monthly_cpayments_id!=''){
            echo $this->adopt(3);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_4(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->monthly_cpayments_id!=''){
            echo $this->adopt(4);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_5(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->monthly_cpayments_id!=''){
            echo $this->adopt(5);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function cancel_2(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->monthly_cpayments_id!=''){
            $res = $this->{$this->MODEL_NAME}->cancel($this->monthly_cpayments_id, 2,$this->NOTE_4);
            if(intval($res) <= 0){
                $this->print_error('لم تتم العملية'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function cancel_3(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->monthly_cpayments_id!=''){
            $res = $this->{$this->MODEL_NAME}->cancel($this->monthly_cpayments_id, 3,$this->NOTE_4);
            if(intval($res) <= 0){
                $this->print_error('لم تتم العملية'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function cancel_4(){
      die;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->monthly_cpayments_id!=''){
            $res = $this->{$this->MODEL_NAME}->cancel($this->monthly_cpayments_id, 4,$this->NOTE_4);
            if(intval($res) <= 0){
                $this->print_error('لم تتم العملية'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function cancel__4(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->monthly_cpayments_id!=''){
            if($this->NOTE_4=='')
            {
                $this->print_error('يجب إدخال سبب ارجاع الرقابة');
            }
            else {
                $res = $this->{$this->MODEL_NAME}->cancel($this->monthly_cpayments_id, -4, $this->NOTE_4);
                if (intval($res) <= 0) {
                    $this->print_error('لم تتم العملية' . '<br>' . $res);
                }
                echo 1;
            }
        }else
            echo "لم يتم ارسال رقم الطلب";
    }


    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['adopt_cons'] = $this->constant_details_model->get_list(186);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'MONTHLY_CPAYMENTS_ID','value'=>$this->monthly_cpayments_id ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_ID','value'=>$this->branch_id ,'type'=>'','length'=>-1),
            array('name'=>'PAYROLL_DATE','value'=>$this->payroll_date ,'type'=>'','length'=>-1),
            array('name'=>'DATE_FROM','value'=>$this->date_from ,'type'=>'','length'=>-1),
            array('name'=>'DATE_TO','value'=>$this->date_to ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}