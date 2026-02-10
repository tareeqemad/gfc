<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 26/11/15
 * Time: 10:13 ص
 */

class electricity_layers_model extends MY_Model{
    var $PKG_NAME= "TECHNICAL_PKG";
    var $TABLE_NAME= 'ELECTRICITY_LAYERS_TB';

    function get($id= 0){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':LAYER_ID','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result[(int)$cursor];
    }

    function get_all(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':GET_ROW_LOG','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,$this->TABLE_NAME.'_GET_ALL',$params);
        return $result[(int)$cursor];
    }

    function edit_case($id){
        $params =array(
            array('name'=>':ELECTRICITY_LOAD_SYSTEM','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_case',$params);
        return $result['MSG_OUT'];
    }

/*
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
            array('name'=>':LAYER_ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }
*/
}
