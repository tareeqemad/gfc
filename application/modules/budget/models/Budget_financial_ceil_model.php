<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 28/08/16
 * Time: 11:21 ص
 */

class budget_financial_ceil_model extends MY_Model{
    var $PKG_NAME= "BUDGET_PKG";
    var $TABLE_NAME= 'BUDGET_FINANCIAL_CEIL_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id= 0){

        $params =array(
            array('name'=>':SER','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
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
    function get_list2($the_year,$sql){

        $params =array(
            array('name'=>':THE_YEAR','value'=>"{$the_year}",'type'=>'','length'=>-1),
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST2',$params);

        return $result;//$result['MSG_OUT'];
    }
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BUDGET_FINANCIAL_CEIL_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BUDGET_FINANCIAL_CEIL_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':AMENDMENT_ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function adopt($id,$case){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BUDGET_FINANCIAL_CEIL_ADOPT',$params);
        return $result['MSG_OUT'];
    }

    function ceil_value($section_no,$branch){
        $params =array(
            array('name'=>':SECTION_NO','value'=>$section_no,'type'=>'','length'=>-1),
            array('name'=>':BRANCH','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'CEIL_VALUE',$params);
        return $result['MSG_OUT'];
    }
    function active_ceil_value($section_no,$branch,$yyear,$exp_rev_type){
        $params =array(
            array('name'=>':SECTION_NO','value'=>$section_no,'type'=>'','length'=>-1),
            array('name'=>':BRANCH','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':YYEAR','value'=>$yyear,'type'=>'','length'=>-1),
            array('name'=>':EXP_REV_TYPE','value'=>$exp_rev_type,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'ACTIVE_CEIL_VALUE',$params);
        return $result['MSG_OUT'];
    }

}
