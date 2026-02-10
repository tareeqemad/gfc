<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Cpanel_model extends MY_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Root/New_rmodel');
    }
    
    function count_info_all(){

        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','COUNT_INFO_ALL',$params);

        return $result;
    }


    function count_requests_tb(){


        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','COUNT_REQUESTS_TB',$params);

        return $result;
    }



    function count_work_order_requests(){

        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','COUNT_WORK_ORDER_REQUESTS',$params);

        return $result;
    }


    function count_work_order_asss_requests(){

        $params =array(

            array('name'=>':REF_CUR_OUT','value'=>'cursor','type'=>OCI_B_CURSOR),
            array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>-1),
        );

        $result = $this->New_rmodel->general_get('TECHNICAL_PKG','COUNT_WORK_ORDER_ASSS_REQUESTS',$params);

        return $result;
    }



}