<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/08/17
 * Time: 09:50 ص
 */

// ser,contractor_file_id,driver_id,driver_name,license_type,license_end_date,driver_work_case,driver_work_date,note

class Car_drivers extends MY_Controller{

    var $MODEL_NAME= 'car_drivers_model';
    var $PAGE_URL= 'rental/car_drivers/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->ser= $this->input->post('ser');
        $this->contractor_file_id= $this->input->post('contractor_file_id');
        $this->driver_id= $this->input->post('driver_id');
        $this->driver_name= $this->input->post('driver_name');
        $this->license_type= $this->input->post('license_type');
        $this->license_end_date= $this->input->post('license_end_date');
        $this->driver_work_case= $this->input->post('driver_work_case');
        $this->driver_work_date= $this->input->post('driver_work_date');
        $this->note= $this->input->post('note');
        $this->entry_user= $this->input->post('entry_user');

        if( HaveAccess(base_url("rental/car_drivers/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;
    }

    function index($page= 1, $ser= -1, $contractor_file_id= -1, $driver_id= -1, $driver_name= -1, $license_type= -1, $license_end_date= -1, $driver_work_case= -1, $entry_user= -1){

        $data['title']='السائقين';
        $data['content']='car_drivers_index';

        $data['entry_user_all'] = $this->get_entry_users('CAR_DRIVERS_TB');

        $data['page']=$page;
        $data['ser']= $ser;
        $data['contractor_file_id']= $contractor_file_id;
        $data['driver_id']= $driver_id;
        $data['driver_name']= $driver_name;
        $data['license_type']= $license_type;
        $data['license_end_date']= $license_end_date;
        $data['driver_work_case']= $driver_work_case;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $ser= -1, $contractor_file_id= -1, $driver_id= -1, $driver_name= -1, $license_type= -1, $license_end_date= -1, $driver_work_case= -1, $entry_user= -1){
        $this->load->library('pagination');

        $ser= $this->check_vars($ser,'ser');
        $contractor_file_id= $this->check_vars($contractor_file_id,'contractor_file_id');
        $driver_id= $this->check_vars($driver_id,'driver_id');
        $driver_name= $this->check_vars($driver_name,'driver_name');
        $license_type= $this->check_vars($license_type,'license_type');
        $license_end_date= $this->check_vars($license_end_date,'license_end_date');
        $driver_work_case= $this->check_vars($driver_work_case,'driver_work_case');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";

        if(!$this->all_branches)
            $where_sql.= " and SERV_RENT_PKG.GET_CONTRACTOR_BRANCH(contractor_file_id)= ".(  ($this->user->branch ==8)? 2:$this->user->branch  );

        $where_sql.= ($ser!= null)? " and ser= '{$ser}' " : '';
        $where_sql.= ($contractor_file_id!= null)? " and contractor_file_id= '{$contractor_file_id}' " : '';
        $where_sql.= ($driver_id!= null)? " and driver_id= '{$driver_id}' " : '';
        $where_sql.= ($driver_name!= null)? " and driver_name like '".add_percent_sign($driver_name)."' " : '';
        $where_sql.= ($license_type!= null)? " and license_type= '{$license_type}' " : '';
        $where_sql.= ($license_end_date!= null)? " and license_end_date= '{$license_end_date}' " : '';
        $where_sql.= ($driver_work_case!= null)? " and driver_work_case= '{$driver_work_case}' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' CAR_DRIVERS_TB '.$where_sql);
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

        $this->load->view('car_drivers_page',$data);
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
            $data['content']='car_drivers_show';
            $data['title']='اضافة سائق';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){ //////////////////////////////////
        if( !check_identity($this->driver_id) ){
            $this->print_error('رقم هوية السائق غير صحيح');
        }
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        //$data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['can_edit'] = true;
        $data['action'] = $action;
        $data['content']='car_drivers_show';
        $data['title']='بيانات السائق ';
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
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['license_type_cons'] = $this->constant_details_model->get_list(179);
        $data['driver_work_case_cons'] = $this->constant_details_model->get_list(180);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_FILE_ID','value'=>$this->contractor_file_id ,'type'=>'','length'=>-1),
            array('name'=>'DRIVER_ID','value'=>$this->driver_id ,'type'=>'','length'=>-1),
            array('name'=>'DRIVER_NAME','value'=>$this->driver_name ,'type'=>'','length'=>-1),
            array('name'=>'LICENSE_TYPE','value'=>$this->license_type ,'type'=>'','length'=>-1),
            array('name'=>'LICENSE_END_DATE','value'=>$this->license_end_date ,'type'=>'','length'=>-1),
            array('name'=>'DRIVER_WORK_CASE','value'=>$this->driver_work_case ,'type'=>'','length'=>-1),
            array('name'=>'DRIVER_WORK_DATE','value'=>$this->driver_work_date ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->note ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}
