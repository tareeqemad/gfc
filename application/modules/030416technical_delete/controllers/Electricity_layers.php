<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 26/11/15
 * Time: 10:22 ص
 */

class Electricity_layers extends MY_Controller{

    var $MODEL_NAME= 'electricity_layers_model';
    var $DETAILS_MODEL_NAME= 'electricity_layers_det_model';
    var $DET_LAYERS_GROUP_MODEL_NAME= 'feeder_layers_group_model';
    var $PAGE_URL= 'technical/electricity_layers/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model($this->DET_LAYERS_GROUP_MODEL_NAME);

        $this->layer_id= $this->input->post('layer_id');
        $this->electricity_load_system= $this->input->post('electricity_load_system');

        // arrays
        $this->g_ser= $this->input->post('g_ser');
        $this->group_id= $this->input->post('group_id');
    }

    function index(){
        $data['title']='شرائح القطع والوصل';
        $data['content']='electricity_layers_index';
        $data['help'] = $this->help;
        $this->load->view('template/template',$data);
    }

    function get_page(){
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('electricity_layers_page',$data);
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!count($result)==1)
            die('get');
        $data['layer_data']=$result;
        $data['content']='electricity_layers_show';
        $data['title']='بيانات الشريحة';
        $this->load->view('template/template',$data);
    }

    function public_get_details($id= 0){
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        $this->load->view('electricity_layers_details',$data);
    }

    function edit_case(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->electricity_load_system!=''){
            $res = $this->{$this->MODEL_NAME}->edit_case($this->electricity_load_system);
            if(intval($res) <= 0){
                $this->print_error('لم تتم العملية'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال نظام توزيع الاحمال";
    }

    // LAYERS_GROUP //

    function public_get_det_groups($id= 0){
        $data['details'] = $this->{$this->DET_LAYERS_GROUP_MODEL_NAME}->get_list($id);
        $this->load->view('feeder_layers_group_details',$data);
    }

    function group_create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_group_validation();
            if(intval($this->layer_id) <= 0){
                $this->print_error(' خطأ '.'<br>'.$this->layer_id);
            }else{
                for($i=0; $i<count($this->group_id); $i++){
                    if($this->g_ser[$i]== 0 and $this->group_id[$i]!='' and $this->group_id[$i]>0 ){
                        $detail_seq= $this->{$this->DET_LAYERS_GROUP_MODEL_NAME}->create($this->layer_id, $this->group_id[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }
        }
    }

    function _post_group_validation(){
        if( $this->layer_id=='' or $this->group_id=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }else if( !($this->group_id) or count(array_filter($this->group_id)) <= 0 ){
            $this->print_error('يجب ادخال مجموعة واحدة على الاقل ');
        }else if( count(array_filter($this->group_id)) !=  count(array_count_values(array_filter($this->group_id)))  ){
            $this->print_error('يوجد تكرار في المجموعات');
        }
    }

    function group_delete(){
        if($this->g_ser!='' and $this->g_ser>0 ){
            $ret= $this->{$this->DET_LAYERS_GROUP_MODEL_NAME}->delete($this->g_ser);
            if(intval($ret) <= 0){
                $this->print_error($ret);
            }
            echo 1;
        }else
            echo 0;
    }

}
