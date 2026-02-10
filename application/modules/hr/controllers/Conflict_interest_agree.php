<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/06/2023
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Conflict_interest_agree extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/New_rmodel');
    }

    function insert(){
        $params =array(
            array('name'=>'emp_no','value'=>$this->user->emp_no,'type'=>'','length'=>-1),
        );
        $result = $this->New_rmodel->general_transactions('HR_REP_PKG','CONFLICT_INTEREST_AGREE_INSERT',$params);
        if(intval($result)==1){
            $this->user->conflict_interest= 1;
        }
        echo $result;
    }

    function show_endorsement(){
        $data['emp_no'] =  $this->user->emp_no;
        $data['emp_name'] =  $this->user->fullname;
        $data['content']='conflict_interest_agree_show';
        $this->load->view('template/view', $data);
    }

}