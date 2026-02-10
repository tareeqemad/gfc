<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * Requests: Ahmed Barakat
 * Date: 29/07/15
 * Time: 09:15 ص
 */
class Requests_model extends MY_Model
{

    /**
     * @return array
     *
     * return all Requests data ..
     */
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($sql, $offset, $row)
    {

        $params = array(


            array('name' => ':INXSQL', 'value' => $sql, 'type' => '', 'length' => -1),
            array('name' => ':offset', 'value' => "{$offset}", 'type' => '', 'length' => -1),
            array('name' => ':row', 'value' => "{$row}", 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'REQUESTS_TB_LIST', $params);

        return $result;
    }

    function get_project_list($sql, $offset, $row)
    {

        $params = array(


            array('name' => ':INXSQL', 'value' => $sql, 'type' => '', 'length' => -1),
            array('name' => ':offset', 'value' => "{$offset}", 'type' => '', 'length' => -1),
            array('name' => ':row', 'value' => "{$row}", 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'PROJECT_WORKS_RP', $params);

        return $result;
    }

    /**
     * @param int $id
     * @return mixed
     * return one Requests ..
     */
    function get($id = 0)
    {

        $params = array(
            array('name' => ':HOLDER_ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'REQUESTS_TB_GET', $params);

        return $result;
    }


    /**
     * @param $data
     *
     * create new Requests ..
     */
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function create_request($id)
    {

        $params = array(
            array('name' => ':REQUEST_APP_SERIAL_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );


        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TB_REQUEST_APP', $params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists Requests ..
     *
     */
    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TB_UPDATE', $params);


        return $result['MSG_OUT'];
    }

    function change_type($id, $type)
    {
        $params = array(
            array('name' => ':ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':TYPE_in', 'value' => $type, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TB_CHANGE_REQ_TYPE ', $params);

        return $result['MSG_OUT'];
    }

    function feadback($id, $notes, $time_in, $time_out, $adapter_serial, $case = 3, $team_id = null, $job_id = null)
    {
        $params = array(
            array('name' => ':REQUEST_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':ACTION_HINTS_IN', 'value' => $notes, 'type' => '', 'length' => -1),
            array('name' => ':REQUEST_CASE_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':TEAM_ID_IN', 'value' => $team_id, 'type' => '', 'length' => -1),
            array('name' => ':JOB_ID_IN', 'value' => $job_id, 'type' => '', 'length' => -1),
            array('name' => ':TIME_IN_IN', 'value' => $time_in, 'type' => '', 'length' => -1),
            array('name' => ':TIME_OUT_IN', 'value' => $time_out, 'type' => '', 'length' => -1),
            array('name' => ':ADAPTER_SERIAL_IN', 'value' => $adapter_serial, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TB_CHANGE_ACTION', $params);

        return $result['MSG_OUT'];
    }


    /**
     * @param $id
     * delete Requests ..
     */
    function delete($id)
    {

        $params = array(
            array('name' => ':ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TB_DELETE', $params);

        return $result['MSG_OUT'];

    }


    /**
     * @return array
     *
     * return all tools data ..
     */
    function tools_list($id)
    {

        $params = array(
            array('name' => ':HOLDER_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'REQUESTS_TOOLS_TB_GET', $params);

        return $result;
    }


    function requests_team_tb_count_workjob($id)
    {

        $params = array(
            array('name' => ':HOLDER_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'REQUESTS_TEAM_TB_COUNT_WORKJOB', $params);

        return $result;
    }

    /**
     * @return array
     *
     * return all works data ..
     */
    function team_list($id)
    {

        $params = array(
            array('name' => ':HOLDER_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'REQUESTS_TEAM_TB_GET', $params);

        return $result;
    }


    function tools_create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TOOLS_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function tools_edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TOOLS_TB_UPDATE', $params);
        return $result['MSG_OUT'];
    }


    function team_create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TEAM_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function team_edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TEAM_TB_UPDATE', $params);
        return $result['MSG_OUT'];
    }


    function delete_tools($id)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TOOLS_TB_DELETE', $params);

        return $result['MSG_OUT'];

    }

    function delete_team($id)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_TEAM_TB_DELETE', $params);

        return $result['MSG_OUT'];

    }


    function sms($request_id, $message_text, $mobile)
    {

        $params = array(
            array('name' => ':REQUEST_ID_IN', 'value' => $request_id, 'type' => '', 'length' => -1),
            array('name' => ':MESSAGE_TEXT_IN', 'value' => $message_text, 'type' => '', 'length' => -1),
            array('name' => ':MOBILE_IN', 'value' => $mobile, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'REQUESTS_SMS_TB_INSERT', $params);

        return $result['MSG_OUT'];

    }

}