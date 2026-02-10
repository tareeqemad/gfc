<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pledges_cont extends MY_Controller {

    var $MODEL_NAME = 'pledges_model';
    var $PAGE_URL= 'pledges_t/pledges_cont/get_page';
    var $PAGE_URL_T= 'pledges_t/pledges_cont/get_page_d';


    function  __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('stores/stores_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');

        $this->seri= $this->input->post('seri');
        $this->emp_no= $this->input->post('emp_no');
        $this->class_id= $this->input->post('class_id');
        $this->class_name= $this->input->post('class_name');
        $this->barcode= $this->input->post('barcode');
        $this->v_note= $this->input->post('v_note');
        $this->pp_date= $this->input->post('pp_date');
        $this->emp_pledges= intval($this->input->post('emp_pledges'));
        $this->customer_id= $this->input->post('customer_id');

         $this->load->model('settings/gcc_branches_model');
        $this->load->model('employees/employees_model');

    }
     function index($page =1,$seri= -1,$emp_no=-1,$class_id=-1,$class_name = -1,$barcode = -1 ,$v_note= -1,$pp_date =-1,$emp_pledges=-1,$customer_id= -1){

         $this->load->model('payment/customers_model');
         $this->load->model('settings/constant_details_model');
         $this->load->model('settings/Gcc_structure_model');
        // $this->load->model('');
        $data['content'] = 'pledges_index';
        $data['title']='ادارةالعهد الغير موجودة-استعلام';
        $data['seri']= $seri;
        $data['emp_no']= $emp_no;
        $data['page']=$page;
         $data['class_id']= $class_id;
         $data['class_name']= $class_name;
         $data['barcode']= $barcode;
         $data['v_note']= $v_note;
         $data['pp_date']= $pp_date;
        $data['emp_pledges']= $emp_pledges;
        $data['customer_id']= $customer_id;
        $data['action'] = 'create';
        $data["employee"] = $this->employees_model->get_all();// الموظفين
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }
    function _postedData($isCreate = true,$class_id = null ,$class_name=null,$barcode=null,$v_note=null)
    {
        $result = array(
            array('name' => 'SERI', 'value' => $this->seri, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_ID', 'value' => $class_id, 'type' => '', 'length' => -1),
            array('name' => 'CLASS_NAME', 'value' => $class_name, 'type' => '', 'length' => -1),
            array('name' => 'BARCODE', 'value' => $barcode, 'type' => '', 'length' => -1),
            array('name' => 'V_NOTE', 'value' => $v_note, 'type' => '', 'length' => -1),
            array('name' => 'PP_DATE', 'value' => $this->pp_date, 'type' => '', 'length' => -1),

        );

        if ($isCreate) {
            array_shift($result);
        }
        return $result;

    }
    function statusupdate(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $res=  $this->{$this->MODEL_NAME}->edit($this->input->post('file_id') , 2);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }
            else
                echo 1;
        }else{
            //$result= $this->{$this->MODEL_NAME}->get($id);

            $this->load->model('settings/constant_details_model');
            $data['content']='customers_pledges_shows';
            $data['title']= 'تعديل عهدة موظف';
            $data['isCreate']= false;
            $data['action'] = 'edit';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);

        }
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
           // $this->_post_validation();
                for($i=0;$i<count($this->p_class_id);$i++){
                    if($this->p_emp_no !='' and $this->p_class_id[$i]!='' and $this->p_class_name[$i]!='' and $this->p_barcode[$i]!=''and $this->p_v_note[$i]!='' ){
                        $this->seri= $this->{$this->MODEL_NAME}->create($this->_postedData('create', $this->p_class_id[$i],$this->p_class_name[$i],$this->p_barcode[$i],$this->p_v_note[$i]));

                        if($this->seri <=0)
                            $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->seri);
                    }
                }

                echo 1;

        }else{
            $data['content']='pledges_test';
            $data['title']='ادارةالعهد الغير موجودة-اضافة';
            $data['action'] = 'index';
            $data['isCreate']= true;
            $this->_look_ups($data);
            $data["employee"] = $this->employees_model->get_all();// الموظفين
            $this->load->view('template/template',$data);
        }

    }


    function _post_validation($isEdit = false){
        if($this->emp_no=='' or $this->class_id=='' or $this->class_name=='' or $this->barcode=='')
            $this->print_error('يجب ادخال جميع البيانات');
    }


    function delete(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $res = $this->{$this->MODEL_NAME}->delete($this->seri);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

/***************************************************************************************/
    function get_page($page= 1,$seri= -1,$emp_no=-1,$class_id=-1,$class_name = -1,$barcode = -1 ,$v_note= -1,$pp_date =-1){
        $this->load->library('pagination');
        $seri=$this->check_vars($seri,'seri');
        $emp_no=$this->check_vars($emp_no,'emp_no');
        $class_id=$this->check_vars($class_id,'class_id');
        $class_name=$this->check_vars($class_name,'class_name');
        $barcode=$this->check_vars($barcode,'barcode');
        $v_note=$this->check_vars($v_note,'v_note');
        $pp_date=$this->check_vars($pp_date,'pp_date');

        /***************************where_sql*******************************/
        $where_sql= " where 1=1 ";
        $where_sql.= ($seri!= null)? " and seri= '{$seri}' " : '';
        $where_sql.= ($emp_no!= null)? " and emp_no= '{$emp_no}' " : '';
        $where_sql.= ($class_id!= null)? " and class_id= '{$class_id}' " : '';
        $where_sql.= ($class_name!= null)? " and class_name= '{$class_name}' " : '';
        $where_sql.= ($barcode!= null)? " and barcode= '{$barcode}' " : '';
        $where_sql.= ($v_note!= null)? " and v_note= '{$v_note}' " : '';
        $where_sql.= ($pp_date!= null)? " and pp_date= '{$pp_date}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('PLED_ADD_TB '.$where_sql);
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
        $data["employee"] = $this->employees_model->get_all();// الموظفين
        $data['title']='استعلام';
        $this->load->view('pledges_page',$data);
    }
    /****************************استعلام عن العهد***********************************************************/
    function get_index($page= 1, $emp_pledges=-1,  $customer_id= -1){
        $this->load->model('payment/customers_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/Gcc_structure_model');
        $data['title']='ادارةالعهد موجودة-استعلام';

        $data['page']=$page;

        $data['customer_id']= $customer_id;
        $data['emp_pledges']= $emp_pledges;
        $data['content']='customers_pledges_page_index';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    /***************************************************************************************/

    function get_page_d($page= 1, $emp_pledges= -1, $customer_id= -1){
        $this->load->library('pagination');
        $emp_pledges= $this->check_vars($emp_pledges,'emp_pledges');
        $customer_id= $this->check_vars($customer_id,'customer_id');
        $where_sql= ' where 1=1 ';
        //$where_sql.= ($emp_pledges!= null && $emp_pledges==1)? " and customer_id in (SELECT customer_id FROM gfc.customers_tb a where a.customer_type=3 and a.company_delegate_id=".$this->user->emp_no." ) " : '';
        $where_sql.= ($customer_id!= null)? " and customer_id= '{$customer_id}' " : '';
        $config['base_url'] = base_url($this->PAGE_URL_T);
        $count_rs = $this->get_table_count('CUSTOMERS_PLEDGES_TEST '.$where_sql);
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
        $data['page_rows_t'] = $this->{$this->MODEL_NAME}->get_list_d($where_sql, $offset , $row );
        $data['offset']=$offset+1;
        $data['page']=$page;
        $this->_look_ups($data);
        $this->load->view('customers_pledges_page_t',$data);
    }
    /***************************************************************************************/
    /*****************************************************************************************/
    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
    /*************************************************************************************/



    function _look_ups(&$data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $this->load->model('payment/customers_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/Gcc_structure_model');
        $data['astatus'] = $this->constant_details_model->get_list(249);
        $data['source_all'] = $this->constant_details_model->get_list(60);
        $data['status_all'] = $this->constant_details_model->get_list(61);
        $data['customer_ids']=$this->customers_model->get_all_by_type(3);
        $data['manage_st_ids']=$this->Gcc_structure_model->getStructure(1);
        $data['cycle_st_ids']=$this->Gcc_structure_model->getStructure(2);
        $data['department_st_ids']=$this->Gcc_structure_model->getStructure(3);
        $data['class_type_all'] = $this->constant_details_model->get_list(41);
        $data['gcc_structure_show_cycle']=$this->Gcc_structure_model->getStructure(2);
        //$data['gcc_structure_shows']=$this->Gcc_structure_model->getList($this->p_movment_manage_st_id,intval($this->user));//
        $data['class_code_ser']='';
    }

}

?>