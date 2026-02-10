<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User_model extends MY_Model
{

    var $PKG_NAME= "DA3EM_PKG";

    function is_admin($username){
        $params =array(
            array('name'=>':USER_ID_IN','value'=>$username,'type'=>'','length'=>-1),
            array('name'=>':FLAG_OUT','value'=>'FLAG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'IS_ADMIN',$params);
        return $result["FLAG_OUT"];
    }

    function get_users(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_ALL_USERS',$params);
        return $result[(int)$cursor];
    }

    function get_admins(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_ADMINS',$params);
        return $result[(int)$cursor];
    }

    function insert_admin($name,$status){
        $params =array(
            array('name'=>':UNAME','value'=>$name,'type'=>SQLT_CHR),
            array('name'=>':STAS','value'=>$status,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ADMIN_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function update_admin($name,$status){
        $params =array(
            array('name'=>':UNAME','value'=>$name,'type'=>SQLT_CHR),
            array('name'=>':STAS','value'=>$status,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ADMIN_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete_admin($name){
        $params =array(
            array('name'=>':UNAME','value'=>$name,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ADMIN_DELETE',$params);
        return $result['MSG_OUT'];
    }

}