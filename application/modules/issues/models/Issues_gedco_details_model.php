<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/06/20
 * Time: 11:58 ص
 */

class issues_gedco_details_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_defendant_tb_all($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('LAW_PKG','DEFENDANT_TB_GET',$params);

        return $result;
    }

    /*******************************************************************************************/

    function get_gedco_issue_requests_all($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('LAW_PKG','GEDCO_ISSUE_REQUESTS_GET',$params);

        return $result;
    }

    /*******************************************************************************************/
    function get_gedco_issues_actions_all($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('LAW_PKG','GEDCO_ISSUES_ACTIONS_TB_GET',$params);

        return $result;
    }

    /*******************************************************************************************/

    function create_defendant_tb($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','DEFENDANT_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }
    /*******************************************************************************************/


    function create_gedco_issue_requests($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','GEDCO_ISSUE_REQUESTS_INSERT',$params);

        return $result['MSG_OUT'];
    }


    /*******************************************************************************************/


    function create_gedco_issues_actions($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('LAW_PKG','GEDCO_ISSUES_ACTIONS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    /*******************************************************************************************/

    function edit_defendant_tb($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','DEFENDANT_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }


    /*******************************************************************************************/
    function edit_gedco_issue_requests($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','GEDCO_ISSUE_REQUESTS_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /*******************************************************************************************/

    function edit_gedco_issues_actions($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','GEDCO_ISSUES_ACTIONS_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }







}
