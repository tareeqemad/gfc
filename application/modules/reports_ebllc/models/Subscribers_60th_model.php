<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/02/23
 * Time: 09:00 ص
 */

class Subscribers_60th_model extends MY_Model{

    var $PKG_NAME= "REPORT_PKG";
    var $TABLE_NAME= 'SUBSCRIBERS_60TH';

    function __construct()
    {
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($sql,$offset,$row){
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_bills_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

    function get($subscriber= 0 ,$from_month = 0 ){
        $params =array(
            array('name'=>':SUBSCRIBER','value'=>$subscriber ,'type'=>'','length'=>-1),
            array('name'=>':FROM_MONTH','value'=>$from_month ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_bills_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

}
