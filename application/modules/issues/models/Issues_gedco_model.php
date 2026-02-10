<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/02/19
 * Time: 12:56 م
 */

class issues_gedco_model extends MY_Model{
    var $PKG_NAME= "LAW_PKG";
    var $TABLE_NAME= 'ISSUE_TO_GEDCO_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_sub_info($id){

        $params =array(
            array('name'=>':SUBSCRIBER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','MONTH_BILLS_TB_GET',$params);

        return $result;
    }
	
	    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'ISSUE_TO_GEDCO_TB_INSERT',$params);

            return $result['MSG_OUT'];
    }
	
	/*******************************************************************************************/

    function get_list($sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('LAW_PKG','ISSUE_TO_GEDCO_TB_LIST',$params);

        return $result;
    }

/*************************/

    function get_curr_list($sql){

        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('LAW_PKG','ISSUE_TO_GEDCO_CURR_LIST',$params);

        return $result;
    }
    /*************/
    function get_list_sub($sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','LAW_SUBSCRIBER_INFO_TB_LIST',$params);
       // var_dump($result);
        //die;
        return $result;
    }


    /*****************************************************************************************************/
    function get_issue($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','ISSUE_TO_GEDCO_TB_GET',$params);
        return $result;
    }

    /************************************************************************************************/


    function get_issue_exec($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','ISSUE_GEDCO_TB_GET_EXEC',$params);
        return $result;
    }

    /************************************************************************************************/
    function edit($data){

        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','ISSUE_TO_GEDCO_TB_UPDATE',$params);
        return $result['MSG_OUT'];

    }
    /************************************************/
    function adopt_info($id,$case,$table_name,$branch,$user_id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':TABLE_NAME_IN','value'=>$table_name,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_BRANCH_USER_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_USER_IN','value'=>$user_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('LAW_PKG','ISSUE_TO_GEDCO_TB_ADOPT',$params);

        return $result['MSG_OUT'];

    }
    /************************************************/





}
