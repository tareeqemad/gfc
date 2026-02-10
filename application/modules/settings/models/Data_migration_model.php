<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/02/19
 * Time: 09:27 ص
 */
class data_migration_model extends MY_Model{
    var $PKG_NAME= "DATA_MIGRATION";
   // var $TABLE_NAME= 'CURRENCY_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function stores_adjustment_tb_transf( $store_id_in ,$year_in ,$notes_in ){
       $params =array(
            array('name'=>':STORE_ID','value'=>$store_id_in ,'type'=>'','length'=>-1),
            array('name'=>':YEAR','value'=>$year_in ,'type'=>'','length'=>-1),
            array('name'=>':NOTES','value'=>$notes_in ,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'STORES_ADJUSTMENT_TB_TRANSF', $params);
        return $result['MSG_OUT'];
    }

    function stores_adjustment_tb_re_transf( $store_id_in ,$year_in ,$notes_in ){
        $params =array(
            array('name'=>':STORE_ID','value'=>$store_id_in ,'type'=>'','length'=>-1),
            array('name'=>':YEAR','value'=>$year_in ,'type'=>'','length'=>-1),
            array('name'=>':NOTES','value'=>$notes_in ,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'STORES_ADJUSTMENT_TB_RE_TRANSF', $params);
        return $result['MSG_OUT'];
    }
    function update_open_price( ){
        $params =array(
           array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'UPDATE_OPEN_PRICE', $params);
        return $result['MSG_OUT'];
    }

    function update_open_price_class($class_id ){
        $params =array(
            array('name'=>':CLASS_ID','value'=>$class_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'UPDATE_OPEN_PRICE_CLASS', $params);
        return $result['MSG_OUT'];
    }
    function update_class_purchasing($class_id,$class_purchasing){
        $params =array(
            array('name'=>':CLASS_ID','value'=>$class_id,'type'=>'','length'=>-1),
            array('name'=>':CLASS_PURCHASING','value'=>$class_purchasing,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'UPDATE_CLASS_PURCHASEING', $params);
        return $result['MSG_OUT'];
    }
    function update_class_used_percent($class_id,$used_percent){
        $params =array(
            array('name'=>':CLASS_ID','value'=>$class_id,'type'=>'','length'=>-1),
            array('name'=>':USED_PERCENT','value'=>$used_percent,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'UPDATE_CLASS_USED_PERCENT', $params);
        return $result['MSG_OUT'];
    }
    function update_donation_balance(){
        $params =array(

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'UPDATE_DONATION_BALANCE', $params);
        return $result['MSG_OUT'];
    }
    function update_order_detail_balance(){
        $params =array(

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'UPDATE_ORDER_DETAIL_BALANCE', $params);
        return $result['MSG_OUT'];
    }

}