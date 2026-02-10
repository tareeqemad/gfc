<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 09:33 ص
 */

class Customers_model extends MY_Model{
    var $PKG_NAME= "PAYMENT_PKG";
    var $TABLE_NAME= 'CUSTOMERS_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id= 0){
        
        $params =array(
            array('name'=>':CUSTOMER_ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    function get_count($customer_id, $customer_name, $customer_type, $account_id){
        
        $params =array(
            array('name'=>':CUSTOMER_ID','value'=>$customer_id,'type'=>'','length'=>-1),
            array('name'=>':CUSTOMER_NAME','value'=>"{$customer_name}",'type'=>'','length'=>-1),
            array('name'=>':CUSTOMER_TYPE','value'=>$customer_type,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID','value'=>$account_id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_COUNT',$params);
        return $result;
    }

    function get_list($customer_id, $customer_name, $customer_type, $account_id, $offset, $row){
        
        $params =array(
            array('name'=>':CUSTOMER_ID','value'=>$customer_id,'type'=>'','length'=>-1),
            array('name'=>':CUSTOMER_NAME','value'=>"{$customer_name}",'type'=>'','length'=>-1),
            array('name'=>':CUSTOMER_TYPE','value'=>$customer_type,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID','value'=>$account_id,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('MV_PKG', $this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }

    function get_all(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ALL',$params);
        return $result;
    }
    function get_all_by_type($customer_type){
        
        $params =array(
            array('name'=>':CUSTOMER_TYPE','value'=>$customer_type,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ALL_BY_TYPE',$params);
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

    function delete($id){
        $params =array(
            array('name'=>':CUSTOMER_SEQ','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }
  /*  function add_purchase_type($id, $purchase_type,$note){
        $params =array();
       $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADVER_NO_IN','value'=>$adver_no,'type'=>'','length'=>-1),
            array('name'=>':SERIAL_IN','value'=>$serial,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_ADVERTISEMENT',$params);
        return $result['MSG_OUT'];
    }*/
    function get_purchases($id= 0){
        
        $params =array(
            array('name'=>':CUSTOMER_ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('PURCHASE_PKG', 'CUSTOMERS_PURCHASE_TB_GET_CUST',$params);
        return $result;
    }
    function create_pur($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('PURCHASE_PKG', 'CUSTOMERS_PURCHASE_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function edit_pur($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('PURCHASE_PKG', 'CUSTOMERS_PURCHASE_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete_pur($id){
        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures('PURCHASE_PKG', 'CUSTOMERS_PURCHASE_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function customers_purchase_get_list($insql, $offset, $row){
        
        $params =array(

            array('name'=>':INSQL','value'=>$insql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('PURCHASE_PKG', 'CUSTOMERS_PURCHASE_TB_LIST',$params);
        return $result;
    }

}
