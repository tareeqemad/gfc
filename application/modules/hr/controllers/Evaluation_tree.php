<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 15/05/16
 * Time: 11:22 ص
 */

class Evaluation_tree extends MY_Controller{

    var $MODEL_NAME= 'evaluation_form_model';
    var $AXES_MODEL_NAME= 'evaluation_form_axes_model';
    var $ASK_MODEL_NAME= 'evaluation_form_axes_ask_model';
    var $MARKS_MODEL_NAME= 'evaluation_form_axes_marks_model';
    var $JOBS_MODEL_NAME= 'evaluation_jobs_model';

    function  __construct(){
        parent::__construct(); 
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->AXES_MODEL_NAME);
        $this->load->model($this->ASK_MODEL_NAME);
        $this->load->model($this->MARKS_MODEL_NAME);
        $this->load->model($this->JOBS_MODEL_NAME);

        // vars
        $this->evaluation_form_id= $this->input->post('evaluation_form_id');

        $this->evaluation_element_id= $this->input->post('evaluation_element_id');
        $this->efa_id= $this->input->post('efa_id');
        $this->evaluation_element_order= $this->input->post('evaluation_element_order');
        $this->evaluation_element_name= $this->input->post('evaluation_element_name');
        $this->evaluation_element_weight= $this->input->post('evaluation_element_weight');

        // array
        $this->marks_ser= $this->input->post('marks_ser');
        $this->range_order= $this->input->post('range_order');
        $this->mark_from= $this->input->post('mark_from');
        $this->mark_to= $this->input->post('mark_to');
        $this->mark_range_description= $this->input->post('mark_range_description');

        $this->job_ser= $this->input->post('job_ser');
        $this->job_id= $this->input->post('job_id');
        $this->hints= $this->input->post('hints');

    }

    function index(){
        $this->load->helper('generate_list');

        $this->load->model('settings/constant_details_model');
        $data['range_order_all'] = $this->constant_details_model->get_list(114);

        $data['jobs_all'] = json_encode($this->{$this->JOBS_MODEL_NAME}->get_all());

        add_css('combotree.css');
        add_js('jquery.tree.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['title']=' تقييم الاداء للوظائف ';
        $data['content']='evaluation_tree_index';

        $resource =  $this->_get_structure(0,1);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li ><span data-id="{EV_PK}" ondblclick="javascript:get(\'{EV_PK}\', \'{EV_TYPE}\');"><i class="glyphicon glyphicon-minus-sign"></i> <div0 class="e_name">{EV_NAME} :</div0> <div0 class="e_weight" style="font-weight: bold; color: #a6d96a" title="الوزن النسبي للعنصر">{WEIGHT}</div0> <div0 class="e_total_weight" style="font-weight: bold; color: #ffb848" title="اجمالي الوزن النسبي للابناء">{TOTAL_WEIGHT}</div0> </span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="evaluation_tree">'.generate_list($resource, $options, $template).'</ul>';

        $data['help']=$this->help;

        $this->load->view('template/template',$data);
    }

    function _get_structure($parent= 0, $level= 0) {
        $result = $this->{$this->MODEL_NAME}->get_child($parent,$level);
        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['EV_PK'],$level+1);
            $i++;
        }
        return $result;
    }

    function get_form(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function get_axes(){
        $id = $this->input->post('id');
        $result = $this->{$this->AXES_MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function get_ask(){
        $id = $this->input->post('id');
        $result = $this->{$this->ASK_MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function public_get_marks(){
        $id = $this->input->post('id');
        $result = $this->{$this->MARKS_MODEL_NAME}->get_list($id);
        $this->return_json($result);
    }

    function create_ask(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->evaluation_element_id= $this->{$this->ASK_MODEL_NAME}->create($this->_postedDataAsk('create'));
            if(intval($this->evaluation_element_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->evaluation_element_id);
            }else{
                for($i=0; $i<count($this->range_order); $i++){
                    if($this->range_order[$i]!='' and $this->mark_from[$i]!='' and $this->mark_to[$i]!='' ){
                        $detail_seq= $this->{$this->MARKS_MODEL_NAME}->create($this->_postedDataMarks(null, $this->range_order[$i], $this->mark_from[$i], $this->mark_to[$i], $this->mark_range_description[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del_ask($detail_seq);
                        }
                    }
                }
                $result= '{"id":"'.$this->evaluation_element_id.'"}';
                $this->return_json($result);
            }
        }
    }

    function edit_ask(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->ASK_MODEL_NAME}->edit($this->_postedDataAsk());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                for($i=0; $i<count($this->range_order); $i++){
                    if($this->marks_ser[$i]== 0  and $this->range_order[$i]!='' and $this->mark_from[$i]!='' and $this->mark_to[$i]!='' ){ // create
                        $detail_seq= $this->{$this->MARKS_MODEL_NAME}->create($this->_postedDataMarks(null, $this->range_order[$i], $this->mark_from[$i], $this->mark_to[$i], $this->mark_range_description[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->marks_ser[$i]!= 0  and $this->range_order[$i]!='' and $this->mark_from[$i]!='' and $this->mark_to[$i]!='' ){ // edit
                        $detail_seq= $this->{$this->MARKS_MODEL_NAME}->edit($this->_postedDataMarks($this->marks_ser[$i], $this->range_order[$i], $this->mark_from[$i], $this->mark_to[$i], $this->mark_range_description[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->evaluation_element_id=='' and $isEdit) or $this->efa_id=='' or $this->evaluation_element_order=='' or $this->evaluation_element_name=='' or $this->evaluation_element_weight=='' )
            $this->print_error('يجب ادخال جميع البيانات');
        elseif($this->evaluation_element_weight<1 or $this->evaluation_element_weight>100 )
            $this->print_error('ادخل وزن صحيح');

        $cnt_rows=count($this->range_order);
        for($i=0;$i<$cnt_rows;$i++){
            if($this->mark_from[$i]=='' )
                $this->print_error('ادخل الفترة من #'.($i+1));
            if($this->mark_to[$i]=='' )
                $this->print_error('ادخل الفترة الى #'.($i+1));
            if($this->mark_from[$i] >= $this->mark_to[$i] or $this->mark_from[$i]>=100 or $this->mark_to[$i]>100 )
                $this->print_error('ادخل فترة صحيحة #'.($i+1));
            if($i<$cnt_rows-1 and $this->mark_to[$i] != $this->mark_from[$i+1])
                $this->print_error('يوجد فجوة بين الفترتين # '.($i+1).' و '.($i+2));
            if($i==0 and $this->mark_from[0]!=0 )
                $this->print_error('ادخل بداية الفترة 0 ');
            if($i==$cnt_rows-1 and $this->mark_to[$cnt_rows-1]!=100 )
                $this->print_error('ادخل نهاية الفترة 100 ');
        }
    }

    function status_ask(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_element_id!=''){
            $res = $this->{$this->ASK_MODEL_NAME}->status($this->evaluation_element_id);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحذف'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السؤال";
    }

    function print_error_del_ask($msg=''){
        $ret= $this->{$this->MARKS_MODEL_NAME}->delete($this->evaluation_element_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السؤال: '.$msg);
        else
            $this->print_error('لم يتم حذف السؤال: '.$msg);
    }

    function get_jobs(){
        $id = $this->input->post('id');
        $result = $this->{$this->JOBS_MODEL_NAME}->get_list($id);
        $this->return_json($result);
    }

    function get_emps(){
        $id = $this->input->post('id');
        $result = $this->{$this->JOBS_MODEL_NAME}->get_emps_list($id);
        $this->return_json($result);
    }

    function save_jobs(){
        if($this->evaluation_form_id!=''){
            for($i=0; $i<count($this->job_id); $i++){
                if($this->job_ser[$i]== 0 and $this->job_id[$i]!=''){ // create
                    $detail_seq= $this->{$this->JOBS_MODEL_NAME}->create($this->_postedDataJobs(null, $this->job_id[$i], $this->hints[$i], 'create'));
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }elseif($this->job_ser[$i]!= 0 and $this->job_id[$i]!=''){ // edit
                    $detail_seq= $this->{$this->JOBS_MODEL_NAME}->edit($this->_postedDataJobs($this->job_ser[$i], $this->job_id[$i], $this->hints[$i], 'edit'));
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }elseif($this->job_ser[$i]!= 0 and $this->job_id[$i]==''){ // delete
                    $detail_seq= $this->{$this->JOBS_MODEL_NAME}->delete($this->job_ser[$i]);
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }
            }
            echo 1;
        }else{
            echo "لم يتم ارسال رقم النموذج";
        }
    }

    function _postedDataAsk($typ= null){
        $result = array(
            array('name'=>'EVALUATION_ELEMENT_ID','value'=>$this->evaluation_element_id ,'type'=>'','length'=>-1),
            array('name'=>'EFA_ID','value'=>$this->efa_id ,'type'=>'','length'=>-1),
            array('name'=>'EVALUATION_ELEMENT_ORDER','value'=>$this->evaluation_element_order ,'type'=>'','length'=>-1),
            array('name'=>'EVALUATION_ELEMENT_NAME','value'=>$this->evaluation_element_name ,'type'=>'','length'=>-1),
            array('name'=>'EVALUATION_ELEMENT_WEIGHT','value'=>$this->evaluation_element_weight ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

    function _postedDataMarks($marks_ser= null, $range_order, $mark_from, $mark_to, $mark_range_description, $typ= null){
        $result = array(
            array('name'=>'MARKS_SER','value'=>$marks_ser ,'type'=>'','length'=>-1),
            array('name'=>'EVALUATION_ELEMENT_ID','value'=>$this->evaluation_element_id ,'type'=>'','length'=>-1),
            array('name'=>'RANGE_ORDER','value'=>$range_order ,'type'=>'','length'=>-1),
            array('name'=>'MARK_FROM','value'=>$mark_from ,'type'=>'','length'=>-1),
            array('name'=>'MARK_TO','value'=>$mark_to ,'type'=>'','length'=>-1),
            array('name'=>'MARK_RANGE_DESCRIPTION','value'=>$mark_range_description ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

    function _postedDataJobs($job_ser= null, $job_id, $hints, $typ= null){
        $result = array(
            array('name'=>'JOB_SER','value'=>$job_ser ,'type'=>'','length'=>-1),
            array('name'=>'EVALUATION_FORM_ID','value'=>$this->evaluation_form_id ,'type'=>'','length'=>-1),
            array('name'=>'JOB_ID','value'=>$job_id ,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$hints ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}
