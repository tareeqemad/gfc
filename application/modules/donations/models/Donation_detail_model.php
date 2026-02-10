<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/10/15
 * Time: 09:37 ص
 */
class donation_detail_model extends MY_Model{
    var $PKG_NAME= "PAYMENT_PKG";
    var $TABLE_NAME= 'DONATION_FILE_DET_TB';
    var $TABLE_NAME_DEPT= 'DONATION_DEPARTMENTS_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_details_all($id= 0){

        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    function get_dept($id= 0){

        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME_DEPT.'_GET',$params);
        return $result;
    }

    function get_list($sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
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
        $result = $this->conn->excuteProcedures($this->PKG_NAME,  $this->TABLE_NAME.'_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function create_dept($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME_DEPT.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,  $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function edit_dept($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME_DEPT.'_UPDATE',$params);
        // echo  $result['MSG_OUT'];
        return $result['MSG_OUT'];
    }

    function delete($id,$donation_id){
        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':DONATION_FILE_ID_IN','value'=>$donation_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function delete_dept($id,$donation_id){
        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':DONATION_FILE_ID_IN','value'=>$donation_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME_DEPT.'_DELETE',$params);
        return $result['MSG_OUT'];
    }
    function get_details_for_stores($id= 0){

        $params =array(
            array('name'=>':DONATION_FILE_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'DONATION_FILE_DET_FOR_STORES',$params);
        return $result;
    }
    function update_class_case($ser,$class_case,$replace_calss_id,$replace_class_unit,$replace_class_type,$remender_amount,$class_case_hints,$replace_class_case_hints){
      $params =array(
            array('name'=>':SER_IN','value'=>"{$ser}",'type'=>'','length'=>-1),
          array('name'=>':CLASS_CASE_IN','value'=>"{$class_case}",'type'=>'','length'=>-1),
          array('name'=>':REPLACE_CALSS_ID_IN','value'=>"{$replace_calss_id}",'type'=>'','length'=>-1),
          array('name'=>':REPLACE_CLASS_UNIT_IN','value'=>"{$replace_class_unit}",'type'=>'','length'=>-1),
          array('name'=>':REPLACE_CLASS_TYPE_IN','value'=>"{$replace_class_type}",'type'=>'','length'=>-1),
          array('name'=>':REMENDER_AMOUNT_IN','value'=>"{$remender_amount}",'type'=>'','length'=>-1),
          array('name'=>':CLASS_CASE_HINTS_IN','value'=>"{$class_case_hints}",'type'=>'','length'=>-1),
          array('name'=>':REPLACE_CLASS_CASE_HINTS_IN','value'=>"{$replace_class_case_hints}",'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DONATION_FILE_DET_TB_REPLACE',$params);
        return $result['MSG_OUT'];
    }

}
