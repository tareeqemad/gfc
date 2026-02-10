<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/03/19
 * Time: 01:37 م
 */
class Gis extends  MY_Controller{

    function __construct(){
        parent::__construct();

    }

    function index(){

       $this->load->view('Simple_show');
    }
	function Advance(){

       $this->load->view('Advance_show');
    }
		function Editable_LV_Customers(){

       $this->load->view('LV_Customers_Editable');
	   //$this->load->view('MCustomer_Show');
    }
	function Show_LV_Customers(){

       //$this->load->view('Customers_Map');
	   $this->load->view('LV_Customers_Show');
    }
	function ShowMapS(){

       //$this->load->view('Customers_Map');
	   $this->load->view('Show_MV_S_MAP');
    }
	
	function Editable_MV(){

       //$this->load->view('Customers_Map');
	   $this->load->view('Editable_MV_S_MAP');
    }
	
	function public_fortest_Map(){

       $this->load->view('Show_MV_S_MAP_test');
	   //$this->load->view('MCustomer_Show');
	// $this->load->view('Advance_show_1');
	//$this->load->view('Editable_MV_S_MAP');
	//$this->load->view('lana');
	//$this->load->view('Customers_Map_1');just4test
	  
    }
}