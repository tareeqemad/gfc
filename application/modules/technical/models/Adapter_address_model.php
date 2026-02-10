<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * Adapter address: Ahmed Barakat
 * Date: 29/07/15
 * Time: 09:15 ص
 */

class Adapter_address_model extends MY_Model{

    /**
     * @return array
     *
     * return all Adapter address data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_list($sql,$offset,$row){


        $params =array(


            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':offset','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':row','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','ADAPTER_ADDRESS_LIST',$params);

        return $result;
    }


    /**
     * @param int $id
     * @return mixed
     * return one Adapter address ..
     */
    function get($id = 0){

        $params =array(
            array('name'=>':Partition_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','ADAPTER_ADDRESS_GET',$params);

        return $result;
    }

  

    /**
     * @param $data
     *
     * create new Adapter address ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ADAPTER_ADDRESS_INSERT',$params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists Adapter address ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ADAPTER_ADDRESS_UPDATE',$params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete Adapter address ..
     */
    function delete($id){

        $params =array(
            array('name'=>':ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ADAPTER_ADDRESS_DELETE',$params);

        return $result['MSG_OUT'];

    }

   
}