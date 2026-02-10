<?php

class morning_delay_adopt_model extends MY_Model {

    var $PKG_NAME = "TRANSACTION_PKG";

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
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'DELAY_SALARY_LIST', $params);
        return $result;
    }
}