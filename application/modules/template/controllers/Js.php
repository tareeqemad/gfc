<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 01/08/16
 * Time: 09:46 ص
 */
class Js extends MY_Controller
{

    public $data;

    function __construct()
    {
        parent::__construct();


        $data['action'] = $_GET['js_action'];
        $data['controller'] = $_GET['controller'];
        $this->data = $data;
    }

    function index()
    {
        $this->output->set_header('Content-type: text/javascript');
        $this->load->view($_GET['view'] . '/' . $_GET['controller'] . '_jsview', $this->data);
    }


}