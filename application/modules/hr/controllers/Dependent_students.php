<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 22/04/19
 * Time: 11:11 ص
 */

//ser_d, ser_m, emp_no, idno_relative, qualification_type, join_date, graduation_date

class dependent_students extends MY_Controller{

    var $MODEL_NAME= 'dependent_students_model';
    var $DEPENDENT_MODEL_NAME= 'dependent_model';
    var $PAGE_URL= 'hr/dependent_students/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DEPENDENT_MODEL_NAME);

        $this->ser_d= $this->input->post('ser_d');
        $this->ser_m= $this->input->post('ser_m');
        $this->emp_no= $this->input->post('emp_no');
        $this->idno_relative= $this->input->post('idno_relative');
        $this->qualification_type= $this->input->post('qualification_type');
        $this->join_date= $this->input->post('join_date');
        $this->graduation_date= $this->input->post('graduation_date');
        $this->from_date= $this->input->post('from_date');
        $this->to_date= $this->input->post('to_date');

        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');

    }

    function index($page= 1, $ser_d= -1 ){
        $data['title']= 'المعالين - طلاب جامعيين';
        $data['content']='dependent_students_index';
        $data['entry_user_all'] = $this->get_entry_users('DEPENDENT_STUDENTS_TB');

        $data['page']=$page;
        $data['ser_d']= $ser_d;

        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    /*
    function get_page($page= 1, $ser_d= -1 ){
        $this->load->library('pagination');

        $ser_d= $this->check_vars($ser_d,'ser_d');

        $where_sql= " where 1=1 ";

        $where_sql.= ($ser_d!= null)? " and ser_d= '{$ser_d}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DEPENDENT_STUDENTS_TB '.$where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get2($id);

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('dependent_students_page',$data);
    }
    */

    function get_page($id=0){
        $result= $this->{$this->MODEL_NAME}->get2($id);
        if((count($result)<1))
            die('لا يوجد طلبة جامعيين مدخلين لهذا الموظف');
        $data['page_rows']=$result;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['content']='dependent_students_page';
        $data['title']='بيانات المعالين';
        $this->_look_ups($data);
        $this->load->view('template/view',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        if($c_var=='adopt')
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function create($ser_m=0){
        if($ser_m==0 and $_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->ser_d= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->ser_d) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->ser_d);
            }else{
                echo intval($this->ser_d);
            }
        }else{
            if($ser_m > 0 ) { $result= $this->{$this->DEPENDENT_MODEL_NAME}->get($ser_m); }else die();
            $data['content']='dependent_students_show';
            $data['title']= 'ادخال طالب جامعي';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['master_tb_data2']=$result;
            $this->_look_ups($data);
            $this->load->view('template/view', $data);
        }
    }

    function _post_validation($isEdit = false){
        if( $this->ser_m=='' or $this->emp_no=='' or $this->idno_relative=='' or $this->qualification_type=='' or $this->join_date=='' or $this->graduation_date=='' or $this->from_date=='' or $this->to_date=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }
    }


    function public_get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['content']='dependent_students_show';
        $data['title']='بيانات الطالب ';
        $this->_look_ups($data);
        $this->load->view('template/view',$data);
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

    private function adopt($case){
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser_d, $case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser_d!=''){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        //$this->load->model('hr_attendance_model');
        //$data['branches']= $this->gcc_branches_model->get_all();
        //$this->load->model('settings/gcc_branches_model');
        $data['qualification_type_cons'] = $this->constant_details_model->get_list(267);
        $data['adopt_cons'] = $this->constant_details_model->get_list(0);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER_D','value'=>$this->ser_d ,'type'=>'','length'=>-1),
            array('name'=>'SER_M','value'=>$this->ser_m ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$this->emp_no ,'type'=>'','length'=>-1),
            array('name'=>'IDNO_RELATIVE','value'=>$this->idno_relative ,'type'=>'','length'=>-1),
            array('name'=>'QUALIFICATION_TYPE','value'=>$this->qualification_type ,'type'=>'','length'=>-1),
            array('name'=>'JOIN_DATE','value'=>$this->join_date ,'type'=>'','length'=>-1),
            array('name'=>'GRADUATION_DATE','value'=>$this->graduation_date ,'type'=>'','length'=>-1),
            array('name'=>'FROM_DATE','value'=>$this->from_date ,'type'=>'','length'=>-1),
            array('name'=>'TO_DATE','value'=>$this->to_date ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}
