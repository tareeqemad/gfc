<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 09:33 ص
 */

class cars_additional_fuel_model extends MY_Model{
    var $PKG_NAME= "PAYMENT_PKG";
    var $TABLE_NAME= 'CARS_ADDITIONAL_FUEL_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id= 0){
        
        $params =array(
            array('name'=>':CAR_FILE_ID','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    function get_count($insql){
        
        $params =array(
            array('name'=>':INSQL','value'=>$insql,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_COUNT',$params);
        return $result;
    }

    function get_list($insql, $offset, $row){
        
        $params =array(

            array('name'=>':INSQL','value'=>$insql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }


    function get_details_list($id){
        
        $params =array(

            array('name'=>':CARS_ADDITIONAL_FUEL_ID_IN','value'=>$id,'type'=>'','length'=>-1),

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'CARS_ADD_FUEL_DET_TB_GET',$params);
        return $result;
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function create_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'CARS_ADD_FUEL_DET_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    function edit_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'CARS_ADD_FUEL_DET_TB_UPDATE',$params);
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
            array('name'=>':CUSTOMER_SEQ','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }

    function  adopt($id,$ser, $case ,$amount,$notes ){


         $params =array(
             array('name'=>':CARS_ADDITIONAL_FUEL_ID_IN','value'=>$id,'type'=>'','length'=>-1),
             array('name'=>':SER_IN','value'=>$ser,'type'=>'','length'=>-1),
             array('name'=>':CARS_ADDITIONAL_FUEL_IN','value'=>$case,'type'=>'','length'=>-1),
             array('name'=>':AMOUNT_IN','value'=>$amount,'type'=>'','length'=>-1),
             array('name'=>':NOTE_IN','value'=>$notes,'type'=>'','length'=>-1),
             array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
         );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'CARS_ADDITIONAL_FUEL_TB_ADOPT',$params);

        return $result['MSG_OUT'];
    }


    function  save($id, $case ,$amount,$notes ){


        $params =array(
            array('name'=>':CARS_ADDITIONAL_FUEL_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':CARS_ADDITIONAL_FUEL_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':AMOUNT_IN','value'=>$amount,'type'=>'','length'=>-1),
            array('name'=>':NOTE_IN','value'=>$notes,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'CARS_ADDITIONAL_FUEL_TB_SAVE',$params);

        return $result['MSG_OUT'];
    }


}
