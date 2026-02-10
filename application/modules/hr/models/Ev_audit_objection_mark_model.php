<?php

class ev_audit_objection_mark_model extends MY_Model{

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 12/08/21
 * Time: 09:50 ص
 */

    var $PKG_NAME= "HR_PKG";
    var $TABLE_NAME= 'EV_AUDIT_OBJECTION_MARK';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_active(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'EVALUATION_ORDER_GET_ACTIVE',$params);
        return $result;
    }
/*
    function get_page($evaluation_order_id,$par_1,$par_2){
        
        $params =array(
            array('name'=>':EVALUATION_ORDER_ID','value'=>$evaluation_order_id,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$par_1,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$par_2,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'EVALUATION_ORDER_ARCHIVES_LIST',$params);
        return $result;
    }
*/
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

}