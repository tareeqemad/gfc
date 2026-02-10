<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * history: Ahmed Barakat
 * Date: 9/22/14
 * Time: 11:45 AM
 */

class History_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($YEAR,$ITEM  ,$BRANCH){

        $params =array(
            array('name'=>':ITEM_NO_IN','value'=>$ITEM ,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$BRANCH ,'type'=>'','length'=>-1),
            array('name'=>':YYEAR_IN','value'=>$YEAR ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('BUDGET_PKG','BUDGET_HISTORY_TB_GET', $params);
        return $result;
    }

    function get_list($ITEM  ,$BRANCH){

        $params =array(
            array('name'=>':ITEM_NO_IN','value'=>$ITEM ,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$BRANCH ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('BUDGET_PKG','BUDGET_HISTORY_TB_GET_LIST', $params);
        return $result;
    }


    /**
     * @param $data
     *
     * create new history ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('BUDGET_PKG','BUDGET_HISTORY_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists history ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('BUDGET_PKG','BUDGET_HISTORY_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete history ..
     */
    function delete($year,$item_no ,$branch){

        $params =array(
            array('name'=>':ITEM_NO_IN','value'=>$item_no,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':YYEAR_IN','value'=>$year,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures('BUDGET_PKG','BUDGET_HISTORY_TB_DELETE',$params);
        return $result['MSG_OUT'];

    }


}