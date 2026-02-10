<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 27/03/22
 * Time: 04:49 ص
 */
class Vision_mission_model extends MY_Model{
    var $PKG_NAME= "PLAN_PKG";
    var $TABLE_NAME= 'VISION_MISSION_TB';

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
            array('name'=>':SER','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }
    /************************************************/
    function adopt($id,$case){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,$this->TABLE_NAME.'_ADOPT',$params);;
        return $result['MSG_OUT'];

    }
/*******************************************************************/
    function get_list($sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST',$params);
        return $result;
    }
    /***************************************************************************/
    function get_objective($id= 0,$id_father=0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ID_FATHER_IN','value'=>"{$id_father}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TREE_GOAL_OBJECTIVE_TB_GET_1',$params);
        return $result;
    }
	    /***************************************************************************/
    function VISION_MISSION_TB_YEARS($from_year=0,$to_year=0){

        $params =array(
            array('name'=>':FROM_YEAR_IN','value'=>"{$from_year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':TO_YEAR_IN','value'=>"{$to_year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'VISION_MISSION_TB_YEARS',$params);
        return $result;
    }
    /****************************************************
     * @param int $id
     * @param int $id_father
     * @return mixed
     */
    /*********************************************************/
    function get_objective_($id_father=0){

        $params =array(
            array('name'=>':ID_FATHER_IN','value'=>"{$id_father}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TREE_GOAL_OBJECTIVE_TB_GET_3',$params);
        return $result;
    }
    /***************************************************************************/
    function get_child_objective($id= 0,$id_father=0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ID_FATHER_IN','value'=>"{$id_father}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TREE_GOAL_OBJECTIVE_TB_GET_2',$params);
        return $result;
    }
/*******************************************************/
    function vision_mission_tb_list(){

        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'VISION_MISSION_TB_LIST',$params);
        return $result;
    }
    /*******************************************************/
    function vision_mission_tb_last(){

        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'VISION_MISSION_TB_LAST',$params);
        return $result;
    }
	    /*******************************************************/
    function vision_mission_get_last(){

        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'VISION_MISSION_TB_GET_LAST',$params);
        return $result;
    }
    /************/
    function get_values($id= 0){

        $params =array(
            array('name'=>':PLAN_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'VALUES_TB_GET',$params);
        return $result;
    }
    function delete($id){
        $params =array(
            array('name'=>':SEQ_NO_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'PLAN_STRATGIC_DEL',$params);

        return $result['MSG_OUT'];
    }

}