<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/10/14
 * Time: 10:43 ص
 */

class Budget_items_details extends MY_Controller{

    var $MODEL_NAME= 'budget_items_details_model';
    var $PAGE_URL= 'budget/budget_items_details/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('budget_items_model');
    }

    function get_page(){
        $data['exp_rev_no']= $this->input->post('exp_rev_no');
        $data['can_update']= $this->input->post('n');
        $data['user_id']= $this->user->id;
        $data['items']= $this->budget_items_model->get_list(-1, 0, null, 1); // special items
        $data['get_list']= $this->{$this->MODEL_NAME}->get_list($data['exp_rev_no']);
        $this->load->view('budget_items_details_page',$data);
    }

    function receive_data(){
        //vars
        $exp_rev_no= $this->input->post('exp_rev_no');
        // arrays
        $no= $this->input->post('no');
        $special_items= $this->input->post('special_items');
        $ccount= $this->input->post('ccount');
        $price= $this->input->post('price');
        $notes= $this->input->post('notes');
        if($exp_rev_no!= null and count($no)==count($special_items) and count($no)==count($ccount) and count($no)==count($price)){
            for($i=0;$i<count($no);$i++){
                if($no[$i]==0 and $special_items[$i]!='' and $special_items[$i]!=0 and $ccount[$i]!='' and $price[$i]!='' and $ccount[$i]>0 and $price[$i]>0 ) // create
                    $this->create($this->_postedData($no[$i], $special_items[$i], $ccount[$i], $price[$i], $exp_rev_no, $notes[$i], 'create'));
                else if ($no[$i]!='' and $no[$i]!=0 and $special_items[$i]!='' and $special_items[$i]!=0 and $ccount[$i]!='' and $price[$i]!='' and $ccount[$i]>0 and $price[$i]>0) // edit
                    $this->edit($this->_postedData($no[$i], $special_items[$i], $ccount[$i], $price[$i], $exp_rev_no, $notes[$i], 'edit'));
            }
        }else
            echo "لم يتم الارسال بطريقة صحيحة";

        echo  modules::run($this->PAGE_URL);
    }

    function create($data){
        $result= $this->{$this->MODEL_NAME}->create($data);
        return $this->Is_success($result);
    }

    function edit($data){
        $result = $this->{$this->MODEL_NAME}->edit($data);
        $this->Is_success($result);
    }

    function delete(){
        $id= $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        $msg= $this->{$this->MODEL_NAME}->delete($id);
        if($msg == 1){
            echo  modules::run($this->PAGE_URL);
        }else{
            $this->print_error_msg($msg);
        }
    }

    function _postedData($no, $items, $ccount, $price, $exp_rev_no, $notes, $typ= null){
        $result = array(
            array('name'=>'NO','value'=>$no ,'type'=>'','length'=>-1),
            array('name'=>'ITEM_NO','value'=>$items ,'type'=>'','length'=>-1),
            array('name'=>'CCOUNT','value'=>$ccount ,'type'=>'','length'=>-1),
            array('name'=>'PRICE','value'=>$price ,'type'=>'','length'=>-1),
            array('name'=>'EXP_REV_NO','value'=>$exp_rev_no ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$notes ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='edit')
            unset($result[4]);
        return $result;
    }

}
