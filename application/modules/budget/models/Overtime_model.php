<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/18/14
 * Time: 8:34 AM
 */
class Overtime_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function get_list($USER_NO,$MONTH = null,$YEAR =null){

        $params =array(
            array('name'=>':EMP_NO_IN','value'=>$USER_NO ,'type'=>'','length'=>-1),
            array('name'=>':MONTH_IN','value'=>$MONTH ,'type'=>'','length'=>-1),
            array('name'=>':YEAR_IN','value'=>$YEAR,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('BUDGET_PKG','BUDGET_OVERTIME_TB_GET_LIST', $params);
        return $result;
    }

    function get_list_history($USER_NO,$YEAR =null,$MONTH = null){

        $params =array(
            array('name'=>':EMP_NO_IN','value'=>$USER_NO ,'type'=>'','length'=>-1),
            array('name'=>':YEAR_IN','value'=>$YEAR,'type'=>'','length'=>-1),
            array('name'=>':MONTH_IN','value'=>$MONTH ,'type'=>'','length'=>-1),

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->New_rmodel->general_get('BUDGET_PKG','BU_OVERTIME_HIST_TB_GET_LIST', $params);
        return $result;
    }


    /**
     * @param $data
     *
     * update exists permission ..
     *
     */
    function create($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('BUDGET_PKG','BUDGET_OVERTIME_TB_INSERT',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $data
     *
     * update adopt ..
     *
     */
    function update_adapt($data){
        $params =array();
        $this->_extract_data($params,$data);
        $result = $this->conn->excuteProcedures('BUDGET_PKG','BUDGET_OVERTIME_TB_ADOPT',$params);

        return $result['MSG_OUT'];
    }

    /**
     * @param $id
     * delete account ..
     */
    function delete($EMP_NO,$MONTH){

        $params =array(
            array('name'=>':EMP_NO_IN','value'=>$EMP_NO,'type'=>'','length'=>-1),
            array('name'=>':MONTH_IN','value'=>$MONTH,'type'=>'','length'=>-1),

            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->conn->excuteProcedures('BUDGET_PKG','BUDGET_OVERTIME_TB_DELETE',$params);

        return $result['MSG_OUT'];

    }

}