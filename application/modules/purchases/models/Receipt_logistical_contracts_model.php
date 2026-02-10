<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Receipt_logistical_contracts_model extends MY_Model
{
    var $PKG_NAME = "LOGISTIC_ITEMS_PKG";
    var $TABLE_NAME = 'RECEIPT_LOGISTIC_TB';


    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }


    function get($id = 0)
    {

        $params = array(array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => $id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500));
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET', $params);
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

    function get_list_all($sql, $offset, $row)
    {

        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_LIST', $params);
        return $result;
    }

    function adopt($id = 0, $case)
    {
        $params = array(array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => $id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':CASE_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500));

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_ADOPT', $params);

        return $result['MSG_OUT'];
    }

}