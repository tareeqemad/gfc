<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 27/10/14
 * Time: 12:22 م
 */

class System_info_model extends MY_Model{
    var $PKG_NAME= "SETTING_PKG";
    var $TABLE_NAME= 'SYSTEM_INFO_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id= 0){
        
        $params =array(
            array('name'=>':SYSTEM_INFO_ID_IN','value'=>$id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    /**
     * @param int $id
     * @return mixed
     *
     * GET ACCOUNT ID FROM BRANCH ACCOUNTS INFO TB
     */
    function get_account_id($source= 0){
        
        $params =array(
            array('name'=>':SYSTEM_INFO_ID_IN','value'=>$source ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'BRANCH_ACCOUNT_INFO_ACCOUNT',$params);
        return $result;
    }

    function get_all(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ALL',$params);
        return $result;
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete($id){
        $params =array(
            array('name'=>':SYSTEM_INFO_ID_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_DELETE',$params);
        return $result['MSG_OUT'];
    }


    function get_table_count($sql){
        
        $params =array(
            array('name'=>':XSQL','value'=>$sql ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('QF_PKG','GET_COUNT_TAB',$params);
        return $result;
    }


    function get_table_count_bills($sql){

        $params =array(
            array('name'=>':XSQL','value'=>$sql ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_bills_get('REPORT_PKG','GET_COUNT_TAB',$params);
        return $result;
    }

    function get_entry_users($tb, $where= null){
        
        $params =array(
            array('name'=>':TB_NAME','value'=>$tb ,'type'=>'','length'=>-1),
            array('name'=>':WHERE_SQL','value'=>$where ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('QF_PKG','GET_ENTRY_USERS', $params);
        return $result;
    }

    function get_emails_by_code($code, $branch= null){
        
        $params =array(
            array('name'=>':CODE_IN','value'=>$code ,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$branch ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('USER_PKG','USER_SEND_EMAIL_GET_LIST', $params);
        return $result;
    }

    function insert_php_log($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'PHP_SYSTEM_ACTION_INSERT',$params);
        return $result['MSG_OUT'];
    }


    function user_notification($url_get , $url_insert  , $message_in,$prev_url = null){
        $params =array(
            array('name'=>':URL_GET','value'=>$url_get,'type'=>'','length'=>-1),
            array('name'=>':URL_INSERT','value'=>$url_insert,'type'=>'','length'=>-1),
            array('name'=>':URL_PREV','value'=>$prev_url,'type'=>'','length'=>-1),
            array('name'=>':MESSAGE_IN','value'=>$message_in,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1),
        );

        $result = $this->conn->excuteProcedures("USER_PKG", 'USER_NOTIFICATION',$params);
        return $result['MSG_OUT'];
    }

    function user_show_notification($user_id,$offset,$row){
        
        $params =array(
            array('name'=>':USER_ID_IN','value'=>$user_id ,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset ,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get('USER_PKG','USER_SHOW_NOTIFICATION',$params);
        return $result;
    }
    function user_notification_update($user_id , $url ){
        $params =array(
            array('name'=>':USER_ID_IN','value'=>$user_id,'type'=>'','length'=>-1),
            array('name'=>':URL_IN','value'=>$url,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1),
        );

        $result = $this->conn->excuteProcedures("USER_PKG", 'USER_NOTIFICATION_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    /**
     * statistic counts of entry data
     * @return mixed
     */
    function  total_sat_rep_tb_list(){ // New_rmodel Done

        $params =array(
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor')
        );
        $result = $this->New_rmodel->general_get('QF_PKG','TOTAL_SAT_REP_TB_LIST',$params,0);
        return $result['CUR_RES'];
    }

    function income_voucher_tb_sat(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1),
        );
        $result = $this->New_rmodel->general_get('STATISTICS_PKG','INCOME_VOUCHER_TB_SAT',$params);
        return $result;
    }

    function financial_payment_tb_sat(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1),
        );
        $result = $this->New_rmodel->general_get('STATISTICS_PKG','FINANCIAL_PAYMENT_TB_SAT',$params);
        return $result;
    }
}
