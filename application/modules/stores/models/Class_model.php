<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/14/14
 * Time: 9:17 AM
 */
class Class_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    // mkilani
    function get_list_table(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get('stores_pkg','CLASS_TB_LIST',$params);
        return $result;
    }

    // mkilani
    function get_class_amount($id=null){
        
        $params =array(
            array('name'=>':CLASS_ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get('stores_pkg','GET_CLASS_T_AMOUNT',$params);
        return $result;
    }

    /**
     * @return array
     *
     * return all accounts data ..
     */
    function getAll(){

        

        $params =array(

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('stores_pkg','CLASS_TB_GET_ALL',$params);

        return $result;
    }

    /**
     * @param int $parent
     * @return array
     * get list of accounts deepened on parent id
     */
   function getList($parent = 0){

        

        $params =array(
            array('name'=>':CLASS_PARENT_ID_in','value'=>"{$parent}",'type'=>'','length'=>-1),
                       array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('stores_pkg','CLASS_TB_GET_LIST',$params);
        return $result;
    }

    function get_lists($id, $name_ar, $name_en,$parent_id,$grand_id,$class_status, $offset, $row,$type=null){
        

        $params =array(
               array('name'=>':CLASS_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NAME_AR_IN','value'=>"{$name_ar}",'type'=>'','length'=>-1),
            array('name'=>':NAME_EN_IN','value'=>$name_en,'type'=>'','length'=>-1),
            array('name'=>':CLASS_PARENT_ID_IN','value'=>$parent_id,'type'=>'','length'=>-1),
            array('name'=>':CLASS_GRAND_ID_IN','value'=>"{$grand_id}",'type'=>'','length'=>-1),
            array('name'=>':CLASS_STATUS_IN','value'=>"{$class_status}",'type'=>'','length'=>-1),
            array('name'=>':CALSS_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
    //    print_r($params);
        $result = $this->New_rmodel->general_get('stores_pkg','CLASS_TB_GET_LISTS',$params);
        return $result;
    }
    function getAllParents(){

        

        $params =array(

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('stores_pkg','CLASS_TB_GET_ALL_PARENTS',$params);

        return $result;
    }

    /**
     * @param int $id
     * @return mixed
     * return one account ..
     */
    function get($id = 0,$type=null){

        

        $params =array(
            array('name'=>':CLASS_ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':CALSS_TYPE_IN','value'=>"{$type}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('stores_pkg','CLASS_TB_GET',$params);

        return $result;
    }


    /**
     * @param $data
     *
     * create new account ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('stores_pkg','CLASS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }




    /**
     * @param $data
     *
     * update exists account ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('stores_pkg','CLASS_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete account ..
     */
    function delete($id){

        $params =array(
            array('name'=>':CLASS_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('stores_pkg','CLASS_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }
    function get_count($id, $name_ar, $name_en,$parent_id,$grand_id,$class_status,$type=null){
        

        $params =array(
            array('name'=>':CLASS_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':NAME_AR_IN','value'=>"{$name_ar}",'type'=>'','length'=>-1),
            array('name'=>':NAME_EN_IN','value'=>"{$name_en}",'type'=>'','length'=>-1),
            array('name'=>':CLASS_PARENT_ID_IN','value'=>"{$parent_id}",'type'=>'','length'=>-1),
            array('name'=>':CLASS_GRAND_ID_IN','value'=>"{$grand_id}",'type'=>'','length'=>-1),
            array('name'=>':CLASS_STATUS_IN','value'=>"{$class_status}",'type'=>'','length'=>-1),
            array('name'=>':CALSS_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('stores_pkg', 'CLASS_TB_GET_COUNT',$params);
        return $result;
    }

    function get_project_item_list($sql,$offset,$row){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','PROJECTS_ITEM_CLASS_LIST',$params);

        return $result;
    }
    function get_project_items_list($sql,$offset,$row){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','PROJECT_ITEM_CLASS_LIST',$params);

        return $result;
    }
    function get_project_item_price($id){

        

        $params =array(


            array('name'=>':CLASS_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','PROJECTS_FILE_TB_GET_PRICE',$params);

        return $result;
    }

    function get_project_item_count($sql){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','PROJECTS_ITEM_CLASS_COUNT',$params);

        return $result;
    }
    function get_project_items_count($sql){

        

        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','PROJECT_ITEM_CLASS_COUNT',$params);

        return $result;
    }
    function getAllParentsProjects($id){

        

        $params =array(
            array('name'=>':PARENT_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','PROJECTS_ITEM_GET_ALL_PARENTS',$params);

        return $result;
    }
    function getAllGrands(){

        

        $params =array(
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','PROJECTS_ITEM_GET_ALL_GRANDS',$params);

        return $result;
    }
    function getAllParentsClasses($id){

        

        $params =array(
            array('name'=>':PARENT_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('STORES_PKG','CLASS_GET_ALL_PARENTS',$params);

        return $result;
    }
    function getAllGrandsClasses(){

        

        $params =array(
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('STORES_PKG','CLASS_GET_ALL_GRANDS',$params);

        return $result;
    }


}