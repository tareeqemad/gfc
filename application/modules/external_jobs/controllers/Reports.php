<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2023-07-06
 * Time: 1:16 PM
 */

class Reports extends MY_Controller{

    function  __construct(){
        parent::__construct();
    }

    function index(){
        $data['title']='التقارير';
        $data['content']='reports_index';
        $this->load->view('template/template',$data);
    }

}