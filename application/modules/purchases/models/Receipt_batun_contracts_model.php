<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Receipt_batun_contracts_model extends MY_Model
{
    var $PKG_NAME = "LOGISTIC_ITEMS_PKG";
    var $TABLE_NAME = 'RECEIPT_BATUN_TB';


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

    function send_to_adopt($rec){
        $params =array(
            array('name'=>':REC_IN','value'=>$rec,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,  $this->TABLE_NAME .'_MERGE',$params);
        return $result['MSG_OUT'];
    }

    function get_list_all_adopt($sql, $offset, $row)
    {

        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'RECEIPT_BATUN_ADOPT_LIST', $params);
        return $result;
    }

    function back_to_prepare($rec){
        $params =array(
            array('name'=>':REC_IN','value'=>$rec,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,  $this->TABLE_NAME .'_BACK',$params);
        return $result['MSG_OUT'];
    }

    function get_adopt($id = 0)
    {

        $params = array(array('name' => ':SER_ADOPT_IN', 'value' => $id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500));
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RECEIPT_BATUN_TB_adopt_GET', $params);
        return $result;
    }

    function manager_adopt($id = 0, $case)
    {
        $params = array(array('name' => ':SER_ADOPT_IN', 'value' => $id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':CASE_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500));

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'RECEIPT_BATUN_MG_ADOPT', $params);
     //   var_dump($params);
        return $result['MSG_OUT'];
    }
}