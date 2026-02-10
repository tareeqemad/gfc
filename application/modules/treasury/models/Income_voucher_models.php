<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:34 ص
 */

class Income_voucher_models extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id){

        $params =array(
            array('name'=>':VOUCHER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_TB_GET',$params);

        return $result;
    }


    /**
 * @return array
 *
 * return all income vouchers data ..
 */
    function get_all(){


        $params =array(

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_TB_GET_ALL',$params);

        return $result;
    }

    /**
     * @return array
     *
     * return all income voucher data ..
     */
    function get_list($sql,$offset,$row){

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_TB_GET_LIST',$params);

        return $result;
    }

    /**
     * @return array
     *
     * return all income voucher data ..
     */
    function get_list_archive($sql,$offset,$row){

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_TB_FAN_LIST',$params);

        return $result;
    }
    /**
     * @return array
     *
     * return all vouchers data ..
     */
    function get_count($sql){

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_TB_GET_COUNT',$params);

        return $result;
    }


    /**
     * @return array
     *
     * return all income vouchers data ..
     */
    function get_service($id){

        $params =array(
            array('name'=>':SHAR_NO_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','SERVICE_FEES_NEED_GET',$params);

        return $result;
    }

    /**
     * @param $data
     *
     * create new voucher ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TREASURY_PKG','INCOME_VOUCHER_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    /**
     * @param $data
     *
     * create new voucher details ..
     */
    function create_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TREASURY_PKG','INCOME_VOUCHER_DET_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }
    /**
     * @param $data
     *
     * create new voucher details ..
     */
    function create_details2($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TREASURY_PKG','INCOME_VOUCHER_DET_2_INSERT',$params);
        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * adopt cash
     */
    function adopt($id,$case){

        $params =array(
            array('name'=>':ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','INCOME_VOUCHER_TB_UPDATE_CASE',$params);

        return $result['MSG_OUT'];

    }

    function validation($id,$count,$fees = null){

        $params =array(
            array('name'=>':VOUCHER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REC_COUNT_IN','value'=>$count,'type'=>'','length'=>-1),
            array('name'=>':ID_INFO_IN','value'=>$fees,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','INCOME_VOUCHER_TB_VALIDATE',$params);

        return $result['MSG_OUT'];

    }


    function validation_cash($id,$count){

        $params =array(
            array('name'=>':VOUCHER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REC_COUNT_IN','value'=>$count,'type'=>'','length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','INCOME_VOUCHER_VALIDATE_CASH',$params);

        return $result['MSG_OUT'];

    }



    function get_details($id){

        $params =array(
            array('name'=>':VOUCHER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_DETAILS_TB_GET',$params);

        return $result;
    }


    function get_details2($id){

        $params =array(
            array('name'=>':VOUCHER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_DET_2_GET',$params);

        return $result;
    }


    function cancel($id){

        $params =array(
            array('name'=>':VOUCHER_ID_IN','value'=>$id,'type'=>'','length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','INCOME_VOUCHER_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }


    function income_voucher_tb_rep2($id,$branch){ // New_rmodel Done

        $params =array(
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name'=>':VOUCHER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
        );

        $result = $this->New_rmodel->general_get('BUDGET_REP_PKG','INCOME_VOUCHER_DETAIL_REP',$params,0);

        return $result['CUR_RES'];
    }


    /***
     * تقوم بإرجاع اجمالي المبلغ سندات القبض كل حسب نوع القبض
     */
    function income_voucher_income_type_get($entry_user,$date,$debit_account_id){

        $params =array(
            array('name'=>':ENTRY_USER_IN','value'=>$entry_user,'type'=>'','length'=>-1),
            array('name'=>':DATE_IN','value'=>$date,'type'=>'','length'=>-1),
            array('name'=>':DEBIT_ACCOUNT_ID_IN','value'=>$debit_account_id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),

        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_INCOME_TYPE_GET',$params);

        return $result;
    }
}