<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/08/18
 * Time: 09:19 ص
 */

class clock_data_model extends MY_Model{
    var $PKG_NAME= "HR_ATTENDANCE_PKG";
    var $TABLE_NAME= 'CLOCK_DATA_VW';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($sql,$offset,$row,$sql2=''){

        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':INXSQL2','value'=>"{$sql2}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

    function trans_no_entry_leave($ser, $emp_no, $entry_date, $status)
    {
        $params = array(
            array('name' => ':SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => ':EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
            array('name' => ':ENTRY_DATE', 'value' => $entry_date, 'type' => '', 'length' => -1),
            array('name' => ':STATUS', 'value' => $status, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => -1)
        );
        $result = $this->conn->excuteProcedures('TRANSACTION_PKG', 'NO_ENTRY_LEAVE_TB_TRANS', $params);
        return $result['MSG_OUT'];
    }

    function edit_status($ser, $emp_no, $status){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':EMP_NO','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>':STATUS','value'=>$status,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DATA_ATTENDANCE_UPDATE',$params);
        return $result['MSG_OUT'];
    }

}
