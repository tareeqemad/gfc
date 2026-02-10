<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 10/12/17
 * Time: 09:29 ص
 */

class Energy_model  extends MY_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('LOAD_FLOW_PKG', 'ENERGY_READS_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }



    function edit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures('LOAD_FLOW_PKG', 'ENERGY_READS_TB_UPDATE', $params);


        return $result['MSG_OUT'];
    }


    function get($id = 0)
    {

        $params = array(
            array('name' => ':ID_in', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1),
        );

        $result = $this->New_rmodel->general_get('LOAD_FLOW_PKG', 'ENERGY_READS_TB_GET', $params);

        return $result;
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

        $result = $this->New_rmodel->general_get('LOAD_FLOW_PKG', 'ENERGY_READS_TB_LIST', $params);

        return $result;
    }



} 