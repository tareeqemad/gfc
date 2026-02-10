<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 20/11/17
 * Time: 10:17 ص
 */


class plan_detail_model extends MY_Model{
    var $PKG_NAME= "PLAN_PKG";
    var $TABLE_NAME= 'DISTRBUTE_ACTIVITY_TB';
    // var $TABLE_NAME2= 'PLAN_PROJECT_TB';

    function get_details_all($id= 0){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result[(int)$cursor];
    }

    function create_part($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);

        return $result['MSG_OUT'];
    }
    function create_achive_evaluate($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACHIVE_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

      function edit_achive_evaluate($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACHIVE_TB_UPDATE',$params);

        return $result['MSG_OUT'];
       }
    function activities_plan_refrech_update($id,$for_month){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':MONTH_ACHIVE_IN','value'=>"{$for_month}",'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'ACTIVITIES_PLAN_REFRECH_UPDATE',$params);;
  //var_dump($result['MSG_OUT']);
        return $result['MSG_OUT'];

    }
    function edit_part($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME,  $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

}
