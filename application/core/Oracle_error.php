<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/2/14
 * Time: 8:57 AM
 */
class Oracle_error {
    public $ORA;

    function __construct(){

       $this->ORA['ORA-01407']='خطا';
        $this->ORA['ORA-00001']= 'يوجد تكرار في البيانات , قد يكون المسجل مسبقا ً';
    }


}