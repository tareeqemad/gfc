<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 25/02/2020
 * Time: 11:38 ص
 */
class Overtime_calc_model extends MY_Model{


    var $PKG_NAME= "TRANSACTION_PKG";
    var $TABLE_NAME= 'OVERTIME';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    /*function get_list($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'OVERTIME_CALC_LIST',$params);
        return $result;
    }*/


    function get_d($EMP_BRANCH = '' , $MONTH = ''  )
    {
        
        $params = array(
            array('name' => ':EMP_BRANCH', 'value' => $EMP_BRANCH, 'type' => '', 'length' => -1),
            array('name' => ':MONTH', 'value' => $MONTH, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'OVERTIME_CALC_GET', $params);
        return $result;
    }


}
