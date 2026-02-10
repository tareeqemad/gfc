<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 26/10/14
 * Time: 11:37 ص
 */

class Checks_processing extends  MY_Controller{
    function  __construct(){
        parent::__construct();
        $this->load->model('checks_processing_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('treasury/convert_cash_bank_model');
        $this->load->model('financial/accounts_model');

        $this->load->model('root/rmodel');
    }

    function index($page = 1){
        $data['title']=' معالجة الشيكات ';
        $data['content']='checks_processing_index';
        $data['banks'] = $this->constant_details_model->get_list(9);

        $data['page']=$page;
        $this->load->view('template/template',$data);

    }

    function list_checks($page = 1){

        $data['title']=' حافظة الشيكات الواردة  ';
        $data['content']='list_checks_index';
        $data['banks'] = $this->constant_details_model->get_list(9);
        $data['page']=$page;
        $this->load->view('template/template',$data);
    }

    function _look_ups(&$data){

        add_css('combotree.css');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('jquery.hotkeys.js');
        $data['banks'] = $this->constant_details_model->get_list(9);
        $data['checks_processing_type'] = $this->constant_details_model->get_list(12);

        $this->rmodel->package = 'financial_pkg';
        $data['BkFin'] = $this->rmodel->getList('BANKS_FIN_CENTER_ST_TB_LIST', "  ", 0, 1000);
    }


    function create($id = ''){

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->_validate_data();

            $result = $this->checks_processing_model->create($this->_postedData());

            $this->_sp_adopt(json_decode($result)->id);

            echo $result;
            $this->Is_success($result);
            $this->return_json($result);

        }else{


            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');


            $data['title']=' معالجة الشيكات ';
            $data['content']='checks_processing_show';
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
            $this->checks_processing_model->adopt($checks_processing_id,2);
        }
    }

    /**
     * Update adopt ..
     */
    function edit($case = null){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_validate_data($case!=null);
            if($case != null)
                echo $this->checks_processing_model->edit($this->_postedData_Edit());
            else
                echo $this->checks_processing_model->update($this->_postedData_Update());

            $this->_sp_adopt($this->p_checks_processing_id);
        }
    }




    function _get_check_data($id,&$data){


        $result= $this->convert_cash_bank_model->get_bank_account_id($id);



        $Deposit = false;
        if(count($result) > 0){
            $Deposit = $result[0]['CHECKS_PROCESSING_DEBIT'] =='';
        }


        if(count($result) > 0 && intval($result[0]['CHECKS_PROCESSING_ID']) > 0 && intval($result[0]['CHECKS_PROCESSING_CASE']) == 1 )
            redirect("treasury/checks_processing/get/{$result[0]['CHECKS_PROCESSING_ID']}");

        $result = json_encode($result);

        $result=str_replace('subs','children',$result);
        $result=($Deposit) ?str_replace('TREASURY_ACCOUNT_ID_NAME','text',$result) :str_replace('CHECKS_PROCESSING_DEBIT_NAME','text',$result) ;
        $result=($Deposit) ?str_replace('TREASURY_ACCOUNT_ID','id',$result) :str_replace('CHECKS_PROCESSING_DEBIT','id',$result) ;


        $data['data']=$result;
    }

    /**
     * Update adopt ..
     */
    function adopt($page = 1){




        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $checks_processing_id= $this->input->post('checks_processing_id');

            echo $this->checks_processing_model->adopt($checks_processing_id,2);

        }else{

            $data['title']='إعتماد شيكات محصلة';
            $data['content']='checks_processing_index';

            $data['page']=$page;


            $this->load->view('template/template',$data);
        }

    }


    function get($id){

        $data['title']=' معالجة الشيكات ';

        $data['content']='checks_processing_show';

        $data['help']=$this->help;


        $result=$this->checks_processing_model->get($id);
        $this->date_format($result,array('CONVERT_CASH_BANK_ONE','CONVERT_CASH_BANK_DATE'));

        $data['checks_data']=$result;

        $data['can_edit'] = count($result) > 0 && $result[0]['CHECKS_PROCESSING_CASE'] == 1 && $result[0]['TREASURY_USER_ID'] == $this->user->id;

        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }


    function get_page($page = 1){

        $this->load->library('pagination');

        $sql = " AND BRANCHES = {$this->user->branch} ";
        $sql .= isset($this->p_check_id) && $this->p_check_id !=null ? " AND  CHECK_ID= {$this->p_check_id} " :"" ;
        $sql .= isset($this->p_bank) && $this->p_bank !=null ? " AND  BANK_ID = {$this->p_bank} " :"" ;

        $count_rs = $this->checks_processing_model->get_count($sql);

        $config['base_url'] = base_url('treasury/checks_processing/get_page');
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



        $result=$this->checks_processing_model->get_list($sql, $offset , $row);
        $this->date_format($result,array('CONVERT_CASH_BANK_ONE','CONVERT_CASH_BANK_DATE'));

        $data["checks"] =$result;

        $this->load->view('checks_processing_page',$data);

    }

    function get_checks_page($page = 1){

        $this->load->library('pagination');

        $sql = " AND I.BRANCHES = {$this->user->branch} ";

        $count_rs = $this->checks_processing_model->checks_count($sql);

        $config['base_url'] = base_url('treasury/checks_processing/list_checks');
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

        $result=$this->checks_processing_model->checks_list($sql, $offset , $row);
        $this->date_format($result,array('CHECK_DATE'));

        $data["rows"] =$result;

        $this->load->view('checks_list_all_show',$data);

    }

    function _postedData(){


        $checks_processing_type= $this->input->post('checks_processing_type');
        $convert_cash_bank_date =$this->p_convert_cash_bank_date;
        $checks_processing_id= $this->input->post('checks_processing_id');
        $check_id = $this->input->post('check_id');

        $convert_cash_bank_one =$this->p_convert_cash_bank_one;

        $check_value =  $this->input->post('check_value');
        $checks_processing_debit =  $this->input->post('checks_processing_debit');
        $checks_processing_credit=  $this->input->post('checks_processing_credit');
        $checks_processing_hints=  $this->input->post('checks_processing_hints');
        $check_currency=  $this->input->post('check_currency');



        $result = array(
            array('name'=>'CHECKS_PROCESSING_TYPE','value'=>$checks_processing_type ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_ID','value'=>$check_id,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_BANK_ONE','value'=>$convert_cash_bank_one,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_BANK_DATE','value'=>$convert_cash_bank_date,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_DEBIT','value'=>$checks_processing_debit,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_CREDIT','value'=>$checks_processing_credit,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_HINTS','value'=>$checks_processing_hints,'type'=>'','length'=>-1),
            array('name'=>'TREASURY_USER_ID','value'=>$this->user->id,'type'=>'','length'=>-1),
            array('name'=>'TREASURY_USER_TRANS','value'=>null,'type'=>'','length'=>-1),
            array('name'=>'TREASURY_USER_TRANS_DATE','value'=>null,'type'=>'','length'=>-1),
            array('name'=>'CHECK_VALUE','value'=>$check_value,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_CASE','value'=>1,'type'=>'','length'=>-1),
            array('name'=>'CHECK_CURRENCY','value'=>$check_currency,'type'=>'','length'=>-1),
            array('name'=>'BANK_ID','value'=>$this->p_bank_id,'type'=>'','length'=>-1),
            array('name'=>'CHECK_PORTFOLIO','value'=>$this->p_check_portfolio,'type'=>'','length'=>-1),
            array('name'=>'BANK_CASH_NO','value'=>$this->p_bank_cash_no,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_TRUE_DATE','value'=>$this->p_convert_cash_true_date,'type'=>'','length'=>-1),
            array('name'=>'BK_FIN_ID','value'=>$this->p_bk_fin_id,'type'=>'','length'=>-1),
        );


        return $result;
    }
    function _postedData_Update(){


        $result = array(

            array('name'=>'CHECKS_PROCESSING_ID','value'=>$this->p_checks_processing_id ,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_DEBIT','value'=>$this->p_checks_processing_debit,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_BANK_DATE','value'=>$this->p_convert_cash_bank_date,'type'=>'','length'=>-1),
            array('name'=>'BANK_CASH_NO','value'=>$this->p_bank_cash_no,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_HINTS','value'=>$this->p_checks_processing_hints,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_TRUE_DATE','value'=>$this->p_convert_cash_true_date,'type'=>'','length'=>-1),
            array('name'=>'BK_FIN_ID','value'=>$this->p_bk_fin_id,'type'=>'','length'=>-1),
        );

        return $result;
    }

    function _postedData_Edit(){


        $result = array(
            array('name'=>'CHECKS_PROCESSING_TYPE','value'=>$this->p_checks_processing_type ,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_ID','value'=>$this->p_checks_processing_id ,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_DEBIT','value'=>$this->p_checks_processing_debit,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_CREDIT','value'=>$this->p_checks_processing_credit,'type'=>'','length'=>-1),
            array('name'=>'CHECKS_PROCESSING_HINTS','value'=>$this->p_checks_processing_hints,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_BANK_DATE','value'=>$this->p_convert_cash_bank_date,'type'=>'','length'=>-1),
            array('name'=>'CONVERT_CASH_TRUE_DATE','value'=>$this->p_convert_cash_true_date,'type'=>'','length'=>-1),

        );

        return $result;
    }
    function  _validate_data($create = true){



        if($this->FIN_YEAR == $this->year){
            $exp_date = str_replace('/', '-', $this->p_convert_cash_bank_date);
            if((strtotime($this->today) < strtotime($exp_date) ) || (strtotime($this->prev_year) > strtotime($exp_date)))
                $this->print_error('تاريخ السند غير صحيح ؟!');
        }


        if($create)
            if(!$this->accounts_model->isAccountExists($this->p_checks_processing_credit))
                $this->print_error('رقم الحساب غير صحيح'.' ('.$this->p_checks_processing_credit.')');

        if(!$this->accounts_model->isAccountExists($this->p_checks_processing_debit))
            $this->print_error('رقم الحساب غير صحيح'.' ('.$this->p_checks_processing_debit.')');

		  if(!isset($this->p_bk_fin_id) || $this->p_bk_fin_id == null) {
            $this->print_error('يجب اختيار المقبوضات و المدفوعات');
        }
    }

    function cancel($check_id , $pid ){

        $result= $this->checks_processing_model->cancel($check_id,$pid);
         redirect('payment/checks_portfolio/index?type=1');


    }


}