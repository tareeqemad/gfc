<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/03/15
 * Time: 10:14 ص
 */

class suppliers_offers_det_model extends MY_Model{
    var $PKG_NAME= "PURCHASE_PKG";
    var $TABLE_NAME= 'SUPPLIERS_OFFERS_DET_TB';
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    //This
    function get_details_all($id= 0){
        
        $params =array(
            array('name'=>':SUPPLIERS_OFFERS_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,  $this->TABLE_NAME.'_INSERT',$params);

        return $result['MSG_OUT'];
    }
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,  $this->TABLE_NAME.'_UPDATE',$params);
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
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'SUPPLIERS_OFFERS_DET_BY_PUR',$params);
        return $result;
    }
    function get_details_all_by_purchase_a($id= 0){
        
        $params =array(
            array('name'=>':PURCHASE_ORDER_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'SUPPLIERS_OFFERS_DET_A_BY_PUR',$params);
        return $result;
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
    function award($ser,$class_id,$approved_amount,$award_hints,$class_discount,$approved_price,$class_discount_value,$discount_value_class){
    $params =array(
        array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
        array('name'=>':CLASS_ID','value'=>$class_id,'type'=>'','length'=>-1),
        array('name'=>':APPROVED_AMOUNT','value'=>$approved_amount,'type'=>'','length'=>-1),
        array('name'=>':AWARD_HINTS','value'=>$award_hints,'type'=>'','length'=>-1),
        array('name'=>':CLASS_DISCOUNT','value'=>$class_discount,'type'=>'','length'=>-1),
        array('name'=>':APPROVED_PRICE','value'=>$approved_price,'type'=>'','length'=>-1),
        array('name'=>':CLASS_DISCOUNT_VALUE','value'=>$class_discount_value,'type'=>'','length'=>-1),
        array('name'=>':DISCOUNT_VALUE_CLASS','value'=>$discount_value_class,'type'=>'','length'=>-1),
        array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500),
    );
    $result = $this->conn->excuteProcedures($this->PKG_NAME, 'SUPPLIERS_OFFERS_DET_AWARD',$params);
    return $result['MSG_OUT'];
}
}
