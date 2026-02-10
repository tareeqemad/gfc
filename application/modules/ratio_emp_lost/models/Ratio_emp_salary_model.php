<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 18/07/23
 * Time: 10:30 ص
 */

class Ratio_emp_salary_model extends MY_Model {
    var $PKG_NAME = "RAITO_EMP_LOST_PKG";
    var $TABLE_NAME = 'WORKS_TEAMS_SALARY';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($sql,$offset,$row){
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

    function get_list2($sql,$offset,$row){
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST2',$params);
        return $result;
    }

    function get_teams(){
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'TEAMS_GET_ALL',$params);
        return $result;
    }

    function adopt($branch ,$month ,$case){
        $params =array(
            array('name'=>':BRANCH_NO','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':THE_MONTH','value'=>$month,'type'=>'','length'=>-1),
            array('name'=>':ADOPT','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'RATIO_EMP_SALARY_ADOPT',$params);
        return $result['MSG_OUT'];
    }
}