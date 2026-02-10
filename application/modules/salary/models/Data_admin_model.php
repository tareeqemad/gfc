<?php

class data_admin_model extends MY_Model{
    var $PKG_NAME= "SALARY_EMP_PKG";
    var $TABLE_NAME= 'DATA_ADMIN';

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function get($id= 0, $month=0){
        
        $params =array(
            array('name'=>':ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>':MONTH','value'=>$month ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, $this->TABLE_NAME.'_GET',$params);
        return $result;
    }

    function get_list($sql,$sal_con,$offset,$row){
        
        $params =array(
            array('name'=>':INXSQL','value'=>"{$sql}",'type'=>'','length'=>-1),
            array('name'=>':SAL_CON','value'=>$sal_con,'type'=>'','length'=>-1),
            array('name'=>':OFFSET','value'=>$offset,'type'=>'','length'=>-1),
            array('name'=>':ROW','value'=>$row,'type'=>'','length'=>-1),
            array('name'=>':GET_ROW_LOG','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME,$this->TABLE_NAME.'_LIST',$params);
        return $result;
    }

    function get_salary($emp_no= 0, $month=0, $is_add=''){
        
        $params =array(
            array('name'=>':EMP_NO_IN','value'=>$emp_no ,'type'=>'','length'=>-1),
            array('name'=>':MONTH_IN','value'=>$month ,'type'=>'','length'=>-1),
            array('name'=>':IS_ADD_IN','value'=>$is_add ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>'','length'=>500)
        );
        $result = $this->New_rmodel->general_get($this->PKG_NAME, 'DATA_SALARY_GET',$params);
        return $result;
    }

}