<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Document_model extends MY_Model
{
    var $PKG_NAME= "DOCUMENTS_PKG";

    function get_all_documents($category_id){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':CATEGORY_ID1','value'=>$category_id),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_ALL_DOC',$params);
        return $result[(int)$cursor];
    }

    function get_document($id){
      $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':ID1','value'=>$id,'type'=>SQLT_CHR),
            array('name' => ':REF_CUR_OUT', 'value' => $cursor, 'type' => OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_DOC',$params);
        return $result[(int)$cursor];
    }

    function get_documents_To_approve(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name' => ':REF_CUR_OUT', 'value' => $cursor, 'type' => OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_DOC_TO_APPROVE',$params);
        return $result[(int)$cursor];
    }

    function insert_document($data){
        $params =array(
            array('name'=>':DOC_NAME1','value'=>$data['DOC_NAME']),
            array('name'=>':CATEGORY_ID1','value'=>$data['CATEGORY_ID']),
            array('name'=>':IS_ACTIVE1','value'=>$data['IS_ACTIVE']),
            array('name'=>':FILES_CODE1','value'=>$data['FILES_CODE']),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DOC_INSERT',$params);
       // print_r($result);die();
        return $result['MSG_OUT'];
    }

    function update_document($data){
        $params =array(
            array('name'=>':DOC_ID','value'=>$data['ID']),
            array('name'=>':DOC_NAME1','value'=>$data['DOC_NAME']),
            array('name'=>':CATEGORY_ID1','value'=>$data['CATEGORY_ID']),
            array('name'=>':IS_ACTIVE1','value'=>$data['IS_ACTIVE']),
            array('name'=>':IS_VALID1','value'=>$data['IS_VALID']),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DOC_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    function approve_document($data){
        $params =array(
            array('name'=>':DOC_ID','value'=>$data['ID']),
            array('name'=>':STATUS','value'=>$data['status']),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DOC_APPROVE',$params);

        return $result['MSG_OUT'];
    }

    function delete_document($id){
        $params =array(
            array('name'=>':DOC_ID','value'=>$id),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DOC_DELETE',$params);
        return $result['MSG_OUT'];

    }
}