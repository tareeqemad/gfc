<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 10/12/17
 * Time: 09:29 ص
 */
class LoadFlow_model extends MY_Model
{
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('LOAD_FLOW_PKG', 'LOAD_FLOW_MEASURE_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function create_cables($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('LOAD_FLOW_PKG', 'CABLES_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function edit_cables($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('LOAD_FLOW_PKG', 'CABLES_TB_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('LOAD_FLOW_PKG', 'LOAD_FLOW_MEASURE_TB_UPDATE', $params);


        return $result['MSG_OUT'];
    }

    function delete($id)
    {

        $params = array(
            array('name' => ':ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('LOAD_FLOW_PKG', 'LOAD_FLOW_MEASURE_TB_DELETE', $params);

        return $result['MSG_OUT'];

    }

    function get($id = 0)
    {


        $params = array(
            array('name' => ':ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get('LOAD_FLOW_PKG', 'LOAD_FLOW_MEASURE_TB_GET', $params);

        return $result;
    }

    function getProjectItem($id = '')
    {

        $params = array(
            array('name' => ':TEC_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get('LOAD_FLOW_PKG', 'PROJECT_ITEMS_GET', $params);

        return $result;
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

        $result = $this->New_rmodel->general_get('LOAD_FLOW_PKG', 'LOAD_FLOW_MEASURE_TB_LIST', $params);

        return $result;
    }

    function tools_list($id)
    {

        $params = array(
            array('name' => ':ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('LOAD_FLOW_PKG', 'CABLES_TB_GET', $params);

        return $result;
    }

    function adopt($tec, $case)
    {
        $params = array(
            array('name' => ':TEC', 'value' => $tec, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );


        $result = $this->conn->excuteProcedures('LOAD_FLOW_PKG', 'PROJECTS_FILE_LOAD_FLOW_ADOPT', $params);

        return $result['MSG_OUT'];
    }

    function delete_cables($id)
    {
        $params = array(
            array('name' => ':Id', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );


        $result = $this->conn->excuteProcedures('LOAD_FLOW_PKG', 'CABLES_TB_DELETE', $params);

        return $result['MSG_OUT'];
    }


} 