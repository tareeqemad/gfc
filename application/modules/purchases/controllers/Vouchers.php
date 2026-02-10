<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 08/10/18
 * Time: 12:43 م
 */
class vouchers extends MY_Controller{

    var $MODEL_NAME= 'vouchers_model';
    var $PAGE_URL= 'purchases/vouchers/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('payment/customers_model');
        $this->load->model('settings/constant_details_model');

    }

    function index(){


        $data['title']=' إدارة إيصالات الموردين';
        $data['help']=$this->help;
        $data['content']='vouchers_index';
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $data['count_all']= count($data['get_all']);
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
     //   $data['adver_type'] = $this->constant_details_model->get_list(236);
        $data['customer_ids']=$this->customers_model->get_all_by_type(1);
        $this->load->view('template/template',$data);
    }

    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('vouchers_page',$data);
    }

    function get_id(){
        $id = $this->input->post('id');
        $voucher = $this->input->post('voucher');
        $result = $this->{$this->MODEL_NAME}->get($id,$voucher);
        $this->return_json($result);//$result
    }

    function create(){

        $result= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function edit(){
    $adopt=$this->input->post('adopt');
        if ($adopt==1){
            $result= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            $this->Is_success($result);
            echo  modules::run($this->PAGE_URL);
        }
        else if ($adopt==2){
        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
        }
    }

    function delete(){
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if(is_array($id)){
            foreach($id as $val){
                $msg= $this->{$this->MODEL_NAME}->delete($val);
            }
        }else{
            $msg= $this->{$this->MODEL_NAME}->delete($id);
        }

        if($msg == 1){
            echo  modules::run($this->PAGE_URL);
        }else{
            $this->print_error_msg($msg);
        }
    }

    function adopt(){
        $id = $this->input->post('id');
        $result= $this->{$this->MODEL_NAME}->adopt($id,3);

        echo $result ;
    }
    function cancel_adopt(){
        $id = $this->input->post('id');
        $result= $this->{$this->MODEL_NAME}->adopt($id,2);
        echo $result ;

    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER','value'=>$this->input->post('ser') ,'type'=>'','length'=>-1),
            array('name'=>'VOUCHER_ID','value'=>$this->input->post('voucher_id') ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_ID','value'=>$this->input->post('customer_id') ,'type'=>'','length'=>-1),
            array('name'=>'PURCHASE_ORDER_ID','value'=>$this->input->post('purchase_order_id') ,'type'=>'','length'=>-1),
            array('name'=>'ENTRY_SER','value'=>$this->input->post('entry_ser') ,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$this->input->post('hints') ,'type'=>'','length'=>-1)
        );

        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }
}

?>
