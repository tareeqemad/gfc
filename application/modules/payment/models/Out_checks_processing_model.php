<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:34 ص
 */

class Out_checks_processing_model extends MY_Model{

    /**
     * @return array
     *
     * return all CHECKS PROCESSING data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id){

        $params =array(
            array('name'=>':CHECKS_PROCESSING_PAY_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('PAYMENT_PKG','CHECKS_PROCESSING_PAY_TB_GET',$params);

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

        $result = $this->New_rmodel->general_get('PAYMENT_PKG','CHECKS_PROCESSING_PAY_GET_LIST',$params);

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

        $result = $this->New_rmodel->general_get('PAYMENT_PKG','CHECKS_PROCESSING_PAY_COUNT',$params);

        return $result;
    }

    /**
     * @param $data
     *
     * create new convert cash ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('PAYMENT_PKG','CHECKS_PROCESSING_PAY_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('PAYMENT_PKG','CHECKS_PROCESSING_PAY_UPDATE',$params);
        return $result['MSG_OUT'];
    }



    /**
     * @param $id
     * adopt cash
     */
    function adopt($id){

        $params =array(
            array('name'=>':CHECKS_PROCESSING_PAY_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('PAYMENT_PKG','CHECKS_PROCESSING_PAY_ADOPT',$params);

        return $result['MSG_OUT'];

    }

    function cancel($CHECK_ID_IN,$CHECK_PORTFOLIO_IN){

        $params =array(
            array('name'=>':CHECK_ID_IN','value'=>$CHECK_ID_IN,'type'=>'','length'=>-1),
            array('name'=>':CHECK_PORTFOLIO_IN','value'=>$CHECK_PORTFOLIO_IN,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('PAYMENT_PKG','CHECKS_PROCESSING_PAY_CANCEL',$params);

        return $result['MSG_OUT'];

    }


    function pay_checks_account_id($SEQ_id){

        $params =array(

            array('name'=>':SEQ_ID_IN','value'=>$SEQ_id,'type'=>'','length'=>-1),

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('PAYMENT_PKG','PAYMENT_CHECKS_ACCOUNT_ID',$params);

        return $result;
    }
}