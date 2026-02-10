<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 9:31 AM
 */

class Gcc_structure_model extends MY_Model{

    /**
     * @return array
     *
     * return all gcc structure  data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function getAll(){


        $params =array(

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('setting_pkg','GCC_STRUCTURE_TB_GET_ALL',$params);

        return $result;
    }

    /**
     * @param int $parent
     * @return array
     * get list of gcc structure  deepened on parent id
     */
    function getList($parent = 0,$user = 0){


        $params =array(
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':ST_PARENT_ID_in','value'=>"{$parent}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('setting_pkg','GCC_STRUCTURE_TB_GET_LIST',$params);
        return $result;
    }

    function getList2($parent = 0,$user = 0){


        $params =array(
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':ST_PARENT_ID_in','value'=>"{$parent}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('setting_pkg','GCC_STRUCTURE_TB_PLAN_LIST',$params);
        return $result;
    }

    function get_level($st_id= 0, $case= -1, $year= 0, $exp_rev_type=0 ){
        
        $params =array(
            array('name'=>':ST_ID_IN','value'=>$st_id,'type'=>'','length'=>-1),
            array('name'=>':CASE_ID_in','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':Yyear_IN','value'=>$year,'type'=>'','length'=>-1),
            array('name'=>':Exp_Rev_Type_IN','value'=>$exp_rev_type,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get('setting_pkg','GCC_STRUCTURE_TB_GET_LEVEL',$params);
        return $result;
    }

    function get_type($type =null, $year ,$branch = null, $exp_rev_type = null){
        
        $params =array(
            array('name'=>':TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':YEAR_IN','value'=>$year,'type'=>'','length'=>-1),
            array('name'=>':branch_in','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>$exp_rev_type,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('setting_pkg','GCC_STRUCTURE_TB_GET_TYPE',$params);
        return $result;
    }

    /**
     * @param int $id
     * @return mixed
     * return one gcc_structure ..
     */
    function get($id = 0){

        $params =array(
            array('name'=>':ST_ID_in','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('setting_pkg','GCC_STRUCTURE_TB_GET',$params);

        return $result;
    }


    function getStructure($type=null){
		
        $params =array(
            array('name'=>':TYPE_IN','value'=>$type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('setting_pkg','GCC_STRUCTURE_TB_GET_BY_TYPE',$params);

        return $result;
	}

    function get_Structure_branch($type,$branch){
        
        $params =array(
            array('name'=>':TYPE_IN','value'=>$type,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCHES_IN','value'=>$branch,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('setting_pkg','GCC_STRUCTURE_TB_TYPE_BRANCHE',$params);

        return $result;
    }
    /**
     * @param $data
     *
     * create new gcc_structure ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('setting_pkg','GCC_STRUCTURE_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists gcc_structure ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('setting_pkg','GCC_STRUCTURE_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete gcc_structure ..
     */
    function delete($id){

        if(trim($id) !=''){
            $params =array(
                array('name'=>':ST_ID_in','value'=>$id,'type'=>'','length'=>-1),
                array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
            );

            $result = $this->conn->excuteProcedures('setting_pkg','GCC_STRUCTURE_TB_DELETE',$params);

            return $result['MSG_OUT'];
        } else return 0;

    }


}