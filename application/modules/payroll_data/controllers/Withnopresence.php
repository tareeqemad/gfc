<?php

class withnopresence extends MY_Controller {



    var $MODEL_NAME= 'withnopresence_model';
    var $PAGE_URL= 'payroll_data/withnopresence/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance/hr_attendance_model');

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';


        $this->emp_no= $this->input->post('emp_no');
        $this->branch_id= $this->input->post('branch_id');
        $this->entry_day= $this->input->post('entry_day');
        $this->entry_day_2= $this->input->post('entry_day_2');
        $this->status= $this->input->post('status');
        $this->the_month= $this->input->post('the_month');




    }

    function index($page= 1,$emp_no= -1,$branch_id = -1,$entry_day= -1, $entry_day_2= -1,$status = -1){

        $yesterday = new DateTime('-1 day');
        $yesterday= $yesterday->format('d/m/Y');
        $entry_day=($entry_day!=-1)?$entry_day:$yesterday;

        $data['title']='عرض الموظفين بدون بصمة  - المعتمد ';
        $data['content']='withnopresence_index';
        $data['page']=$page;

        $data['emp_no']= $emp_no;
        $data['branch_id']= $branch_id;
        $data['entry_day']= $entry_day;
        $data['entry_day_2']= $entry_day_2;
        $data['status']= $status;
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }


    function get_page($page= 1,$emp_no= -1,$branch_id = -1,$entry_day= -1, $entry_day_2= -1,$status = -1 ){
        $this->load->library('pagination');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $branch_id=$this->check_vars($branch_id,'branch_id');
        $entry_day= $this->check_vars($entry_day,'entry_day');
        $entry_day_2= $this->check_vars($entry_day_2,'entry_day_2');
        $status= $this->check_vars($status,'status');

        $where_sql = '';

        if(!$this->input->is_ajax_request()){
            $where_sql.= " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= {$this->user->branch}  ";

        }

        $where_sql.= ($emp_no!= null)? " and M.emp_no= '{$emp_no}' " : '';
        $where_sql.= ($branch_id!= null)? " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$branch_id}' " : '';
        $where_sql.= ($entry_day!= null or $entry_day_2!= null)? " and TRUNC(ttime) between nvl('{$entry_day}','01/01/1000') and nvl('{$entry_day_2}','01/01/3000') " : '';
        $where_sql.= ($status!= null)? " and M.status= '{$status}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' ATTENDACE_NO_ENTRY_LEAVE_TB  M  where 1=1   '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0? $count_rs[0]['NUM_ROWS']:0 ;
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
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        //echo '...'.$where_sql;
        $data['offset']=$offset+1;
        $data['page']=$page;
        $this->load->view('withnopresence_page',$data);

    }


    function index_financial($page= 1,$emp_no= -1,$the_month= -1,$branch_id = -1){

        $data['title']='عرض الموظفين بدون بصمة  - اعتماد الخصم مالياً ';
        $data['content']='withnopresence_f_index';
        $data['page']=$page;

        $data['emp_no']= $emp_no;
        $data['the_month']= $the_month;

        $data['branch_id']= $branch_id;
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }


    function get_page_f($page= 1,$emp_no= -1,$the_month= -1,$branch_id = -1){
        $this->load->library('pagination');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $branch_id=$this->check_vars($branch_id,'branch_id');
        $the_month=$this->check_vars($the_month,'the_month');


        $current_month = date('Ym') ;
        $where_sql = '';

        if(!$this->input->is_ajax_request()){
            $where_sql.= " and the_month= $current_month  ";
            $where_sql.= " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= {$this->user->branch}  ";

        }

        $where_sql.= ($emp_no!= null)? " and M.emp_no= '{$emp_no}' " : '';
        $where_sql.= ($branch_id!= null)? " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$branch_id}' " : '';
        $where_sql.= ($the_month!= null)? " and M.the_month= '{$the_month}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' NO_ENTRY_LEAVE_MV  M  where 1=1   '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0? $count_rs[0]['NUM_ROWS']:0 ;
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
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list1($where_sql, $offset , $row );
       // print_r($data['page_rows']);
        //echo '...'.$where_sql;
        $data['offset']=$offset+1;
        $data['page']=$page;
        $this->load->view('withnopresence_f_page',$data);

    }



    // اعتماد سجل بدون بصمة حضور لموظف
    function adopt_finical(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $emp_no= $this->input->post('emp_no');
            $the_month= $this->input->post('the_month');
            $count_day= $this->input->post('count_day');
            $res= $this->{$this->MODEL_NAME}->adopt_no_entry_leave($emp_no, $the_month, $count_day);
            if(intval($res) == 1 ){
                echo 1;
            }else {
                $this->print_error('Error_'.$res);
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

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['is_active_cons'] = $this->constant_details_model->get_list(335);
        $data['the_post_cons'] = $this->constant_details_model->get_list(336);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no ,'hr_admin');
        add_css('select2_metro_rtl.css');
        //  add_css('fontawesome/css/all.min.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }
}