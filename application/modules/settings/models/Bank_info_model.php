<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 20/12/14
 * Time: 09:37 ص
 */

class Bank_info_model extends MY_Model{

    var $PKG_NAME= "SETTING_PKG";
    var $TABLE_NAME= 'BANK_CHECKS_TRANS_INFO';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_list($curr_id= 0){
        
        $params =array(
            array('name'=>':CURR_ID_IN','value'=>$curr_id ,'type'=>SQLT_INT,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }
}