<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class subscribers_model extends MY_Model{
    var $PKG_NAME= "LAW_PKG";
    var $TABLE_NAME= 'ISSUE_GEDCO_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list_sub($sql,$offset,$row){

        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('LAW_PKG','LAW_SUBSCRIBER_INFO_TB_LIST',$params);
       // var_dump($result);
        //die;
        return $result;
    }


    /*****************************************************************************************************/







}
