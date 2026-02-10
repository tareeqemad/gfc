<?php

class News_model extends MY_Model
{
    var $PKG_NAME= "DA3EM_PKG";

    function get_all_news(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_ALL_NEWS',$params);
        return $result[(int)$cursor];
    }

    function get_last_active(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_LAST_ACTIVE',$params);
        return $result[(int)$cursor];
    }


    function insert_news($title,$date,$active,$link){
        $params =array(
            array('name'=>':NEWS_TITLE','value'=>$title,'type'=>SQLT_CHR),
            array('name'=>':NEWS_DATE','value'=>$date,'type'=>SQLT_CHR),
            array('name'=>':ACT','value'=>$active,'type'=>SQLT_CHR),
            array('name'=>':LNK','value'=>$link,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'NEWS_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function update_news($id,$title,$date,$active,$link){
        $params =array(
            array('name'=>':NEWS_ID','value'=>$id,'type'=>SQLT_CHR),
            array('name'=>':NEWS_TITLE','value'=>$title,'type'=>SQLT_CHR),
            array('name'=>':NEWS_DATE','value'=>$date,'type'=>SQLT_CHR),
            array('name'=>':ACT','value'=>$active,'type'=>SQLT_CHR),
            array('name'=>':LNK','value'=>$link,'type'=>SQLT_CHR),
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
