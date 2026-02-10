<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 16/03/25
 * Time: 10:08 ص
 */
class Entitl_deduct extends MY_Controller
{
    var $page_url = 'salary/Entitl_deduct/get_page';
    var $financial_query_url = 'salary/Entitl_deduct/financial_query';
	
	var $hrAdmins = [
			1008 => 'hesahm',
			705 => 'akram',
			976 => 'fady',
			674 => 'moh_ashi',
			1022 => 'moh_zan',
			997 => 'tareq_dal',
			1015 => 'basam_mous',
			994 => 'morsed',
			708 => 'neveen_non',
			1500 => 'khaled_has',
			743 => 'khaled_shamia',
			1492 => 'Mahmoud Badawi',
			1362 => 'MK',
		];
	var $hrBranch = [ 
			1009 => 'Mohammed Najjar',
			1029 => 'Mahmoud Zaiter',
			1014 => 'Rami Shaaer',
			765 => 'Zaher Kafarna',
			960 => 'ibrahem shamia',
		];

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
		$this->load->model('data_admin_model');
        $this->rmodel->package = 'SALARYFORM';

    }

    function index($page = 1)
    {
        $data['title'] = 'مراجعة البدلات بعد الاحتساب  ';
        $data['content'] = 'Entitl_deduct_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.EMP_NO = '{$this->p_emp_no}' " : "";
		$where_sql .= " AND M.MONTH BETWEEN '{$this->p_from_month}' AND '{$this->p_to_month}' ";
        //$where_sql .= isset($this->p_branch_id) && $this->p_branch_id != null ? " AND TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT BRANCH_NO  FROM DATA.BRANCH gg WHERE gg.BRANCH_NO  = '{$this->p_branch_id}' ) " : '';
		//echo $where_sql;
        $config['base_url'] = base_url($this->page_url);
        $count_rs = /*$this->get_table_count(" DATA.ADD_AND_DED M  WHERE 1 = 1  {$where_sql}");*/ 0;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = 200; //200;
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
        $data["page_rows"] = $this->rmodel->getList('SALARY_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('Entitl_deduct_page', $data);

    }

    function get($emp_no, $month, $page_act = -1)
    {
        $result = $this->data_admin_model->get($emp_no, $month); 
        if (!(count($result) == 1))
            die('get');
		
		if(!in_array($this->user->emp_no, array_keys($this->hrAdmins))){
			// اذا حاول الموظف الدخول لبيانات موظف اخر
			if($emp_no!= $this->user->emp_no ){
				die('Error emp_no');
			}
		}

        // منع عرض بيانات الراتب الا بعد اعتماده باستثناء صلاحية كل المقرات
        if($page_act!='hr_admin' and $result[0]['MONTH'] > $result[0]['LAST_SALARY_MONTH'] ){
           // die('Error last_salary_month');
        }

        $additions= $this->data_admin_model->get_salary($emp_no, $month, 1);
        $discounts= $this->data_admin_model->get_salary($emp_no, $month, 0);

        $data['master_tb_data'] = $result;
        $data['additions_data'] = $additions;
        $data['discounts_data'] = $discounts;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        if ($page_act == '' or $page_act == -1) die('get:page_act');
        $data['page_act'] = $page_act;
        $data['content'] = 'Entitl_deduct_show';
        $data['title'] = 'بيانات الراتب ';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function _look_ups(&$data)
    {
        //$data['data_cons'] = $this->rmodel->getData('CONSTANT_DATA_GET_ALL');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model'); 
        $this->load->model('constants_sal_model');

        $data['status_cons'] = $this->constants_sal_model->get_list(1);
        $data['q_no_cons'] = $this->constants_sal_model->get_list(8);
        $data['degree_cons'] = $this->constants_sal_model->get_list(11);
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);
        $data['bank_cons'] = $this->constants_sal_model->get_list(17);
        $data['department_cons'] = $this->constants_sal_model->get_list(7);
        $data['branch_cons'] = $this->constants_sal_model->get_list(4);
        $data['bran_cons'] = $this->constants_sal_model->get_list(5);

        $data['sal_con_cons'] = $this->constants_sal_model->get_list(25);

		
		

		$role = in_array($this->user->emp_no, array_keys($this->hrAdmins)) ? 'hr_admin' : (in_array($this->user->emp_no, array_keys($this->hrBranch)) ? 'hr_branch' : 'manager');

		$data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, $role);
		if(in_array($this->user->emp_no, array_keys($hrAdmins))){
			$data['branches'] = $this->gcc_branches_model->get_all();
		}else{
			$data['branches'] = $this->gcc_branches_model->user_branch($this->user->id);
		}
        

    }

}