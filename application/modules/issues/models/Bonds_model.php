<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 16/03/19
 * Time: 08:37 ص
 */


class bonds_model extends MY_Model{
    var $PKG_NAME= "LAW_PKG";
    var $TABLE_NAME= 'ISSUE_GEDCO_TB';

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
               
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'STRUCTURED_BOND_TB_INSERT',$params);

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
        $result = $this->New_rmodel->general_get('LAW_PKG','STRUCTURED_BOND_TB_LIST',$params);
        return $result;
    }


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
    function get_bond($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','STRUCTURED_BOND_TB_GET',$params);
        return $result;
    }



    /************************************************************************************************/
    function edit($data){

        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','STRUCTURED_BOND_TB_UPDATE',$params);
        return $result['MSG_OUT'];

    }
    /************************************************************************************************/
    function edit_deliver($data){

        $params =array();
        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures('LAW_PKG','STRUCTURED_BOND_TB_DILEVER',$params);
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

        $result = $this->conn->excuteProcedures('LAW_PKG','STRUCTURED_BOND_TB_ADOPT',$params);

        return $result['MSG_OUT'];

    }
    /*****************************************************************************************************/
    function PRE_PAID_POWER_GET($SUB_NO){

        $params =array(
            array('name'=>':SUB_NO_IN','value'=>"{$SUB_NO}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
    //   $result = $this->New_rmodel->general_get('PRE_PAID_PKG','PRE_PAID_POWER_GET',$params);
        $result = $this->New_rmodel->general_get('LAW_PKG','PRE_PAID_POWER_GET',$params);
        return $result;
    }
    /************************************************/

 /*****************************************************************************************************/
    function SUBSCRIBERS_TB_GET($id){

        $params =array(
            array('name'=>':ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
    //   $result = $this->New_rmodel->general_get('PRE_PAID_PKG','PRE_PAID_POWER_GET',$params);
        $result = $this->New_rmodel->general_get('LAW_PKG','SUBSCRIBERS_TB_GET',$params);
        return $result;
    }
 /*****************************************************************************************************/
    function SUBSCRIBERS_TB_SUB_GET($no){

        $params =array(
            array('name'=>':NO_IN','value'=>"{$no}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
    //   $result = $this->New_rmodel->general_get('PRE_PAID_PKG','PRE_PAID_POWER_GET',$params);
        $result = $this->New_rmodel->general_get('LAW_PKG','SUBSCRIBERS_TB_SUB_GET',$params);
        return $result;
    }
    /*****************************************************************************************************/
    function to_issues_get($no){

        $params =array(
            array('name'=>':NO_IN','value'=>"{$no}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        //   $result = $this->New_rmodel->general_get('PRE_PAID_PKG','PRE_PAID_POWER_GET',$params);
        $result = $this->New_rmodel->general_get('LAW_PKG','TO_ISSUES_GET',$params);
        return $result;
    }
/****************************************************************/

    function bound_count($sql)
    {

        $params =array(
            array('name'=>':XSQL','value'=>$sql ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','GET_COUNT_STRUCTURED_BOND_TB',$params);
        return $result;
    }

}
