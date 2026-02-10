<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 7/7/14
 * Time: 1:20 PM
 */
class MY_Class
{

    /***
     * @param array $properties
     * Convert array to class properties ..
     */
    public function __construct(Array $properties = array())
    {
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }
    }
}