<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 21/12/14
 * Time: 09:06 ص
 */

class Bills_payments_model extends MY_Model{
    var $PKG_NAME= "BILLS_PKG";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($date,$id= null){

        $params =array(
            array('name'=>':DATE_IN','value'=>$date ,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_IN','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'CACHIER_PAYMENTS_BRANCH_LIST',$params);
        return $result;
    }

    function get_details($id= null){

        $params =array(

            array('name'=>':BANK_VOUCHER_NO_IN','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'CACHIER_PAYMENTS_LIST',$params);
        return $result;
    }


}