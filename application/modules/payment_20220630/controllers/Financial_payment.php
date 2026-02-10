<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani & Ahmed Barakat
 * Date: 08/11/14
 * Time: 09:02 ص
 */
class Financial_payment extends MY_Controller
{

    var $MODEL_NAME = 'financial_payment_model';
    // vars
    var $financial_payment_id, $curr_id, $payment_type, $financial_payment_type, $hints,
        $financial_payment_acc_id, $customer_type, $customer_id, $check_id, $check_customer, $check_bank_id, $check_date,
        $receipt_customer_id, $receipt_customer_name;
    // arrays
    var $payment_account_id, $bill_number, $bill_date, $payment_value, $deduction_account_id, $deduction_value, $ser, $d_ser;

     var $url = "payment/financial_payment/get";

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('financial_payment_detail_model');
        $this->load->model('deduction_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('financial/accounts_model');
        $this->load->model('payment_cover_model');
        $this->load->model('settings/CustomerAccountInterface_model');

        $this->financial_payment_id = $this->input->post('financial_payment_id');
        $this->curr_id = $this->input->post('curr_id');
        $this->payment_type = $this->input->post('payment_type');
        $this->financial_payment_type = $this->input->post('financial_payment_type');
        $this->hints = $this->input->post('hints');
        $this->financial_payment_acc_id = $this->input->post('financial_payment_account_id');
        $this->customer_type = $this->input->post('customer_type');
        $this->customer_id = $this->input->post('customer_id');
        $this->check_id = $this->input->post('check_id');
        $this->check_customer = $this->input->post('check_customer');
        $this->check_bank_id = $this->input->post('check_bank_id');
        $this->check_date = $this->input->post('check_date');

        $this->payment_account_id = $this->input->post('payment_account_id');
        $this->bill_number = $this->input->post('bill_number');
        $this->bill_date = $this->input->post('bill_date');
        $this->payment_value = $this->input->post('payment_value');
        $this->deduction_account_id = $this->input->post('deduction_account_id');
        $this->deduction_value = $this->input->post('deduction_value');

        $this->ser = $this->input->post('SER');
        $this->d_ser = $this->input->post('D_SER');


        $this->load->model('root/rmodel');
    }

    function index($page = 1)
    {
        $data['title'] = 'سند الصرف';
        $data['content'] = 'financial_payment_index';
        $data['page'] = $page;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $data['case'] = 1;
        $this->load->view('template/template', $data);
    }


    function archive($page = 1)
    {

        $data['title'] = 'أرشيف سندات الصرف';
        $data['banks'] = $this->constant_details_model->get_list(9);
        $data['currency'] = $this->currency_model->get_all_date(null);
        $data['payment_type'] = $this->constant_details_model->get_list(16);
		$data['ACCOUNT_TYPES'] = $this->constant_details_model->get_list(154);

        $data['content'] = 'financial_payment_archive';

        $data['page'] = $page;
        $data['help'] = $this->help;
        $data['action'] = 'archive';
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template', $data);

    }

    function edit($page = 1)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation(true);


            for ($i = 0; $i < count($this->payment_value); $i++) {
                if ($this->payment_value[$i] == '' or $this->payment_value[$i] <= 0) {
                    $this->print_error('يجب ادخال حساب الانفاق والمبلغ');
                }
                if ($this->financial_payment_type != 1) {
                    $this->bill_number[$i] = '';
                    $this->bill_date[$i] = '';
                }

                if (isset($this->payment_account_id[$i])) {
                    $account_id = $this->payment_account_id[$i];
                } else {
                    $account_id = null;
                }

                if ($this->ser[$i] == 0)
                    $this->financial_payment_detail_model->create($this->_postedData_detail($this->financial_payment_id, $account_id, $this->bill_number[$i], $this->bill_date[$i], $this->payment_value[$i], $this->p_dt_hints[$i]));
                else
                    $this->financial_payment_detail_model->edit($this->_postedData_detail_Edit($this->ser[$i], $account_id, $this->bill_number[$i], $this->bill_date[$i], $this->payment_value[$i], $this->p_dt_hints[$i]));

            }

            for ($i = 0; $i < count($this->deduction_account_id); $i++) {
                if ($this->deduction_account_id[$i] != '' and ($this->deduction_value[$i] == '' or $this->deduction_value[$i] <= 0)
                    or ($this->deduction_account_id[$i] == '' and ($this->deduction_value[$i] != '' and $this->deduction_value[$i] > 0))
                ) {
                    $this->print_error('يجب ادخال حساب الإستقطاع والمبلغ');
                }
                if ($this->d_ser[$i] == 0)
                    $this->deduction_model->create($this->_postedData_detailD($this->financial_payment_id, $this->deduction_account_id[$i], $this->deduction_value[$i], $this->p_deduction_account_type[$i], $this->p_hints[$i], isset($this->p_d_customer_account_type) ? $this->p_d_customer_account_type[$i] : null));
                else
                    $this->deduction_model->edit($this->_postedData_detailD_Edit($this->d_ser[$i], $this->deduction_account_id[$i], $this->deduction_value[$i], $this->p_deduction_account_type[$i], $this->p_d_hints[$i], isset($this->p_d_customer_account_type) ? $this->p_d_customer_account_type[$i] : null));

            }

            $rs = $this->{$this->MODEL_NAME}->edit($this->_postedData_Edit());

            if (intval($rs) <= 0) {
                $this->print_error('فشل في حفظ السند ؟!');
            }
            $this->_notify('adopt', "سند صرف : {$this->p_hints}", $this->financial_payment_id);
            echo "1";


        } else {
            $data['title'] = 'مراجعة و تعديل سند الصرف';
            $data['content'] = 'financial_payment_index';
            $data['page'] = $page;
            $data['help'] = $this->help;
            $data['action'] = 'edit';
            $data['case'] = 1;
            $this->load->view('template/template', $data);
        }
    }

    function _validate_accounts()
    {


        /* for($ix = 0;$ix<count($this->p_payment_account_id);$ix++){
             if(!$this->accounts_model->isAccountExists($this->p_payment_account_id[$ix]) && ($this->p_payment_account_id[$ix] !='' || $this->p_customer_type != 1))
                 $this->print_error('حساب المصروف غير صحيح ');
         }*/

    }

    function _post_validation($isEdit = false)
    {

        /*********** payment *********************/
        if (!isset($this->p_payment_value))
            $this->print_error('يجب إدخال مبلغ الصرف');
        $this->p_payment_value = array_filter($this->p_payment_value);
        if (array_sum($this->p_payment_value) <= 0)
            $this->print_error('يجب إدخال مبلغ الصرف');

        /**********************************************/

        //if($this->p_payment_type == 2 && $this->p_check_id =='')
        //    $this->print_error('يجب إدخال رقم الشيك');


        if ($this->curr_id == '' or $this->payment_type == '' or ($this->financial_payment_type == '' && !$isEdit) or $this->hints == '' or $this->financial_payment_acc_id == '' or ($this->customer_type == '' && !$isEdit) or $this->customer_id == '') {
            $this->print_error('يجب ادخال جميع البيانات');
        } else if ($this->payment_type != 1 and ($this->check_customer == '' or $this->check_bank_id == '' /*or $this->check_date==''*/)) {
            if ($this->payment_type == 3)
                $this->print_error('يجب ادخال بيانات الحوالة');
            else
                $this->print_error('يجب ادخال بيانات الشيك');
        } else if ($this->payment_type == 1) { // نقدا
            $this->check_id = '';
            $this->check_customer = '';
            $this->check_bank_id = '';
            $this->check_date = '';
        }

        if (!($this->payment_account_id) || count($this->payment_account_id) <= 0 or (count($this->payment_account_id) != count($this->payment_value))) {
            $this->print_error('يجب ادخال تفاصيل السند');
        }

        $this->_validate_accounts();

    }

    function create($cover_id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->_post_validation();

            $this->financial_payment_id = $this->{$this->MODEL_NAME}->create($this->_postedData());

            if (intval($this->financial_payment_id) <= 0) {
                $this->print_error('فشل في حفظ السند ؟!'.$this->financial_payment_id);
            }


            $this->payment_account_id = array_filter($this->payment_account_id);
            $this->deduction_account_id = array_filter($this->deduction_account_id);

            for ($i = 0; $i < count($this->payment_value); $i++) {
                if ($this->payment_value[$i] == '' or $this->payment_value[$i] <= 0) {
                    $this->print_error_del('يجب ادخال حساب الانفاق والمبلغ');
                }

                if ($this->financial_payment_type != 1) {
                    $this->bill_number[$i] = '';
                    $this->bill_date[$i] = '';
                }
                if (isset($this->payment_account_id[$i])) {
                    $account_id = $this->payment_account_id[$i];
                } else {
                    $account_id = null;
                }

                $this->financial_payment_detail_model->create($this->_postedData_detail($this->financial_payment_id, $account_id, $this->bill_number[$i], $this->bill_date[$i], $this->payment_value[$i], $this->p_dt_hints[$i]));
            }

            for ($i = 0; $i < count($this->deduction_account_id); $i++) {

                if ($this->deduction_account_id[$i] != '' and ($this->deduction_value[$i] == '' or $this->deduction_value[$i] <= 0)
                    or ($this->deduction_account_id[$i] == '' and ($this->deduction_value[$i] != '' and $this->deduction_value[$i] > 0))
                ) {
                    $this->print_error_del('يجب ادخال حساب الإستقطاع والمبلغ');
                }
                if ($this->deduction_account_id[$i] != '')
                    $this->deduction_model->create($this->_postedData_detailD($this->financial_payment_id,
                        $this->deduction_account_id[$i],
                        $this->deduction_value[$i],
                        $this->p_deduction_account_type[$i],
                        $this->p_d_hints[$i],
                        isset($this->p_d_customer_account_type) ? $this->p_d_customer_account_type[$i] : null
                    ));
            }

            $rs = $this->{$this->MODEL_NAME}->validation($this->financial_payment_id, count($this->payment_value), count($this->deduction_account_id));

            $this->_notify('adopt', "سند صرف : {$this->p_hints}", $this->financial_payment_id);

            echo $rs;

        } else {


            $cover = $this->payment_cover_model->get($cover_id);


            if (count($cover) <= 0)
                redirect('payment/payment_cover/create');

            $cover[0] = preg_replace("/(\r\n|\n|\r)/", "\\n", $cover[0]);

            $data['content'] = 'financial_payment_show';
            $data['title'] = ' سند صرف للموردين والمستفيدين';
            $data['action'] = 'index';
            $data['cover_id'] = $cover_id;
            $data['case'] = 1;

            $data['CUSTOMER_ID'] = $cover[0]['CUSTOMER_ID'];
            $data['CURR_ID'] = $cover[0]['CURR_ID'];
            $data['CUSTOMER_TYPE'] = $cover[0]['CUSTOMER_TYPE'];
            $data['CUSTOMER_ID_NAME'] = $cover[0]['CUSTOMER_ID_NAME'];
            $data['COVER_TYPE'] = $cover[0]['COVER_TYPE'];
            $data['CUST_TYPE'] = $cover[0]['CUST_TYPE'];
            $data['CUSTOMER_ACCOUNT_TYPE'] = $cover[0]['CUSTOMER_ACCOUNT_TYPE'];

            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }

    }

    function print_error_del($msg = '')
    {
        $ret = $this->{$this->MODEL_NAME}->delete($this->financial_payment_id);
        if ($ret == 1)
            $this->print_error('لم يتم حفظ السند: ' . $msg);
        else
            $this->print_error('لم يتم حذف السند: ' . $msg);
    }

    function get_page($page = 1, $case = 1, $action = 'edit')
    {

        $this->load->library('pagination');

        $case = isset($this->p_case) ? $this->p_case : $case;
        $action = isset($this->p_action) ? $this->p_action : $action;


        $sql = $case == 1 ? "  AND   ENTRY_USER={$this->user->id} " : "  ";
        $sql .= " and  FINANCIAL_PAYMENT_CASE between {$case}-1 and {$case} ";

		
		$sql .= $case == 4 && $action == 'review' ? " and FINANCIAL_PAYMENT_CASE between {$case}-1 and {$case} AND B_REVIEW_HINTS_USER IS NOT NULL AND AUDIT_DEP_USER IS NOT NULL" : "";
        $sql .= $case == 4 && $action == 'breview' ? " and FINANCIAL_PAYMENT_CASE between {$case}-1 and {$case} AND B_REVIEW_HINTS_USER IS NULL AND AUDIT_DEP_USER IS NOT NULL" : "";
        $sql .= $case == 4 && $action == 'audit_dep' ? " and FINANCIAL_PAYMENT_CASE between {$case}-1 and {$case} AND AUDIT_DEP_USER IS  NULL" : "";

		
        $sql .= isset($this->p_id) && $this->p_id != null ? " AND  ENTRY_SER= {$this->p_id} " : "";
        $sql .= isset($this->p_name) && $this->p_name != null ? " AND ( FINANCIAL_PKG.ACOUNTS_TB_GET_NAME_ALL(CUSTOMER_ID,CUSTOMER_TYPE) LIKE '%{$this->p_name}%' or CHECK_CUSTOMER LIKE '%{$this->p_name}%' )  " : "";
        $sql .= isset($this->p_check_id) && $this->p_check_id != null ? " AND  (CHECK_ID= '{$this->p_check_id}' or (PAYMENT_TYPE = 3 and TRANSER_ID = {$this->p_check_id})) " : "";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  TRUNC(FINANCIAL_PAYMENT_DATE) >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  TRUNC(FINANCIAL_PAYMENT_DATE) <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_price) && $this->p_price != null ? " AND   PAYMENT_PKG.FINANCIAL_PAYMENT_DET_TOTAL(FINANCIAL_PAYMENT_ID) {$this->p_price_op} {$this->p_price} " : "";
        $sql .= isset($this->p_cover) && $this->p_cover != null ? " AND  COVER_ID in (select COVER_SEQ from PAYMENT_COVER_TB where SER={$this->p_cover}) " : "";


        $count_rs = $this->{$this->MODEL_NAME}->get_count($sql);

        $config['base_url'] = base_url('payment/financial_payment/get_page');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $result = $this->{$this->MODEL_NAME}->get_list($sql, $offset, $row);
        $this->date_format($result, 'FINANCIAL_PAYMENT_DATE');
        $data["payments"] = $result;

        $data['action'] = $action;
        $data['case'] = $case;

        $data['offset'] = $offset + 1;

        $this->load->view('financial_payment_page', $data);
    }

    function get_page_archive($page = 1)
    {

        $this->load->library('pagination');

        $sql = isset($this->p_id) && $this->p_id != null ? " AND  ENTRY_SER= {$this->p_id} " : "";
        $sql .= isset($this->p_name) && $this->p_name != null ? " AND ( FINANCIAL_PKG.ACOUNTS_TB_GET_NAME_ALL(CUSTOMER_ID,CUSTOMER_TYPE) LIKE '%{$this->p_name}%' or CHECK_CUSTOMER LIKE '%{$this->p_name}%' ) " : "";
        $sql .= isset($this->p_check_id) && $this->p_check_id != null ? " AND  (CHECK_ID= '{$this->p_check_id}' or (PAYMENT_TYPE = 3 and TRANSER_ID = {$this->p_check_id})) " : "";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  TRUNC(FINANCIAL_PAYMENT_DATE) >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  TRUNC(FINANCIAL_PAYMENT_DATE) <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_price) && $this->p_price != null ? " AND   PAYMENT_PKG.FINANCIAL_PAYMENT_DET_TOTAL(FINANCIAL_PAYMENT_ID) {$this->p_price_op} {$this->p_price} " : "";
        $sql .= isset($this->p_cover) && $this->p_cover != null ? " AND  COVER_ID in (select COVER_SEQ from PAYMENT_COVER_TB where SER={$this->p_cover}) " : "";
        $sql .= isset($this->p_hints) && $this->p_hints != null ? " AND  HINTS  like '%{$this->p_hints}%' " : "";
        $sql .= isset($this->p_status) && $this->p_status != null ? ($this->p_status == 0 ? " AND  FINANCIAL_PAYMENT_CASE = 0 " : ($this->p_status < 5 ? " AND  FINANCIAL_PAYMENT_CASE = " . ($this->p_status - 1) : " AND  FINANCIAL_PAYMENT_CASE  BETWEEN 4 AND {$this->p_status}")) : "";
        $sql .= isset($this->p_status) && $this->p_status != null && $this->p_status == 7 ? " AND ATTACHMENT_PKG.GFC_ATTACHMENT_TB_COUNT (FINANCIAL_PAYMENT_ID  ,'FINANCIAL_PAYMENT' ) <= 0" : "";
        $sql .= isset($this->p_bank) && $this->p_bank != null ? " AND  CHECK_BANK_ID={$this->p_bank} " : "";
        $sql .= isset($this->p_currency) && $this->p_currency != null ? " AND  CURR_ID={$this->p_currency} " : "";
        $sql .= isset($this->p_invoice_id) && $this->p_invoice_id != null ? " AND  FINANCIAL_PAYMENT_ID  IN ( SELECT FINANCIAL_PAYMENT_ID FROM  FINANCIAL_PAYMENT_DETAIL_TB WHERE BILL_NUMBER ='{$this->p_invoice_id}' ) " : "";

        $sql .= isset($this->p_from_date_receipt_date) && $this->p_from_date_receipt_date != null ? " AND  TRUNC(RECEIPT_DATE) >= '{$this->p_from_date_receipt_date}' " : "";
        $sql .= isset($this->p_to_date_receipt_date) && $this->p_to_date_receipt_date != null ? " AND  TRUNC(RECEIPT_DATE) <= '{$this->p_to_date_receipt_date}' " : "";
        $sql .= isset($this->p_entry_user) && $this->p_entry_user != null ? " AND  USER_PKG.GET_USER_NAME(ENTRY_USER) LIKE '%{$this->p_entry_user}%' " : "";
        $sql .= isset($this->p_payment_type) && $this->p_payment_type != null ? " AND  payment_type = {$this->p_payment_type} " : "";
  $sql .= isset($this->p_customer_account_type) && $this->p_customer_account_type != null ? " AND  customer_account_type = {$this->p_customer_account_type} " : "";


        //entry_user
        //ATTACHMENT_PKG.GFC_ATTACHMENT_TB_COUNT (FINANCIAL_PAYMENT_ID  ,''FINANCIAL_PAYMENT''  )

        $count_rs = $this->{$this->MODEL_NAME}->get_count($sql);

        $config['base_url'] = base_url('payment/financial_payment/archive');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $result = $this->{$this->MODEL_NAME}->get_list($sql, $offset, $row);
        $this->date_format($result, 'FINANCIAL_PAYMENT_DATE');
        $data["payments"] = $result;

        $data['offset'] = $offset + 1;
        $this->load->view('financial_payment_page', $data);

    }

    function get($id, $action = 'index', $case = 1)
    {
        $result = $this->financial_payment_model->get($id);
        $this->date_format($result, 'FINANCIAL_PAYMENT_DATE');
        $data['payments_data'] = $result;

        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['ENTRY_USER'] && $result[0]['FINANCIAL_PAYMENT_CASE'] == 1 && $action == 'edit') ? true : false : false;
        $data['action'] = $action;
        $data['case'] = $case;

        $data['CUST_TYPE'] = count($result) > 0 ? $result[0]['CUST_TYPE'] : '1';

        $data['content'] = 'financial_payment_show';
        $data['title'] = ' سند صرف للموردين والمستفيدين';

        if (count($result) && ($this->user->id == $result[0]['ENTRY_USER'] && $result[0]['FINANCIAL_PAYMENT_CASE'] == 1 && $action == 'edit')) {
            $cover = $this->payment_cover_model->get($result[0]['COVER_ID']);
            $data['C_CUSTOMER_ID'] = $cover[0]['CUSTOMER_ID'];
            $data['C_CURR_ID'] = $cover[0]['CURR_ID'];
            $data['C_CUSTOMER_TYPE'] = $cover[0]['CUSTOMER_TYPE'];
            $data['C_CUSTOMER_ID_NAME'] = $cover[0]['CUSTOMER_ID_NAME'];
            $data['C_COVER_TYPE'] = $cover[0]['COVER_TYPE'];
            $data['C_CUST_TYPE'] = $cover[0]['CUST_TYPE'];
            $data['C_CUSTOMER_ACCOUNT_TYPE'] = $cover[0]['CUSTOMER_ACCOUNT_TYPE'];
        }

        $this->_look_ups($data, count($result) > 0 ? $result[0]['FINANCIAL_PAYMENT_DATE'] : null);
        $this->load->view('template/template', $data);
    }

    function _look_ups(&$data, $date = null)
    {


        $data['currency'] = $this->currency_model->get_all_date($date);

        $data['payment_type'] = $this->constant_details_model->get_list(16);
        $data['financial_payment_type'] = $this->constant_details_model->get_list(17);
        $data['customer_type'] = $this->constant_details_model->get_list(18);
        $data['banks'] = $this->constant_details_model->get_list(9);
        $data['review_hints_type'] = $this->constant_details_model->get_list(112);
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(6);

        $data['help'] = $this->help;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('combotree.css');

        $this->rmodel->package = 'financial_pkg';
        $data['BkFin'] = $this->rmodel->getList('BANKS_FIN_CENTER_ST_TB_LIST', "  ", 0, 1000);
		
		
        $this->_generate_std_urls($data, true);
    }

    function _postedData()
    {
        $result = array(
            array('name' => 'CURR_ID', 'value' => $this->curr_id, 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $this->p_curr_value, 'type' => '', 'length' => -1),
            array('name' => 'PAYMENT_TYPE', 'value' => $this->payment_type, 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_PAYMENT_TYPE', 'value' => $this->financial_payment_type, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $this->hints, 'type' => '', 'length' => -1),
            array('name' => 'FIANANCIAL_PAYMENT_ACC_ID', 'value' => $this->financial_payment_acc_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_TYPE', 'value' => $this->customer_type, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $this->customer_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_ID', 'value' => $this->check_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_CUSTOMER', 'value' => $this->check_customer, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_BANK_ID', 'value' => $this->check_bank_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_DATE', 'value' => $this->check_date, 'type' => '', 'length' => -1),
            array('name' => 'RECEIPT_CUSTOMER_ID', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'RECEIPT_CUSTOMER_NAME', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'COVER_ID', 'value' => $this->p_cover_id, 'type' => '', 'length' => -1),
            array('name' => 'BANK_ACOUNTS_NUMBER', 'value' => $this->p_bank_acounts_number, 'type' => '', 'length' => -1),
            array('name' => 'NOTE', 'value' => $this->p_note, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_BANK_TRANSFER', 'value' => $this->p_convert_bank_transfer, 'type' => '', 'length' => -1),
            array('name' => 'IBAN', 'value' => $this->p_iban, 'type' => '', 'length' => -1),
            array('name' => 'TRNASER_CURR_ID', 'value' => $this->p_trnaser_curr_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNT_TYPE', 'value' => $this->p_customer_account_type, 'type' => '', 'length' => -1),
            array('name' => 'BK_FIN_ID', 'value' => isset($this->p_bk_fin_id) ? $this->p_bk_fin_id : null, 'type' => '', 'length' => -1),


        );


        return $result;
    }

    function _postedData_Edit()
    {
        $result = array(
            array('name' => 'FINANCIAL_PAYMENT_ID', 'value' => $this->financial_payment_id, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => $this->curr_id, 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $this->p_curr_value, 'type' => '', 'length' => -1),
            array('name' => 'PAYMENT_TYPE', 'value' => $this->payment_type, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $this->hints, 'type' => '', 'length' => -1),
            array('name' => 'FIANANCIAL_PAYMENT_ACC_ID', 'value' => $this->financial_payment_acc_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_ID', 'value' => $this->check_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_CUSTOMER', 'value' => $this->check_customer, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_BANK_ID', 'value' => $this->check_bank_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_DATE', 'value' => $this->check_date, 'type' => '', 'length' => -1),
            array('name' => 'BANK_ACOUNTS_NUMBER', 'value' => $this->p_bank_acounts_number, 'type' => '', 'length' => -1),
            array('name' => 'NOTE', 'value' => $this->p_note, 'type' => '', 'length' => -1),
            array('name' => 'CONVERT_BANK_TRANSFER', 'value' => $this->p_convert_bank_transfer, 'type' => '', 'length' => -1),
            array('name' => 'IBAN', 'value' => $this->p_iban, 'type' => '', 'length' => -1),
            array('name' => 'TRNASER_CURR_ID', 'value' => $this->p_trnaser_curr_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $this->p_customer_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_TYPE', 'value' => $this->p_customer_type, 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_PAYMENT_TYPE', 'value' => $this->p_financial_payment_type, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNT_TYPE', 'value' => isset($this->p_customer_account_type) ? $this->p_customer_account_type : null, 'type' => '', 'length' => -1),
            array('name' => 'BK_FIN_ID', 'value' => isset($this->p_bk_fin_id) ? $this->p_bk_fin_id : null, 'type' => '', 'length' => -1),
        );


        return $result;
    }

    function _postedData_detail($financial_payment_id, $payment_account_id, $bill_number, $bill_date, $payment_value, $hints)
    {
        $result = array(
            array('name' => 'FINANCIAL_PAYMENT_ID_IN', 'value' => $financial_payment_id, 'type' => '', 'length' => -1),
            array('name' => 'PAYMENT_ACCOUNT_ID_IN', 'value' => $payment_account_id, 'type' => '', 'length' => -1),
            array('name' => 'BILL_NUMBER_IN', 'value' => $bill_number, 'type' => '', 'length' => -1),
            array('name' => 'BILL_DATE_IN', 'value' => $bill_date, 'type' => '', 'length' => -1),
            array('name' => 'PAYMENT_VALUE_IN', 'value' => $payment_value, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
        );
        return $result;
    }

    function _postedData_detail_Edit($ser, $payment_account_id, $bill_number, $bill_date, $payment_value, $hints)
    {
        $result = array(
            array('name' => 'SER_IN', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'PAYMENT_ACCOUNT_ID_IN', 'value' => $payment_account_id, 'type' => '', 'length' => -1),
            array('name' => 'BILL_NUMBER_IN', 'value' => $bill_number, 'type' => '', 'length' => -1),
            array('name' => 'BILL_DATE_IN', 'value' => $bill_date, 'type' => '', 'length' => -1),
            array('name' => 'PAYMENT_VALUE_IN', 'value' => $payment_value, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
        );

        return $result;
    }

    function _postedData_detailD($financial_payment_id, $account_id, $value, $type, $hints, $d_customer_account_type)
    {
        $result = array(
            array('name' => 'FINANCIAL_PAYMENT_ID', 'value' => $financial_payment_id, 'type' => '', 'length' => -1),
            array('name' => 'DEDUCTION_ACCOUNT_ID', 'value' => $account_id, 'type' => '', 'length' => -1),
            array('name' => 'DEDUCTION_VALUE', 'value' => $value, 'type' => '', 'length' => -1),
            array('name' => 'DEDUCTION_ACCOUNT_TYPE', 'value' => $type, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNT_TYPE', 'value' => $d_customer_account_type, 'type' => '', 'length' => -1),
        );
        return $result;
    }

    function _postedData_detailD_Edit($ser, $account_id, $value, $type, $hints, $d_customer_account_type)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'DEDUCTION_ACCOUNT_ID', 'value' => $account_id, 'type' => '', 'length' => -1),
            array('name' => 'DEDUCTION_VALUE', 'value' => $value, 'type' => '', 'length' => -1),
            array('name' => 'DEDUCTION_ACCOUNT_TYPE', 'value' => $type, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNT_TYPE', 'value' => $d_customer_account_type, 'type' => '', 'length' => -1),
        );

        return $result;
    }

    function public_get_details($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['payment_details'] = $this->financial_payment_detail_model->get_list($id);
        $data['deduction'] = $this->deduction_model->get_list($id);
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(2);

        //print_r($data['deduction']);
        //die;
        $this->load->view('financial_payment_details_page', $data);
    }
    /***************************************** adopts **************************/
    /**
     * Update adopt ..
     */
    function adopt($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->input->post('financial_payment_id');
            $this->_notify('adopt_financial', "سند صرف : {$this->p_hints}", $this->financial_payment_id);
            echo $this->financial_payment_model->adopt($id, 2);


        } else {

            $data['title'] = 'اعتماد سندات الصرف ';
            $data['content'] = 'financial_payment_index';

            $data['page'] = $page;
            $data['case'] = 2;
            $data['action'] = 'adopt';

            $data['help'] = $this->help;

            $this->load->view('template/template', $data);
        }
    }

    function print_payment($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->input->post('financial_payment_id');
            echo $this->financial_payment_model->adopt($id, 5);


        } else {

            $data['title'] = 'طباعة سندات الصرف';
            $data['content'] = 'financial_payment_index';

            $data['page'] = $page;
            $data['case'] = 5;
            $data['action'] = 'print_payment';

            $data['help'] = $this->help;

            $this->load->view('template/template', $data);
        }
    }

    function adopt_financial($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->input->post('financial_payment_id');
            $this->_notify('review', "سند صرف : {$this->p_hints}", $this->financial_payment_id);
            echo $this->financial_payment_model->adopt($id, 3);


        } else {

            $data['title'] = 'الإعتماد المالي لسندات الصرف ';
            $data['content'] = 'financial_payment_index';

            $data['page'] = $page;
            $data['case'] = 3;
            $data['action'] = 'adopt_financial';

            $data['help'] = $this->help;

            $this->load->view('template/template', $data);
        }
    }

    /**
     * Update review ..
     */
    function breview($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->input->post('financial_payment_id');
            echo $this->financial_payment_model->FINANCIAL_PAYMENT_TB_B_UP_HINT($id, $this->p_rnotes);

        } else {

            $data['title'] = 'تدقيق سندات الصرف ';
            $data['content'] = 'financial_payment_index';

            $data['page'] = $page;
            $data['case'] = 4;
            $data['action'] = 'breview';
            $data['help'] = $this->help;
            $this->load->view('template/template', $data);
        }
    }

    function review($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->input->post('financial_payment_id');
            echo $this->financial_payment_model->adopt($id, 4, $this->p_rnotes);

        } else {

            $data['title'] = 'تدقيق سندات الصرف ';
            $data['content'] = 'financial_payment_index';

            $data['page'] = $page;
            $data['case'] = 4;
            $data['action'] = 'review';
            $data['help'] = $this->help;
            $this->load->view('template/template', $data);
        }
    }
	
	 function Audit_dep($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $this->input->post('financial_payment_id');
            echo $this->financial_payment_model->audit_dep($id);

        } else {

            $data['title'] = 'تدقيق سندات الصرف ';
            $data['content'] = 'financial_payment_index';

            $data['page'] = $page;
            $data['case'] = 4;
            $data['action'] = 'audit_dep';
            $data['help'] = $this->help;
            $this->load->view('template/template', $data);
        }
    }
	

    function delivery($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $this->input->post('action');
            $financial_payment_id = intval($this->input->post('id'));
            if ($action == 'get') {
                $customer_type = 2;
                $customer_id = $this->input->post('customer_id');
                $sql = " AND FINANCIAL_PAYMENT_CASE >=4 ";

                if (isset($this->p_fid))
                    $sql .= " and ENTRY_SER={$this->p_fid} ";
                elseif ($customer_type != '' and $customer_id != '')
                    $sql .= " and CUSTOMER_TYPE='{$customer_type}' and CUSTOMER_ID='{$customer_id}' ";
                else
                    $sql .= " and 1=2 ";

                $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($sql, 0, 1);
                $this->return_json($data['get_list']);
            } elseif ($action == 'receipt') {
                $receipt_customer_id = $this->input->post('receipt_customer_id');
                $receipt_customer_name = $this->input->post('receipt_customer_name');
                $receipt_date = $this->input->post('receipt_date');
                if ($financial_payment_id != '' and $receipt_customer_id != '' and $receipt_customer_name != '' and $receipt_date != '') {
                    $param = array(
                        array('name' => 'FINANCIAL_PAYMENT_ID', 'value' => $financial_payment_id, 'type' => '', 'length' => -1),
                        array('name' => 'RECEIPT_CASE', 'value' => 2, 'type' => '', 'length' => -1),
                        array('name' => 'RECEIPT_CUSTOMER_ID', 'value' => $receipt_customer_id, 'type' => '', 'length' => -1),
                        array('name' => 'RECEIPT_CUSTOMER_NAME', 'value' => $receipt_customer_name, 'type' => '', 'length' => -1),
                        array('name' => 'RECEIPT_DATE', 'value' => $receipt_date, 'type' => '', 'length' => -1),
                    );
                    $res = $this->{$this->MODEL_NAME}->receipt($param);

                    if ($res == 1)
                        echo 1;
                    else
                        echo 0;
                } else
                    $this->print_error('ادخل بيانات المستلم');
            }

        } else {

            $data['title'] = 'تسليم مبالغ مالية';
            $data['content'] = 'financial_payment_search';
            $data['page'] = $page;
            $data['case'] = 6;
            $data['action'] = 'delivery';
            $data['help'] = $this->help;
            add_js('jquery.hotkeys.js');
            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');
            $this->load->view('template/template', $data);
        }
    }

    /**
     *  return to lower case ..
     */
    function public_return()
    {

        $id = $this->input->post('financial_payment_id');
        $this->_notify('edit', "سند صرف : {$this->p_hints}", $this->financial_payment_id);
        echo $this->financial_payment_model->adopt($id, 1);
    }

    function delete_details()
    {

        $id = $this->input->post('id');
        $type = $this->input->post('type');

        if ($type == 1) {
            echo $this->financial_payment_detail_model->delete($id);
        } else if ($type == 2) {
            echo $this->deduction_model->delete($id);
        }
    }

    function cancel($page = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $this->input->post('action');
            $financial_payment_id = intval($this->input->post('id'));
            if ($action == 'get') {
                $sql = " AND FINANCIAL_PAYMENT_CASE !=0 ";

                if (isset($this->p_fid))
                    $sql .= " and ENTRY_SER = {$this->p_fid} ";
                else
                    $sql .= " and 1=2 ";

                $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($sql, 0, 1);

                $this->return_json($data['get_list']);


            } elseif ($action == 'cancel') {
                if ($financial_payment_id != '') {
                    $res = $this->{$this->MODEL_NAME}->adopt($financial_payment_id, 0);

                    if ($res == 1)
                        echo 1;
                    else
                        echo 0;
                } else
                    $this->print_error('خطأ في رقم الايصال');
            }

        } else {
            $data['title'] = 'الغاء سند صرف';
            $data['content'] = 'financial_payment_cancel';
            $data['page'] = $page;
            $data['case'] = 0;
            $data['action'] = 'cancel';
            $data['help'] = $this->help;
            add_js('jquery.hotkeys.js');
            $this->load->view('template/template', $data);
        }
    }


    function public_get_invoices($customer, $curr_id)
    {

        $data['rows'] = $this->financial_payment_model->get_invoices($customer, $curr_id);
        $data['content'] = 'financial_payment_invoices';
        $this->load->view('template/view', $data);
    }

    function _notify($action, $message, $id = null)
    {
        if ($id == null)
            $this->_notifyMessage("{$action}", "{$this->url}/{$this->p_financial_payment_id}/{$action}", $message);
        else
            $this->_notifyMessage("{$action}", "{$this->url}/{$id}/{$action}", $message);


    }


    function review_doc()
    {

        $res = $this->{$this->MODEL_NAME}->review_doc($this->p_id, $this->p_notes, $this->p_review_type);

        if ($res == 1)
            echo 1;
        else
            echo 0;

    }

    function review_rest()
    {

        $res = $this->{$this->MODEL_NAME}->review_rest($this->p_financial_payment_id);

        if ($res == 1)
            echo 1;
        else
            echo 0;

    }

}