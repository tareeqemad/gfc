<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:34 ص
 */

class Convert_cash_model extends MY_Model{


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
            array('name'=>':CONVERT_CASH_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CONVERT_CASH_TB_GET',$params);

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

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CONVERT_CASH_TB_GET_LIST',$params);

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

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CONVERT_CASH_TB_GET_COUNT',$params);

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
        $result = $this->conn->excuteProcedures('TREASURY_PKG','CONVERT_CASH_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    /**
     * @return array
     *
     * return all cashs data ..
     */
    function get_debit_sum($user,$id,$date,$case = 2,$type=null){

        $params =array(
            array('name'=>':USER_ID_IN','value'=>$user,'type'=>'','length'=>-1),
            array('name'=>':DEBIT_ACCOUNT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':VOUCHER_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':VOUCHER_DATE_IN','value'=>$date,'type'=>'','length'=>-1),
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


    function get_checks_list($debit_account_id,$entry_user,$voucher_date){

        $params =array(
            array('name'=>':DEBIT_ACCOUNT_ID_IN','value'=>$debit_account_id,'type'=>'','length'=>-1),
            array('name'=>':ENTRY_USER_IN','value'=>$entry_user,'type'=>'','length'=>-1),
            array('name'=>':VOUCHER_DATE_IN','value'=>$voucher_date,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','INCOME_VOUCHER_CHECKS_LIST',$params);

        return $result;
    }

    /**
     * @param $id
     * adopt cash
     */
    function adopt($id,$case){

        $params =array(
            array('name'=>':ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CONVERT_CASH_CASE_in','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','CONVERT_CASH_TB_UPDATE_CASE',$params);

        return $result['MSG_OUT'];

    }

    /**
     * @param $id
     * delete cash  ..
     */
    function delete($id){

        $params =array(
            array('name'=>':CONVERT_CASH_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','CONVERT_CASH_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }



    function cancel($id){

        $params =array(
            array('name'=>':CONVERT_CASH_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','CONVERT_CASH_TB_CANCEL',$params);

        return $result['MSG_OUT'];

    }

}