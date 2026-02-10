<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 16/03/15
 * Time: 10:01 ص
 */

class purchase_order_detail_model extends MY_Model{
    var $PKG_NAME= "PURCHASE_PKG";
    var $TABLE_NAME= 'PURCHASE_ORDER_DET_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    function get_details_all($id= 0){
        
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':SHOW_APPROVED_IN','value'=>1,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }
    function get_lists($sql){
        
        $params =array(
            array('name'=>':SQL_IN','value'=>$sql ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }
    function get_list($id= 0){
        
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':SHOW_APPROVED_IN','value'=>0,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

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

    function edit_approved($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_DET_UP_APROVE',$params);
        return $result['MSG_OUT'];
    }

    function quote_order_date($ser, $date){
        $params =array(
            array('name'=>':SER_IN','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':ORDER_DATE_IN','value'=>$date,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_DET_ORDER_DATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }
    function update_award($class_id,$class_unit,$award_delay_decision,$award_delay_decision_hint,$delay){
        $params =array(
            array('name'=>':CLASS_ID','value'=>$class_id,'type'=>'','length'=>-1),
            array('name'=>':CLASS_UNIT','value'=>$class_unit,'type'=>'','length'=>-1),
            array('name'=>':AWARD_DELAY_DECISION','value'=>$award_delay_decision,'type'=>'','length'=>-1),
            array('name'=>':AWARD_DELAY_DECISION_HINT','value'=>$award_delay_decision_hint,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DELAY_ID','value'=>$delay,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'PURCHASE_ORDER_DET_UP_AWARD',$params);
        return $result['MSG_OUT'];
    }

}
