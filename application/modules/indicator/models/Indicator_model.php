<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/06/18
 * Time: 11:56 ص
 */


class indicator_model extends MY_Model{
    var $PKG_NAME= "INDECATOR_PKG";
    var $TABLE_NAME= 'INDECATOR_DATA_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function indecator_data_tb_get($indecatior_id,$for_month,$to_month){

        $params =array(
            array('name'=>':GOAL_T_IN','value'=>"{$indecatior_id}",'type'=>'','length'=>-1),
            array('name'=>':YEAR_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':YEAR_IN','value'=>"{$to_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_GET',$params);
        return $result;
    }
    function indecator_info_tb_get($indecatior_id,$for_month,$entry_way){

        $params =array(
            array('name'=>':SECTOR_IN','value'=>"{$indecatior_id}",'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ENTERY_TARGET_WAY_IN','value'=>"{$entry_way}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATOR_INFO_TB_GET',$params);
        return $result;
    }


    function indecator_info_tb_branch_get($indecatior_id,$class,$for_month,$branch,$adopt,$effect,$second_class){


        $params =array(
            array('name'=>':SECTOR_IN','value'=>"{$indecatior_id}",'type'=>'','length'=>-1),
            array('name'=>':CLASS_IN','value'=>"{$class}",'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>"{$adopt}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EFFECT_IN','value'=>"{$effect}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':SECOND_CLASS_IN','value'=>"{$second_class}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATOR_INFO_TB_BRANCH_GET',$params);
        return $result;
    }

    /******************************************************************************************************************************************/

    function INDECATOR_INFO_TB_INFOO_GET($indecatior_id,$class,$for_month,$branch,$adopt,$effect,$second_class){

        $params =array(
            array('name'=>':SECTOR_IN','value'=>"{$indecatior_id}",'type'=>'','length'=>-1),
            array('name'=>':CLASS_IN','value'=>"{$class}",'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>"{$adopt}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EFFECT_IN','value'=>"{$effect}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':SECOND_CLASS_IN','value'=>"{$second_class}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATOR_INFO_TB_INFOO_GET',$params);
        return $result;
    }
    /*------------------------------------------------------------------------------------------------------------------------------------------------*/
    function indecator_info_gedco_rep_get($indecatior_id,$class,$for_month,$branch,$adopt,$effect,$second_class){

        $params =array(
            array('name'=>':SECTOR_IN','value'=>"{$indecatior_id}",'type'=>'','length'=>-1),
            array('name'=>':CLASS_IN','value'=>"{$class}",'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>"{$adopt}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EFFECT_IN','value'=>"{$effect}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':SECOND_CLASS_IN','value'=>"{$second_class}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATOR_INFO_GEDCO_REP_GET',$params);
        return $result;
    }
    /*******************************************************************************************************************************************************/
    function create_target_branch($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'INDECATOR_TARGET_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function edit_target_branch($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'INDECATOR_TARGET_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    function adopt($sector,$from_month,$entry_way,$adopt,$user){

        $params =array(
            array('name'=>':SECTOR_IN','value'=>$sector,'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH_IN','value'=>$from_month,'type'=>'','length'=>-1),
            array('name'=>':ENTERY_TARGET_WAY_IN','value'=>$entry_way,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':USER_IN','value'=>$user,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INDECATOR_TARGET_TB_ADOPT',$params);;

        return $result['MSG_OUT'];

    }

    function indecator_target_tb_is_adopt($sector,$for_month,$entry_way){

        $params =array(
            array('name'=>':SECTOR_IN','value'=>"{$sector}",'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ENTERY_TARGET_WAY_IN','value'=>"{$entry_way}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATOR_TARGET_TB_IS_ADOPT',$params);
        return $result;
    }

    function get_list_indicatior($sql,$offset,$row){


        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATOR_DATA_TB_LIST',$params);

        return $result;
    }
    function indecator_info($TB_NO,$ACCOUNT_ID){

        $params =array(
            array('name'=>':GOAL_T_IN','value'=>"{$TB_NO}",'type'=>'','length'=>-1),
            array('name'=>':YEAR_IN','value'=>"{$ACCOUNT_ID}",'type'=>'','length'=>-1),

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'CONSTANT_DET_TB_ACCOUNT_GET',$params);
        return $result;
    }
    /********************************************************/
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INDECATOR_INFO_TB_INSERT_1',$params);

        return $result['MSG_OUT'];
    }
    /***********************************************************/
    function get_indecator($id= 0){

        $params =array(
            array('name'=>':INDECATOR_SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATOR_INFO_TB_GET_1',$params);
        return $result;
    }
    /***********************************************************/
    function get_indecator_calc(){

        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATIORS_GET',$params);
        return $result;
    }

    /***************************************************************/
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INDECATOR_INFO_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }
    /************************************************/
    function adopt_info($id,$case){

        $params =array(
            array('name'=>':INDECATOR_SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INDECATOR_INFO_TB_ADOPT',$params);;
        return $result['MSG_OUT'];

    }
    /************************************************/
    function flag_info($id,$case){

        $params =array(
            array('name'=>':INDECATOR_SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':INDECATOR_FLAG','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INDECATOR_INFO_TB_FLAG',$params);;
        return $result['MSG_OUT'];

    }
    /********************************************************/
    function get_tahseel($for_month)
    {


        $params =array(
             array('name'=>':FOR_MONTH_IN','value'=>$for_month,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'CALC_TAHSEL_TARGET',$params);
        return $result;
    }
    /************************************************************************************/
    function create_target_tahseel($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'TAHSEL_VALUES_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function edit_target_tahseel($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'TAHSEL_VALUES_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }
    /**********************************************************************************/
    function get_list_tahseel($sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TAHSEL_VALUES_TB_GET_LIST',$params);

        return $result;
    }

    function update_status_info($id){

        $params =array(
            array('name'=>':INDECATOR_SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INDECATOR_INFO_TB_EFFECT',$params);;
        return $result['MSG_OUT'];

    }

    function get_sectors($id,$id_father_in){

        $params =array(
            array('name'=>':ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ID_FATHER_IN_IN','value'=>"{$id_father_in}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->conn->excuteProcedures($this->PKG_NAME,'TREE_SECTORS_INDECATIORS_GET',$params);

        return $result;
    }

    function indecator_data_manual($indecatior_id,$for_month,$entry_way,$branch){

        $params =array(
            array('name'=>':SECTOR_IN','value'=>"{$indecatior_id}",'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ENTERY_VALUE_WAY_IN','value'=>"{$entry_way}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATOR_DATA_MANUAL',$params);
        return $result;
    }
    /*********************/
    function indecator_data_tb_is_adopt($sector,$for_month,$entry_way,$branch){

        $params =array(
            array('name'=>':SECTOR_IN','value'=>"{$sector}",'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH_IN','value'=>"{$for_month}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ENTERY_VALUE_WAY_IN','value'=>"{$entry_way}",'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'INDECATOR_DATA_TB_IS_ADOPT',$params);
        return $result;
    }
    /****************/

    function create_data_branch($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'INDECATOR_DATA_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function edit_data_branch($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'INDECATOR_DATA_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    function adopt_data($sector,$from_month,$entry_way,$adopt,$user,$branch){

        $params =array(
            array('name'=>':SECTOR_IN','value'=>$sector,'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH_IN','value'=>$from_month,'type'=>'','length'=>-1),
            array('name'=>':ENTERY_TARGET_WAY_IN','value'=>$entry_way,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':USER_IN','value'=>$user,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'INDECATOR_DATA_TB_ADOPT',$params);

        return $result['MSG_OUT'];

    }

    /***********************************/
    function Refreash(){
       $params =array(
           array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'indecator_info_calc_rep',$params);

        return $result['MSG_OUT'];
    }
}
