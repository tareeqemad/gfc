<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Entitl_deduct - مراجعة البدلات بعد الاحتساب
 * ملخص مستحقات 48 (المسدد من المستحقات) عبر GET_EMP_DUES48_SUMMARY
 */
class Entitl_deduct_model extends MY_Model
{
    var $PKG_NAME = 'SALARY_DUES_PKG';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }


    public function get_dues48_summary($emp_no)
    {
        $emp_no = (int) $emp_no;
        $params = array(
            array('name' => ':P_EMP_NO',    'value' => $emp_no,     'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'CUR_RES',   'type' => 'cursor'),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT',    'type' => SQLT_CHR, 'length' => 500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_EMP_DUES48_SUMMARY_P', $params);
        if (is_array($result['CUR_RES'] ?? null) && isset($result['CUR_RES'][0])) {
            return $result['CUR_RES'][0];
        }
        return null;
    }

    /**
     * قائمة حركات المستحقات (إضافة/خصم) لموظف واحد - من EMP_DUES48_MOVEMENTS
     * @param int $emp_no
     * @param int|string|null $from_month YYYYMM
     * @param int|string|null $to_month YYYYMM
     * @return array
     */
    public function get_dues48_movements($emp_no, $from_month = null, $to_month = null)
    {
        $emp_no = (int) $emp_no;
        $params = array(
            array('name' => ':P_EMP_NO',      'value' => $emp_no,       'type' => '', 'length' => -1),
            array('name' => ':P_FROM_MONTH', 'value' => $from_month,   'type' => '', 'length' => -1),
            array('name' => ':P_TO_MONTH',   'value' => $to_month,     'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'CUR_RES',     'type' => 'cursor'),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT',     'type' => SQLT_CHR, 'length' => 500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMP_DUES48_MOVEMENTS', $params);
        if (is_array($result['CUR_RES'] ?? null)) {
            return $result['CUR_RES'];
        }
        return array();
    }
}
