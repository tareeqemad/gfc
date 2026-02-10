<?php

class News_model extends MY_Model
{
    var $PKG_NAME= "DA3EM_PKG";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_all_news(){
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'GET_ALL_NEWS',$params);
        return $result;
    }

    function get_last_active(){
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'GET_LAST_ACTIVE',$params);
        return $result;
    }


    function insert_news($title,$date,$active){
        $params =array(
            array('name'=>':NEWS_TITLE','value'=>$title,'type'=>SQLT_CHR),
            array('name'=>':NEWS_DATE','value'=>$date,'type'=>SQLT_CHR),
            array('name'=>':ACT','value'=>$active,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'NEWS_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function update_news($id,$title,$date,$active){
        $params =array(
            array('name'=>':NEWS_ID','value'=>$id,'type'=>SQLT_CHR),
            array('name'=>':NEWS_TITLE','value'=>$title,'type'=>SQLT_CHR),
            array('name'=>':NEWS_DATE','value'=>$date,'type'=>SQLT_CHR),
            array('name'=>':ACT','value'=>$active,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'NEWS_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete_news($id){
        $params =array(
            array('name'=>':NEWS_ID','value'=>$id,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'NEWS_DELETE',$params);
        return $result['MSG_OUT'];
    }

}
