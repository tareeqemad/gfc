<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/01/16
 * Time: 12:27 م
 */

class feeder_layers_group_model extends MY_Model{
    var $PKG_NAME= "TECHNICAL_PKG";
    var $TABLE_NAME= 'FEEDER_LAYERS_GROUP_TB';

    function get_list($id= 0){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':LAYER_ID','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result[(int)$cursor];
    }

    function create($layer_id,$group_id){
        $params =array(
            array('name'=>':LAYER_ID','value'=>$layer_id ,'type'=>'','length'=>-1),
            array('name'=>':GROUP_ID','value'=>$group_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':G_SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

}
