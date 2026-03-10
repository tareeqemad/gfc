<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_req_model extends MY_Model
{
    var $PKG_NAME       = "PAYMENT_REQ_PKG";
    var $BATCH_PKG_NAME = "PAYMENT_BATCH_PKG";
    var $TABLE_NAME     = "PAYMENT_REQ_TB";

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    /* ---------- GET single ---------- */
    function get($req_id = 0)
    {
        $params = array(
            array('name' => ':REQ_ID_IN',   'value' => $req_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
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
            array('name' => ':REQ_ID_IN',     'value' => $req_id,      'type' => SQLT_INT, 'length' => -1),
            array('name' => ':CANCEL_NOTE_IN', 'value' => $cancel_note, 'type' => SQLT_CHR, 'length' => 200),
            array('name' => ':MSG_OUT',        'value' => 'MSG_OUT',    'type' => SQLT_CHR, 'length' => -1),
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

    /* ==========================================================
       BATCH - Server-Side (استدعاء PAYMENT_BATCH_PKG)
       ========================================================== */

    function batch_execute($batch_type, $the_month, $req_type, $wallet_type,
                           $calc_method, $percent_val, $fixed_amount,
                           $pay_type, $emp_list, $note)
    {
        $proc_name = null;
        $params    = array();

        switch ($batch_type) {
            case 'full_salary':
                $proc_name = 'CREATE_FULL_SALARY';
                $params = array(
                    array('name' => ':THE_MONTH_IN',  'value' => $the_month ?: null, 'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':NOTE_IN',       'value' => $note ?: null,      'type' => SQLT_CHR, 'length' => 500),
                    array('name' => ':BATCH_ID_OUT',  'value' => 'BATCH_ID_OUT',     'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':SUCCESS_OUT',   'value' => 'SUCCESS_OUT',      'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':FAIL_OUT',      'value' => 'FAIL_OUT',         'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':MSG_OUT',       'value' => 'MSG_OUT',          'type' => SQLT_CHR, 'length' => 4000),
                );
                break;

            case 'partial_salary':
                $proc_name = 'CREATE_PARTIAL_SALARY';
                $params = array(
                    array('name' => ':THE_MONTH_IN',    'value' => $the_month ?: null,   'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':CALC_METHOD_IN',  'value' => $calc_method ?: null, 'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':PERCENT_VAL_IN',  'value' => $percent_val ?: null, 'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':FIXED_AMOUNT_IN', 'value' => $fixed_amount ?: null,'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':NOTE_IN',         'value' => $note ?: null,        'type' => SQLT_CHR, 'length' => 500),
                    array('name' => ':BATCH_ID_OUT',    'value' => 'BATCH_ID_OUT',       'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':SUCCESS_OUT',     'value' => 'SUCCESS_OUT',        'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':FAIL_OUT',        'value' => 'FAIL_OUT',           'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':MSG_OUT',         'value' => 'MSG_OUT',            'type' => SQLT_CHR, 'length' => 4000),
                );
                break;

            case 'by_list':
                $proc_name = 'CREATE_BY_LIST';
                $params = array(
                    array('name' => ':P_EMP_LIST',      'value' => $emp_list ?: '',         'type' => SQLT_CHR, 'length' => 32767),
                    array('name' => ':THE_MONTH_IN',    'value' => $the_month ?: null,      'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':REQ_TYPE_IN',     'value' => $req_type ?: null,       'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':WALLET_TYPE_IN',  'value' => $wallet_type ?: null,    'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':CALC_METHOD_IN',  'value' => $calc_method ?: null,    'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':PERCENT_VAL_IN',  'value' => $percent_val ?: null,    'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':REQ_AMOUNT_IN',   'value' => $fixed_amount ?: null,   'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':PAY_TYPE_IN',     'value' => $pay_type ?: null,       'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':NOTE_IN',         'value' => $note ?: null,           'type' => SQLT_CHR, 'length' => 500),
                    array('name' => ':BATCH_ID_OUT',    'value' => 'BATCH_ID_OUT',          'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':SUCCESS_OUT',     'value' => 'SUCCESS_OUT',           'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':FAIL_OUT',        'value' => 'FAIL_OUT',              'type' => SQLT_INT, 'length' => -1),
                    array('name' => ':MSG_OUT',         'value' => 'MSG_OUT',               'type' => SQLT_CHR, 'length' => 4000),
                );
                break;

            default:
                return ['ok' => false, 'msg' => 'نوع الدفعة غير صحيح'];
        }

        try {
            $result = $this->conn->excuteProcedures($this->BATCH_PKG_NAME, $proc_name, $params);
            return [
                'ok'       => true,
                'batch_id' => $result['BATCH_ID_OUT'] ?? null,
                'success'  => $result['SUCCESS_OUT'] ?? 0,
                'fail'     => $result['FAIL_OUT'] ?? 0,
                'msg'      => $result['MSG_OUT'] ?? ''
            ];
        } catch (Exception $e) {
            return ['ok' => false, 'msg' => $e->getMessage()];
        }
    }

    /* ==========================================================
       TRANSFER REMAINING TO DUES
       ========================================================== */
    function transfer_remaining($the_month, $pay_type = null, $note = null)
    {
        $params = array(
            array('name' => ':THE_MONTH_IN',      'value' => $the_month,            'type' => SQLT_INT, 'length' => -1),
            array('name' => ':PAY_TYPE_IN',       'value' => $pay_type ?: null,     'type' => SQLT_INT, 'length' => -1),
            array('name' => ':NOTE_IN',           'value' => $note ?: null,         'type' => SQLT_CHR, 'length' => 500),
            array('name' => ':TRANSFERRED_OUT',   'value' => 'TRANSFERRED_OUT',     'type' => SQLT_INT, 'length' => -1),
            array('name' => ':SKIPPED_OUT',       'value' => 'SKIPPED_OUT',         'type' => SQLT_INT, 'length' => -1),
            array('name' => ':TOTAL_AMOUNT_OUT',  'value' => 'TOTAL_AMOUNT_OUT',    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':MSG_OUT',           'value' => 'MSG_OUT',             'type' => SQLT_CHR, 'length' => 4000),
        );

        try {
            $result = $this->conn->excuteProcedures($this->BATCH_PKG_NAME, 'TRANSFER_REMAINING_TO_DUES', $params);
            return [
                'ok'          => true,
                'transferred' => $result['TRANSFERRED_OUT'] ?? 0,
                'skipped'     => $result['SKIPPED_OUT'] ?? 0,
                'total_amount'=> $result['TOTAL_AMOUNT_OUT'] ?? 0,
                'msg'         => $result['MSG_OUT'] ?? ''
            ];
        } catch (Exception $e) {
            return ['ok' => false, 'msg' => $e->getMessage()];
        }
    }
}
