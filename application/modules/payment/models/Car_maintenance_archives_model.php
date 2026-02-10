<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/10/22
 * Time: 13:30 ص
 */

class Car_maintenance_archives_model extends MY_Model {
    var $PKG_NAME = "FLEET_PKG";
    var $TABLE_NAME = 'MASTER_CAR_MAINTAIN_TB';

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
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'MASTER_CAR_MAINTAIN_TB_LIST',$params);
        return $result;
    }

}