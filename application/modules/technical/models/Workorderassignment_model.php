<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * Holders Equipments: Ahmed Barakat
 * Date: 29/07/15
 * Time: 09:15 ص
 */
class WorkOrderAssignment_model extends MY_Model
{

    /**
     * @return array
     *
     * return all Holders Equipments data ..
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

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_TB_LIST', $params);

        return $result;
    }


    /**
     * @param int $id
     * @return mixed
     * return one Holders Equipments ..
     */
    function get($id = 0)
    {


        $params = array(
            array('name' => ':HOLDER_ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_GET', $params);

        return $result;
    }


    /**
     * @param $data
     *
     * create new Holders Equipments ..
     */
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function tools_create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_TOOLS_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function tools_edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_TOOLS_UPDATE', $params);
        return $result['MSG_OUT'];
    }


    function team_create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORKER_ORDER_ASSIG_TEAM_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function team_edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORKER_ORDER_ASSIG_TEAM_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    function workOrder_create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_ORDER_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function workOrder_edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_ORDER_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    function cars_create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_CARS_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function cars_edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_CARS_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    function delete_tools($id)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_TOOLS_DELETE', $params);

        return $result['MSG_OUT'];

    }

    function delete_works($id)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORKER_ORDER_ASSIG_TEAM_DELETE', $params);

        return $result['MSG_OUT'];

    }

    function delete_WOrder($id)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_ORDER_DELETE', $params);

        return $result['MSG_OUT'];

    }

    function delete_cars($id)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_CARS_DELETE', $params);

        return $result['MSG_OUT'];

    }

    /**
     * @param $data
     *
     * update exists Holders Equipments ..
     *
     */
    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_UPDATE', $params);


        return $result['MSG_OUT'];
    }

    function edit_permit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_PERMIT', $params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete Holders Equipments ..
     */
    function delete($id)
    {

        $params = array(
            array('name' => ':ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_DELETE', $params);

        return $result['MSG_OUT'];

    }


    function adopt($id, $adopt)
    {

        $params = array(
            array('name' => ':WORK_ORDER_ASSIGNMENT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT_IN', 'value' => $adopt, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIGNMENT_TB_ADOPT', $params);

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

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_TOOLS_GET', $params);

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

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORKER_ORDER_ASSIG_TEAM_GET', $params);

        return $result;
    }

    /**
     * @return array
     *
     * return all works data ..
     */
    function cars_list($id)
    {

        $params = array(
            array('name' => ':HOLDER_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_CARS_GET', $params);

        return $result;
    }

    /**
     * @return array
     *
     * return all works data ..
     */
    function workOrder_list($id)
    {

        $params = array(
            array('name' => ':HOLDER_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_ORDER_GET', $params);

        return $result;
    }


    function FEED_BAK_WORK_ORDER_ASSIGNMENT($id, $date, $hints)
    {

        $params = array(
            array('name' => ':WORK_ORDER_ASSIGNMENT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':TIME_RETURN', 'value' => $date, 'type' => '', 'length' => -1),
            array('name' => ':HINTS_IN', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'FEED_BAK_WORK_ORDER_ASSIGNMENT', $params);

        return $result['MSG_OUT'];

    }

    function FEED_BAK_ORDER_ASSIG_ORDER($id, $date, $hints,$adapter_ser)
    {

        $params = array(
            array('name' => ':ORDER_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':ACTION_END_IN', 'value' => $date, 'type' => '', 'length' => -1),
            array('name' => ':HINTS_IN', 'value' => $hints, 'type' => '', 'length' => -1),
            array('name' => ':ADAPTER_SER_IN', 'value' => $adapter_ser, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'FEED_BAK_ORDER_ASSIG_ORDER', $params);

        return $result['MSG_OUT'];

    }


    function FEED_BAK_ORDER_ASSIG_TOOLS($id, $count)
    {

        $params = array(
            array('name' => ':TOOLS_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':CLASS_COUNT_IN', 'value' => $count, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'FEED_BAK_ORDER_ASSIG_TOOLS', $params);

        return $result['MSG_OUT'];

    }

    function  WORK_ORDER_ASSIG_CARS_UP_TIME($id, $the_time_minute)
    {
        $params = array(
            array('name' => ':SER', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':THE_TIME_MINUTE_IN', 'value' => $the_time_minute, 'type' => '', 'length' => -1),

            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_ASSIG_CARS_UP_TIME', $params);

        return $result['MSG_OUT'];
    }

    function WORKER_ORDER_ASSIG_TEAM_COST ($id)
    {


        $params = array(
            array('name' => ':WORK_ORDER_ASSIGNMENT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORKER_ORDER_ASSIG_TEAM_COST ', $params);

        return $result;
    }

    function WORK_ORDER_ASSIGNMENT_TOL_COST($id)
    {


        $params = array(
            array('name' => ':WORK_ORDER_ASSIGNMENT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORK_ORDER_ASSIGNMENT_TOL_COST', $params);

        return $result;
    }


    function WORK_ORDER_ASSIGNMENT_CAR_COST($id)
    {

        $params = array(
            array('name' => ':WORK_ORDER_ASSIGNMENT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORK_ORDER_ASSIGNMENT_CAR_COST ', $params);

        return $result;
    }

    function WORK_ORDER_RETURN_TOOLS_GET($id)
    {

        $params = array(
            array('name' => ':WORK_ORDER_ASSIGNMENT_ID_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG', 'WORK_ORDER_RETURN_TOOLS_GET ', $params);

        return $result;
    }



    function WORK_ORDER_RETURN_TOOLS_INSERT($work_order_assignment_id,$workOrder_id, $class_id, $class_unit,$class_count_ret,$class_type,$notes)
    {

        $params = array(
            array('name' => ':WORK_ORDER_ASSIGNMENT_ID_IN', 'value' => $work_order_assignment_id, 'type' => '', 'length' => -1),
            array('name' => ':WORK_ORDER_ID_IN', 'value' => $workOrder_id, 'type' => '', 'length' => -1),

            array('name' => ':CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => ':CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => ':CLASS_COUNT_RET', 'value' => $class_count_ret, 'type' => '', 'length' => -1),
            array('name' => ':CLASS_TYPE', 'value' => $class_type, 'type' => '', 'length' => -1),
            array('name' => ':NOTES', 'value' => $notes, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_RETURN_TOOLS_INSERT', $params);

        return $result['MSG_OUT'];

    }

    function WORK_ORDER_RETURN_TOOLS_UPDATE($ser,$work_order_assignment_id,$workOrder_id, $class_id, $class_unit,$class_count_ret,$class_type,$notes)
    {

        $params = array(
            array('name' => ':SER_IN', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => ':WORK_ORDER_ASSIGNMENT_ID_IN', 'value' => $work_order_assignment_id, 'type' => '', 'length' => -1),
            array('name' => ':WORK_ORDER_ID_IN', 'value' => $workOrder_id, 'type' => '', 'length' => -1),
            array('name' => ':CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => ':CLASS_UNIT', 'value' => $class_unit, 'type' => '', 'length' => -1),
            array('name' => ':CLASS_COUNT_RET', 'value' => $class_count_ret, 'type' => '', 'length' => -1),
            array('name' => ':CLASS_TYPE', 'value' => $class_type, 'type' => '', 'length' => -1),
            array('name' => ':NOTES', 'value' => $notes, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_RETURN_TOOLS_UPDATE', $params);

        return $result['MSG_OUT'];

    }


    function WORK_ORDER_RETURN_TOOLS_DELETE($id)
    {

        $params = array(
            array('name' => ':ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG', 'WORK_ORDER_RETURN_TOOLS_DELETE', $params);

        return $result['MSG_OUT'];

    }



}