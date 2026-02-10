<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/11/14
 * Time: 09:49 ص
 */
class receipt_class_input_group_model extends MY_Model
{
    var $PKG_NAME = "STORES_PKG";
    var $TABLE_NAME = 'RECEIPT_CLASS_INPUT_GROUP_TB';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id = 0, $type)
    {

        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':SOURCE_IN', 'value' => "{$type}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RECEIPT_CLASS_INPUT_GRO_GET', $params);
        return $result;
    }

    function get_all()
    {

        $params = array(
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RECEIPT_CLASS_INPUT_GRO_ALL', $params);
        return $result;
    }

    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'RECEIPT_CLASS_INPUT_GRO_INSERT', $params);

        return $result['MSG_OUT'];
    }

    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'RECEIPT_CLASS_INPUT_GRO_UPDATE', $params);

        return $result['MSG_OUT'];
    }

    function delete($id)
    {
        $params = array(
            array('name' => ':SER', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'RECEIPT_CLASS_INPUT_GRO_DELETE', $params);
        return $result['MSG_OUT'];
    }

    function get_details_all($id = 0, $type)
    {

        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':SOURCE_IN', 'value' => "{$type}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RECEIPT_CLASS_INPUT_GROUP_LIST', $params);
        return $result;
    }

    function get_emails($id = 0, $type)
    {

        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':SOURCE_IN', 'value' => "{$type}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RECEIPT_CLASS_INPUT_GROUP_EMAI', $params);
        return $result;
    }
}
