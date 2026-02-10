<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/02/19
 * Time: 12:56 م
 */

class actions_model extends MY_Model{
    var $PKG_NAME= "LAW_PKG";
    var $TABLE_NAME= 'ISSUES_ACTIONS_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

	/*******************************************************************************************/
	
	    function get_details_actions_all($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('LAW_PKG','ISSUES_ACTIONS_TB_GET',$params);

        return $result;
    }
	
	/*******************************************************************************************/


    function get_details_bond_all($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('LAW_PKG','PAYMENTS_BONDS_TB_GET',$params);

        return $result;
    }

    /*******************************************************************************************/

    function get_details_check_all($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('LAW_PKG','CHECK_PAYMENTS_TB_GET',$params);

        return $result;
    }

    /*******************************************************************************************/


    function create_sub_actions($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','ISSUES_ACTIONS_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }
    /*******************************************************************************************/


    function create_sub_actions_bonds($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','PAYMENTS_BONDS_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }


    /*******************************************************************************************/


    function create_sub_actions_checks($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('LAW_PKG','CHECK_PAYMENTS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    /*******************************************************************************************/

    function edit_sub_actions($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','ISSUES_ACTIONS_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }


    /*******************************************************************************************/
    function edit_sub_actions_bonds($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','PAYMENTS_BONDS_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /*******************************************************************************************/

    function edit_sub_actions_checks($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','CHECK_PAYMENTS_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /*******************************************************************************************/



    function create_sub_payments($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','PAYMENTS_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }


    /*******************************************************************************************/

    function edit_sub_payments($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','PAYMENTS_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }


    /*******************************************************************************************/

    function get_issue_action($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ISSUES_ACTIONS_TB_GET',$params);

        return $result;
    }



    /*******************************************************************************************/

    function get_bond_payment($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PAYMENTS_BONDS_TB_GET',$params);

        return $result;
    }


    /*******************************************************************************************/

    function get_check_payment($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'CHECK_PAYMENTS_TB_GET',$params);

        return $result;
    }

    /**************************************************************************************************/

    function get_issue_ins($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PAYMENTS_TB_GET',$params);
        return $result;
    }








}
