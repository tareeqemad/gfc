<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 14/07/16
 * Time: 11:05 ص
 */


if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class budget_tree_model extends MY_Model{
    var $PKG_NAME= "BUDGET_PKG";
    var $TABLE_NAME= 'BUDGET_TREE_VW';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function getList($parent = NULL){

        $params =array(
            array('name'=>':PPARENT_in','value'=>"{$parent}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('BUDGET_PKG','BUDGET_TREE_VW_GET_LIST',$params);
      //  print_r($result);
        return $result;
    }
}
