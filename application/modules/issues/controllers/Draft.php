<?php

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 08/08/20
 * Time: 09:01 ص
 */

class draft extends MY_Controller{

    var $MODEL_NAME= 'draft_model';
    var $MODULE_NAME= 'issues';
    var $TB_NAME= 'draft';
    var $PAGE_URL= 'issues/draft/public_get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

    }

    /********************************************************************************************************************************/

    function index($page = 1,$Req='',$type=''){
        $data['page'] = $page;
        $data['Req'] = $Req;
        $data['type'] = $type;
        $data['title']='الكمبيالات';
        $data['content']='draft_index';
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }
    function public_get_page($page = 1)
    {
       
if($this->p_service_type!='')
{
                        $this->load->library('pagination');
                        $where_sql = "";

                        $where_sql .= isset($this->p_request_app_serial) && $this->p_request_app_serial != null ? " AND  REQUEST_APP_SERIAL  ={$this->p_request_app_serial}  " : "";
                        $where_sql .= isset($this->p_applicant_name) && $this->p_applicant_name != null ? " AND  APPLICANT_NAME LIKE '%{$this->p_applicant_name}%' " : "";
                        $where_sql .= isset($this->p_subscriber) && $this->p_subscriber != null ? " AND  REQUEST_SUBSCRIBER  ={$this->p_subscriber}  " : "";
                        $where_sql .= isset($this->p_applicant_id) && $this->p_applicant_id != null ? " AND  APPLICANT_ID  ={$this->p_applicant_id}  " : "";
                        $where_sql .= isset($this->p_paid) && $this->p_paid != null ? " AND  IS_PAID_  ={$this->p_paid}  " : "";
		        if($this->user->branch !=1)
			$where_sql .= " AND  BRANCH_ID  ={$this->user->branch} ";
                        else
                        $where_sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  BRANCH_ID  ={$this->p_branch} " : "";
                        //$where_sql .= isset($this->p_service_type) && $this->p_service_type != null ? " AND  APPLICANT_ID  ={$this->p_service_type}  " : "";
                $config['base_url'] = base_url($this->PAGE_URL);
						if($this->p_service_type == 1)
                        {
                        $count_rs = $this->get_table_count('TSG.CONTRI_PAID_APROVE_TB', $where_sql);
						$procedure='CONTRI_PAID_APROVE_TB_GET_LIST';
						}
						else 	if($this->p_service_type == 2)
                        {
                        $count_rs = $this->get_table_count('TSG.CONTRI_PAID_APROVE_TB', $where_sql);
						$procedure='CONTRI_PAID_APROVE_TB_GET_LIST';
						}
					    $config['use_page_numbers'] = TRUE;
                        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
                        $config['per_page'] = $this->page_size;
                        $config['num_links'] = 20;
                        $config['cur_page'] = $page;

                        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
                        $config['full_tag_close'] = '</ul></div>';
                        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
                        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
                        $config['cur_tag_open'] = '<li class="active"><span><b>';
                        $config['cur_tag_close'] = "</b></span></li>";

                        $this->pagination->initialize($config);

                        $offset = (((($page) - 1) * $config['per_page']));
                        $row = ((($page) * $config['per_page']));
					    $data['page_rows']=$this->{$this->MODEL_NAME}->get_list($procedure,$where_sql, $offset, $row);
                        $data['offset'] = $offset + 1;
                        $data['page'] = $page;
            $this->_look_ups($data);
				if($this->p_service_type == 1)
                        {
                        $this->load->view('draft_page', $data);
						}
             else if($this->p_service_type == 2)
                        {
                         $this->load->view('installment_page', $data);
						}
        }
  
    }
    /*******************************************************************/
    function adopt(){
       if($this->p_SUB_NO != '' && $this->p_SUB_NAME != '' && $this->p_ID != '' && $this->p_SER_ACTION != '')                          
       $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_SUB_NO,$this->p_SUB_NAME,$this->p_ID,$this->p_SER_ACTION,1,$this->user->id,$this->user->branch,2);
       else
       $this->print_error('لم يتم الاعتماد'.'<br>'.'لعدم اكتمال احد البيانات التالية: رقم المشترك,اسم المشترك,رقم هوية المشترك,رقم الطلب!!');

        if(!intval($res) ){
           // $this->print_error('لم يتم الاعتماد عدد الأقساط غير مساوي لعدد المرفقات!!'.'<br>'.$res);
echo 'لم يتم الاعتماد عدد الأقساط غير مساوي لعدد المرفقات!!';
      
        }
        else 
           echo  1;

    }
  /*******************************************************************/
    function unadopt(){
       if($this->p_SUB_NO != '' && $this->p_SUB_NAME != '' && $this->p_ID != '' && $this->p_SER_ACTION != '')                          
       $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_SUB_NO,$this->p_SUB_NAME,$this->p_ID,$this->p_SER_ACTION,1,$this->user->id,$this->user->branch,1);
       else
       $this->print_error('لم يتم  الغاء الاعتماد'.'<br>'.'لعدم اكتمال احد البيانات التالية: رقم المشترك,اسم المشترك,رقم هوية المشترك,رقم الطلب!!');

        if(intval($res) <= 0){
            $this->print_error('لم يتم  الغاء الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;

    }
/*********************************************/
   function print(){


    }
    /***************************************************************/
    function _look_ups(&$data){
        add_css('combotree.css');
        add_css('datepicker3.css');
        add_js('jquery.hotkeys.js');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['service_type'] = $this->constant_details_model->get_list(366);
          // $data['branches_all'] = $this->gcc_branches_model->get_all();
        if ($this->user->branch==1)
        {

            $data['branches'] = $this->gcc_branches_model->get_all();
        }
        else
        {
            $data['branches'] =$this->gcc_branches_model->user_branch($this->user->id);

        }

       }


}