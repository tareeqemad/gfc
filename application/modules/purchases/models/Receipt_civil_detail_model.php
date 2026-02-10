<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Receipt_civil_detail_model extends MY_Model
{

    var $PKG_NAME = "LOGISTIC_ITEMS_PKG";
    var $TABLE_NAME = 'RECEIPT_CIVIL_DET';


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
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET', $params);
        return $result;
    }

    function create($data)
    {

        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    function get_details_all($id = 0)
    {

        $params = array(array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1), array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),);
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RECEIPT_CLASS_INPUT_DET_LIST', $params);
        return $result;
    }
}
