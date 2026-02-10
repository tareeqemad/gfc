<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 28/09/16
 * Time: 09:15 ص
 */

class Gedco_execute_gpolicy extends MY_Controller {

    var $MODEL_NAME= 'gedco_execute_gpolicy_model';
    var $PAGE_URL= 'hr/gedco_execute_gpolicy/public_get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('hr/evaluation_order_model');
        $this->load->model('settings/gcc_structure_model');// الإدارة
        $this->load->model('employees/employees_model');

        // vars
        $this->ser= $this->input->post('ser');
        $this->evaluation_order_id= $this->input->post('evaluation_order_id');
        $this->admin_id= $this->input->post('admin_id');
        $this->dept_manager_no= $this->input->post('dept_manager_no');
        $this->dept_id= $this->input->post('dept_id');
        $this->degree_no= $this->input->post('degree_no');
        $this->rating_number= $this->input->post('rating_number');
        $this->note= $this->input->post('note');
    }

    function index($page= 1, $ser= -1 ,$evaluation_order_id= -1, $admin_id= -1, $dept_manager_no= -1, $dept_id= -1, $rating_number= -1, $note= -1, $entry_user= -1){
        $data['title']='سياسة توزيع الدرجات للدوائر';
        $data['content']='gedco_execute_gpolicy_index';

        $data['page']=$page;
        $data['ser']= $ser;
        $data['evaluation_order_id']= $evaluation_order_id;
        $data['admin_id']= $admin_id;
        $data['dept_manager_no']= $dept_manager_no;
        $data['dept_id']= $dept_id;
        $data['rating_number']= $rating_number;
        $data['note']= $note;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function public_get_page($page= 1, $evaluation_order_id= -1, $admin_id= -1, $dept_manager_no= -1, $dept_id= -1){
        $this->load->library('pagination');

        $evaluation_order_id= $this->check_vars($evaluation_order_id,'evaluation_order_id');
        $admin_id= $this->check_vars($admin_id,'admin_id');
        $dept_manager_no= $this->check_vars($dept_manager_no,'dept_manager_no');
        $dept_id= $this->check_vars($dept_id,'dept_id');

        $where_sql= " where 1=1 ";
        $where_sql.= ($evaluation_order_id!= null)? " and evaluation_order_id= '{$evaluation_order_id}' " : '';
        $where_sql.= ($admin_id!= null)? " and admin_id= '{$admin_id}' " : '';
        $where_sql.= ($dept_manager_no!= null)? " and dept_manager_no= '{$dept_manager_no}' " : '';
        $where_sql.= ($dept_id!= null)? " and dept_id= '{$dept_id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' GEDCO_EXECUTE_GPOLICY_TB '.$where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('gedco_execute_gpolicy_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->SER= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->SER) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->SER);
            }else{
                echo intval($this->SER);
            }
        }else{
            $data['content']='gedco_execute_gpolicy_show';
            $data['title']= 'إدخال سياسة توزيع الدرجات للتقييم العام';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->ser=='' and $isEdit) or $this->evaluation_order_id=='' or $this->admin_id=='' or $this->dept_manager_no=='' or $this->dept_id=='' or $this->rating_number=='' or $this->note=='' )
            $this->print_error('يجب ادخال جميع البيانات');
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1 ))
            die('get');
        $data['execute_gpolicy_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;

        $data['content']='gedco_execute_gpolicy_show';
        $data['title']='سياسة توزيع الدرجات للتقييم العام';

        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }
    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $data['order_id'] = $this->evaluation_order_model->get_all();
        $data["employee"]=  $this->employees_model->get_all();         // مدير الإدارة (الموظفين)
        $data["gcc_structure"]= $this->gcc_structure_model->getStructure(1);         // الإدارة
        $data["gcc_structure_dep"]= $this->gcc_structure_model->getStructure(2); //الدوائر
        $data["degree"] = $this->constant_details_model->get_list(123);
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
    }

    function adopt(){
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        echo intval($res);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EVALUATION_ORDER_ID','value'=>$this->evaluation_order_id ,'type'=>'','length'=>-1),
            array('name'=>'ADMIN_ID','value'=>$this->admin_id ,'type'=>'','length'=>-1),
            array('name'=>'DEPT_MANAGER_NO','value'=>$this->dept_manager_no ,'type'=>'','length'=>-1),
            array('name'=>'DEPT_ID','value'=>$this->dept_id ,'type'=>'','length'=>-1),
            array('name'=>'RANGE_ORDER','value'=>$this->degree_no ,'type'=>'','length'=>-1),
            array('name'=>'RATING_NUMBER','value'=>$this->rating_number ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}