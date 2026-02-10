<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 13/12/22
 * Time: 09:30 ص
 */

class Billing_data_model extends MY_Model{

    var $PKG_NAME= "REPORT_PKG";
    var $TABLE_NAME= 'BILLS';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($for_month,$sql,$offset,$row){
        $params =array(
            array('name'=>':FOR_MONTH','value'=>$for_month,'type'=>'','length'=>-1),
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_bills_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

}
