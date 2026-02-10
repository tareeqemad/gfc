<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class checks_model extends MY_Model{
    var $PKG_NAME= "LAW_PKG";
    var $TABLE_NAME= 'ISSUE_GEDCO_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    /***********************************************************************************************/

    function create($data){
        $params =array();

        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'CHECKS_GEDCO_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    /*****************************************************************************************************/
    function edit($data){

        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('LAW_PKG','CHECKS_GEDCO_TB_UPDATE',$params);


        return $result['MSG_OUT'];

    }

    /**********************************************************************************************************/

    function get_sub_info($id){

        $params =array(
            array('name'=>':SUBSCRIBER_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','MONTH_BILLS_TB_GET',$params);

        return $result;
    }


    /**********************************************************************************************************/
    function get_check($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','CHECKS_GEDCO_TB_GET',$params);
        return $result;
    }

    /**********************************************************************************************************/
    function get_ret_check($id= 0){

        $params =array(
            array('name'=>':SER_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','CHECKS_PORTFOLIO_TB_GET',$params);


        return $result;
    }

    /*****************************************************************************************************/


    function get_list($sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','CHECKS_GEDCO_TB_LIST',$params);
        return $result;
    }



    /*****************************************************************************************************/

    function adopt_info($id,$case,$table_name,$branch,$user_id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':TABLE_NAME_IN','value'=>$table_name,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_BRANCH_USER_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_USER_IN','value'=>$user_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('LAW_PKG','CHECKS_GEDCO_TB_ADOPT',$params);

        return $result['MSG_OUT'];

    }
    /************************************************/


    function get_pay_list($sql,$offset,$row){


        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','CHECKS_PORTFOLIO_TB_LIST',$params);




        return $result;
    }

/****************************************************************************************************/
function get_count($id, $name){


        $params =array(
            array('name'=>':ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NAME_IN','value'=>"{$name}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG', 'SUBSCRIBER_TB_GET_COUNT',$params);
        return $result;
    }
/******************************************************************************************************/
   function get_lists($id, $name,$offset, $row){


        $params =array(
            array('name'=>':ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NAME_IN','value'=>"{$name}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','SUBSCRIBER_TB_GET_LIST',$params);
        return $result;
    }

}
