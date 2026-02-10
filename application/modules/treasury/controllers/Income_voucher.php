<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 07:50 ص
 */
class Income_voucher extends MY_Controller
{

    var $service_id;
    var $server_type;
    var $supscriper_id;
    var $currency_id;

    //----------------------

    var $credit_account_id;
    var $credit;

    var $type;

    function  __construct()
    {
        parent::__construct();
        $this->load->model('income_voucher_model');
        $this->load->model('convert_cash_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/CustomerAccountInterface_model');

        $this->load->model('financial/accounts_model');

        $this->type = $this->input->get('type');

    }

    function index($page = 1)
    {


        $data['title'] = ($this->type == 'service') ? 'إيصالات القبض ' : ($this->type == 'general' ? 'إيصالات القبض العام' : ($this->type == 'main_general' ? 'إيصالات القبض المركزي' : ''));
        $data['content'] = 'income_voucher_index';

        $voucher_type = ($this->type == 'service') ? 1 : ($this->type == 'general' ? 3 : ($this->type == 'main_general' ? 6 : -1));


        $data['page'] = $page;
        $data['sums'] = $this->convert_cash_model->get_debit_sum($this->user->id, null, null, 1, $voucher_type);


        $data['type'] = $this->type;

        $this->load->view('template/template', $data);

    }


    function archive($page = 1)
    {


        $data['title'] = 'أرشيف إيصلات القبض';

        $data['content'] = 'income_voucher_archive';

        $data['page'] = $page;

        $data['currency'] = $this->currency_model->get_all();
        $data['income_type'] = $this->constant_details_model->get_list(4);


        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

         $this->load->view('template/template', $data);

    }

    function _look_ups(&$data)
    {

        $data['currency'] = $this->currency_model->get_all();
        $data['service_type'] = $this->constant_details_model->get_list(6);
        $data['voucher_type'] = $this->constant_details_model->get_list(7);

        $data['voucher_case'] = $this->constant_details_model->get_list(8);
        $data['banks'] = $this->constant_details_model->get_list(9);


        $data['fund_prefix'] = $this->get_system_info('FUND_PREFIX', '1');
        $data['treasure_prefix'] = $this->get_system_info('TREASURE_PREFIX', '1');
        $data['collection_prefix'] = $this->get_system_info('COLLECTION_ACCOUNT_PREFIX', '1');
    }

    function public_get_service($id = 0)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;


        return $this->income_voucher_model->get_service($id);
    }

    function create($request_app_serial = 0)
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            $this->service_id = $this->input->post('service_id');
            $this->server_type = $this->input->post('server_type');
            $this->supscriper_id = $this->input->post('supscriper_id');
            $this->currency_id = $this->input->post('currency_id');

            $fees_type = $this->input->post('fees_type');

            //------------- Check input data is correct ..
            $_data = $this->check_input_voucher($this->service_id, $this->supscriper_id, $this->server_type, $this->currency_id);


            $this->credit_account_id = $this->input->post('credit_account_id');
            $this->credit = $this->input->post('credit');
            //------------ Check posted Details is correct ..
            usort($_data, function ($a, $b) {
                return $a['ACCOUNT_ID'] - $b['ACCOUNT_ID'];
            });

            $this->credit_account_id = array_filter($this->credit_account_id);
            $this->credit = array_filter($this->credit);
            if (count($this->credit) != count($this->credit_account_id)) {
                $this->print_error('يجب إدخال جميع البيانات');
            }

            $this->check_voucher_details($_data, $this->credit_account_id, $this->credit);


            $details_array = array();
            for ($i = 0; $i < count($this->credit_account_id); $i++) {
                $details_string = "0჻{$this->credit_account_id[$i]}჻{$this->credit[$i]}჻1჻0";

                array_push($details_array, $details_string);
            }

            $id = $this->income_voucher_model->create($this->_postedData($details_array,implode(',', $fees_type)));


            //echo $id;
            if (intval($id) <= 0)
                $this->print_error('فشل في حفظ البيانات'.$id);


             //$this->income_voucher_model->INCOME_VOUCHER(implode(',', $fees_type),$id) ;
/*
            for ($i = 0; $i < count($this->credit_account_id); $i++)
                $this->income_voucher_model->create_details($this->_postDetailsData($id, $this->credit_account_id[$i], $this->credit[$i]));

            $rs = $this->income_voucher_model->validation($id, count($_data), implode(',', $fees_type));

            //echo ($rs == 1) ? $id : '0';*/
            echo (intval($id) > 0) ? $id : '0';

        } else {
            add_css('combotree.css');
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_js('jquery.hotkeys.js');
            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');

            $data['title'] = 'إيصال القبض';
            $data['content'] = 'income_voucher_show';
            $data['help'] = $this->help;

            $this->_look_ups($data);

            $income_type = $this->constant_details_model->get_list(4);
            array_pop($income_type);
            array_pop($income_type);
            $data['income_type'] = $income_type;

            $data['branch'] = $this->user->branch;
            $data['request_app_serial'] = $request_app_serial;
            $this->load->view('template/template', $data);

        }
    }

    function create_x()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            $this->service_id = $this->input->post('service_id');
            $this->server_type = $this->input->post('server_type');
            $this->supscriper_id = $this->input->post('supscriper_id');
            $this->currency_id = $this->input->post('currency_id');

            $fees_type = $this->input->post('fees_type');

            //------------- Check input data is correct ..
            $_data = $this->check_input_voucher($this->service_id, $this->supscriper_id, $this->server_type, $this->currency_id);


            $this->credit_account_id = $this->input->post('credit_account_id');
            $this->credit = $this->input->post('credit');
            //------------ Check posted Details is correct ..
            usort($_data, function ($a, $b) {
                return $a['ACCOUNT_ID'] - $b['ACCOUNT_ID'];
            });

            $this->credit_account_id = array_filter($this->credit_account_id);
            $this->credit = array_filter($this->credit);
            if (count($this->credit) != count($this->credit_account_id)) {
                $this->print_error('يجب إدخال جميع البيانات');
            }

            $this->check_voucher_details($_data, $this->credit_account_id, $this->credit);

            $id = $this->income_voucher_model->create($this->_postedData());

            if (intval($id) <= 0)
                $this->print_error('فشل في حفظ البيانات');

            for ($i = 0; $i < count($this->credit_account_id); $i++)
                $this->income_voucher_model->create_details($this->_postDetailsData($id, $this->credit_account_id[$i], $this->credit[$i]));

            $rs = $this->income_voucher_model->validation($id, count($_data), implode(',', $fees_type));

            //echo ($rs == 1) ? $id : '0';
            echo (intval($id) > 0) ? $id : '0';

        } else {
            add_css('combotree.css');
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_js('jquery.hotkeys.js');
            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');

            $data['title'] = 'إيصال القبض';
            $data['content'] = 'income_voucher_show';
            $data['help'] = $this->help;

            $this->_look_ups($data);

            $income_type = $this->constant_details_model->get_list(4);
            array_pop($income_type);
            array_pop($income_type);
            $data['income_type'] = $income_type;

            $data['branch'] = $this->user->branch;

            $this->load->view('template/template', $data);

        }
    }


    /**
     * Update adopt ..
     */
    function adopt()
    {

        $voucher_id = $this->input->post('voucher_id');
        $msg = 0;
        if (is_array($voucher_id)) {
            foreach ($voucher_id as $val) {
                $msg = $this->income_voucher_model->adopt($val, 2);
            }
        }

        echo $msg;

    }

    function get_page($page = 1, $type)
    {

        $this->load->library('pagination');

        $sql = ($type == 'service') ? '1' : ($type == 'general' ? '3' : ($type == 'main_general' ? '6' : '1'));
        $sql = ' and VOUCHER_TYPE =' . $sql;

        $count_rs = $this->income_voucher_model->get_count(" AND VOUCHER_CASE <> 0 and I.CONVERT_CASH_ID is null {$sql} and  I.ENTRY_USER={$this->user->id} ");

        $config['base_url'] = base_url('treasury/income_voucher/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = 1000; //$this->page_size;
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

        $result = $this->income_voucher_model->get_list(" where   VOUCHER_CASE <> 0 AND CONVERT_CASH_ID is null {$sql} and ENTRY_USER={$this->user->id} ", $offset, $row);
        $this->date_format($result, 'VOUCHER_DATE');
        $data["vouchers"] = $result; //$this->income_voucher_model->get_list(null,null,null, $offset , $row );


        $data['user'] = $this->user->id;
        $data['branch'] = $this->user->branch;
        $data['type'] = $type;

        $this->load->view('income_voucher_page', $data);

    }

    function get_page_archive($page = 1)
    {

        $this->load->library('pagination');


        $voucher_id = $this->input->post('voucher_id');
        $voucher_date_from = $this->input->post('voucher_date_from');
        $voucher_date_to = $this->input->post('voucher_date_to');
        $customer = $this->input->post('customer');
        $financial_chains_id = $this->input->post('financial_chains_id');
        $entry_user_name = $this->input->post('entry_user_name');


        $sql = '';

        $sql = $sql . ($voucher_id != null ? " and (I.voucher_id = {$voucher_id} OR I.ENTRY_SER = {$voucher_id}  ) " : '');
        $sql = $sql . ($voucher_date_from != null && $voucher_date_to != null ? " and I.VOUCHER_DATE between '{$voucher_date_from}' and '{$voucher_date_to}' " : '');
        $sql = $sql . ($customer != null ? " and I.CUST_NAME LIKE '%{$customer}%' " : '');
        $sql = $sql . ($financial_chains_id != null ? " and  F.FINANCIAL_CHAINS_ID = {$financial_chains_id} " : '');
        $sql = $sql . (isset($this->p_check_id) && $this->p_check_id != null ? " and  I.check_id = {$this->p_check_id} " : '');
        $sql = $sql . ($entry_user_name != null ? " and U.USER_NAME LIKE  '%{$entry_user_name}%' " : '');
        $sql = $sql . (isset($this->p_curr_id) && $this->p_curr_id != null ? " and I.CURRENCY_ID = {$this->p_curr_id} " : '');
        $sql = $sql . (isset($this->p_income_type) && $this->p_income_type != null ? " and I.INCOME_TYPE = {$this->p_income_type} " : '');
        $sql = $sql . ($this->user->branch != 1 ? " AND I.BRANCHES = {$this->user->branch} " : "");
        $sql = $sql . (isset($this->p_hints) && $this->p_hints ? " AND I.HINTS LIKE '%$this->p_hints%'  " : "");

        $count_rs = $this->income_voucher_model->get_count(" {$sql}  ");

        $config['base_url'] = base_url('treasury/income_voucher/get_page_archive');
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

        $result = $this->income_voucher_model->get_list_archive("  {$sql} ", $offset, $row);
        $this->date_format($result, 'VOUCHER_DATE');
        $data["vouchers"] = $result; //$this->income_voucher_model->get_list(null,null,null, $offset , $row );

        $data['user'] = $this->user->id;
        $data['branch'] = $this->user->branch;

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('income_voucher_archive_page', $data);

    }

    function get_service($id = 0, $isPublic = false,$isMain =false)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['account_type'] = $this->constant_details_model->get_list(15);
        $data['vouchers_details'] = $id > 0 ? $this->income_voucher_model->get_service($id) : array();
        $data['vouchers_details2'] = array();
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(1);

        $data['isPublic'] = $isPublic;
        $data['isMain'] = $isMain;


        $this->load->view('income_voucher_details', $data);
    }


    function public_sub_tb_get($id = 0)
    {

        $rs = $this->income_voucher_model->income_voucher_sub_tb_get($id);

        return $this->return_json($rs);

    }

    function _postedData($details_array = null,$fees_type = null,$voucher_type = 1, $voucher_case = 1)
    {


        $cust_name = $this->input->post('cust_name');
        $voucher_date = date($this->SERVER_DATE_FORMAT); //DateTime::createFromFormat('d/m/Y', $this->input->post('voucher_date'))->format('d-M-Y');


        $income_type = $this->input->post('income_type');
        $debit_account_id = $this->input->post('debit_account_id');
        $hints = $this->input->post('hints');

        $curr_value = $this->input->post('curr_value');

        $check_id = intval($income_type) == 1 ? null : $this->input->post('check_id');
        $check_customer = intval($income_type) == 1 ? null : $this->input->post('check_customer');
        $check_bank_id = intval($income_type) == 1 ? null : $this->input->post('check_bank_id');
        $check_date = intval($income_type) == 1 ? null : ($this->input->post('check_date') ? DateTime::createFromFormat('d/m/Y', $this->input->post('check_date'))->format($this->SERVER_DATE_FORMAT) : null);

        //----------------------------------

        $notes = isset($this->p_notes) ? $this->p_notes : null;

        $convert_cash_id = null;

        $result = array(
            array('name' => 'SERVICE_TYPE', 'value' => $this->server_type, 'type' => '', 'length' => -1),
            array('name' => 'SERVICE_ID', 'value' => $this->service_id, 'type' => '', 'length' => -1),
            array('name' => 'SUPSCRIPER_ID', 'value' => $this->supscriper_id, 'type' => '', 'length' => -1),
            array('name' => 'VOUCHER_DATE', 'value' => $voucher_date, 'type' => '', 'length' => -1),
            array('name' => 'VOUCHER_TYPE', 'value' => $voucher_type, 'type' => '', 'length' => -1),
            array('name' => 'CURRENCY_ID', 'value' => $this->currency_id, 'type' => '', 'length' => -1),
            array('name' => 'INCOME_TYPE', 'value' => $income_type, 'type' => '', 'length' => -1),
            array('name' => 'DEBIT_ACCOUNT_ID', 'value' => $debit_account_id, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => 'VOUCHER_CASE', 'value' => $voucher_case, 'type' => '', 'length' => -1),

            array('name' => 'CONVERT_CASH_ID', 'value' => $convert_cash_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_ID', 'value' => $check_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_CUSTOMER', 'value' => $check_customer, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_BANK_ID', 'value' => $check_bank_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_DATE', 'value' => $check_date, 'type' => '', 'length' => -1),
            array('name' => 'CURR_VALUE', 'value' => $curr_value, 'type' => '', 'length' => -1),
            array('name' => 'CUST_NAME', 'value' => $cust_name, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $notes, 'type' => '', 'length' => -1),
            array('name'=>'INCOME_LIST','value'=>$details_array,'type'=>SQLT_CHR,'length'=>-1),
            array('name' => 'ID_INFO_IN', 'value' => $fees_type, 'type' => '', 'length' => -1),
        );

        if($voucher_type == 6)
            array_pop($result);

        return $result;
    }

    function  _postDetailsData($voucher_id, $credit_account_id, $credit, $account_root_id = null, $account_type = 1)
    {

        $result = array(
            array('name' => 'VOUCHER_ID', 'value' => $voucher_id, 'type' => '', 'length' => -1),
            array('name' => 'CREDIT_ACCOUNT_ID', 'value' => $credit_account_id, 'type' => '', 'length' => -1),
            array('name' => 'CREDIT', 'value' => $credit, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $account_type, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_ROOT_ID', 'value' => $account_root_id, 'type' => '', 'length' => -1),
        );


        return $result;

    }

    function  _postDetailsData2($voucher_id, $credit_account_id, $credit, $account_type = 1)
    {

        $result = array(
            array('name' => 'VOUCHER_ID', 'value' => $voucher_id, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_ID', 'value' => $credit_account_id, 'type' => '', 'length' => -1),
            array('name' => 'CREDIT', 'value' => $credit, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $account_type, 'type' => '', 'length' => -1),
        );

        return $result;

    }


    function  _postSubDetailsData($voucher_id, $sub_no, $value, $note)
    {

        $result = array(
            array('name' => 'VOUCHER_ID_IN', 'value' => $voucher_id, 'type' => '', 'length' => -1),
            array('name' => 'SUB_NO_IN', 'value' => $sub_no, 'type' => '', 'length' => -1),
            array('name' => 'VALUE_IN', 'value' => $value, 'type' => '', 'length' => -1),
            array('name' => 'NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),

        );


        return $result;

    }

    /**
     * check if data posted is meet data in database ..
     * @param $service_id
     * @param $supscriper_id
     * @return mixed
     */
    function check_input_voucher($service_id, $supscriper_id, $server_type, $currency_id)
    {
        $result = $this->income_voucher_model->get_service($service_id);


        if (count($result) <= 0)
            $this->print_error('البيانات غير مطابقة لبيانات الخدمة!!'. '  قد لا يوجد خدمة' );
        if ($result[0]['SHAR_NO'] != $service_id ||
            $result[0]['SUBSCRIBER'] != $supscriper_id ||
            $result[0]['REQ_TYPE'] != $server_type || $result[0]['VALUE_TYPE'] != $currency_id
        )
            $this->print_error('البيانات غير مطابقة لبيانات الخدمة!!');

        return $result;

    }

    function check_voucher_details($data, $credit_account_id, $credit)
    {

        if (count($data) != count($credit)){
			
            $this->print_error('البيانات غير مطابقة لبيانات الخدمة!!');
		}
		
		$sum = 0;
		
        for ($i = 0; $i < count($data); $i++) {
              
				 $sum += $data[$i]['VALUE'];
		 
        }
		
		if ( array_sum($credit) != $sum){
		 
			$this->print_error('البيانات غير مطابقة لبيانات الخدمة!!');
		}
		 

    }



    function general_()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->service_id = null;
            $this->server_type = null;
            $this->supscriper_id = null;

            $credit_sum = 0;

            $this->currency_id = $this->input->post('currency_id');
            $this->credit_account_id = $this->input->post('credit_account_id');
            $this->credit = $this->input->post('credit');


            for ($i = 0; $i < count($this->credit); $i++) {
                $credit_sum += $this->credit[$i];
            }

            if ($credit_sum <= 0) {

                $this->print_error('السند المدخل غير صحيح , يجب إدخال المبالغ المطلوبة');
            }

            $this->_validate_accounts();

            $id = $this->income_voucher_model->create($this->_postedData(null,null,3));

            if (intval($id) <= 0)
                $this->print_error('فشل في حفظ البيانات' . $id);

            $this->credit_account_id = array_filter($this->credit_account_id);
            $this->credit = array_filter($this->credit);
            if (count($this->credit) != count($this->credit_account_id)) {
                $this->print_error('يجب إدخال جميع البيانات');
            }

            for ($i = 0; $i < count($this->credit_account_id); $i++) {
                $this->income_voucher_model->create_details($this->_postDetailsData($id, $this->credit_account_id[$i], $this->credit[$i], $this->p_root_account_id[$i], $this->p_account_type[$i]));
            }

            if (isset($this->p_d2_account_id_name))
                for ($i = 0; $i < count($this->p_d2_account_id_name); $i++) {
                    $this->income_voucher_model->create_details2($this->_postDetailsData2($id, $this->p_d2_account_id[$i], $this->p_d2_credit[$i], $this->p_account_type[$i]));
                }


            if (isset($this->p_sub_no))
                for ($i = 0; $i < count($this->p_sub_no); $i++) {
                    $this->income_voucher_model->income_voucher_sub_tb_insert($this->_postSubDetailsData($id, $this->p_sub_no[$i], $this->p_value[$i], $this->p_note[$i]));
                }


            $rs = $this->income_voucher_model->validation($id, count($this->credit));

            echo ($rs == 1) ? $id : '0';

        } else {
            add_css('combotree.css');
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_js('jquery.hotkeys.js');
            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');

            $data['title'] = 'إيصال قبض عام';
            $data['content'] = 'income_voucher_general';
            $data['help'] = $this->help;

            $this->_look_ups($data);

            $income_type = $this->constant_details_model->get_list(4);
            array_pop($income_type);
            $data['income_type'] = $income_type;

            $data['branch'] = $this->user->branch;

            $this->load->view('template/template', $data);

        }
    }

    function general()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->service_id = null;
            $this->server_type = null;
            $this->supscriper_id = null;

            $credit_sum = 0;

            $this->currency_id = $this->input->post('currency_id');
            $this->credit_account_id = $this->input->post('credit_account_id');
            $this->credit = $this->input->post('credit');


            for ($i = 0; $i < count($this->credit); $i++) {
                $credit_sum += $this->credit[$i];
            }

            if ($credit_sum <= 0) {

                $this->print_error('السند المدخل غير صحيح , يجب إدخال المبالغ المطلوبة');
            }

            $this->_validate_accounts();




            $this->credit_account_id = array_filter($this->credit_account_id);
            $this->credit = array_filter($this->credit);
            if (count($this->credit) != count($this->credit_account_id)) {
                $this->print_error('يجب إدخال جميع البيانات');
            }


            $details_array = array();
            for ($i = 0; $i < count($this->credit_account_id); $i++) {
                $p_root_account_id = isset($this->p_root_account_id)?$this->p_root_account_id[$i]:0;
                $customer_account_type =  isset($this->p_customer_account_type)?$this->p_customer_account_type[$i]:0;
                $details_string = "0჻{$this->credit_account_id[$i]}჻{$this->credit[$i]}჻{$this->p_account_type[$i]}჻{$p_root_account_id}჻{$customer_account_type}";

                array_push($details_array, $details_string);
            }


            $id = $this->income_voucher_model->create($this->_postedData($details_array,null,3));

            if (intval($id) <= 0)
                $this->print_error('فشل في حفظ البيانات' . $id);

            if (isset($this->p_d2_account_id_name))
                for ($i = 0; $i < count($this->p_d2_account_id_name); $i++) {
                    $this->income_voucher_model->create_details2($this->_postDetailsData2($id, $this->p_d2_account_id[$i], $this->p_d2_credit[$i], $this->p_account_type[$i]));
                }

            echo   $id  ;

        } else {
            add_css('combotree.css');
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_js('jquery.hotkeys.js');
            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');

            $data['title'] = 'إيصال قبض عام';
            $data['content'] = 'income_voucher_general';
            $data['help'] = $this->help;

            $this->_look_ups($data);

            $income_type = $this->constant_details_model->get_list(4);
            array_pop($income_type);
            $data['income_type'] = $income_type;

            $data['branch'] = $this->user->branch;

            $this->load->view('template/template', $data);

        }
    }


    function _validate_accounts()
    {


        $this->_validate_sums();

        if (!isset($this->p_debit_account_id) || !$this->accounts_model->isAccountExists($this->p_debit_account_id))
            $this->print_error(' حساب الصندوق  غير صحيح ');

        for ($i = 0; $i < count($this->p_credit_account_id); $i++) {
            if ($this->p_account_type[$i] == 1) {
                if (!$this->accounts_model->isAccountExists($this->p_credit_account_id[$i]))
                    $this->print_error('حساب الإيراد غير صحيح ');
            } else if ($this->p_account_type[$i] == 2) {

            } else if ($this->p_account_type[$i] == 3) {
                if (!$this->accounts_model->isProjectAccountExists($this->p_credit_account_id[$i]))
                    $this->print_error('حساب المشروع غير صحيح ');
            }
        }

        if ($this->p_income_type == 2) {
            if (isset($this->p_d2_account_id)) {
                for ($ix = 0; $ix < count($this->p_d2_account_id); $ix++) {

                    if ($this->p_d2_account_type[$ix] == 1) {
                        if (!$this->accounts_model->isAccountExists($this->p_d2_account_id[$ix]) && $this->p_d2_credit[$ix] > 0)
                            $this->print_error('حساب الإيراد غير صحيح ');
                    } else if ($this->p_d2_account_type[$ix] == 2) {

                    } else if ($this->p_d2_account_type[$ix] == 3) {
                        if (!$this->accounts_model->isProjectAccountExists($this->p_d2_account_id[$ix]))
                            $this->print_error('حساب المشروع غير صحيح ');
                    }
                }
            }
        }
    }

    function  _validate_sums()
    {

        if ($this->p_income_type == 2) {
            if (isset($this->p_d2_account_id)) {
                $sum1 = array_sum($this->p_credit);
                $sum2 = array_sum($this->p_d2_credit);

                if ($sum1 != $sum2)
                    $this->print_error('إجمالي مبلغ تحت التحصيل لا يساوي إجمالي مبلغ الإيراد');
            }
        }

    }

    function main_general_()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->service_id = null;
            $this->server_type = null;
            $this->supscriper_id = null;

            $credit_sum = 0;

            $this->currency_id = $this->input->post('currency_id');
            $this->credit_account_id = $this->input->post('credit_account_id');
            $this->credit = $this->input->post('credit');


            for ($i = 0; $i < count($this->credit); $i++) {
                $credit_sum += $this->credit[$i];
            }

            if ($credit_sum <= 0) {

                $this->print_error('السند المدخل غير صحيح , يجب إدخال المبالغ المطلوبة');
            }

            $this->_validate_accounts();

            $id = $this->income_voucher_model->create($this->_postedData(null,null,6, 1));

            if (intval($id) <= 0)
                $this->print_error('فشل في حفظ البيانات');

            $this->credit_account_id = array_filter($this->credit_account_id);
            $this->credit = array_filter($this->credit);
            if (count($this->credit) != count($this->credit_account_id)) {
                $this->print_error('يجب إدخال جميع البيانات');
            }

            for ($i = 0; $i < count($this->credit_account_id); $i++) {

                $this->income_voucher_model->create_details($this->_postDetailsData($id, $this->credit_account_id[$i], $this->credit[$i], $this->p_root_account_id[$i], $this->p_account_type[$i]));
            }
            if (isset($this->p_d2_account_id_name))
                for ($i = 0; $i < count($this->p_d2_account_id_name); $i++) {
                    if ($this->p_d2_account_id[$i] != '')
                        $this->income_voucher_model->create_details2($this->_postDetailsData2($id, $this->p_d2_account_id[$i], $this->p_d2_credit[$i], $this->p_d2_account_type[$i]));

                }
            $rs = $this->income_voucher_model->validation_cash($id, count($this->credit));


            echo ($rs == 1) ? $id : '0';

        } else {
            add_css('combotree.css');
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_js('jquery.hotkeys.js');
            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');

            $data['title'] = 'إيصال قبض مركزي';
            $data['content'] = 'income_voucher_main_general';
            $data['help'] = $this->help;

            $this->_look_ups($data);

            $income_type = $this->constant_details_model->get_list(4);
            array_pop($income_type);
            $data['income_type'] = $income_type;

            $data['branch'] = $this->user->branch;

            $this->load->view('template/template', $data);

        }
    }

    function main_general()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->service_id = null;
            $this->server_type = null;
            $this->supscriper_id = null;

            $credit_sum = 0;

            $this->currency_id = $this->input->post('currency_id');
            $this->credit_account_id = $this->input->post('credit_account_id');
            $this->credit = $this->input->post('credit');


            for ($i = 0; $i < count($this->credit); $i++) {
                $credit_sum += $this->credit[$i];
            }

            if ($credit_sum <= 0) {

                $this->print_error('السند المدخل غير صحيح , يجب إدخال المبالغ المطلوبة');
            }

            $this->_validate_accounts();


            $this->credit_account_id = array_filter($this->credit_account_id);
            $this->credit = array_filter($this->credit);
            if (count($this->credit) != count($this->credit_account_id)) {
                $this->print_error('يجب إدخال جميع البيانات');
            }



            $details_array = array();
            for ($i = 0; $i < count($this->credit_account_id); $i++) {
                $p_root_account_id = isset($this->p_root_account_id)?$this->p_root_account_id[$i]:0;
                $customer_account_type =  isset($this->p_customer_account_type)?$this->p_customer_account_type[$i]:0;
                $details_string = "0჻{$this->credit_account_id[$i]}჻{$this->credit[$i]}჻{$this->p_account_type[$i]}჻{$p_root_account_id}჻{$customer_account_type}";

                array_push($details_array, $details_string);
            }


            $id = $this->income_voucher_model->create_cash($this->_postedData($details_array,null,6));


            if (intval($id) <= 0)
                $this->print_error('فشل في حفظ البيانات');

            if (isset($this->p_d2_account_id_name))
                for ($i = 0; $i < count($this->p_d2_account_id_name); $i++) {
                    if ($this->p_d2_account_id[$i] != '')
                        $this->income_voucher_model->create_details2($this->_postDetailsData2($id, $this->p_d2_account_id[$i], $this->p_d2_credit[$i], $this->p_d2_account_type[$i]));

                }




            //$rs = $this->income_voucher_model->validation_cash($id, count($this->credit));


            echo $id;

        } else {
            add_css('combotree.css');
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
            add_js('jquery.hotkeys.js');
            add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');

            $data['title'] = 'إيصال قبض مركزي';
            $data['content'] = 'income_voucher_main_general';
            $data['help'] = $this->help;

            $this->_look_ups($data);

            $income_type = $this->constant_details_model->get_list(4);
            array_pop($income_type);
            $data['income_type'] = $income_type;

            $data['branch'] = $this->user->branch;

            $this->load->view('template/template', $data);

        }
    }

    function get($id)
    {


        $data['content'] = 'income_voucher_show';
        $data['help'] = $this->help;

        $this->_look_ups($data);
        $data['branch'] = $this->user->branch;

        $result = $this->income_voucher_model->get($id);

        $data['income_voucher'] = $result;

        if (count($result) > 0) {

            $voucher_type = $result[0]['VOUCHER_TYPE'];

            if ($voucher_type == 1) {
                $this->type = $data['type'] = 'service';
            } else if ($voucher_type == 3) {
                $this->type = $data['type'] = 'general';
            } else if ($voucher_type == 6) {
                $this->type = $data['type'] = 'main_general';
            }

        }

        $income_type = $this->constant_details_model->get_list(4);
        array_pop($income_type);

        $data['income_type'] = $income_type;

        $data['title'] = ($this->type == 'service') ? 'إيصالات القبض ' : ($this->type == 'general' ? 'إيصالات القبض العام' : ($this->type == 'main_general' ? 'إيصالات القبض المركزي' : ''));


        $this->load->view('template/template', $data);
    }

    function public_get_details($id)
    {

        $id = $this->input->post('id') ? $this->input->post('id') : $id;

        $data['vouchers_details'] = $this->income_voucher_model->get_details($id);
        $data['vouchers_details2'] = $this->income_voucher_model->get_details2($id);


        $this->load->view('income_voucher_details_page', $data);
    }

    function cancel()
    {

        echo $this->income_voucher_model->cancel($this->p_id);
    }

    function  public_print()
    {

    }
}