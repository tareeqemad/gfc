<?php

class Report_model extends MY_Model
{
    var $PKG_NAME= "DA3EM_PKG";

    function get_main_categories(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_INDICATOR_MAINCATEGORIES',$params);
        return $result[(int)$cursor];
    }


    function get_sub_categories(){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_INDICATOR_SUBCATEGORIES',$params);
        return $result[(int)$cursor];
    }

    function get_branch_managers($user_id,$month){
        $cursor = $this->db->get_cursor();
        $params =array(
            array('name'=>':user_id','value'=>$user_id,'type'=>SQLT_CHR),
            array('name'=>':FOR_MONTH','value'=>$month,'type'=>SQLT_CHR),
            array('name'=>':REF_CUR_OUT','value'=>$cursor,'type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'GET_BRANCH_MANAGER',$params);
        return $result[(int)$cursor];
    }

    function insert_indicator($data){
        $params =array(
            array('name'=>':BRANCH_ID','value'=>$data['branch_id'],'type'=>SQLT_INT),
            array('name'=>':FOR_MONTH','value'=>$data['month'],'type'=>SQLT_CHR),
            array('name'=>':INDICATOR_NAME','value'=>$data['indicator_id'],'type'=>SQLT_INT),
            array('name'=>':INDICATOR_VALUE','value'=>$data['value'],'type'=>''),
            array('name'=>':CREATED_USER','value'=>$data['user_id'],'type'=>SQLT_CHR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT')
        );
        $result = $this->conn->excuteProcedures($this->PKG_NAME, 'BRANCH_REPORT_INSERT',$params);
        return $result['MSG_OUT'];
    }

}