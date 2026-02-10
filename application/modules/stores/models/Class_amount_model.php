<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/01/15
 * Time: 07:26 م
 */

class class_amount_model extends MY_Model{
    var $PKG_NAME= "STORES_PKG";
    var $TABLE_NAME= 'CLASS_AMOUNT_VW';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_list($store_id= null, $class_id= null, $class_id2= null, $get_min=0, $without_resv=0){
        
        $params =array(
            array('name'=>':STORE_ID_IN','value'=>$store_id,'type'=>'','length'=>-1),
            array('name'=>':CLASS_ID_IN','value'=>$class_id,'type'=>'','length'=>-1),
            array('name'=>':CLASS_ID2_IN','value'=>$class_id2,'type'=>'','length'=>-1),
            array('name'=>':CHECK_MIN','value'=>$get_min,'type'=>'','length'=>-1),
            array('name'=>':CHECK_RESERVE','value'=>$without_resv,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }

    function get_count($insql= ''){
        
        $params =array(
            array('name'=>':INSQL','value'=>$insql,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('QF_PKG','GET_COUNT_TAB',$params);
        return $result;
    }

    function get_actions($insql= '', $order= '', $offset=0, $row=0){
        
        $params =array(
            array('name'=>':INSQL','value'=>$insql,'type'=>'','length'=>-1),
            array('name'=>':ORDER','value'=>$order,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_ACTIONS_LIST',$params);

        return $result;
    }

    function get_balance($insql= ''){
        
        $params =array(
            array('name'=>':INSQL','value'=>$insql,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_GET_BALANCE',$params);
        return $result;
    }


    function actions_list($insql= '', $offset=0, $row=0){
        
        $params =array(
            array('name'=>':INSQL','value'=>$insql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'STORES_ACTIONS_VW_LIST',$params);
        return $result;
    }

    function stores_val_list($insql= ''){
        
        $params =array(
            array('name'=>':INSQL','value'=>$insql,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_STORE_VAL',$params);
        return $result;
    }

}
