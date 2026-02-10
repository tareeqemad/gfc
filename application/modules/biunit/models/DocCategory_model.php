<?php

class DocCategory_model extends MY_Model
{

    var $PKG_NAME= "DOCUMENTS_PKG";


    function get_all_categories(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_DOC_CATEGORIES',$params);
        return $result[(int)$cursor];
    }

    function get_category($id){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':CATEGORY_ID1','value'=>$id),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_CATEGORY',$params);
        //print_r($result[(int)$cursor]);
        return $result[(int)$cursor];
    }

    function insert_category($title){
        $params =array(
            array('name'=>':CATEGORY_TITLE','value'=>$title,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CATEGORY_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function update_category($id,$new_title){
        $params =array(
            array('name'=>':CATEGORY_ID','value'=>$id,'type'=>SQLT_CHR),
            array('name'=>':CAT_NEW_TITLE','value'=>$new_title,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CATEGORY_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete_category($id){
        $params =array(
            array('name'=>':CATEGORY_ID','value'=>$id,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CATEGORY_DELETE',$params);
        return $result['MSG_OUT'];
    }

}