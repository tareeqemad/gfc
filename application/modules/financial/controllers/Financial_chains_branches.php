<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 09/09/14
 * Time: 09:41 ص
 */

class Financial_chains_branches extends MY_Controller{

    var $MODEL_NAME= 'help_ticket_model';
    var $PAGE_URL= 'settings/help_ticket/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model('settings/report_branch_model');
        $this->report_name= $this->input->post('report_name');
        $this->report_hints= $this->input->post('report_hints');
     }

    function index($page= 1,$report_name=-1,$report_hints=-1){
        $data['title']='ادخال الفروع';
        $data['content']='financial_chains_branches_index';
       $data['report_name_all']= $this->report_branch_model->type_get_all();
        //$data['report_name_all']=$this->report_branch_model->get_all();
        $data['page']=$page;
        $data['report_name'] =$report_name;
        $data['report_hints'] =$report_hints;
        $data['help'] = $this->help;
        //$data['count_all']= count($data['get_all']);
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->view('template/template',$data);
    }


    function get_page(){
        $data['get_all']= $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('help_ticket_page',$data);
    }
    function get_id(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function public_get(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get_list($id);
        $this->return_json($result);
    }


    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->request_no= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->request_no) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->request_no);
            }else{
                for($i=0; $i<count($this->class_id); $i++){
                    if($this->class_id[$i]!='' and $this->request_amount[$i]!='' and $this->request_amount[$i]>0 and $this->class_unit[$i]!=''){
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->class_id[$i], $this->request_amount[$i], $this->class_unit[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->request_no);
            }

        }else{
            $data['content']='financial_chains_branches_show';
            $data['title']= 'ادخال الفروع شجرة الحسابات';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('stores_model');
        $data['stores'] = $this->stores_model->get_all();
        $data['request_type'] = $this->constant_details_model->get_list(35);
        $data['request_side'] = $this->constant_details_model->get_list(18);
        $data['class_unit'] = json_encode($this->constant_details_model->get_list(29));
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }
    function edit(){
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
            array('name'=>'ID','value'=>$this->input->post('id') ,'type'=>'','length'=>10),
            array('name'=>'TITLE','value'=>$this->input->post('title') ,'type'=>'','length'=>250),
            array('name'=>'FORM_ID','value'=>$this->input->post('form_id') ,'type'=>'','length'=>100),
            array('name'=>'HELP_TEXT','value'=>$this->input->post('help_text') ,'type'=>'','length'=>-1)

        );
        if($typ=='create'){
            array_shift($result);
        }
        return $result;
    }
}

