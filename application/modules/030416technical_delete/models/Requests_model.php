<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * Requests: Ahmed Barakat
 * Date: 29/07/15
 * Time: 09:15 ص
 */

class Requests_model extends MY_Model{

    /**
     * @return array
     *
     * return all Requests data ..
     */
    function get_list($sql,$offset,$row){

        $cursor = $this->db->get_cursor();

        $params =array(


            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':offset','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':row','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TB_LIST',$params);

        return $result[(int)$cursor];
    }


    /**
     * @param int $id
     * @return mixed
     * return one Requests ..
     */
    function get($id = 0){

        $cursor = $this->db->get_cursor();

        $params =array(
            array('name'=>':HOLDER_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TB_GET',$params);

        return $result[(int)$cursor];
    }



    /**
     * @param $data
     *
     * create new Requests ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }



    /**
     * @param $data
     *
     * update exists Requests ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TB_UPDATE',$params);


        return $result['MSG_OUT'];
    }

    function change_type($id,$type){
        $params =array(
            array('name'=>':ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':TYPE_in','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TB_CHANGE_REQ_TYPE ',$params);

        return $result['MSG_OUT'];
    }

    function feadback($id,$notes,$case = 3,$team_id = null,$job_id = null){
        $params =array(
            array('name'=>':REQUEST_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ACTION_HINTS_IN','value'=>$notes,'type'=>'','length'=>-1),
            array('name'=>':REQUEST_CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':TEAM_ID_IN','value'=>$team_id,'type'=>'','length'=>-1),
            array('name'=>':JOB_ID_IN','value'=>$job_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TB_CHANGE_ACTION ',$params);

        return $result['MSG_OUT'];
    }


    /**
     * @param $id
     * delete Requests ..
     */
    function delete($id){

        $params =array(
            array('name'=>':ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }




    /**
     * @return array
     *
     * return all tools data ..
     */
    function tools_list($id){

        $cursor = $this->db->get_cursor();

        $params =array(
            array('name'=>':HOLDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TOOLS_TB_GET',$params);

        return $result[(int)$cursor];
    }

    /**
     * @return array
     *
     * return all works data ..
     */
    function team_list($id){

        $cursor = $this->db->get_cursor();

        $params =array(
            array('name'=>':HOLDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TEAM_TB_GET',$params);

        return $result[(int)$cursor];
    }





    function tools_create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TOOLS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function tools_edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TOOLS_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }


    function team_create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TEAM_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function team_edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TEAM_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }




    function delete_tools($id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TOOLS_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }

    function delete_team($id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','REQUESTS_TEAM_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }



}