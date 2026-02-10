<?php

class Contract_gcc_model extends MY_Model
{

    var $PKG_NAME = "CONTRACTS_SERVICES_PKG";
    var $TABLE_NAME = 'CONTRACT_SERVICES_TB';

    function get_list($sql, $offset, $row)
    {
        $cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => $cursor, 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CONTRACT_SERVICES_REQTB_LIST', $params);
        return $result[(int)$cursor];
    }


    function adopt($ser, $case, $note)
    {
        $params = array(
            array('name' => ':SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CONTRACT_SERVICES_ADOPT', $params);
        return $result['MSG_OUT'];
    }

    function get($id = 0)
    {
        $cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':SER', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => $cursor, 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CONTRACT_SERVICES_ADOPT_GET', $params);
        return $result[(int)$cursor];
    }
}
