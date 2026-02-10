<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/02/15
 * Time: 12:58 م
 */

class Customers_pledges_model extends MY_Model{
    var $PKG_NAME= "FIXED_PKG";
   var $TABLE_NAME= 'CUSTOMERS_PLEDGES';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_branch_all(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('EMP_PKG','BRANCH_LINK_GET_ALL',$params);
        return $result;
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
    function get_details($id= 0){
        
        $params =array(
            array('name'=>':D_FILE_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_DETAILS',$params);
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
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
    // echo  $result['MSG_OUT'];
        return $result['MSG_OUT'];
    }
    function editBarcode($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE_BAR',$params);
        // echo  $result['MSG_OUT'];
        return $result['MSG_OUT'];
    }

    function adopt($id,$case){

        $params =array(
            array('name'=>':FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_ADOPT',$params);;

        return $result['MSG_OUT'];

    }
    function stop($id,$case){

        $params =array(
            array('name'=>':FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':STATUS_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_STATUS_ADOPT',$params);;

        return $result['MSG_OUT'];

    }
    function onEmp($id,$case){

        $params =array(
            array('name'=>':FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':STATUS_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_STATUS_ADOPT',$params);;

        return $result['MSG_OUT'];

    }
    function move($id,$customer_movment_id,$movment_manage_st_id,$movment_cycle_st_id,$movment_depart_st,$class_type,$customer_movment_type,$room_movement_id){

        $params =array(
            array('name'=>':FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CUSTOMER_MOVMENT_ID_IN','value'=>$customer_movment_id,'type'=>'','length'=>-1),
            array('name'=>':MOVMENT_MANAGE_ST_ID_IN','value'=>$movment_manage_st_id,'type'=>'','length'=>-1),
            array('name'=>':MOVMENT_CYCLE_ST_ID_IN','value'=>$movment_cycle_st_id,'type'=>'','length'=>-1),
            array('name'=>':MOVMENT_DEPART_ST_ID_IN','value'=>$movment_depart_st,'type'=>'','length'=>-1),
            array('name'=>':CLASS_TYPE_IN','value'=>$class_type,'type'=>'','length'=>-1),
            array('name'=>':CUSTOMER_MOVMENT_TYPE_IN','value'=>$customer_movment_type,'type'=>'','length'=>-1),
            array('name'=>':ROOM_MOVEMENT_ID_IN','value'=>$room_movement_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_MOVE',$params);;

        return $result['MSG_OUT'];

    }
    function move_adopt($id){

        $params =array(
            array('name'=>':FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_MOVE_ADOPT',$params);;

        return $result['MSG_OUT'];

    }

    function structre_emloyee_get($id){ // New_rmodel Done

        $params =array(
            array('name'=>':ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),

        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'STRUCTRE_EMLOYEE_GET',$params,0);

        return $result['CUR_RES'];

    }

    function get_bar_code_info($id= 0){
        
        $params =array(
            array('name'=>':CLASS_CODE_SER_IN','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'CLASS_CODES_TB_GET_CLASS',$params);
        return $result;
    }
    function return_store($id,$store_id,$class_type){

        $params =array(
            array('name'=>':FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':STORE_ID_IN','value'=>$store_id,'type'=>'','length'=>-1),
             array('name'=>':CLASS_TYPE_IN','value'=>$class_type,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_RETURN',$params);;

        return $result['MSG_OUT'];

    }
    function return_adopt($id){

        $params =array(
            array('name'=>':FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_RETURN_ADOPT',$params);;

        return $result['MSG_OUT'];

    }
	
	    function customers_pledges_get_file_ids($customer_type,$customer_id,$file_id){
        
        $params =array(
            array('name'=>':CUSTOMER_TYPE_IN','value'=>$customer_type,'type'=>'','length'=>-1),
            array('name'=>':CUSTOMER_ID_IN','value'=>$customer_id,'type'=>'','length'=>-1),
            array('name'=>':FILE_ID_IN','value'=>$file_id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'CUSTOMERS_PLEDGES_GET_FILE_IDS',$params);
        return $result;
    }
	
 
    function set_code($id){

        $params =array(
            array('name'=>':FILE_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_CODES',$params);;

        return $result['MSG_OUT'];

    }
 // Mkilani


    // Mkilani
    function get_rooms(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'ROOMS_STRUCTURE_LIST_PLEDGES',$params);
        return $result;
    }

}