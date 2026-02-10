<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 25/01/22
 * Time: 05:17 ص
 */

class Workfield_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function getTwoColumn($procedure, $first_parameter, $second_parameter)
    {
        
        $params = array(
            array('name' => ':FIRST_PARAMETER', 'value' => $first_parameter, 'type' => '', 'length' => -1),
            array('name' => ':SECOND_PARAMETER', 'value' => $second_parameter, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->package, $procedure, $params);

        return $result;
    }
    function getThreeColumn($procedure, $first_parameter, $second_parameter, $third_parameter)
    {
        
        $params = array(
            array('name' => ':FIRST_PARAMETER', 'value' => $first_parameter, 'type' => '', 'length' => -1),
            array('name' => ':SECOND_PARAMETER', 'value' => $second_parameter, 'type' => '', 'length' => -1),
            array('name' => ':THIRD_PARAMETER', 'value' => $third_parameter, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->package, $procedure, $params);

        return $result;
    }
	
	function getFourColumn($procedure, $first_parameter, $second_parameter, $third_parameter, $fourth_parameter)
    {

        $params = array(
            array('name' => ':FIRST_PARAMETER', 'value' => $first_parameter, 'type' => '', 'length' => -1),
            array('name' => ':SECOND_PARAMETER', 'value' => $second_parameter, 'type' => '', 'length' => -1),
            array('name' => ':THIRD_PARAMETER', 'value' => $third_parameter, 'type' => '', 'length' => -1),
            array('name' => ':FOURTH_PARAMETER', 'value' => $fourth_parameter, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->package, $procedure, $params);

        return $result;
    }
	

    function getMSG($procedure)
    {
        $params = array(
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures($this->package, $procedure, $params);

        return $result['MSG_OUT'];
    }

    function getData($procedure)
    {
        
        $params = array(
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->package, $procedure, $params);
        return $result;
    }

    function get($procedure, $id)
    {
        
        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get($this->package, $procedure, $params);

        return $result;
    }

    function getMsg_ID($procedure, $id)
    {
        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );

        $result = $this->conn->excuteProcedures($this->package, $procedure, $params);
        return $result['MSG_OUT'];
    }

    function get_user_by_branch($id){
        $params =array(
            array('name'=>':BRANCH_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->package,'GET_USERS_ACCORDING_TO_BRANCH',$params);
        return $result;
    }

    function public_get_users_by_user_type($id){
        $params =array(
            array('name'=>':BRANCH_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->package,'GET_USERS_ACCORDING_TO_BRANCH',$params);
        return $result;
    }

}