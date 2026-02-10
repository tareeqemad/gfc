<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 30/12/14
 * Time: 04:15 م
 */

class checks_portfolio_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_list($sql,$offset,$row){

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('PAYMENT_PKG','CHECKS_PORTFOLIO_TB_LIST',$params);

        return $result;
    }

    function get_count($sql){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('PAYMENT_PKG','CHECKS_PORTFOLIO_TB_COUNT',$params);

        return $result;
    }

    function get_pay_list($sql,$offset,$row){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('PAYMENT_PKG','CHECKS_PORTFOLIO_TB_PAY_LIST',$params);

        return $result;
    }

    function get_pay_count($sql){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('PAYMENT_PKG','CHECKS_PORTFOLIO_TB_PAY_COUNT',$params);

        return $result;
    }

    function checks_portfolio_rep($type,$branch){

        

        $params =array(
            array('name'=>':SOURCE_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),

        );

        $result = $this->New_rmodel->general_get('PAYMENT_REP_PKG','CHECKS_PORTFOLIO_REP',$params);

        return $result;
    }

    function checks_portfolio_process_rep($branch){ // New_rmodel Done

        $params =array(

            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),

        );

        $result = $this->New_rmodel->general_get('PAYMENT_REP_PKG','CHECKS_PORTFOLIO_PROCESS_REP',$params,0);

        return $result['CUR_RES'];
    }

     function checks_processing_change($id,$debit,$credit,$date,$account_type_debit , $account_type_credit,$hints){

        $params =array(
            array('name'=>':CHECKS_PROCESSING_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CHECKS_PROCESSING_DEBIT_IN','value'=>$debit,'type'=>'','length'=>-1),
            array('name'=>':CONVERT_CASH_BANK_DATE_IN','value'=>$date,'type'=>'','length'=>-1),
            array('name'=>':CHECKS_PROCESSING_CREDIT_IN','value'=>$credit,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_TYPE_DEBIT_IN','value'=>$account_type_debit,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_TYPE_CREDIT_IN','value'=>$account_type_credit,'type'=>'','length'=>-1),
            array('name'=>':HINTS','value'=>$hints,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures('TREASURY_PKG', 'CHECKS_PROCESSING_CHANGE',$params);
        return $result['MSG_OUT'];
    }

    function checks_processing_pay_change($id,$debit,$credit, $date,$account_type_debit , $account_type_credit){

        $params =array(
            array('name'=>':CHECKS_PROCESSING_PAY_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CHECKS_PROCESSING_DEBIT_IN','value'=>$debit,'type'=>'','length'=>-1),
            array('name'=>':CHECKS_PROCESSING_CREDIT_IN','value'=>$credit,'type'=>'','length'=>-1),
            array('name'=>':DATE_IN','value'=>$date,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_TYPE_DEBIT_IN','value'=>$account_type_debit,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_TYPE_CREDIT_IN','value'=>$account_type_credit,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>5000)
        );
        $result = $this->conn->excuteProcedures('PAYMENT_PKG', 'CHECKS_PROCESSING_PAY_CHANGE',$params);
        return $result['MSG_OUT'];
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('PAYMENT_PKG', 'CHECKS_PORTFOLIO_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('PAYMENT_PKG', 'CHECKS_PORTFOLIO_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':SEQ_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures('PAYMENT_PKG', 'CHECKS_PORTFOLIO_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }

}