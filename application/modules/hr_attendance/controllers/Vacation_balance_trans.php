<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 04/02/20
 * Time: 10:13 ص
 */

// SER,EMP_NO,VAC_TYPE,YEAR_FROM,YEAR_TO,BALANCE_VAL,ADOPT

class vacation_balance_trans extends MY_Controller{

    var $MODEL_NAME= 'vacation_balance_trans_model';
    var $PAGE_URL= 'hr_attendance/vacation_balance_trans/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->ser= $this->input->post('ser');
        $this->emp_no= $this->input->post('emp_no');
        $this->vac_type= $this->input->post('vac_type');
        $this->year_from= $this->input->post('year_from');
        $this->year_to= $this->input->post('year_to');
        $this->balance_val= $this->input->post('balance_val');
        $this->adopt= $this->input->post('adopt');
    }

    function index($page= 1, $ser= -1, $emp_no= -1, $vac_type= -1, $year_from= -1, $year_to= -1, $balance_val= -1, $adopt= -1 ){

        $data['title']='ترحيل رصيد الاجازات';
        $data['content']='vacation_balance_trans_index';

        $data['page']=$page;
        $data['ser']= $ser;
        $data['emp_no']= $emp_no;
        $data['vac_type']= $vac_type;
        $data['year_from']= $year_from;
        $data['year_to']= $year_to;
        $data['balance_val']= $balance_val;
        $data['adopt']= $adopt;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);

    }

    function get_page($page= 1, $ser= -1, $emp_no= -1, $vac_type= -1, $year_from= -1, $year_to= -1, $balance_val= -1, $adopt= -1 ){
        $this->load->library('pagination');

        $ser= $this->check_vars($ser,'ser');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $vac_type= $this->check_vars($vac_type,'vac_type');
        $year_from= $this->check_vars($year_from,'year_from');
        $year_to= $this->check_vars($year_to,'year_to');
        $balance_val= $this->check_vars($balance_val,'balance_val');
        $adopt= $this->check_vars($adopt,'adopt');

        $where_sql= " where 1=1 ";

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= ($vac_type!= null)? " and vac_type= '{$vac_type}' " : '';
        $where_sql.= ($year_from!= null)? " and year_from= '{$year_from}' " : '';
        $where_sql.= ($year_to!= null)? " and year_to= '{$year_to}' " : '';
        $where_sql.= ($balance_val!= null)? " and balance_val= '{$balance_val}' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' VACATION_BALANCE_TRANS_TB '.$where_sql);
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

        $this->load->view('vacation_balance_trans_page',$data);
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
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->ser) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->ser);
            }else{
                echo intval($this->ser);
            }
        }else{

            $data['content']='vacation_balance_trans_show';
            $data['title']='اضافة سجل ';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){
        if( $this->emp_no=='' or $this->year_from=='' or $this->year_to=='' or $this->balance_val=='' ){
            $this->print_error('يجب ادخال جميع البيانات');
        }elseif( $this->year_from +1 != $this->year_to ){
            $this->print_error('يجب ان يكون الترحيل للسنة التالية');
        }elseif( $this->year_from < 2018 ){
            $this->print_error('اختر سنة صحيحة');
        }elseif( $this->balance_val < 1 ){
            $this->print_error('ادخل رصيد صحيح');
        }
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['content']='vacation_balance_trans_show';
        $data['title']='بيانات السجل ';
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

    private function adopt($case, $type=null){
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_2(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(2);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( $this->user->emp_no , 'hr_admin' );
        $data['adopt_cons'] = $this->constant_details_model->get_list(312);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$this->emp_no ,'type'=>'','length'=>-1),
            array('name'=>'YEAR_FROM','value'=>$this->year_from ,'type'=>'','length'=>-1),
            array('name'=>'YEAR_TO','value'=>$this->year_to ,'type'=>'','length'=>-1),
            array('name'=>'BALANCE_VAL','value'=>$this->balance_val ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}

