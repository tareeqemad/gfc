<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 25/05/16
 * Time: 12:19 م
 */

class evaluations_emps_structure_model extends MY_Model{
    var $PKG_NAME= "HR_PKG";
    var $TABLE_NAME= 'EVA_EMPS_STRUCTURE_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id= 0){
        
        $params =array(
            array('name'=>':SER','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    function get_to_print($emp_no= 0){
        
        $params =array(
            array('name'=>':EMP_NO','value'=>$emp_no ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_TREE',$params);
        return $result;
    }

    function get_child($manager_no= -10, $is_branch_manager=0){
        
        $params =array(
            array('name'=>':MANAGER_NO','value'=>$manager_no ,'type'=>'','length'=>-1),
            array('name'=>':IS_BRANCH_MANAGER','value'=>$is_branch_manager ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_CHILD',$params);
        return $result;
    }

    function get_brother($employee_no= 0){
        
        $params =array(
            array('name'=>':EMPLOYEE_NO','value'=>$employee_no ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_BROTHER',$params);
        return $result;
    }

    function get_grandson($manager_no= 0){
        
        $params =array(
            array('name'=>':EMPLOYEE_NO','value'=>$manager_no ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GRANDSON',$params);
        return $result;
    }

    function get_branch_manager($branch_no= 0){
        
        $params =array(
            array('name'=>':BRANCH','value'=>$branch_no ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'EVA_EMPS_STRUCTURE_BRANCH_MNGR',$params);
        return $result;
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }


    function edit($ser,$manager_no){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':MANAGER_NO','value'=>$manager_no,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function edit_emp($ser,$job_id,$supervision,$alternative_manager_no){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':JOB_ID','value'=>$job_id,'type'=>'','length'=>-1),
            array('name'=>':SUPERVISION','value'=>$supervision,'type'=>'','length'=>-1),
            array('name'=>':ALTERNATIVE_MANAGER_NO','value'=>$alternative_manager_no,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UP_EMP',$params);
        return $result['MSG_OUT'];
    }
}
