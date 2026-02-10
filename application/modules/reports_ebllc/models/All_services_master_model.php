<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 06/03/23
 * Time: 09:00 ص
 */

class All_services_master_model extends MY_Model{

    var $PKG_NAME= "REPORT_PKG";
    var $TABLE_NAME= 'ALL_SERVICES_MASTER';

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

    function getID($id)
    {
        $params = array(
            array('name' => ':ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_bills_get($this->PKG_NAME,'SERVICES_TYPES_BILLS_GET',$params);
        return $result;
    }

}
