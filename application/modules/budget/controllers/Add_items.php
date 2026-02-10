<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/09/15
 * Time: 08:48 ص
 */

class add_items extends MY_Controller{

    var $MODEL_NAME= 'add_items_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        // vars
        $this->section_no= $this->input->post('section_no');
        $this->classes= $this->input->post('classes');
        $this->grand_id= $this->input->post('grand_id');
        $this->parent_id= $this->input->post('parent_id');
    }

    function index(){
        $this->load->model('budget_section_model');
        $this->load->model('stores/class_model');
        $data['sections'] = $this->budget_section_model->get_all();
        $data['grands'] = $this->class_model->getAllGrandsClasses();
        $data['class_parent_id'] = $this->class_model->getAllParentsClasses(null);
        $data['title']='اضافة بنود للفصول';
        $data['content']='add_items_index';
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->view('template/template',$data);
    }

    function get_classes(){
        $data['classes']= $this->{$this->MODEL_NAME}->get_classes($this->section_no, $this->grand_id, $this->parent_id);
        $this->load->view('add_items_classes_page',$data);
    }

    function create(){
        $classes='';
        foreach($this->classes as $class){
            $classes.= $class.',';
        }
        $classes= trim($classes, ',');
        echo $this->{$this->MODEL_NAME}->create($this->_postedData($classes));
    }

    function _postedData($classes){
        $result = array(
            array('name'=>'CLASS_ID','value'=>$classes ,'type'=>'','length'=>-1),
            array('name'=>'SECTION_NO','value'=>$this->section_no ,'type'=>'','length'=>-1),
        );
        return $result;
    }

}