<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 27/12/14
 * Time: 12:42 م
 */
class Expense_bill extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('expense_bill_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('financial/accounts_model');
        $this->load->model('settings/CustomerAccountInterface_model');
    }

    function index($page= 1){
        $data['title']='فواتير المصروف';
        $data['content']='expense_bill_index';
        $data['page']=$page;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $data['case'] = 1;
        $this->_look_ups($data);


        $this->load->view('template/template',$data);
    }

    function archive($page= 1){
        $data['title']='فواتير المصروف';
        $data['content']='expense_bill_archive';
        $data['page']=$page;
        $data['help'] = $this->help;
        $data['action'] = 'index';
        $data['case'] = 1;

        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function _look_ups(&$data,$date = null){
        $data['currency'] =  $this->currency_model->get_all_date($date);
        $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['customer_type'] = $this->constant_details_model->get_list(18);

        $data['vat_account_id'] =  $this->get_system_info('VAT_ACCOUNT_ID','1');
        $data['vat_value'] =  $this->get_system_info('VAT_VALUE','1');
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(3);

        $data['help']=$this->help;

        add_css('datepicker3.css');
        add_css('combotree.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

    }

    function get_page($page = 1,$case = 1){

        $this->load->library('pagination');

        $sql = $case == 1? " where   EXPENSE_BILLT_USER={$this->user->id} " : " where 1=1 ";
        $sql .= " and EXPENSE_BILL_CASE between {$case}-1 and {$case}  ";

        $sql .= isset($this->p_id) && $this->p_id !=null ? " AND  INVOICE_ID ='{$this->p_id}' " :"" ;
        $sql .= isset($this->p_name) && $this->p_name !=null ? " AND  FINANCIAL_PKG.ACOUNTS_TB_GET_NAME_ALL(ACCOUNT_ID,ACCOUNT_TYPE) LIKE '%{$this->p_name}%' " :"" ;
        $sql .= isset($this->p_from_date) && $this->p_from_date !=null ? " AND  EXPENSE_BILL_DATE >= '{$this->p_from_date}' " :"" ;
        $sql .= isset($this->p_to_date) && $this->p_to_date !=null ? " AND  EXPENSE_BILL_DATE <= '{$this->p_to_date}' " :"" ;

        $data['action']=isset($this->p_action)?$this->p_action : 'edit';

        $count_rs = $this->expense_bill_model->get_count($sql);

        $config['base_url'] = base_url('financial/expense_bill/get_page');
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

        $result = $this->expense_bill_model->get_list($sql,$offset , $row );
        $this->date_format($result,array('INVOICE_DATE','EXPENSE_BILL_DATE'));
        $data["rows"] =$result;

        $this->load->view('expense_bill_page',$data);
    }

    function get_archive_page($page = 1){

        $this->load->library('pagination');

        $sql =" where 1=1 ";

        $sql .= isset($this->p_id) && $this->p_id !=null ? " AND  INVOICE_ID = '{$this->p_id}' " :"" ;
        $sql .= isset($this->p_name) && $this->p_name !=null ? " AND  FINANCIAL_PKG.ACOUNTS_TB_GET_NAME_ALL(ACCOUNT_ID,ACCOUNT_TYPE) LIKE '%{$this->p_name}%' " :"" ;
        $sql .= isset($this->p_from_date) && $this->p_from_date !=null ? " AND  EXPENSE_BILL_DATE >= '{$this->p_from_date}' " :"" ;
        $sql .= isset($this->p_to_date) && $this->p_to_date !=null ? " AND  EXPENSE_BILL_DATE <= '{$this->p_to_date}' " :"" ;





        $count_rs = $this->expense_bill_model->get_count($sql);

        $config['base_url'] = base_url('financial/expense_bill/get_archive_page');
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

        $result = $this->expense_bill_model->get_list($sql,$offset , $row );
        $this->date_format($result,array('INVOICE_DATE','EXPENSE_BILL_DATE'));
        $data["rows"] =$result;

        $this->load->view('expense_bill_page',$data);
    }

    function get($id,$action = 'index',$case = 1){
        $result=$this->expense_bill_model->get($id);
        $this->date_format($result,array('INVOICE_DATE','EXPENSE_BILL_DATE'));
        $data['result']=$result;

        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['EXPENSE_BILLT_USER'] && $result[0]['EXPENSE_BILL_CASE'] == 1 && $action == 'edit')?true : false : false;
        $data['can_edit'] = $action == 'copy' ? true : $data['can_edit'];
        $data['action'] = $action;
        $data['case']= $case;
        $data['content']='expense_bill_show';
        $data['title']='فاتورة مصروف';

        $this->_look_ups($data,count($result) > 0? $result[0]['EXPENSE_BILL_DATE']:null);

        $this->load->view('template/template',$data);
    }

    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_validate_data();

            $id =  $this->expense_bill_model->create($this->_postedData());

            if(intval($id) <= 0)
                $this->print_error('فشل في حفظ السند ؟!'.$id);

            for($i = 0;$i<count($this->p_d_account_id);$i++){

                $this->expense_bill_model->create_details(
                    $this->_postedData_details(
                        $id,
                        $this->p_d_account_id[$i],
                        $this->p_amount[$i],
                        $this->p_unit_price[$i],
                        $this->p_vat_done[$i],
                        $this->p_service_hints[$i],
                        $this->p_customer_id[$i],
                        $this->p_customer_type[$i],
                        $this->p_d_account_type[$i],
                        $this->p_customer_account_type[$i],
                        $this->p_customer_id_name[$i]

                    ));
            }

            $rs =  $this->expense_bill_model->validation($id,count($this->p_d_account_id));


            if(intval($rs) > 0)
                echo 1;
            else
                $this->print_error('فشل في حفظ السند ؟!'.$rs);


        }else{

            $data['content']='expense_bill_show';
            $data['title']='فاتورة مصروف';
            $data['action'] = 'create';

            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }

    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->expense_bill_model->edit($this->_postedData(false));

            if(intval($id) <= 0)
                $this->print_error('فشل في حفظ السند ؟!'.$id);

            for($i = 0;$i<count($this->p_d_account_id);$i++){

                if( $this->p_SER[$i] > 0)
                    $rs=  $this->expense_bill_model->edit_details(
                        $this->_postedData_details(
                            $this->p_expense_bill_id,
                            $this->p_d_account_id[$i],
                            $this->p_amount[$i],
                            $this->p_unit_price[$i],
                            $this->p_vat_done[$i],
                            $this->p_service_hints[$i],
                            $this->p_customer_id[$i],
                            $this->p_customer_type[$i],
                            $this->p_d_account_type[$i],
                            $this->p_customer_account_type[$i],
                            $this->p_customer_id_name[$i],

                            $this->p_SER[$i]
                        ));
                else
                    $rs=  $this->expense_bill_model->create_details(
                        $this->_postedData_details(
                            $this->p_expense_bill_id,
                            $this->p_d_account_id[$i],
                            $this->p_amount[$i],
                            $this->p_unit_price[$i],
                            $this->p_vat_done[$i],
                            $this->p_service_hints[$i],
                            $this->p_customer_id[$i],
                            $this->p_customer_type[$i],
                            $this->p_d_account_type[$i],
                            $this->p_customer_account_type[$i],
                            $this->p_customer_id_name[$i]
                        ));


                if(intval($rs) <= 0)
                    $this->print_error('فشل في حفظ السند ؟! '.$rs);


            }
            $rs =  $this->expense_bill_model->validation_affter_edit($this->p_expense_bill_id);


            if(intval($rs) <= 0)
                $this->print_error('فشل في حفظ السند ؟!'.$rs);
            else
                echo 1 ;
        }
    }

    function public_get_details($id = 0,$can_edit =false , $isCopy = false){

        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['details'] = $this->expense_bill_model->get_details($id);
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(5);

        $data['can_edit']=$can_edit;
        $data['is_copy']=$isCopy;

        $this->load->view('expense_bill_details',$data);
    }

    function _postedData($create = true){
        $result = array(
            array('name'=>'EXPENSE_BILL_ID','value'=>$this->p_expense_bill_id ,'type'=>'','length'=>-1),
            array('name'=>'CURR_ID','value'=>$this->p_curr_id ,'type'=>'','length'=>-1),
            array('name'=>'CURR_VALUE','value'=>$this->p_curr_value ,'type'=>'','length'=>-1),
            array('name'=>'EXPENSE_BILL_CLOSE','value'=>$this->p_expense_bill_close ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_TYPE','value'=>$this->p_account_type ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$this->p_account_id ,'type'=>'','length'=>-1),
            array('name'=>'INVOICE_ID','value'=>$this->p_invoice_id ,'type'=>'','length'=>-1),
            array('name'=>'INVOICE_DATE','value'=>$this->p_invoice_date ,'type'=>'','length'=>-1),
            array('name'=>'DECLARATION','value'=>$this->p_declaration ,'type'=>'','length'=>-1),
            array('name'=>'DISCOUNT_TYPE','value'=>$this->p_discount_type ,'type'=>'','length'=>-1),
            array('name'=>'DISCOUNT_VALUE','value'=>$this->p_discount_value ,'type'=>'','length'=>-1),
            array('name'=>'VAT_TYPE','value'=>$this->p_vat_type ,'type'=>'','length'=>-1),
            array('name'=>'VAT_ACCOUNT_ID','value'=>$this->p_vat_account_id ,'type'=>'','length'=>-1),
            array('name'=>'VAT_VALUE','value'=>$this->p_vat_value ,'type'=>'','length'=>-1),
            array('name'=>'SECOND_DATE','value'=>$this->p_second_date ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ACCOUNT_TYPE','value'=>$this->p_m_customer_account_type ,'type'=>'','length'=>-1),

        );


        if($create){
            array_shift($result);
        }

        return $result;
    }

    function _postedData_details($expense_bill_id,$account_id,$amount,$unit_price,$vat_done,$service_hints,$customer_id,$customer_type,$account_type,$customer_account_type,$customer_name,$ser = 0){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'EXPENSE_BILL_ID','value'=>$expense_bill_id ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$account_id ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT','value'=>$amount ,'type'=>'','length'=>-1),
            array('name'=>'UNIT_PRICE','value'=>$unit_price ,'type'=>'','length'=>-1),
            array('name'=>'VAT_DONE','value'=>$vat_done ,'type'=>'','length'=>-1),
            array('name'=>'SERVICE_HINTS','value'=>$service_hints ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ID','value'=>$customer_id ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_TYPE','value'=>$customer_type ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_TYPE','value'=>$account_type ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ACCOUNT_TYPE','value'=>$customer_account_type ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_NAME','value'=>$customer_name ,'type'=>'','length'=>-1),

        );

        if($ser == 0){
            array_shift($result);
        }
        //print_r($result);

        return $result;
    }

    function  _validate_data(){

        if(!isset($this->p_vat_account_id) ||  !$this->accounts_model->isAccountExists($this->p_vat_account_id))
            $this->print_error(' رقم حساب الضريبة   غير صحيح ');

        if(!isset($this->p_d_account_id) )
            $this->print_error('بيانات التفاصيل غير صحيحة');

        for($i = 0;$i<count($this->p_d_account_id);$i++){
            if($this->p_d_account_type[$i] == 1)
                if(!$this->accounts_model->isAccountExists($this->p_d_account_id[$i]))
                    $this->print_error('رقم الحساب في التفاصيل غير صحيح');
        }

        $counts = count($this->p_d_account_id)== count($this->p_customer_id);
        $counts= $counts && count($this->p_amount) == count($this->p_unit_price);

        if(!($counts)){
            $this->print_error('بيانات التفاصيل غير صحيحة');
        }

    }

    function delete(){
        $rs =  $this->expense_bill_model->delete($this->p_id,$this->p_expense_bill_id);
        if(intval($rs) > 0)
            $this->expense_bill_model->validation_affter_edit($this->p_expense_bill_id);

        echo $rs;

    }
}