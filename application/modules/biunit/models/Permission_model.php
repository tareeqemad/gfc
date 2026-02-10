<?php

class Permission_model extends MY_Model
{
    var $PKG_NAME= "DOCUMENTS_PKG";

    function get_all_users(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_ALL_USERS',$params);
        return $result[(int)$cursor];
    }

    function get_all_permissions(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_ALL_PERMISSIONS',$params);
        return $result[(int)$cursor];
    }

    function get_all_roles(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_ALL_ROLES',$params);
        return $result[(int)$cursor];
    }

    function get_user_permissions($id){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':USER_ID1','value'=>$id,'type'=>SQLT_CHR),
            array('name' => ':REF_CUR_OUT', 'value' => $cursor, 'type' => OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_USER_PERMISSIONS',$params);
        return $result[(int)$cursor];
    }


    function insert_permission($data){
        $params =array(
            array('name'=>':USER_ID1','value'=>$data['user']),
            array('name'=>':ROLE_ID1','value'=>$data['role']),
            array('name'=>':ADD1','value'=>$data['add']),
            array('name'=>':EDIT1','value'=>$data['edit']),
            array('name'=>':DELETE1','value'=>$data['delete']),
            array('name'=>':created_user','value'=>$data['created_user']),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PERMISSION_INSERT',$params);
        // print_r($result);die();
        return $result['MSG_OUT'];
    }

    function update_permission($data){

    }


    function delete_permission($id){
        $params =array(
            array('name'=>':PERMISSION_ID','value'=>$id),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PERMISSION_DELETE',$params);
        return $result['MSG_OUT'];

    }

}