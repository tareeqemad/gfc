<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 30/12/14
 * Time: 04:00 م
 */

class Checks_portfolio extends MY_Controller{

    function  __construct(){
        parent::__construct();

        $this->load->model('checks_portfolio_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
    }

    function index($page = 1){

        $data['title']=$this->q_type == 1 ? ' حافظة الشيكات الواردة  ' : 'حافظة الشيكات الصادرة';
        $data['content']='checks_portfolio_index';
        $data['banks'] = $this->constant_details_model->get_list(9);
        $data['page']=$page;
        $data['type'] = $this->q_type;

        $data['checks_count'] = $this->get_table_count("  CHECKS_PORTFOLIO_TB  M WHERE M.CHECKS_CASE = -1 AND   M.SOURCE_TYPE = {$this->q_type} AND   M.CHECK_DATE <= SYSDATE AND ( M.BRANCH = {$this->user->branch}  OR {$this->user->branch} = 1 ) ")[0]['NUM_ROWS'];

        $this->_loadDatePicker();

        $this->load->view('template/template',$data);
    }

    function public_checks($type){
        if($type == 2){
            $data['rows']=$this->checks_portfolio_model->checks_portfolio_rep($type,$this->user->branch);
            $data['content']='checks_index';
            $this->load->view('template/view',$data);
        }else{
            $data['rows'] = $this->checks_portfolio_model->checks_portfolio_process_rep($this->user->branch);
            $data['content']='checks_in_index';
            $this->load->view('template/view',$data);
        }
    }

    function get_page($page = 1,$type){

        $this->load->library('pagination');


        $sql = " AND  (C.BRANCH = {$this->user->branch} or {$this->user->branch} = 1) AND CHECK_TYPE ={$type} ";

        $sql .= isset($this->p_check_id) && $this->p_check_id !=null ? " AND  C.CHECK_ID = '{$this->p_check_id}' " :"" ;
        $sql .= isset($this->p_bank) && $this->p_bank !=null ? " AND  C.CHECK_BANK_ID = {$this->p_bank} " :"" ;
        $sql .= isset($this->p_price) && $this->p_price !=null ? " AND  CRIDET {$this->p_price_op} {$this->p_price} " :"" ;

        $count_rs =$type == 2? $this->checks_portfolio_model->get_pay_count($sql) :$this->checks_portfolio_model->get_count($sql) ;
        $config['base_url'] = base_url("payment/checks_portfolio/get_page/{$type}");
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] =10000;// $this->page_size;
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

        $result=$type == 2? $this->checks_portfolio_model->get_pay_list($sql, $offset , $row) :  $this->checks_portfolio_model->get_list($sql, $offset , $row);
        $this->date_format($result,array('CHECK_DATE'));

        $data["rows"] =$result;
        $data['type']=$type;

        $this->load->view('checks_portfolio_page',$data);

    }

    function withdraw( ){


        if($this->p_type == 1 ){
           
		    $rs = $this->checks_portfolio_model->checks_processing_change($this->p_id,
                $this->p_debit,
                $this->p_credit,
                $this->p_date,
                $this->p_account_type_debit,
                $this->p_account_type_credit,
                isset($this->p_hints) ? $this->p_hints : '');
				
        }else {
            $rs =  $this->checks_portfolio_model->checks_processing_pay_change($this->p_id , $this->p_debit, $this->p_credit,$this->p_date , $this->p_account_type_debit , $this->p_account_type_credit);
        }

        if(intval($rs) > 0){
            echo 1;
        }else
        {
            $this->print_error('فشل في سحب الشيك '.$rs);
        }
    }

    function _lookUps_data(&$data){

        $data['currency'] = $this->currency_model->get_all();
        $data['banks'] = $this->constant_details_model->get_list(9);
        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['help'] = $this->help;
        $this->_loadDatePicker();
    }

    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $this->checks_portfolio_model->create($this->_postedData());

            echo $id;

        } else {
            $data['content'] = 'checks_portfolio_show';
            $data['title'] = 'إدارة الشيكات';
            $data['action'] = 'index';
            $this->_lookUps_data($data);

            $this->load->view('template/template', $data);
        }
    }

    function delete()
    {
        echo $this->checks_portfolio_model->delete($this->p_id);
    }


    function _postedData($create = null){

        $result = array(
            array('name'=>'SEQ_IN','value'=>$this->p_seq ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_ID_IN','value'=>$this->p_check_id ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_CUSTOMER_IN','value'=>$this->p_check_customer ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_BANK_ID_IN','value'=>$this->p_check_bank_id ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_DATE_IN','value'=>$this->p_check_date ,'type'=>'','length'=>-1),
            array('name'=>'CRIDET_IN','value'=>$this->p_cridet ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_TYPE_IN','value'=>$this->p_check_type ,'type'=>'','length'=>-1),
            array('name'=>'THISYEAR_IN','value'=>2 ,'type'=>'','length'=>-1),
            array('name'=>'CURR_ID_IN','value'=>$this->p_curr_id ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID_IN','value'=>$this->p_account_id ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_IN','value'=>$this->p_branch ,'type'=>'','length'=>-1),

        );

        if($create == null){
            array_shift($result);
        }

        return $result;
    }

}