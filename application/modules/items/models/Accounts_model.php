<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/14/14
 * Time: 9:17 AM
 */
class Accounts_model extends MY_Model{

    /**
     * @return array
     *
     * return all accounts data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function getAll(){

        

        $params =array(

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('financial_pkg','ACOUNTS_TB_GET_ALL',$params);

        return $result;
    }

    /**
     * @param int $parent
     * @return array
     * get list of accounts deepened on parent id
     */
    function getList($parent = 0,$user = null,$level  = 0){

        

        $params =array(
            array('name'=>':ACOUNT_PARENT_ID_in','value'=>"{$parent}",'type'=>'','length'=>-1),
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':Level_IN','value'=>$level,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('financial_pkg','ACOUNTS_TB_GET_LIST',$params);
        return $result;
    }

    /**
     * @param int $id
     * @return mixed
     * return one account ..
     */
    function get($id = 0){

        

        $params =array(
            array('name'=>':ACOUNT_ID_in','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('financial_pkg','ACOUNTS_TB_GET',$params);

        return $result;
    }


    /**
     * @param $data
     *
     * create new account ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('financial_pkg','ACOUNTS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    /**
     * @param $data
     *
     * create new account ..
     */
    function update_adapt($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('financial_pkg','ACOUNTS_TB_UPDATE_ADOPT',$params);
        return $result['MSG_OUT'];
    }



    /**
     * @param $data
     *
     * update exists account ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('financial_pkg','ACOUNTS_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete account ..
     */
    function delete($id){

        $params =array(
            array('name'=>':ACOUNT_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('financial_pkg','ACOUNTS_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }


}