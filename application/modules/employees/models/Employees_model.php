<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/25/14
 * Time: 10:02 AM
 */

class Employees_model extends MY_Model{
    /**
     * @return array
     *
     * return all users data ..
     */

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_all($SQL =''){



        $params =array(
            //array('name'=>':XSQL','value'=>$SQL,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get('EMP_PKG','EMPLOYEES_TB_GET_ALL',$params);

        return $result;
    }

    /* MKILANI */
    function get_all_from_data($branch =''){

        $params =array(
            array('name'=>':BRANCH_IN','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('EMP_PKG','EMPLOYEES_DATA_GET_ALL',$params);
        return $result;
    }


    /**
     * @return array
     *
     * return all users data ..
     */
    function get_count($branch,$sql = null){



        $params =array(

            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );

        $result = $this->New_rmodel->general_get('EMP_PKG','EMPLOYEES_TB_GET_COUNT',$params);

        return $result;
    }

    /**
     * @return array
     *
     * return all users data ..
     */
    function get_list($branch ,$offset,$row,$sql = null){



        $params =array(

            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>'','length'=>-1),
            array('name'=>':INSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':offset','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':row','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('EMP_PKG','EMPLOYEES_TB_GET_LIST',$params);

        return $result;
    }

    /**
     * employees Grads ..
     * @return mixed
     */
    function get_gradesn(){



        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('EMP_PKG','gradesn_DATA_GET_ALL',$params);

        return $result;

    }

    /**
     * employees التدرج الوظيفي ..
     * @return mixed
     */
    function get_kader(){



        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('EMP_PKG','kader_DATA_GET_ALL',$params);

        return $result;

    }

    /**
     * employees
     * @return mixed
     */
    function get_lookUps($tb){



        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('EMP_PKG',$tb.'_GET_ALL',$params);

        return $result;

    }





    /**
     * @param int $id
     * @return mixed
     * return one user ..
     */
    function get($id = '0'){



        $params =array(
            array('name'=>':USER_ID_in','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('EMP_PKG','EMPLOYEES_TB_GET',$params);

        return $result;
    }


    /**
     * @param $data
     *
     * create new user ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('EMP_PKG','EMPLOYEES_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists user ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('EMP_PKG','EMPLOYEES_TB_UPDATE',$params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete user ..
     */
    function delete($id){

        $params =array(
            array('name'=>':NO_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('EMP_PKG','EMPLOYEES_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }




}