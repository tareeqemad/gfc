<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/12/19
 * Time: 01:51 م
 */

// ser ,room_parent, room_id, room_name, room_type

class Rooms_structure_tree extends MY_Controller{

    var $MODEL_NAME= 'rooms_structure_model';
    var $EMPS_MODEL_NAME= 'rooms_structure_emp_model';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->EMPS_MODEL_NAME);
        $this->load->model('settings/constant_details_model');

        // vars
        $this->ser= $this->input->post('ser');
        $this->room_parent= $this->input->post('room_parent');
        $this->room_id= $this->input->post('room_id');
        $this->room_name= $this->input->post('room_name');
        $this->room_type= $this->input->post('room_type');

        $this->customer_id= $this->input->post('customer_id');

        $this->ser_d= $this->input->post('ser_d');
        $this->emp_no= $this->input->post('emp_no');

        if( HaveAccess(base_url("pledges/rooms_structure_tree/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;
    }

    function index(){
        $this->load->helper('generate_list');

        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emps_all'] = json_encode( $this->hr_attendance_model->get_child( 0 , 'hr_admin' ) );
        $data['user_branch'] = $this->user->branch;

        $data["room_type_cons"]=  $this->constant_details_model->get_list(308);

        add_css('combotree.css');
        add_js('jquery.tree.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['title']=' هيكلية الغرف ';
        $data['content']='rooms_structure_index';

        if($this->all_branches)
            $resource =  $this->_get_structure(0);
        else
            $resource =  $this->_get_structure(0); // $this->user->branch

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li ><span data-id="{SER}" data-no="{ROOM_ID}" ondblclick="javascript:get_();"><i class="glyphicon glyphicon-minus-sign"></i> <input type="checkbox" class="checkboxes" value="{SER}" /> {ROOM_ID}:{ROOM_NAME} <div0 style="font-weight: bold; color: #ffb848" title="عدد الغرف">{CNT}</div0> </span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="rooms_structure_tree">'.generate_list($resource, $options, $template).'</ul>';

        $data['help']=$this->help;

        $this->load->view('template/template',$data);
    }

    function _get_structure($parent= 0) {
        $result = $this->{$this->MODEL_NAME}->get_child($parent);
        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['ROOM_ID']);
            $i++;
        }
        return $result;
    }

    function public_select_rooms($txt= null, $branch= null){
        $data['txt']= $txt;
        $data['title'] = ' الغرف ';
        $data['content']='rooms_select_page';
        $data['rooms_data']= modules::run('pledges/customers_pledges/public_get_rooms', 'array');
        $this->load->view('template/view',$data);
    }

    function get(){
        $result= $this->{$this->MODEL_NAME}->get($this->ser);
        $this->return_json($result[0]);
    }

    function public_get_json($parent=0){ // parent = branch
        if(intval($parent) <= 0 ) die();
        $result = $this->{$this->MODEL_NAME}->get_list($parent);
        $this->return_json($result);
    }

    function create(){
        $result= $this->{$this->MODEL_NAME}->create($this->_postedData());
        $this->Is_success($result);
        $this->return_json($result);
    }

    function edit_room(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->ser!=null){
                $res= $this->{$this->MODEL_NAME}->edit_room($this->_postedData_edit());
                if(intval($res) <= 0){
                    $this->print_error($res);
                }
            }
            echo intval($res);
        }
    }

/*
    function get_to_print(){
        if($_SERVER['REQUEST_METHOD'] != 'POST' or $this->employee_no== '')
            die('post_employee_no');
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_to_print($this->room_id);
        $this->load->view('evaluation_emps_structure_print',$data);
    }


    function edit(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= explode(",",$this->ser);
            $cnt=0;
            for($i=0; $i<count($this->ser); $i++){
                if($this->ser[$i]!=null and $this->manager_no!=null){
                    $res= $this->{$this->MODEL_NAME}->edit($this->ser[$i], $this->manager_no);
                    if(intval($res) == 1){
                        $cnt++;
                    }
                }
            }
            echo $cnt;
        }
    }

*/

    function delete(){
        $id = $this->input->post('id');
        $no = $this->input->post('no');
        //$this->IsAuthorized();
        if(false and is_array($id)){
            foreach($id as $val){
                //echo $this->{$this->MODEL_NAME}->delete($val);
            }
        }else{
            if( strlen($no) > 2  ){
                $res= $this->{$this->MODEL_NAME}->delete($id, $no);
                if($res==1){
                    echo 1;
                }else{
                    $this->print_error('E '.$res);
                }
            }
            else
                $this->print_error('لا يمكن حذف المقر');
        }
    }


    function get_emps(){
        $id = $this->input->post('id');
        $result = $this->{$this->EMPS_MODEL_NAME}->get_list($id);
        $this->return_json($result);
    }

    function public_get_emps_json(){
        $result = $this->{$this->EMPS_MODEL_NAME}->get_list('');
        $this->return_json($result);
    }

    function save_emps(){
        if($this->room_id!=''){
            for($i=0; $i<count($this->emp_no); $i++){
                if($this->ser_d[$i]== 0 and $this->emp_no[$i]!=''){ // create
                    $detail_seq= $this->{$this->EMPS_MODEL_NAME}->create($this->_postedDataEmps(null, $this->emp_no[$i], 'create'));
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }elseif(false and $this->ser_d[$i]!= 0 and $this->emp_no[$i]!=''){ // edit
                    $detail_seq= $this->{$this->EMPS_MODEL_NAME}->edit($this->_postedDataEmps($this->ser_d[$i], $this->emp_no[$i], 'edit'));
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }elseif(false and $this->ser_d[$i]!= 0 and $this->emp_no[$i]==''){ // delete
                    $detail_seq= $this->{$this->EMPS_MODEL_NAME}->delete($this->ser_d[$i]);
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }
            }
            echo 1;
        }else{
            echo "لم يتم ارسال رقم الغرفة";
        }
    }

    function public_get_room(){
        if(intval($this->emp_no)>0){
            $result = $this->{$this->EMPS_MODEL_NAME}->get_room($this->emp_no);
            $this->return_json($result);
        }else
            $this->print_error('اختر موظف');
    }

    function public_get_room_by_id(){
        if($this->customer_id !=''){
            $result = $this->{$this->EMPS_MODEL_NAME}->get_room_by_id($this->customer_id);
            $this->return_json($result);
        }else
            $this->print_error('اختر موظف');
    }

    function _postedData(){
        $result = array(
            array('name'=>'ROOM_PARENT','value'=>$this->room_parent ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_NAME','value'=>$this->room_name ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_TYPE','value'=>$this->room_type ,'type'=>'','length'=>-1),
        );
        return $result;
    }

    function _postedData_edit(){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_ID','value'=>$this->room_id ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_NAME','value'=>$this->room_name ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_TYPE','value'=>$this->room_type ,'type'=>'','length'=>-1),
        );
        return $result;
    }


    function _postedDataEmps($ser_d= null, $emp_no, $typ= null){
        $result = array(
            array('name'=>'SER_D','value'=>$ser_d ,'type'=>'','length'=>-1),
            array('name'=>'ROOM_ID','value'=>$this->room_id ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$emp_no ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }


}
