<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 20/12/14
 * Time: 09:36 ص
 */

class Banks_info extends MY_Controller{

    function __construct(){
        parent::__construct();

        $this->load->model('bank_info_model');
    }

    function public_get_banks(){

        $curr_id = $this->input->post('curr_id');
        $result = $this->bank_info_model->get_list($curr_id);

        $this->return_json($result);

    }
}