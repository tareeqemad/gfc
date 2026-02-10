<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User: mkilani
 * Date: 19/02/2020
 */

class emps_pledges extends MY_Controller{

    var $MODEL_NAME= 'customers_pledges_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

    }

    function public_get_emp_pledges_json($emp_id=0){
        if(intval($emp_id)<99999999 and $emp_id!='all'){
            die('Error emp id');
        }
        $where_sql=' where 1=1 ';
        if($emp_id!='all')
            $where_sql.=" and c.CUSTOMER_ID= ".$emp_id;
        $arr = $this->{$this->MODEL_NAME}->get_list($where_sql, 0 , 9999999 );

        $arr_col= array('CUSTOMER_ID','CUSTOMER_ID_NAME','CLASS_ID','CLASS_NAME','STATUS_NAME','CLASS_TYPE_NAME','AMOUNT','PRICE','PLEDGE_TYPE');

        $arr_res= array();

        foreach($arr as $row){
            $arr_new = $row;
            foreach($row as $key => $value){
                if(!in_array($key, $arr_col))
                    unset($arr_new[$key]);
            }
            $arr_res[]= $arr_new;
        }

        $this->return_json($arr_res);
    }

}
