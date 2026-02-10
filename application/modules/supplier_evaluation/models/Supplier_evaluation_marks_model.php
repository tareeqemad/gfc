<?php

class supplier_evaluation_marks_model extends MY_Model
{
    var $PKG_NAME = "SUPPLIER_EVALUATION_PKG";
    var $TABLE_NAME = 'SUPPLIER_EVALUATION_TB';
    var $TABLE_NAME_MARKS = 'SUPPLIER_MARKS_TB';


    function get_page($purchase_request_id, $par_1, $par_2)
    {
    
        $params = array(array('name' => ':purchase_request_id', 'value' => $purchase_request_id, 'type' => '', 'length' => -1), array('name' => ':OFFSET', 'value' => $par_1, 'type' => '', 'length' => -1), array('name' => ':ROW', 'value' => $par_2, 'type' => '', 'length' => -1), array('name' => ':GET_ROW_LOG', 'value' => $cursor, 'type' => OCI_B_CURSOR), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500));
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME . '_GET_ALL', $params);
        return $result[(int)$cursor];
    }


    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function create_marks($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME_MARKS . '_INSERT', $params);
         return $result;
    }

    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_UPDATE', $params);

        return $result['MSG_OUT'];
    }

    function edit_marks($data)
    {

        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME_MARKS . '_UPDATE', $params);
        return $result['MSG_OUT'];
    }

    function get($id = 0)
    {
	     $params = array(array('name' => ':PURCHASE_REQUEST_ID', 'value' => $id, 'type' => '', 'length' => -1), array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500));
          $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;

    }

    function get_marks($ser, $id)
    {
    
        $params = array(array('name' => ':SER_EVALUATION', 'value' => $ser, 'type' => '', 'length' => -1), array('name' => ':ID_FATHER', 'value' => $id, 'type' => '', 'length' => -1), array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500));
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME_MARKS . '_GET', $params);
          return $result;
    }


    function get_list($father_id)
    {

        $params = array(array('name' => ':FATHER_ID', 'value' => $father_id, 'type' => '', 'length' => -1), array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1));
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'SUPPLIER_EV_FORM_LIST', $params);
        return $result;

    }

    function purchase_order_get($purchase_order_num)
    {

        $params = array(array('name' => ':purchase_order_num', 'value' => $purchase_order_num, 'type' => '', 'length' => -1), array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR), array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1));
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'PURCHASE_ORDER_GET', $params);
        return $result;

    }
    function adopt($id, $case, $note = '')
    {
        $params = array(
            array('name' => ':SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':ADOPT_IN', 'value' => $case, 'type' => '', 'length' => -1),
            array('name' => ':NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME . '_ADOPT', $params);

        return $result['MSG_OUT'];
    }

    function get_list_all($sql, $offset, $row)
    {

        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'REP_SUPPLIER_EVALUATION_LIST', $params);
        return $result;
    }

    function m_get_list_all($sql, $offset, $row)
    {

        $params = array(
            array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
            array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
            array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'M_REP_SUPPLIER_EVALUATION_LIST', $params);
        return $result;
    }

}
