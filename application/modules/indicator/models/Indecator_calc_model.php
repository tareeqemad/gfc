<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 15/10/19
 * Time: 12:31 م
 */
class indecator_calc_model extends MY_Model{
    var $PKG_NAME= "INDECATOR_PKG";
    var $TABLE_NAME= 'INDECATOR_CALC_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    /*******************************************************************************************/

    function get_details_calc_all($id= 0,$calc_type){

        $params =array(
            array('name'=>':INDECATOR_CALC_IN','value'=>"{$id}",'type'=>'','length'=>-1),
            array('name'=>':CALC_TYPE_IN','value'=>"{$calc_type}",'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_GET',$params);

        return $result;
    }

    /*******************************************************************************************/


    function create_calc_indecatior($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME,$this->TABLE_NAME.'_INSERT',$params);

        return $result['MSG_OUT'];
    }

    /*******************************************************************************************/


    function edit_calc_indecatior($data){
        $params =array();

        $this->_extract_data($params,$data);

        $result = $this->conn->excuteProcedures($this->PKG_NAME,$this->TABLE_NAME.'_UPDATE',$params);

        return $result['MSG_OUT'];
    }



}
