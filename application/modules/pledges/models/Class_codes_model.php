<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 13/05/15
 * Time: 11:15 ص
 */

class Class_codes_model extends MY_Model{
    var $PKG_NAME= "FIXED_PKG";
    var $TABLE_NAME= 'CLASS_CODES_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id= 0){
        
        $params =array(
            array('name'=>':BAECODE_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    function get_list($sql= ""){
        
        $params =array(
            array('name'=>':SQL_IN','value'=>$sql ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }
    function set_printed($sql= ""){
        
        $params =array(
            array('name'=>':SQL_IN','value'=>$sql ,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_IS_PRINTED',$params);
        return $result['MSG_OUT'];
    }

    /*
     *
     *  function get_list($id= 0){
        
        $params =array(
            array('name'=>':RECEIPT_CLASS_INPUT_ID','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }
     */

    function print_case($id,$case,$class_id=null,$store_id=null){
        $params =array(
            array('name'=>':RECEIPT_CLASS_INPUT_ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CASE','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':CLASS_ID_IN','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>':STORE_ID_IN','value'=>$store_id ,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_CODE_CASE',$params);
        return $result['MSG_OUT'];
    }

    function get_barcode($id=0,$class_id=null,$store_id=null){ // New_rmodel Done

        $params =array(
            array('name'=>':RECEIPT_CLASS_INPUT_ID_IN','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':CLASS_ID_IN','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>':STORE_ID_IN','value'=>$store_id ,'type'=>'','length'=>-1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            //array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('FIXED_REP_PKG', 'CLASS_CODES_TB_REP',$params,0);
        return $result['CUR_RES'];
    }

    function get_barcode_rep($id=null,$class_id=null,$store_id=null,$class_code_ser=null,$code_case=null){ // New_rmodel Done

       // die ($class_id."hhhh");
        $params =array(
            array('name'=>':RECEIPT_CLASS_INPUT_ID_IN','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':CLASS_ID_IN','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>':STORE_ID_IN','value'=>$store_id ,'type'=>'','length'=>-1),
            array('name'=>':CLASS_CODE_SER_IN','value'=>$class_code_ser ,'type'=>'','length'=>-1),
            array('name'=>':CODE_CASE_IN','value'=>$code_case ,'type'=>'','length'=>-1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            //array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('FIXED_REP_PKG', 'CLASS_CODES_TB_BARCODE',$params,0);

        return $result['CUR_RES'];
    }
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE_SERIAL',$params);
        return $result['MSG_OUT'];
    }

    function print_case_code($id){

        $params =array(
            array('name'=>':CLASS_CODE_SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CLASS_CODES_BAR_CODE_CASE',$params);;

        return $result['MSG_OUT'];

    }
    function print_case_codes($id){

        $params =array(
            array('name'=>':BARCODE_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CLASS_CODES_BAR_CODE_CASES',$params);;

        return $result['MSG_OUT'];

    }

}
