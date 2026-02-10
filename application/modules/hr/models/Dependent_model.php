<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 21/02/2019
 * Time: 09:36 ص
 */

class dependent_model extends MY_Model{

    var $PKG_NAME = "DEPENDENT_PKG";
    var $TABLE_NAME = 'DEPENDENT_TB';
    var $GET_E = '';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    /*********************************add _note_request_first******************************/
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DEPENDENT_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function update_dates($id, $from_date, $to_date){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':FROM_DATE','value'=>$from_date,'type'=>'','length'=>-1),
            array('name'=>':TO_DATE','value'=>$to_date,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DEPENDENT_TB_UPDATE_DATES',$params);
        return $result['MSG_OUT'];
    }

    function create_mtit($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DEPENDENT_FROM_MTIT_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }

    function get_mtit($id= 0){
        
        $params =array(
            array('name'=>':ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'DEPENDENT_FROM_MTIT_TB_GET',$params);
        return $result;
    }
    /************************************************************************************/
    function get($id= 0){
        
        $params =array(
            array('name'=>':ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }
    /************************************************************************************/
    function get_all($emp_no=0)
    {
        
        $params = array(
            array('name' => ':EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'DEPENDENT_TB_GET_ALL', $params);
        return $result;
    }
    /****************************************************************************************/

    function relatives_2019($emp_no )
    {
        
        $params = array(
            array('name' => ':EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
            array('name' => ':GET_ROW_LOG', 'value' => 'cursor', 'type' => OCI_B_CURSOR),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => '', 'length' => 500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'RELATIVES_2019_GET', $params);
        return $result;
    }
    /*******************************************************************************************/

    function adopt($id, $case){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ADOPT','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'DEPENDENT_TB_ADOPT',$params);
        return $result['MSG_OUT'];
    }

    /*************************************************************************************************/
    function delete($id){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'DEPENDENT_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }

}
