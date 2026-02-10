<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 26/08/15
 * Time: 10:07 ص
 */

class purchase_order_items_model extends MY_Model{
    var $PKG_NAME= "PURCHASE_PKG";
    var $TABLE_NAME= 'PURCHASE_ORDER_ITEMS_TB';

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
    function get_lists($sql){
        
        $params =array(
            array('name'=>':SQL_IN','value'=>$sql ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'PURCHASE_ORDER_ITEMS_GET_LIST',$params);
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
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_ITM_UP_APROVE',$params);
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
    function update_award($ser,$class_unit,$award_delay_decision,$award_delay_decision_hint,$delay){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':CLASS_UNIT','value'=>$class_unit,'type'=>'','length'=>-1),
            array('name'=>':AWARD_DELAY_DECISION','value'=>$award_delay_decision,'type'=>'','length'=>-1),
            array('name'=>':AWARD_DELAY_DECISION_HINT','value'=>$award_delay_decision_hint,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DELAY_ID','value'=>$delay,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'PURCHASE_ORDER_ITEMS_UP_AWARD',$params);
        return $result['MSG_OUT'];
    }
}
