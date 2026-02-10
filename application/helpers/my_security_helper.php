<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 7/8/14
 * Time: 9:32 AM
 */

function &get_my_instance()
{
    return MY_Controller::get_instance();
}


function &get_curr_user()
{
    return get_my_instance()->user;
}

function &get_notify_count()
{
    return get_my_instance()->notify_Count;
}


if (!function_exists('AntiForgeryToken')) {

    /**
     * @return string
     * return AntiForgeryToken ..
     */
    function AntiForgeryToken()
    {
        $CI = get_instance();
        $CI->load->library('session');
        return '<input name="__AntiForgeryToken" type="hidden" value="' . $CI->session->userdata('__AntiForgeryToken') . '" />';
    }

}
if (!function_exists('HaveAccess')) {

    function HaveAccess($url,$url2 =null){
        $CI = get_my_instance();
        $url2 = $url2 == null ? $url :$url2;

      
        $url = str_replace(base_url('/'),'',$url);
        $url = str_replace('//','/',$url);

        $url2 = str_replace(base_url('/'),'',$url2);
        $url2 = str_replace('//','/',$url2);

		$url =  rtrim($url,'/');
 
        $permission_count=  $CI->user_menus_model->check_permission($CI->user->id,$url,$url2);
        return intval($permission_count) > 0;
    }
}