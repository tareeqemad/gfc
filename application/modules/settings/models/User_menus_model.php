<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/4/14
 * Time: 9:32 AM
 */

class User_menus_model extends MY_Model{
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    /**
     * @param int $parent
     * @return array
     * get list of system_menus deepened on parent id
     */
    function getList($parent = 0,$user,$id_system = null){

        

        $params =array(
            array('name'=>':USER_NO_in','value'=>"{$user}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':MENU_PARENT_NO_in','value'=>"{$parent}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':ID_SYSTEM_IN','value'=>$id_system,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('setting_pkg','SYSTEM_M_U_MENUS_V_GET_LIST',$params);
        return $result;
    }

    /**
     * @param $user
     * @param $menu
     * @param $menu_index
     * @return array
     */
    function check_permission($user,$menu,$menu_index){



        $params =array(
            array('name'=>':USER_NO_in','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':menu_full_code_IN1','value'=>"{$menu}",'type'=>'','length'=>-1),
            array('name'=>':menu_full_code_IN2','value'=>"{$menu_index}",'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->conn->excuteProcedures('setting_pkg','USER_MENUS_TB_JOIN_COUNT',$params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists account ..
     *
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('setting_pkg','USER_MENUS_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete account ..
     */
    function delete($id,$id_system){

        $params =array(
            array('name'=>':USER_NO_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ID_SYSTEM','value'=>$id_system,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('setting_pkg','USER_MENUS_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }

    function get_user_menus($user_no,$system_id){

        $params =array(
            array('name'=>':USER_NO','value'=>$user_no,'type'=>'','length'=>-1),
            array('name'=>':SYSTEM_ID','value'=>$system_id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('SETTING_PKG','GET_USER_MENUS',$params);
        return $result;
    }
}