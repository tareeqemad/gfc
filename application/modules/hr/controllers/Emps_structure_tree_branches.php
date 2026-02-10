<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/01/19
 * Time: 12:43 م
 */

// هيكلية النظام الاداري الخاصة بتقييم الاداء للمقرات
class emps_structure_tree_branches extends MY_Controller{

    var $MODEL_NAME= 'evaluations_emps_structure_model';
    var $JOBS_MODEL_NAME= 'evaluation_jobs_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->JOBS_MODEL_NAME);
        $this->load->model('settings/constant_details_model');

        // vars
        $this->ser= $this->input->post('ser');
        $this->manager_no= $this->input->post('manager_no');
        $this->employee_no= $this->input->post('employee_no');
        $this->job_id= $this->input->post('job_id');
        $this->supervision= $this->input->post('supervision');
        $this->alternative_manager_no= $this->input->post('alternative_manager_no');

        $this->branch= $this->user->branch;

    }

    function index(){
        $this->load->helper('generate_list');
        $this->load->model('employees/employees_model');
        $data['employee_all'] = $this->employees_model->get_list($this->branch ,0,5000); // $this->employees_model->get_all();
        $data['jobs_all'] = $this->{$this->JOBS_MODEL_NAME}->get_all();
        ///المسمى الإشرافي//
        $data["extra_name"]=  $this->constant_details_model->get_list(116);

        add_css('combotree.css');
        add_js('jquery.tree.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['title']=' هيكلية تبعية الموظفين لتقييم الاداء للمقر';
        $data['content']='evaluation_emps_structure_branches_index';

        $branch_manager= $this->{$this->MODEL_NAME}->get_branch_manager($this->branch);
        $branch_manager= $branch_manager[0]['EMPLOYEE_NO'];

        $data['branch_manager'] = $branch_manager;
		
		if(1){
			if($this->branch==2){
				$branch_manager= 569;
			}else if($this->branch==3){
				$branch_manager= 1486;
			}else if($this->branch==4){
				$branch_manager= 1347;
			}else if($this->branch==6){
				$branch_manager= 507;
			}else if($this->branch==7){
				$branch_manager= 468;
			}
		}
		

        if($branch_manager > 0){

            $resource =  $this->_get_structure($branch_manager,1);

            $options = array(
                'template_head'=>'<ul>',
                'template_foot'=>'</ul>',
                'use_top_wrapper'=>false
            );

            $template = '<li ><span data-id="{SER}" data-no="{EMPLOYEE_NO}" ondblclick="javascript:get_emp();"><i class="glyphicon glyphicon-minus-sign"></i> <input type="checkbox" class="checkboxes" value="{SER}" /> {EMPLOYEE_NO}:{EMPLOYEE_NO_NAME} <div0 style="font-weight: bold; color: #ffb848" title="عدد الموظفين">{CNT}</div0> </span>{SUBS}</li>';

            $data['tree'] = '<ul class="tree" id="emps_structure_tree">'.generate_list($resource, $options, $template).'</ul>';

        }else{
            $data['tree'] = 'لا يوجد مدير للمقر، او يوجد اكثر من مدير للمقر، يرجى مراجعة الشؤون الادارية في المقر الرئيسي';
        }

        $data['help']=$this->help;

        $this->load->view('template/template',$data);
    }

    function _get_structure($parent, $is_branch_manager=0) {
        $result = $this->{$this->MODEL_NAME}->get_child($parent, $is_branch_manager);

        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['EMPLOYEE_NO']);
            $i++;
        }
        return $result;
    }

    function get_to_print(){
        if($_SERVER['REQUEST_METHOD'] != 'POST' or $this->employee_no== '')
            die('post_employee_no');
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_to_print($this->employee_no);
        $this->load->view('evaluation_emps_structure_print',$data);
    }
/*
    function create(){
        $result= $this->{$this->MODEL_NAME}->create($this->_postedData()); /////////////// branch
        $this->Is_success($result);
        $this->return_json($result);
    }
*/
    function get(){
        $result= $this->{$this->MODEL_NAME}->get($this->ser);
        $this->return_json($result[0]);
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

    function edit_emp(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->ser!=null){
                $res= $this->{$this->MODEL_NAME}->edit_emp($this->ser, $this->job_id, $this->supervision, $this->alternative_manager_no);
                if(intval($res) <= 0){
                    $this->print_error($res);
                }
            }
            echo intval($res);
        }
    }

    function _postedData(){
        $result = array(
            //array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'MANAGER_NO','value'=>$this->manager_no,'type'=>'','length'=>-1),
            array('name'=>'EMPLOYEE_NO','value'=>$this->employee_no,'type'=>'','length'=>-1),
            array('name'=>'JOB_ID','value'=>$this->job_id,'type'=>'','length'=>-1),
            array('name'=>'SUPERVISION','value'=>$this->supervision,'type'=>'','length'=>-1),
            array('name'=>'ALTERNATIVE_MANAGER_NO','value'=>$this->alternative_manager_no,'type'=>'','length'=>-1),
        );
        return $result;
    }

}
