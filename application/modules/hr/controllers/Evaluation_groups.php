<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 27/06/16
 * Time: 11:09 ص
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Evaluation_groups extends MY_Controller{
		
		var $MODEL_NAME= 'evaluation_groups_model';
		var $EMP_MODEL_NAME= 'evaluation_groups_emp_model';
		var $TREE_MODEL_NAME= 'evaluation_groups_tree_model';
		var $PAGE_URL= 'hr/evaluation_groups/get_page';
	
	    function  __construct(){
			parent::__construct();
			$this->load->helper('form');
			$this->load->library('pagination');
			$this->load->model($this->MODEL_NAME);
			$this->load->model($this->EMP_MODEL_NAME);
			$this->load->model($this->TREE_MODEL_NAME);
			$this->load->model('settings/gcc_structure_model');
			$this->load->model('settings/constant_details_model');
			$this->load->model('employees/employees_model');
			
			$this->EVALUATION_ORDER_ID = $this->input->post('EVALUATION_ORDER_ID');
			$this->EGROUPS_SERIAL = $this->input->post('EGROUPS_SERIAL');
			$this->EVALUATION_GROUPS_TYPE = $this->input->post('EVALUATION_GROUPS_TYPE');
			$this->EMP_MANAGER_ID_S = $this->input->post('EMP_MANAGER_ID_S');
			$this->EVALUATION_ORDER_DATE = $this->input->post('EVALUATION_ORDER_DATE');
			$this->ENTRY_USER= $this->input->post('ENTRY_USER');
			
			$this->SER= $this->input->post('SER');
			$this->EMP_ID= $this->input->post('EMP_ID');
			$this->MANAGER= $this->input->post('MANAGER');
			$this->EMP_NOTE= $this->input->post('EMP_NOTE');
			
			$this->SER1= $this->input->post('SER1');
			$this->EMP_MANAGERS= $this->input->post('EMP_MANAGER_ID');
			$this->MANAGEMENT_NO= $this->input->post('MANAGEMENT_NO');
			$this->TREE_NOTE= $this->input->post('TREE_NOTE');
		}
		function index($page= 1, $EVALUATION_ORDER_ID= -1, $EGROUPS_SERIAL= -1, $EVALUATION_GROUPS_TYPE= -1 , $EMP_MANAGER_ID_S= -1, $EVALUATION_ORDER_DATE= -1, $ENTRY_USER= -1){
			///// 
			$data['content']='evaluation_groups_index';
			$data['title']='لجان تقييم الأداء للموظفين';
			add_js('jquery.hotkeys.js');
			add_css('select2_metro_rtl.css');
			add_js('select2.min.js');
			add_css('datepicker3.css');
            add_js('moment.js');
            add_js('bootstrap-datetimepicker.js');
			/// GET Params And Pass To View
			$data['page']=$page;
			$data['EVALUATION_ORDER_ID']= $EVALUATION_ORDER_ID;
			$data['EGROUPS_SERIAL']= $EGROUPS_SERIAL;
			$data['EVALUATION_GROUPS_TYPE']= $EVALUATION_GROUPS_TYPE;
			$data['EMP_MANAGER_ID_S']= $EMP_MANAGER_ID_S;
			$data['EVALUATION_ORDER_DATE']= $EVALUATION_ORDER_DATE;
			$data['ENTRY_USER']= $ENTRY_USER;
			///// Action
			$data['action'] = 'edit';
			/////
			///نوع التقييم//
			$data["extra_form"]=  $this->constant_details_model->get_list(115);							
			///// 
			///نوع اللجنة//
			$data["eval"]=  $this->constant_details_model->get_list(120);							
			/////
			///المدخل//
			$data["entry_user_all"]=  $this->get_entry_users('EVALUATION_GROUPS_TB');
			//////
			$this->load->view('template/template',$data);
		}
		function get_page($page= 1, $EVALUATION_ORDER_ID= -1, $EGROUPS_SERIAL= -1, $EVALUATION_GROUPS_TYPE= -1 , $EMP_MANAGER_ID_S= -1, $EVALUATION_ORDER_DATE= -1, $ENTRY_USER= -1){
		
			$EVALUATION_ORDER_ID= $this->check_vars($EVALUATION_ORDER_ID,'EVALUATION_ORDER_ID');
			$EGROUPS_SERIAL = $this->check_vars($EGROUPS_SERIAL,'EGROUPS_SERIAL');
			$EVALUATION_GROUPS_TYPE = $this->check_vars($EVALUATION_GROUPS_TYPE,'EVALUATION_GROUPS_TYPE');
			$EMP_MANAGER_ID_S = $this->check_vars($EMP_MANAGER_ID_S,'EMP_MANAGER_ID_S');
			$EVALUATION_ORDER_DATE = $this->check_vars($EVALUATION_ORDER_DATE,'EVALUATION_ORDER_DATE');
			$ENTRY_USER= $this->check_vars($ENTRY_USER,'ENTRY_USER');
			
			$where_sql = ' where 1=1 ';
			$where_sql.= ($EVALUATION_ORDER_ID!= null)? " and EVALUATION_ORDER_ID= '{$EVALUATION_ORDER_ID}' " : '';
			$where_sql.= ($EGROUPS_SERIAL!= null)? " and EGROUPS_SERIAL= '{$EGROUPS_SERIAL}' " : '';
			$where_sql.= ($EVALUATION_GROUPS_TYPE!= null)? " and EVALUATION_GROUPS_TYPE= '{$EVALUATION_GROUPS_TYPE}' " : '';
			$where_sql.= ($EMP_MANAGER_ID_S!= null)? " and EMP_MANAGER_ID= '{$EMP_MANAGER_ID_S}' " : '';
			$where_sql.= ($EVALUATION_ORDER_DATE!= null)? " and EVALUATION_ORDER_DATE= '{$EVALUATION_ORDER_DATE}' " : '';
			$where_sql.= ($ENTRY_USER!= null)? " and ENTRY_USER= '{$ENTRY_USER}' " : '';
			
			$config['base_url'] = base_url($this->PAGE_URL);
			$count_rs = $this->get_table_count('EVALUATION_GROUPS_TB'.$where_sql);
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
			///echo $where_sql;
			$data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
	
			$data['offset']=$offset+1;
			$data['page']=$page;
	
			$this->load->view('evaluation_groups_page',$data);
			
		}
		function get($id, $action= 'index'){
			$data['result'] = $this->{$this->MODEL_NAME}->get($id);
			$data['action'] = $action;
			$data['can_edit'] =count($data['result']) > 0?  ($this->user->id == $data['result'][0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
			$data['content']='evaluation_groups_show';
			$data['isCreate']= true;
			$data['title']='لجان تقييم الأداء للموظفين';
			
			add_js('jquery.hotkeys.js');
			add_css('select2_metro_rtl.css');
			add_js('select2.min.js');
			///نوع التقييم//
			$data["extra_form"]=  $this->constant_details_model->get_list(115);							
			/////
			///نوع اللجنة//
			$data["eval"]=  $this->constant_details_model->get_list(120);						
			/////
			///المدخل//
			$data["entry_user_all"]=  $this->get_entry_users('EVALUATION_GROUPS_TB');
			//////
			///الموظف//
			$data["employee"]=  json_encode($this->employees_model->get_all());
			$data["employ"]=  json_encode($this->employees_model->get_all());
			//////
			///الإدارة//
			$data["gcc_structure"]=  json_encode($this->gcc_structure_model->getStructure(1));
			//////
			$this->load->view('template/template',$data);
		}
		function public_get_details($id = 0){
			$id = $this->input->post('id') ? $this->input->post('id') : $id;
			$data['return_details'] = $this->{$this->EMP_MODEL_NAME}->get($id);
			$data['return_details1'] = $this->{$this->TREE_MODEL_NAME}->get($id);
			$this->load->view('evaluation_groups_details',$data);
		}
		function create(){
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				   $arr = array();
				   $arr2 = array();
				   $arr3 = array();
				   $arr4 = array();
				   for($i=0; $i<count($this->EMP_ID); $i++){
						if($this->EMP_ID[$i]!='' and $this->MANAGER[$i]!=''){
							if($this->MANAGER[$i] == 0 && $this->MANAGER[$i]+1 != 0){
								if(!in_array($this->MANAGER[$i],$arr)){
									array_push($arr,$this->MANAGER[$i]);
							    }else{
									$this->print_error('عليك اختيار رئيس لجنة واحد فقط');
								}
							}else{
                                array_push($arr,$this->MANAGER[$i]);
							}
							
							if($this->EMP_ID[$i] != $this->EMP_ID[$i]+1){
								if(!in_array($this->EMP_ID[$i],$arr2)){
									array_push($arr2,$this->EMP_ID[$i]);
							    }else{
									$this->print_error('هناك تكرار في أسماء الموظفين');
								}
							}else{
                                array_push($arr2,$this->EMP_ID[$i]);
							}
						}
					}
					$mange = 0;
					if(in_array($mange,$arr)){
					   $this->EGROUPS_SERIAL = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
					   if(intval($this->EGROUPS_SERIAL) <= 0){
							$this->print_error('لم يتم الحفظ'.'<br>'.$this->EGROUPS_SERIAL);
					   }else{
						   for($k=0;$k<count($arr);$k++){
							 $detail_seq = $this->{$this->EMP_MODEL_NAME}->create($this->_postedData_details(null, $this->EMP_ID[$k], $arr[$k], 
								$this->EMP_NOTE[$k], 'create'));
								if(intval($detail_seq) <= 0){
									$this->print_error('لم يتم الحفظ');
								}
						   }
						   for($j=0; $j<count($this->EMP_MANAGERS); $j++){
								if($this->EMP_MANAGERS[$j]!='' and $this->MANAGEMENT_NO[$j]!=''){
									
								    if($this->EMP_MANAGERS[$j] != $this->EMP_MANAGERS[$j]+1){
										if(!in_array($this->EMP_MANAGERS[$j],$arr3)){
											array_push($arr3,$this->EMP_MANAGERS[$j]);
										}else{
											$this->print_error('هناك تكرار في أسماء مدير الإدارة');
										}
									}else{
										array_push($arr3,$this->EMP_MANAGERS[$j]);
									}
									if($this->MANAGEMENT_NO[$j] != $this->MANAGEMENT_NO[$j]+1){
										if(!in_array($this->MANAGEMENT_NO[$j],$arr4)){
											array_push($arr4,$this->MANAGEMENT_NO[$j]);
										}else{
											$this->print_error('هناك تكرار في أسماء الإدارة');
										}
									}else{
										array_push($arr4,$this->MANAGEMENT_NO[$j]);
									}
									
									
									
									$detail_seq1 = $this->{$this->TREE_MODEL_NAME}->create($this->_postedData_details1(null,$this->EMP_MANAGERS[$j],$this->TREE_NOTE[$j],
									$this->MANAGEMENT_NO[$j],'create'));
									if(intval($detail_seq1) <= 0){
										$this->print_error('لم يتم الحفظ');
									}
								}
								
							}
							echo intval($this->EGROUPS_SERIAL);
					   }
					 }else{
						 $this->print_error('عليك اختيار رئيس لجنة واحد على الأقل');
					 }
			}else{
				$data['content']='evaluation_groups_show';
				$data['title']= 'ادخال لجنة تقييم لأداء الموظفين';
				$data['isCreate']= true;
				$data['action'] = 'index';
				$data['can_edit'] = false;
                add_js('jquery.hotkeys.js');
			    add_css('select2_metro_rtl.css');
			    add_js('select2.min.js');
				add_css('datepicker3.css');
                add_js('moment.js');
                add_js('bootstrap-datetimepicker.js');
				///نوع التقييم//
				$data["extra_form"]=  $this->constant_details_model->get_list(115);							
				/////
				///نوع اللجنة//
				$data["eval"]=  $this->constant_details_model->get_list(120);						
				/////
				///المدخل//
				$data["entry_user_all"]=  $this->get_entry_users('EVALUATION_GROUPS_TB');
				//////
				///الموظف//
				$data["employee"]=  json_encode($this->employees_model->get_all());
				$data["employ"]=  json_encode($this->employees_model->get_all());
				//////
				///الإدارة//
				$data["gcc_structure"]=  json_encode($this->gcc_structure_model->getStructure(1));
				//////
				$this->load->view('template/template',$data);
			}
		}
		function edit(){
			 if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				   $arr = array();
				   $arr2 = array();
				   $arr3 = array();
				   $arr4 = array();
				   for($i=0; $i<count($this->EMP_ID); $i++){
						if($this->EMP_ID[$i]!='' and $this->MANAGER[$i]!=''){
							if($this->MANAGER[$i] == 0 && $this->MANAGER[$i]+1 != 0){
								if(!in_array($this->MANAGER[$i],$arr)){
									array_push($arr,$this->MANAGER[$i]);
							    }else{
									$this->print_error('عليك اختيار رئيس لجنة واحد فقط');
								}
							}else{
                                array_push($arr,$this->MANAGER[$i]);
							}
							
							if($this->EMP_ID[$i] != $this->EMP_ID[$i]+1){
								if(!in_array($this->EMP_ID[$i],$arr2)){
									array_push($arr2,$this->EMP_ID[$i]);
							    }else{
									$this->print_error('هناك تكرار في أسماء الموظفين');
								}
							}else{
                                array_push($arr2,$this->EMP_ID[$i]);
							}
						}
					}
				    $mange = 0;
					if(in_array($mange,$arr)){
					   $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
					   if(intval($res) <= 0){
							$this->print_error('لم يتم الحفظ'.'<br>'.$res);
						}else{
						   for($k=0;$k<count($arr);$k++){
							 if($this->SER[$k]== 0  and $this->EMP_ID[$k]!='' and $this->MANAGER[$k]!=''){
								  $detail_seq1 = $this->{$this->EMP_MODEL_NAME}->create($this->_postedData_details(null, $this->EMP_ID[$k],$arr[$k],$this->EMP_NOTE[$k],
								  'create'));
									if(intval($detail_seq1) <= 0){
										$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq1);
									}
							 }elseif($this->SER[$k]!= 0  and $this->EMP_ID[$k]!='' and $this->MANAGER[$k]!=''){
								 $detail_seq2 = $this->{$this->EMP_MODEL_NAME}->edit($this->_postedData_details($this->SER[$k], $this->EMP_ID[$k], $arr[$k],
								 $this->EMP_NOTE[$k],'edit'));
								if(intval($detail_seq2) <= 0){
									$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq2);
								}
							 }
						   }
						   for($j=0; $j<count($this->EMP_MANAGERS); $j++){
								if($this->SER1[$j]== 0  and $this->EMP_MANAGERS[$j]!='' and $this->MANAGEMENT_NO[$j]!=''){
									if($this->EMP_MANAGERS[$j] != $this->EMP_MANAGERS[$j]+1){
										if(!in_array($this->EMP_MANAGERS[$j],$arr3)){
											array_push($arr3,$this->EMP_MANAGERS[$j]);
										}else{
											$this->print_error('هناك تكرار في أسماء مدير الإدارة');
										}
									}else{
										array_push($arr3,$this->EMP_MANAGERS[$j]);
									}
									if($this->MANAGEMENT_NO[$j] != $this->MANAGEMENT_NO[$j]+1){
										if(!in_array($this->MANAGEMENT_NO[$j],$arr4)){
											array_push($arr4,$this->MANAGEMENT_NO[$j]);
										}else{
											$this->print_error('هناك تكرار في أسماء الإدارة');
										}
									}else{
										array_push($arr4,$this->MANAGEMENT_NO[$j]);
									}
									$detail_seq3 = $this->{$this->TREE_MODEL_NAME}->create($this->_postedData_details1(null, $this->EMP_MANAGERS[$j], $this->TREE_NOTE[$j], 
									$this->MANAGEMENT_NO[$j],'create'));
									if(intval($detail_seq3) <= 0){
										$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq3);
									}
								}elseif($this->SER1[$j]!= 0  and $this->EMP_MANAGERS[$j]!='' and $this->MANAGEMENT_NO[$j]!=''){
									if($this->EMP_MANAGERS[$j] != $this->EMP_MANAGERS[$j]+1){
										if(!in_array($this->EMP_MANAGERS[$j],$arr3)){
											array_push($arr3,$this->EMP_MANAGERS[$j]);
										}else{
											$this->print_error('هناك تكرار في أسماء مدير الإدارة');
										}
									}else{
										array_push($arr3,$this->EMP_MANAGERS[$j]);
									}
									if($this->MANAGEMENT_NO[$j] != $this->MANAGEMENT_NO[$j]+1){
										if(!in_array($this->MANAGEMENT_NO[$j],$arr4)){
											array_push($arr4,$this->MANAGEMENT_NO[$j]);
										}else{
											$this->print_error('هناك تكرار في أسماء الإدارة');
										}
									}else{
										array_push($arr4,$this->MANAGEMENT_NO[$j]);
									}
									$detail_seq4 = $this->{$this->TREE_MODEL_NAME}->edit($this->_postedData_details1($this->SER1[$j], $this->EMP_MANAGERS[$j], 
									$this->TREE_NOTE[$j],$this->MANAGEMENT_NO[$j],'edit'));
									if(intval($detail_seq4) <= 0){
										$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq4);
									}	
								}
							}
						   
							echo 1;
					   }
					 }else{
						 $this->print_error('عليك اختيار رئيس لجنة واحد على الأقل');
					 }
				 /*$res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
				    if(intval($res) <= 0){
						$this->print_error('لم يتم الحفظ'.'<br>'.$res);
					}else{
						for($i=0; $i<count($this->EMP_ID); $i++){
							if($this->SER[$i]== 0  and $this->EMP_ID[$i]!='' and $this->MANAGER[$i]!='' and $this->EMP_NOTE[$i]!=''){
								$detail_seq1 = $this->{$this->EMP_MODEL_NAME}->create($this->_postedData_details(null, $this->EMP_ID[$i], $this->MANAGER[$i],$this->EMP_NOTE[$i],'create'));
								if(intval($detail_seq1) <= 0){
									$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq1);
								}
							}elseif($this->SER[$i]!= 0  and $this->EMP_ID[$i]!='' and $this->MANAGER[$i]!='' and $this->EMP_NOTE[$i]!=''){
								$detail_seq2 = $this->{$this->EMP_MODEL_NAME}->edit($this->_postedData_details($this->SER[$i], $this->EMP_ID[$i], $this->MANAGER[$i],$this->EMP_NOTE[$i],'edit'));
								if(intval($detail_seq2) <= 0){
									$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq2);
								}		
							}
						}
						
						for($j=0; $j<count($this->EMP_MANAGERS); $j++){
							if($this->SER1[$j]== 0  and $this->EMP_MANAGERS[$j]!='' and $this->TREE_NOTE[$j]!=''){
					    		$detail_seq3 = $this->{$this->TREE_MODEL_NAME}->create($this->_postedData_details1(null, $this->EMP_MANAGERS[$j], $this->TREE_NOTE[$j],'create'));
								if(intval($detail_seq3) <= 0){
									$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq3);
								}
							}elseif($this->SER1[$i]!= 0  and $this->EMP_MANAGERS[$i]!='' and $this->TREE_NOTE[$i]!=''){
								$detail_seq4 = $this->{$this->TREE_MODEL_NAME}->edit($this->_postedData_details1($this->SER1[$j], $this->EMP_MANAGERS[$j], $this->TREE_NOTE[$j],'edit'));
								if(intval($detail_seq4) <= 0){
									$this->print_error('لم يتم الحفظ'.'<br>'.$detail_seq4);
								}	
							}
						}
					}
					echo 1;*/
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
			$ret= $this->{$this->MODEL_NAME}->delete($this->EGROUPS_SERIAL);
			if(intval($ret) > 0)
				$this->print_error('لم يتم حفظ اللجنة: '.$msg);
			else
				$this->print_error('لم يتم حذف اللجنة: '.$msg);
		}
		function _postedData($type= null){
			$ArrayOfParams = array(
				array('name'=>'EGROUPS_SERIAL','value'=>$this->EGROUPS_SERIAL ,'type'=>'','length'=>-1),
				//array('name'=>'EVALUATION_ORDER_ID','value'=>$this->EVALUATION_ORDER_ID ,'type'=>'','length'=>-1),
				array('name'=>'EVALUATION_GROUPS_TYPE','value'=>$this->EVALUATION_GROUPS_TYPE ,'type'=>'','length'=>-1),
				array('name'=>'EMP_MANAGER_ID','value'=>$this->EMP_MANAGER_ID_S ,'type'=>'','length'=>-1),
				array('name'=>'EVALUATION_ORDER_DATE','value'=>$this->EVALUATION_ORDER_DATE,'type'=>'','length'=>-1)
			);
			if($type=='create')
				unset($ArrayOfParams[0]);
			return($ArrayOfParams);
		}
		function _postedData_details($SER= null,$Emp_id= null, $Manager= null, $Hints= null, $typ= null){
			$result = array(
				array('name'=>'SER','value'=>$SER ,'type'=>'','length'=>-1),
				array('name'=>'EGROUPS_SERIAL','value'=>$this->EGROUPS_SERIAL ,'type'=>'','length'=>-1),
				array('name'=>'EMP_ID','value'=>$Emp_id ,'type'=>'','length'=>-1),
				array('name'=>'MANAGER','value'=>$Manager ,'type'=>'','length'=>-1),
				array('name'=>'EMP_NOTE','value'=>$Hints ,'type'=>'','length'=>-1)
			);
			if($typ=='create')
				unset($result[0]);
			return $result;
		}
		function _postedData_details1($SER1= null,$EMP_MANAGERS= null, $TREE_NOTE= null, $MANAGER_NO= null, $typ= null){
			$result = array(
				array('name'=>'SER','value'=>$SER1 ,'type'=>'','length'=>-1),
				array('name'=>'EGROUPS_SERIAL','value'=>$this->EGROUPS_SERIAL ,'type'=>'','length'=>-1),
				array('name'=>'EMP_MANAGER_ID','value'=>$EMP_MANAGERS ,'type'=>'','length'=>-1),
				array('name'=>'TREE_NOTE','value'=>$TREE_NOTE ,'type'=>'','length'=>-1),
				array('name'=>'MANAGEMENT_NO','value'=>$MANAGER_NO ,'type'=>'','length'=>-1)
			);
			if($typ=='create')
				unset($result[0]);
				
			return $result;
		}
		
}
?>