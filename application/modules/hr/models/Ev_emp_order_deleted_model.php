<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 01/02/22
 * Time: 10:10 ص
 */

class Ev_emp_order_deleted_model extends MY_Model{
    var $PKG_NAME = "HR_PKG";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function check_emp_order($emp_no){
        $params =array(
            array('name'=>':EMP_NO','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'EVALUATION_EMP_ORDER_TB_CHECK',$params);
        return $result['MSG_OUT'];
    }

    function insert_delete($id, $notes){
        $params =array(
            array('name'=>':EMP_NO','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NOTES','value'=>$notes,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'EV_EMP_ORDER_DELETED_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }
}