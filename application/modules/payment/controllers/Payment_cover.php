<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 08/12/14
 * Time: 08:49 ص
 */
class Payment_cover extends MY_Controller
{

    var $MODEL_Extract_NAME = 'purchases/Extract_model';

    function __construct()
    {
        parent::__construct();

        $this->load->model('payment_cover_model');
        $this->load->model('payment_cover_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/CustomerAccountInterface_model');
        $this->load->model($this->MODEL_Extract_NAME);
    }


    function index($page = 1)
    {
        $data['title'] = 'نموذج تجهيز معاملة صرف';
        $data['content'] = 'payment_cover_index';
        $data['page'] = $page;
        $data['help'] = $this->help;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $this->load->view('template/template', $data);
    }

    function edit()
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (($this->p_customer_type == 2) and ($this->p_customer_account_type == ''))
                $this->print_error('يجب اختيار نوع الحساب');


            $id = $this->payment_cover_model->edit($this->_postedData(false));

            if (intval($id) <= 0) {
                $this->print_error('فشل في حفظ البيانات ؟!');
            }

            $this->payment_cover_details_model->delete($this->p_cover_seq);

            foreach ($this->p_attachments as $row) {
                $this->payment_cover_details_model->create($this->_postDataAttachments($this->p_cover_seq, $row));
            }

            echo $id;

        } else {
            $this->_look_ups($data);
            $data['action'] = 'create';
            $data['extract_data'] = array();
            $data['is_extract'] = false;
            $this->load->view('template/template', $data);
        }
    }

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'COVER_SEQ', 'value' => $this->p_cover_seq, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $this->p_customer_id, 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_ID', 'value' => $this->p_invoice_id, 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_VALUE', 'value' => $this->p_invoice_value, 'type' => '', 'length' => -1),
            array('name' => 'INVOICE_DATE', 'value' => $this->p_invoice_date, 'type' => '', 'length' => -1),
            array('name' => 'CURR_ID', 'value' => $this->p_curr_id, 'type' => '', 'length' => -1),
            array('name' => 'REQUEST_ID', 'value' => $this->p_request_id, 'type' => '', 'length' => -1),
            array('name' => 'SUPPLY_ID', 'value' => $this->p_supply_id, 'type' => '', 'length' => -1),
            array('name' => 'COVER_TYPE', 'value' => "$this->p_cover_type", 'type' => '', 'length' => -1),
            array('name' => 'FINANICAL_STATEMENT', 'value' => "$this->p_finanical_statement", 'type' => '', 'length' => -1),
            array('name' => 'ELEC_WANTS', 'value' => "$this->p_elec_wants", 'type' => '', 'length' => -1),
            array('name' => 'INVONTRY_MONITER', 'value' => "$this->p_invontry_moniter", 'type' => '', 'length' => -1),
            array('name' => 'TREASURY_DEP', 'value' => "$this->p_treasury_dep", 'type' => '', 'length' => -1),
            array('name' => 'EDUIT_DEP', 'value' => "$this->p_eduit_dep", 'type' => '', 'length' => -1),
            array('name' => 'FINANCIAL_CHAINS_ID', 'value' => $this->p_financial_chains_id, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_TYPE', 'value' => $this->p_customer_type, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_NAME', 'value' => $this->p_customer_name, 'type' => '', 'length' => -1),
            array('name' => 'SELLING_DEP', 'value' => $this->p_selling_dep, 'type' => '', 'length' => -1),
            array('name' => 'PURCHESES_TYPE', 'value' => $this->p_purcheses_type, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ACCOUNT_TYPE', 'value' => isset($this->p_customer_account_type) ? $this->p_customer_account_type : null, 'type' => '', 'length' => -1),

        );

        if ($isCreate) {
            array_shift($result);
            $result[]= array('name' => 'EXTRACT_SER', 'value' => $this->p_extract_ser, 'type' => '', 'length' => -1);
        }
        return $result;
    }

    function create($ser = '')
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (($this->p_customer_type == 2) and ($this->p_customer_account_type == ''))
                $this->print_error('يجب اختيار نوع الحساب');

            $id = $this->payment_cover_model->create($this->_postedData());

            if (intval($id) <= 0) {
                $this->print_error('فشل في حفظ البيانات ؟!');
            }

            if (isset($this->p_attachments))
                foreach ($this->p_attachments as $row) {
                    $this->payment_cover_details_model->create($this->_postDataAttachments($id, $row));
                }

            echo $id;
        } else {
            $this->_look_ups($data);
            $data['action'] = 'create';
            if ($ser != '') {
                $result = $this->Extract_model->get($ser);
                if (count($result) == 0 || $result[0]['ADOPT'] !=30)
                    die;

                $data['extract_data'] = $result;
                $data['is_extract'] = true;

            }
            else
            {
                $data['extract_data'] = array();
                $data['is_extract'] = false;
            }
            $this->load->view('template/template', $data);
        }
    }

    function _postDataAttachments($cover_id, $id, $type = 1, $hints = null)
    {
        $result = array(
            array('name' => 'SEQ', 'value' => $cover_id, 'type' => '', 'length' => -1),
            array('name' => 'CONSTANT_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'TYPE', 'value' => $type, 'type' => '', 'length' => -1),
            array('name' => 'HINTS', 'value' => $hints, 'type' => '', 'length' => -1),


        );

        return $result;
    }

    function _look_ups(&$data)
    {


        $data['currency'] = $this->currency_model->get_all();
        $data['attachments'] = $this->constant_details_model->get_list(30);
        $data['purcheses_type'] = $this->constant_details_model->get_list(71);
        $data['ACCOUNT_TYPES'] = $this->CustomerAccountInterface_model->customers_account_interf_acc(6);

        add_js('jquery.hotkeys.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['account_type'] = $this->constant_details_model->get_list(15);

        $data['title'] = 'نموذج تجهيز معاملة صرف';
        $data['content'] = 'payment_cover_show';
        $data['help'] = $this->help;
        $data['currency'] = $this->currency_model->get_all();

    }

    function get($id)
    {
        $result = $this->payment_cover_model->get($id);
        $this->date_format($result, array('INVOICE_DATE', 'ENTERY_DATE'));
        $data['payments_data'] = $result;

        $details = $this->payment_cover_details_model->get($id);
        $data['details'] =/*count($details) > 0 ? */
            json_encode($details) /*: '{}'*/
        ;

        $data['action'] = 'edit';
        $data['extract_data'] = array();
        $data['is_extract'] = false;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function get_page($page = 1)
    {

        $this->load->library('pagination');

        $sql = isset($this->p_id) && $this->p_id != null ? " AND  SER= {$this->p_id} " : "";
        $sql .= isset($this->p_name) && $this->p_name != null ? " AND  (FINANCIAL_PKG.ACOUNTS_TB_GET_NAME_ALL(CUSTOMER_ID,CUSTOMER_TYPE) LIKE '%{$this->p_name}%' OR CUSTOMER_NAME LIKE '%{$this->p_name}%' ) " : "";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  ENTERY_DATE >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  ENTERY_DATE <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_entry_user) && $this->p_entry_user != null ? " AND  USER_PKG.GET_USER_NAME( ENTER_USER ) like '%{$this->p_entry_user}%' " : "";
        $sql .= isset($this->p_hint) && $this->p_hint != null ? " AND  cover_type like '%{$this->p_hint}%' " : "";


        $count_rs = $this->payment_cover_model->get_count($sql);

        $config['base_url'] = base_url('payment/payment_cover/get_page');
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

        $result = $this->payment_cover_model->get_list($sql, $offset, $row);

        $this->date_format($result, array('INVOICE_DATE', 'ENTERY_DATE'));

        $data["rows"] = $result;

        $data['action'] = 'index';
        $data['offset'] = $offset + 1;

        $this->load->view('payment_caver_page', $data);

    }
}