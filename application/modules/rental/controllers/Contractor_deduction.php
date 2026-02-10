<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 11:25 ص
 */

########## متابعه مع عايدة اللوح ###########
// يتم ادخال خصم كهرباء بدون قيمة ويتم جلب القيمة من الفواتير في حال كان سداد الي تكون قيمة الفاتورة صفر وان تاخر  عن تسديد فاتورة يتم اضافة المبلغ في الشهر التالي
// contractor_deduction_id,contractor_file_id,adopt,entry_user,entry_date,adopt_user,adopt_date,deduction_id,deduction_decdate,deduction_sdate,deduvtion_edate,deduction_bill_id,deduction_value,deduction_add,deduction_discount,note

class Contractor_deduction extends MY_Controller{

    var $MODEL_NAME= 'contractor_deduction_model';
    var $PAGE_URL= 'rental/contractor_deduction/get_page';

    function  __construct(){
        parent::__construct();

        // تم الغاء الشاشة في 02/2018 بناء على طلب المالية - عبير الحويطي
        die('Closed..');

        $this->load->model($this->MODEL_NAME);

        $this->contractor_deduction_id= $this->input->post('contractor_deduction_id');
        $this->contractor_file_id= $this->input->post('contractor_file_id');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        $this->deduction_id= $this->input->post('deduction_id');
        $this->deduction_decdate= $this->input->post('deduction_decdate');
        $this->deduction_sdate= $this->input->post('deduction_sdate');
        $this->deduvtion_edate= $this->input->post('deduvtion_edate');
        $this->deduction_bill_id= $this->input->post('deduction_bill_id');
        $this->deduction_value= $this->input->post('deduction_value');
        $this->deduction_add= $this->input->post('deduction_add');
        $this->deduction_discount= $this->input->post('deduction_discount');
        $this->note= $this->input->post('note');
    }

    function index($page= 1, $contractor_deduction_id= -1, $contractor_file_id= -1, $adopt= -1, $deduction_id= -1, $deduction_sdate= -1, $deduvtion_edate= -1, $deduction_bill_id= -1, $deduction_value= -1, $note= -1, $entry_user= -1 ){

        $data['title']='الاستقطاعات';
        $data['content']='contractor_deduction_index';

        $data['entry_user_all'] = $this->get_entry_users('CONTRACTOR_DEDUCTION_TB');

        $data['page']=$page;
        $data['contractor_deduction_id']= $contractor_deduction_id;
        $data['contractor_file_id']= $contractor_file_id;
        $data['adopt']= $adopt;
        $data['deduction_id']= $deduction_id;
        $data['deduction_sdate']= $deduction_sdate;
        $data['deduvtion_edate']= $deduvtion_edate;
        $data['deduction_bill_id']= $deduction_bill_id;
        $data['deduction_value']= $deduction_value;
        $data['note']= $note;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $contractor_deduction_id= -1, $contractor_file_id= -1, $adopt= -1, $deduction_id= -1, $deduction_sdate= -1, $deduvtion_edate= -1, $deduction_bill_id= -1, $deduction_value= -1, $note= -1, $entry_user= -1 ){
        $this->load->library('pagination');

        $contractor_deduction_id= $this->check_vars($contractor_deduction_id,'contractor_deduction_id');
        $contractor_file_id= $this->check_vars($contractor_file_id,'contractor_file_id');
        $adopt= $this->check_vars($adopt,'adopt');
        $deduction_id= $this->check_vars($deduction_id,'deduction_id');
        $deduction_sdate= $this->check_vars($deduction_sdate,'deduction_sdate');
        $deduvtion_edate= $this->check_vars($deduvtion_edate,'deduvtion_edate');
        $deduction_bill_id= $this->check_vars($deduction_bill_id,'deduction_bill_id');
        $deduction_value= $this->check_vars($deduction_value,'deduction_value');
        $note= $this->check_vars($note,'note');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";

        $where_sql.= ($contractor_deduction_id!= null)? " and contractor_deduction_id= '{$contractor_deduction_id}' " : '';
        $where_sql.= ($contractor_file_id!= null)? " and contractor_file_id= '{$contractor_file_id}' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($deduction_id!= null)? " and deduction_id= '{$deduction_id}' " : '';
        $where_sql.= ($deduction_sdate!= null)? " and deduction_sdate= '01/{$deduction_sdate}' " : '';
        $where_sql.= ($deduvtion_edate!= null)? " and deduvtion_edate= '01/{$deduvtion_edate}' " : '';
        $where_sql.= ($deduction_bill_id!= null)? " and deduction_bill_id= '{$deduction_bill_id}' " : '';
        $where_sql.= ($deduction_value!= null)? " and deduction_value= '{$deduction_value}' " : '';
        $where_sql.= ($note!= null)? " and note like '".add_percent_sign($note)."' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' CONTRACTOR_DEDUCTION_TB '.$where_sql);
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

        $this->load->view('contractor_deduction_page',$data);
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
            $this->contractor_deduction_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->contractor_deduction_id) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->contractor_deduction_id);
            }else{
                echo intval($this->contractor_deduction_id);
            }
        }else{
            $data['content']='contractor_deduction_show';
            $data['title']='اضافة استقطاع ';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){ //////////////////////////////////
        //$this->print_error('يجب ادخال جميع البيانات');
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='contractor_deduction_show';
        $data['title']='بيانات الاستقطاع ';
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

    private function adopt($case){
        $res = $this->{$this->MODEL_NAME}->adopt($this->contractor_deduction_id, $case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_2(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->contractor_deduction_id!=''){
            echo $this->adopt(2);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['adopt_cons'] = $this->constant_details_model->get_list(181);
        $data['deduction_bill_id_cons'] = $this->constant_details_model->get_list(182);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'CONTRACTOR_DEDUCTION_ID','value'=>$this->contractor_deduction_id ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_FILE_ID','value'=>$this->contractor_file_id ,'type'=>'','length'=>-1),
            array('name'=>'DEDUCTION_ID','value'=>$this->deduction_id ,'type'=>'','length'=>-1),
            array('name'=>'DEDUCTION_DECDATE','value'=>$this->deduction_decdate ,'type'=>'','length'=>-1),
            array('name'=>'DEDUCTION_SDATE','value'=>$this->deduction_sdate ,'type'=>'','length'=>-1),
            array('name'=>'DEDUVTION_EDATE','value'=>$this->deduvtion_edate ,'type'=>'','length'=>-1),
            array('name'=>'DEDUCTION_BILL_ID','value'=>$this->deduction_bill_id ,'type'=>'','length'=>-1),
            array('name'=>'DEDUCTION_VALUE','value'=>$this->deduction_value ,'type'=>'','length'=>-1),
            array('name'=>'DEDUCTION_ADD','value'=>$this->deduction_add ,'type'=>'','length'=>-1),
            array('name'=>'DEDUCTION_DISCOUNT','value'=>$this->deduction_discount ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}