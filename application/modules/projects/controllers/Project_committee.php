<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 28/07/16
 * Time: 09:55 ص
 */ 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Project_committee extends MY_Controller{
	  var $MODEL_NAME= 'project_committee_model';
	  var $MODEL_DET = 'project_committee_det_model';
	  var $PAGE_URL= 'projects/project_committee/get_page';
	  
	  function  __construct(){
			parent::__construct();
			$this->load->helper('form');
			$this->load->library('pagination');
			$this->load->model($this->MODEL_NAME);
			$this->load->model($this->MODEL_DET);
			$this->load->model('employees/employees_model');
			$this->load->model('settings/gcc_branches_model');
			// master vars
			$this->COMMITTEE_SER = $this->input->post('COMMITTEE_SER');
			$this->TITLES = $this->input->post('TITLES');
			$this->MASTER_TYPE = $this->input->post('THE_TYPE');
			$this->BRANCH = $this->input->post('BRANCH');
			$this->ENTRY_USER= $this->input->post('ENTRY_USER');
			// detail vars
			$this->SER= $this->input->post('SER');
			$this->EMP_ID= $this->input->post('EMP_ID');
			$this->THE_TYPE= $this->input->post('THE_TYPEE');
	  }
	  function index($page= 1, $COMMITTEE_SER= -1, $TITLES= -1, $MASTER_TYPE= -1 , $BRANCH= -1, $ENTRY_USER= -1){
			///// Call Important Page
			$data['content']='project_committee_index';
			$data['title']='لجان الاستلام';
			add_js('jquery.hotkeys.js');
			add_css('select2_metro_rtl.css');
			add_js('select2.min.js');
			/// GET Params And Pass To View
			$data['page']=$page;
			$data['COMMITTEE_SER']= $COMMITTEE_SER;
			$data['TITLES']= $TITLES;
			$data['MASTER_TYPE']= $MASTER_TYPE;
			$data['BRANCH']= $BRANCH;
			$data['ENTRY_USER']= $ENTRY_USER;
			///// Action
			$data['action'] = 'edit';
			/////
			///المدخل//
			$data["entry_user_all"]=  $this->get_entry_users('project_committee');
			//////
			////// الأفرع
			$data['branches'] = $this->gcc_branches_model->get_all();
			//////
			$this->load->view('template/template',$data);
		}
		function get_page($page= 1, $COMMITTEE_SER= -1, $TITLES= -1, $MASTER_TYPE= -1 , $BRANCH= -1, $ENTRY_USER= -1){
		    // Check Vars
			$COMMITTEE_SER= $this->check_vars($COMMITTEE_SER,'COMMITTEE_SER');
			$TITLES = $this->check_vars($TITLES,'TITLES');
			$MASTER_TYPE = $this->check_vars($MASTER_TYPE,'MASTER_TYPE');
			$BRANCH = $this->check_vars($BRANCH,'BRANCH');
			$ENTRY_USER= $this->check_vars($ENTRY_USER,'ENTRY_USER');
			/// Append IF Where
			$where_sql = ' where 1=1 ';
			$where_sql.= ($COMMITTEE_SER!= null)? " and COMMITTEE_SER= '{$COMMITTEE_SER}' " : '';
			$where_sql.= ($TITLES!= null)? " and TITLES= '{$TITLES}' " : '';
			$where_sql.= ($MASTER_TYPE!= null)? " and THE_TYPE= '{$MASTER_TYPE}' " : '';
			$where_sql.= ($BRANCH!= null)? " and BRANCH= '{$BRANCH}' " : '';
			$where_sql.= ($ENTRY_USER!= null)? " and ENTRY_USER= '{$ENTRY_USER}' " : '';
			// Pagenation
			$config['base_url'] = base_url($this->PAGE_URL);
			$count_rs = $this->get_table_count('project_committee'.$where_sql);
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
	
			$this->load->view('project_committee_page',$data);
			
		}
		function get($id, $action= 'index'){
			//Return Data From Parms Pass And Check If User Editable
			$data['result'] = $this->{$this->MODEL_NAME}->get($id);
			$data['action'] = $action;
			$data['can_edit'] =count($data['result']) > 0?  ($this->user->id == $data['result'][0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
			$data['content']='project_committee_show';
			$data['isCreate']= true;
			$data['title']='لجان الاستلام';
			// Call Important Page
			add_js('jquery.hotkeys.js');
			add_css('select2_metro_rtl.css');
			add_js('select2.min.js');
			///المدخل//
			$data["entry_user_all"]=  $this->get_entry_users('project_committee');
			//////
			///الموظف//
			$data["employee"]=  json_encode($this->employees_model->get_all());
			//////
			////// الأفرع
			$data['branches'] = $this->gcc_branches_model->get_all();
			//////
			$this->load->view('template/template',$data);
		}
		function public_get_details($id = 0){
			$id = $this->input->post('id') ? $this->input->post('id') : $id;
			$data['return_details'] = $this->{$this->MODEL_DET}->get($id);
			$this->load->view('project_committee_details',$data);
		}
		function create(){
			 if($_SERVER['REQUEST_METHOD'] == 'POST') {
				 $arr = array();
				 $arr2 = array();
				 for($i=0; $i<count($this->EMP_ID); $i++){
						if($this->EMP_ID[$i]!='' and $this->THE_TYPE[$i]!=''){
							if($this->THE_TYPE[$i] == 0 && $this->THE_TYPE[$i]+1 != 0){
								if(!in_array($this->THE_TYPE[$i],$arr)){
									array_push($arr,$this->THE_TYPE[$i]);
							    }else{
									$this->print_error('عليك اختيار رئيس لجنة واحد فقط');
								}
							}else{
                                array_push($arr,$this->THE_TYPE[$i]);
							}
							
							if($this->EMP_ID[$i] != $this->EMP_ID[$i]+1){
								if(!in_array($this->EMP_ID[$i],$arr2)){
									array_push($arr2,$this->EMP_ID[$i]);
							    }else{
									$this->print_error('هناك تكرار في أسماء الموظفين');
								}//end else
							}else{
                                array_push($arr2,$this->EMP_ID[$i]);
							}//end else
						}
				   }/// end for
				   $mange = 0;
				   if(in_array($mange,$arr)){
					   $this->COMMITTEE_SER = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
					   if(intval($this->COMMITTEE_SER) <= 0){
							$this->print_error('لم يتم الحفظ'.'<br>'.$this->COMMITTEE_SER);
					   }else{
						   for($k=0;$k<count($arr);$k++){
							    $detail_seq = $this->{$this->MODEL_DET}->create($this->_postedData_details(null, $this->EMP_ID[$k], $this->THE_TYPE[$k],  'create'));
								if(intval($detail_seq) <= 0){
									$this->print_error('لم يتم الحفظ');
								}//end if
						   }//end for
					   }//end else
					   echo intval($this->COMMITTEE_SER);
				   }else{
						 $this->print_error('عليك اختيار رئيس لجنة واحد على الأقل');
				   } 
			 }else{
				$data['content']='project_committee_show';
				$data['title']= 'ادخال لجنة الاستلام';
				$data['isCreate']= true;
				$data['action'] = 'index';
				$data['can_edit'] = false;
                add_js('jquery.hotkeys.js');
				add_css('select2_metro_rtl.css');
				add_js('select2.min.js');
				///الموظف//
				$data["employee"]=  json_encode($this->employees_model->get_all());
				//////
				////// الأفرع
				$data['branches'] = $this->gcc_branches_model->get_all();
				//////
				$this->load->view('template/template',$data);
			 }
		}
		function edit(){
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				 $arr = array();
				 $arr2 = array();
				 for($i=0; $i<count($this->EMP_ID); $i++){
						if($this->EMP_ID[$i]!='' and $this->THE_TYPE[$i]!=''){
							if($this->THE_TYPE[$i] == 0 && $this->THE_TYPE[$i]+1 != 0){
								if(!in_array($this->THE_TYPE[$i],$arr)){
									array_push($arr,$this->THE_TYPE[$i]);
							    }else{
									$this->print_error('عليك اختيار رئيس لجنة واحد فقط');
								}
							}else{
                                array_push($arr,$this->THE_TYPE[$i]);
							}
							
							if($this->EMP_ID[$i] != $this->EMP_ID[$i]+1){
								if(!in_array($this->EMP_ID[$i],$arr2)){
									array_push($arr2,$this->EMP_ID[$i]);
							    }else{
									$this->print_error('هناك تكرار في أسماء الموظفين');
								}//end else
							}else{
                                array_push($arr2,$this->EMP_ID[$i]);
							}//end else
						}
				   }/// end for
				   $mange = 0;
				   if(in_array($mange,$arr)){
					   $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
					   if(intval($res) <= 0){
							$this->print_error('لم يتم الحفظ'.'<br>'.$res);
					   }else{
						   for($k=0;$k<count($arr);$k++){
							 if($this->SER[$k]== 0  and $this->EMP_ID[$k]!='' and $this->THE_TYPE[$k]!=''){
								    $detail_seq = $this->{$this->MODEL_DET}->create($this->_postedData_details(null, $this->EMP_ID[$k], $this->THE_TYPE[$k], 'create'));
									if(intval($detail_seq) <= 0){
										$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq);
									}//end if
							 }elseif($this->SER[$k]!= 0  and $this->EMP_ID[$k]!='' and $this->THE_TYPE[$k]!=''){
								    $detail_seq2 = $this->{$this->MODEL_DET}->edit($this->_postedData_details($this->SER[$k], $this->EMP_ID[$k], $this->THE_TYPE[$k],'edit'));
									if(intval($detail_seq2) <= 0){
										$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq2);
									}
							 }
						   }//end for
						   echo 1;
					   }//end else
				   }else{
						 $this->print_error('عليك اختيار رئيس لجنة واحد على الأقل');
				   } 
			 }
		}
		function _post_validation($isEdit = false){
			if(!$isEdit and ($this->TITLES=='' or $this->THE_TYPES=='' or $this->BRANCH=='') ){
				$this->print_error('يجب ادخال البيانات الناقصة');
			}else if(!($this->EMP_ID) or count(array_filter($this->EMP_ID)) <= 0 ){
				$this->print_error('يجب ادخال عضو لجنة واحد على الأقل ');
			}else if (1){
				for($i=0;$i<count($this->EMP_ID);$i++){
					if($this->EMP_ID[$i]!='' and $this->THE_TYPE[$i]==''){
						$this->print_error('ادخل نوع العضو في اللجنة');
					}
				}
			}
		}
		function check_vars($var, $c_var){
			// if post take it, else take the parameter
			$var= ($this->{$c_var})? $this->{$c_var}:$var;
			// if val is -1 then null, else take the val
			$var= $var == -1 ?null:$var;
			return $var;
		}
		function _postedData($type= null){
			$ArrayOfParams = array(
				array('name'=>'COMMITTEE_SER','value'=>$this->COMMITTEE_SER ,'type'=>'','length'=>-1),
				array('name'=>'TITLES','value'=>$this->TITLES ,'type'=>'','length'=>-1),
				array('name'=>'THE_TYPE','value'=>$this->MASTER_TYPE ,'type'=>'','length'=>-1),
				array('name'=>'BRANCH','value'=>$this->BRANCH ,'type'=>'','length'=>-1)
			);
			if($type=='create')
				unset($ArrayOfParams[0]);
			return($ArrayOfParams);
		}
		function _postedData_details($SER= null,$EMP_ID= null, $THE_TYPE= null, $typ= null){
			$result = array(
				array('name'=>'SER','value'=>$SER ,'type'=>'','length'=>-1),
				array('name'=>'EMP_ID','value'=>$EMP_ID ,'type'=>'','length'=>-1),
				array('name'=>'THE_TYPE','value'=>$THE_TYPE ,'type'=>'','length'=>-1),
				array('name'=>'COMMITTEE_SER','value'=>$this->COMMITTEE_SER ,'type'=>'','length'=>-1)
			);
			if($typ=='create')
				unset($result[0]);
			if($typ=='edit')
				unset($result[3]);
			return $result;
		}
}
?>