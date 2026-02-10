<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/02/23
 * Time: 11:40 ص
 */
class Contact_data extends MY_Controller {

    var $MODEL_NAME= 'Contact_data_model';
    var $PAGE_URL= 'salary/Contact_data/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->load->model('Root/New_rmodel');
        $this->rmodel->package = 'REPORT_PKG';

        $this->ser = $this->input->post('ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->jawal_no = $this->input->post('jawal_no');
        $this->jawal_no_2 = $this->input->post('jawal_no_2');
        $this->tel_no = $this->input->post('tel_no');
        $this->email = $this->input->post('email');
        $this->branch_id = $this->input->post('branch_id');

        $this->w_no = $this->input->post('w_no');
        $this->w_no_admin = $this->input->post('w_no_admin');
        $this->head_department = $this->input->post('head_department');
        $this->address = $this->input->post('address');

        $this->shoes_measure = $this->input->post('shoes_measure');
        $this->tshirt_measure = $this->input->post('tshirt_measure');
        $this->pants_measure = $this->input->post('pants_measure');
        $this->jacket_measure = $this->input->post('jacket_measure');

    }

    function index()
    {
        $data['content']='contact_data_index';
        $data['title']='بيانات الموظفين';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->emp_no!= null)? " and M.EMP_NO= '{$this->emp_no}' " : '';
        $where_sql.= ($this->w_no!= null)? " and SALARY_EMP_PKG.GET_VOCATIONAL_NO(M.EMP_NO) =  '{$this->w_no}' " : '';
        $where_sql.= ($this->w_no_admin!= null)? " and SALARY_EMP_PKG.GET_JOB_NO(M.EMP_NO)= {$this->w_no_admin} " : '';
        $where_sql.= ($this->head_department!= null)? " and SALARY_EMP_PKG.GET_HEAD_DEPARTMENT_NO(M.EMP_NO) = '{$this->head_department}' " : '';
        $where_sql.= ($this->address!= null)? " and SALARY_EMP_PKG.GET_ADDRESS(M.EMP_NO) like '%{$this->address}%' " : '';
        $where_sql.= "and M.STATUS = 1";
         if ($this->branch_id != 10){
            $where_sql.= ($this->branch_id!= null)? "AND EMP_PKG.GET_EMP_BRANCH (M.EMP_NO) = '{$this->branch_id}' " : '';
        }else{
            $where_sql.= ($this->branch_id!= null)? "AND EMP_PKG.GET_EMP_BRANCH (M.EMP_NO) in (1,2,3,4,6,7) " : '';
        }

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('CONTACT_DATA_TB  M'.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('contact_data_page',$data);

    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);

            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if(intval($this->ser) <= 0){
                $this->print_error($this->ser);
            }else{
                echo intval($this->ser);
            }
        }
        $data['content']='contact_data_show';
        $data['title']='إضافة بيانات الموظف';
        $data['action'] = 'index';
        $data['isCreate']= true;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
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

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['content']='contact_data_show';
        $data['title']='بيانات اتصال الموظف ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function _post_validation($isEdit = false){

        $email = explode('@', $this->email);
        $sub_email = $email[1];

        if( $this->emp_no == ''){
            $this->print_error('يجب اختيار اسم الموظف');
        }else if( $this->jawal_no == '' ){
            $this->print_error('يجب ادخال رقم الجوال');
        }else if( strlen( $this->jawal_no ) != 10 ){
            $this->print_error('الرجاء كتابة رقم الجوال بشكل صحيح');
        }else if( substr($this->jawal_no, 0, 3) != '059' and substr($this->jawal_no, 0, 3) != '056'){
            $this->print_error('يجب ان يبدأ رقم الجوال ب059 أو 056');
        }else if( $sub_email != 'gedco.ps' && strlen($this->email) >= 1 ){
            $this->print_error('يجب ادخال ايميل الشركة بشكل صحيح');
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('constants_sal_model');

        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['measure_letters'] = $this->constant_details_model->get_list(499);
        $data['measure_no'] = $this->constant_details_model->get_list(500);
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['head_department_cons'] = $this->constants_sal_model->get_list(7);

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'SER','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=> $this->emp_no ,'type'=>'','length'=>-1),
            array('name'=>'JAWAL_NO','value'=> $this->jawal_no ,'type'=>'','length'=>-1),
            array('name'=>'TEL_NO','value'=> $this->tel_no ,'type'=>'','length'=>-1),
            array('name'=>'EMAIL','value'=> $this->email ,'type'=>'','length'=>-1),
            array('name'=>'JAWAL_NO_2','value'=> $this->jawal_no_2 ,'type'=>'','length'=>-1),
            array('name'=>'SHOES_MEASURE','value'=> $this->shoes_measure ,'type'=>'','length'=>-1),
            array('name'=>'TSHIRT_MEASURE','value'=> $this->tshirt_measure ,'type'=>'','length'=>-1),
            array('name'=>'PANTS_MEASURE','value'=> $this->pants_measure ,'type'=>'','length'=>-1),
            array('name'=>'JACKET_MEASURE','value'=> $this->jacket_measure ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}