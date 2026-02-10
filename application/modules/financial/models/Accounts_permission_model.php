<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/7/14
 * Time: 12:09 PM
 */

class Accounts_permission_model extends MY_Model{
    /**
     * @param int $parent
     * @return array
     * get list of gcc accounts permission  deepened on   id
     */


    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get($id,$user){



        $params =array(
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID_IN','value'=>"{$id}",'type'=>'','length'=>-1),

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );


        $result = $this->New_rmodel->general_get('financial_pkg','ACCOUNT_PERMISSION_TB_GET',$params);
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
        $result = $this->conn->excuteProcedures('financial_pkg','ACCOUNT_PERMISSION_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete permission ..
     */
    function delete($USER_id,$ACCOUNT){

        $params =array(
            array('name'=>':USER_ID_IN','value'=>$USER_id,'type'=>'','length'=>-1),
            array('name'=>':ACCOUNT_ID_IN','value'=>$ACCOUNT,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->conn->excuteProcedures('financial_pkg','ACCOUNT_PERMISSION_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }

    /**
     * return User Accounts depend on income type , prefix of account , currency ..
     * @param $prefix
     * @param $type
     * @param $curr_id
     */
    function get_user_accounts($prefix,$type,$curr_id=null,$user = null,$case = 0,$account_id =null){


        $params =array(
            array('name'=>':ACOUNT_ID_IN','value'=>$prefix,'type'=>'','length'=>-1),
            array('name'=>':INCOME_TYPE_IN','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':CURR_ID_IN','value'=>$curr_id,'type'=>'','length'=>-1),
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':TB_CASE_IN','value'=>"{$case}",'type'=>'','length'=>-1),
            array('name'=>':R_ACOUNT_ID_IN','value'=>"{$account_id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );

        $result = $this->New_rmodel->general_get('TREASURY_PKG','ACCOUNT_PERMISSION_GET',$params);
        return $result;
    }


}