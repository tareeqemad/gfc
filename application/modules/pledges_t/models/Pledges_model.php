<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 29/01/2019
 * Time: 12:00 م
 */




class pledges_model extends MY_Model {

    var $PKG_NAME= "HR_PLEDGES";
    var $TABLE_NAME= 'PLED_ADD_TB';
    var $DETAIL='CUSTOMERS_PLEDGES';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id= 0){
        
        $params =array(
            array('name'=>':FILE_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }



    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function get_list($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

/*****************************************************************************************************************/
    function get_list_d($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'CUSTOMERS_PLEDGES_LIST',$params);
        return $result;
    }
/*****************************************************************************************************************/

    function delete($id){
        $params =array(
            array('name'=>':ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }
/******************************************/
    function edit($file_id,$pledges_exist){
    $params =array(
        array('name'=>':FILE_ID','value'=>$file_id,'type'=>'','length'=>-1),
        array('name'=>':PLEDGES_EXIST','value'=>$pledges_exist,'type'=>'','length'=>-1),
        array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
    );
    // $this->_extract_data($params);
    $result = $this->conn->excuteProcedures($this->PKG_NAME, 'CUSTOMERS_PLEDGES_UPDATE_ST',$params);
    // echo  $result['MSG_OUT'];
    return $result['MSG_OUT'];
}
/************************************/
}

?>