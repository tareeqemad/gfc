<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/08/15
 * Time: 11:47 ص
 */

class Technical_work_tree extends MY_Controller{

    var $MODEL_NAME= 'technical_work_tree_model';
    var $DETAILS_MODEL= 'technical_work_jobs_model';
    var $PAGE_URL= 'technical/technical_work_tree/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL);

        // vars
        $this->technical_work_id= $this->input->post('technical_work_id');
        $this->technical_work_name= $this->input->post('technical_work_name');
        $this->parent_id= $this->input->post('parent_id');
        $this->notes= $this->input->post('notes');

        // array
        $this->ser= $this->input->post('ser');
        $this->job_id= $this->input->post('job_id');
        $this->note= $this->input->post('note');
    }

    function index(){
        $this->load->helper('generate_list');

        //$this->load->model('settings/constant_details_model');

        // add_css('jquery-hor-tree.css');
        add_css('combotree.css');
        add_js('jquery.tree.js');

        $data['title']=' هيكلية الأعمال الفنية';
        $data['content']='tec_work_tree_index';

        $resource =  $this->_get_structure(0);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li ><span data-id="{TECHNICAL_WORK_ID}" ondblclick="javascript:get(\'{TECHNICAL_WORK_ID}\');"><i class="glyphicon glyphicon-minus-sign"></i>{TECHNICAL_WORK_ID} : {TECHNICAL_WORK_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="tec_works">'.generate_list($resource, $options, $template).'</ul>';

        $data['help']=$this->help;

        $this->load->view('template/template',$data);
    }

    function _get_structure($parent= 0) {
        $result = $this->{$this->MODEL_NAME}->get_child($parent);
        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['TECHNICAL_WORK_ID']);
            $i++;
        }
        return $result;
    }

    function get(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function create(){
        $result= $this->{$this->MODEL_NAME}->create($this->_postedData());
        $this->Is_success($result);
        $this->return_json($result);
    }

    function edit(){
        echo $this->{$this->MODEL_NAME}->edit($this->_postedData());
    }

    function delete(){
        $id = $this->input->post('id');
        $this->IsAuthorized();
        if(is_array($id)){
            foreach($id as $val){
                echo $this->{$this->MODEL_NAME}->delete($val);
            }
        }else{
            echo $this->{$this->MODEL_NAME}->delete($id);
        }
    }

    function get_details(){
        $id = $this->input->post('id');
        $result = $this->{$this->DETAILS_MODEL}->get_list($id);
        $this->return_json($result);
    }

    function save_details(){
        if($this->technical_work_id!=''){
            for($i=0; $i<count($this->job_id); $i++){
                if($this->ser[$i]== 0 and $this->job_id[$i]!=''){ // create
                    $detail_seq= $this->{$this->DETAILS_MODEL}->create($this->_postedData_details(null, $this->job_id[$i], $this->note[$i], 'create'));
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }elseif($this->ser[$i]!= 0 and $this->job_id[$i]!=''){ // edit
                    $detail_seq= $this->{$this->DETAILS_MODEL}->edit($this->_postedData_details($this->ser[$i], $this->job_id[$i], $this->note[$i], 'edit'));
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }elseif($this->ser[$i]!= 0 and $this->job_id[$i]==''){ // delete
                    $detail_seq= $this->{$this->DETAILS_MODEL}->delete($this->ser[$i]);
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }
            }
            echo 1;
        }else{
            echo "لم يتم ارسال رقم العمل";
        }
    }

    function _postedData(){
        $result = array(
            array('name'=>'TECHNICAL_WORK_ID','value'=>$this->technical_work_id ,'type'=>'','length'=>-1),
            array('name'=>'TECHNICAL_WORK_NAME','value'=>$this->technical_work_name,'type'=>'','length'=>-1),
            array('name'=>'PARENT_ID','value'=>$this->parent_id,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes,'type'=>'','length'=>-1),
        );
        return $result;
    }

    function _postedData_details($ser= null, $job_id, $note, $typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'TECHNICAL_WORK_ID','value'=>$this->technical_work_id ,'type'=>'','length'=>-1),
            array('name'=>'JOB_ID','value'=>$job_id ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$note ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}
