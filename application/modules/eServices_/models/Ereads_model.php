<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/06/18
 * Time: 11:56 ص
 */

class EReads_model extends MY_Model{
    var $PKG_NAME= "ESERVICES_PKG";
   function SUBSCRIBER_READS_GET_LIST($sql,$offset,$row){

        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':INXSQL','value'=>$sql,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'SUBSCRIBER_READS_GET_LIST',$params);

        return $result[(int)$cursor];
    }
}
