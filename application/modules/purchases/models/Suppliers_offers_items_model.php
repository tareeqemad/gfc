<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/08/15
 * Time: 10:02 ص
 */

class suppliers_offers_items_model extends MY_Model{
    var $PKG_NAME= "PURCHASE_PKG";
    var $TABLE_NAME= 'SUPPLIERS_OFFERS_ITEMS_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_details_all($id= 0){
        
        $params =array(
            array('name'=>':SUPPLIERS_OFFERS_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'SUPPLIERS_OFFERS_ITEMS_GET',$params);
        return $result;
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,  'SUPPLIERS_OFFERS_ITEMS_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'SUPPLIERS_OFFERS_ITEMS_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function get_details_all_by_purchase($id= 0){
        
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'SUPPLIERS_OFFERS_ITEMS_BY_PUR',$params);
        return $result;
    }
    function get_items_all_by_purchase($id= 0){
        
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'SUPPLIERS_OFFERS_ITEMS_A_B_PUR',$params);
        return $result;
    }
    function get_details_all_by_SQL($sql){
        
        $params =array(
            array('name'=>':SQL_IN','value'=>"{$sql}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'SUPPLIERS_OFFERS_ITEMS_BY_SQL',$params);
        return $result;
    }
    function award($ser,$approved_amount,$award_hints,$class_discount,$approved_price,$class_discount_value){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':APPROVED_AMOUNT','value'=>$approved_amount,'type'=>'','length'=>-1),
            array('name'=>':AWARD_HINTS','value'=>$award_hints,'type'=>'','length'=>-1),
            array('name'=>':CLASS_DISCOUNT','value'=>$class_discount,'type'=>'','length'=>-1),
            array('name'=>':APPROVED_PRICE','value'=>$approved_price,'type'=>'','length'=>-1),
            array('name'=>':CLASS_DISCOUNT_VLAUE','value'=>$class_discount_value,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SUPPLIERS_OFFERS_ITEMS_AWARD',$params);
        return $result['MSG_OUT'];
    }


}
