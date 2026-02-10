<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 13/06/16
 * Time: 01:53 م
 */ 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluation_extra_axes_ask_model extends MY_Model{
    var $PKG_NAME= "HR_PKG";
    var $TABLE_NAME= 'EVA_EXTRA_AXES_ASK';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
	function get($id= 0){
        
        $params =array(
            array('name'=>':EEXTRA_ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }
	function getID($id= 0){
        
        $params =array(
            array('name'=>':EEXTRA_ELEMENT_ID_IN','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ID',$params);
        return $result;
    }
	function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }
	function update($id= 0,$status){
		$params =array(
            array('name'=>':EEXTRA_ELEMENT_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':STATUS_IN','value'=>$status,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_STATUS',$params);
        return $result['MSG_OUT'];
	}
}