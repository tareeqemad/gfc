<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/11/14
 * Time: 09:02 ص
 */
class receipt_class_input_model extends MY_Model
{
    var $PKG_NAME = "STORES_PKG";
    var $TABLE_NAME = 'RECEIPT_CLASS_INPUT_TB';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id = 0)
    {

        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET', $params);
        return $result;
    }

    function get_all()
    {

        $params = array(
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET_ALL', $params);
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

    function transform($id)
    {
        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_TRANSF', $params);
        return $result['MSG_OUT'];
    }

    function delete($id)
    {
        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_DELETE', $params);
        return $result['MSG_OUT'];
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

    function get_list($sql, $offset, $row)
    {


        $params = array(

            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RECEIPT_CLASS_INPUT_GET_LIST', $params);

        return $result;
    }

    function adopt($id, $case, $hints)
    {
        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':SEND_CASE_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':SEND_HINTS_IN', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_ADOPT', $params);
        return $result['MSG_OUT'];
    }

    function record($id, $case, $hints, $record_declaration_list, $r)
    {
        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':RECORD_CASE_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':RECORD_DECLARATION_IN', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => ':RECORD_ID_IN', 'value' => $r, 'type' => '', 'length' => -1),
            array('name' => ':RECORD_DECLARATION_LIST_IN', 'value' => $record_declaration_list, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_RECORD', $params);
        return $result['MSG_OUT'];
    }

    function member_record($id, $case, $hints, $record_declaration_list, $r)
    {
        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':RECORD_CASE_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':RECORD_DECLARATION_IN', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => ':RECORD_ID_IN', 'value' => $r, 'type' => '', 'length' => -1),
            array('name' => ':RECORD_DECLARATION_LIST_IN', 'value' => $record_declaration_list, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'RECEIPT_CLASS_INPUT_COMMTIEE', $params);
        return $result['MSG_OUT'];
    }

    function returnp($id, $case, $hints)
    {
        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':RETURN_CASE_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':RETURN_HINT_IN', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_RETURN', $params);
        return $result['MSG_OUT'];
    }


    function get_account_project_by_order($id)
    {

        $params = array(
            array('name' => ':ORDER_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'GET_ACCOUNT_PROJECT_BY_ORDER', $params);
        return $result;
    }

    function projects_file_det_customer_get($id)
    {

        $params = array(
            array('name' => ':ORDER_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'PROJECTS_FILE_DET_CUSTOMER_GET', $params);
        return $result;
    }

    function get_committee_emails($id)
    {

        $params = array(
            array('name' => ':RECEIPT_CLASS_INPUT_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RECIPT_COMMITTEE_EMAILS_GET_', $params);
        return $result;
    }
}
