<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 21/12/14
 * Time: 09:06 ص
 */

class Projects_model extends MY_Model{
    var $PKG_NAME= "BUDGET_PKG";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id){

        $params =array(
            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BUDGET_PROJECTS_FILE_TB_GET',$params);

        return $result;
    }

    function get_last($id){

        $params =array(
            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BUDGET_PROJECTS_FILE_TB_GET',$params);

        return $result;
    }

    /**
     * @return array
     *
     * return all projects data ..
     */
    function get_list($sql,$offset,$row){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BUDGET_PROJECTS_FILE_TB_LIST',$params);

        return $result;
    }


    /**
     * @return array
     *
     * return all projects data ..
     */
    function get_list_archive($sql,$offset,$row){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_FILE_ARCHIVE_GET_LIST',$params);

        return $result;
    }

    /**
     * @return array
     *
     * return all projects data ..
     */
    function get_count($sql){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BUDGET_PROJECTS_FILE_GET_COUNT',$params);

        return $result;
    }

    /**
     * @param $data
     *
     * create new project ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BUDGET_PROJECTS_FILE_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    /**
     * @param $data
     *
     * update exists project ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BUDGET_PROJECTS_FILE_TB_UPDATE',$params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete project ..
     */
    function delete($id){

        $params =array(
            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BUDGET_PROJECTS_FILE_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }


    function delete_details($id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BUDGET_PROJECTS_FILE_D_DELETE',$params);

        return $result['MSG_OUT'];

    }


    /* function get_project_items(){



         $params =array(
             array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
             array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
         );

         $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_ITEM_GET_ALL',$params);

         return $result;
     }*/

    function get_item_count($sql){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_ITEM_CLASS_COUNT',$params);

        return $result;
    }


    function get_project_items($sql='',$offset,$row){



        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_ITEM_GET_LIST',$params);

        return $result;
    }


    function insert_price($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'PROJECTS_ITEM_INSERT',$params);


        return $result['MSG_OUT'];
    }

    function update_price($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'PROJECTS_ITEM_UPDATE',$params);


        return $result['MSG_OUT'];
    }
    function delete_price($id){

        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'PROJECTS_ITEM_DELETE',$params);

        return $result['MSG_OUT'];

    }


    /******************************** details ***************************/

    function create_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BUDGET_PROJECTS_FILE_D_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit_details($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BUDGET_PROJECTS_FILE_D_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function get_details($id,$case= null){



        $params =array(

            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BUDGET_PROJECTS_FILE_D_GET',$params);

        return $result;
    }

    function get_details_last($id){



        $params =array(

            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_FILE_DET_ARCHIVE_GET',$params);

        return $result;
    }

    function adopt($id,$case){

        $params =array(
            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT_IN','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'BUDGET_PROJECTS_FILE_TB_ADOPT',$params);

        return $result['MSG_OUT'];

    }

    /*********************************************************************/
    function adopt_com($id,$type,$value){

        $params =array(
            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':COMPANY_VALUE_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':COMPANY_VALUE_IN','value'=>$value,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'PROJECTS_FILE_UPDATE_COMPANY',$params);

        return $result['MSG_OUT'];

    }
    /************************************* accounts **********************/

    function get_accounts($id){



        $params =array(

            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_ACCOUNTS_TB_GET',$params);

        return $result;
    }


    function edit_accounts($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'PROJECTS_ACCOUNTS_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function get_accounts_list($sql='',$offset,$row){



        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_ACCOUNTS_TB_LIST',$params);

        return $result;
    }

    /**
     * @param $id
     * @param $adopt
     * @return mixed
     *
     */
    function transfer($id,$adopt,$adopter_id){

        $params =array(
            array('name'=>':PROJECT_SERIAL_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT','value'=>$adopt,'type'=>'','length'=>-1),
            array('name'=>':POWER_ADAPTER_NAME_IN','value'=>$adopter_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('PROJECTS_PKG','PROJECTS_FILE_ARCHIVE_TRANSFER',$params);

        return $result['MSG_OUT'];

    }

    /**
     * @param $id
     * @param $notes
     * @return mixed
     */
    function update_inUse($id,$notes){

        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT','value'=>$notes,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('PROJECTS_PKG','PROJECTS_FILE_DET_CHANGE',$params);

        return $result['MSG_OUT'];

    }

    function BUDGET_PROJECTS_FILE_TB_COPY($PROJECT_TEC_CODE ,$SECTION_NO, $YYEAR ,$MONTH){

        $params =array(
            array('name'=>':PROJECT_TEC_CODE_IN','value'=>$PROJECT_TEC_CODE,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$SECTION_NO,'type'=>'','length'=>-1),
            array('name'=>':YYEAR_IN','value'=>$YYEAR,'type'=>'','length'=>-1),
            array('name'=>':MONTH_IN','value'=>$MONTH,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('BUDGET_PKG','BUDGET_PROJECTS_FILE_TB_COPY',$params);

        return $result['MSG_OUT'];

    }


    function projects_accounts_fina_list($sql='',$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'PROJECTS_ACCOUNTS_FINA_LIST',$params);

        return $result;

    }
    function BUDGET_PROJECTS_FILE_sat(){

        $params =array(
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('statistics_pkg','BUDGET_PROJECTS_FILE_SAT',$params);

        return $result;
    }

}