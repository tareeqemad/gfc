<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 30/08/14
 * Time: 12:38 م
 * Test: success 31/08/14
 */

class budget_chapter_model extends MY_Model{
    var $PKG_NAME= "BUDGET_PKG";
    var $TABLE_NAME= 'BUDGET_CHAPTER_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id= 0){

        $params =array(
            array('name'=>':NO','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET', $params);
        return $result;
    }

    function get_list_new($type, $year, $branch= null, $position= null){

        $params =array(
            array('name'=>':TYPE','value'=>$type ,'type'=>'','length'=>-1),
            array('name'=>':YEAR','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':branch_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':position_IN','value'=>$position,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_NEW_LIST', $params);
        return $result;
    }

    function get_all($has_history= null){


        $params =array(
            array('name'=>':HAS_HISTORY','value'=>$has_history ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ALL',$params);
        return $result;
    }

    function get_all_permission($type, $exists= 0, $department_no= 0, $year= 0 ){

        $params =array(
            array('name'=>':TYPE','value'=>$type ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':EXISTS','value'=>$exists ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':DEPARTMENT_NO','value'=>$department_no ,'type'=>'','length'=>-1),
            array('name'=>':YYEAR','value'=>$year ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_PERMISSION',$params);
        return $result;
    }


    function get_all_permission_3($type, $branch= 0, $year= 0 ){

        $params =array(
            array('name'=>':TYPE','value'=>$type ,'type'=>'','length'=>-1),
            array('name'=>':BRANCH','value'=>$branch ,'type'=>'','length'=>-1),
            array('name'=>':YYEAR','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_PERMISSION_3',$params);
        return  $result ;
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

    function delete($id){
        $params =array(
            array('name'=>':NO_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>255),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }
    function get_by_type($type){


        $params =array(
            array('name'=>':TYPE','value'=>$type ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_BY_TYPE',$params);
        return $result;
    }
}
