<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/03/18
 * Time: 11:04 ص
 */

class exit_permission_sum_model extends MY_Model{
    var $PKG_NAME= "HR_ATTENDANCE_PKG";
    var $TABLE_NAME= 'EXIT_PERMISSION_SUM_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

}
