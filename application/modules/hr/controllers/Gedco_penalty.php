<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 28/09/16
 * Time: 05:26 م
 */

class Gedco_penalty extends MY_Controller {

    var $MODEL_NAME= 'gedco_penalty_model';
    var $PAGE_URL= 'hr/gedco_penalty/public_get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('hr/evaluation_order_model');
        $this->load->model('employees/employees_model');

        // vars
        $this->ser= $this->input->post('ser');
        $this->evaluation_order_id= $this->input->post('evaluation_order_id');
        $this->emp_id= $this->input->post('emp_id');
        $this->discount= $this->input->post('discount');
        $this->note= $this->input->post('note');
    }

    function index($page= 1, $ser= -1 ,$evaluation_order_id= -1, $emp_id= -1){
        $data['title']='جزاءات التقييم';
        $data['content']='gedco_penalty_index';

        $data['page']=$page;
        $data['ser']= $ser;
        $data['evaluation_order_id']= $evaluation_order_id;
        $data['emp_id']= $emp_id;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function public_get_page ($page= 1,$evaluation_order_id= -1, $emp_id= -1){
        $this->load->library('pagination');

        $evaluation_order_id= $this->check_vars($evaluation_order_id,'evaluation_order_id');
        $emp_id= $this->check_vars($emp_id,'emp_id');

        $where_sql= " where 1=1 ";
        $where_sql.= ($evaluation_order_id!= null)? " and evaluation_order_id= '{$evaluation_order_id}' " : '';
        $where_sql.= ($emp_id!= null)? " and emp_id= '{$emp_id}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('GEDCO_PENALTY_TB'.$where_sql);
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

        $this->load->view('gedco_penalty_page',$data);

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
            $data['content']='gedco_penalty_show';
            $data['title']= 'إدخال جزاءات التقييم';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( ($this->ser=='' and $isEdit) or $this->evaluation_order_id=='' or $this->emp_id=='' or $this->discount=='' or $this->note=='')
            $this->print_error('يجب ادخال جميع البيانات');
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1 ))
            die('get');
        $data['gedco_penalty_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;

        $data['content']='gedco_penalty_show';
        $data['title']='جزاءات التقييم';

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
        $data['order_id'] = $this->evaluation_order_model->get_all();
        $data["employee"]=  $this->employees_model->get_all();// الموظفين
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
            array('name'=>'EMP_ID','value'=>$this->emp_id ,'type'=>'','length'=>-1),
            array('name'=>'DISCOUNT','value'=>$this->discount ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}



