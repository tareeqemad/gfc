<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 29/09/16
 * Time: 12:36 م
 */

class Evaluation_order_archives extends MY_Controller {
    var $MODEL_NAME= 'evaluation_emp_order_model';
    var $PAGE_URL= 'hr/evaluation_order_archives/public_get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('employees/employees_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr/evaluation_order_model');

        $this->evaluation_order_serial= $this->input->post('evaluation_order_serial');
        $this->emp_no= $this->input->post('emp_no');
        $this->emp_manager_id= $this->input->post('emp_manager_id');
        $this->management_manager_no= $this->input->post('management_manager_no');
        $this->evaluation_order_id= $this->input->post('evaluation_order_id');
        $this->degree_no= $this->input->post('degree_no');
    }

    function index($page= 1,$evaluation_order_id= -1, $emp_no= -1, $emp_manager_id= -1, $management_manager_no= -1, $degree_no= -1){
        $data['title']='ارشيف التقييمات';
        $data['content']='evaluation_order_archives_index';

        $data['page']=$page;
        $data['evaluation_order_id']= $evaluation_order_id;
        $data['emp_no']= $emp_no;
        $data['emp_manager_id']= $emp_manager_id;
        $data['management_manager_no']= $management_manager_no;
        $data['degree_no']= $degree_no;

        $data['admin_manager'] = 0;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function public_get_page($page= 1,$evaluation_order_id= -1, $emp_no= -1, $emp_manager_id= -1, $management_manager_no= -1, $degree_no= -1){
        $this->load->library('pagination');

        $evaluation_order_id= $this->check_vars($evaluation_order_id,'evaluation_order_id');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $emp_manager_id= $this->check_vars($emp_manager_id,'emp_manager_id');
        $management_manager_no= $this->check_vars($management_manager_no,'management_manager_no');
        $degree_no= $this->check_vars($degree_no,'degree_no');

        $where_sql= " ";
        $where_sql.= ($evaluation_order_id!= null)? " and d.evaluation_order_id= '{$evaluation_order_id}' " : '';
        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= ($emp_manager_id!= null)? " and emp_manager_id= '{$emp_manager_id}' " : '';
        $where_sql.= ($management_manager_no!= null)? " and HR_PKG.GET_MANAGEMENT_MANAGER(D.EMP_NO)= '{$management_manager_no}' " : '';
        $where_sql.= ($degree_no!= null)? " and HR_PKG.GET_DEGREE_ID( COALESCE(D.final_mark, D.final_mark_after_objection, D.final_mark_before_objection, D.final_mark_before_audit) )= '{$degree_no}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' EVALUATION_EMP_ORDER_TB d where hr_pkg.GET_FORM_TYPE_BY_ORDER_DET(d.evaluation_order_serial)= 1 '.$where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_archives_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;
        $data['is_paging']=1;
        $data['admin_manager'] = 0;

        $this->load->view('evaluation_order_archives_page',$data);
    }

    function admin_manager($page= 1,$evaluation_order_id= -1, $emp_no= -1, $emp_manager_id= -1, $management_manager_no= -1, $degree_no= -1){
        $data['title']='اعتماد مدير الادارة/المقر';
        $data['content']='evaluation_order_archives_index';

        $data['page']=$page;
        $data['evaluation_order_id']= $evaluation_order_id;
        $data['emp_no']= $emp_no;
        $data['emp_manager_id']= $emp_manager_id;
        $data['management_manager_no']= $management_manager_no;
        $data['degree_no']= $degree_no;

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $data['admin_manager'] = 1;
        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function public_get_page_admin_manager($page= 1,$evaluation_order_id= -1, $emp_no= -1, $emp_manager_id= -1, $management_manager_no= -1, $degree_no= -1){
    /*
        $this->load->library('pagination');

        $evaluation_order_id= $this->check_vars($evaluation_order_id,'evaluation_order_id');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $emp_manager_id= $this->check_vars($emp_manager_id,'emp_manager_id');
        $degree_no= $this->check_vars($degree_no,'degree_no');

        $where_sql= " ";
        $where_sql.= ($evaluation_order_id!= null)? " and evaluation_order_id= '{$evaluation_order_id}' " : '';
        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= ($emp_manager_id!= null)? " and emp_manager_id= '{$emp_manager_id}' " : '';
        $where_sql.= ($degree_no!= null)? " and degree_no= '{$degree_no}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' EVALUATION_ORDER_TB '.$where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_archives_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;
    */
        $data['offset']=1;
        $data['page']=1;
        $data['is_paging']=0;
        $data['admin_manager'] = 1;

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_admin_manager();

        $this->load->view('evaluation_order_archives_page',$data);
    }

    function check_vars($var, $c_var){
        if($c_var=='management_manager_no'){
            // if post take it, else take the parameter
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        }else{
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        }
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function _look_ups(&$data){
        $data['order_id'] = $this->evaluation_order_model->get_all();
        $data["employee"]=  $this->employees_model->get_all();         // مدير الإدارة (الموظفين)
        $data["degree"] = $this->constant_details_model->get_list(123); // التقدير
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }

}

