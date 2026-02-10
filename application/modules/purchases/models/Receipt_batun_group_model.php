<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Receipt_batun_group_model extends MY_Model
{

    var $PKG_NAME = "LOGISTIC_ITEMS_PKG";
    var $TABLE_NAME = 'RECEIPT_BATUN_GROUP';


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

    function get_group_list($id = 0)
    {

        $params = array(
            array('name' => ':COMMITTEES_ID', 'value' => $id, 'type' => SQLT_INT, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get('STORES_PKG', 'STORE_MEMBERS_TB_GET_LIST', $params);
        return $result;
    }

    function member_record($id, $committees_id, $member_note)
    {
        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':COMMITTEES_ID', 'value' => $committees_id, 'type' => '', 'length' => -1),
            array('name' => ':MEMBER_NOTE_IN', 'value' => $member_note, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'B_RECEIPT_CLASS_INPUT_COMMTIEE', $params);
        return $result['MSG_OUT'];
    }

    function get_committee_emails($id)
    {

        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RECIPT_COMMITTE_EMAILS_GET_B', $params);
        return $result;
    }
}