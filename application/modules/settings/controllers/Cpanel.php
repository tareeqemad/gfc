<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/2/14
 * Time: 10:00 AM
 */

class Cpanel extends  MY_Controller{

    function __construct(){
        parent::__construct();

    }

    function index(){

        $data['title']='لوحة التحكم - النظام المالي';
        $data['content']='cpanel_index';
        $data['module'] = $this->module;

        $this->load->view('template/template',$data);
    }
}