<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 29/12/22
 * Time: 12:20 ص
 */

class Payment_status_model extends MY_Model{

    var $PKG_NAME= "REPORT_PKG";
    var $TABLE_NAME= 'BANK_ORG_PAR';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($branch_id,$payment_type , $the_month, $from_date, $to_date, $offset,$row){
        $params =array(
            array('name'=>':BRANCH','value'=>$branch_id,'type'=>'','length'=>-1),
            array('name'=>':PD_TYPE','value'=>$payment_type,'type'=>'','length'=>-1),
            array('name'=>':FOR_MONTH','value'=>$the_month,'type'=>'','length'=>-1),
            array('name'=>':FROM_DATE','value'=>$from_date,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>$to_date,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_bills_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

    function get($bank_type ,$branch ,$for_month ,$from_date ,$to_date ,$pd_type)
    {

        $params = array(
            array('name' => ':BANK_TYPE', 'value' => $bank_type, 'type' => '', 'length' => -1),
            array('name' => ':BRANCH', 'value' => $branch, 'type' => '', 'length' => -1),
            array('name' => ':FOR_MONTH', 'value' => $for_month, 'type' => '', 'length' => -1),
            array('name' => ':FROM_DATE', 'value' => $from_date, 'type' => '', 'length' => -1),
            array('name' => ':TO_DATE', 'value' => $to_date, 'type' => '', 'length' => -1),
            array('name' => ':PD_TYPE', 'value' => $pd_type, 'type' => '', 'length' => -1),
            array('name'=>  ':REF_CURSOR_OUT', 'value'=> 'cursor', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );

        $result = $this->New_rmodel->general_bills_get($this->PKG_NAME,'BANK_ORG_PAR_DET_GET',$params);
        return $result;
    }

}
