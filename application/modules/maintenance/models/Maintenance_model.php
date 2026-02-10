<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 27/07/2019
 * Time: 10:30 ص
 */

class maintenance_model extends MY_Model {
    var $PKG_NAME= "MAINTENANCE_PKG";
    var $TABLE_NAME= 'MAINTENANCE_REQ_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    //for create request mentaince
    function create($data)
    {
        $params = array();
        $this->_extract_data($params, $data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'MAINTENANCE_REQ_TB_INSERT', $params);
        return $result['MSG_OUT'];
    }

    //for get pledges by emp_no on change function
    function get($emp_no = ''){
        
        $params =array(
            array('name'=>':EMP_NO_IN','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('MAINTENANCE_PKG','CUSTOMERS_PLEDGES_GET_B_EMP_NO',$params);
        return $result;
    }

    //for get request maintencae
    function get_req($id){
        
        $params =array(
            array('name'=>':SER_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('MAINTENANCE_PKG','MAINTENANCE_REQ_TB_GET',$params);
        return $result;
    }

        //for get class id to request

    //for query
    function get_list($sql,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,'MAINTENANCE_REQ_TB_LIST2',$params);
        return $result;
    }
    //for query update
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    //for delete
    function delete($id){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME,'MAINTENANCE_REQ_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }



    //

    function adopt($id, $case){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':STATUS','value'=>$case,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'MAINTENANCE_REQ_TB_ADOPT',$params);
        return $result['MSG_OUT'];
    }
    ///


    function AddFinalSolve($ser,$solve_problem1 ,$status){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':SOLVE_PROBLEM','value'=>$solve_problem1,'type'=>'','length'=>-1),
            array('name'=>':STATUS','value'=>$status,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'MAINTENANCE_REQ_TB_UPDATE_SOL',$params);
        return $result['MSG_OUT'];
    }



    function insert_Receipt($ser,$receipt_user,$receipt_date,$receipt_note){
        $params =array(
            array('name'=>':SER','value'=>$ser,'type'=>'','length'=>-1),
            array('name'=>':RECEIPT_USER','value'=>$receipt_user,'type'=>'','length'=>-1),
            array('name'=>':RECEIPT_DATE','value'=>$receipt_date,'type'=>'','length'=>-1),
            array('name'=>':RECEIPT_NOTE','value'=>$receipt_note,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'MAINTENANCE_REQ_TB_RECEIPT',$params);
        return $result['MSG_OUT'];
    }





}
