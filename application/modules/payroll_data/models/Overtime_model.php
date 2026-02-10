<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 15/02/2020
 * Time: 10:14 ص
 */
class overtime_model extends MY_Model
{

    var $PKG_NAME = "TRANSACTION_PKG";
    var $TABLE_NAME = 'OVERTIME';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }


    function get_budget($branch_no, $section_no)
    {

        $params = array(
            array('name' => ':EMP_BRANCH', 'value' => $branch_no, 'type' => '', 'length' => -1),
            array('name' => ':SECTION_NO', 'value' => $section_no, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'OVERTIME_BUDGET_GET', $params);
        return $result;
    }

    function get_budget_interval($section_no, $branch_no, $month, $to_month, $emp_type)
    {

        $params = array(
            array('name' => ':SECTION_NO', 'value' => $section_no, 'type' => '', 'length' => -1),
            array('name' => ':BRANCH_NO', 'value' => $branch_no, 'type' => '', 'length' => -1),
            array('name' => ':FROM_MONTH', 'value' => $month, 'type' => '', 'length' => -1),
            array('name' => ':TO_MONTH', 'value' => $to_month, 'type' => '', 'length' => -1),
            array('name' => ':EMP_TYPE', 'value' => $emp_type, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'OVERTIME_BUDGET_INTERVAL_GET', $params);
        return $result;
    }


    function get_salary_interval($branch_no, $month, $to_month, $emp_type, $account_id)
    {

        $params = array(
            array('name' => ':BRANCH_NO', 'value' => $branch_no, 'type' => '', 'length' => -1),
            array('name' => ':FROM_MONTH', 'value' => $month, 'type' => '', 'length' => -1),
            array('name' => ':TO_MONTH', 'value' => $to_month, 'type' => '', 'length' => -1),
            array('name' => ':EMP_TYPE', 'value' => $emp_type, 'type' => '', 'length' => -1),
            array('name' => ':ACCOUNT_ID', 'value' => $account_id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'OVERTIME_SALARY_INTERVAL_GET', $params);
        return $result;
    }


}
