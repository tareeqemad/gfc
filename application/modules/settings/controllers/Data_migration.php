<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/02/19
 * Time: 09:20 ص
 */


class data_migration extends  MY_Controller{

    var $MODEL_NAME= 'data_migration_model';
    var $PAGE_URL= 'settings/data_migration/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('stores/stores_model');
        $this->load->model($this->MODEL_NAME);
    }

    function new_adjustments(){
        add_js('jquery.tree.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['title']='سحب و تحديث الأرصدة الافتتاحية';
        $data['content']='new_adjustments_index';
        $data['stores'] = $this->stores_model->get_all();
        $this->load->view('template/template',$data);
    }



  function add_new_adjustments()
  {
      $store_id = $this->input->post('store_id');
      $notes = $this->input->post('notes');
      $result = $this->{$this->MODEL_NAME}->stores_adjustment_tb_transf($store_id,date('Y') ,$notes);
    //  $this->return_json($result);
      echo $result;
  }

    function update_new_adjustments()
    {
        $store_id = $this->input->post('store_id');
        $notes = $this->input->post('notes');
        $result = $this->{$this->MODEL_NAME}->stores_adjustment_tb_re_transf($store_id,date('Y') ,$notes);
       // $this->return_json($result);
        echo $result;
    }



    function new_prices(){
        add_js('jquery.tree.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['title']='تحديث الأسعار';
        $data['content']='new_prices_index';
       // $data['stores'] = $this->stores_model->get_all();
        $this->load->view('template/template',$data);
    }

    function update_all_prices()
    {
        $result = $this->{$this->MODEL_NAME}->update_open_price();
       // $this->return_json($result);
        echo $result;
    }
    function update_class_price()
    {
        $class_id = $this->input->post('class_id');
        $result = $this->{$this->MODEL_NAME}->update_open_price_class($class_id);
       echo $result;
        // $this->return_json($result);
    }
    function update_class_purchasing_price()
    {
        $class_id = $this->input->post('class_id');
        $class_purchasing = $this->input->post('class_purchasing');
        $result = $this->{$this->MODEL_NAME}->update_class_purchasing($class_id,$class_purchasing);
       // $this->return_json($result);
        echo $result;
    }
    function update_class_used_percent()
    {
        $class_id = $this->input->post('class_id');
        $used_percent= $this->input->post('used_percent');
        $result = $this->{$this->MODEL_NAME}->update_class_used_percent($class_id,$used_percent);
        // $this->return_json($result);
        echo $result;
    }
    function update_donation_balance()
    {
        $result = $this->{$this->MODEL_NAME}->update_donation_balance();
       // $this->return_json($result);
        echo $result;
    }

    function update_order_detail_balance()
    {
        $result = $this->{$this->MODEL_NAME}->update_order_detail_balance();
        //$this->return_json($result);
        echo $result;
    }



}