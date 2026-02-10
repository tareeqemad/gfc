<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 1:40 PM
 */

//Dynamically add Javascript files to header page
if(!function_exists('add_js')){
    function add_js($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_js  = $ci->config->item('header_js');

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_js[] = $item;
            }
            $ci->config->set_item('header_js',$header_js);
        }else{
            $str = $file;
            $header_js[] = $str;
            $ci->config->set_item('header_js',$header_js);
        }
    }
}

//Dynamically add CSS files to header page
if(!function_exists('add_css')){
    function add_css($file='')
    {
        $str = '';
        $ci = &get_instance();
        $header_css = $ci->config->item('header_css');

        if(empty($file)){
            return;
        }

        if(is_array($file)){
            if(!is_array($file) && count($file) <= 0){
                return;
            }
            foreach($file AS $item){
                $header_css[] = $item;
            }
            $ci->config->set_item('header_css',$header_css);
        }else{
            $str = $file;
            $header_css[] = $str;
            $ci->config->set_item('header_css',$header_css);
        }
    }
}

if(!function_exists('put_headers')){
    function put_headers($type)
    {
        $str = '';
        $ci = &get_instance();
        $ci->load->driver('minify');
        $header_css = $ci->config->item('header_css');
        $header_js  = $ci->config->item('header_js');
        $scripts_content  = $ci->config->item('scripts');

        if($type =='css')
            foreach($header_css AS $item){
                $str .= '<link rel="stylesheet" href="'.base_url().'assets/css/'.$item.'" type="text/css" />'."\n";
            }

        if($type =='js')
            foreach($header_js AS $item){
                $str .= '<script type="text/javascript" src="'.base_url().'assets/js/'.$item.'"></script>'."\n";
            }

       // $str .=$ci->minify->js->min($scripts_content)."\n";
          $str .=$scripts_content;
         return $str;
    }
}


if(!function_exists('sec_scripts')){
    function sec_scripts($scripts='')
    {

        $ci = &get_instance();
        $ci->config->set_item('scripts',$scripts);

    }
}
