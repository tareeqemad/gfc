

<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/11/14
 * Time: 09:58 ص
 */

class plan_model extends MY_Model{
    var $PKG_NAME= "PLAN_PKG";
    var $TABLE_NAME= 'ACTIVITIES_PLAN_TB';
    var $TABLE_NAME2= 'STRATGIC_ACTIVITIES';

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

    function create_achive_month($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'FOLLOW_MONTH_ACHIVE_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function edit($data){

        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);

        return $result['MSG_OUT'];
    }
	   function Stratgic_edit($data){

        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE_1',$params);

        return $result['MSG_OUT'];
    }
    function delete($id){
        $params =array(
            array('name'=>':SEQ_NO_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);

        return $result['MSG_OUT'];
    }
    function delete_unit($id){
        $params =array(
            array('name'=>':SEQ_NO_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'ACTIVITIES_PLAN_UNIT_DEL',$params);

        return $result['MSG_OUT'];
    }
    function get_all(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ALL',$params);
        return $result;
    }

    function get($id= 0,$branch=null){
        
        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }
    function get_achive($id= 0){
        
        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ACHIVE_TB_GET',$params);
        return $result;
    }


    function get_project($branch=null,$year){
        
        $params =array(
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':YYEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_TB_PALN_LIST',$params);
        return $result;
    }
    function get_year(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_YEAR',$params);
        return $result;
    }

    function get_objective($id,$id_father_in,$plan){
        
        $params =array(
            array('name'=>':ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ID_FATHER_IN_IN','value'=>"{$id_father_in}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':PLAN_IN_IN','value'=>"{$plan}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TREE_GOAL_OBJECTIVE_TB_GET',$params);

        return $result;
    }
	
	    function GET_SEQ($STRATGIC_SER,$YEAR){
        
        $params =array(
            array('name'=>':STRATGIC_SER_IN','value'=>"{$STRATGIC_SER}",'type'=>SQLT_CHR,'length'=>-1),
			array('name'=>':YEAR_IN','value'=>"{$YEAR}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'STRATGIC_PROJECT_SEQ_GET',$params);
        return $result;
    }

    function get_tree_goal_t($id_father_in,$year){
        
        $params =array(
            array('name'=>':YEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ID_FATHER_IN_IN','value'=>"{$id_father_in}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TREE_GOAL_T_V_GET',$params);

        return $result;
    }
    function get_goal_t_project($id,$year){

        

        $params =array(
            array('name'=>':GOAL_T_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':YEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ACTIVITIES_PLAN_TB_GOAL_T',$params);
        return $result;
    }

    function get_goal($objective){
        
        $params =array(
            array('name'=>':OBJECTIVE_NO_IN','value'=>"{$objective}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'GOAL_OBJECTIVE_TB_GET',$params);
        return $result;
    }

    function get_project_info($id= 0,$branch=null){
        
        $params =array(
            array('name'=>':PROJECT_SERIAL_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_TB_PALN_GET',$params);
        return $result;
    }


    function get_list($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST',$params);
        return $result;
    }
	
	    function get_list_all($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST_ALL',$params);
        return $result;
    }

    function get_list_branch($sql,$offset,$row){

        
        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_BR_LIST2',$params);

        return $result;
    }
    function stratigic_list($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
	
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME2.'_LIST',$params);
		
        return $result;
    }
    function adopt($id,$adopt){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_ADOPT',$params);;

        return $result['MSG_OUT'];

    }

    function activities_plan_achive_adopt($id,$ser_paln,$status,$new_from_month){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':SER_PLAN_IN','value'=>"{$ser_paln}",'type'=>'','length'=>-1),
            array('name'=>':STATUS_IN','value'=>"{$status}",'type'=>'','length'=>-1),
            array('name'=>':NEW_FROM_MONTH_IN','value'=>"{$new_from_month}",'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACTIVITIES_PLAN_ACHIVE_ADOPT',$params);;

        return $result['MSG_OUT'];

    }

    function activities_plan_refrech_adopt($id,$ser_plan,$for_month){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':SEQ_IN','value'=>"{$ser_plan}",'type'=>'','length'=>-1),
            array('name'=>':NEW_FROM_MONTH_IN','value'=>"{$for_month}",'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

      $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACTIVITIES_PLAN_REFRECH_ADOPT',$params);;

        return $result['MSG_OUT'];



    }

    function activities_plan_achive_tb_list($for_month,$year){
        
        $params =array(
            array('name'=>':NEW_FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':YEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ACTIVITIES_PLAN_ACHIVE_TB_LIST',$params);
        return $result;
    }

    function activities_plan_follow_list($for_month,$year){
        
        $params =array(
            array('name'=>':NEW_FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':YEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ACTIVITIES_PLAN_FOLLOW_LIST',$params);

        return $result;
    }

    function activities_plan_refrech_get($year){
        
        $params =array(
            array('name'=>':YEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ACTIVITIES_PLAN_REFRECH_GET',$params);
        return $result;
    }

    function activities_plan_achive_get($for_month,$year){
        
        $params =array(
            array('name'=>':NEW_FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':YEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ACTIVITIES_PLAN_ACHIVE_GET',$params);
        return $result;
    }

    function get_list_no_tech(){
        
        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'GET_LIST_NO_TECH',$params);
        return $result;
    }
    function get_timeline($year){
        
        $params =array(

            array('name'=>':YEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ACTIVITIES_PLAN_TB_TIMELINE',$params);
        return $result;
    }
    //////////////
    function HAVE_EMPS_STRUCTURE_TB_GET(){

        $params =array(
           array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'HAVE_EMPS_STRUCTURE_TB_GET',$params);
        return $result;
}
    //////////////
    function HR_EMPS_STRUCTURE_TB_GET(){

        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'HR_EMPS_STRUCTURE_TB_GET',$params);
        return $result;
    }
}