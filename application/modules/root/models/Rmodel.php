<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 31/07/16
 * Time: 11:25 ص
 */
class Rmodel extends MY_Model
{

    public $package;

    function iud($procedure, $data)
    {

        $params = array();

        $this->_extract_data($params, $data);

        $result = $this->conn->excuteProcedures($this->package, $procedure, $params);

        return $result['MSG_OUT'];
    }

    function insert($procedure, $data)
    {

        return $this->iud($procedure, $data);
    }

    function update($procedure, $data)
    {

        return $this->iud($procedure, $data);

    }


    function delete($procedure, $id)
    {

        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500)
        );
        $result = $this->conn->excuteProcedures($this->package, $procedure, $params);

        return $result['MSG_OUT'];

    }

    function get_msg($procedure, $data)
    {

        return $this->iud($procedure, $data);

    }


    function get($procedure, $id)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->package, $procedure, $params);

        return $result['CUR_RES'];
    }


    function getID($package, $procedure, $id)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        return $result['CUR_RES'];
    }

    function getTwoColum($package, $procedure, $id,$branch)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':BRANCH', 'value' => $branch, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        return $result['CUR_RES'];
    }

    function getThreeColum($procedure,$id,$one,$two)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':FROM', 'value' => $one, 'type' => '', 'length' => -1),
            array('name' => ':TO', 'value' => $two, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->package, $procedure, $params);

        return $result['CUR_RES'];
    }

    function get_table_count($sql)
    {

        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':XSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500)
        );
        $result = $this->conn->excuteProcedures('QF_PKG', 'GET_COUNT_TAB', $params);

        return $result['CUR_RES'];
    }

    function getList($procedure, $sql, $offset, $row)
    {

        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':offset', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':row', 'value' => $row, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->package, $procedure, $params);

        return $result['CUR_RES'];
    }

    function getListBySQL($procedure, $sql)
    {

        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->package, $procedure, $params);

        return $result['CUR_RES'];
    }

    function getAll($package, $procedure)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($package, $procedure, $params);
        return $result['CUR_RES'];
    }


    function getData($procedure)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->package, $procedure, $params);
        return $result['CUR_RES'];
    }



}