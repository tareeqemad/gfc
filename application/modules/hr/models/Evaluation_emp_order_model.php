<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 10/07/16
 * Time: 09:11 ص
 */

class Evaluation_emp_order_model extends MY_Model{

    var $PKG_NAME= "HR_PKG";
    var $TABLE_NAME= 'EVAL_EMP_ORDER';
    var $TABLE_NAME_COURSES= 'EVA_EMPS_COURSES';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    
    function get($id= 0){
        
        $params =array(
            array('name'=>':EVALUATION_ORDER_SERIAL','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    function get_adopt($id= 0){
        
        $params =array(
            array('name'=>':EVALUATION_ORDER_SERIAL','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ADOPT',$params);
        return $result;
    }

    function get_archives_list($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'EVALUATION_ORDER_ARCHIVES_LIST',$params);
        return $result;
    }

    function get_admin_manager(){
        
        $params =array(
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'EVALUATION_ORDER_DEP_LIST',$params);
        return $result;
    }

    //Check if employee had evaluate or not
    function get_serial($id= 0,$type=0){
        
        $params =array(
            array('name'=>':EMP_NO','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':EVAL_FROM_TYPE','value'=>$type ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_SERIAL',$params);
        return $result;
    }
    function get_Audit_List($sql){
        
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'EVALUATION_EMP_ORDER_TB_LIST',$params);
        return $result;
    }
    function get_objection($sql){
        
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'EVAL_EMP_ORDER_GET_OBJECTION',$params);
        return $result;
    }

    function get_count_marke($branch = null){
        
        $params =array(
            array('name'=>':BRANCH','value'=>$branch ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'EVALUATION_EMP_ORDER_CALC_MARK',$params);
        return $result;
    }

    function get_not_evaluated($insql=''){
        
        $params =array(
            array('name'=>':INSQL','value'=>$insql ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'GET_EMPS_NOT_EVALUATED',$params);
        return $result;
    }

    function insert_not_evaluate(){
        $data = array();
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INSERT_NOT_EVALUATED',$params);
        return $result['MSG_OUT'];
    }

    function get_eval_user($id = NULL){
        
        $params =array(
            array('name'=>':EMP_NO','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'GET_FORM_FOR_USER',$params);
        return $result;
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
            array('name'=>':EVALUATION_ORDER_SERIAL','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function adopt($id,$excellent_note,$manager_note,$emp_transfer){
        $params =array(
            array('name'=>':EVALUATION_ORDER_SERIAL','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':EXCELLENT_EDEPENDENCY','value'=>$excellent_note,'type'=>'','length'=>-1),
            array('name'=>':MANAGER_NOTE','value'=>$manager_note,'type'=>'','length'=>-1),
            array('name'=>':EMP_TRANSFER','value'=>$emp_transfer,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_ADOPT',$params);
        return $result['MSG_OUT'];
    }

    function cancel_adopt($id){
        $params =array(
            array('name'=>':EVALUATION_ORDER_SERIAL','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_CANCEL',$params);
        return $result['MSG_OUT'];
    }

    function adopt_admin_manager($id=0, $opinion, $opinion_reason){
        $params =array(
            array('name'=>':EVALUATION_ORDER_SERIAL','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':OPINION','value'=>$opinion,'type'=>'','length'=>-1),
            array('name'=>':OPINION_REASON','value'=>$opinion_reason,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_OPINION',$params);
        return $result['MSG_OUT'];
    }

    function get_emps_list($id,$branch){
        
        $params =array(
            array('name'=>':DEGREE','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':BRANCH','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_BY_DEGREE',$params);
        return $result;
    }

    function get_courses_list($id){
        
        $params =array(
            array('name'=>':EVALUATION_ORDER_SERIAL','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME_COURSES.'_TB_LIST',$params);
        return $result;
    }

    function create_courses($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME_COURSES.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function delete_courses($id){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME_COURSES.'_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }

}