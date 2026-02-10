<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 08/10/16
 * Time: 11:20 ص
 */

class Reports_model extends MY_Model{
    var $PKG_NAME= "HR_REP_PKG";
    var $TABLE_NAME= "FILL_UP_REP";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function fill($order_id= 0){
        $params =array(
            array('name'=>':EVALUATION_ORDER_ID','value'=>$order_id ,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,$this->TABLE_NAME,$params);
        return $result['MSG_OUT'];
    }

}