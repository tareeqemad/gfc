<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/11/14
 * Time: 12:31 م
 */

class Exp_rev_update_model extends MY_Model{
    var $PKG_NAME= "BUDGET_PKG";
    var $TABLE_NAME= 'BUDGET_EXP_REV_UP_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($year, $branch, $department= null, $chapter, $exp_rev_type, $adopt){

        $params =array(
            array('name'=>':YYEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>"{$department}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':CHAPTER_NO_IN','value'=>"{$chapter}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>"{$exp_rev_type}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ADOPT_IN','value'=>"{$adopt}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }

    function get_chapter($year, $branch, $department= null, $exp_rev_type){

        $params =array(
            array('name'=>':YEAR_IN','value'=>"{$year}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':DEPARTMENT_NO_IN','value'=>"{$department}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':EXP_REV_TYPE_IN','value'=>"{$exp_rev_type}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_CHP',$params);
        return $result;
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':NO_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

}
