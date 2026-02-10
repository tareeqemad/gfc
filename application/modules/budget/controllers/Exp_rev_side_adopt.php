<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 15/09/15
 * Time: 12:34 م
 */

class exp_rev_side_adopt extends MY_Controller{

    var $MODEL_NAME= 'exp_rev_side_adopt_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        // vars
        $this->section_no= $this->input->post('section_no');
        $this->branch= $this->input->post('branch');
        $this->adopt= $this->input->post('adopt');
        $this->year= $this->budget_year;
        // array
        $this->items= $this->input->post('items');
    }

    function index(){
        $this->load->model('settings/gcc_branches_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['sections'] = $this->{$this->MODEL_NAME}->get_sections();
        $data['title']= 'اعتماد الجهة المختصة لموازنة '.$this->year;
        $data['content']='exp_rev_side_adopt_index';
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->view('template/template',$data);
    }

    function get_page(){
        if($this->section_no==null or $this->branch== null) $this->print_error('اختر الفرع والفصل');
        $data['get_list']= $this->{$this->MODEL_NAME}->get_list($this->section_no, $this->year, $this->branch);
        $this->load->view('exp_rev_side_adopt_page',$data);
    }

    function adopt(){
        $items='';
        foreach($this->items as $item){
            $items.= $item.',';
        }
        $items= trim($items, ',');
        if($items==null or $this->branch==null ) $this->print_error('اختر الفرع والبنود');
        echo $this->{$this->MODEL_NAME}->adopt($items, $this->year, $this->branch, $this->adopt);
    }

}
