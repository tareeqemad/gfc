<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 25/02/2020
 * Time: 11:37 ص
 */
class overtime_calc extends MY_Controller {

    var $PKG_NAME = "TRANSACTION_PKG";
    var $MODEL_NAME= 'overtime_calc_model';
    var $PAGE_URL= 'payroll_data/overtime_calc/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';
        //this for constant using
        $this->load->model('settings/constant_details_model');

    }

    function index($page= 1){
        $data['title'] = 'تجميع الوقت الاضافي للمقرات';
        $data['content'] = 'overtime_calc_index';
        $data['page']=$page;
        $this->_lookup($data);
        $this->load->view('template/template',$data);
    }

    function get_page($page = 1, $EMP_BRANCH = '',$MONTH = ''){
        $this->load->library('pagination');
        $emp_branch = $this->input->post('emp_branch');
        $month = $this->input->post('month');

        if ($emp_branch != '') {
            $EMP_BRANCH = $emp_branch;
        }

        if ($month != '') {
            $MONTH = $month;
        }


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.OVERTIME  M '.' where 1=1 ');
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
        // echo $where_sql;
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_d($EMP_BRANCH,$MONTH);
        $data['page']=$page;
        $this->load->view('overtime_calc_page',$data);

    }
    /********************************************************************************************/

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

    /********************************************************************************************/
    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $this->load->model('salary/constants_sal_model');
        $data['branches'] = $this->constants_sal_model->get_list(5);

        $data['adopt_cons'] = $this->constant_details_model->get_list(318);

        $this->load->model('salary/constants_sal_model');
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);
        $data['bran_cons'] = $this->constants_sal_model->get_list(5);
        $data['head_department_cons'] = $this->constants_sal_model->get_list(7);
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no ,'hr_admin');
        $data['help'] = $this->help;
    }

}
