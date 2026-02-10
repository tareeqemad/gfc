<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 20/11/17
 * Time: 10:17 ص
 */


class plan_detail_model extends MY_Model{
    var $PKG_NAME= "PLAN_PKG";
    var $TABLE_NAME= 'DISTRBUTE_ACTIVITY_TB';
    // var $TABLE_NAME2= 'PLAN_PROJECT_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_details_all($id= 0){
        
        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }
	    function get_stratgic_projects($id){
        
        $params =array(
            array('name'=>':STRATGIC_SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'STRATGIC_PROJECT_GET',$params);
        return $result;
    }

    function create_part($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);

        return $result['MSG_OUT'];
    }
	
	function create_class_items_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PLAN_CLASS_ITEMS_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function edit_class_items_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PLAN_CLASS_ITEMS_UPDATE',$params);

        return $result['MSG_OUT'];
    }
	
    function create_achive_evaluate($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACHIVE_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

      function edit_achive_evaluate($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACHIVE_TB_UPDATE',$params);

        return $result['MSG_OUT'];
       }
       function edit_($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'S_ACTIVITIES_PLAN_TB_UPDATE',$params);

        return $result['MSG_OUT'];
       }
    function activities_plan_refrech_update($id,$for_month){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':MONTH_ACHIVE_IN','value'=>"{$for_month}",'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACTIVITIES_PLAN_REFRECH_UPDATE',$params);;
  //var_dump($result['MSG_OUT']);
        return $result['MSG_OUT'];

    }
    function edit_part($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME,  $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }
	    function delete_goals($id,$type){
        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
			array('name'=>':TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
		
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'TREE_GOAL_OBJECTIVE_TB_DELETE',$params);

        return $result['MSG_OUT'];
    }

    function create_sub_activites($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'SUB_ACTIVITY_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }
    function create_follow_dir($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FOLLOW_PLANNING_DIR_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }
	 function create_target_dir($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'TARGET_ACTIVITIES_PLAN_INSERT',$params);

        return $result['MSG_OUT'];
    }
	
    function edit_sub_activites($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'SUB_ACTIVITY_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }
	
	    function edit_follow_dir($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FOLLOW_PLANNING_DIR_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }
	
		    function edit_target_dir($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'TARGET_ACTIVITIES_PLAN_UPDATE',$params);
        return $result['MSG_OUT'];
    }
    function get_details_activites_all($plan_no,$id){
        
        $params =array(
            array('name'=>':ACTIVITIES_PLAN_SER_IN','value'=>"{$plan_no}",'type'=>'','length'=>-1),
			array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'SUB_ACTIVITY_TB_GET',$params);
        return $result;
    }
	    function get_details_activites_list($plan_no){
        
        $params =array(
            array('name'=>':ACTIVITIES_PLAN_SER_IN','value'=>"{$plan_no}",'type'=>'','length'=>-1),
	        array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'SUB_ACTIVITY_TB_GET_LIST',$params);
        return $result;
    }
	
	function get_Structure_emp($id,$branch){
        
        $params =array(
            array('name'=>':ID_IN','value'=>"{$id}",'type'=>'','length'=>-1),
			array('name'=>':branch_IN','value'=>"{$branch}",'type'=>'','length'=>-1),
	        array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'STRUCTRE_EMLOYEE_GET',$params);

        return $result;
    }
			function get_cycle_emp($id,$branch){
        
        $params =array(
            array('name'=>':ID_IN','value'=>"{$id}",'type'=>'','length'=>-1),
			array('name'=>':branch_IN','value'=>"{$branch}",'type'=>'','length'=>-1),
	        array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'CYCLE_EMLOYEE_GET',$params);

        return $result;
    }
	
		    function SUB_ACTIVITY_TB_GET_ALL($plan_no){
        
        $params =array(
            array('name'=>':ACTIVITIES_PLAN_SER_IN','value'=>"{$plan_no}",'type'=>'','length'=>-1),
	        array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'SUB_ACTIVITY_TB_GET_ALL',$params);
        return $result;
    }
	
	function SUB_ACTIVITY_TB_W_GET($id,$SEQ){
        
        $params =array(
            array('name'=>':PLAN_NO_IN','value'=>"{$id}",'type'=>'','length'=>-1),
			array('name'=>':F_SEQ_IN','value'=>"{$SEQ}",'type'=>'','length'=>-1),
		    array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'SUB_ACTIVITY_TB_W_GET',$params);

        return $result;
    }
	
	    function get_follow_exe_all($id= 0){
        
        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'FLLOW_EXE_DIR_GET',$params);
        return $result;
    }
		    function get_target_list($id= 0){
        
        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TARGET_ACTIVITIES_PLAN_TB_GET',$params);
        return $result;
    }
	
		    function FLLOW_EXE_DIR_GET_1($id= 0,$ser){
        
        $params =array(
            array('name'=>':ACTIVITIES_PLAN_SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
			array('name'=>':F_SER_IN','value'=>"{$ser}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'FLLOW_EXE_DIR_GET_1',$params);
        return $result;
    }
	
	    function get_details_follow_all($id= 0){
        
        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'FOLLOW_PLANNING_DIR_TB_GET',$params);
        return $result;
    }
	
    function delete_activites($id){
        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'SUB_ACTIVITY_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function create_objective($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'TREE_GOAL_OBJECTIVE_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit_objective($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'TREE_GOAL_OBJECTIVE_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function create_values($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'VALUES_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit_values($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'VALUES_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function create_monthes($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FOLLOW_MONTH_ACHIVE_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function delete_monthes($id){
        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FOLLOW_MONTH_ACHIVE_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function get_monthes($id,$month){

        $params =array(
            array('name'=>':SER_PLAN_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':MONTH_IN','value'=>"{$month}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'FOLLOW_MONTH_ACHIVE_GET',$params);
        return $result;
    }
	
	    function get_monthes_list($id){

        $params =array(
            array('name'=>':SER_PLAN_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'FOLLOW_MONTH_ACHIVE_LIST',$params);
        return $result;
    }
	
	    function get_class_details($id){

        $params =array(

            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PLAN_CLASS_ITEMS_GET',$params);

        return $result;
    }
	
}
