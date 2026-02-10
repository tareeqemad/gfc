<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 08:09 ص
 */
class Financial_mqasah extends MY_Controller{
    var $curr_id;
    var $title;
    function  __construct(){
        parent::__construct();
        $this->load->model('financial_mqasah_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('accounts_model');

        if($this->input->get('type'))
            if($this->q_type){
                switch($this->q_type){
                    case 4:$this->title = ' القيد المالي العام ';break;
                    case 12: $this->title ='قيد مقاصة ';break;

                }

            }

    }

    function index($page = 1){

        $data['title']=' إعداد '.$this->title;
        $data['content']='financial_mqasah_index';

        $data['page']=$page;

        $data['case']=1;
        $data['type']=12;
        $data['action'] = 'index';

        $this->load->view('template/template',$data);
    }


    function archive($page = 1){

        $data['title']='أرشيف قيود المقاصة  ';

        $data['content']='financial_mqasah_archive';

        $data['page']=$page;

        add_js('jquery.hotkeys.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template',$data);

    }

    function look_ups(&$data){
        add_css('combotree.css');

        add_js('jquery.hotkeys.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['title']=$this->title;
        $data['content']='financial_mqasah_show';
        $data['help']=$this->help;
        $data['mqasah_type'] = $this->constant_details_model->get_list(34);
        $data['currency'] =  $this->currency_model->get_all();

    }

    function get($id,$action = 'index'){

        $result=$this->financial_mqasah_model->get($id);

        $this->date_format($result,'FINANCIAL_CHAINS_DATE');

        $data['chains_data']=$result;

        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $result[0]['FINANCIAL_CHAINS_CASE'] == 1 && $action == 'index')?true : false : false;

        $data['action'] = $action;

        $data['type']=count($result) > 0? $result[0]['FIANANCIAL_CHAINS_SOURCE']:0;

        $this->look_ups($data);

        $this->load->view('template/template',$data);
    }

    function create(){


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $debit_sum = 0;
            $credit_sum = 0;
            $this->curr_id= $this->input->post('curr_id');
            $account_type = $this->input->post('account_type');
            $account_id = $this->input->post('account_id');
            $debit = $this->input->post('debit');
            $credit = $this->input->post('credit');
            $hints =  $this->input->post('dhints');

            $this->_validate_data();


            for($i = 0;$i<count($account_id);$i++){



                $debit_sum +=$debit[$i];
                $credit_sum +=$credit[$i];

                if(($debit[$i] <=0 && $credit[$i] <=0)){

                    $this->print_error('القيد غير متزن');
                }
            }

            if(''.$debit_sum != ''.$credit_sum){

                $this->print_error('القيد غير متزن');
            }

            $account_id = array_filter($account_id);
            $account_type = array_filter($account_type);
            if(count($account_type) != count($account_id) || count($account_id) !=count($debit)){
                $this->print_error('يجب إدخال جميع البيانات');
            }

            $id = $this->financial_mqasah_model->create($this->_postedData($this->q_type));


            if(intval($id) <= 0){
                $this->print_error('فشل في حفظ القيد ؟!');
            }

            for($i = 0;$i<count($account_id);$i++){
                $this->financial_mqasah_model->create_details($this->_postDetailsData($id,$account_type[$i],$account_id[$i],$debit[$i],$credit[$i],$hints[$i]));

            }

        }else{


            $this->look_ups($data);
            $data['action'] = 'index';
            $data['type']=$this->q_type;;
            $this->load->view('template/template',$data);
        }
    }

    function edit(){


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $debit_sum = 0;
            $credit_sum = 0;
            $this->curr_id= $this->input->post('curr_id');
            $account_type = $this->input->post('account_type');
            $account_id = $this->input->post('account_id');
            $debit = $this->input->post('debit');
            $credit = $this->input->post('credit');
            $hints =  $this->input->post('dhints');

            $id= $this->input->post('financial_chains_id');
            $financial_chains_seq = $this->input->post('financial_chains_seq');

            $this->_validate_data();


            for($i = 0;$i<count($account_id);$i++){


                $debit_sum +=$debit[$i];
                $credit_sum +=$credit[$i];

                if(($debit[$i] <=0 && $credit[$i] <=0)){

                    $this->print_error('القيد غير متزن');
                }
            }

            if(''.$debit_sum != ''.$credit_sum){

                $this->print_error('القيد غير متزن');
            }

            $account_id = array_filter($account_id);
            $account_type = array_filter($account_type);

            if(count($account_type) != count($account_id) || count($account_id) !=count($debit)){
                $this->print_error('يجب إدخال جميع البيانات');
            }

            $result = $this->financial_mqasah_model->edit($this->_postedData_update());


            for($i = 0;$i<count($financial_chains_seq);$i++){

                if($financial_chains_seq[$i] == null || $financial_chains_seq[$i] == ''){

                    $this->financial_mqasah_model->create_details($this->_postDetailsData($id,$account_type[$i],$account_id[$i],$debit[$i],$credit[$i],$hints[$i]));
                } else {

                    $this->financial_mqasah_model->edit_details($this->_postDetailsData_Update($id,$account_type[$i],$account_id[$i],$debit[$i],$credit[$i],$hints[$i],$financial_chains_seq[$i]));
                }

            }

            echo $result ;

        }
    }

    /**
     * Update adopt ..
     */
    function adopt($page = 1){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id= $this->input->post('financial_chains_id');
            echo $this->financial_mqasah_model->adopt($id,2);


        }else{

            $data['title']='إعتماد '.$this->title;
            $data['content']='financial_mqasah_index';

            $data['page']=$page;
            $data['case']=1;
            $data['action'] = 'adopt';
            $data['type']=$this->q_type;

            $this->load->view('template/template',$data);
        }
    }

    /**
     * Update review ..
     */
    function review($page = 1){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id= $this->input->post('financial_chains_id');
            echo $this->financial_mqasah_model->adopt($id,3);

        }else{

            $data['title']='تدقيق '.$this->title;
            $data['content']='financial_mqasah_index';

            $data['page']=$page;
            $data['case']=2;
            $data['action'] = 'review';
            $data['type']=$this->q_type;
            $this->load->view('template/template',$data);
        }
    }


    /**
     *  return to lower case ..
     */
    function public_return(){

        $id= $this->input->post('financial_chains_id');

        echo $this->financial_mqasah_model->adopt($id,1);
    }

    function public_get_details($id = 0,$can_edit =false){

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['chains_details'] = $this->financial_mqasah_model->get_details_all($id);
        $data['can_edit'] = $can_edit;
        $this->load->view('financial_mqasah_page',$data);
    }

    function get_page($page = 1,$case = 1,$type  = 0){


        $sql = isset($this->p_id) && $this->p_id !=null ? " AND FINANCIAL_CHAINS_ID ={$this->p_id} " : '';
        $sql =$sql.(isset($this->p_date_from) && $this->p_date_from !=null ? " AND FINANCIAL_CHAINS_DATE >= '{$this->p_date_from}' " : '');
        $sql =$sql.(isset($this->p_date_to) && $this->p_date_to !=null ? " AND FINANCIAL_CHAINS_DATE <= '{$this->p_date_to}' " : '');
        $sql =$sql.(isset($this->p_account_id) && $this->p_account_id !=null ? " AND FINANCIAL_CHAINS_ID IN (SELECT FINANCIAL_CHAINS_ID FROM FINANCIAL_CHAINS_DETAIL_TB WHERE ACCOUNT_ID='{$this->p_account_id}' OR ROOT_ACCOUNT_ID ='{$this->p_account_id}' ) " : '');
        $sql =$sql.(isset($this->p_source) && $this->p_source !=null ? " AND FIANANCIAL_CHAINS_SOURCE = {$this->p_source} " : '');
        $sql =$sql.(isset($this->p_entry_user) && $this->p_entry_user!=null ? " AND ENTRY_USER IN (SELECT ID FROM USERS_PROG_TB WHERE USER_NAME LIKE '%{$this->p_entry_user}%') " : '');

        $sql =$sql.(isset($this->p_hints) && $this->p_hints!=null ? " AND HINTS  LIKE '%{$this->p_hints}%'  " : '');


        $case = isset($this->p_case)?$this->p_case : $case;
        $case_prev = $case;
        $case = $case +1;

        $this->load->library('pagination');

        $count_rs = $this->financial_mqasah_model->get_count(" AND (U.BRANCH = {$this->user->branch} ) and FINANCIAL_CHAINS_CASE BETWEEN {$case_prev} AND {$case}  {$sql} ");

        $config['base_url'] = base_url('financial/financial_mqasah/get_page');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] =count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
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

        $result=$this->financial_mqasah_model->get_list(" where  (U.BRANCH = {$this->user->branch}) {$sql} and FINANCIAL_CHAINS_CASE BETWEEN {$case_prev} AND {$case} ", $offset , $row);

        $this->date_format($result,'FINANCIAL_CHAINS_DATE');

        $data["chains"] =$result;

        $data['action'] =isset($this->p_action) && $this->p_action != null ? $this->p_action : $this->action;

        $data['type']=$type;

        $this->load->view('financial_mqasah_rows_page',$data);

    }


    function get_page_archive($page = 1){

        $sql ='';


        $sql = isset($this->p_id) && $this->p_id !=null ? " AND FINANCIAL_CHAINS_ID ={$this->p_id} " : '';
        $sql =$sql.(isset($this->p_date_from) && $this->p_date_from !=null ? " AND FINANCIAL_CHAINS_DATE >= '{$this->p_date_from}' " : '');
        $sql =$sql.(isset($this->p_date_to) && $this->p_date_to !=null ? " AND FINANCIAL_CHAINS_DATE <= '{$this->p_date_to}' " : '');
        $sql =$sql.(isset($this->p_account_id) && $this->p_account_id !=null ? " AND FINANCIAL_CHAINS_ID IN (SELECT FINANCIAL_CHAINS_ID FROM FINANCIAL_CHAINS_DETAIL_TB WHERE ACCOUNT_ID='{$this->p_account_id}' OR ROOT_ACCOUNT_ID ='{$this->p_account_id}' ) " : '');
        $sql =$sql.(isset($this->p_source) && $this->p_source !=null ? " AND FIANANCIAL_CHAINS_SOURCE = {$this->p_source} " : '');
        $sql =$sql.(isset($this->p_entry_user) && $this->p_entry_user!=null ? " AND ENTRY_USER IN (SELECT ID FROM USERS_PROG_TB WHERE USER_NAME LIKE '%{$this->p_entry_user}%') " : '');

        $sql =$sql.(isset($this->p_hints) && $this->p_hints!=null ? " AND HINTS  LIKE '%{$this->p_hints}%'  " : '');

        $this->load->library('pagination');

        $count_rs = $this->financial_mqasah_model->get_count(" AND (U.BRANCH = {$this->user->branch} or {$this->user->branch} = 1 )    {$sql} ");

        $config['base_url'] = base_url('financial/financial_mqasah/get_page_archive');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] =count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
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

        $result=$this->financial_mqasah_model->get_list(" where  (U.BRANCH = {$this->user->branch} or {$this->user->branch} = 1) {$sql}  ", $offset , $row);

        $this->date_format($result,'FINANCIAL_CHAINS_DATE');

        $data["chains"] =$result;

        $data['action'] =$this->action;

        $data['action'] ='index';
        $data['offset']=$offset+1;
        $this->load->view('financial_mqasah_rows_page',$data);

    }

    function _postedData($type,$create = null){


        $financial_chains_id= $this->input->post('financial_chains_id');

        $payment_id = $this->input->post('payment_id');

        $curr_value =  $this->input->post('curr_value');
        $check_id =  $this->input->post('check_id');
        $hints =  $this->input->post('hints');

        $result = array(
            array('name'=>'FINANCIAL_CHAINS_ID','value'=>$financial_chains_id ,'type'=>'','length'=>-1),
            array('name'=>'FINANCIAL_CHAINS_DATE','value'=>$this->p_financial_chains_date ,'type'=>'','length'=>-1),
            array('name'=>'ENTRY_USER','value'=>$this->user->id,'type'=>'','length'=>-1),
            array('name'=>'FIANANCIAL_CHAINS_SOURCE','value'=>12,'type'=>'','length'=>-1),
            array('name'=>'PAYMENT_ID','value'=>$payment_id,'type'=>'','length'=>-1),
            array('name'=>'FINANCIAL_CHAINS_CASE','value'=>1,'type'=>'','length'=>-1),
            array('name'=>'CURR_ID','value'=>$this->curr_id,'type'=>'','length'=>-1),
            array('name'=>'CURR_VALUE','value'=>$curr_value,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$hints,'type'=>'','length'=>-1),
            array('name'=>'AUDIT_DATE','value'=>null,'type'=>'','length'=>-1),
            array('name'=>'AUDIT_USER','value'=>null,'type'=>'','length'=>-1),
            array('name'=>'TRANS_DATE','value'=>null,'type'=>'','length'=>-1),
            array('name'=>'TRANS_USER','value'=>null,'type'=>'','length'=>-1),
            array('name'=>'CHECK_ID','value'=>$check_id,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->p_notes,'type'=>'','length'=>-1),
            array('name'=>'MQASAH_TYPE','value'=>$this->p_mqasah_type,'type'=>'','length'=>-1),
        );

        if($create == null){
            array_shift($result);
        }

        return $result;
    }

    function _postedData_update(){


        $financial_chains_id= $this->input->post('financial_chains_id');

        $payment_id = $this->input->post('payment_id');

        $curr_value =  $this->input->post('curr_value');
        $check_id =  $this->input->post('check_id');
        $hints =  $this->input->post('hints');

        $result = array(
            array('name'=>'FINANCIAL_CHAINS_ID','value'=>$financial_chains_id ,'type'=>'','length'=>-1),
            array('name'=>'FINANCIAL_CHAINS_DATE','value'=>$this->p_financial_chains_date ,'type'=>'','length'=>-1),
            array('name'=>'FIANANCIAL_CHAINS_SOURCE','value'=>12,'type'=>'','length'=>-1),
            array('name'=>'PAYMENT_ID','value'=>$payment_id,'type'=>'','length'=>-1),
            array('name'=>'FINANCIAL_CHAINS_CASE','value'=>1,'type'=>'','length'=>-1),
            array('name'=>'CURR_VALUE','value'=>$curr_value,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$hints,'type'=>'','length'=>-1),
            array('name'=>'CHECK_ID','value'=>$check_id,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->p_notes,'type'=>'','length'=>-1),
            array('name'=>'MQASAH_TYPE','value'=>$this->p_mqasah_type,'type'=>'','length'=>-1),
        );



        return $result;
    }

    function  _postDetailsData($id,$type,$account,$debit,$credit,$hints,$create =null,$Did = null){

        $result = array(
            array('name'=>'FINANCIAL_chains_SEQ','value'=>$Did ,'type'=>'','length'=>-1),
            array('name'=>'FINANCIAL_chains_ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_TYPE','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$account,'type'=>'','length'=>-1),
            array('name'=>'DEBIT','value'=>$debit,'type'=>'','length'=>-1),
            array('name'=>'CREDIT','value'=>$credit,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$hints,'type'=>'','length'=>-1),

        );

        if($create == null){
            array_shift($result);
        }

        return $result;

    }

    function  _postDetailsData_Update($id,$type,$account,$debit,$credit,$hints,$Did = null){

        $result = array(
            array('name'=>'FINANCIAL_CHAINS_SEQ','value'=>$Did ,'type'=>'','length'=>-1),

            array('name'=>'ACCOUNT_TYPE','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$account,'type'=>'','length'=>-1),
            array('name'=>'DEBIT','value'=>$debit,'type'=>'','length'=>-1),
            array('name'=>'CREDIT','value'=>$credit,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$hints,'type'=>'','length'=>-1),

        );

        return $result;

    }

    function accounts_validation($account,$curr_id){

        $result = $this->accounts_model->get($account);
        $result = count($result) > 0 ?$result[0] :null;

        // if($result['CURR_ID'] != $curr_id)
        //     $this->print_error('عملة القيد تختلف عن عملة الحسابات ؟!!');

    }

    function  _validate_data(){

        $exp_date = str_replace('/', '-', $this->p_financial_chains_date);
        if((strtotime($this->today) < strtotime($exp_date) ) || (strtotime($this->prev_year) > strtotime($exp_date)))
            $this->print_error('تاريخ القيد غير صحيح ؟!');

        for($i = 0;$i<count($this->p_account_id);$i++){

            if($this->p_account_type[$i] == 1)
                if(!$this->accounts_model->isAccountExists($this->p_account_id[$i]))
                    $this->print_error('رقم الحساب غير صحيح'.' ('.$this->p_account_id[$i].')');
        }

    }

    function  public_get_balance($id = 0){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        echo $this->financial_mqasah_model->get_balance($id);
    }

    function delete($id = 0){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        echo $this->financial_mqasah_model->delete($id);
    }

    function cancel(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            echo $this->financial_mqasah_model->adopt($this->p_id,0);

        }
    }
}