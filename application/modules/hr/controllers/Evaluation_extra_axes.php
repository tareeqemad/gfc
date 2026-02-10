<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 13/06/16
 * Time: 10:34 ص
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Evaluation_extra_axes extends MY_Controller{

    var $MODEL_NAME= 'evaluation_extra_axes_model';
	var $ASK_MODEL_NAME= 'evaluation_extra_axes_ask_model';
	var $PAGE_URL= 'hr/evaluation_extra_axes/get_page';

    function  __construct(){
        parent::__construct();
		$this->load->helper('form');
		$this->load->library('pagination');
		$this->load->model($this->MODEL_NAME);
		$this->load->model($this->ASK_MODEL_NAME);
		$this->load->model('settings/constant_details_model');
		
		$this->eextra_id = $this->input->post('eextra_id');
        $this->eextra_form_id = $this->input->post('eextra_form_id');
        $this->note = $this->input->post('note');
		$this->eextra_name = $this->input->post('eextra_name');
        $this->eextra_relative_weight = $this->input->post('eextra_relative_weight');
		$this->eextra_supervision_weight = $this->input->post('eextra_supervision_weight');
		$this->entry_user= $this->input->post('entry_user');
		
		$this->extra_element_id= $this->input->post('eextra_element_id');
        $this->extra_element_name= $this->input->post('element_name');
        $this->extra_element_weight= $this->input->post('element_weight');
        $this->extra_element_order= $this->input->post('element_order');
		$this->elem_id= $this->input->post('elem_id');
    }
	function index($page= 1, $eextra_id= -1, $eextra_form_id= -1, $note= -1 , $eextra_name= -1, $eextra_relative_weight= -1, $eextra_supervision_weight= -1, $entry_user= -1){
		///// 
		$data['content']='evaluation_extra_axes_index';
        $data['title']='التقييمات الإضافية للوظائف';
		add_js('jquery.hotkeys.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
		/// GET Params And Pass To View
		$data['page']=$page;
		$data['eextra_id']= $eextra_id;
        $data['eextra_form_id']= $eextra_form_id;
        $data['note']= $note;
		$data['eextra_name']= $eextra_name;
        $data['eextra_relative_weight']= $eextra_relative_weight;
		$data['eextra_supervision_weight']= $eextra_supervision_weight;
		$data['entry_user']= $entry_user;
		///// Action
		$data['action'] = 'edit';
		///// 
		///نوع التقييم//
		$data["extra_form"]=  $this->constant_details_model->get_list(115);							
		/////
		///المسمى الإشرافي//
		$data["extra_name"]=  $this->constant_details_model->get_list(116);							
		/////
		///المدخل//
		$data["entry_user_all"]=  $this->get_entry_users('EVALUATION_EXTRA_AXES_TB');
		//////
		$this->load->view('template/template',$data);
	}
	function get_page($page= 1, $eextra_id= -1, $eextra_form_id= -1, $note = -1 , $eextra_name= -1, $eextra_relative_weight= -1, $eextra_supervision_weight= -1, $entry_user= -1){
		
		$eextra_id= $this->check_vars($eextra_id,'eextra_id');
        $eextra_form_id = $this->check_vars($eextra_form_id,'eextra_form_id');
        $note = $this->check_vars($note,'note');
		$eextra_name = $this->check_vars($eextra_name,'eextra_name');
        $eextra_relative_weight = $this->check_vars($eextra_relative_weight,'eextra_relative_weight');
		$eextra_supervision_weight = $this->check_vars($eextra_supervision_weight,'eextra_supervision_weight');
		$entry_user= $this->check_vars($entry_user,'entry_user');

		$where_sql = ' where 1=1 ';
        $where_sql.= ($eextra_id!= null)? " and eextra_id= '{$eextra_id}' " : '';
        $where_sql.= ($eextra_form_id!= null)? " and eextra_form_id= '{$eextra_form_id}' " : '';
        $where_sql.= ($note!= null)? " and note= '{$note}' " : '';
		$where_sql.= ($eextra_name!= null)? " and supervision= '{$eextra_name}' " : '';
        $where_sql.= ($eextra_relative_weight!= null)? " and relative_weight= '{$eextra_relative_weight}' " : '';
		$where_sql.= ($eextra_supervision_weight!= null)? " and SUPERVISION_WEIGHT = '{$eextra_supervision_weight}' " : '';
		$where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';
		
		$config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('evaluation_extra_axes_tb'.$where_sql);;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );
		//echo $where_sql;
		$data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('evaluation_extra_axes_page',$data);
		
	}
	function get($id, $action= 'index'){
		$data['result'] = $this->{$this->MODEL_NAME}->get($id);
        $data['action'] = $action;
		$data['can_edit'] =count($data['result']) > 0?  ($this->user->id == $data['result'][0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
		$data['content']='evaluation_extra_axes_show';
        $data['isCreate']= true;
        $data['title']='التقييمات الإضافية للوظائف';
		
		add_js('jquery.hotkeys.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
		///نوع التقييم//
		$data["extra_form"]=  $this->constant_details_model->get_list(115);							
		/////
		///المسمى الإشرافي//
		$data["extra_name"]=  $this->constant_details_model->get_list(116);							
		/////
		///المدخل//
		$data["entry_user_all"]=  $this->get_entry_users('EVALUATION_EXTRA_AXES_TB');
		//////
		$this->load->view('template/template',$data);
	}
	function public_get_details($id = 0){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['return_details'] = $this->{$this->ASK_MODEL_NAME}->get($id);
        $this->load->view('evaluation_extra_axes_details',$data);
    }
	function create(){
	   if($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $this->_post_validation();
            $this->eextra_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
			if(intval($this->eextra_id) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->eextra_id);
            }else{
				//echo count($this->extra_element_order);
				for($i=0; $i<count($this->extra_element_order); $i++){
					
					if($this->extra_element_name[$i]!='' and $this->extra_element_weight[$i]!='' and $this->extra_element_order[$i]!=''){
						
						$ask_extra= $this->{$this->ASK_MODEL_NAME}->create($this->_postedData_details($this->extra_element_name[$i], 
						$this->extra_element_weight[$i], $this->extra_element_order[$i], null ,'create'));
                        
						if(intval($ask_extra) <= 0){
                            $this->print_error_del($ask_extra);
                        }
					}
				}
				echo intval($this->eextra_id);
			}
	   }else{
		    $data['content']='evaluation_extra_axes_show';
            $data['title']= 'ادخال تقييم إضافي';
            $data['action'] = 'index';
			$data['isCreate']= true;
			$data['can_edit'] = false;
            add_js('jquery.hotkeys.js');
            add_css('select2_metro_rtl.css');
            add_js('select2.min.js');
			///نوع التقييم//
			$data["extra_form"]=  $this->constant_details_model->get_list(115);							
			/////
			///المسمى الإشرافي//
			$data["extra_name"]=  $this->constant_details_model->get_list(116);							
			/////
			///المدخل//
			$data["entry_user_all"]=  $this->get_entry_users('EVALUATION_EXTRA_AXES_TB');
			//////
			$this->load->view('template/template',$data);
	   }
	}
	function edit(){
		 if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->_post_validation(true);
            /*$res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
			if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
				for($i=0; $i<count($this->elem_id); $i++){
					
					if($this->elem_id[$i]==0 and $this->extra_element_weight[$i]!='' and $this->extra_element_order[$i]!=''){
						$ask_extra= $this->{$this->ASK_MODEL_NAME}->create($this->_postedData_details($this->extra_element_name[$i], 
						$this->extra_element_weight[$i], $this->extra_element_order[$i], null ,'create'));
						if(intval($ask_extra) <= 0){
                            $this->print_error($ask_extra);
                        }
					}
				}
			}
			echo 1;*/
			   for($i=0; $i<count($this->elem_id); $i++){
					if($this->elem_id[$i]==0 and $this->extra_element_weight[$i]!='' and $this->extra_element_order[$i]!=''){
						$ask_extra= $this->{$this->ASK_MODEL_NAME}->create($this->_postedData_details($this->extra_element_name[$i], 
						$this->extra_element_weight[$i], $this->extra_element_order[$i], null ,'create'));
						if(intval($ask_extra) <= 0){
                            $this->print_error($ask_extra);
                        }
					}
				}
				echo 1;
		 }
	}
	function change_status($status){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$check = $this->input->post('c');
			for($i=0;$i<count($check);$i++){
				$result = $this->{$this->ASK_MODEL_NAME}->update($check[$i],$status);
			}
			if(intval($result) <= 0){
                $this->print_error('لم يتم تغيير الحالة'.'<br>'.$result);
            }
            echo 1;
		}
	}
	function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
	function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->eextra_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ التقييم: '.$msg);
        else
            $this->print_error('لم يتم حذف التقييم: '.$msg);
    }
	function _post_validation($isEdit = false){
        if(!$isEdit and ($this->eextra_form_id=='' or $this->eextra_relative_weight=='') ){
            $this->print_error('يجب ادخال نوع التقييم و الوزن النسبي');
        }else if(!($this->extra_element_order) or count(array_filter($this->extra_element_order)) <= 0 ){
            $this->print_error('يجب ادخال سؤال واحد على الاقل ');
        }else if (1){
            for($i=0;$i<count($this->extra_element_order);$i++){
                if($this->extra_element_order[$i]!='' and $this->extra_element_weight[$i]=='' )
                    $this->print_error('ادخل الوزن النسبي');
                elseif($this->extra_element_order[$i]!='' and $this->extra_element_name[$i]=='' )
                    $this->print_error('ادخل اسم السؤال');
            }
        }
    }
	function _postedData($type= null){
        $ArrayOfParams = array(
            array('name'=>'EEXTRA_ID','value'=>$this->eextra_id ,'type'=>'','length'=>-1),
            array('name'=>'EEXTRA_FORM_ID','value'=>$this->eextra_form_id ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
			array('name'=>'SUPERVISION','value'=>$this->eextra_name ,'type'=>'','length'=>-1),
            array('name'=>'RELATIVE_WEIGHT','value'=>$this->eextra_relative_weight,'type'=>'','length'=>-1),
			array('name'=>'SUPERVISION_WEIGHT','value'=>$this->eextra_supervision_weight,'type'=>'','length'=>-1)
        );
        if($type=='create')
            unset($ArrayOfParams[0]);
        return($ArrayOfParams);
    }
	function _postedData_details($element_name= null, $element_weight= null, $element_order= null, $element_id,$typ= null){
		$result = array(
            array('name'=>'EEXTRA_ELEMENT_ID','value'=>$element_id ,'type'=>'','length'=>-1),
			array('name'=>'ELEMENT_NAME','value'=>$element_name ,'type'=>'','length'=>-1),
			array('name'=>'ELEMENT_WEIGHT','value'=>$element_weight ,'type'=>'','length'=>-1),
			array('name'=>'ELEMENT_ORDER','value'=>$element_order ,'type'=>'','length'=>-1),
			array('name'=>'EEXTRA_ID','value'=>$this->eextra_id ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        elseif($typ=='edit')
            unset($result[1]);
        return $result;
	}

}