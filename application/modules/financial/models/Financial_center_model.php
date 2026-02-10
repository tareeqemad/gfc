<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/14/14
 * Time: 9:17 AM
 */
class Financial_center_model extends MY_Model{

    /**
     * @return array
     *
     * return all accounts data ..
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

        $result = $this->New_rmodel->general_get('financial_pkg','FINANCIAL_CENTER_GET_ALL',$params);

        return $result;
    }

    /**
     * @param int $parent
     * @return array
     * get list of accounts deepened on parent id
     */
    function getList($parent = 0,$user = null,$level  = 0,$curr_id = null){



        $params =array(
            array('name'=>':ACOUNT_PARENT_ID_in','value'=>"{$parent}",'type'=>'','length'=>-1),
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),

            array('name'=>':Level_IN','value'=>$level,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('financial_pkg','FINANCIAL_CENTER_GET_LIST',$params);
        return $result;
    }


    /**
     * @param int $parent
     * @return array
     * get list of accounts deepened on parent id
     */
    function getList2($user = null,$level  = 0,$curr_id = null){



        $params =array(

            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':CURR_ID_in','value'=>$curr_id,'type'=>'','length'=>-1),
            array('name'=>':Level_IN','value'=>$level,'type'=>'','length'=>-1),

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('financial_pkg','FINANCIAL_CENTER_GET_LIST_TYPE2',$params);
        return $result;
    }


    function getList2B($branch = null,$level  = 0,$curr_id = null){



        $params =array(

            array('name'=>':BRANCH_IN','value'=>"{$branch}",'type'=>'','length'=>-1),
            array('name'=>':CURR_ID_in','value'=>$curr_id,'type'=>'','length'=>-1),
            array('name'=>':Level_IN','value'=>$level,'type'=>'','length'=>-1),

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('financial_pkg','FINANCIAL_CENTER_GET_LIST_TYPE2B',$params);
        return $result;
    }
    /**
     * @param int $parent
     * @return array
     * get list of accounts deepened on parent id
     */
    function getList_S($parent = 0,$user = null,$level  = 0,$curr_id = null,$type = null){



        $params =array(
            array('name'=>':ACOUNT_PARENT_ID_in','value'=>"{$parent}",'type'=>'','length'=>-1),
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':CURR_ID_in','value'=>$curr_id,'type'=>'','length'=>-1),
            array('name'=>':ACOUNT_TYPE_in','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':Level_IN','value'=>$level,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('financial_pkg','FINANCIAL_CENTER_GET_LIST_S',$params);
        return $result;
    }
    /**
     * @param int $parent
     * @return array
     * get list of accounts deepened on parent id
     */
    function getList_Table($parent = 0,$user = null,$level  = 0,$curr_id = null,$type = null){



        $params =array(
            array('name'=>':ACOUNT_PARENT_ID_in','value'=>"{$parent}",'type'=>'','length'=>-1),
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':CURR_ID_in','value'=>$curr_id,'type'=>'','length'=>-1),
            array('name'=>':ACOUNT_TYPE_in','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':Level_IN','value'=>$level,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('MV_PKG','FINANCIAL_CENTER_GET_LIST_Table',$params);
        return $result;
    }

    /**
     * @param int $parent
     * @return array
     * get list of accounts deepened on parent id
     */
    function getList_Table_balance($parent = 0,$user = null,$level  = 0,$curr_id = null,$type = null){



        $params =array(
            array('name'=>':ACOUNT_PARENT_ID_in','value'=>"{$parent}",'type'=>'','length'=>-1),
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':CURR_ID_in','value'=>$curr_id,'type'=>'','length'=>-1),
            array('name'=>':ACOUNT_TYPE_in','value'=>$type,'type'=>'','length'=>-1),
            array('name'=>':Level_IN','value'=>$level,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('financial_pkg','ACOUNTS_TABLE_BALANCE',$params);
        return $result;
    }


    function getList_parent($parent = 0,$user = null,$level  = 0,$curr_id = null){



        $params =array(
            array('name'=>':ACOUNT_PARENT_ID_in','value'=>"{$parent}",'type'=>'','length'=>-1),
            array('name'=>':USER_ID_IN','value'=>"{$user}",'type'=>'','length'=>-1),
            array('name'=>':CURR_ID_in','value'=>$curr_id,'type'=>'','length'=>-1),
            array('name'=>':Level_IN','value'=>$level,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->New_rmodel->general_get('financial_pkg','FINANCIAL_CENTER_GET_PARENT',$params);
        return $result;

    }
    /**
     * @param int $id
     * @return mixed
     * return one account ..
     */
    function get($id = 0){



        $params =array(
            array('name'=>':ACOUNT_ID_in','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('financial_pkg','FINANCIAL_CENTER_GET',$params);

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
        $result = $this->conn->excuteProcedures('financial_pkg','FINANCIAL_CENTER_INSERT',$params);
        return $result['MSG_OUT'];
    }

    /**
     * @param $data
     *
     * create new account ..
     */
    function update_adapt($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('financial_pkg','FINANCIAL_CENTER_UPDATE_ADOPT',$params);
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
        $result = $this->conn->excuteProcedures('financial_pkg','FINANCIAL_CENTER_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete account ..
     */
    function delete($id){

        $params =array(
            array('name'=>':ACOUNT_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('financial_pkg','FINANCIAL_CENTER_DELETE',$params);

        return $result['MSG_OUT'];

    }

    /*
     *  check if account exists ..
     *
     */

    function isAccountExists($account_id){


        $rs = $this->get($account_id);

        return count($rs) > 0 && $rs[0]['ACOUNT_TYPE'] == 2;
    }


    function isProjectAccountExists($account_id ,$type = 2){



        $params =array(
            array('name'=>':PROJECT_SERIAL_IN','value'=>"{$account_id}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('PROJECTS_PKG','PROJECTS_ACCOUNTS_TB_GET_BYC',$params);

        $rs = $result;

        return count($rs) > 0 && $rs[0]['PROJECT_ACCOUNT_TYPE'] == $type;
    }


    /**************************** accounts featured views ***************************/

    /**
     * @return array
     *
     * return all chains data ..
     */
    function stm_accounts_get_list($sql,$offset,$row){



        $params =array(

            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('FINANCIAL_FEED_PKG','FINANCIAL_CHAINS_INFO_LIST',$params);

        return $result;
    }





}