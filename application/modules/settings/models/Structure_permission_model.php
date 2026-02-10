<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/17/14
 * Time: 9:30 AM
 */

class Structure_permission_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    /**
     * @param int $parent
     * @return array
     * get list of gcc structure permission  deepened on   id
     */
    function get($id,$user){


        $params =array(
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':ST_ID_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('USER_PKG','STRUCTURE_PERMISSION_TB_GET',$params);
        return $result;
    }

    /**
     * @param int $parent
     * @return array
     * get list of gcc structure  deepened on parent id
     */
    function get_list($parent = 0,$user = 0){


        $params =array(
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':ST_PARENT_ID_in','value'=>"{$parent}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('setting_pkg','GCC_STRUCTURE_TB_GET_LIST',$params);
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

        $result = $this->conn->excuteProcedures('USER_PKG','STRUCTURE_PERMISSION_TB_JOIN_COUNT',$params);
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
        $result = $this->conn->excuteProcedures('USER_PKG','STRUCTURE_PERMISSION_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete structure permission ..
     */
    function delete($id,$ST_ID){

        $params =array(
            array('name'=>':USER_NO_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ST_ID_IN','value'=>$ST_ID,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('USER_PKG','STRUCTURE_PERMISSION_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }


}