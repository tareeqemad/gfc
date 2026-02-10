<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * PROJECT CLOSE: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:35 AM
 */
class Project_close_request_model extends MY_Model
{


    /**
     * @return array
     *
     * return all PROJECT CLOSEs data ..
     */
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_all($SQL = '')
    {

        

        $params = array(
            array('name' => ':XSQL', 'value' => $SQL, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'PROJECT_CLOSE_REQUEST_GET_ALL', $params);

        return $result;
    }


    /**
     * @return array
     *
     * return all PROJECT CLOSEs data ..
     */
    function get_list($sql, $offset, $row)
    {

        

        $params = array(


            array('name' => ':INXSQL', 'value' => $sql, 'type' => '', 'length' => -1),
            array('name' => ':offset', 'value' => "{$offset}", 'type' => '', 'length' => -1),
            array('name' => ':row', 'value' => "{$row}", 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'PROJECT_CLOSE_REQUEST_LIST', $params);

        return $result;
    }


    /**
     * @param int $id
     * @return mixed
     * return one PROJECT CLOSE ..
     */
    function get($id = '0')
    {

        

        $params = array(
            array('name' => ':ID_in', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'PROJECT_CLOSE_REQUEST_GET', $params);

        return $result;
    }


    /**
     * @param $data
     *
     * create new PROJECT CLOSE ..
     */
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'PROJECT_CLOSE_REQUEST_INSERT', $params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists PROJECT CLOSE ..
     *
     */
    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'PROJECT_CLOSE_REQUEST_UPDATE', $params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete PROJECT CLOSE ..
     */
    function delete($id)
    {

        $params = array(
            array('name' => ':ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'PROJECT_CLOSE_REQUEST_DELETE', $params);

        return $result['MSG_OUT'];

    }

    function adopt($id, $case)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':PROJECT_CLOSE_CASE_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'PROJECT_CLOSE_REQUEST_ADOPT', $params);

        return $result['MSG_OUT'];

    }


    function delivery($id, $close_type, $hints,$company,$visit_date)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':CLOSE_TYPE_IN', 'value' => $close_type, 'type' => '', 'length' => -1),
            array('name' => ':HINTS_IN', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => ':COMPANY_NAME_IN', 'value' => $company, 'type' => '', 'length' => -1),
            array('name' => ':VISIT_DATE_IN', 'value' => $visit_date, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'PROJECT_CLOSE_REQUEST_DELIVERY', $params);

        return $result['MSG_OUT'];

    }

    function project_close_hinst_list ($sql, $offset, $row)
    {

        

        $params = array(


            array('name' => ':INXSQL', 'value' => $sql, 'type' => '', 'length' => -1),
            array('name' => ':offset', 'value' => "{$offset}", 'type' => '', 'length' => -1),
            array('name' => ':row', 'value' => "{$row}", 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'PROJECT_CLOSE_HINST_LIST', $params);

        return $result;
    }

}