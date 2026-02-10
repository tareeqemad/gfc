<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 07/02/19
 * Time: 09:07 ص
 */

class LV_Network_model extends MY_Model
{

    var $PKG_NAME= "GIS_WORK_PKG";
    var $TABLE_NAME= "LV_Network";
    /************************************get list function*****************************/
    function get_list($sql,$offset,$row){
        $this->conn = new GISConn();
        $cursor = $this->db->get_cursor();
        $params = array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'LV_NETWORK_A_LIST',$params);
        return $result[(int)$cursor];
    }
    /**********************************edit function*****************************************/
    function edit($data){
        $this->conn = new GISConn();
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'LV_NETWORK_A_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    /*****************************************************************************************************/
    function get_LV_Network($id= 0){
        $this->conn = new GISConn();
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->conn->excuteProcedures($this->PKG_NAME,'LV_NETWORK_A_GET',$params);
        //var_dump($result);
        //die();
        return $result[(int)$cursor];
    }
    /*****************************************get_list_network************************************************************/
}
