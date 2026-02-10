<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/11/17
 * Time: 09:22 ص
 */
class Complaints extends MY_Controller{

    function  __construct(){
        parent::__construct();
		
    }
	 function index(){
       
		redirect('http://gmt.gedco.ps/MinistryApp');
    }

}

?>
