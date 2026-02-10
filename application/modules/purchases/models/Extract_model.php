<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Extract_model extends MY_Model
{
    var $PKG_NAME = "PURCHASE_PKG";
    var $TABLE_NAME = 'EXTRACT_TB';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id = 0)
    {

        $params = array(
            array('name' => ':SER_in', 'value' => "{$id}", 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET', $params);

        return $result;
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

    function adopt($id, $case, $note = '')
    {
        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_ADOPT', $params);

        return $result['MSG_OUT'];
    }

    function extract_det_tb_get($ser, $order_id, $customer_id)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => "{$ser}", 'type' => '', 'length' => -1),
            array('name' => ':ORDER_ID_IN', 'value' => "{$order_id}", 'type' => '', 'length' => -1),
            array('name' => ':CUSTOMER_ID_IN', 'value' => "{$customer_id}", 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'EXTRACT_DET_TB_GET', $params);

        return $result;
    }

    function merge_extracts($rec){
        $params =array(
            array('name'=>':REC_IN','value'=>$rec,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,  $this->TABLE_NAME .'_MERGE',$params);
        return $result['MSG_OUT'];
    }

}
