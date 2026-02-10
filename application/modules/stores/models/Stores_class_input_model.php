<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/11/14
 * Time: 09:24 ص
 */
class stores_class_input_model extends MY_Model
{
    var $PKG_NAME = "STORES_PKG";
    var $TABLE_NAME = 'STORES_CLASS_INPUT_TB';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id = 0)
    {

        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET', $params);
        return $result;
    }

    function get_count($sql)
    {


        $params = array(
            array('name' => ':XSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500)
        );
        $result = $this->New_rmodel->general_get('QF_PKG', 'GET_COUNT_TAB', $params);
        return $result;
    }

    function get_list($sql, $offset, $row)
    {


        $params = array(

            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'STORES_CLASS_INPUT_GET_LIST', $params);

        return $result;
    }

    //lana
    function store_class_input_adopt_get_list($sql, $offset, $row)
    {


        $params = array(

            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'STORES_CLASS_INPUT_ADOPT_LIST', $params);

        return $result;
    }

    function list_mk($sql, $offset, $row)
    {

        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'STORES_CLASS_INPUT_LIST', $params);
        return $result;
    }

    function get_all()
    {

        $params = array(
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET_ALL', $params);
        return $result;
    }

    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_INSERT', $params);

        return $result['MSG_OUT'];
    }

    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    function delete($id)
    {
        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_DELETE', $params);
        return $result['MSG_OUT'];
    }

    function update_after_queed($id, $type, $dec, $fin_dec, $grant_date, $vat_type, $vat_val, $donation_curr_value)
    {
        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':CLASS_INPUT_TYPE_IN', 'value' => $type, 'type' => '', 'length' => -1),
            array('name' => ':DECLARATION_IN', 'value' => $dec, 'type' => '', 'length' => -1),
            array('name' => ':FINANCIAL_DECLARATION_IN', 'value' => $fin_dec, 'type' => '', 'length' => -1),
            array('name' => ':GRANT_DATE_IN', 'value' => $grant_date, 'type' => '', 'length' => -1),
            array('name' => ':VAT_TYPE_IN', 'value' => $vat_type, 'type' => '', 'length' => -1),
            array('name' => ':VAT_VALUE_IN', 'value' => $vat_val, 'type' => '', 'length' => -1),
            array('name' => ':DONATION_CURR_VALUE_IN', 'value' => $donation_curr_value, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'STORES_CLASS_INPUT_AFTER_QUEED', $params);
        return $result['MSG_OUT'];
    }

//tasneem
    function refresh_queed($id)
    {
        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures('STORES_QUEED_PKG', 'STORES_CLASS_INPUT_QUEED', $params);
        return $result['MSG_OUT'];
    }

    function adopt($id)
    {
        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_ADOPT', $params);
        return $result['MSG_OUT'];
    }

    function adopt0($id)
    {
        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_CANCEL', $params);
        return $result['MSG_OUT'];
    }

    function transfer($id)
    {
        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_TRANSFER', $params);
        return $result['MSG_OUT'];
    }

    function transfer_chain($id)
    {
        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_CHAIN', $params);
        return $result['MSG_OUT'];
    }

    function update_case($id, $case)
    {
        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':INVOICE_CASE_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_CASE', $params);
        return $result['MSG_OUT'];
    }

    //
    function transferInvoice($id)
    {
        $params = array(
            array('name' => ':REC_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'STORES_CLASS_INPUT_TO_INVOICE', $params);
        return $result['MSG_OUT'];
    }

    function storesInvoice($id)
    {

        $params = array(
            array('name' => ':REC_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'STORES_CLASS_INPUT_INVOICE', $params);
        return $result;
    }

    function cancel_chain($id)
    {
        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'STORES_CLASS_INPUT_CANCEL_CHA', $params);
        return $result['MSG_OUT'];
    }

    function currency_get_by_donation($the_date, $donation_file_id)
    {
        $params = array(
            array('name' => ':THE_DATE', 'value' => $the_date, 'type' => '', 'length' => -1),
            array('name' => ':DONATION_FILE_ID_IN', 'value' => $donation_file_id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('PAYMENT_PKG', 'CURRENCY_TB_GET_BY_DONATION', $params);;

        return $result['MSG_OUT'];

    }
}
