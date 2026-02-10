<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 21/09/22
 * Time: 08:30 ص
 */

class Insurance_and_pensions_model extends MY_Model {
    var $PKG_NAME = "PAYROLL_STATEMENT_PKG";
    var $TABLE_NAME = 'INSURANCE_AND_PENSIONS';

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
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_LIST',$params);
        return $result;
    }


}