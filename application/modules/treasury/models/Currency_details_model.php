<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/09/14
 * Time: 01:26 م
 */

class Currency_details_model extends MY_Model{
    var $PKG_NAME= "TREASURY_PKG";
    var $TABLE_NAME= 'CURRENCY_DETAILS_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_list($date= '01/01/2015'){
        
        $params =array(
            array('name'=>':ENTRY_DATE','value'=>'' ,'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LIST', $params);
        return $result;
    }


    function get_all(){
        
        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ALL', $params);
        return $result;
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }
}

?>
