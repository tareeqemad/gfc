<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 15/02/2020
 * Time: 01:45 م
 */
class bouns_equivalent_model extends MY_Model
{
    var $PKG_NAME= "TRANSACTION_PKG";
    var $TABLE_NAME= 'BOUNS_EQUIVALENT';
    
    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'REWARD_REQUESTS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    function get_list($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'REWARD_REQUESTS_TB_LIST',$params);
        return $result;
    }


    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'REWARD_REQUESTS_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }


    function adopt($ser,$agree_ma,$note=''){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':AGREE_MA','value'=>$agree_ma,'type'=>'','length'=>-1),
            array('name'=>':NOTE','value'=>$note,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'REWARD_REQUESTS_ADOPT_TB_ADOPT',$params);
        return $result['MSG_OUT'];
    }


    function unadopt($ser,$agree_ma,$note=''){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':AGREE_MA','value'=>$agree_ma,'type'=>'','length'=>-1),
            array('name'=>':NOTE','value'=>$note,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'REWARD_REQUESTS_ADOPT_UNADOPT',$params);
        return $result['MSG_OUT'];
    }


    function get($id=0)
    {
        
        $params = array(
            array('name' => ':SER', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'REWARD_REQUESTS_ADOPT_TB_GET', $params);
        return $result;
    }



    //تحويل الى اللجنة
     function transfer_comm($ser,$committee_no){
         $params =array(
             array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
             array('name'=>':COMMITTEE_NO','value'=>$committee_no,'type'=>'','length'=>-1),
             array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
         );
         $result = $this->conn->excuteProcedures($this->PKG_NAME, 'REWARD_REQUESTS_TB_TRANS_COMM',$params);
         return $result['MSG_OUT'];
     }



    function adopt_comm($id, $committee_case, $committee_note,$case){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':COMMITTEE_CASE','value'=>$committee_case,'type'=>'','length'=>-1),
            array('name'=>':COMMITTEE_NOTE','value'=>$committee_note,'type'=>'','length'=>-1),
            array('name'=>':AGREE_MA','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'REWARD_REQUESTS_TB_ADOPT_COMM',$params);
        return $result['MSG_OUT'];
    }

}
