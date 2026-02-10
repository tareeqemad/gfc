<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * High Power Partition: Ahmed Barakat
 * Date: 29/07/15
 * Time: 09:15 ص
 */

class HPP_model extends MY_Model{

    /**
     * @return array
     *
     * return all High Power Partition data ..
     */
    function get_list($sql,$offset,$row){

        $cursor = $this->db->get_cursor();

        $params =array(


            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':offset','value'=>"{$offset}",'type'=>'','length'=>-1),
            array('name'=>':row','value'=>"{$row}",'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','HIGH_POWER_PARTITION_TB_LIST',$params);

        return $result[(int)$cursor];
    }


    /**
     * @param int $id
     * @return mixed
     * return one High Power Partition ..
     */
    function get($id = 0){

        $cursor = $this->db->get_cursor();

        $params =array(
            array('name'=>':Partition_ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','HIGH_POWER_PARTITION_TB_GET',$params);

        return $result[(int)$cursor];
    }

  

    /**
     * @param $data
     *
     * create new High Power Partition ..
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','HIGH_POWER_PARTITION_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }


    /**
     * @param $data
     *
     * update exists High Power Partition ..
     *
     */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','HIGH_POWER_PARTITION_TB_UPDATE',$params);


        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete High Power Partition ..
     */
    function delete($id){

        $params =array(
            array('name'=>':ID_in','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('TECHNICAL_PKG','HIGH_POWER_PARTITION_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }

   
}