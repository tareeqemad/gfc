<?php

class Indicator_model extends MY_Model
{
    var $PKG_NAME= "DA3EM_PKG";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_all_indicators(){
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'GET_ALL_INDICATORS',$params);
        return $result;
    }

    function insert_indicator($title,$val,$act,$icon){
        $params =array(
            array('name'=>':INDICATOR_TITLE','value'=>$title,'type'=>SQLT_CHR),
            array('name'=>':VAL','value'=>$val,'type'=>SQLT_CHR),
            array('name'=>':ACT','value'=>$act,'type'=>SQLT_CHR),
            array('name'=>':IN_ICON','value'=>$icon,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'INDICATOR_INSERT',$params);
        return $result['MSG_OUT'];

    }

    function update_indicator($id,$title,$val,$active,$icon){
        $params =array(
            array('name'=>':INDICATOR_ID','value'=>$id,'type'=>SQLT_CHR),
            array('name'=>':INDICATOR_TITLE','value'=>$title,'type'=>SQLT_CHR),
            array('name'=>':VAL','value'=>$val,'type'=>SQLT_CHR),
            array('name'=>':ACT','value'=>$active,'type'=>SQLT_CHR),
            array('name'=>':IN_ICON','value'=>$icon,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'INDICATOR_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    function delete_indicator($id){
        $params =array(
            array('name'=>':INDICATOR_ID','value'=>$id,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'INDICATOR_DELETE',$params);
        return $result['MSG_OUT'];
    }

}
