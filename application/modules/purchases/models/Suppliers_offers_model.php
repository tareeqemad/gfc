<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/03/15
 * Time: 10:14 ص
 */

class suppliers_offers_model extends MY_Model{
    var $PKG_NAME= "PURCHASE_PKG";
    var $TABLE_NAME= 'SUPPLIERS_OFFERS_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id= 0){
        
        $params =array(
            array('name'=>':SUPPLIERS_OFFERS_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
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
    function get_count($sql){

        
        $params =array(
            array('name'=>':XSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('QF_PKG','GET_COUNT_TAB',$params);
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

    function delete($id){
        $params =array(
            array('name'=>':SUPPLIERS_OFFERS_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function adopt($id,$adopt){
        $params =array(
            array('name'=>':SUPPLIERS_OFFERS_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_ADOPT',$params);
        return $result['MSG_OUT'];
    }

    function get_details_all_by_SQL($sql){
        
        $params =array(
            array('name'=>':SQL_IN','value'=>"{$sql}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'SUPPLIERS_OFFERS_DET_BY_SQL',$params);
        return $result;
    }

    function getCurrVal($purchase_curr= 0,$customer_curr=0){

        $params =array(
            array('name'=>':MASTER_CURR_IN','value'=>$purchase_curr ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':CURR_ID_IN','value'=>$customer_curr ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT' ,'type'=>SQLT_CHR,'length'=>-1)
                   );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'CURRENCY_DETAILS_GET_LAST_DATE',$params);

        return $result['MSG_OUT'];
    }
    function get_lists($purchase_order_id){
        
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>"{$purchase_order_id}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }
    function get_list_by_pur($purchase_order_id){
        
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>"{$purchase_order_id}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_GET_BY_PUR',$params);
        return $result;
    }
    function award_notes($suppliers_offers_id,$award_notes,$suppliers_discount,$suppliers_discount_value){
            $params =array(
             array('name'=>':SUPPLIERS_OFFERS_ID_ID','value'=>$suppliers_offers_id ,'type'=>'','length'=>-1),
             array('name'=>':AWARD_NOTES_IN','value'=>$award_notes ,'type'=>'','length'=>-1),
             array('name'=>':SUPPLIERS_DISCOUNT_IN','value'=>$suppliers_discount ,'type'=>'','length'=>-1),
             array('name'=>':SUPPLIERS_DISCOUNT_VALUE_IN','value'=>$suppliers_discount_value ,'type'=>'','length'=>-1),
             array('name'=>':MSG_OUT','value'=>'MSG_OUT' ,'type'=>SQLT_CHR,'length'=>-1)
         );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'SUPPLIERS_OFFERS_UPDATE_AWARD',$params);

        return $result['MSG_OUT'];
    }
    //This
    function get_details_all($id= 0){
        
        $params =array(
            array('name'=>':SUPPLIERS_OFFERS_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'SUPPLIERS_OFFERS_DET_TB_GET',$params);
        return $result;
    }
}
