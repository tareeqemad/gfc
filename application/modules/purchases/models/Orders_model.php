<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 03/03/15
 * Time: 11:32 ص
 */
class orders_model extends MY_Model
{
    var $PKG_NAME = "PURCHASE_PKG";
    var $TABLE_NAME = 'ORDERS_TB';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id = 0)
    {

        $params = array(
            array('name' => ':ORDER_ID_IN', 'value' => $id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET', $params);
        return $result;
    }

    function get_class_order($id = 0)
    {

        $params = array(
            array('name' => ':CLASS_INPUT_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get('STORES_PKG', 'STORES_CLASS_INPUT_ADOPT_V', $params);
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
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_LIST', $params);
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
            array('name' => ':ORDER_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_DELETE', $params);
        return $result['MSG_OUT'];
    }

    function adopt($id, $adopt)
    {
        $params = array(
            array('name' => ':ORDER_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT_IN', 'value' => $adopt, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_ADOPT', $params);
        return $result['MSG_OUT'];
    }

    function cancel_adopt($id, $adopt)
    {
        $params = array(
            array('name' => ':ORDER_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT_IN', 'value' => $adopt, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_CANCEL_ADOPT', $params);
        return $result['MSG_OUT'];
    }

    function return_adopt($id, $adopt)
    {
        $params = array(
            array('name' => ':ORDER_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT_IN', 'value' => $adopt, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_RETURN_ADOPT', $params);
        return $result['MSG_OUT'];
    }

    function orders_tb_real_order_update($id, $real_order)
    {
        $params = array(
            array('name' => ':ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':REAL_ORDER_IN', 'value' => $real_order, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'ORDERS_TB_REAL_ORDER_UPDATE', $params);
        return $result['MSG_OUT'];
    }

}
