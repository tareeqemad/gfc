<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/03/22
 * Time: 08:10 ص
 */
class Car_maintenance extends MY_Controller {

    var $MODEL_NAME= 'Car_maintenance_model';
    var $PAGE_URL= 'payment/Car_maintenance/get_page';
    var $DETAILS_MODEL_NAME= 'Car_maintenance_detail_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'FLEET_PKG';

        $this->ser = $this->input->post('ser');
        $this->car_id = $this->input->post('car_id');
        $this->car_owner = $this->input->post('car_owner');
        $this->car_type = $this->input->post('car_type');
        $this->car_model = $this->input->post('car_model');
        $this->definition_code = $this->input->post('definition_code');
        $this->branch_id = $this->input->post('branch_id');
        $this->driver_id = $this->input->post('driver_id');
        $this->des_problem = $this->input->post('des_problem');
        $this->car_ownership = $this->input->post('car_ownership');
        $this->entry_user = $this->input->post('entry_user');
        $this->req_start_date = $this->input->post('req_start_date');
        $this->req_end_date = $this->input->post('req_end_date');
        $this->adopt = $this->input->post('adopt');
    }

    function index()
    {
        $data['content']='car_maintenance_index';
        $data['title']='طلبات صيانة السيارات';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');
        $where_sql= " where 1=1 ";

        $MODULE_NAME= 'payment';
        $TB_NAME= 'Car_maintenance';
        $get_branch_url=base_url("$MODULE_NAME/$TB_NAME/get_branch");
        $get_entry_user_url=base_url("$MODULE_NAME/$TB_NAME/get_entry_user");

        if (HaveAccess($get_branch_url)){
            $where_sql.= ($this->branch_id!= null)? " and BRANCH_ID= '{$this->branch_id}' " : '';
        }else {
            $where_sql.= " and BRANCH_ID= '{$this->user->branch}'";
        }

        if (HaveAccess($get_entry_user_url)){
            $where_sql.= ($this->entry_user!= null)? " and ENTRY_USER= '{$this->entry_user}' " : '';
        }else {
            $where_sql.= " and ENTRY_USER= '{$this->user->id}'";
        }

        $where_sql.= ($this->ser!= null)? " and SER= '{$this->ser}' " : '';
        $where_sql.= ($this->car_id!= null)? " and CAR_ID= '{$this->car_id}' " : '';
        $where_sql.= ($this->car_type!= null)? " and CAR_TYPE= '{$this->car_type}' " : '';
        $where_sql.= ($this->req_start_date!= null or $this->req_end_date!= null)? " and TRUNC(ENTRY_DATE) between nvl('{$this->req_start_date}','01/01/1000') and nvl('{$this->req_end_date}','01/01/3000') " : '';
        $where_sql.= ($this->adopt!= null)? " and ADOPT= '{$this->adopt}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('CAR_MAINTENANCE_REQUEST_TB  M'.$where_sql);
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
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('car_maintenance_page',$data);

    }

    function create(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if(intval($this->ser) <= 0){
                $this->print_error($this->ser);
            }else{
                echo intval($this->ser);
            }
        }
        $data['content']='car_maintenance_show';
        $data['title']='طلب صيانة سيارة';
        $data['action'] = 'index';
        $data['isCreate']= true;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    function public_get_car_info(){
        $car_id  = $this->input->post('car_id');
        if(intval($car_id) > 0 ) {
            $data = $this->rmodel->get('CAR_INFO', $car_id);
            echo  json_encode($data);
        }
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['content']='car_maintenance_show';
        $data['title']='بيانات طلب الصيانة ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    private function adopt($case){

        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);

        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('root/Rmodel');

        $data['emp_branch_selected'] = $this->user->branch;
        $data['emp_no_selected'] = $this->user->id;
        $data['entry_user_all'] = $this->get_entry_users('CAR_MAINTENANCE_REQUEST_TB');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( '', 'hr_admin' );
        $data['car_owner'] = $this->Rmodel->getAll('FLEET_PKG', 'CARS_OWNER_LIST');
        $data['car_class'] = $this->constant_details_model->get_list(43);
        $data['adopt_cons'] = $this->constant_details_model->get_list(418);
        $data['car_ownership_list'] = $this->constant_details_model->get_list(272);
        $data['con_group'] = $this->Rmodel->getAll('FLEET_PKG', 'CARS_LIST');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'SER','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'CAR_ID','value'=> $this->car_id,'type'=>'','length'=>-1),
            array('name'=>'CAR_OWNER','value'=> $this->car_owner ,'type'=>'','length'=>-1),
            array('name'=>'CAR_TYPE','value'=>$this->car_type,'type'=>'','length'=>-1),
            array('name'=>'CAR_MODEL','value'=> $this->car_model ,'type'=>'','length'=>-1),
            array('name'=>'DEFINITION_CODE','value'=>$this->definition_code ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_ID','value'=>$this->branch_id ,'type'=>'','length'=>-1),
            array('name'=>'DRIVER_ID','value'=>$this->driver_id ,'type'=>'','length'=>-1),
            array('name'=>'DES_PROBLEM','value'=>$this->des_problem ,'type'=>'','length'=>-1),
            array('name'=>'CAR_OWNERSHIP','value'=>$this->car_ownership ,'type'=>'','length'=>-1)
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}