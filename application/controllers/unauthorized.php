<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/9/14
 * Time: 11:27 AM
 */

class Unauthorized extends MY_Controller{

    function  __construct(){
        parent::__construct();
    }
    function index(){
        echo 'UnAuthorized';
    }

}