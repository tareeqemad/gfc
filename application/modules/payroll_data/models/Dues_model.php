<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dues_model extends MY_Model
{
    var $PKG_NAME   = "SALARY_DUES_PKG";
    var $TABLE_NAME = "SALARY_DUES_TB";

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($serial = 0)
    {
        $params = array(
            array('name' => ':SERIAL_IN',    'value' => $serial, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',  'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',      'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET', $params);
    }

    function get_all()
    {
        $params = array(
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',     'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        return $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET_ALL', $params);
    }


    function create($data)
    {
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }


    function edit($data)
    {
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
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

    /**
     * يرجع ملخص المستحقات/المسدد/المتبقي
     * THE_MONTH_IN ممكن يكون NULL (البروسيجر عندك default NULL)
     */
    public function get_summary($emp_no, $the_month = null)
    {
        $params = array(
            array('name' => ':EMP_NO_IN',     'value' => $emp_no,     'type' => SQLT_INT, 'length' => -1),
            array('name' => ':THE_MONTH_IN',  'value' => $the_month,  'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT',   'value' => 'cursor',    'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT',       'value' => 'MSG_OUT',   'type' => SQLT_CHR, 'length' => -1)
        );

        return $this->New_rmodel->general_get($this->PKG_NAME, 'SALARY_DUES_SUMMARY_LIST', $params);
    }

    public function get_emp_balance($emp_no)
    {

        $res = $this->get_summary($emp_no);

        if (is_array($res) && isset($res[0]['TOTAL_BALANCE'])) {
            return (float)$res[0]['TOTAL_BALANCE'];
        }
        return 0;
    }
}
