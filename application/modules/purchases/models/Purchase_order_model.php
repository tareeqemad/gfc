<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 18/03/15
 * Time: 11:00 ص
 */

class purchase_order_model extends MY_Model{
    var $PKG_NAME= "PURCHASE_PKG";
    var $TABLE_NAME= 'PURCHASE_ORDER_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id= 0){
        
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
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

    function edit_quote($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UP_QUOTE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function adopt($id, $case, $note, $purchase_type, $curr_id, $section_no, $committee_envelopes, $committee_award){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':NOTE_IN','value'=>$note,'type'=>'','length'=>-1),
            array('name'=>':PURCHASE_TYPE_IN','value'=>$purchase_type,'type'=>'','length'=>-1),
            array('name'=>':QUOTE_CURR_ID_IN','value'=>$curr_id,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$section_no,'type'=>'','length'=>-1),
            array('name'=>':COMMITTEE_ENVELOPES_IN','value'=>$committee_envelopes,'type'=>'','length'=>-1),
            array('name'=>':COMMITTEE_AWARD_IN','value'=>$committee_award,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_ADOPT',$params);
//var_dump($result['MSG_OUT']);
        return $result['MSG_OUT'];
    }

    function adopt_reversion($id, $note, $case){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NOTE_IN','value'=>$note,'type'=>'','length'=>-1),
            array('name'=>':CASE_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_ADOPT_REVERSION',$params);
        return $result['MSG_OUT'];
    }

    function quote_case($id, $case, $note){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':NOTE_IN','value'=>$note,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_QUOTE_CASE',$params);
        return $result['MSG_OUT'];
    }

    function do_order($id){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SUPPLIERS_OFFERS_LOOP_NO_DELAY',$params);
        return $result['MSG_OUT'];
    }
    function do_order_items($id){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SUPPLIERS_OFFERS_NO_DELAY_ITEM',$params);
    //    echo "dddd".$result['MSG_OUT'] ;
        return $result['MSG_OUT'];
    }

/*
    function cmt_case($id, $case){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_CMT_CASE',$params);
        return $result['MSG_OUT'];
    }
*/

    function committee_adopt($id, $case){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_COMMITTEE_ADOPT',$params);
        return $result['MSG_OUT'];
    }

    function get_section_data($branch=0, $section= 0){ // New_rmodel Done

        $params =array(
            array('name'=>':BRANCH_IN','value'=>$branch ,'type'=>'','length'=>-1),
            array('name'=>':CHAPTER_NO_IN','value'=>null ,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO','value'=>$section ,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID_IN','value'=>null ,'type'=>'','length'=>-1),
            array('name'=>':FROM_DATE','value'=>null ,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>null ,'type'=>'','length'=>-1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            //array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('MV_PKG', 'BUDGET_EXP_REV_UP_TB_BALANCE',$params,0);
        return $result['CUR_RES'];
    }
    function get_emails($id){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
             array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>1000)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_GET_EMAILS',$params);
        return $result['MSG_OUT'];
    }
    function merge_purchase_order($rec){
        $params =array(
            array('name'=>':REC_IN','value'=>$rec,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_TB_MERGE',$params);
        return $result['MSG_OUT'];
    }

    function distribute_purchase_order($id, $rec){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REC_IN','value'=>$rec,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_TB_DISTRIBUTE',$params);
        return $result['MSG_OUT'];
    }

    function budget_balance_no_branch($branch=null,$chapter=null, $section= null,$account_id=null,$from_date=null,$to_date=null,$purchase_order_id){ // New_rmodel Done

        $params =array(
            array('name'=>':BRANCH_IN','value'=>$branch ,'type'=>'','length'=>-1),
            array('name'=>':CHAPTER_NO_IN','value'=>$chapter ,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO','value'=>$section ,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID_IN','value'=>$account_id ,'type'=>'','length'=>-1),
            array('name'=>':FROM_DATE','value'=>$from_date ,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>$to_date ,'type'=>'','length'=>-1),
            array('name'=>':PURCHASE_ORDER_ID','value'=>$purchase_order_id ,'type'=>'','length'=>-1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            //array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'BUDGET_BALANCE_NO_BRANCH',$params,0);
        return $result['CUR_RES'];
    }
    function add_advertisement($id, $adver_no,$serial){
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADVER_NO_IN','value'=>$adver_no,'type'=>'','length'=>-1),
            array('name'=>':SERIAL_IN','value'=>$serial,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'PURCHASE_ORDER_ADVERTISEMENT',$params);
        return $result['MSG_OUT'];
    }
    function get_priv(){
        
        $params =array(
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'menu_adopt_priv',$params);
        return $result;
    }

}
