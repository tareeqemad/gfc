<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/10/18
 * Time: 09:31 ص
 */

class emps_absence_model extends MY_Model{
    var $PKG_NAME= "HR_ATTENDANCE_PKG";
    var $TABLE_NAME= 'EMPS_ABSENCE';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($sql,$offset,$row, $dt1='01/01/1800', $dt2='01/01/3000', $qr_type='', $is_note=0){

        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':DT1','value'=>$dt1,'type'=>'','length'=>-1),
            array('name'=>':DT2','value'=>$dt2,'type'=>'','length'=>-1),
            array('name'=>':QR_TYPE','value'=>$qr_type,'type'=>'','length'=>-1),
            array('name'=>':IS_NOTE','value'=>intval($is_note),'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

    function reason_insert($emp_no, $absence_date, $reason_no){
        $params =array(
            array('name'=>':EMP_NO','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>':ABSENCE_DATE','value'=>$absence_date,'type'=>'','length'=>-1),
            array('name'=>':REASON_NO','value'=>$reason_no,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMPS_ABSENCE_REASON_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function reason_update($ser, $emp_no, $reason_no){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':EMP_NO','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>':REASON_NO','value'=>$reason_no,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EMPS_ABSENCE_REASON_UPDATE',$params);
        return $result['MSG_OUT'];
    }

}
