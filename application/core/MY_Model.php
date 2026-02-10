<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 7/7/14
 * Time: 9:36 AM
 */

class MY_Model extends CI_Model{

    protected $conn;
    function __construct(){
        parent::__construct();
 
        //init DataBase Connection ..
         $this->conn = get_my_instance()->conn;

    }

    function closeConnection(){
        //$this->conn->close();
    }

    /**
     * @param $params
     * @param $data
     *
     * extract data from param in array of params
     */
    function _extract_data(&$params,$data){

        foreach($data as $array){
            array_push($params,array('name'=>":{$array['name']}_in",'value'=>$array['value'],'type'=>$array['type'],'length'=>$array['length']));
        }

        array_push($params, array('name'=>':MSG_OUT','value'=>'MSG_OUT','type'=>SQLT_CHR,'length'=>500));


    }
}