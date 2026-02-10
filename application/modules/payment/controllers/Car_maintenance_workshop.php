<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 30/03/22
 * Time: 08:00 ص
 */
class Car_maintenance_workshop extends MY_Controller
{
    var $MODEL_NAME = 'Car_maintenance_model';
    var $PAGE_URL = 'payment/Car_maintenance_workshop/get_page';
    var $DETAILS_MODEL_NAME = 'Car_maintenance_detail_model';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'FLEET_PKG';

        //Master
        $this->ser = $this->input->post('ser');
        $this->car_id = $this->input->post('car_id');
        $this->car_owner = $this->input->post('car_owner');
        $this->car_type = $this->input->post('car_type');
        $this->car_model = $this->input->post('car_model');
        $this->definition_code = $this->input->post('definition_code');
        $this->branch_id = $this->input->post('branch_id');
        $this->driver_id = $this->input->post('driver_id');
        $this->des_problem = $this->input->post('des_problem');
        $this->entry_user = $this->input->post('entry_user');
        $this->req_start_date = $this->input->post('req_start_date');
        $this->req_end_date = $this->input->post('req_end_date');
        $this->adopt = $this->input->post('adopt');
        $this->reasons_problem = $this->input->post('reasons_problem');
        $this->maintenance_type = $this->input->post('maintenance_type');
        $this->technical_detection = $this->input->post('technical_detection');
        $this->notes = $this->input->post('notes');

        // Details
        $this->ser_det= $this->input->post('txt_ser_det');
        $this->class_name= $this->input->post('class_name');
        $this->quantity= $this->input->post('quantity');
        $this->class_unit= $this->input->post('class_unit');
        $this->price= $this->input->post('price');
        $this->complementing_amount= $this->input->post('complementing_amount');
        $this->review_amount= $this->input->post('review_amount');
        $this->class_type= $this->input->post('class_type');
        $this->supplier_no= $this->input->post('supplier_no');
        $this->bill_no= $this->input->post('bill_no');
        $this->bill_date= $this->input->post('bill_date');
        $this->class_status= $this->input->post('class_status');
        $this->consignment_no= $this->input->post('consignment_no');

    }

    function index()
    {
        $data['content']='car_maintenance_workshop_index';
        $data['title']=' طلبات صيانة السيارات - الورشة';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $MODULE_NAME= 'payment';
        $TB_NAME= 'Car_maintenance_workshop';
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
        $where_sql.= ($this->entry_user!= null)? " and ENTRY_USER= '{$this->entry_user}' " : '';
        $where_sql.= " and adopt >= 10  ";
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
        $this->load->view('car_maintenance_workshop_page',$data);

    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['content']='car_maintenance_workshop_show';
        $data['title']='بيانات طلب الصيانة - الورشة ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function update(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $res = $this->{$this->MODEL_NAME}->update($this->_postedData(false));
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{

                for($i=0; $i<count($this->class_name); $i++){
                    $this->_post_validation();
                    if($this->ser_det[$i]== 0 and $this->class_name[$i]!='' and $this->quantity[$i]>0 and $this->price[$i]>0 and $this->complementing_amount[$i]!='' and $this->review_amount[$i]!=''){ // create
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->ser  ,$this->class_name[$i], $this->quantity[$i], $this->class_unit[$i], $this->price[$i], $this->complementing_amount[$i], $this->review_amount[$i] , $this->consignment_no[$i], $this->supplier_no[$i], $this->bill_no[$i], $this->bill_date[$i], $this->class_status[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }elseif($this->ser_det[$i]!= 0 and $this->class_name[$i]!='' and $this->quantity[$i]>0 and $this->price[$i]>0 and $this->complementing_amount[$i]!='' and $this->review_amount[$i]!=''){ // edit
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser_det[$i] ,$this->ser ,$this->class_name[$i], $this->quantity[$i], $this->class_unit[$i], $this->price[$i], $this->complementing_amount[$i], $this->review_amount[$i] , $this->consignment_no[$i], $this->supplier_no[$i], $this->bill_no[$i], $this->bill_date[$i],$this->class_status[$i], 'edit'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }elseif($this->ser_det[$i]!= 0 and  $this->quantity[$i]== 0 ){ // delete
                        $detail_seq= $this->{$this->DETAILS_MODEL_NAME}->delete($this->ser_det[$i]);
                        if(intval($detail_seq) <= 0){
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }
        }
    }

    function create_det(){

        $data['content']='car_maintenance_workshop_show';
        $data['title']='بيانات طلب الصيانة ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function public_get_details($id= 0,$adopt_det){
        $data['adopt_det'] = $adopt_det;
        $data['can_edit'] = 1;
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        $this->load->view('car_maintenance_workshop_details',$data);
    }

    private function adopt($case){

        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);

        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        return 1;
    }

    function adopt_20(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){

            echo $this->adopt(20);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_30(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(30);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function adopt_40(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(40);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function public_get_id(){
        $class_name  = $this->input->post('id');
        if(intval($class_name) > 0 ) {
            $data = $this->rmodel->get('SPARE_PARTS_INFO', $class_name);
            echo  json_encode($data);
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('root/Rmodel');

        $data['branches']= $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( '', 'hr_admin' );
        $data['entry_user_all'] = $this->get_entry_users('CAR_MAINTENANCE_REQUEST_TB');
        $data['car_owner'] = $this->Rmodel->getAll('FLEET_PKG', 'CARS_OWNER_LIST');
        $data['spare_parts'] = $this->Rmodel->getAll('FLEET_PKG', 'SPARE_PARTS_LIST');
        $data['car_class'] = $this->constant_details_model->get_list(43);
        $data['adopt_cons'] = $this->constant_details_model->get_list(418);
        $data['reasons_problem'] = $this->constant_details_model->get_list(419);
        $data['maintenance_type'] = $this->constant_details_model->get_list(420);
        $data['class_type'] = $this->constant_details_model->get_list(422);
        $data['car_ownership_list'] = $this->constant_details_model->get_list(272);
        $data['class_status'] = $this->constant_details_model->get_list(512);
        $data['suppliers'] = $this->Rmodel->getAll('FLEET_PKG', 'GET_SUPPLIERS_LIST');
        $data['date_attr'] = date('d/m/Y');
        $data['class_unit'] = $this->constant_details_model->get_list(513);
        $data['emp_branch_selected'] = $this->user->branch;
        $data['emp_no_selected'] = $this->user->id;

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['class_name_data'] = $this->Rmodel->getAll('FLEET_PKG', 'SPARE_PARTS_LIST');
        $data['class_name_data_options']='<option>    </option>';
        foreach ($data['class_name_data'] as $row2) :
            $data['class_name_data_options']=$data['class_name_data_options'].'<option value="'.$row2['CLASS_NO'].'">'.$row2['CLASS_NAME'].'</option>';
        endforeach;

        $data['class_unit_data'] = $this->Rmodel->getAll('FLEET_PKG', 'SPARE_PARTS_LIST');
        $data['class_unit_data_options']='<option>_________</option>';
        foreach ($data['class_unit'] as $row2) :
            $data['class_unit_data_options']=$data['class_unit_data_options'].'<option value="'.$row2['CON_NO'].'">'.$row2['CON_NAME'].'</option>';
        endforeach;

        $data['class_type_data'] = $this->constant_details_model->get_list(422);
        $data['class_type_data_options']='<option>_________</option>';
        foreach ($data['class_type_data'] as $row2) :
            $data['class_type_data_options']=$data['class_type_data_options'].'<option value="'.$row2['CON_NO'].'">'.$row2['CON_NAME'].'</option>';
        endforeach;

        $data['supplier_data_options']='<option>_________</option>';
        foreach ($data['suppliers'] as $row2) :
            $data['supplier_data_options']=$data['supplier_data_options'].'<option value="'.$row2['CUSTOMER_ID'].'">'.$row2['CUSTOMER_NAME'].'</option>';
        endforeach;

        $data['class_status_data_options']='<option>_________</option>';
        foreach ($data['class_status'] as $row2) :
            $data['class_status_data_options']=$data['class_status_data_options'].'<option value="'.$row2['CON_NO'].'">'.$row2['CON_NAME'].'</option>';
        endforeach;
    }


    function _post_validation($isEdit = false){


        for($i=0; $i<count($this->class_name); $i++) {

            if ( $this->class_name[$i] == ''){
                $this->print_error('يجب ادخال اسم الصنف ');
            }elseif ( $this->quantity[$i] == ''){
                $this->print_error('يجب ادخال العدد المطلوب ');
            }elseif ( $this->price[$i] == ''){
                $this->print_error('يجب ادخال المبلغ ');
            }elseif ( $this->complementing_amount[$i] == ''){
                $this->print_error('يجب ادخال الكمية المنجزة ');
            }elseif ( $this->review_amount[$i] == ''){
                $this->print_error('يجب ادخال الكمية المرجعة ');
            }elseif ( $this->supplier_no[$i] == 0){
                $this->print_error('يجب اختيار المورد ');
            }elseif ( $this->bill_no[$i] == ''){
                $this->print_error('يجب ادخال رقم الفاتورة ');
            }elseif ( $this->bill_date[$i] == ''){
                $this->print_error('يجب ادخال تاريخ الفاتورة ');
            }
        }
    }

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name'=>'SER','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'REASONS_PROBLEM','value'=> $this->reasons_problem,'type'=>'','length'=>-1),
            array('name'=>'MAINTENANCE_TYPE','value'=> $this->maintenance_type ,'type'=>'','length'=>-1),
            array('name'=>'TECHNICAL_DETECTION','value'=> $this->technical_detection ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=> $this->notes ,'type'=>'','length'=>-1),
        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }

    function _postedData_details($ser= null,$res = null, $class_name = null, $quantity= null, $class_unit= null, $price= null, $complementing_amount= null,$review_amount= null,$consignment_no= null ,$supplier_no ,$bill_no ,$bill_date ,$class_status , $typ= null){

        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_NO','value'=>$res ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_name ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$class_unit ,'type'=>'','length'=>-1),
            array('name'=>'CONSIGNMENT_NO','value'=>$consignment_no ,'type'=>'','length'=>-1),
            array('name'=>'PRICE','value'=>$price ,'type'=>'','length'=>-1),
            array('name'=>'COMPLEMENTING_AMOUNT','value'=>$complementing_amount ,'type'=>'','length'=>-1),
            array('name'=>'REVIEW_AMOUNT','value'=>$review_amount ,'type'=>'','length'=>-1),
            array('name'=>'QUANTITY','value'=>$quantity ,'type'=>'','length'=>-1),
            array('name'=>'SUPPLIER_NO','value'=>$supplier_no ,'type'=>'','length'=>-1),
            array('name'=>'BILL_NO','value'=>$bill_no ,'type'=>'','length'=>-1),
            array('name'=>'BILL_DATE','value'=>$bill_date ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_STATUS','value'=>$class_status ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            array_shift($result);

        return $result;
    }
}
