<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 27/06/19
 * Time: 10:08 ص
 */
class Employees extends MY_Controller
{

    var $MODEL_NAME = 'employees_model';
    var $PAGE_URL = 'salary/employees/get_page';

    //var $PAGE_ACT;

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'SALARY_EMP_PKG';

        //$this->page_act= $this->input->post('page_act');
        $this->no = $this->input->post('no');
        $this->name = $this->input->post('name');
        $this->id = $this->input->post('id');
        $this->emp_no = $this->input->post('emp_no');

        $this->tel = $this->input->post('tel');
        $this->birth_place = $this->input->post('birth_place');
        $this->status = $this->input->post('status');
        $this->birth_date = $this->input->post('birth_date');
        $this->religion = $this->input->post('religion');
        $this->sex = $this->input->post('sex');
        $this->address = $this->input->post('address');

        $this->hire_date = $this->input->post('hire_date');
        $this->fex_date = $this->input->post('fex_date');
        $this->bran = $this->input->post('bran');
        $this->kad_no_n = $this->input->post('kad_no_n');
        $this->head_department = $this->input->post('head_department');
        $this->q_no = $this->input->post('q_no');
        $this->q_date = $this->input->post('q_date');
        $this->sp_no = $this->input->post('sp_no');
        $this->degree = $this->input->post('degree');
        $this->periodical_allownces = $this->input->post('periodical_allownces');
        $this->hire_years = $this->input->post('hire_years');
        $this->w_no = $this->input->post('w_no');
        $this->w_no_admin = $this->input->post('w_no_admin');
        $this->emp_type = $this->input->post('emp_type');
        $this->gcc_no = $this->input->post('gcc_no');
        $this->experiences = $this->input->post('experiences');

        $this->bank = $this->input->post('bank');
        $this->account = $this->input->post('account');
        $this->master_banks_email = $this->input->post('master_banks_email');
        $this->iban = $this->input->post('iban');
        $this->is_active = $this->input->post('is_active');
        $this->is_taxed = $this->input->post('is_taxed');
        $this->insuranced = $this->input->post('insuranced');
        $this->bank_branch_email = $this->input->post('bank_branch_email');
        $this->account_bank_email = $this->input->post('account_bank_email');
        $this->insurance_no = $this->input->post('insurance_no');

    }

    function index($page = 1)
    {
        $data['title'] = 'تسكين الموظفين - استعلام';
        $data['content'] = 'employees_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');


        $where_sql = " where 1=1 ";
        //$where_sql.= " and branch_id= {$this->user->branch} ";
        $where_sql .= isset($this->p_employee_no) && $this->p_employee_no != null ? " AND  NO = '{$this->p_employee_no}'  " : "";
        $where_sql .= isset($this->p_name) && $this->p_name != null ? " AND NAME LIKE '" . add_percent_sign($this->p_name) . "'  " : "";
        $where_sql .= isset($this->p_id) && $this->p_id != null ? " AND  ID = '{$this->p_id}'  " : "";
        $where_sql .= isset($this->p_status) && $this->p_status != null ? " AND  STATUS = '{$this->p_status}'  " : "";
        $where_sql .= isset($this->p_sex) && $this->p_sex != null ? " AND  sex = '{$this->p_sex}'  " : "";
        $where_sql .= isset($this->p_emp_type) && $this->p_emp_type != null ? " AND  emp_type = '{$this->p_emp_type}'  " : "";
        $where_sql .= isset($this->p_bran) && $this->p_bran != null ? " AND  bran = '{$this->p_bran}'  " : "";
        $where_sql .= isset($this->p_q_no) && $this->p_q_no != null ? " AND  q_no = '{$this->p_q_no}'  " : "";
        $where_sql .= isset($this->p_sp_no) && $this->p_sp_no != null ? " AND  sp_no = '{$this->p_sp_no}'  " : "";
        $where_sql .= isset($this->p_w_no_admin) && $this->p_w_no_admin != null ? " AND  w_no_admin = '{$this->p_w_no_admin}'  " : "";
        $where_sql .= isset($this->p_master_banks_email) && $this->p_master_banks_email != null ? " AND  master_banks_email = '{$this->p_master_banks_email}'  " : "";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.EMPLOYEES  M ' . $where_sql);
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
        $data["page_rows"] = $this->rmodel->getList('EMPLOYEES_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('employees_page', $data);
    }


    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $this->no = $this->{$this->MODEL_NAME}->create($this->_postedData());
            if (intval($this->no) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            } else {
                echo intval($this->no);
            }
        } else {
            $emp_no = $this->rmodel->getAll('SALARY_EMP_PKG', 'MAX_EMP_NO_GET');
            $data['emp_no'] = $emp_no;
            $data['content'] = 'employees_show';
            $data['title'] = 'اضافة موظف جديد';
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template1', $data);
        }
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            } else {
                echo 1;
            }
        }
    }

    function _post_validation()
    {
        if ($this->no == '') {
            $this->print_error('يجب ادخال رقم الموظف');
        } elseif( !check_identity($this->id) ){
            $this->print_error('رقم هويةالموظف  غير صحيح');
       } elseif (trim($this->name) == '' || $this->status == '' || $this->sex == '' || $this->emp_type == '' ||
                 $this->bran == '' || $this->birth_date == '' || $this->hire_date == '' || $this->q_no == '' ||
                 $this->sp_no == ''  || $this->kad_no_n == '' || $this->periodical_allownces == '' || $this->degree== '' ||
                 $this->hire_years == '' || $this->is_active == '' || $this->is_taxed == '' || $this->insuranced == '') {
            $this->print_error(' يجب ادخال جميع البيانات المطلوبة باللون الأحمر ');
        }
    }

    function my_data()
    {
        $result = $this->{$this->MODEL_NAME}->get($this->user->emp_no);
        if (!(count($result) == 1))
            die('get');
        $data['master_tb_data'] = $result;
        $data['can_edit'] = 0;
        $data['action'] = 'my_data';
        $data['content'] = 'employees_show';
        $data['title'] = 'بياناتي';
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function get($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        if (!(count($result) == 1))
            die('get');
        $data['master_tb_data'] = $result;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['content'] = 'employees_show';
        $data['title'] = 'بيانات الموظف ';
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function _look_ups(&$data)
    {
        //$this->load->model('settings/gcc_branches_model');
        $this->load->model('constants_sal_model');
        //$data['branches']= $this->gcc_branches_model->get_all();

        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');

        $data['status_cons'] = $this->constants_sal_model->get_list(1);
        $data['religion_cons'] = $this->constants_sal_model->get_list(2);
        $data['sex_cons'] = $this->constants_sal_model->get_list(19);

        $data['bran_cons'] = $this->constants_sal_model->get_list(5);
        $data['kad_no_n_cons'] = $this->constants_sal_model->get_list(6);
        $data['head_department_cons'] = $this->constants_sal_model->get_list(7);
        $data['q_no_cons'] = $this->constants_sal_model->get_list(8);
        $data['sp_no_cons'] = $this->constants_sal_model->get_list(9);
        $data['degree_cons'] = $this->constants_sal_model->get_list(11);
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);

        $data['bank_cons'] = $this->constants_sal_model->get_list(17);
        $data['master_banks_email_cons'] = $this->constants_sal_model->get_list(18);
        $data['is_active_cons'] = $this->constants_sal_model->get_list(24);
        $data['is_taxed_cons'] = $this->constants_sal_model->get_list(23);
        $data['insuranced_cons'] = $this->constants_sal_model->get_list(22);
        $data['bank_branch_email_cons'] = $this->constants_sal_model->get_list(20);

    }


    function _postedData()
    {
        $result = array(
            array('name' => 'NO', 'value' => $this->no, 'type' => '', 'length' => -1),
            array('name' => 'ID', 'value' => $this->id, 'type' => '', 'length' => -1),
            array('name' => 'NAME', 'value' => $this->name, 'type' => '', 'length' => -1),
            array('name' => 'TEL', 'value' => $this->tel, 'type' => '', 'length' => -1),
            array('name' => 'BIRTH_PLACE', 'value' => $this->birth_place, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $this->status, 'type' => '', 'length' => -1),
            array('name' => 'BIRTH_DATE', 'value' => $this->birth_date, 'type' => '', 'length' => -1),
            array('name' => 'RELIGION', 'value' => $this->religion, 'type' => '', 'length' => -1),
            array('name' => 'SEX', 'value' => $this->sex, 'type' => '', 'length' => -1),
            array('name' => 'ADDRESS', 'value' => $this->address, 'type' => '', 'length' => -1),
            array('name' => 'EMP_TYPE', 'value' => $this->emp_type, 'type' => '', 'length' => -1),

            array('name' => 'HIRE_DATE', 'value' => $this->hire_date, 'type' => '', 'length' => -1),
            array('name' => 'FEX_DATE', 'value' => $this->fex_date, 'type' => '', 'length' => -1),
            array('name' => 'BRAN', 'value' => $this->bran, 'type' => '', 'length' => -1),
            array('name' => 'GCC_NO', 'value' => $this->gcc_no, 'type' => '', 'length' => -1),
            array('name' => 'HEAD_DEPARTMENT', 'value' => $this->head_department, 'type' => '', 'length' => -1),
            array('name' => 'Q_DATE', 'value' => $this->q_date, 'type' => '', 'length' => -1),
            array('name' => 'Q_NO', 'value' => $this->q_no, 'type' => '', 'length' => -1),
            array('name' => 'SP_NO', 'value' => $this->sp_no, 'type' => '', 'length' => -1),
            array('name' => 'KAD_NO_N', 'value' => $this->kad_no_n, 'type' => '', 'length' => -1),
            array('name' => 'PERIODICAL_ALLOWNCES', 'value' => $this->periodical_allownces, 'type' => '', 'length' => -1),
            array('name' => 'DEGREE', 'value' => $this->degree, 'type' => '', 'length' => -1),
            array('name' => 'HIRE_YEARS', 'value' => $this->hire_years, 'type' => '', 'length' => -1),
            array('name' => 'W_NO', 'value' => $this->w_no, 'type' => '', 'length' => -1),
            array('name' => 'W_NO_ADMIN', 'value' => $this->w_no_admin, 'type' => '', 'length' => -1),
            array('name' => 'EXPERIENCES', 'value' => $this->experiences, 'type' => '', 'length' => -1),

            array('name' => 'BANK', 'value' => $this->bank, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT', 'value' => $this->account, 'type' => '', 'length' => -1),
            array('name' => 'IBAN', 'value' => $this->iban, 'type' => '', 'length' => -1),
            array('name' => 'IS_ACTIVE', 'value' => $this->is_active, 'type' => '', 'length' => -1),
            array('name' => 'IS_TAXED', 'value' => $this->is_taxed, 'type' => '', 'length' => -1),
            array('name' => 'INSURANCED', 'value' => $this->insuranced, 'type' => '', 'length' => -1),
            array('name' => 'INSURANCE_NO', 'value' => $this->insurance_no, 'type' => '', 'length' => -1),
            array('name' => 'MASTER_BANKS_EMAIL', 'value' => $this->master_banks_email, 'type' => '', 'length' => -1),
            array('name' => 'BANK_BRANCH_EMAIL', 'value' => $this->bank_branch_email, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_BANK_EMAIL', 'value' => $this->account_bank_email, 'type' => '', 'length' => -1),

        );
        return $result;
    }

}
