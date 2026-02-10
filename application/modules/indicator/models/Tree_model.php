<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/06/18
 * Time: 11:56 ص
 */


class tree_model extends MY_Model{
    var $PKG_NAME= "INDECATOR_PKG";
    var $TABLE_NAME= 'INDECATOR_DATA_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

	    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'TREE_SECTORS_INSERT',$params);
           // var_dump($result);
        return $result['MSG_OUT'];
    }
	
         function get_tree_sectors($id_father_in){

        $params =array(
            array('name'=>':ID_FATHER_IN_IN','value'=>"{$id_father_in}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TREE_SECTORS_GET',$params);

        return $result;
    }

    function get_parent_tree_sectors($id_father_in,$user){

        $params =array(
            array('name'=>':USER_IN_IN','value'=>"{$user}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ID_FATHER_IN_IN','value'=>"{$id_father_in}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TREE_PARENT_SECTORS_GET',$params);

        return $result;
    }

    function get($id = 0){



        $params =array(
            array('name'=>':ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'TREE_SECTORS_INDECATIORS_SHOW',$params);

        return $result;
    }
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'TREE_SECTORS_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    function delete($id){

        $params =array(
            array('name'=>':ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'TREE_SECTORS_DELETE',$params);

        return $result['MSG_OUT'];

    }
	
}
