<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 13/03/18
 * Time: 09:19 ص
 */

class hr_attendance_model extends MY_Model{
    var $PKG_NAME= "HR_ATTENDANCE_PKG";
    var $TABLE_NAME= '';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_child($manager= 0, $action=0){

        $params =array(
            array('name'=>':EMPLOYEE_NO_IN','value'=>$manager ,'type'=>'','length'=>-1),
            array('name'=>':ACTION_IN','value'=>$action ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'EVA_EMPS_STRUCTURE_CHILD_GET',$params);
        return $result;
    }

}