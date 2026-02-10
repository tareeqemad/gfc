<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends MY_Model
{
    var $PKG_NAME= "DA3EM_PKG";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_all_dashboards(){
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'GET_ALL_DASHBOARDS',$params);
        return $result;
    }

    function get_dashboard($id){
        $params =array(
            array('name'=>':DASHBOARD_ID','value'=>$id,'type'=>SQLT_CHR),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'GET_DASHBOARD',$params);
        return $result;
    }

    function insert_dashboard($data){
        $params =array(
            array('name'=>':TITLE','value'=>$data['DASHBOARD_TITLE'],'type'=>SQLT_CHR),
            array('name'=>':CATEGORY_ID','value'=>$data['CATEGORY_ID'],'type'=>SQLT_CHR),
            array('name'=>':LINK','value'=>$data['DASHBOARD_URL'],'type'=>SQLT_CHR),
            array('name'=>':USERS','value'=>$data['DASHBOARD_USERS'],'type'=>SQLT_CHR),
            array('name'=>':ICON','value'=>$data['ICON'],'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DASHBOARD_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function update_dashboard($data){
        $params =array(
            array('name'=>':DASHBOARD_ID','value'=>$data['ID'],'type'=>SQLT_CHR),
            array('name'=>':DASHBOARD_TITLE','value'=>$data['DASHBOARD_TITLE'],'type'=>SQLT_CHR),
            array('name'=>':CAT_ID','value'=>$data['CATEGORY_ID'],'type'=>SQLT_CHR),
            array('name'=>':DASHBOARD_URL','value'=>$data['DASHBOARD_URL'],'type'=>SQLT_CHR),
            array('name'=>':DASHBOARD_USERS','value'=>$data['DASHBOARD_USERS'],'type'=>SQLT_CHR),
            array('name'=>':DASHBOARD_ICON','value'=>$data['ICON'],'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DASHBOARD_UPDATE',$params);

        return $result['MSG_OUT'];
    }

    function delete_dashboard($id){
        $params =array(
            array('name'=>':DASHBOARD_ID','value'=>$id,'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DASHBOARD_DELETE',$params);
        return $result['MSG_OUT'];

    }
}