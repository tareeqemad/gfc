<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/7/14
 * Time: 12:09 PM
 */

class Permission_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($SECTION_NO,$USER_NO){

        $params =array(
            array('name'=>':SECTION_NO','value'=>$SECTION_NO ,'type'=>'','length'=>-1),
            array('name'=>':USER_NO','value'=>$USER_NO ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('setting_pkg','BUDGET_PERMISSION_TB_GET', $params);
        return $result;
    }

    

    /**
     * @param $data
     *
     * update exists permission ..
     *
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('setting_pkg','BUDGET_PERMISSION_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete permission ..
     */
    function delete($USER_id,$SEC_NO){

        $params =array(
            array('name'=>':USER_NO_IN','value'=>$USER_id,'type'=>'','length'=>-1),
            array('name'=>':SECTION_NO_IN','value'=>$SEC_NO,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('setting_pkg','BUDGET_PERMISSION_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }


}