<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 12:33 م
 */

class Room_model extends MY_Model
{

    var $PKG_NAME= "GIS_WORK_PKG";
    var $TABLE_NAME= "ROOM_TB";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    /************************************get list function*****************************/
    function get_list($sql,$offset,$row){
        $this->conn = new GISConn();

        $params = array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ROOM_LIST',$params);
        // var_dump($result);
        // die();
        return $result;
    }
    /**********************************edit function*****************************************/
    function edit($data){
        $this->conn = new GISConn();
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'ROOM_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    /*****************************************************************************************************/
    function get_Room($id= 0){
        $this->conn = new GISConn();

        $params =array(
            array('name'=>':ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );

        $result = $this->New_rmodel->general_get($this->PKG_NAME,'ROOM_GET',$params);
        // var_dump($result);
        //die();
        return $result;
    }
    /*****************************************get_list_network************************************************************/
}