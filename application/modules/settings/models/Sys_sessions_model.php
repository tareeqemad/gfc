<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 31/01/15
 * Time: 04:33 م
 */

class Sys_sessions_model extends MY_Model{
    var $PKG_NAME= "SETTING_PKG";
    var $TABLE_NAME= 'SYS_SESSIONS';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_list($where){
        
        $params =array(
            array('name'=>':INSQL','value'=>$where,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }

    function create_edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function edit_status($id, $status){
        $params =array(
            array('name'=>':ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':STATUS','value'=>$status,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures('USER_PKG', 'USERS_PROG_TB_UPDATE_STATUS',$params);
        return $result['MSG_OUT'];
    }

    function get_status($id){
        
        $params =array(
            array('name'=>':ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('USER_PKG', 'USERS_PROG_TB_GET_STATUS',$params);
        return $result;
    }

}
