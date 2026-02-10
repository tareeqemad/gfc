<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/1/14
 * Time: 1:18 PM
 */

class Sysmenus_model extends MY_Model{

    /**
     * @return array
     *
     * return all system_menus data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function getAll(){

        $params =array(

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('setting_pkg','SYSTEM_MENU_TB_GET_ALL',$params);

        return $result;
    }

    /**
     * @param int $parent
     * @return array
     * get list of system_menus deepened on parent id
     */
    function getList($parent = 0,$view,$main,$user,$MENU_C0DE = -1,$system_id = null){

        $params =array(
            array('name'=>':MENU_PARENT_NO_in','value'=>"{$parent}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':MAIN_MENU_IN','value'=>"{$main}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':VIEW_MENU_IN','value'=>"{$view}",'type'=>SQLT_CHR,'length'=>-1),

            array('name'=>':USER_NO_IN','value'=>"{$user}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':MENU_PARENT_CODE_IN','value'=>"{$MENU_C0DE}",'type'=>'','length'=>-1),
            array('name'=>':SYSTEM_ID','value'=>"{$system_id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->New_rmodel->general_get('setting_pkg','SYSTEM_MENU_TB_GET_LIST',$params);

        return $result;
    }



    /**
     * @param int $id
     * @return mixed
     * return one account ..
     */
    function get($id = 0){

        $params =array(
            array('name'=>':MENU_NO_in','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('setting_pkg','SYSTEM_MENU_TB_GET',$params);

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
        $result = $this->conn->excuteProcedures('setting_pkg','SYSTEM_MENU_TB_INSERT',$params);
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
        $result = $this->conn->excuteProcedures('setting_pkg','SYSTEM_MENU_TB_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $data
     *
     * update exists account ..
     *
     */
    function sort($id,$count){
        $params =array(
            array('name'=>':MENU_NO_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':SORT_IN','value'=>$count,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures('setting_pkg','SYSTEM_MENU_TB_SORT_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete account ..
     */
    function delete($id){

        $params =array(
            array('name'=>':MENU_NO_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('setting_pkg','SYSTEM_MENU_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }


    function getAllSystems(){

        $params =array(

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('setting_pkg','GFC_SYSTEM_GET_ALL',$params);

        return $result;
    }



}