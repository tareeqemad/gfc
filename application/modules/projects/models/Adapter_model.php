<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:35 AM
 */

class Adapter_model extends MY_Model{

    /**
     * @return array
     *
     * return all users data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_all($SQL =''){

        

        $params =array(
            array('name'=>':XSQL','value'=>$SQL,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','POWER_ADAPTER_TB_GET_ALL',$params);

        return $result;
    }


    /**
     * @return array
     *
     * return all users data ..
     */
    function get_count($sql){

        

        $params =array(

            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','POWER_ADAPTER_TB_COUNT',$params);

        return $result;
    }



    /**
     * @return array
     *
     * return all users data ..
     */
    function get_list($sql,$offset,$row){

        

        $params =array(


            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':offset','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':row','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','POWER_ADAPTER_TB_GET_LIST',$params);

        return $result;
    }


    /**
     * @param int $id
     * @return mixed
     * return one user ..
     */
    function get($id = '0'){

        

        $params =array(
            array('name'=>':USER_ID_in','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','POWER_ADAPTER_TB_GET',$params);

        return $result;
    }

    function get_user_info($id = 0, $email= ''){
        
        $params =array(
            array('name'=>':ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':EMAIL_in','value'=>$email,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get('QF_PKG','GET_USER_INFO',$params);
        return $result;
    }


    /**
     * @param $data
     *
     * create new user ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('PROJECTS_PKG','POWER_ADAPTER_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists user ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('PROJECTS_PKG','POWER_ADAPTER_TB_UPDATE',$params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete user ..
     */
    function delete($id){

        $params =array(
            array('name'=>':USER_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('PROJECTS_PKG','POWER_ADAPTER_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }

    function partition_create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','POWER_ADAPTER_PARTITION_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function partition_edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','POWER_ADAPTER_PARTITION_UPDATE',$params);
        return $result['MSG_OUT'];
    }


    function partition_delete($id){

        $params =array(
            array('name'=>':USER_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','POWER_ADAPTER_PARTITION_DELETE',$params);

        return $result['MSG_OUT'];

    }


    /**
     * @return array
     *
     * return all users data ..
     */
    function partitions_list($id){

        

        $params =array(
            array('name'=>':ADAPTER_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','POWER_ADAPTER_PARTITIONS_LIST',$params);

        return $result;
    }


    function adapter_location_get($id){

        

        $params =array(
            array('name'=>':ADAPTER_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','ADAPTER_LOCATION_GET',$params);

        return $result;
    }


}