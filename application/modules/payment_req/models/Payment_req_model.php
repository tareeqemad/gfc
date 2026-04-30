<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment_req_model extends MY_Model
{
    var $PKG_NAME       = "GFC_PAK.DISBURSEMENT_PKG";
    var $BATCH_PKG_NAME = "GFC_PAK.DISBURSEMENT_BATCH_PKG";
    var $TABLE_NAME     = "PAYMENT_REQ_TB";

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    // =========================================================
    // MASTER — GET single
    // =========================================================
    function get($req_id = 0)
    {
        $params = array(
            array('name' => ':P_REQ_ID',    'value' => $req_id,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',  'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_GET', $params);
    }

    // =========================================================
    // MASTER — CREATE (returns REQ_ID or error)
    // =========================================================
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_INSERT', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // MASTER — UPDATE
    // =========================================================
    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // MASTER — APPROVE
    // =========================================================
    function approve($req_id)
    {
        $params = array(
            array('name' => ':P_REQ_ID',  'value' => $req_id,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_APPROVE', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // MASTER — PAY
    // =========================================================
    function pay($req_id)
    {
        $params = array(
            array('name' => ':P_REQ_ID',  'value' => $req_id,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_PAY', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // MASTER — CANCEL
    // =========================================================
    function cancel($req_id, $cancel_note = 'Cancelled')
    {
        $params = array(
            array('name' => ':P_REQ_ID',      'value' => $req_id,      'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_CANCEL_NOTE', 'value' => $cancel_note, 'type' => SQLT_CHR, 'length' => 200),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG_OUT',    'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_CANCEL', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // MASTER — DELETE (حذف فعلي — مسودة ومعتمد فقط)
    // =========================================================
    function delete_request($req_id)
    {
        if ($this->conn->user != 'GFC_PAK') {
            $this->conn = new DBConn();
        }
        $params = array(
            array('name' => ':P_REQ_ID',  'value' => $req_id,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT', 'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_DELETE', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // MASTER — LIST (paginated)
    // =========================================================
    function get_list($where_sql, $offset, $page_size)
    {
        $params = array(
            array('name' => ':P_INSQL',     'value' => $where_sql, 'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':P_OFFSET',    'value' => $offset,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_PAGE_SIZE', 'value' => $page_size, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',   'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_LIST', $params);
    }

    function get_list_master($where_sql, $offset, $page_size)
    {
        $params = array(
            array('name' => ':P_INSQL',     'value' => $where_sql, 'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':P_OFFSET',    'value' => $offset,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_PAGE_SIZE', 'value' => $page_size, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',   'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_LIST_MASTER', $params);
    }

    // =========================================================
    // DETAIL — ADD employee to request
    // =========================================================
    function detail_add($req_id, $emp_no, $req_amount, $note = '')
    {
        $params = array(
            array('name' => ':P_REQ_ID',     'value' => (int)$req_id,       'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_EMP_NO',     'value' => (int)$emp_no,       'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_AMOUNT', 'value' => (float)$req_amount, 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':P_NOTE',       'value' => $note ?: null,      'type' => SQLT_CHR, 'length' => 500),
            array('name' => ':P_MSG_OUT',    'value' => 'MSG_OUT',          'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DETAIL_INSERT', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // DETAIL — UPDATE employee row
    // =========================================================
    function detail_update($detail_id, $req_amount, $note = '')
    {
        $params = array(
            array('name' => ':P_DETAIL_ID',  'value' => (int)$detail_id,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_AMOUNT', 'value' => (float)$req_amount, 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':P_NOTE',       'value' => $note ?: null,      'type' => SQLT_CHR, 'length' => 500),
            array('name' => ':P_MSG_OUT',    'value' => 'MSG_OUT',          'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DETAIL_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // DETAIL — DELETE employee from request
    // =========================================================
    function detail_delete($detail_id)
    {
        $params = array(
            array('name' => ':P_DETAIL_ID', 'value' => (int)$detail_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG_OUT',       'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DETAIL_DELETE', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // DETAIL — APPROVE selected employees
    // =========================================================
    function detail_approve($detail_ids)
    {
        $params = array(
            array('name' => ':P_DETAIL_IDS', 'value' => $detail_ids, 'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':P_MSG_OUT',    'value' => 'MSG_OUT',   'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DETAIL_APPROVE', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // DETAIL — PAY selected employees
    // =========================================================
    function detail_pay($detail_ids)
    {
        $params = array(
            array('name' => ':P_DETAIL_IDS', 'value' => $detail_ids, 'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':P_MSG_OUT',    'value' => 'MSG_OUT',   'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DETAIL_PAY', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // DETAIL — LIST employees in a request
    // =========================================================
    function get_details($req_id)
    {
        $params = array(
            array('name' => ':P_REQ_ID',    'value' => (int)$req_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',     'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',    'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'DETAIL_LIST', $params);
    }

    function get_log($req_id)
    {
        $params = array(
            array('name' => ':P_REQ_ID',    'value' => (int)$req_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',     'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',    'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_LOG_LIST', $params);
    }

    // =========================================================
    // SUMMARY
    // =========================================================
    function get_summary($emp_no, $the_month = null)
    {
        $params = array(
            array('name' => ':P_EMP_NO',    'value' => $emp_no,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_THE_MONTH', 'value' => $the_month, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',   'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_SUMMARY_GET', $params);
    }

    // =========================================================
    // TOTALS
    // =========================================================
    function get_totals($where_sql)
    {
        $params = array(
            array('name' => ':P_INSQL',     'value' => $where_sql, 'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',   'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => -1),
        );
        try {
            $rows = $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_TOTALS', $params);
            $r = (is_array($rows) && count($rows) > 0) ? $rows[0] : [];
            return array(
                'ok'               => true,
                'count'            => (int)($r['CNT'] ?? 0),
                'amount'           => (float)($r['TOTAL_AMOUNT'] ?? 0),
                'active_amount'    => (float)($r['ACTIVE_AMOUNT'] ?? 0),
                'active_count'     => (int)($r['ACTIVE_COUNT'] ?? 0),
                'cancelled_amount' => (float)($r['CANCELLED_AMOUNT'] ?? 0),
                'cancelled_count'  => (int)($r['CANCELLED_COUNT'] ?? 0),
                't1_amount'  => (float)($r['T1_AMOUNT'] ?? 0), 't1_count'  => (int)($r['T1_COUNT'] ?? 0),
                't2_amount'  => (float)($r['T2_AMOUNT'] ?? 0), 't2_count'  => (int)($r['T2_COUNT'] ?? 0),
                't3_amount'  => (float)($r['T3_AMOUNT'] ?? 0), 't3_count'  => (int)($r['T3_COUNT'] ?? 0),
                's0_amount'  => (float)($r['S0_AMOUNT'] ?? 0), 's0_count'  => (int)($r['S0_COUNT'] ?? 0),
                's1_amount'  => (float)($r['S1_AMOUNT'] ?? 0), 's1_count'  => (int)($r['S1_COUNT'] ?? 0),
                's2_amount'  => (float)($r['S2_AMOUNT'] ?? 0), 's2_count'  => (int)($r['S2_COUNT'] ?? 0),
            );
        } catch (Exception $e) {
            return array('ok' => false, 'count' => 0, 'amount' => 0,
                         'active_amount' => 0, 'cancelled_amount' => 0,
                         'active_count' => 0, 'cancelled_count' => 0);
        }
    }

    // =========================================================
    // TOTALS BRANCH
    // =========================================================
    function get_totals_branch($where_sql)
    {
        $params = array(
            array('name' => ':P_INSQL',     'value' => $where_sql, 'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',   'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => -1),
        );
        try {
            $rows = $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_TOTALS_BRANCH', $params);
            return array('ok' => true, 'branches' => is_array($rows) ? $rows : []);
        } catch (Exception $e) {
            return array('ok' => false, 'branches' => []);
        }
    }

    // =========================================================
    // BATCH APPROVE
    // =========================================================
    function approve_batch($the_month, $req_ids = null)
    {
        $params = array(
            array('name' => ':P_THE_MONTH', 'value' => (int)$the_month,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_IDS',   'value' => $req_ids ?: null, 'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG_OUT',        'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_APPROVE_BATCH', $params);
        return $this->_parse_batch_result($result['MSG_OUT'] ?? '0|0');
    }

    // =========================================================
    // BATCH PAY
    // =========================================================
    function pay_batch($the_month, $req_ids = null)
    {
        $params = array(
            array('name' => ':P_THE_MONTH', 'value' => (int)$the_month,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_IDS',   'value' => $req_ids ?: null, 'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG_OUT',        'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_PAY_BATCH', $params);
        return $this->_parse_batch_result($result['MSG_OUT'] ?? '0|0');
    }

    // =========================================================
    // MONTH WALLET TOTALS (for check_month_status)
    // =========================================================
    // =========================================================
    // MONTH STATUS
    // =========================================================
    function get_month_status($month)
    {
        $p = array(
            array('name' => ':P_THE_MONTH', 'value' => (int)$month,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',       'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',      'type' => SQLT_CHR, 'length' => 4000),
        );
        try {
            $rows = $this->New_rmodel->general_get($this->PKG_NAME, 'GET_MONTH_STATUS', $p, 1);
            return (is_array($rows) && count($rows) > 0) ? $rows[0] : [];
        } catch (Exception $e) {
            return [];
        }
    }

    // =========================================================
    // BULK PREVIEW
    // =========================================================
    function bulk_preview($params)
    {
        $p = array(
            array('name' => ':P_THE_MONTH',   'value' => (int)$params['the_month'],           'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_TYPE',    'value' => (int)$params['req_type'],            'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_CALC_METHOD', 'value' => (int)($params['calc_method'] ?: -1), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_PERCENT_VAL', 'value' => (int)($params['percent_val'] ?: -2), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_AMOUNT',  'value' => (int)($params['req_amount']  ?: -3), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_PAY_TYPE',    'value' => (int)($params['pay_type']    ?: -4), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_SAL_FROM',    'value' => (int)($params['sal_from']    ?: -5), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_SAL_TO',      'value' => (int)($params['sal_to']      ?: -6), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_BRANCH_NO',   'value' => (int)($params['branch_no']   ?: -7), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_L_VALUE',     'value' => (int)($params['l_value']     ?: -8), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_H_VALUE',     'value' => (int)($params['h_value']     ?: -9), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',   'value' => 'cursor',  'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',       'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 4000),
        );
        try {
            $rows = $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_BULK_PREVIEW', $p, 1);
            return array('msg' => '1', 'rows' => is_array($rows) ? $rows : []);
        } catch (Exception $e) {
            return array('msg' => $e->getMessage(), 'rows' => []);
        }
    }

    // =========================================================
    // BULK CREATE (1 master + N details)
    // =========================================================
    function bulk_create($params)
    {
        if ($this->conn->user != 'GFC_PAK') {
            $this->conn = new DBConn();
        }

        $p = array(
            array('name' => ':P_THE_MONTH',   'value' => (int)$params['the_month'],           'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_TYPE',    'value' => (int)$params['req_type'],            'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_CALC_METHOD', 'value' => (int)($params['calc_method'] ?: -1), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_PERCENT_VAL', 'value' => (int)($params['percent_val'] ?: -2), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_AMOUNT',  'value' => (int)($params['req_amount']  ?: -3), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_PAY_TYPE',    'value' => (int)($params['pay_type']    ?: -4), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_SAL_FROM',    'value' => (int)($params['sal_from']    ?: -5), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_SAL_TO',      'value' => (int)($params['sal_to']      ?: -6), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_BRANCH_NO',   'value' => (int)($params['branch_no']   ?: -7), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_L_VALUE',     'value' => (int)($params['l_value']     ?: -8), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_H_VALUE',     'value' => (int)($params['h_value']     ?: -9), 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_NOTE',        'value' => $params['note'] ?: 'N/A',            'type' => SQLT_CHR, 'length' => 500),
            array('name' => ':P_MSG_OUT',     'value' => 'MSG_OUT',                            'type' => SQLT_CHR, 'length' => 4000),
        );
        try {
            $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_BULK_CREATE', $p);
            return $this->_parse_bulk_result($result['MSG_OUT'] ?? '0|0|0|0');
        } catch (Exception $e) {
            return array('ok' => false, 'msg' => $e->getMessage());
        }
    }

    // =========================================================
    // Private — parse batch result 'SUCCESS|ERRORS'
    // =========================================================
    private function _parse_batch_result($msg)
    {
        $parts   = explode('|', $msg);
        $success = (int)($parts[0] ?? 0);
        $errors  = (int)($parts[1] ?? 0);

        if ($success === 0 && $errors === 0) {
            return array(
                'ok' => false, 'success' => 0, 'errors' => 0,
                'msg' => 'لا توجد سجلات مؤهلة لهذه العملية',
            );
        }

        return array(
            'ok'      => ($success > 0),
            'success' => $success,
            'errors'  => $errors,
            'msg'     => $errors > 0
                ? "تمت العملية على {$success} طلب - فشل {$errors}"
                : "تمت العملية بنجاح على {$success} طلب",
        );
    }

    // =========================================================
    // Private — parse bulk create result 'REQ_ID|SUCCESS|SKIPPED|ERRORS'
    // =========================================================
    private function _parse_bulk_result($msg)
    {
        $parts   = explode('|', $msg);
        $req_id  = (int)($parts[0] ?? 0);
        $success = (int)($parts[1] ?? 0);
        $skipped = (int)($parts[2] ?? 0);
        $errors  = (int)($parts[3] ?? 0);

        return array(
            'ok'      => ($req_id > 0),
            'req_id'  => $req_id,
            'success' => $success,
            'skipped' => $skipped,
            'errors'  => $errors,
            'msg'     => $req_id > 0
                ? "تم إنشاء الطلب #{$req_id} يضم {$success} موظف" .
                    ($skipped > 0 ? " - تخطي {$skipped}" : '') .
                    ($errors  > 0 ? " - فشل {$errors}" : '')
                : (isset($parts[4]) ? $parts[4] : 'فشل الإنشاء'),
        );
    }

    // =========================================================
    // DISBURSEMENT BATCH — تجهيز الصرف: معاينة
    // =========================================================
    function batch_preview($req_ids)
    {
        $params = array(
            array('name' => ':P_REQ_IDS',   'value' => $req_ids, 'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',  'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => 4000),
        );
        try {
            $rows = $this->New_rmodel->general_get($this->BATCH_PKG_NAME, 'PAYMENT_REQ_BATCH_PREVIEW', $params);
            return array('ok' => true, 'rows' => is_array($rows) ? $rows : []);
        } catch (Exception $e) {
            return array('ok' => false, 'rows' => [], 'msg' => $e->getMessage());
        }
    }

    // =========================================================
    // DISBURSEMENT BATCH — تحذيرات: طلبات فيها موظفين غير معتمدين
    // =========================================================
    function batch_warnings($req_ids)
    {
        $safe_ids = implode(',', array_map('intval', explode(',', $req_ids)));
        if (!$safe_ids) return [];

        // نستخدم get_list_master الموجود لجلب بيانات الطلبات
        // ثم نقارن عدد الموظفين الكلي مع المعتمدين من preview
        // أبسط: نمرر على كل طلب ونجلب تفاصيله
        $warnings = [];
        $ids = array_map('intval', explode(',', $safe_ids));
        foreach ($ids as $rid) {
            if ($rid <= 0) continue;
            try {
                $details = $this->get_details($rid);
                if (!is_array($details)) continue;
                $draft = 0; $approved = 0; $total = 0;
                foreach ($details as $d) {
                    $st = (int)($d['DETAIL_STATUS'] ?? 0);
                    if ($st == 9) continue; // skip cancelled
                    $total++;
                    if ($st == 0) $draft++;
                    if ($st == 1) $approved++;
                }
                if ($draft > 0) {
                    $req = $this->get($rid);
                    $reqRow = (is_array($req) && count($req) > 0) ? $req[0] : [];
                    $warnings[] = [
                        'REQ_ID' => $rid,
                        'REQ_NO' => $reqRow['REQ_NO'] ?? 'PR-' . str_pad($rid, 5, '0', STR_PAD_LEFT),
                        'DRAFT_COUNT' => $draft,
                        'APPROVED_COUNT' => $approved,
                        'TOTAL_COUNT' => $total,
                    ];
                }
            } catch (Exception $e) { continue; }
        }
        return $warnings;
    }

    // =========================================================
    // DISBURSEMENT BATCH — خطوة 1: اعتماد التجهيز
    // Result: BATCH_ID|EMP_COUNT|0   أو   0|0|1|error
    // =========================================================
    function batch_confirm($req_ids, $exclude_detail_ids = '', $disburse_method = 1)
    {
        if ($this->conn->user != 'GFC_PAK') {
            $this->conn = new DBConn();
        }
        // 1=قديم (DATA.EMPLOYEES) | 2=جديد (PAYMENT_ACCOUNTS_TB + split)
        $method = in_array((int)$disburse_method, [1, 2], true) ? (int)$disburse_method : 1;
        $params = array(
            array('name' => ':P_REQ_IDS',            'value' => $req_ids,                      'type' => SQLT_CHR, 'length' => 4000),
            array('name' => ':P_EXCLUDE_DETAIL_IDS', 'value' => $exclude_detail_ids ?: null,   'type' => SQLT_CHR, 'length' => 32767),
            array('name' => ':P_DISBURSE_METHOD',    'value' => $method,                       'type' => '',       'length' => -1),
            array('name' => ':P_MSG_OUT',            'value' => 'MSG_OUT',                     'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->BATCH_PKG_NAME, 'PAYMENT_REQ_BATCH_CONFIRM', $params);
        return $this->_parse_batch_id_result($result['MSG_OUT'] ?? '0|0|0');
    }

    // =========================================================
    // DISBURSEMENT BATCH — خطوة 2: تنفيذ الصرف
    // Result: BATCH_ID|SUCCESS|ERRORS
    // =========================================================
    function batch_pay($batch_id)
    {
        if ($this->conn->user != 'GFC_PAK') {
            $this->conn = new DBConn();
        }
        $params = array(
            array('name' => ':P_BATCH_ID', 'value' => (int)$batch_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',  'value' => 'MSG_OUT',       'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->BATCH_PKG_NAME, 'PAYMENT_REQ_BATCH_PAY', $params);
        return $this->_parse_batch_id_result($result['MSG_OUT'] ?? '0|0|0');
    }

    // =========================================================
    // Private — parse BATCH_ID|SUCCESS|ERRORS result
    // =========================================================
    private function _parse_batch_id_result($msg)
    {
        $parts    = explode('|', $msg);
        $batch_id = (int)($parts[0] ?? 0);
        $success  = (int)($parts[1] ?? 0);
        $errors   = (int)($parts[2] ?? 0);

        if ($batch_id === 0 && $success === 0 && $errors === 0) {
            return array(
                'ok' => false, 'batch_id' => 0, 'success' => 0, 'errors' => 0,
                'msg' => 'لا توجد سجلات مؤهلة',
            );
        }

        $err_detail = isset($parts[3]) ? $parts[3] : '';
        return array(
            'ok'       => ($batch_id > 0),
            'batch_id' => $batch_id,
            'success'  => $success,
            'errors'   => $errors,
            'msg'      => $batch_id > 0
                ? "دفعة رقم PB-" . str_pad($batch_id, 5, '0', STR_PAD_LEFT) . " ({$success} موظف)"
                    . ($errors > 0 ? " — فشل {$errors}" : '')
                : ($err_detail ?: 'فشلت العملية'),
        );
    }

    // =========================================================
    // REPORT — تقرير شهري
    // =========================================================
    function get_report($month_from, $month_to = null, $branch_no = null, $req_type = null, $status = null)
    {
        $params = array(
            array('name' => ':P_MONTH_FROM', 'value' => (int)$month_from,        'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MONTH_TO',   'value' => $month_to ? (int)$month_to : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_BRANCH_NO',  'value' => $branch_no ?: null,      'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_TYPE',   'value' => $req_type ?: null,       'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_STATUS',     'value' => ($status !== null && $status !== '') ? (int)$status : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'cursor',  'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 4000),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'PAYMENT_REQ_REPORT', $params);
    }

    // =========================================================
    // STATEMENT — كشف حساب موظف
    // =========================================================
    function get_emp_statement($emp_no, $month_from = null, $month_to = null)
    {
        $params = array(
            array('name' => ':P_EMP_NO',     'value' => (int)$emp_no,                        'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MONTH_FROM', 'value' => $month_from ? (int)$month_from : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MONTH_TO',   'value' => $month_to ? (int)$month_to : null,     'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'cursor',  'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 4000),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'EMP_STATEMENT', $params);
    }

    // =========================================================
    // BENEFIT ITEMS — بنود الاستحقاقات (نوع 5)
    // =========================================================
    function get_benefit_items()
    {
        $params = array(
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',  'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'GET_BENEFIT_ITEMS', $params);
    }

    // =========================================================
    // BATCH HISTORY — list + get
    // =========================================================
    function batch_history_list()
    {
        $params = array(
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',  'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',  'type' => SQLT_CHR, 'length' => 4000),
        );
        return $this->New_rmodel->general_get($this->BATCH_PKG_NAME, 'BATCH_HISTORY_LIST', $params);
    }

    function unapprove($req_id, $note = '')
    {
        $params = array(
            array('name' => ':P_REQ_ID', 'value' => (int)$req_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_NOTE',   'value' => $note ?: null, 'type' => SQLT_CHR, 'length' => 500),
            array('name' => ':P_MSG_OUT','value' => 'MSG_OUT',      'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PAYMENT_REQ_UNAPPROVE', $params);
        return $result['MSG_OUT'];
    }

    function batch_reverse_pay($batch_id)
    {
        if ($this->conn->user != 'GFC_PAK') {
            $this->conn = new DBConn();
        }
        $params = array(
            array('name' => ':P_BATCH_ID', 'value' => (int)$batch_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',  'value' => 'MSG_OUT',       'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->BATCH_PKG_NAME, 'BATCH_REVERSE_PAY', $params);
        return $result['MSG_OUT'];
    }

    function batch_cancel($batch_id)
    {
        $params = array(
            array('name' => ':P_BATCH_ID', 'value' => (int)$batch_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',  'value' => 'MSG_OUT',       'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->BATCH_PKG_NAME, 'BATCH_CANCEL', $params);
        return $result['MSG_OUT'];
    }

    function batch_history_get($batch_id)
    {
        $params = array(
            array('name' => ':P_BATCH_ID',  'value' => (int)$batch_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',       'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',       'type' => SQLT_CHR, 'length' => 4000),
        );
        return $this->New_rmodel->general_get($this->BATCH_PKG_NAME, 'BATCH_HISTORY_GET', $params);
    }

    function batch_emp_accounts($batch_id, $emp_no)
    {
        $params = array(
            array('name' => ':P_BATCH_ID',  'value' => (int)$batch_id, 'type' => SQLT_INT,    'length' => -1),
            array('name' => ':P_EMP_NO',    'value' => (int)$emp_no,   'type' => SQLT_INT,    'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor',       'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',      'type' => SQLT_CHR,    'length' => 4000),
        );
        return $this->New_rmodel->general_get($this->BATCH_PKG_NAME, 'BATCH_EMP_ACCOUNTS_GET', $params);
    }

    // =========================================================
    // BANK EXPORT — تصدير بيانات الدفعة للبنك
    // =========================================================
    function batch_bank_export($batch_id, $master_bank_no = null)
    {
        $params = array(
            array('name' => ':P_BATCH_ID',       'value' => (int)$batch_id,                          'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MASTER_BANK_NO', 'value' => $master_bank_no ? (int)$master_bank_no : -1, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',      'value' => 'cursor',                                'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',          'value' => 'MSG_OUT',                                'type' => SQLT_CHR, 'length' => 4000),
        );
        return $this->New_rmodel->general_get($this->BATCH_PKG_NAME, 'BATCH_BANK_EXPORT', $params);
    }

    // =========================================================
    // DETAIL — PREVIEW single employee (no insert)
    // =========================================================
    function detail_preview_single($req_id, $emp_no, $req_amount = 0)
    {
        $params = array(
            array('name' => ':P_REQ_ID',     'value' => (int)$req_id,       'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_EMP_NO',     'value' => (int)$emp_no,       'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_AMOUNT', 'value' => (float)$req_amount, 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':P_MSG_OUT',    'value' => 'MSG_OUT',          'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DETAIL_PREVIEW_SINGLE', $params);
        return $result['MSG_OUT'];
    }

    // =========================================================
    // DUPLICATE CHECK — active request with same month+type
    // =========================================================
    function count_active_requests($the_month, $req_type)
    {
        $params = array(
            array('name' => ':P_THE_MONTH', 'value' => (int)$the_month, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_TYPE',  'value' => (int)$req_type,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT',   'value' => 'MSG_OUT',       'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CHECK_DUPLICATE_REQUEST', $params);
        return (int)($result['MSG_OUT'] ?? 0);
    }

    // =========================================================
    // DUPLICATE CHECK — employee in another active request (same month+type)
    // =========================================================
    function emp_in_other_request($emp_no, $req_id)
    {
        $params = array(
            array('name' => ':P_EMP_NO',  'value' => (int)$emp_no, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_REQ_ID',  'value' => (int)$req_id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_MSG_OUT', 'value' => 'MSG_OUT',    'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CHECK_DUPLICATE_EMP', $params);
        $msg = $result['MSG_OUT'] ?? '';
        return ($msg && $msg !== '0') ? $msg : null;
    }
}
