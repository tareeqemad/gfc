<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 12/02/19
 * Time: 11:08 ص
 */

 class users_model extends MY_Model
 {
     var $PKG_NAME= "ADMIN_PKG";
     var $TABLE_NAME= "USERS";

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
         $result = $this->New_rmodel->general_get($this->PKG_NAME,'USERS_PROG_TB_LIST',$params);
         //var_dump($result);
         //die();
         return $result;;
     }
     /**********************************edit function*****************************************/
     function edit($data){
         $this->conn = new GISConn();
         $params =array();
         $this->_extract_data($params,$data);
         $result = $this->conn->excuteProcedures($this->PKG_NAME,'CHANGE_PW_USER',$params);
            //var_dump($result);
              //die;
         return $result['MSG_OUT'];
     }
     /*****************************************************************************************************/
     function get_users($id= 0){
         $this->conn = new GISConn();

         $params =array(
             array('name'=>':ID_IN','value'=>"{$id}",'type'=>SQLT_CHR,'length'=>-1),
             array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
             array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
         );
         $result = $this->New_rmodel->general_get($this->PKG_NAME,'USERS_PROG_TB_GET',$params);
        //var_dump($result);
        //die();
         return $result;;
     }
     /*********************************create function************************************************/
     function create($data){
         $this->conn = new GISConn();
         $params =array();
         $this->_extract_data($params,$data);
         $result = $this->conn->excuteProcedures($this->PKG_NAME,'CREATE_USER',$params);
         return $result['MSG_OUT'];
     }
     /*********************************status function**************************************************/
     function adopt_info($id,$case){
         $this->conn = new GISConn();
         $params =array(
             array('name'=>':ID_IN','value'=>$id,'type'=>'','length'=>-1),
             array('name'=>':STATUS_IN','value'=>$case,'type'=>'','length'=>-1),
             array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
         );
         $result = $this->conn->excuteProcedures($this->PKG_NAME,'LOCKED_USER',$params);

         return $result['MSG_OUT'];

     }
     /*********************************************************************************************/


 }