<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 18/10/14
 * Time: 08:08 ص
 */ class Convert_cash extends  MY_Controller{

    function  __construct(){
        parent::__construct();
        $this->load->model('convert_cash_model');
        $this->load->model('income_voucher_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/users_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('financial/accounts_permission_model');
    }

    function index($page = 1){
        $data['title']=' ترحيل مبالغ محصلة للخزينة ';
        $data['content']='convert_cash_index';

        $data['page']=$page;

        $this->load->view('template/template',$data);
    }

    function look_ups(&$data){

        add_css('combotree.css');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('jquery.hotkeys.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['cash_type'] = $this->constant_details_model->get_list(4);
        $data['money_received'] = $this->constant_details_model->get_list(10);
        $income_type =  $this->constant_details_model->get_list(4);
        array_pop($income_type);
        $data['income_type'] =$income_type ;

        $data['fund_prefix'] =  $this->get_system_info('FUND_PREFIX','1');
        $data['treasure_prefix'] =  $this->get_system_info('TREASURE_PREFIX','1');
        $data['deficit_surplus'] =  $this->system_info_model->get_account_id(1);

    }


    function create(){

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result = $this->convert_cash_model->create($this->_postedData());
            $this->Is_success($result);
            $this->return_json($result);

        }else{


            $data['title']='ترحيل مبالغ محصلة إلي الخزينة ';
            $data['content']='convert_cash_show';
            $data['help']=$this->help;

            $data['users'] =$this->users_model->teller_id();

            $this->look_ups($data);

            $this->load->view('template/template',$data);
        }
    }


    function get($id,$action = 'index'){

        $data['title']='ترحيل مبالغ محصلة إلي الخزينة ';

        $data['content']='convert_cash_show';

        $data['help']=$this->help;


        $result=$this->convert_cash_model->get($id);
        $this->date_format($result,'CONVERT_CASH_DATE');

        $data['cash_data']=$result;


        $data['users'] =$this->users_model->teller_id();

        $data['can_delete'] =  count($result) > 0 ? ($result[0]['TREASURY_USER_ID'] == $this->user->id && $result[0]['CONVERT_CASH_CASE'] == 1) : false;

        $data['action'] = $action;

        $this->look_ups($data);

        $this->load->view('template/template',$data);
    }


    /**
     * Update adopt ..
     */
    function adopt($page = 1){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $convert_cash_id= $this->input->post('convert_cash_id');
            echo $this->convert_cash_model->adopt($convert_cash_id,2);

        }else{

            $data['title']='اعتماد مبالغ محصلة للخزينة ';
            $data['content']='convert_cash_index';

            $data['page']=$page;


            $this->load->view('template/template',$data);
        }
    }

    function get_page($page = 1){

        $this->load->library('pagination');


        $data['adopt']=$this->action == 'adopt' ? true :false;

        $sql = $data['adopt']?'':" AND TREASURY_USER_ID = {$this->user->id} ";

        $sql = $sql." AND BRANCHES = {$this->user->branch} ";

        $count_rs = $this->convert_cash_model->get_count($sql);

        $config['base_url'] = base_url('treasury/convert_cash/'.$this->action);
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

        $result=$this->convert_cash_model->get_list($sql, $offset , $row);
        $this->date_format($result,'CONVERT_CASH_DATE');

        $data["cashes"] =$result;

        $data['adopt'] =$this->action;


        $this->load->view('convert_cash_page',$data);

    }

    function public_get_sum($id = 0,$usid =0){

        $id= $this->input->post('id') ? $this->input->post('id') : $id;
        $usid= $this->input->post('usid') ? $this->input->post('usid') : $usid;

        $this->p_date = isset($this->p_date)?$this->p_date:null;

        $result=   $this->convert_cash_model->get_debit_sum($usid,$id,$this->p_date);
        //array_push($result, $this->convert_cash_model->get_debit_sum($usid,$id,1));

        $result = json_encode($result);

        echo $result;
    }

    /**
     * returns checks list ..
     */
    function public_check_list(){

        $result=  $this->convert_cash_model->get_checks_list($this->p_account_id,$this->p_entry_user,$this->p_date);
        $this->date_format($result,'CONVERT_CASH_DATE');
        $data['rows'] = $result;
        $this->load->view('checks_list_show',$data);
    }

    function _postedData(){


        $telar_id= $this->input->post('telar_id');
        $convert_cash_date =date($this->SERVER_DATE_FORMAT);//DateTime::createFromFormat('d/m/Y', $this->input->post('convert_cash_date'))->format('d-M-Y');

        $debit_account_id= $this->input->post('debit_account_id');
        $convert_cash_type = $this->input->post('convert_cash_type');

        $treasury_account_id =  $this->input->post('treasury_account_id');
        $convert_cash_hint =  $this->input->post('convert_cash_hint');
        $convert_cash_total =  $this->input->post('convert_cash_total');
        $money_received_id=  $this->input->post('money_received_id');
        $convert_cash_total_account_id= (intval($money_received_id)  == 1)? null : $this->input->post('convert_cash_total_account_id');
        $convert_cash_total_dc=(intval($money_received_id)  == 1)? null :  $this->input->post('convert_cash_total_dc');
        $convert_cash_bank_id=  $this->input->post('convert_cash_bank_id');

        $convert_cash_id= $this->input->post('convert_cash_id');

        //----------------------------------

        $convert_cash_case = 1;
        $treasury_user_trans =null;
        $treasury_user_trans_date = null;


        $this->check_telair_debit_account($this->get_system_info('FUND_PREFIX','1'),$convert_cash_type,null,$telar_id,1,null,$debit_account_id);

        $this->check_telair_debit_account($this->get_system_info('TREASURE_PREFIX','1'),$convert_cash_type,null,$this->user->id,null,$debit_account_id,$treasury_account_id);

        $this->check_totals($telar_id,$debit_account_id,$convert_cash_total_dc,$convert_cash_total);


        $result = array(
            array('name'=>'CONVERT_CASH_TYPE','value'=>$convert_cash_type ,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_DATE','value'=>$convert_cash_date,'type'=>'','length'=>-1),
            array('name'=>'TELAR_ID','value'=>$telar_id,'type'=>'','length'=>-1),
            array('name'=>'TREASURY_ACCOUNT_ID','value'=>$treasury_account_id,'type'=>'','length'=>-1),
            array('name'=>'TREASURY_USER_ID','value'=>$this->user->id,'type'=>'','length'=>-1),
            array('name'=>'TREASURY_USER_TRANS','value'=>$treasury_user_trans,'type'=>'','length'=>-1),
            array('name'=>'TREASURY_USER_TRANS_DATE','value'=>$treasury_user_trans_date,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_HINT','value'=>$convert_cash_hint,'type'=>'','length'=>-1),
            array('name'=>'MONEY_RECEIVED_ID','value'=>$money_received_id,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_TOTAL','value'=>$convert_cash_total,'type'=>'','length'=>-1),
            array('name'=>'TOTAL_ACCOUNT_ID','value'=>$convert_cash_total_account_id,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_TOTAL_DC','value'=>$convert_cash_total_dc,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_BANK_ID','value'=>$convert_cash_bank_id,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_CASE','value'=>$convert_cash_case,'type'=>'','length'=>-1),
            array('name'=>'DEBIT_ACCOUNT_ID','value'=>$debit_account_id,'type'=>'','length'=>-1),
            array('name'=>'VOUCHER_DATE','value'=>$this->p_voucher_date,'type'=>'','length'=>-1)
        );

        return $result;
    }

    function  check_telair_debit_account($prefix,$type,$curr_id,$user,$case = null,$account_id = null,$debit_account_id){

        //$prefix,$type,$curr_id,$user,$case,$account_id
        $result= $this->accounts_permission_model->get_user_accounts($prefix,$type,$curr_id,$user,$case,$account_id);
        foreach($result as $ac){
            if($ac['ACOUNT_ID'] == $debit_account_id){

                return;
            }
        }

        $this->print_error('بيانات الحسابات غير متطابقة مع بيانات المحصل!!');
    }

    function check_totals($telar_id,$id,$convert_cash_total_dc,$val){

        $this->p_voucher_date = isset($this->p_voucher_date)?$this->p_voucher_date:null;
        $debit_sum= $this->convert_cash_model->get_debit_sum($telar_id,$id,$this->p_voucher_date)[0]['SM2'];
        $_sub = $debit_sum - $val;
        $_sub = $_sub < 0? ($_sub * -1) :$_sub;

        if($_sub != $convert_cash_total_dc)
            $this->print_error('البيانات المدخلة غير متطابقة مع بيانات المحصل!!');
    }

    function delete(){

        echo $this->convert_cash_model->delete($this->p_convert_cash_id);

    }

    function cancel(){

        echo $this->convert_cash_model->cancel($this->p_convert_cash_id);

    }


    /***
     * تقوم بإرجاع اجمالي المبلغ سندات القبض كل حسب نوع القبض
     */
    function public_income_voucher_income_type_get(){

        $data['rows'] = $this->income_voucher_model->income_voucher_income_type_get($this->p_telar_id,$this->p_voucher_date,$this->p_debit_account_id);

        $this->load->view('vouchers_types_credits',$data);

    }
}