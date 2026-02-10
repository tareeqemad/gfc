<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 2020-05-28
 * Time: 8:53 AM
 */
//promotion_EMP_TB
//emp_promotion/promotion/get_efficient_performance
class promotion extends MY_Controller{

    var $PKG_NAME = "PROMOTION_EMP_PKG";
    var $MODEL_NAME= 'promotion_model';
    var $PAGE_URL= 'emp_promotion/promotion/get_page';

    function  __construct(){
        parent::__construct();

        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'PROMOTION_EMP_PKG';

        $this->load->model('settings/gcc_branches_model');
        $this->load->model('employees/employees_model');
        $this->load->model('settings/constant_details_model');



        $this->ser= $this->input->post('ser');
        $this->branch_no= $this->input->post('branch_no');
        $this->yyears= $this->input->post('yyears');
        $this->emp_no= $this->input->post('emp_no');
        $this->head_department= $this->input->post('head_department');
        $this->degree_adopt= $this->input->post('degree_adopt');
        $this->degree_current= $this->input->post('degree_current');
        $this->adopt_note= $this->input->post('adopt_note');
        $this->status= $this->input->post('status');
		$this->get_date_degree= $this->input->post('get_date_degree');
        $this->date_degree= $this->input->post('date_degree');


    }

    function index($page = 1,$branch_no= -1 ,$yyears= -1, $emp_no= -1,$head_department =-1,$degree_adopt= -1,$status = -1)
    {
        $data['title']='ترقيات الموظفين';
        $data['content']='promotion_index';
        $data['page']=$page;
        $data['branch_no']= $branch_no;
        $data['yyears']= $yyears;
        $data['emp_no']= $emp_no;
        $data['head_department']= $head_department;
        $data['degree_adopt']= $degree_adopt;
        $data['status']= $status;
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->_lookup($data);
        $this->load->view('template/template1',$data);
    }


    function index_hr_manager($page = 1)
    {
        $data['title']='ترقيات الموظفين';
        $data['content']='promotion_hr_index';
        $data['adopt_cons'] = $this->constant_details_model->get_all('347');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['status_cons'] = $this->constant_details_model->get_all('348');
        $data['page']=$page;
        $this->load->view('template/template1',$data);
    }



    function get_page($page = 1,$branch_no= -1 ,$yyears= -1, $emp_no= -1,$head_department =-1 , $degree_adopt= -1,$status = -1)
    {
        $this->load->library('pagination');
        $branch_no= $this->check_vars($branch_no,'branch_no');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $head_department= $this->check_vars($head_department,'head_department');
        $status=  $this->check_vars($status,'status');
        $degree_adopt=  $this->check_vars($degree_adopt,'degree_adopt');



        if (!$this->input->is_ajax_request()) {
            $yyears = DATE('Y');
        }else {
            $yyears = $this->p_yyears;
        }


        $where_sql = '';
        if ($this->user->branch == 1){
            $where_sql.= ($branch_no!= null)? " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$branch_no}' " : '';
        }else{
            $where_sql.= " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$this->user->branch}' ";
        }
        $where_sql.= ($yyears!= null)? " and M.yyears= '{$yyears}' " : '';
        $where_sql.= ($emp_no!= null)? " and M.emp_no= '{$emp_no}' " : '';
        $where_sql.= ($head_department!= null)? " and D.head_department= '{$head_department}' " : '';
        $where_sql.= ($status!= null)? " and M.status= '{$status}' " : '';
        $where_sql.= ($degree_adopt!= null)? " and M.adopt= '{$degree_adopt}' " : '';


        $MODULE_NAME= 'emp_promotion';
        $TB_NAME="promotion";
        $get_by_emp_child_url= base_url("$MODULE_NAME/$TB_NAME/get_by_child_emp");

        /*if(HaveAccess($get_by_emp_child_url))
        {
            $where_sql .= " and M.adopt >= 10 and
                        M.emp_no in
                        ( SELECT A.EMPLOYEE_NO
                        FROM GFC_HR.HR_EMPS_STRUCTURE_TB A
                        WHERE A.EMPLOYEE_NO != {$this->user->emp_no} 
                        START WITH A.EMPLOYEE_NO = {$this->user->emp_no} 
                        CONNECT BY PRIOR  A.EMPLOYEE_NO =A.MANAGER_NO  )";

        }*/
        //echo $where_sql;
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' PROMOTION_EMP_TB M , DATA.EMPLOYEES D  where M.EMP_NO = D.NO '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0? $count_rs[0]['NUM_ROWS']:0 ;
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
        //    DATA.EMPLOYEES  D  WHERE  M.EMP_NO = D.NO
        $data['offset']=$offset+1;
        $data['page']=$page;
        $this->load->view('promotion_page',$data);


    }

    function get_page_hr($page = 1)
    {
        $this->load->library('pagination');
        if (!$this->input->is_ajax_request()) {
            $yyears = DATE('Y');
            $degree_adopt = 40;
        }else {
            $yyears = $this->input->post('yyears');
            $degree_adopt = $this->input->post('degree_adopt');
        }
        $where_sql = '';
        $where_sql.= ($this->p_branch_no!= null)? " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$this->p_branch_no}' " : '';
        $where_sql.= ($yyears!= null)? " and M.yyears= '{$yyears}' " : '';
        $where_sql.= ($this->p_emp_no!= null)? " and M.emp_no= '{$this->p_emp_no}' " : '';
        $where_sql.= ($this->p_head_department!= null)? " and D.head_department= '{$this->p_head_department}' " : '';
        $where_sql.= ($this->p_status!= null)? " and M.status= '{$this->p_status}' " : '';
        $where_sql.= ($degree_adopt!= null)? " and M.adopt = '{$degree_adopt}' " : '';
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' PROMOTION_EMP_TB M , DATA.EMPLOYEES D  where M.EMP_NO = D.NO '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0? $count_rs[0]['NUM_ROWS']:0 ;
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
        $this->load->view('promotion_hr_page',$data);
    }


    /**********************************************************/
    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        if($c_var=='sex')
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
    /**********************************************************/
    function create()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->ser) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->ser);
            }else{
                echo intval($this->ser);
            }
        }else{
            $data['title'] = 'اضافة نموذج جديد ';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['content'] = 'promotion_show';
            $this->_lookup($data);
            $this->load->view('template/template1', $data);
        }
    }
    /**********************************************************/
    function _post_validation($isEdit = false){
        if( $this->emp_no ==''){
            $this->print_error('يجب ادخال رقم الموظف');
        }elseif ($this->degree_adopt =='' ){
            $this->print_error('يجب ادخال درجة الترقية');
        }
    }
    /**********************************************************/
    function get($id)
    {

        $result = $this->rmodel->get('PROMOTION_EMP_TB_GET', $id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['content']='promotion_show';
        $data['title']='بيانات نموذج الترقية  ';
        $this->_lookup($data);
        $this->load->view('template/template1', $data);
    }
    /**********************************************************/
    function edit()
    {
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
    /**********************************************************/
    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$this->p_emp_no ,'type'=>'','length'=>-1),
            array('name'=>'YYEARS','value'=>$this->p_yyears ,'type'=>'','length'=>-1),
            array('name'=>'DEGREE_CURRENT','value'=>$this->p_degree_current,'type'=>'','length'=>-1),
			array('name'=>'GET_DATE_DEGREE','value'=>$this->p_get_date_degree,'type'=>'','length'=>-1),
            array('name'=>'degree_adopt','value'=>$this->p_degree_adopt,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }
    /**********************************************************/
    function public_get_emp_data()
    {
        $no  = $this->input->post('no');
        if(intval($no) > 0 ) {
            $data = $this->rmodel->get('EMPLOYEE_DETAIL_GET', $no);
            echo  json_encode($data);
        }
    }

    /***************************************************/
     function public_get_adopt_detail(){
        $ser = $this->input->post('ser');
        $ret = $this->rmodel->get('PROMOTION_EMP_ADOPT_GET', $ser);
        //print_r($ret);
        echo json_encode($ret);
    }



    /**********************************************************/
    function  public_get_path_adopt()
    {
        $emp_no = $this->input->post('emp_no');
        $ret = $this->rmodel->get('PROMOTION_PATH_ADOPT_GET', $emp_no);
        //print_r($ret);
        echo json_encode($ret);
    }

    /******************************************************/
    function adopt($case)
    {
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case, $this->adopt_note,$this->status,$this->date_degree);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        return 1;
    }

    /************************اعتماد المدخل*******************************/
    function adopt_10()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser != '') {
            echo $this->adopt(10);
        } else
            echo "لم يتم الاعتماد";
    }

    /************************************اعتماد مدير الدائرة************/
    function adopt_20()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser != '') {
            echo $this->adopt(20);
        } else
            echo "لم يتم الاعتماد";
    }

    /************************************اعتماد مدير المقر************/
    function adopt_30()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser != '') {
            echo $this->adopt(30);
        } else
            echo "لم يتم الاعتماد";
    }

    /************************************اعتماد مدير دائرة شؤون الموظفين ************/
    function adopt_40()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser != '') {
            echo $this->adopt(40);
        } else
            echo "لم يتم الاعتماد";
    }
    /**********************************اعتماد ادارة الموارد البشرية***********/
    function adopt_50()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser != '') {
            echo $this->adopt(50);
        } else
            echo "لم يتم الاعتماد";
    }
    


    public function delete(){
        $result = $this->rmodel->delete('PROMOTION_EMP_TB_DELETE', $this->p_ser);
        echo $result;
    }

    function _lookup(&$data)
    {
        $data['adopt_cons'] = $this->constant_details_model->get_all('347');
        $data['status_cons'] = $this->constant_details_model->get_all('348');
        $this->load->model('salary/constants_sal_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['head_department_cons'] = $this->constants_sal_model->get_list(7);
        $data['degree_cons'] = $this->constants_sal_model->get_list(11);
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no ,'hr_admin');
    }

    function public_AdoptFinal(){
        $x = 0;
        for ($i = 0; $i < count($this->p_ser); $i++) {
            $data_arr = array(
                array('name' => 'SER', 'value' => $this->p_ser[$i], 'type' => '', 'length' => -1),
                array('name' => 'ADOPT', 'value' => 50, 'type' => '', 'length' => -1),
                array('name' => 'NOTE_IN', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
                array('name' => 'STATUS_IN', 'value' =>$this->p_status, 'type' => '', 'length' => -1),
                array('name' => 'DATE_DEGREE', 'value' => '', 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('PROMOTION_EMP_ADOPT', $data_arr);
            if (intval($res) >= 1 ){
                    echo 1;
            }else{
                echo $res;
            }
        }
    }



}


