<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * BREAKERS: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:35 AM
 */

class Breakers_model extends MY_Model{
    /**
     * @return array
     *
     * return all BREAKERSs data ..
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

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','BREAKERS_TB_GET_ALL',$params);

        return $result;
    }


    /**
     * @return array
     *
     * return all BREAKERSs data ..
     */
    function get_count($sql){

        

        $params =array(

            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','BREAKERS_TB_COUNT',$params);

        return $result;
    }



    /**
     * @return array
     *
     * return all BREAKERSs data ..
     */
    function get_list($sql,$offset,$row){

        

        $params =array(


            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':offset','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':row','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','BREAKERS_TB_LIST',$params);

        return $result;
    }


    /**
     * @param int $id
     * @return mixed
     * return one BREAKERS ..
     */
    function get($id = '0'){

        

        $params =array(
            array('name'=>':BREAKERS_ID_in','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','BREAKERS_TB_GET',$params);

        return $result;
    }

    function get_BREAKERS_info($id = 0, $email= ''){
        
        $params =array(
            array('name'=>':ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':EMAIL_in','value'=>$email,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get('QF_PKG','GET_BREAKERS_INFO',$params);
        return $result;
    }


    /**
     * @param $data
     *
     * create new BREAKERS ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','BREAKERS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists BREAKERS ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','BREAKERS_TB_UPDATE',$params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete BREAKERS ..
     */
    function delete($id){

        $params =array(
            array('name'=>':BREAKERS_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','BREAKERS_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }



}