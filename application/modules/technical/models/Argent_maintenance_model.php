<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * Holders Equipments: Ahmed Barakat
 * Date: 29/07/15
 * Time: 09:15 ص
 */

class Argent_Maintenance_model extends MY_Model{

    /**
     * @return array
     *
     * return all Holders Equipments data ..
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

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','ARGENT_MAINTENANCE_TB_LIST',$params);

        return $result;
    }


    /**
     * @param int $id
     * @return mixed
     * return one Holders Equipments ..
     */
    function get($id = 0){


        $params =array(
            array('name'=>':HOLDER_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','ARGENT_MAINTENANCE_TB_GET',$params);

        return $result;
    }

  

    /**
     * @param $data
     *
     * create new Holders Equipments ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ARGENT_MAINTENANCE_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function tools_create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ARGENT_MAINTENANCE_TOOL_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function tools_edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ARGENT_MAINTENANCE_TOOL_UPDATE',$params);
        return $result['MSG_OUT'];
    }


    function works_create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ARGENT_MAINTENANCE_WORK_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function works_edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ARGENT_MAINTENANCE_WORK_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete_tools($id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ARGENT_MAINTENANCE_TOOL_DELETE',$params);

        return $result['MSG_OUT'];

    }


    function delete_works($id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ARGENT_MAINTENANCE_WORK_DELETE',$params);

        return $result['MSG_OUT'];

    }

    /**
     * @param $data
     *
     * update exists Holders Equipments ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ARGENT_MAINTENANCE_TB_UPDATE',$params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete Holders Equipments ..
     */
    function delete($id){

        $params =array(
            array('name'=>':ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ARGENT_MAINTENANCE_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }

    /**
     * @return array
     *
     * return all tools data ..
     */
    function tools_list($id){

        $params =array(
            array('name'=>':HOLDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','ARGENT_MAINTENANCE_TOOL_GET',$params);

        return $result;
    }

    /**
     * @return array
     *
     * return all works data ..
     */
    function works_list($id){

        $params =array(
            array('name'=>':HOLDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','ARGENT_MAINTENANCE_WORK_GET',$params);

        return $result;
    }


}