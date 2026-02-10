<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/04/15
 * Time: 08:34 ص
 */

class Warranty extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('warranty_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('financial/accounts_model');
    }

    function index($page= 1){
        $data['title']='الكفالات';
        $data['content']='warranty_index';
        $data['page']=$page;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $data['case'] = 1;
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function archive($page= 1){
        $data['title']='الكفالات';
        $data['content']='warranty_archive';
        $data['page']=$page;
        $data['help'] = $this->help;
        $data['action'] = 'index';
        $data['case'] = 1;

        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function _look_ups(&$data,$date = null){
        $data['currency'] = $this->currency_model->get_all_date($date);
        $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['customer_type'] = $this->constant_details_model->get_list(18);
        $data['banks'] = $this->constant_details_model->get_list(9);
        $data['vat_account_id'] =  $this->get_system_info('VAT_ACCOUNT_ID','1');
        $data['vat_value'] =  $this->get_system_info('VAT_VALUE','1');

        $data['bail_type'] = $this->constant_details_model->get_list(78);
        $data['bail_case'] = $this->constant_details_model->get_list(72);

        $data['help']=$this->help;


        $data['treasury_prefix'] =  $this->get_system_info('TREASURE_PREFIX','1');
        $data['warranty_prefix'] =  $this->get_system_info('WARRANTY_PREFIX','1');

        add_css('datepicker3.css');
        add_css('combotree.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');


    }

    function get_page($page = 1,$case = 1){

        $this->load->library('pagination');

        $sql = "  ";


        $sql .= isset($this->p_id) && $this->p_id !=null ? " AND  BAIL_ID= {$this->p_id} " :"" ;
        $sql .= isset($this->p_name) && $this->p_name !=null ? " AND  (FINANCIAL_PKG.ACOUNTS_TB_GET_NAME_ALL(CUST_ID,2) LIKE '%{$this->p_name}%'  OR CHECK_CUSTOMER LIKE '%{$this->p_name}%' ) " :"" ;
        $sql .= isset($this->p_from_date) && $this->p_from_date !=null ? " AND  TRUNC(BAIL_BANK_DATE) >= '{$this->p_from_date}' " :"" ;
        $sql .= isset($this->p_to_date) && $this->p_to_date !=null ? " AND   TRUNC(BAIL_BANK_DATE) <= '{$this->p_to_date}' " :"" ;
        $sql .= isset($this->p_finished_date) && $this->p_finished_date !=null ? " AND   TRUNC(CHECK_DATE) <= '{$this->p_finished_date}' " :"" ;
        $sql .= isset($this->p_to_finished_dateFrom) && $this->p_to_finished_dateFrom !=null ? " AND   TRUNC(CHECK_DATE) >= '{$this->p_to_finished_dateFrom}' " :"" ;

        $sql .= isset($this->p_bill_case) && $this->p_bill_case !=null ? " AND  BAIL_TYPE= {$this->p_bill_case} " :"" ;
        $sql .= isset($this->p_curr_id) && $this->p_curr_id !=null ? " AND  CURRENCY_ID= {$this->p_curr_id} " :"" ;



        $data['action']=isset($this->p_action)?$this->p_action : 'edit';

        $count_rs = $this->get_table_count(" BAIL_TB  M where 1=1 $sql");

        $config['base_url'] = base_url('financial/warranty/get_page');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] =$this->page_size;
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

        $result = $this->warranty_model->get_list($sql,$offset , $row );
        $this->date_format($result,array('INVOICE_DATE','warranty_DATE'));
        $data["rows"] =$result;

        $data['offset']=$offset+1;

        $this->load->view('warranty_page',$data);
    }

    function get_archive_page($page = 1){

        $this->load->library('pagination');

        $sql =" where 1=1 ";

        $sql .= isset($this->p_id) && $this->p_id !=null ? " AND  INVOICE_ID= {$this->p_id} " :"" ;
        $sql .= isset($this->p_name) && $this->p_name !=null ? " AND  (FINANCIAL_PKG.ACOUNTS_TB_GET_NAME_ALL(ACCOUNT_ID,ACCOUNT_TYPE) LIKE '%{$this->p_name}%' OR CHECK_CUSTOMER LIKE '%{$this->p_name}%' ) " :"" ;
        $sql .= isset($this->p_from_date) && $this->p_from_date !=null ? " AND  warranty_DATE >= '{$this->p_from_date}' " :"" ;
        $sql .= isset($this->p_to_date) && $this->p_to_date !=null ? " AND  warranty_DATE <= '{$this->p_to_date}' " :"" ;



        $count_rs = $this->warranty_model->get_count($sql);

        $config['base_url'] = base_url('financial/warranty/get_archive_page');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] =$this->page_size;
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

        $result = $this->warranty_model->get_list($sql,$offset , $row );
        $this->date_format($result,array('INVOICE_DATE','warranty_DATE'));
        $data["rows"] =$result;
        $data['offset']=$offset+1;
        $this->load->view('warranty_page',$data);
    }

    function get($id,$action = 'index',$case = 1){
        $result=$this->warranty_model->get($id);
        $this->date_format($result,array('INVOICE_DATE','warranty_DATE'));
        $data['result']=$result;

        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['case']= $case;
        $data['content']='warranty_show';
        $data['title']='بيانات الكفالة';
        $this->_look_ups($data,count($result) > 0? $result[0]['BAIL_DATE']:null);

        $this->load->view('template/template',$data);
    }

    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_validate_data();

            $id =  $this->warranty_model->create($this->_postedData());

            if(intval($id) <= 0)
                $this->print_error('فشل في حفظ السند ؟!');
            else echo 1;

        }else{

            $data['content']='warranty_show';
            $data['title']='بيانات الكفالة';
            $data['action'] = 'create';

            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }

    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->warranty_model->edit($this->_postedData(false));

            if(intval($id) <= 0)
                $this->print_error('فشل في حفظ السند ؟!');



            echo 1 ;
        }
    }

    function public_get_details($id = 0){

        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['rows'] = $this->warranty_model->get_details($id);

        $this->load->view('warranty_details',$data);
    }

    function _postedData($create = true){
        $result = array(
            array('name'=>'BAIL_ID','value'=>$this->p_bail_id,'type'=>'','length'=>-1),
            array('name'=>'BAIL_DATE','value'=>$this->p_bail_date ,'type'=>'','length'=>-1),
            array('name'=>'BAIL_BANK_ID','value'=>$this->p_bail_bank_id ,'type'=>'','length'=>-1),
            array('name'=>'BAIL_BANK_DATE','value'=>$this->p_bail_bank_date ,'type'=>'','length'=>-1),
            array('name'=>'BAIL_TYPE','value'=>1 ,'type'=>'','length'=>-1),
            array('name'=>'CURRENCY_ID','value'=>$this->p_currency_id ,'type'=>'','length'=>-1),

            array('name'=>'CURR_VALUE','value'=>$this->p_curr_value ,'type'=>'','length'=>-1),
            array('name'=>'CUST_ID','value'=>$this->p_cust_id ,'type'=>'','length'=>-1),
            array('name'=>'TREASURY_ACCOUNT_ID','value'=>$this->p_treasury_account_id ,'type'=>'','length'=>-1),
            array('name'=>'SEC_ACCOUNT_ID','value'=>$this->p_sec_account_id ,'type'=>'','length'=>-1),
            array('name'=>'BANK_ACCOUNT_ID','value'=>null ,'type'=>'','length'=>-1),
            array('name'=>'INCOME_ACCOUNT_ID','value'=>null ,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$this->p_hints ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_ID','value'=>$this->p_check_id ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_CUSTOMER','value'=>$this->p_check_customer ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_BANK_ID','value'=>$this->p_check_bank_id ,'type'=>'','length'=>-1),
            array('name'=>'CHECK_DATE','value'=>$this->p_check_date ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT','value'=>$this->p_amount ,'type'=>'','length'=>-1),
            array('name'=>'BAIL_TYPE2','value'=>$this->p_bail_type2 ,'type'=>'','length'=>-1),
        );


        if($create){
            array_shift($result);
        }

        return $result;
    }

    function _postedData_details($bail_id,$expand_date,$expand_condition,$ser = 0){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'BAIL_ID','value'=>$bail_id ,'type'=>'','length'=>-1),
            array('name'=>'EXPAND_DATE','value'=>$expand_date ,'type'=>'','length'=>-1),
            array('name'=>'EXPAND_CONDITION','value'=>$expand_condition ,'type'=>'','length'=>-1),
        );

        if($ser == 0){
            array_shift($result);
        }
        //print_r($result);

        return $result;
    }

    function  _validate_data(){



    }

    function delete(){
        $rs =  $this->warranty_model->delete($this->p_id,$this->p_warranty_id);
        if(intval($rs) > 0)
            $this->warranty_model->validation_affter_edit($this->p_warranty_id);

        echo $rs;

    }

    function public_actions(){


        $this->load->view('warranty_actions');
    }

    function return_bail(){
        $rs =  $this->warranty_model->return_bail($this->p_id);

        if(intval($rs) <= 0)
            $this->print_error('فشل إرجاع الكفالة ؟!');
        echo $rs;
    }

    function cash_bail(){
        $rs =  $this->warranty_model->cash_bail($this->p_bail_id,$this->p_bank_account , $this->p_income_account);

        if(intval($rs) <= 0)
            $this->print_error('فشل تسييل الكفالة ؟!');
        echo $rs;
    }

    function create_details(){


        $rs = $this->warranty_model->create_details($this->_postedData_details($this->p_bail_id,$this->p_date,$this->p_reason));;

        if(intval($rs) <= 0)
            $this->print_error('فشل إرجاع الكفالة ؟!');
        echo $rs;

    }

}