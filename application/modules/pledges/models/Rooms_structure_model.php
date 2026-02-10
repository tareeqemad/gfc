<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 15/12/19
 * Time: 12:39 م
 */

class rooms_structure_model extends MY_Model{
    var $PKG_NAME= "FIXED_PKG";
    var $TABLE_NAME= 'ROOMS_STRUCTURE';

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

    function get_list($id= 0){
        
        $params =array(
            array('name'=>':ROOM_ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }

/*
    function get_to_print($emp_no= 0){
        
        $params =array(
            array('name'=>':EMP_NO','value'=>$emp_no ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_TREE',$params);
        return $result;
    }
*/
    function get_child($room_parent= -10, $is_branch_manager=0){
        
        $params =array(
            array('name'=>':ROOM_PARENT','value'=>$room_parent ,'type'=>'','length'=>-1),
            //array('name'=>':IS_BRANCH_MANAGER','value'=>$is_branch_manager ,'type'=>'','length'=>-1),
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

    function get_grandson($room_parent= 0){
        
        $params =array(
            array('name'=>':ROOM_ID','value'=>$room_parent ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GRANDSON',$params);
        return $result;
    }
/*
    function get_branch_manager($branch_no= 0){
        
        $params =array(
            array('name'=>':BRANCH','value'=>$branch_no ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'HR_EMPS_STRUCTURE_BRANCH_MNGR',$params);
        return $result;
    }
*/
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function delete($id, $no){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ROOM_ID','value'=>$no,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

/*
    function edit($ser,$room_parent){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':ROOM_PARENT','value'=>$room_parent,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }
*/
    function edit_room($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

}
