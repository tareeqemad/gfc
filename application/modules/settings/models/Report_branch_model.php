<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 31/12/14
 * Time: 09:23 ص
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class report_branch_model extends MY_Model{
    var $PKG_NAME= "SETTING_PKG";
    var $TABLE_NAME= 'REPORTS_TB';
    var $TABLE_NAMEs= 'REPORT_NAME_TB';
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_all(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_ALL',$params);
        return $result;
    }
    function type_get_all(){
        
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAMEs.'_GET_ALL',$params);
        return $result;
    }
}
?>