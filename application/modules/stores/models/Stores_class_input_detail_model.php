<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/11/14
 * Time: 09:58 ص
 */
class stores_class_input_detail_model extends MY_Model{
    var $PKG_NAME= "STORES_PKG";
    //  var $TABLE_NAME= 'RECEIPT_CLASS_INPUT_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    
    function get($id= 0){
        
        $params =array(
            array('name'=>':SER','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'STORES_CLASS_INPUT_DET_GET',$params);
        return $result;
    }
    function get_details_all($id= 0){
        
        $params =array(
            array('name'=>':CLASS_INPUT_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'STORES_CLASS_INPUT_DET_LIST',$params);
        return $result;
    }
    function get_details_all_stores($id= 0){
        
        $params =array(
            array('name'=>':CLASS_INPUT_IDS_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'STORES_CLASS_INPUT_DET_TRANS',$params);
        return $result;
    }



    function get_all(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'STORES_CLASS_INPUT_DET_ALL',$params);
        return $result;
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'STORES_CLASS_INPUT_DET_INSERT',$params);

        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'STORES_CLASS_INPUT_DET_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'STORES_CLASS_INPUT_DET_DELETE',$params);
        return $result['MSG_OUT'];
    }
}
