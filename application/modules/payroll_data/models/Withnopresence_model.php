<?php

class withnopresence_model extends MY_Model
{
    var $PKG_NAME= "TRANSACTION_PKG";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
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
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'NO_ENTRY_LEAVE_TB_LIST', $params);
        return $result;
    }



    function get_list1($sql, $offset, $row)
    {
        
        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'NO_ENTRY_LEAVE_LIST_VM', $params);
        return $result;
    }


    function adopt_no_entry_leave($emp_no, $the_month, $count_day)
    {
        $params = array(
            array('name' => ':EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
            array('name' => ':THE_MONTH', 'value' => $the_month, 'type' => '', 'length' => -1),
            array('name' => ':COUNT_DAY', 'value' => $count_day, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'NO_ENTRY_LEAVE_TB_ADOPT', $params);
        return $result['MSG_OUT'];
    }

}

