<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/09/14
 * Time: 09:52 ص
 */

class currency_details extends MY_Controller{

    var $MODEL_NAME= 'currency_details_model';
    var $PAGE_URL= 'treasury/currency_details/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/currency_model');
    }

    function index(){
        $data['title']='أسعار العملات';
        $data['content']='currency_details_index';
        $data['count']= count($this->{$this->MODEL_NAME}->get_list());
        $data['select_master_curr']= $this->select();
        $data['select_curr_id']= $this->select2();
        $data['help']=$this->help;
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->view('template/template',$data);
    }

    function get_page(){
        $data['get_list']= $this->{$this->MODEL_NAME}->get_list();
        $this->load->view('currency_details_page',$data);
    }

    function create(){
        $curr_id = $this->input->post('curr_id');
        $curr_value = $this->input->post('curr_value');
        $master_curr = $this->input->post('master_curr');
        $date_by_user = $this->input->post('date_by_user');

        for($i=0;$i<count($curr_id);$i++){
            $result= $this->{$this->MODEL_NAME}->create($this->_postedData($curr_id[$i],$curr_value[$i],$master_curr[$i],$date_by_user[$i]));
            $msg= $this->Is_success($result);
            if($msg and $msg!= 1 )
                break;
        }

        if($msg and $msg!= 1 )
            $this->print_error_msg($msg);
        else
            echo modules::run($this->PAGE_URL);
    }



    function select(){
        $all_curr= $this->currency_model->get_all();
        $select= "<select class='master_curr' name='master_curr[]' id='txt_master_curr'>";
        foreach ($all_curr as $row){
            $select.= "<option value='{$row['CURR_ID']}'>{$row['CURR_NAME']}</option>";
        }
        $select.= "</select>";

        return $select;
    }

    function select2(){
        $id= $this->input->post('id');
        if(!$id or $id=='' or $id==0){
            $id=1;
        }
        $all_curr= $this->currency_model->get_all();
        $select= "<select name='curr_id[]' class='curr_id' id='txt_curr_id'>";
        foreach ($all_curr as $row){
            if($row['CURR_ID']!= $id)
                $select.= "<option value='{$row['CURR_ID']}'>{$row['CURR_NAME']}</option>";
        }
        $select.= "</select>";

        if($id== $this->input->post('id'))
            echo $select;
        else
            return $select;
    }


    function _postedData($curr_id,$curr_value,$master_curr,$date_by_user){
        $result = array(
            array('name'=>'CURR_ID','value'=>$curr_id ,'type'=>'','length'=>2),
            array('name'=>'CURR_VALUE','value'=>$curr_value ,'type'=>'','length'=>8),
            array('name'=>'MASTER_CURR','value'=>$master_curr ,'type'=>'','length'=>2),
            array('name'=>'DATE_BY_USER','value'=>$date_by_user ,'type'=>'','length'=>-1)
        );
        return $result;
    }
}
