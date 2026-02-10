<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/11/14
 * Time: 09:24 ص
 */

class invoice_class_input_model extends MY_Model{
    var $PKG_NAME= "STORES_PKG";
    var $TABLE_NAME= 'INVOICE_CLASS_INPUT_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id= 0){
        
        $params =array(
            array('name'=>':CLASS_INPUT_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }
    function get_count($sql){

        
        $params =array(
            array('name'=>':XSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('QF_PKG','GET_COUNT_TAB',$params);
        return $result;
    }
    function get_list($sql,$offset,$row){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>1000)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INVOICE_CLASS_INPUT_GET_LIST',$params);
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
            array('name'=>':CLASS_INPUT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function transfer($id){
        $params =array(
            array('name'=>':CLASS_INPUT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_TRANSFER',$params);
        return $result['MSG_OUT'];
    }
    function update_case($id,$case){
        $params =array(
            array('name'=>':CLASS_INPUT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':INVOICE_CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_CASE',$params);
        return $result['MSG_OUT'];
    }
    function back_case($id,$case){
        $params =array(
            array('name'=>':CLASS_INPUT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':INVOICE_CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INVOICE_CLASS_INPUT_BACK_CASE',$params);
        return $result['MSG_OUT'];
    }

    function invoice_validate($id){
        $params =array(
            array('name'=>':CLASS_INPUT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
                  );
        //tasneem
      //  $result = $this->conn->excuteProcedures($this->PKG_NAME,'INVOICE_CLASS_INPUT_VALIDATE',$params);
        $result = $this->conn->excuteProcedures('STORES_QUEED_PKG', 'INVOICE_CLASS_INPUT_QUEED', $params);
        return $result['MSG_OUT'];
    }
   /*  tasneem
   function invoice_qeed($id){
    $params =array(
        array('name'=>':CLASS_INPUT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
        array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
    );
    $result = $this->conn->excuteProcedures($this->PKG_NAME,'INVOICE_CLASS_INPUT_QEED',$params);
    return $result['MSG_OUT'];
}*/
    function customer_has_other_invoice($id,$customer_resource_id,$invoice_id){
        $params =array(
            array('name'=>':CLASS_INPUT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CUSTOMER_RESOURCE_ID_IN','value'=>$customer_resource_id,'type'=>'','length'=>-1),
            array('name'=>':INVOICE_ID_IN','value'=>$invoice_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INVOICE_CLASS_INPUT_DUP_CUST',$params);
        return $result['MSG_OUT'];
    }
}
