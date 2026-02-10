<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * Holders Equipments: Ahmed Barakat
 * Date: 29/07/15
 * Time: 09:15 ص
 */

class itemCollections_model extends MY_Model{

    /**
     * @return array
     *
     * return all Holders Equipments data ..
     */
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_all(){

        $params =array(

            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','ITEM_COLLECTION_REP_GET_ALL',$params);

        return $result;
    }


    /**
     * @param int $id
     * @return mixed
     * return one Holders Equipments ..
     */
    function get($id = 0){

        $params =array(
            array('name'=>':HOLDER_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','ITEM_COLLECTION_REP_GET',$params);

        return $result;
    }


    /**
     * @param $data
     *
     * update exists Holders Equipments ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','ITEM_COLLECTION_REP_UPDATE',$params);


        return $result['MSG_OUT'];
    }




}