<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_req_model extends MY_Model
{
    var $PKG_NAME   = "PAYMENT_REQ_PKG";
    var $TABLE_NAME = "PAYMENT_REQ_TB";

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    /* ---------- GET single ---------- */
    function get($req_id = 0)
    {
        $params = array(
            array('name' => ':REQ_ID_IN',    'value' => $req_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET', $params);
    }

    /* ---------- CREATE ---------- */
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_INSERT', $params);
        return $result['MSG_OUT'];
    }

    /* ---------- UPDATE ---------- */
    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    /* ---------- APPROVE ---------- */
    function approve($req_id)
    {
        $params = array(
            array('name' => ':REQ_ID_IN', 'value' => $req_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':MSG_OUT',   'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_APPROVE', $params);
        return $result['MSG_OUT'];
    }

    /* ---------- PAY ---------- */
    function pay($req_id)
    {
        $params = array(
            array('name' => ':REQ_ID_IN', 'value' => $req_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':MSG_OUT',   'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_PAY', $params);
        return $result['MSG_OUT'];
    }

    /* ---------- DELETE (Cancel) ---------- */
    function cancel($req_id, $cancel_note = 'Canceled')
    {
        $params = array(
            array('name' => ':REQ_ID_IN',      'value' => $req_id,      'type' => SQLT_INT, 'length' => -1),
            array('name' => ':CANCEL_NOTE_IN',  'value' => $cancel_note, 'type' => SQLT_CHR, 'length' => 200),
            array('name' => ':MSG_OUT',         'value' => 'MSG_OUT',    'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_DELETE', $params);
        return $result['MSG_OUT'];
    }

    /* ---------- SUMMARY ---------- */
    function get_summary($emp_no, $the_month = null)
    {
        $params = array(
            array('name' => ':EMP_NO_IN',    'value' => $emp_no,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':THE_MONTH_IN', 'value' => $the_month, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'cursor',   'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_SUMMARY_GET', $params);
    }
}
