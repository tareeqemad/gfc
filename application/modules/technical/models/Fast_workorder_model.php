<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 22/08/16
 * Time: 08:43 ص
 */
class Fast_workorder_model extends MY_Model
{

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

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'FAST_WORKORDER_TB_LIST', $params);

        return $result;
    }


    function get($id = 0)
    {

        $params = array(
            array('name' => ':HOLDER_ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'FAST_WORKORDER_TB_GET', $params);

        return $result;
    }


    function generate($id = 0)
    {


        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),

            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'FAST_WORKORDER_TO_REQUESTS', $params);
        return $result['MSG_OUT'];
    }


    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'FAST_WORKORDER_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }


    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'FAST_WORKORDER_TB_UPDATE', $params);


        return $result['MSG_OUT'];
    }


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


}