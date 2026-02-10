<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 31/05/15
 * Time: 10:07 ص
 */

class Maps extends MY_Controller{
    function  __construct(){
        parent::__construct();
    }
    function index(){}

    function public_map($lng ,$lut){

        add_js('gmaps.js');

        $data['content']='map_show';
        $data['lng']=$lng;
        $data['lut']=$lut;

        $this->load->view('template/view',$data);
    }

    function public_map_location($location){

        add_js('gmaps.js');

        $data['content']='map_show';
        $data['location']=$location;

        $this->load->view('template/view',$data);
    }
}