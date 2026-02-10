<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 18/08/14
 * Time: 12:21 م
 */

class Exp_rev_model extends MY_Model{
    var $PKG_NAME= "BUDGET_PKG";
    var $TABLE_NAME= 'BUDGET_EXP_REV_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($item_no,$mmonth,$department_no,$yyear,$branch,$exp_rev_type,$adopt=null,$adopt_emp_no=null){

        $params =array(
            array('name'=>':ITEM_NO_IN','value'=>"{$item_no}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':MMONTH_IN','value'=>"{$mmonth}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>"{$department_no}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':YYEAR_IN','value'=>"{$yyear}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>"{$exp_rev_type}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>"{$adopt}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_EMP_NO_IN','value'=>"{$adopt_emp_no}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }


    function get_list2($sql){

        $params =array(
            array('name'=>':WSQL_IN','value'=>"{$sql}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LIST2',$params);
        return $result;
    }

    function get_up_new($year, $section_no, $branch= null, $position= null ){

        $params =array(
            array('name'=>':YEAR_IN','value'=>$year,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section_no,'type'=>'','length'=>-1),
            array('name'=>':branch_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':position_IN','value'=>$position,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'BUDGET_EXP_REV_UP_TB_GET_NEW', $params);
        return $result;
    }

    function get_department_new($exp_rev_type, $year){

        $params =array(
            array('name'=>':EXP_REV_TYPE_IN','value'=>$exp_rev_type,'type'=>'','length'=>-1),
            array('name'=>':YEAR_IN','value'=>$year,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'EXP_REV_DEPARTMENT_GET_NEW', $params);
        return $result;
    }

    function get_list_new($exp_rev_type, $year, $section_no, $branch= null, $position= null){

        $params =array(
            array('name'=>':EXP_REV_TYPE_IN','value'=>$exp_rev_type,'type'=>'','length'=>-1),
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section_no,'type'=>'','length'=>-1),
            array('name'=>':branch_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':position_IN','value'=>$position,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST_NEW',$params);
        return $result;
    }

    function adopt_new($type, $year, $branch, $department, $section, $adopt){
        $params =array(
            array('name'=>':EXP_REV_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':YEAR_IN','value'=>$year,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>$department,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_ADOPT_NEW',$params);
        return $result['MSG_OUT'];
    }

    function get_total($section_no,$department_no,$yyear,$branch,$exp_rev_type,$adopt){

        $params =array(
            array('name'=>':SECTION_NO_IN','value'=>"{$section_no}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>"{$department_no}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':YYEAR_IN','value'=>"{$yyear}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>"{$exp_rev_type}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>"{$adopt}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_TOTAL',$params);
        return $result;
    }

    // اجمالي النفقات والايرادات لكل باب حسب الشروط
    function get_chp_total($year, $branch, $department_no, $type,$budget_type){

        $params =array(
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>$department_no,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>$type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BUDGET_TYPE_IN','value'=>$budget_type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_CHP_TOTAL',$params);
        return $result;
    }
    // اجمالي النفقات والايرادات لكل باب حسب الشروط
    function get_chp_bran_total($year, $branch, $department_no, $chapter,$budget_type){

        $params =array(
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>$department_no,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':CHAPTER_NO_IN','value'=>$chapter,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BUDGET_TYPE_IN','value'=>$budget_type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BUDGET_EXP_REV_GET_CHP_TOTAL',$params);
        return $result;
    }



    function get_sec_bran_total($year, $branch, $department_no, $section_no,$adopt,$budget_type){

        $params =array(
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>$department_no,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section_no,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BUDGET_TYPE_IN','value'=>$budget_type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BUDGET_EXP_REV_BRANCH_SEC_TOT',$params);
        return $result;
    }


    // اجمالي النفقات والايرادات لكل فصل حسب الشروط
    function get_sec_total($year, $branch, $department_no, $type, $chapter, $adopt,$budget_type){

        $params =array(
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>$department_no,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>$type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':CHAPTER_NO_IN','value'=>$chapter,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BUDGET_TYPE_IN','value'=>$budget_type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_SEC_TOTAL',$params);
        return $result;
    }

    // اجمالي النفقات والايرادات لكل دائرة حسب الشروط
    function get_dpt_total($year, $branch, $type, $section, $department= null){

        $params =array(
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>$type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>$department,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_DPT_TOTAL',$params);
        return $result;
    }

    // اجمالي النفقات والايرادات لكل بند حسب الشروط
    function get_itm_total($year, $branch, $department_no, $type, $section, $adopt){

        $params =array(
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>$department_no,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>$type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_ITM_TOTAL',$params);
        return $result;
    }

    // اجمالي موازنة الاعوام السابقة للفصل
    function get_history_total($section, $branch){

        $params =array(
            array('name'=>':SECTION_NO_IN','value'=>$section,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'BUDGET_HISTORY_TB_SEC_TOTAL',$params);
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

    function edit_new($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE_NEW',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':NO_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function adopt($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_ADOPT',$params);
        return $result['MSG_OUT'];
    }

    function active_ceil_value($section_no,$branch,$yyear,$exp_rev_type){
        $params =array(
            array('name'=>':SECTION_NO_IN','value'=>$section_no,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':YYEAR_IN','value'=>$yyear,'type'=>'','length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>$exp_rev_type,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'ACTIVE_CEIL_VALUE',$params);
        return $result['MSG_OUT'];
    }
}
