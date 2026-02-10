<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/09/15
 * Time: 10:57 ص
 */

class add_items_model extends MY_Model{
    var $PKG_NAME= "BUDGET_PKG";
    //var $TABLE_NAME= '';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_classes($section_no=0, $grand_id=0, $parent_id=0){

        $params =array(
            array('name'=>':SECTION_NO_IN','value'=>$section_no,'type'=>'','length'=>-1),
            array('name'=>':GRAND_ID_IN','value'=>$grand_id,'type'=>'','length'=>-1),
            array('name'=>':PARENT_ID_IN','value'=>$parent_id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'GET_CLASSES_FOR_BUDGET_SECTION', $params);
        return $result;
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BUDGET_ITEMS_TB_INSERT_CLASS', $params);
        return $result['MSG_OUT'];
    }

}