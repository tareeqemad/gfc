<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ahmed barakat
 * Date: 16/11/16
 * Time: 09:06 ص
 */

class Cars_fuel_price extends MY_Controller{

    var $MODEL_NAME= 'Cars_fuel_price_model';
    var $PAGE_URL= 'settings/Cars_fuel_price/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->_loadDatePicker();
    }

    function index(){
        $data['title']='تسعير الوقود';
        $data['content']='cars_fuel_price_index';
        $data['get_all']= $this->{$this->MODEL_NAME}->get_list('', 0, 2000);
        $data['count_all']= count($data['get_all']);


        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $data['fuel_type'] = $this->constant_details_model->get_list(58);
        $data['suppliers'] = $this->constant_details_model->get_list(250);

        $this->load->view('template/template',$data);
    }

    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_list('', 0, 2000);
        $this->load->view('cars_fuel_price_page',$data);
    }

    function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function create(){
        $result= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

        if(intval($result) <= 0)
            $this->print_error_msg('فشل فحفظ البيانات');

        echo  modules::run($this->PAGE_URL);
    }

    function edit(){
        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function adopt(){

        $result= $this->{$this->MODEL_NAME}->adopt($this->p_id);

        if(intval($result) <= 0)
            $this->print_error_msg('فشل فحفظ البيانات');

        echo  modules::run($this->PAGE_URL);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'PRFUEL_SER','value'=>$this->p_prfuel_ser ,'type'=>'','length'=>-1),
            array('name'=>'FUEL_MONTH_PRICE','value'=>$this->p_fuel_month_price ,'type'=>'','length'=>-1),
            array('name'=>'GASOLINE_ID','value'=>$this->p_gasoline_id ,'type'=>'','length'=>-1),
            array('name'=>'GASOLINE_PRICE','value'=>$this->p_gasoline_price ,'type'=>'','length'=>-1),
            array('name'=>'SUPPLIER','value'=>$this->p_supplier_id ,'type'=>'','length'=>-1)
        );
        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }
}

?>
