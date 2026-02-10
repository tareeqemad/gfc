<?php

class Category_model extends MY_Model
{
    var $PKG_NAME= "DA3EM_PKG";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_all_categories(){
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'GET_ALL_CATEGORIES',$params);
        return $result;
    }

    function get_category($id){
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
