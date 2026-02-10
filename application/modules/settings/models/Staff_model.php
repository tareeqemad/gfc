<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/16/14
 * Time: 10:03 AM
 */

class Staff_model extends MY_Model{


    /**
     * @return array
     *
     * return all users data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_list($BRANCH,$EMP_NO,$ST_NO,$NAME = null ){

        $params =array(
            array('name'=>':BRANCH_IN','value'=>$BRANCH,'type'=>'','length'=>-1),
            array('name'=>':NO_IN','value'=>"{$EMP_NO}",'type'=>'','length'=>-1),
            array('name'=>':NAME_IN','value'=>"{$NAME}",'type'=>'','length'=>-1),
            array('name'=>':ST_NO_IN','value'=>"{$ST_NO}",'type'=>'','length'=>-1),

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('EMP_PKG','EMP_GET_LIST',$params);

        return $result;
    }


    /**
     * @return array
     *
     * return all users data ..
     */
    function get_emp_st_list($BRANCH,$EMP_NO,$ST_NO = null,$SQL = null){

        $params =array(
            array('name'=>':BRAN_IN','value'=>"{$BRANCH}",'type'=>'','length'=>-1),
            array('name'=>':USER_ID_IN','value'=>"{$EMP_NO}",'type'=>'','length'=>-1),
            array('name'=>':ST_ID_IN','value'=>"{$ST_NO}",'type'=>'','length'=>-1),
            array('name'=>':PXSQL_IN','value'=>  $SQL  ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('EMP_PKG','EMP_STRUCTURE_LIST',$params);


        return $result;
    }


    /**
     * @param $data
     *
     * create new staff ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('EMP_PKG','EMP_UPDATE_GCC_ST_NO',$params);
        return $result['MSG_OUT'];
    }

}