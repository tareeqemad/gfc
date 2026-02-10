<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Civil_works_model extends MY_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }


    function getAll()
    {


        $params = array(

            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1));

        $result = $this->New_rmodel->general_get('CIVIL_WORKS_PKG', 'CIVIL_WORKS_TB_GET_LIST', $params);

        return $result;
    }

    function getList($parent = 0)
    {


        $params = array(array('name' => ':CLASS_PARENT_ID_in', 'value' => "{$parent}", 'type' => '', 'length' => -1), array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),);

        $result = $this->New_rmodel->general_get('CIVIL_WORKS_PKG', 'CIVIL_WORKS_TB_GET_LIST', $params);
        return $result;
    }


    function get($id = 0)
    {

        $params = array(array('name' => ':CLASS_ID_IN', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1), array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),);

        $result = $this->New_rmodel->general_get('CIVIL_WORKS_PKG', 'CIVIL_WORKS_TB_GET', $params);

        return $result;
    }


    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('CIVIL_WORKS_PKG', 'CIVIL_WORKS_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }


    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('CIVIL_WORKS_PKG', 'CIVIL_WORKS_TB_UPDATE', $params);

        return $result['MSG_OUT'];
    }

    function delete($id)
    {

        $params = array(array('name' => ':CLASS_ID_in', 'value' => $id, 'type' => '', 'length' => -1), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),);

        $result = $this->conn->excuteProcedures('CIVIL_WORKS_PKG', 'CIVIL_WORKS_TB_DELETE', $params);
        return $result['MSG_OUT'];

    }

    function get_parent($id)
    {
           $params = array(array('name' => ':PARENT_IN', 'value' => $id, 'type' => '', 'length' => -1),
                        array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
                        array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),);

        $result = $this->New_rmodel->general_get('CIVIL_WORKS_PKG', 'CIVIL_WORKS_TB_GET_PARENT', $params);

        return $result;
    }


}