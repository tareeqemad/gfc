<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 15/09/15
 * Time: 10:17 ص
 */

class exp_rev_side_adopt_model extends MY_Model{
    var $PKG_NAME= "BUDGET_PKG";
    //var $TABLE_NAME= 'BUDGET_EXP_REV_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_sections(){

        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'BUDGET_SECTION_TB_SIDE_GET', $params);
        return $result;
    }

    function get_list($section,$year,$branch){

        $params =array(
            array('name'=>':SECTION_NO_IN','value'=>$section,'type'=>'','length'=>-1),
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'BUDGET_EXP_REV_TB_SIDE_GET', $params);
        return $result;
    }

    function adopt($item,$year,$branch,$adopt){
        $params =array(
            array('name'=>':ITEM_NO_IN','value'=>$item,'type'=>'','length'=>-1),
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BUDGET_EXP_REV_TB_SIDE_ADOPT',$params);
        return $result['MSG_OUT'];
    }

}