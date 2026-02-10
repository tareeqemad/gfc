<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/07/15
 * Time: 10:51 ص
 */

class Technical_jobs_cars_model extends MY_Model{
    var $PKG_NAME= "TECHNICAL_PKG";
    var $TABLE_NAME= 'TECHNICAL_JOBS_CARS_TB';

    function get_list($id= 0){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':JOB_ID','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result[(int)$cursor];
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
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

}
