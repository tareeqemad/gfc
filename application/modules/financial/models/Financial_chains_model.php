<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/11/14
 * Time: 08:29 ص
 */

class Financial_chains_model extends MY_Model{
    var $PKG_NAME= "FINANCIAL_PKG";
    var $TABLE_NAME= 'FINANCIAL_CHAINS_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id= 0){

        $params =array(
            array('name'=>':FINANCIAL_CHAINS_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET', $params);
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

    function get_details_all($id){

        $params =array(
            array('name'=>':Id_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'FINANCIAL_CHAIN_DET_TB_GET_ALL',$params);
        return $result;
    }




    /**
     * @return array
     *
     * return all chains data ..
     */
    function get_list($sql,$offset,$row,$orderby){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':ORDERBY','value'=>$orderby,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('FINANCIAL_PKG','FINANCIAL_CHAIN_GET_LIST',$params);

        return $result;
    }



    /**
     * @return array
     *
     * return all chains data ..
     */
    function get_count($sql){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('FINANCIAL_PKG','FINANCIAL_CHAIN_TB_GET_COUNT',$params);

        return $result;
    }

    function get_balance($account_id){
        $params =array(

            array('name'=>':ACOUNT_ID_IN','value'=>"{$account_id}",'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->conn->excuteProcedures('FINANCIAL_PKG','ACCOUNT_ID_Balance',$params);
        return $result['MSG_OUT'];
    }




    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function create_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FINANCIAL_CHAINS_DET_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function edit_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'FINANCIAL_CHAINS_DET_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':FINANCIAL_CHAINS_SEQ_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FINANCIAL_CHAINS_DET_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * adopt cash
     */
    function adopt($id,$case){

        $params =array(
            array('name'=>':FINANCIAL_CHAINS_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':FINANCIAL_CHAINS_CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('FINANCIAL_PKG','FINANCIAL_CHAINS_TB_UP_CASE',$params);

        return $result['MSG_OUT'];

    }

    function Test($id){

        $params =array(
            array('name'=>':C1','value'=>$id,'type'=>SQLT_CHR,'length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('TEEST','ARRTYPE_TEST',$params);

        return $result['MSG_OUT'];

    }



    function validation($financial_chains_id,$count){
        $params =array(
            array('name'=>':FINANCIAL_CHAINS_ID_IN','value'=>$financial_chains_id,'type'=>'','length'=>-1),
            array('name'=>':REC_NO','value'=>$count,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures('FINANCIAL_PKG','FINANCIAL_CHAIN_DET_VALIDATE',$params);
        return $result['MSG_OUT'];

    }

    function copy_chain($id){
        $params =array(
            array('name'=>':FINANCIAL_CHAINS_ID_IN','value'=>$id,'type'=>'','length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('FINANCIAL_PKG','FINANCIAL_CHAINS_COPY',$params);

        return $result['MSG_OUT'];
    }


    function currency_difference($account_id,$to_account_id,$date){
        $params =array(
            array('name'=>':ACCOUNT_ID_IN','value'=>$account_id,'type'=>'','length'=>-1),
            array('name'=>':TO_ACCOUNT_ID_IN','value'=>$to_account_id,'type'=>'','length'=>-1),
            array('name'=>':DATE_IN','value'=>$date,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('MV_PKG','CURRENCY_DIFFERENCE_ALL',$params);

        return $result['MSG_OUT'];
    }

}

