<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/09/14
 * Time: 09:08 ص
 */

class Login_model extends MY_Model{
    var $PKG_NAME= "USER_PKG";
    var $TABLE_NAME= 'USERS_PROG_TB';

    function get($user_id, $user_pwd){
        
        $params =array(
            array('name'=>':USER_ID','value'=>$user_id ,'type'=>'','length'=>-1),
            array('name'=>':USER_PWD','value'=>$user_pwd ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'REF_CUR_OUT' ,'type'=>'cursor'),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_LOGIN', $params);
        return $result['REF_CUR_OUT'];
    }

    function get_task_user($emp_id=0){
        
        $params =array(
            array('name'=>':I_EMPLOYEE','value'=>$emp_id ,'type'=>'','length'=>-1),
            array('name'=>':O_EMP_DATA','value'=>'REF_CUR_OUT' ,'type'=>'cursor')
        );
        $result = $this->conn->excuteProcedures('TASK_ADMIN.PRIVILEGES_PKG', 'GET_USER_SESSION_DATA', $params);
        return $result['REF_CUR_OUT'];
    }

    function get_task_user_menu($emp_id=0){
        
        $params =array(
            array('name'=>':I_EMPLOYEE','value'=>$emp_id ,'type'=>'','length'=>-1),
            array('name'=>':O_EMP_DATA','value'=>'REF_CUR_OUT' ,'type'=>'cursor')

        );
        $result = $this->conn->excuteProcedures('TASK_ADMIN.PRIVILEGES_PKG', 'GET_EMPLOYEE_MENU_ITEMS', $params);
        return $result['REF_CUR_OUT'];
    }

    function get_task_user_profile($emp_id=0){
        
        $params =array(
            array('name'=>':I_EMPLOYEE','value'=>$emp_id ,'type'=>'','length'=>-1),
            array('name'=>':O_EMP_DATA','value'=>'REF_CUR_OUT' ,'type'=>'cursor')
        );
        $result = $this->conn->excuteProcedures('TASK_ADMIN.PRIVILEGES_PKG', 'GET_USER_PROFILE', $params);
        return $result['REF_CUR_OUT'];
    }

}
