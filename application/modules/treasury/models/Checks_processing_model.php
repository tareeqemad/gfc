<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:34 ص
 */

class Checks_processing_model extends MY_Model{


    /**
     * @return array
     *
     * return all cashs data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id){

        

        $params =array(
            array('name'=>':CHECKS_PROCESSING_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CHECKS_PROCESSING_TB_GET',$params);

        return $result;
    }
    /**
     * @return array
     *
     * return all cashs data ..
     */
    function get_all(){

        

        $params =array(

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CONVERT_CASH_TB_GET_ALL',$params);

        return $result;
    }

    /**
     * @return array
     *
     * return all cash data ..
     */
    function get_list($sql,$offset,$row){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CHECKS_PROCESSING_TB_GET_LIST',$params);

        return $result;
    }

    function checks_list($sql,$offset,$row){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CHECKS_LIST',$params);

        return $result;
    }

    function checks_count($sql){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CHECKS_COUNT',$params);

        return $result;
    }


    /**
     * @return array
     *
     * return all cash data ..
     */
    function get_count($sql){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CHECKS_PROCESSING_TB_GET_COUNT',$params);

        return $result;
    }

    /**
     * @param $data
     *
     * create new check proccesing ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TREASURY_PKG','CHECKS_PROCESSING_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function update($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TREASURY_PKG','CHECKS_PROCESSING_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TREASURY_PKG','CHECKS_PROCESSING_TB_EDIT',$params);
        return $result['MSG_OUT'];
    }
    /**
     * @return array
     *
     * return all cashs data ..
     */
    function get_debit_sum($id){

        

        $params =array(
            array('name'=>':DEBIT_ACCOUNT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_TB_SUM_CREDIT',$params);

        return $result;
    }


    /**
     * @return array
     *
     * return all cashs data ..
     */
    function get_service($id){

        

        $params =array(
            array('name'=>':SHAR_NO_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','SERVICE_FEES_NEED_GET',$params);

        return $result;
    }


    /**
     * @param $id
     * adopt cash
     */
    function adopt($id,$case){

        $params =array(
            array('name'=>':CHECKS_PROCESSING_CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':CHECKS_PROCESSING_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','CHECKS_PROCESSING_UPDATE_CASE',$params);

        return $result['MSG_OUT'];

    }


    function cancel($CHECK_ID_IN,$CHECK_PORTFOLIO_IN){

        $params =array(
            array('name'=>':CHECK_ID_IN','value'=>$CHECK_ID_IN,'type'=>'','length'=>-1),
            array('name'=>':CHECK_PORTFOLIO_IN','value'=>$CHECK_PORTFOLIO_IN,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','CHECKS_PROCESSING_TB_CANCEL',$params);

        return $result['MSG_OUT'];

    }



}