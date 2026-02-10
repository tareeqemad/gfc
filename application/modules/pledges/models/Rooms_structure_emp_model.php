<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/12/19
 * Time: 09:46 ص
 */

class rooms_structure_emp_model extends MY_Model{
    var $PKG_NAME= "FIXED_PKG";
    var $TABLE_NAME= 'ROOMS_STRUCTURE_EMP';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    /*
    function get_all(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('EMP_PKG', 'W_NO_ADMIN_DATA_GET_ALL',$params);
        return $result['cursor'];
    }
    */

    function get_list($id){
        
        $params =array(
            array('name'=>':ROOM_ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

    function get_room($emp=0){
        
        $params =array(
            array('name'=>':EMP_NO','value'=>$emp,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ROOM',$params);
        return $result;
    }

    function get_room_by_id($customer_id){
        
        $params =array(
            array('name'=>':CUSTOMER_ID_IN','value'=>$customer_id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'ROOMS_STRUCTURE_CUSTOMER_GET_R',$params);
        return $result;
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
