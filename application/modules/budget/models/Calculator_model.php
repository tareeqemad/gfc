<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 01/10/14
 * Time: 07:27 ص
 */

class Calculator_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }

    function salary_count($year){


        $params =array(
            array('name'=>':MONTH_IN','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );


        $result = $this->New_rmodel->general_get('progress_pkg', 'BUDGET_SALARY_TB_GET_COUNT',$params);

        return $result;

    }

     function salary_calc($year,$no1 =1  ,$no2 =2000){

        $params =array(
            array('name'=>':YEAR_IN','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':NO1','value'=>$no1 ,'type'=>'','length'=>-1),
            array('name'=>':NO2','value'=>$no2 ,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures('EMP_PKG','BUDGET_SALARY_TB_CALC', $params);
        return $result['MSG_OUT'];
    }


    function overtime_calc($year,$no1 =1  ,$no2 =2000){

        $params =array(
            array('name'=>':YEAR_IN','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':NO1','value'=>$no1 ,'type'=>'','length'=>-1),
            array('name'=>':NO2','value'=>$no2 ,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures('EMP_PKG','BUDGET_OVERTIME_TB_CALC', $params);
        return $result['MSG_OUT'];
    }

    function overtime_count($year){

        $params =array(
            array('name'=>':MONTH_IN','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );


        $result = $this->New_rmodel->general_get('progress_pkg', 'BUDGET_OVERTIME_TB_GET_COUNT',$params);

        return $result;
    }


    function budget_history_items_count($year){

        $params =array(
            array('name'=>':YEAR_IN','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );


        $result = $this->New_rmodel->general_get('progress_pkg', 'BUDGET_HISTORY_ITEMS_COUNT',$params);

        return $result;
    }



    function budget_calc($year){

        $params =array(
            array('name'=>':YEAR_IN','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures('BUDGET_PKG','BUDGET_CALC', $params);
        return $result['MSG_OUT'];
    }

    function budget_update_prices($year){

        $params =array(
            array('name'=>':YEAR_IN','value'=>$year ,'type'=>'','length'=>-1),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1)
        );
        $result = $this->conn->excuteProcedures('BUDGET_PKG','BUDGET_UP_PRICES', $params);
        return $result['MSG_OUT'];
    }


}