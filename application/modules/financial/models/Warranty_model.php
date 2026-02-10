<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 27/12/14
 * Time: 12:43 م
 */

class Warranty_model extends MY_Model{
    var $PKG_NAME= "FINANCIAL_PKG";
    var $TABLE_NAME= 'BAIL_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
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


    function get($id= 0){

        $params =array(
            array('name'=>':BAIL_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }


    /**
     * @return array
     *
     * return all WARRANTY data ..
     */
    function get_list($sql,$offset,$row){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BAIL_TB_LIST',$params);

        return $result;
    }

    /**
     * @return array
     *
     * return count of  WARRANTY data ..
     */
    function get_count($sql){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BAIL_TB_COUNT',$params);

        return $result;
    }

    function delete($id,$WARRANTY_bill_id){
        $params =array(
            array('name'=>':BAIL_ID_IN','value'=>$WARRANTY_bill_id,'type'=>'','length'=>-1),
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BAIL_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function return_bail($id){
        $params =array(
            array('name'=>':BAIL_ID_IN','value'=>$id,'type'=>'','length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BAIL_TB_RETURN',$params);
        return $result['MSG_OUT'];
    }


    function cash_bail($id,$bank_account , $income_account){
        $params =array(
            array('name'=>':BAIL_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':bank_account_IN','value'=>$bank_account,'type'=>'','length'=>-1),
            array('name'=>':income_account_IN','value'=>$income_account,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BAIL_TB_CASH',$params);
        return $result['MSG_OUT'];
    }

    //BAIL_EXPAND_DET_TB_INSERT
    function create_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BAIL_EXPAND_DET_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    function get_details($id= 0){

        $params =array(
            array('name'=>':BAIL_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'BAIL_EXPAND_DET_TB_GET',$params);
        return $result;
    }


}