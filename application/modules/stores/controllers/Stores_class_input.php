<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 13/12/14
 * Time: 10:53 ص
 */

class stores_class_input extends MY_Controller
{
    var $record_page = 0;

    var $MODEL_NAME = 'stores_class_input_model';
    var $PAGE_URL = 'stores/stores_class_input/get_page';
    var $ADOPT_STORE_PAGE_URL = 'stores/stores_class_input/get_page_adopt';
    var $DETAIL_MODEL_NAME = 'stores_class_input_detail_model';

    var $class_input_id, $class_input_date, $class_input_user, $record_id, $order_id, $store_id, $customer_resource_id, $class_input_case
    , $class_input_type, $class_input_user_trans_id, $class_input_user_trans_date, $declaration, $invoice_id, $invoice_date, $invoice_user_entry
    , $invoice_case, $invoice_audit_id, $invoice_audit_date, $invoice_trans_id, $invoice_trans_date, $curr_id, $curr_value, $descount_type
    , $descount_value, $vat_type, $vat_account_id, $vat_value, $enrty_date, $year_order, $class_input_class_type, $invoice_inbox, $grant_date
    , $account_type, $send_id2, $financial_declaration, $input_seq_t, $donation_file_id;

    var $class_id, $class_unit, $amount, $price, $ser, $taxable_det, $vat_type_det, $price_befor_vat, $class_price;


    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAIL_MODEL_NAME);


        $this->load->model('settings/currency_model');


        $this->load->model('settings/constant_details_model');
        $this->load->model('stores/stores_model');
        $this->load->model('stores/store_members_model');
        $this->load->model('stores/store_committees_model');
        $this->load->model('settings/CustomerAccountInterface_model');

        $this->record_page = intval($this->input->get_post('case'));


//-----------------------------------------------------
        $this->class_input_id = $this->input->post('class_input_id');
        $this->class_input_date = $this->input->post('class_input_date');
        $this->class_input_user = $this->input->post('class_input_user');
        $this->record_id = $this->input->post('record_id');
        $this->order_id = $this->input->post('order_id');
        $this->store_id = $this->input->post('store_id');
        $this->customer_resource_id = $this->input->post('customer_resource_id');
        $this->class_input_case = $this->input->post('class_input_case');
        $this->class_input_type = $this->input->post('class_input_type');
        $this->class_input_user_trans_id = $this->input->post('class_input_user_trans_id');
        $this->class_input_user_trans_date = $this->input->post('class_input_user_trans_date');
        $this->declaration = $this->input->post('declaration');
        $this->invoice_id = $this->input->post('invoice_id');
        $this->invoice_date = $this->input->post('invoice_date');
        $this->invoice_user_entry = $this->input->post('invoice_user_entry');
        $this->invoice_case = $this->input->post('invoice_case');
        $this->invoice_audit_id = $this->input->post('invoice_audit_id');
        $this->invoice_audit_date = $this->input->post('invoice_audit_date');
        $this->invoice_trans_id = $this->input->post('invoice_trans_id');
        $this->invoice_trans_date = $this->input->post('invoice_trans_date');
        $this->vat_type = $this->input->post('vat_type');
        $this->vat_account_id = $this->input->post('vat_account_id');
        $this->vat_value = $this->input->post('vat_value');
        $this->enrty_date = $this->input->post('enrty_date');
        $this->year_order = $this->input->post('year_order');
        $this->class_input_class_type = $this->input->post('class_input_class_type');
        $this->invoice_inbox = $this->input->post('invoice_inbox');
        $this->grant_date = $this->input->post('grant_date');
        $this->send_id2 = $this->input->post('send_id2');
        $this->donation_file_id = $this->input->post('donation_file_id');
        $this->account_type = $this->input->post('account_type');
        $this->financial_declaration = $this->input->post('financial_declaration');
        $this->input_seq_t = $this->input->post('input_seq_t');
        $this->donation_curr_value = $this->input->post('donation_curr_value');
        $this->customer_account_type = $this->input->post('customer_account_type');

        $this->class_id = $this->input->post('h_class_id');
        $this->class_unit = $this->input->post('unit_class_id');
        $this->amount = $this->input->post('amount');
        $this->price = $this->input->post('price');
        $this->ser = $this->input->post('h_ser');
        $this->taxable_det = $this->input->post('taxable_det');
        $this->class_price = $this->input->post('price_class_id');

        $this->invoice_seq = $this->input->get_post('invoice_seq');

        $this->donation_file_ser = $this->input->post('donation_file_ser');

    }

    function public_index_invoice($invoice_seq = -1)
    {
        $data['title'] = 'الادخالات المخزنية';
        $data['content'] = 'stores_class_input_index_invoice';
        $data['page'] = 1;
        $data['invoice_seq'] = $this->invoice_seq;
        //  $this->_look_ups($data);
        $data['action'] = 'edit';
        $this->load->view('template/view', $data);
    }


    function public_get_page_invoice($page = 1, $invoice_seq = -1)
    {
        // $this->load->library('pagination');
        $invoice_seq = $this->check_vars($invoice_seq, 'invoice_seq');
        $where_sql = ' where 1=1 ';
        $where_sql .= ($invoice_seq != null) ? " and invoice_class_seq= {$invoice_seq} " : '';
        //echo $where_sql;
        //   $config['base_url'] = base_url('stores/stores_class_input/get_page_invoice');
        //   $count_rs = $this->{$this->MODEL_NAME}->get_count( ' STORES_CLASS_INPUT_TB '.$where_sql);
        //   $config['use_page_numbers'] = TRUE;
        //  $config['total_rows'] =50; //count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        //   $config['per_page'] = $this->page_size;
        //   $config['num_links'] = 20;
        /*   $config['cur_page']=$page;

     * $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
         $config['full_tag_close'] = '</ul></div>';
         $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
         $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
         $config['cur_tag_open'] = '<li class="active"><span><b>';
         $config['cur_tag_close'] = "</b></span></li>";

         $this->pagination->initialize($config);*/

        //   $offset = ((($page-1) * $config['per_page']) );
        //    $row = (($page * $config['per_page'])  );

        //     $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, 0, 100);

        $data['offset'] = 1;//$offset+1;
        //   $data['page']=$page;
//stores_class_input_invoice_page
        $this->load->view('stores_class_input_page_invoice', $data);
    }

    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        $var = $this->{$c_var} ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function index_mk($page = 1, $class_input_id = -1, $input_seq_t = -1, $send_id2 = -1, $account_type = -1, $customer_resource_id = -1)
    {
        $type = intval($this->input->get_post('type'));
        $data['title'] = 'الادخالات المخزنية';
        $data['content'] = 'stores_class_input_index_mk';
        $data['page'] = $page;
        $data['class_input_id'] = $class_input_id;
        $data['send_id2'] = $send_id2;
        $data['account_type'] = $account_type;
        $data['customer_resource_id'] = $customer_resource_id;
        $data['input_seq_t'] = $input_seq_t;
        $this->_look_ups($data);
        $data['type'] = $type;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->load->view('template/template', $data);
    }

    function _look_ups(&$data)
    {

        $data['customer_type'] = $this->constant_details_model->get_list(18);
        $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        //   $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        //  $data['unit_class_id'] = $this->constant_details_model->get_list(29);
        $data['class_input_class_type'] = $this->store_committees_model->get_all_by_type(1);
        $data['currency'] = $this->currency_model->get_all();
        $data['class_input_type'] = $this->constant_details_model->get_list(31);
        $data['vat_account_id_c'] = $this->get_system_info('VAT_ACCOUNT_ID', '1'); //$this->constant_details_model->get_list(32);
        $data['vat_value_c'] = $this->get_system_info('VAT_VALUE', '1');//$this->constant_details_model->get_list(33);
        $data['stores'] = $this->stores_model->get_all();
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(3);
        $data['help'] = $this->help;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

    }

    function get_page_mk($page = 1, $class_input_id = -1, $input_seq_t = -1, $type = 0, $send_id2 = -1, $account_type = -1, $customer_resource_id = -1)
    {
        $this->load->library('pagination');
        $type = intval($this->input->get_post('type'));
        $data['type'] = $type;
        $class_input_id = $this->check_vars($class_input_id, 'class_input_id');
        $input_seq_t = $this->check_vars($input_seq_t, 'input_seq_t');
        $send_id2 = $this->check_vars($send_id2, 'send_id2');
        $account_type = $this->check_vars($account_type, 'account_type');
        $customer_resource_id = $this->check_vars($customer_resource_id, 'customer_resource_id');
        if ($type == 0) //تحويل لفاتورة
            $where_sql = ' where 1=1 and class_input_type=1 and CLASS_INPUT_CASE = 2 AND IS_CONVERT=0 and nvl(donation_file_id,0)=0';
        else if ($type == 1)  //تحويل لقيد
            $where_sql = ' where 1=1 and (( class_input_type=1 and CLASS_INPUT_CASE = 2 AND IS_CONVERT=0) or (class_input_type=2 and CLASS_INPUT_CASE = 2 AND IS_CONVERT=0))  ';
        else if ($type == 2)  //أرشيف سندات الادخال
            $where_sql = ' where 1=1 ';
        else if ($type == 3)  //أرشيف سندات الادخال
            $where_sql = ' where account_type=3 and class_input_type=3 AND CLASS_INPUT_CASE =2  ';//AND IS_CONVERT=0

        $where_sql .= ($class_input_id != null) ? " and class_input_id= {$class_input_id} " : '';
        $where_sql .= ($input_seq_t != null) ? " and STORES_PKG.STORES_TB_GET_CODE_STORE(STORE_ID)||'/'||INPUT_SEQ like '%{$input_seq_t}%' " : '';
        $where_sql .= ($send_id2 != null) ? " and send_id2= {$send_id2} " : '';
        $where_sql .= ($account_type != null) ? " and account_type= {$account_type} " : '';
        $where_sql .= ($customer_resource_id != null) ? " and customer_resource_id= '{$customer_resource_id}' " : '';
        //echo $where_sql;
        $config['base_url'] = base_url('stores/stores_class_input/get_page_mk');
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' STORES_CLASS_INPUT_TB ' . $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        // ECHO  $config['total_rows'] ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('stores_class_input_page_mk', $data);
    }

    function public_index($text = null, $type = null, $from_date = '', $to_date = '', $page = 1)
    {
        $data['text'] = $this->input->get_post('text') ? $this->input->get_post('text') : $text;
        $data['from_date'] = $this->input->get_post('from_date') ? $this->input->get_post('from_date') : $from_date;
        $data['to_date'] = $this->input->get_post('to_date') ? $this->input->get_post('to_date') : $to_date;
        $data['page'] = $this->input->get_post('page') ? $this->input->get_post('page') : $page;
        $data['content'] = 'stores_class_invoice_index';

        $data['txt'] = $text;
        echo "dddd" . $data['from_date'];
        $this->load->view('template/view', $data);
    }

    function public_get_stores_input($prm = array())
    {
        $f = $this->input->get_post('from_date');
        $t = $this->input->get_post('to_date');
        $page = $this->input->get_post('page');
        if (!$prm)
            $prm = array('text' => $this->input->get_post('text'),
                'from_date' => isset($f) ? $f : null,
                'to_date' => isset($t) ? $t : null,
                'page' => isset($page) ? $page : null,
            );

        $this->load->library('pagination');

        $page = $prm['page'] ? $prm['page'] : 1;

        $config['base_url'] = base_url("stores/stores_class_input/public_index/?text={$prm['text']}&from_date={$prm['from_date']}&to_date={$prm['to_date']}");

        $prm['from_date'] = $prm['from_date'] != -1 ? $prm['from_date'] : null;
        $prm['to_date'] = $prm['to_date'] != -1 ? $prm['to_date'] : null;


        //   $count_rs = $this->{$this->MODEL_NAME}->get_count($prm['from_date'], $prm['to_date']);
        $count_rs = 10;

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));

        $data['get_all'] = $this->{$this->MODEL_NAME}->get_list('', $offset, $row);
        $this->load->view('stores_class_input_page', $data);


    }

    function index($page = 1)
    {


        $data['help'] = $this->help;
        if ($this->record_page == 0) {
            $data['content'] = 'stores_class_input_index';
            $data['title'] = ' سند إدخال أصناف جديدة للمخازن';
        } else if ($this->record_page == 2) {
            $data['content'] = 'stores_class_invoice_index';
            $data['title'] = 'فواتير الشراء';
        } else {
            $data['content'] = 'stores_class_input_invoice_index';
            $data['title'] = 'فواتير الشراء';
        }


        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $data['page'] = $page;
        $data['action'] = 'edit';
        //   $data['case'] = 1;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);

    }

    /*
        function get_page_invoice ($page = 1,$case = 1){
            //   $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
            //    $this->load->view('receipt_class_input_page',$data);


            $this->load->library('pagination');
            $sql =' where INVOICE_TRANSFER=1';

            $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
            $count_rs = count($data['get_all']);

            $config['base_url'] = base_url('payment/financial_payment/index');
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

            $result = $this->{$this->MODEL_NAME}->get_list($sql,$offset , $row );

            //  echo "fff".$page." ".$offset ." ". $row;
            $this->date_format( $data['get_all'],'CLASS_INPUT_DATE');
            $data['get_all'] =$result;

            $this->load->view('stores_class_input_invoice_page',$data);

        }
    */
    function get_page($page = 1, $case = 1)
    {
        //   $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        //    $this->load->view('receipt_class_input_page',$data);


        $this->load->library('pagination');

        $sql = "  WHERE 1=1   and '{$this->user->id}' in (select user_id from store_usres_tb where store_id=R.store_id) ";

        //   $sql = $case == 1? " where   ENTRY_USER={$this->user->id} " : " where 1=1 ";
        //   $sql .= " and FINANCIAL_PAYMENT_CASE between {$case}-1 and {$case}  ";
        $count_rs = $this->{$this->MODEL_NAME}->get_count(" stores_class_input_tb R " . $sql);


        $config['base_url'] = base_url('payment/financial_payment/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $config['total_rows'];
        $config['num_links'] = 20;
        $config['cur_page'] = $page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));

        $result = $this->{$this->MODEL_NAME}->get_list($sql, $offset, $row);
        echo $sql;
        //  echo "fff".$page." ".$offset ." ". $row;
        $data['get_all'] = $result;
        $this->date_format($data['get_all'], 'CLASS_INPUT_DATE');


        $this->load->view('stores_class_input_page', $data);

    }

//lana
    function get_page_adopt($page = 1)
    {

        $this->load->library('pagination');
        //$where_sql = "where 1=1 AND IS_EXTENED=0 and class_input_type=1 and CLASS_INPUT_CASE = 2 AND IS_CONVERT=0 and nvl(donation_file_id,0)=0";
        $where_sql = "where 1=1 AND IS_EXTENED=0";

        $where_sql .= isset($this->p_input_seq_t) && $this->p_input_seq_t != null ? " and STORES_PKG.STORES_TB_GET_CODE_STORE(STORE_ID)||'/'||INPUT_SEQ like '%{$this->p_input_seq_t}%' " : '';
        $where_sql .= isset($this->p_order_id) && $this->p_order_id != null ? " and ORDER_ID= '{$this->p_order_id}' " : '';
        $where_sql .= isset($this->p_account_type) && $this->p_account_type != null ? " and send_id2= {$this->p_send_id2} " : '';
        $where_sql .= isset($this->p_customer_resource_id) && $this->p_customer_resource_id != null ? " and customer_resource_id= '{$this->p_customer_resource_id}' " : '';
        $config['base_url'] = base_url('stores/stores_class_input/get_page_adopt');
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' STORES_CLASS_INPUT_TB_ADOPT_V ' . $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        // ECHO  $config['total_rows'] ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));

        $data['page_rows'] = $this->{$this->MODEL_NAME}->store_class_input_adopt_get_list($where_sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('stores_class_input_adopt_page', $data);

    }

    function get_id($id, $action = 'index', $case = 1, $type = 0)
    {

        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->date_format($result, 'CLASS_INPUT_DATE');

        $data['receipt_data'] = $result[0];
        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['CLASS_INPUT_USER'] && $result[0]['CLASS_INPUT_CASE'] == 1 && $action == 'edit') ? true : false : false;

        $data['action'] = $action;
        $data['case'] = $case;
        $data['type'] = $type;
        $data['content'] = 'stores_class_input_show';

        $data['title'] = 'سند إدخال   للمخازن';
        $this->_look_ups($data);
        //  if ($data['can_edit']==1)
        $data['stores'] = $this->stores_model->get_all_privs();
        $this->load->view('template/template', $data);
    }

    function get_chain_id($id, $action = 'index', $case = 1, $type = 0)
    {

        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->date_format($result, 'CLASS_INPUT_DATE');

        $data['receipt_data'] = $result[0];
        //  $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $result[0]['FINANCIAL_PAYMENT_CASE'] == 1 && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['case'] = $case;
        $data['type'] = $type;

        $data['content'] = 'stores_class_input_chain_show';

        $data['title'] = 'سند إدخال   للمخازن';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function get_invoice_id($id, $action = 'edit', $case = 1)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->date_format($result, 'CLASS_INPUT_DATE');

        $data['receipt_data'] = $result[0];
        //  $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $result[0]['FINANCIAL_PAYMENT_CASE'] == 1 && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['case'] = $case;
        $data['content'] = 'stores_class_input_invoice_show';
        $data['title'] = 'إدخال فاتورة الشراء';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function adopt()
    {
        /*  if(!$this->check_db_for_stores())
              die('CLOSED..');*/
        $id = $this->input->post('id');

        $result = $this->{$this->MODEL_NAME}->adopt($id);
        $this->return_json($result);
    }

    function adopt0()
    {
        /* if(!$this->check_db_for_stores())
             die('CLOSED..');*/
        $id = $this->input->post('id');
        $res = $this->{$this->MODEL_NAME}->get($id);
        if ($res[0]['IS_EXTENED'] == 1) {
            $this->print_error('الطلب محول لفواتورة أو قيد مالي ولا يمكن إلغاؤه');
        } else if ($res[0]['IS_CONVERT'] == 1) {
            $this->print_error('الطلب له مستخلص ولا يمكن إلغاؤه');
        } else {
            $result = $this->{$this->MODEL_NAME}->adopt0($id);
            echo $result;
        }


        //  $this->($result);
    }

    function transfer()
    {
        /*    if(!$this->check_db_for_stores())
                die('CLOSED..');*/
        $id = $this->input->post('id');

        $result = $this->{$this->MODEL_NAME}->transfer($id);
        //  $this->return_json($result);
        echo $result;
    }

    function transfer_chain3()
    {
        /*  if(!$this->check_db_for_stores())
              die('CLOSED..');*/
        $id = $this->input->post('id');

        $result = $this->{$this->MODEL_NAME}->transfer_chain($id);
        //$this->return_json($result); tasneem
        echo $result;
    }

    function transfer_chain()
    {
        /*  if(!$this->check_db_for_stores())
              die('CLOSED..');*/
        $id = $this->input->post('id');

        $result = $this->{$this->MODEL_NAME}->transfer_chain($id);
        // $this->return_json($result);
        echo $result;
    }

    function update_case()
    {
        /*   if(!$this->check_db_for_stores())
               die('CLOSED..');*/
        $id = $this->input->post('id');
        $case = $this->input->post('case');

        $result = $this->{$this->MODEL_NAME}->update_case($id, $case);
        $this->return_json($result);
    }

    function update_transfer_chain($page = 1)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->amount)) <= 0) {
                $this->print_error('يجب ادخال صنف واحد على الاقل ');
            } else if (count(array_filter($this->class_id)) != count(array_count_values(array_filter($this->class_id)))) {
                $this->print_error('يوجد تكرار في الاصناف');
            }

            $rs = $this->{$this->MODEL_NAME}->update_after_queed($this->class_input_id, $this->class_input_type, $this->declaration, $this->financial_declaration, $this->grant_date, $this->vat_type, $this->vat_value, $this->donation_curr_value);


            if (intval($rs) <= 0) {
                $this->print_error('فشل في حفظ  ؟!' . $rs);
            }
            for ($i = 0; $i < count($this->class_id); $i++) {
                if ($this->ser[$i] == 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->class_unit[$i] != '') { // create
                    $detail_seq = $this->stores_class_input_detail_model->create($this->_postDetailsDataInsert($this->class_input_id, $this->class_id[$i], $this->class_unit[$i], $this->amount[$i], $this->price[$i], $this->taxable_det[$i], $this->class_price[$i], $this->donation_file_ser[$i]));

                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                } elseif ($this->ser[$i] != 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->class_unit[$i] != '') { // edit
                    $detail_seq = $this->stores_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i], $this->class_input_id, $this->class_id[$i], $this->class_unit[$i], $this->amount[$i], $this->price[$i], $this->taxable_det[$i], $this->class_price[$i], $this->donation_file_ser[$i]));
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                } elseif ($this->ser[$i] != 0 and ($this->amount[$i] == '' or $this->amount[$i] == 0)) { // delete
                    $detail_seq = $this->stores_class_input_detail_model->delete($this->ser[$i]);
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                }
            }

            $q = $this->{$this->MODEL_NAME}->refresh_queed($this->class_input_id);
            if (intval($q) <= 0) {
                $this->print_error('فشل في تحديث القيد  ؟!' . $q);
            }

            echo "1";


        }


    }

    function create()
    {
        /*  if(!$this->check_db_for_stores())
              die('CLOSED..');*/
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer_account_type = $this->input->post('customer_account_type');
            $account_type = $this->input->post('account_type');
            $class_id = $this->input->post('h_class_id');

            $class_unit = $this->input->post('unit_class_id');
            $amount = $this->input->post('amount');
            $price = $this->input->post('price');
            $taxable_det = $this->input->post('taxable_det');
            $class_price = $this->input->post('price_class_id');
            $donation_file_ser = $this->input->post('donation_file_ser');

            if (($account_type == 2) and ($customer_account_type == '')) {
                $this->print_error('يجب اختيار نوع حساب المستفيد');
            }

            if (!($class_id) or count(array_filter($class_id)) <= 0 or count(array_filter($amount)) <= 0) {
                $this->print_error('يجب ادخال صنف واحد على الاقل ');
            }/*else if( count(array_filter($class_id)) !=  count(array_count_values(array_filter($class_id))) ){
                $this->print_error('يوجد تكرار في الاصناف');
            }*/


            $id = $this->{$this->MODEL_NAME}->create($this->_postedDataInsert());

            //  $this->Is_success($result);
            if (intval($id) <= 0) {
                $this->print_error('فشل في الحفظ' . $id);
            }

            for ($i = 0; $i < count($class_id); $i++) {
                if ($class_id[$i] != '' and $class_unit[$i] != '' and $amount[$i] != '' and $amount[$i] > 0) {
                    $did = $this->stores_class_input_detail_model->create($this->_postDetailsDataInsert($id, $class_id[$i], $class_unit[$i], $amount[$i], $price[$i], $taxable_det[$i], $class_price[$i], $donation_file_ser[$i]));

                    if (intval($did) <= 0)
                        $this->print_error_del($id, 'فشل في حفظ الأصناف' . $did);
                }
                //  else  $this->print_error_del($id,'يجب إدخال بيانات الأصناف');

            }
            /*      for($i = 0;$i<count($class_id);$i++){
                      if ($class_id[$i] !='' and $class_unit[$i] !='' and  $amount[$i] !=''){



                          $did=  $this->stores_class_input_detail_model->create($this->_postDetailsDataInsert($id,$class_id[$i],$class_unit[$i],$amount[$i],$price[$i],$taxable_det[$i],$class_price[$i]));
      //echo "<pre>"; print_r($this->_postDetailsDataInsert($id,$class_id[$i],$class_unit[$i],$amount[$i],0,0,0)); echo "</pre>";

                          if(intval($did) <= 0){
                              print_error_del($id,'');
                              $this->print_error('فشل في حفظ الأصناف');
                          }
                      } else{  print_error_del($id,'');
                          $this->print_error('يجب إدخال بيانات الأصناف');}
                  }*/

            echo 1;

        } else {
            //  echo  modules::run($this->PAGE_URL);

            $data['title'] = 'سند إدخال مخازن جديد';
            $data['help'] = $this->help;
            $data['content'] = 'stores_class_input_show';
            $data['action'] = 'index';
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_css('combotree.css');

            add_css('jquery.dataTables.css');
            add_js('jquery.dataTables.js');

            //  add_css('jquery.dataTables.css');
            // add_js('jquery.dataTables.js');
            $this->_look_ups($data);
            $data['stores'] = $this->stores_model->get_all_privs();

            $this->load->view('template/template', $data);
        }
    }

    function _postedDataInsert()
    {

        $result = array(

            array('name' => 'CLASS_INPUT_DATE', 'value' => $this->input->post('class_input_date'), 'type' => '', 'length' => -1),
            array('name' => 'RECORD_ID', 'value' => $this->input->post('record_id'), 'type' => '', 'length' => -1),
            array('name' => 'ORDER_ID', 'value' => $this->input->post('order_id'), 'type' => '', 'length' => -1),
            array('name' => 'STORE_ID', 'value' => $this->input->post('store_id'), 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_RESOURCE_ID', 'value' => $this->input->post('customer_resource_id'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_TYPE', 'value' => $this->input->post('class_input_type'), 'type' => '', 'length' => -1),
            array('name' => 'DECLARATION', 'value' => $this->input->post('declaration'), 'type' => '', 'length' => -1),
            array('name' => 'YEAR_ORDER', 'value' => $this->input->post('year_order'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_CLASS_TYPE', 'value' => $this->input->post('class_input_class_type'), 'type' => '', 'length' => -1),
            array('name' => 'GRANT_DATE', 'value' => $this->input->post('grant_date'), 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $this->input->post('account_type'), 'type' => '', 'length' => -1),
            array('name' => 'SEND_ID2', 'value' => $this->input->post('send_id2'), 'type' => '', 'length' => -1),
            array('name' => 'DONATION_FILE_ID', 'value' => $this->input->post('donation_file_id'), 'type' => '', 'length' => -1),
            array('name' => 'DONATION_CURR_VALUE', 'value' => $this->input->post('donation_curr_value'), 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNT_TYPE', 'value' => $this->input->post('customer_account_type'), 'type' => '', 'length' => -1)

        );


        return $result;
    }

    function _postDetailsDataInsert($id, $class_id, $class_unit, $amount, $price, $taxable_det, $class_price, $donation_file_ser)
    {

        $result = array(

            array('name' => 'STORES_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
            array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => 0, 'type' => '', 'length' => -1),
            array('name' => 'TAXABLE_DET', 'value' => $taxable_det, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_PRICE', 'value' => $class_price, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_FILE_SER', 'value' => $donation_file_ser, 'type' => '', 'length' => -1)

        );


        return $result;

    }

    function print_error_del($rid = 0, $msg = '')
    {
        $ret = $this->{$this->MODEL_NAME}->delete($rid);
        if ($ret == 1)
            $this->print_error('لم يتم الحفظ ' . $msg);
        else
            $this->print_error('لم يتم الحفظ ' . $msg);
    }

    function delete()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->delete($val);
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->delete($id);
        }

        if ($msg == 1) {
            echo modules::run($this->PAGE_URL);
        } else {
            $this->print_error_msg($msg);
        }
    }

    function edit($page = 1)
    {
        /*  if(!$this->check_db_for_stores())
              die('CLOSED..');*/
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (($this->account_type == 2) and ($this->customer_account_type == '')) {
                $this->print_error('يجب اختيار نوع حساب المستفيد');
            }

            if (!($this->class_id) or count(array_filter($this->class_id)) <= 0 or count(array_filter($this->amount)) <= 0) {
                $this->print_error('يجب ادخال صنف واحد على الاقل ');
            }/*else if( count(array_filter($this->class_id)) !=  count(array_count_values(array_filter($this->class_id))) ){
                $this->print_error('يوجد تكرار في الاصناف');
            }*/

            $rs = $this->{$this->MODEL_NAME}->edit($this->_postedDataEdit());


            if (intval($rs) <= 0) {
                $this->print_error('فشل في حفظ  ؟!' . $rs);
            }
            for ($i = 0; $i < count($this->class_id); $i++) {
                if ($this->ser[$i] == 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->class_unit[$i] != '') { // create
                    $detail_seq = $this->stores_class_input_detail_model->create($this->_postDetailsDataInsert($this->class_input_id, $this->class_id[$i], $this->class_unit[$i], $this->amount[$i], $this->price[$i], $this->taxable_det[$i], $this->class_price[$i], $this->donation_file_ser[$i]));

                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                } elseif ($this->ser[$i] != 0 and $this->class_id[$i] != '' and $this->amount[$i] != '' and $this->amount[$i] > 0 and $this->class_unit[$i] != '') { // edit
                    //  echo $this->price[$i]."ffff";
                    $detail_seq = $this->stores_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i], $this->class_input_id, $this->class_id[$i], $this->class_unit[$i], $this->amount[$i], $this->price[$i], $this->taxable_det[$i], $this->class_price[$i], $this->donation_file_ser[$i]));
                    // echo $detail_seq."dddd";
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                } elseif ($this->ser[$i] != 0 and ($this->amount[$i] == '' or $this->amount[$i] == 0)) { // delete
                    $detail_seq = $this->stores_class_input_detail_model->delete($this->ser[$i]);
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                }
            }
            /*     for($i = 0;$i<count($this->class_id);$i++){
                  if ($this->class_id[$i] =='' and $this->class_unit[$i] ==''){
                      $this->print_error('يجب إدخال بيانات الأصناف');
                  }else {




               /*     if($this->ser[$i] == 0){
                           $this->stores_class_input_detail_model->create($this->_postDetailsDataInsert( $this->class_input_id,$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->price[$i],$this->taxable_det[$i],$this->class_price[$i]));

                  }  else{

                          $this->stores_class_input_detail_model->edit($this->_postDetailsDataEdit($this->ser[$i], $this->class_input_id,$this->class_id[$i],$this->class_unit[$i],$this->amount[$i],$this->price[$i],$this->taxable_det[$i],$this->class_price[$i]));
            }
                }


            }*/

            echo "1";


        } else {
            $data['title'] = ' سند إدخال أصناف جديدة للمخازن';
            $data['content'] = 'stores_class_input_index';
            $data['page'] = $page;
            $data['help'] = $this->help;
            $data['action'] = 'edit';
            $data['case'] = 1;
            $this->load->view('template/template', $data);
        }


    }

    function _postedDataEdit()
    {


        $result = array(

            array('name' => 'CLASS_INPUT_ID', 'value' => $this->input->post('class_input_id'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_DATE', 'value' => $this->input->post('class_input_date'), 'type' => '', 'length' => -1),
            array('name' => 'RECORD_ID', 'value' => $this->input->post('record_id'), 'type' => '', 'length' => -1),
            array('name' => 'ORDER_ID', 'value' => $this->input->post('order_id'), 'type' => '', 'length' => -1),
            array('name' => 'STORE_ID', 'value' => $this->input->post('store_id'), 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_RESOURCE_ID', 'value' => $this->input->post('customer_resource_id'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_TYPE', 'value' => $this->input->post('class_input_type'), 'type' => '', 'length' => -1),
            array('name' => 'DECLARATION', 'value' => $this->input->post('declaration'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_ID', 'value' => $this->input->post('invoice_id'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_CASE', 'value' => $this->input->post('invoice_case'), 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => $this->input->post('curr_id'), 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $this->input->post('curr_value'), 'type' => '', 'length' => -1),
            array('name' => 'DESCOUNT_TYPE', 'value' => $this->input->post('descount_type'), 'type' => '', 'length' => -1),
            array('name' => 'DESCOUNT_VALUE', 'value' => $this->input->post('descount_value'), 'type' => '', 'length' => -1),
            array('name' => 'VAT_TYPE', 'value' => $this->input->post('vat_type'), 'type' => '', 'length' => -1),
            array('name' => 'VAT_ACCOUNT_ID', 'value' => $this->input->post('vat_account_id'), 'type' => '', 'length' => -1),
            array('name' => 'VAT_VALUE', 'value' => $this->input->post('vat_value'), 'type' => '', 'length' => -1),
            array('name' => 'CLASS_INPUT_CLASS_TYPE', 'value' => $this->input->post('class_input_class_type'), 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_INBOX', 'value' => $this->input->post('invoice_inbox'), 'type' => '', 'length' => -1),
            array('name' => 'GRANT_DATE', 'value' => $this->input->post('grant_date'), 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $this->input->post('account_type'), 'type' => '', 'length' => -1),
            array('name' => 'SEND_ID2', 'value' => $this->input->post('send_id2'), 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_DECLARATION', 'value' => $this->input->post('financial_declaration'), 'type' => '', 'length' => -1),
            array('name' => 'DONATION_FILE_ID', 'value' => $this->input->post('donation_file_id'), 'type' => '', 'length' => -1),
            array('name' => 'DONATION_CURR_VALUE', 'value' => $this->input->post('donation_curr_value'), 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNT_TYPE', 'value' => $this->input->post('customer_account_type'), 'type' => '', 'length' => -1)


        );


        return $result;
    }

    function _postDetailsDataEdit($ser, $id, $class_id, $class_unit, $amount, $price, $taxable_det, $class_price, $donation_file_ser)
    {

        $result = array(

            array('name' => 'STORES_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
            array('name' => 'PRICE', 'value' => $price, 'type' => '', 'length' => -1),
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => 1, 'type' => '', 'length' => -1),
            array('name' => 'TAXABLE_DET', 'value' => $taxable_det, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_PRICE', 'value' => $class_price, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_FILE_SER', 'value' => $donation_file_ser, 'type' => '', 'length' => -1)
        );


        return $result;

    }

    function cancel_transfer_chain3($page = 1)
    {

        $id = $this->input->post('id');

        $result = $this->{$this->MODEL_NAME}->cancel_chain($id);
        $this->return_json($result);


    }

    function cancel_transfer_chain($page = 1)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $q = $this->{$this->MODEL_NAME}->cancel_chain($this->class_input_id);
            if (intval($q) <= 0) {
                $this->print_error('فشل في إلغاء القيد  ؟!' . $q);
            }

            echo "1";


        }

    }

    function public_get_details($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        //  $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['rec_details'] = $this->stores_class_input_detail_model->get_details_all($id);

        $this->load->view('stores_class_input_detail_page', $data);
    }

    function public_get_details_chain($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        //  $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['rec_details'] = $this->stores_class_input_detail_model->get_details_all($id);

        $this->load->view('stores_class_input_detail_chain_page', $data);
    }

    function public_get_details_invoice($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        //  $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['rec_details'] = $this->stores_class_input_detail_model->get_details_all($id);

        $this->load->view('stores_class_input_detail_invoice_page', $data);
    }

    function transferInvoice()
    {
        /*   if(!$this->check_db_for_stores())
               die('CLOSED..');*/
        $id = $this->input->post('id');

        $result = $this->{$this->MODEL_NAME}->transferInvoice($id);
        //  $this->return_json($result);
        echo intval($result);
    }

    function public_get_don_curr_val($donation_file_id = 0, $class_input_date = '')
    {
        $donation_file_id = $this->input->post('donation_file_id') ? $this->input->post('donation_file_id') : $donation_file_id;
        $class_input_date = $this->input->post('class_input_date') ? $this->input->post('class_input_date') : $class_input_date;
        $result = $this->{$this->MODEL_NAME}->currency_get_by_donation($class_input_date, $donation_file_id);
        echo $result;
    }
}

?>
