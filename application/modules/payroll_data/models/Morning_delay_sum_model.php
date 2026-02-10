<?php


class  morning_delay_sum_model extends MY_Model
{

    var $PKG_NAME = "TRANSACTION_PKG";

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    /* عرض من ال VM */
    function get_list($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'DELAY_EMP_LIST_VM',$params);
        return $result;
    }




    /*********ترحيل البيانات الى delay_salary************/

    function trans_data($the_month,$branch_id){
        $params =array(
            array('name'=>':THE_MONTH','value'=>$the_month,'type'=>'','length'=>-1),
            array('name'=>':BRANCH_ID','value'=>$branch_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DELAY_EMP_ADOPT_DISCOUNT',$params);
        return $result['MSG_OUT'];
    }

    /*  function get_list($sql, $offset, $row)
      {
          
          $params = array(
              array('name' => ':INXSQL', 'value' => "{$sql}", 'type' => '', 'length' => -1),
              array('name' => ':OFFSET', 'value' => $offset, 'type' => '', 'length' => -1),
              array('name' => ':ROW', 'value' => $row, 'type' => '', 'length' => -1),
              array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
              array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
          );
          $result = $this->New_rmodel->general_get($this->PKG_NAME, 'DELAY_EMP_LIST_VM', $params);
          return $result;
      }*/

}









