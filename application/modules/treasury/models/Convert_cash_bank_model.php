<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:34 ص
 */

class Convert_cash_bank_model extends MY_Model{


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

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CONVERT_CASH_BANK_TB_GET',$params);

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

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CONVERT_CASH_BANK_TB_GET_ALL',$params);

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

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CONVERT_CASH_BANK_TB_LIST',$params);

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

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CONVERT_CASH_BANK_TB_GET_COUNT',$params);

        return $result;
    }

    /**
     * @param $data
     *
     * create new convert cash ..
     */
    function create($data){
	
		{ // mkilani 202210
            $create_time= time();
            if($create_time < $this->session->userdata('cash_bank_insert_time') + 20){
                return -20;
            }
            $this->session->set_userdata('cash_bank_insert_time', $create_time);
        }
		
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TREASURY_PKG','CONVERT_CASH_BANK_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TREASURY_PKG','CONVERT_CASH_BANK_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * adopt cash bank
     */
    function adopt($id,$case){

        $params =array(
            array('name'=>':CONVERT_CASH_BANK_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CONVERT_CASH_BANK_CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TREASURY_PKG','CONVERT_CASH_BANK_UPDATE_CASE',$params);

        return $result['MSG_OUT'];

    }

    function get_bank_account_id($SEQ_id){

        

        $params =array(

            array('name'=>':SEQ_ID_IN','value'=>$SEQ_id,'type'=>'','length'=>-1),

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','CONVERT_CASH_BANK_ACCOUNT_ID',$params);

        return $result;
    }




}