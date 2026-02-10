<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 01/12/22
 * Time: 09:30 ص
 */

class Total_subscriptions_model extends MY_Model{

    var $PKG_NAME= "REPORT_PKG";
    var $TABLE_NAME= 'BRANCH_SUBSCRIBERS';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($sql ,$the_month ,$from_the_date,$to_the_date ,$subscriber_type){
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':FOR_MONTH_IN','value'=>$the_month,'type'=>'','length'=>-1),
            array('name'=>':FROM_DATE_IN','value'=>$from_the_date,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE_IN','value'=>$to_the_date,'type'=>'','length'=>-1),
            array('name'=>':SUBSCRIBER_TYPE_IN','value'=>$subscriber_type,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_bills_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

}
