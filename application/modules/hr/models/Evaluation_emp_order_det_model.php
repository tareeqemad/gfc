<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 10/07/16
 * Time: 09:11 ص
 */

class Evaluation_emp_order_det_model extends MY_Model{

    var $PKG_NAME= "HR_PKG";
    var $TABLE_NAME= 'EVAL_EMP_ORDER_DET';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get_list($id= 0,$emp_no= 0,$obj= 0){
        
        $params =array(
            array('name'=>':EVALUATION_ORDER_SERIAL','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':EMP_NO','value'=>$emp_no ,'type'=>'','length'=>-1),
            array('name'=>':OBJECTION','value'=>$obj ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET_LIST',$params);
        return $result;
    }

    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_INSERT',$params);
        return $result['MSG_OUT'];
    }

    /* تعديل الدرجة قبل الاعتماد */
    function edit($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UPDATE_MARK',$params);
        return $result['MSG_OUT'];
    }


/*	function edit_audit_marke($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_MARK_ADUIT',$params);
        return $result['MSG_OUT'];
    }*/
   
    /*  لجنة التظلم */
    function gr_objection($id,$mark,$hint){
        $params =array(
            array('name'=>':EVALUATION_FORM_ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GR_OBJECTION_MARK','value'=>$mark,'type'=>'','length'=>-1),
            array('name'=>':GR_OBJECTION_HINT','value'=>$hint,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_GR_OBJECTION',$params);
        return $result['MSG_OUT'];
    }

    /* لجنة التدقيق */
/*    function audit($id,$mark,$hint){
        $params =array(
            array('name'=>':EVALUATION_FORM_ID','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':AUDIT_MARK','value'=>$mark,'type'=>'','length'=>-1),
            array('name'=>':AUDIT_HINT','value'=>$hint,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500),
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_AUDIT',$params);
        return $result['MSG_OUT'];
    }*/

    /*  طلب تظلم */
    function objec_req($id,$objec_hint){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':OBJECTION_HINT','value'=>$objec_hint,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_REQ_OBJ',$params);
        return $result['MSG_OUT'];
    }


    /* تعديل الدرجة من لجنة التظلم */
    function edit_objec_mark($id,$mark,$hint){
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':GR_OBJECTION_MARK','value'=>$mark,'type'=>'','length'=>-1),
            array('name'=>':GR_OBJECTION_HINT','value'=>$hint,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
            $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_UP_MARK_OBJ',$params);
        return $result['MSG_OUT'];
    }

    /* تعديل الدرجة من لجنة التدقيق */
    function edit_audit_mark($id,$element_id,$mark,$hint,$old_mark_calc,$evaluation_order_serial,$extra_order_serial){
        $hint=($hint==null)?'-':$hint;
        $params =array(
            array('name'=>':SER','value'=>$id,'type'=>'','length'=>-1),
            array('name'=>':ELEMENT_ID','value'=>$element_id,'type'=>'','length'=>-1),
            array('name'=>':AUDIT_MARK','value'=>$mark,'type'=>'','length'=>-1),
            array('name'=>':AUDIT_HINT','value'=>$hint,'type'=>'','length'=>-1),
            array('name'=>':OLD_MARK_CALC','value'=>$old_mark_calc,'type'=>'','length'=>-1),
            array('name'=>':EVALUATION_ORDER_SERIAL','value'=>$evaluation_order_serial,'type'=>'','length'=>-1),
            array('name'=>':EXTRA_ORDER_SERIAL','value'=>$extra_order_serial,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, $this->TABLE_NAME.'_MARK_ADUIT',$params);
        return $result['MSG_OUT'];
    }

}