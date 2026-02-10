<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/09/14
 * Time: 09:24 ص
 */

class stores extends MY_Controller{

    var $MODEL_NAME= 'stores_model';
    var $PAGE_URL= 'stores/stores/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('store_users_model');
        $this->load->model('settings/users_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
    }

    function index(){


        $data['title']=' إدارة المخازن';
        $data['help']=$this->help;
        $data['users'] = $this->users_model->get_all();
        $data['content']='stores_index';
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $data['count_all']= count($data['get_all']);
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['store_pos'] = $this->constant_details_model->get_list(37);
        $data['is_donationss'] = $this->constant_details_model->get_list(213);
        $data['is_can_outputs'] = $this->constant_details_model->get_list(95);
        $data['is_need_tech'] = $this->constant_details_model->get_list(248);
        $data['branches']= $this->gcc_branches_model->get_all();
        $this->load->view('template/template',$data);
    }

    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('stores_page',$data);
    }
    function get_page_users(){
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['users'] = $this->users_model->get_all();
        $data['store_id']= $this->input->post('store_id');
        $data['get_all']= $this->store_users_model->getusers($data['store_id']);
        $this->load->view('store_users_page',$data);
    }
    function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function create(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');
        $result= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
    }

    function edit(){
        if(!$this->check_db_for_stores())
            die('CLOSED..');
        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
        $this->Is_success($result);
        echo  modules::run($this->PAGE_URL);
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

    function _postedData($typ= null){

     $result = array(
            array('name'=>'STORE_ID','value'=>$this->input->post('store_id') ,'type'=>'','length'=>-1),
            array('name'=>'STORE_NAME','value'=>$this->input->post('store_name') ,'type'=>'','length'=>-1),
            array('name'=>'ADDRESS','value'=>$this->input->post('address') ,'type'=>'','length'=>-1),
            array('name'=>'STORE_EMPLOYEE','value'=>$this->input->post('store_employee') ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$this->input->post('account_id') ,'type'=>'','length'=>-1),
            array('name'=>'TEL','value'=>$this->input->post('tel') ,'type'=>'','length'=>-1),
            array('name'=>'FAX','value'=>$this->input->post('fax') ,'type'=>'','length'=>-1),
            array('name'=>'EMAIL','value'=>$this->input->post('email') ,'type'=>'','length'=>-1),
         array('name'=>'STORE_TYPE','value'=>$this->input->post('store_type') ,'type'=>'','length'=>-1),
         array('name'=>'ENTER_BOOK','value'=>$this->input->post('enter_book') ,'type'=>'','length'=>-1),
         array('name'=>'PAYMENT_BOOK','value'=>$this->input->post('payment_book') ,'type'=>'','length'=>-1),
         array('name'=>'STORE_POS','value'=>$this->input->post('store_pos') ,'type'=>'','length'=>-1),
         array('name'=>'ASEEL_NO','value'=>$this->input->post('aseel_no') ,'type'=>'','length'=>-1),
         array('name'=>'STORE_NO','value'=>$this->input->post('store_no') ,'type'=>'','length'=>-1),
         array('name'=>'BRANCH','value'=>$this->input->post('branch') ,'type'=>'','length'=>-1),
         array('name'=>'ISDONATION','value'=>$this->input->post('isdonation') ,'type'=>'','length'=>-1),
         array('name'=>'CAN_OUTPUT','value'=>$this->input->post('can_output') ,'type'=>'','length'=>-1),
         array('name'=>'NEED_TEC_ADOPT','value'=>$this->input->post('need_tec_adopt') ,'type'=>'','length'=>-1)


        );


         // return $result;



        if($typ=='create'){
              array_shift($result);
          }
          return $result;
    }
}

?>
