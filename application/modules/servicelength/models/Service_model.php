<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 06/02/2019
 * Time: 8:10 ص
 */
class Service_model extends MY_Model
{
    var $PKG_NAME = "HR_VACANCY_PKG";
    var $TABLE_NAME = 'EMP_VACANCY_TB';
    var $GET_E = '';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMP_VACANCY_TB_HR_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function get($emp_no = 0)
    {

        $params = array(
            array('name' => ':EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RETIREMENT_EMP_V_GET', $params);
        return $result;
    }



    function getDateDiff($FROM_DATE = '' ,$TO_DATE='')
    {
        echo 1;
        die();

        $params = array(
            array('name'=>':FROM_DATE','value'=>$FROM_DATE ,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>$TO_DATE ,'type'=>'','length'=>-1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)

         );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'DATE_DIFF', $params);

        return $result;
    }
    function getTwoColum($package, $procedure, $EMP_NO,$THE_TYPE )
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':EMP_NO_IN', 'value' => $EMP_NO, 'type' => '', 'length' => -1),
            array('name' => ':THE_TYPE_IN', 'value' => $THE_TYPE, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        return $result['CUR_RES'];
    }
    function update($package, $procedure, $EMP_NO,$THE_TYPE )
    {
        $cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':EMP_NO_IN', 'value' => $EMP_NO, 'type' => '', 'length' => -1),
            array('name' => ':THE_TYPE_IN', 'value' => $THE_TYPE, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($package, $procedure, $params);

        return $result['CUR_RES'];
    }

}




