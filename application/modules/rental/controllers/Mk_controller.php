<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/08/17
 * Time: 12:40 م
 */

// id,name,...

class NAME__ extends MY_Controller{

    function  __construct(){
        parent::__construct();
    }

    function public_create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

        }else{
            $data['content']='NAME___show';
            $data['title']='اضافة';
            $data['action'] = 'index';
            $data['isCreate']= true;
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }


    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['con__'] = $this->constant_details_model->get_list(0);
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }

}