<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 22/08/2019
 * Time: 12:30 م
 */
class  maintenance_det_model extends MY_Model {
    var $PKG_NAME= "MAINTENANCE_PKG";
    var $TABLE_NAME= 'MAINTENANCE_REQ_CLAS_TB';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function create_detail($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'MAINTENANCE_REQ_CLAS_TB_INSERT',$params);
        return $result['MSG_OUT'];
    }

    //for get request maintencae
    function get_class_id($id){
        
        $params =array(
            array('name'=>':SER_REQ_IN','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get('MAINTENANCE_PKG','MAINTENANCE_REQ_CLAS_TB_GET',$params);
        return $result;
    }

    function update_det_classid($ser_class_id,$class_id,$comp_name,$cost_main,$solve_problem,$status_class_id){
        $params =array(
            array('name'=>':SER_CLASS_ID','value'=>$ser_class_id,'type'=>'','length'=>-1),
            array('name'=>':CLASS_ID','value'=>$class_id,'type'=>'','length'=>-1),
            array('name'=>':COMP_NAME','value'=>$comp_name,'type'=>'','length'=>-1),
            array('name'=>':COST_MAIN','value'=>$cost_main,'type'=>'','length'=>-1),
            array('name'=>':SOLVE_PROBLEM','value'=>$solve_problem,'type'=>'','length'=>-1),
            array('name'=>':STATUS_CLASS_ID','value'=>$status_class_id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'MAINTENANCE_REQ_CLAS_TB_UPDATE',$params);
        return $result['MSG_OUT'];
    }

    function delete_class_id($id){
        $params =array(
            array('name'=>':SER_CLASS_ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'MAINTENANCE_REQ_CLAS_TB_DELETE',$params);
        return $result['MSG_OUT'];
    }


}
