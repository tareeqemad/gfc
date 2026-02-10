<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 26/10/14
 * Time: 11:37 ص
 */

class Out_checks_processing extends  MY_Controller{
    function  __construct(){
        parent::__construct();
        $this->load->model('out_checks_processing_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('treasury/convert_cash_bank_model');

        $this->load->model('root/rmodel');
    }

    function index($page = 1){
        $data['title']='معالجة الشيكات الصادر ';
        $data['content']='out_checks_processing_index';

        $data['page']=$page;
        $this->load->view('template/template',$data);

    }


    function _look_ups(&$data){

        add_css('combotree.css');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('jquery.hotkeys.js');

        $data['out_checks_processing_type'] = $this->constant_details_model->get_list(38);

        $this->rmodel->package = 'financial_pkg';
        $data['BkFin'] = $this->rmodel->getList('BANKS_FIN_CENTER_ST_TB_LIST', "  ", 0, 1000);
    }


    function create($id = ''){

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result = $this->out_checks_processing_model->create($this->_postedData());
            $this->_sp_adopt($result);

            echo $result;

        }else{


            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');


            $data['title']=' معالجة الشيكات ';
            $data['content']='out_checks_processing_show';
            $data['help']=$this->help;

            $data['seq_id'] = $id;
            $this->_get_check_data($id,$data);
            $this->_look_ups($data);

            $this->load->view('template/template',$data);
        }
    }


    /**
     * Spacial Adopt data
     */
    function _sp_adopt($checks_processing_id){

        if($this->user->id == 551){
            $this->out_checks_processing_model->adopt($checks_processing_id);
        }
    }

    function edit($case = null){


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $result = $this->out_checks_processing_model->edit($this->_postedDataEdit($case));
            $this->_sp_adopt($this->p_checks_processing_payment_id);
            echo $result;

        }
    }


    function _get_check_data($id,&$data){


        $result= $this->out_checks_processing_model->pay_checks_account_id($id);

        $Deposit = false;
        if(count($result) > 0){
            $Deposit = $result[0]['CHECKS_PROCESSING_DEBIT'] =='';
        }

        if(count($result) > 0 && intval($result[0]['CP_ID']) > 0 && intval($result[0]['CHECKS_CASE']) == 1 )
            redirect("payment/out_checks_processing/get/{$result[0]['CP_ID']}");


        $result = json_encode($result);

        $result=str_replace('subs','children',$result);
        $result=($Deposit) ?str_replace('PAYMENT_ACCOUNT_ID_NAME','text',$result) :str_replace('CHECKS_PROCESSING_DEBIT_NAME','text',$result) ;
        $result=($Deposit) ?str_replace('FIANANCIAL_PAYMENT_ACCOUNT_ID','id',$result) :str_replace('CHECKS_PROCESSING_DEBIT','id',$result) ;

        $data['data']=$result;
    }

    /**
     * Update adopt ..
     */
    function adopt($page = 1){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rs = $this->out_checks_processing_model->adopt($this->p_checks_processing_id);
            echo $rs;
            $this->Is_success($rs);

        }else{

            $data['title']='إعتماد شيكات محصلة';
            $data['content']='out_checks_processing_index';
            $data['page']=$page;
            $this->load->view('template/template',$data);
        }

    }


    function get($id){

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['title']=' معالجة الشيكات ';

        $data['content']='out_checks_processing_show';

        $data['help']=$this->help;

        $result=$this->out_checks_processing_model->get($id);
        $this->date_format($result,array('CHECKS_PROCESSING_PAYMENT_DATE','CHECK_BANK_WARNING'));
        $data['checks_data']=$result;
        $data['data']='[]';

        $data['can_edit'] = count($result) > 0 && $result[0]['CHECKS_PROCESSING_CASE'] == 1 && $result[0]['TREASURY_USER_ID'] == $this->user->id;

        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }


    function get_page($page = 1){

        $this->load->library('pagination');


        $count_rs = $this->out_checks_processing_model->get_count(null);

        $config['base_url'] = base_url('payment/out_checks_processing/index');
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

        $result=$this->out_checks_processing_model->get_list(null, $offset , $row);
        $this->date_format($result,array('CONVERT_CASH_BANK_ONE','CONVERT_CASH_BANK_DATE'));

        $data["checks"] =$result;

        $this->load->view('out_checks_processing_page',$data);

    }


    function _postedData(){



        $result = array(
            array('name'=>'CHECKS_PROCESSING_PAYMENT_TYPE','value'=>$this->p_checks_processing_payment_type ,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_SOURCE','value'=>$this->p_checks_source,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_SOURCE_ID','value'=>$this->p_checks_source_id,'type'=>'','length'=>-1),
            array('name'=>'CHECK_ID','value'=>$this->p_check_id,'type'=>'','length'=>-1),
            array('name'=>'CHECK_BANK_WARNING','value'=>$this->p_check_bank_warning,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_PAY_DATE','value'=>$this->p_checks_processing_payment_date,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_DEBIT','value'=>$this->p_checks_processing_debit,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_CREDIT','value'=>$this->p_checks_processing_credit,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_HINTS','value'=>$this->p_checks_processing_hints,'type'=>'','length'=>-1),
            array('name'=>'CHECK_VALUE','value'=>$this->p_check_value,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_CASE','value'=>1,'type'=>'','length'=>-1),
            array('name'=>'CHECK_PORTFOLIO','value'=>$this->p_check_portfolio,'type'=>'','length'=>-1),
            array('name'=>'CHECK_CURRENCY','value'=>$this->p_check_currency,'type'=>'','length'=>-1),
            array('name'=>'BK_FIN_ID','value'=>$this->p_bk_fin_id,'type'=>'','length'=>-1),
        );

        return $result;
    }

    function _postedDataEdit($type = null){



        $result = array(
            array('name'=>'CHECKS_CASE','value'=>$type ,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_PAY_ID','value'=>$this->p_checks_processing_payment_id,'type'=>'','length'=>-1),
            array('name'=>'CHECK_ID','value'=>$this->p_check_id,'type'=>'','length'=>-1),
            array('name'=>'CHECK_BANK_WARNING','value'=>$this->p_check_bank_warning,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_PAY_DATE','value'=>$this->p_checks_processing_payment_date,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_CREDIT','value'=>$this->p_checks_processing_credit,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_HINTS','value'=>$this->p_checks_processing_hints,'type'=>'','length'=>-1),
            array('name'=>'CHECK_VALUE','value'=>$this->p_check_value,'type'=>'','length'=>-1),
            array('name'=>'BK_FIN_ID','value'=>$this->p_bk_fin_id,'type'=>'','length'=>-1),

        );

        return $result;
    }

    function check_account_debit_id($type,$account_id){

        $result= $this->constant_details_model->get(12,$type);

        foreach($result as $ac){
            if($ac['ACCOUNT_ID'] == $account_id){

                return;
            }
        }
        $this->print_error('البيانات الحسابات غير مطابقة!!');
    }


    function check_account_credit_id($check_id,$account_id){

        $result= $this->convert_cash_bank_model->get_bank_account_id($check_id);

        foreach($result as $ac){
            if($ac['ACOUNT_ID'] == $account_id){

                return;
            }
        }
        $this->print_error('البيانات الحسابات غير مطابقة!!');
    }

    function cancel($check_id , $pid ){

        $result= $this->out_checks_processing_model->cancel($check_id,$pid);

        redirect('payment/checks_portfolio/index?type=2');

    }


}