<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 27/12/14
 * Time: 12:43 م
 */

class Expense_bill_model extends MY_Model{
    var $PKG_NAME= "PAYMENT_PKG";
    var $TABLE_NAME= 'EXPENSE_BILL_TB';

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


    function create_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EXPENSE_BILL_DETAIL_TB_INSERT',$params);
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
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'EXPENSE_BILL_DETAIL_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }


    function get($id= 0){

        $params =array(
            array('name'=>':EXPENSE_BILL_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }


    function get_details($id= 0){

        $params =array(
            array('name'=>':EXPENSE_BILL_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'EXPENSE_BILL_DETAIL_TB_GET',$params);
        return $result;
    }

    /**
     * @return array
     *
     * return all expense data ..
     */
    function get_list($sql,$offset,$row){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'EXPENSE_BILL_TB_LIST',$params);

        return $result;
    }



    /**
     * @return array
     *
     * return count of  expense data ..
     */
    function get_count($sql){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'EXPENSE_BILL_TB_COUNT',$params);

        return $result;
    }


    function validation($expense_bill_id,$count){
        $params =array(
            array('name'=>':EXPENSE_BILL_ID_IN','value'=>$expense_bill_id,'type'=>'','length'=>-1),
            array('name'=>':REC_NO','value'=>$count,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'EXPENSE_BILL_TB_VALIDATE',$params);
        return $result['MSG_OUT'];

    }

    function validation_affter_edit($expense_bill_id){
        $params =array(
            array('name'=>':EXPENSE_BILL_ID_IN','value'=>$expense_bill_id,'type'=>'','length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'EXPENSE_BILL_TB_QEED',$params);
        return $result['MSG_OUT'];

    }

    function delete($id,$expense_bill_id){
        $params =array(
            array('name'=>':EXPENSE_BILL_ID_IN','value'=>$expense_bill_id,'type'=>'','length'=>-1),
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'EXPENSE_BILL_DETAIL_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }

}