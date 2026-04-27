<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dues_model extends MY_Model
{
    var $PKG_NAME   = "SALARY_DUES_PKG1";
    var $TABLE_NAME = "SALARY_DUES_TB";

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($serial = 0)
    {
        $params = array(
            array('name' => ':SERIAL_IN',   'value' => $serial, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET', $params);
    }

    function get_list($filters = array())
    {
        $params = array(
            array('name' => ':P_EMP_NO',     'value' => isset($filters['emp_no'])     ? $filters['emp_no']     : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_BRANCH',     'value' => isset($filters['branch'])     ? $filters['branch']     : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_FROM_MONTH', 'value' => isset($filters['from_month']) ? $filters['from_month'] : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_TO_MONTH',   'value' => isset($filters['to_month'])   ? $filters['to_month']   : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_PAY_TYPE',   'value' => isset($filters['pay_type'])   ? $filters['pay_type']   : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_LINE_TYPE',  'value' => isset($filters['line_type'])  ? $filters['line_type']  : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_STATUS',     'value' => isset($filters['status'])     ? $filters['status']     : 1,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':OFFSET_IN',    'value' => isset($filters['offset'])     ? $filters['offset']     : 0,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':ROW_IN',       'value' => isset($filters['limit'])      ? $filters['limit']      : 50,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_LIST', $params);
    }

    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_INSERT', $params);
        return $result['MSG_OUT'];
    }

    /** إجماليات كل النتائج (بدون pagination) */
    function get_totals($filters = array())
    {
        $params = array(
            array('name' => ':P_EMP_NO',     'value' => isset($filters['emp_no'])     ? $filters['emp_no']     : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_BRANCH',     'value' => isset($filters['branch'])     ? $filters['branch']     : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_FROM_MONTH', 'value' => isset($filters['from_month']) ? $filters['from_month'] : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_TO_MONTH',   'value' => isset($filters['to_month'])   ? $filters['to_month']   : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_PAY_TYPE',   'value' => isset($filters['pay_type'])   ? $filters['pay_type']   : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_LINE_TYPE',  'value' => isset($filters['line_type'])  ? $filters['line_type']  : null, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_STATUS',     'value' => isset($filters['status'])     ? $filters['status']     : 1,    'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_TOTALS', $params);
    }

    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    function delete($serial)
    {
        $params = array(
            array('name' => ':SERIAL_IN', 'value' => $serial, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':MSG_OUT',   'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_DELETE', $params);
        return $result['MSG_OUT'];
    }

    public function get_summary($emp_no, $the_month = null)
    {
        $params = array(
            array('name' => ':EMP_NO_IN',     'value' => (int)$emp_no, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':THE_MONTH_IN',  'value' => null,         'type' => SQLT_CHR, 'length' => 32),
            array('name' => ':REF_CURSOR_OUT','value' => 'CUR_RES',    'type' => 'cursor'),
            array('name' => ':MSG_OUT',       'value' => 'MSG_OUT',    'type' => SQLT_CHR, 'length' => 4000),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SALARY_DUES_SUMMARY_LIST', $params);
        return $result['CUR_RES'];
    }

    public function get_emp_balance($emp_no)
    {
        $res = $this->get_summary($emp_no);
        if (is_array($res) && isset($res[0]['TOTAL_BALANCE'])) {
            return (float)$res[0]['TOTAL_BALANCE'];
        }
        return 0;
    }

    public function get_emp_movements($emp_no, $from_month = null, $to_month = null)
    {
        $params = array(
            array('name' => ':P_EMP_NO',     'value' => $emp_no,     'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_FROM_MONTH', 'value' => $from_month, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':P_TO_MONTH',   'value' => $to_month,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'cursor',    'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT',   'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, 'EMP_DUES48_MOVEMENTS', $params);
    }

    /** ═══ ترحيل المستحقات من الرواتب ═══ */

    /**
     * فحص حالة الترحيل — كم سجل مرحل وكم متبقي
     */
    public function migrate_check($con_no, $the_month, $pay_type)
    {
        $params = array(
            array('name' => ':CON_NO_IN',      'value' => (int)$con_no,     'type' => SQLT_INT, 'length' => -1),
            array('name' => ':THE_MONTH_IN',    'value' => (int)$the_month,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':PAY_TYPE_IN',     'value' => (int)$pay_type,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CURSOR_OUT',  'value' => 'CUR_RES',        'type' => 'cursor'),
            array('name' => ':MSG_OUT',         'value' => 'MSG_OUT',         'type' => SQLT_CHR, 'length' => 4000),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SALARY_DUES_MIGRATE_CHECK', $params);
        return $result['CUR_RES'];
    }

    /**
     * تنفيذ الترحيل — يرحل فقط غير المرحل
     */
    public function migrate_run($con_no, $the_month, $pay_type)
    {
        $params = array(
            array('name' => ':CON_NO_IN',      'value' => (int)$con_no,     'type' => SQLT_INT, 'length' => -1),
            array('name' => ':THE_MONTH_IN',    'value' => (int)$the_month,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':PAY_TYPE_IN',     'value' => (int)$pay_type,   'type' => SQLT_INT, 'length' => -1),
            array('name' => ':INSERTED_OUT',    'value' => 'INSERTED_OUT',   'type' => SQLT_INT, 'length' => 32),
            array('name' => ':MSG_OUT',         'value' => 'MSG_OUT',         'type' => SQLT_CHR, 'length' => 4000),
        );
        return $this->conn->excuteProcedures($this->PKG_NAME, 'SALARY_DUES_MIGRATE_RUN', $params);
    }
}