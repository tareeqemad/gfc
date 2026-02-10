<?php

class Gcc_tree_structure_model extends MY_Model
{

    var $PKG_NAME = "CONTRACTS_SERVICES_PKG";
    var $TABLE_NAME = 'CONTRACT_SERVICES_TB';


    function get($id = 0)
    {

        $cursor = $this->db->get_cursor();

        $params = array(
            array('name' => ':GCC_ID', 'value' => "{$id}", 'type' => SQLT_CHR, 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => $cursor, 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_GET', $params);

        return $result[(int)$cursor];
    }


    function get_child($parent_id = 0)
    {
        $cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':GCC_PARENT_ID', 'value' => $parent_id, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => $cursor, 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => 500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_CHILD', $params);
        return $result[(int)$cursor];
    }


    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function create_contracts($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CONTRACT_SERVICES_CON_INSERT', $params);
        return $result['MSG_OUT'];
    }


    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    function delete($id)
    {

        if (trim($id) != '') {
            $params = array(
                array('name' => ':ST_ID_in', 'value' => $id, 'type' => '', 'length' => -1),
                array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
            );

            $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_DELETE', $params);

            return $result['MSG_OUT'];
        } else return 0;

    }


}
