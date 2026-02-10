<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 09:20 ص
 */

class Financial_payment_model extends MY_Model{
    var $PKG_NAME= "PAYMENT_PKG";
    var $TABLE_NAME= 'FINANCIAL_PAYMENT_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
   function get($id= 0){
        
        $params =array(
            array('name'=>':FINANCIAL_PAYMENT_ID','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    function get_all(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ALL',$params);
        return $result;
    }



    /**
     * @return array
     *
     * return all payment data ..
     */
    function get_list($sql,$offset,$row){

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'FINANCIAL_PAYMENT_TB_GET_LIST',$params);

        return $result;
    }



    /**
     * @return array
     *
     * return all payments data ..
     */
    function get_count($sql){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'FINANCIAL_PAYMENT_TB_GET_COUNT',$params);

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

    function receipt($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_RECEIPT',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':FINANCIAL_PAYMENT_ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    /**
     * @param $id

     */
    function adopt($id,$case,$hints = null){

        $params =array(
            array('name'=>':FINANCIAL_PAYMENT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':FINANCIAL_PAYMENT_CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':AUDIT_HINTS_IN','value'=>$hints,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FINANCIAL_PAYMENT_UPDATE_CASE',$params);

        return $result['MSG_OUT'];

    }


    function review_doc($id,$notes,$type,$note_category,$audit_manager_opinion,$specialized_side){

        $params =array(
            array('name'=>':FINANCIAL_PAYMENT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NOTES','value'=>$notes,'type'=>'','length'=>-1),
            array('name'=>':REVIEW_HINTS_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':NOTE_CATEGORY_IN','value'=>$note_category,'type'=>'','length'=>-1),
            array('name'=>':AUDIT_MANAGER_OPINION_IN','value'=>$audit_manager_opinion,'type'=>'','length'=>-1),
            array('name'=>':SPECIALIZED_SIDE_IN','value'=>$specialized_side,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FINANCIAL_PAYMENT_TB_UP_HINT',$params);

        return $result['MSG_OUT'];

    }

    function review_rest($id){

        $params =array(
            array('name'=>':FINANCIAL_PAYMENT_ID_IN','value'=>$id,'type'=>'','length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FINANCIAL_PAYMENT_TB_R_UP_HINT',$params);

        return $result['MSG_OUT'];

    }

    function FINANCIAL_PAYMENT_TB_B_UP_HINT($id,$notes){

        $params =array(
            array('name'=>':FINANCIAL_PAYMENT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NOTES','value'=>$notes,'type'=>'','length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FINANCIAL_PAYMENT_TB_B_UP_HINT',$params);

        return $result['MSG_OUT'];

    }


  function audit_dep($id){

        $params =array(
            array('name'=>':FINANCIAL_PAYMENT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FINANCIAL_PAYMENT_DEP_UP',$params);

        return $result['MSG_OUT'];

    }
	
    function validation($id,$count,$ded){

        $params =array(
            array('name'=>':FINANCIAL_PAYMENT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':PAY_DET_REC_COUNT_IN','value'=>$count,'type'=>'','length'=>-1),
            array('name'=>':DED_REC_COUNT_IN','value'=>$ded,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('PAYMENT_PKG','FINANCIAL_PAY_TB_CHECK_INPUT',$params);

        return $result['MSG_OUT'];

    }


    function get_invoices($customer = null , $curr_id = null){

        $params =array(
            array('name'=>':CURR_ID_IN','value'=>$curr_id,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID_IN','value'=>$customer,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->New_rmodel->general_get('MV_PKG','CUSTOMER_INVOICE_GET_LIST',$params);

        return $result;
    }

}
