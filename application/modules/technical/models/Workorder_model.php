<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * Holders Equipments: Ahmed Barakat
 * Date: 29/07/15
 * Time: 09:15 ص
 */

class WorkOrder_model extends MY_Model{

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

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORK_ORDER_TB_LIST',$params);

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

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORK_ORDER_TB_GET',$params);

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
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function tools_create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_TOOLS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function tools_edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_TOOLS_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }


    function works_create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORKER_ORDER_TEAM_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function works_edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORKER_ORDER_TEAM_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }


    function cars_create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_CARS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function cars_edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_CARS_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }


    function delete_tools($id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_TOOLS_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }


    function delete_works($id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORKER_ORDER_TEAM_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }

    function delete_cars($id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_CARS_TB_DELETE',$params);

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
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_TB_UPDATE',$params);


        return $result['MSG_OUT'];
    }

    function edit_permit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_TB_PERMIT',$params);


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

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_TB_DELETE',$params);

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

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORK_ORDER_TOOLS_TB_GET',$params);

        return $result;
    }


    /**
     * @return array
     *
     * return all works data ..
     */
    function team_list($id){


        $params =array(
            array('name'=>':HOLDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORKER_ORDER_TEAM_TB_GET',$params);

        return $result;
    }


    function request_team_list($id){


        $params =array(
            array('name'=>':WORK_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORK_ORDER_TB_GET_TEAM',$params);

        return $result;
    }

    /**
     * @return array
     *
     * return all works data ..
     */
    function cars_list($id){


        $params =array(
            array('name'=>':HOLDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORK_ORDER_CARS_TB_GET',$params);

        return $result;
    }

    function WORK_ORDER_TB_GET_JOB($id){


        $params =array(
            array('name'=>':WORK_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORK_ORDER_TB_GET_JOB',$params);

        return $result;
    }

    function adopt($id,$adopt){

        $params =array(
            array('name'=>':WORK_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','WORK_ORDER_TB_ADOPT',$params);

        return $result['MSG_OUT'];

    }


    function WORK_ORDER_JOB_COST_GET($id){


        $params =array(
            array('name'=>':WORK_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORK_ORDER_JOB_COST_GET',$params);

        return $result;
    }

    function WORK_ORDER_TOOL_COST_GET($id){


        $params =array(
            array('name'=>':WORK_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORK_ORDER_TOOL_COST_GET',$params);

        return $result;
    }


    function WORK_ORDER_CAR_COST_GET($id){

        $params =array(
            array('name'=>':WORK_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','WORK_ORDER_CAR_COST_GET',$params);

        return $result;
    }

}