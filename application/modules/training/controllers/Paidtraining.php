<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 27/01/20
 * Time: 12:26 م
 */


class Paidtraining extends MY_Controller
{

    var $PKG_NAME = "TRAIN_PKG";

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->load->model('training_model');
        $this->rmodel->package = 'TRAIN_PKG';
        $this->training_model->package = 'TRAIN_PKG';
        //this for constant using
        $this->load->model('stores/store_committees_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model("stores/receipt_class_input_group_model");
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model');
    }

    /************************************index*********************************************/

    function index($page = 1)
    {

        $data['title'] = 'الاستعلام عن طلبات المتدربين';
        $data['content'] = 'Paidtraining_index';


        $data['offset']=1;
        $data['page']=$page;


        $data['manage'] = $this->rmodel->getList('MANAGE_ALL', " ", 0, 2000);

        $data['action'] = 'index';
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    /*************************************_lookup****************************************/

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( 0 , 'hr_admin' );
        $data['trainee_type'] = $this->constant_details_model->get_list(312);

        $data['branches'] = $this->gcc_branches_model->get_all();



        $data['manage'] = $this->rmodel->getList('MANAGE_ALL', " ", 0, 2000);
        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Paidtraining/get_page/");

        $sql = '';

        //$sql .= isset($this->p_branch) && $this->p_branch ? " AND BRANCH= {$this->p_branch} " : '';
        $sql .= isset($this->p_manage) && $this->p_manage ? " AND MANAGE = {$this->p_manage} " : '';
        $sql .= isset($this->p_id) && $this->p_id ? " AND Q.ID = {$this->p_id} " : '';
        $sql .= isset($this->p_start_date) && $this->p_start_date ? " AND ENTERY_DATE  >= '".($this->p_start_date)."' " : "";
        $sql .= isset($this->p_end_date) && $this->p_end_date ? " AND ENTERY_DATE  <= '".($this->p_end_date)."' " : "";


        $sql .= isset($this->p_field) && $this->p_field ? " AND FIELD like '".add_percent_sign($this->p_field)."' " : "";
        $sql .= isset($this->p_name) && $this->p_name ? " AND TRAIN_PKG.GET_NAME (Q.FIRST_NAME,Q.SECOND_NAME,Q.THIRD_NAME,Q.LAST_NAME) LIKE '".add_percent_sign($this->p_name)."' " : "";
        $count_rs = 20000;// $this->get_table_count(" gedcoapps.TRANNING_TB@TT.GEDCO.COM {$sql}");


        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = 20000;// $count_rs ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $data["rows"] = $this->rmodel->getList('TRANNING_TB_LIST', " $sql ", $offset, 20000);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('Paidtraining_page', $data);
    }

    function get($id)
    {
        $result = $this->rmodel->get('TRAIN_TB_GET', $id);
        $data['title'] = 'البيانات الشخصية';
        $data['content'] = 'traineeRequest_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['type'] = 0 ;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function create(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser = $this->rmodel->insert('TRAIN_PAID_TB_INSERT', $this->_postedData());

            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                echo intval($this->ser);
            }
        }
    }

    function create_interview(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //var_dump($this->_postedData_interview()); die;

            $this->ser = $this->rmodel->insert('TRAIN_INTEVIEWS_TB_INSERT', $this->_postedData_interview());

            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                echo intval($this->ser);
            }
        }
    }

    function _postedData_interview($isCreate = true)
    {
        $result = array(
            array('name' => 'TRAIN_SER', 'value' => $this->p_ser2, 'type' => '', 'length' => -1),
            array('name' => 'ID', 'value' => $this->p_id_nu, 'type' => '', 'length' => -1),
            array('name' => 'INTERVIEW_PLACE', 'value' => $this->p_interview_place, 'type' => '', 'length' => -1),
            array('name' => 'INTERVIEW_DATE', 'value' => $this->p_interview_date, 'type' => '', 'length' => -1),
            array('name' => 'INTERVIEW_TIME', 'value' => $this->p_interview_time, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->p_branch, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
        );

        return $result;
    }

    function _postedData($isCreate = true)
    {
		
        $result = array(
            array('name' => 'ID', 'value' => $this->p_id_nu, 'type' => '', 'length' => -1),
            array('name' => 'NAME', 'value' => $this->p_id_name, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->p_branch, 'type' => '', 'length' => -1),
            array('name' => 'MANAGE', 'value' => $this->p_manage, 'type' => '', 'length' => -1),
            array('name' => 'INCENTIVE_VALUE', 'value' => $this->p_incentive_value, 'type' => '', 'length' => -1),
            array('name' => 'TRAINING_PERIOD', 'value' => $this->p_training_period, 'type' => '', 'length' => -1),
            array('name' => 'START_DATE', 'value' => $this->p_start_date, 'type' => '', 'length' => -1),
            array('name' => 'RESPONSIBLE_EMP', 'value' => $this->p_responsible_emp, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
        );

        return $result;
    }

    function accept($page = 1){

        $data['title'] = 'الاستعلام عن المدربين المقبولين ';
        $data['content'] = 'Paidtraining_accept_index';

        $data['offset']=1;
        $data['page']=$page;

        $data['action'] = 'create';

        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    function candidates($page = 1){
        $data['title'] = 'الاستعلام عن المرشحين للمقابلة  ';
        $data['content'] = 'Paidtraining_candidates_index';

        $data['offset']=1;
        $data['page']=$page;

        $data['action'] = 'index';

        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }


    function accept_get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Paidtraining/accept_get_page/");

        //echo  1; die;

        $sql = '';

        $sql .= isset($this->p_branch) && $this->p_branch ? " AND BRANCH= {$this->p_branch} " : '';
        $sql .= isset($this->p_manage) && $this->p_manage ? " AND MANAGE = {$this->p_manage} " : '';
        $sql .= isset($this->p_id) && $this->p_id ? " AND M.ID = {$this->p_id} " : '';
        $sql .= isset($this->p_name) && $this->p_name ? " AND M.NAME LIKE '".add_percent_sign($this->p_name)."' " : "";

        $sql .= isset($this->p_start_date) && $this->p_start_date ? " AND START_DATE  >= '".($this->p_start_date)."' " : "";
        $sql .= isset($this->p_end_date) && $this->p_end_date ? " AND ADD_MONTHS(START_DATE, TRAINING_PERIOD) <= '".($this->p_end_date)."' " : "";

        //echo $sql; die;


        $count_rs = $this->get_table_count(" TRAIN_PAID_TB where 1 = 1 ");


        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $data["rows"] = $this->rmodel->getList('TRAIN_PAID_TB_LIST', " $sql ", $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('Paidtraining_accept_page', $data);
    }

    function get_accept($id)
    {

        $result = $this->rmodel->get('TRAIN_ACCEPT_TB_GET', $id);

        $data['title'] = 'البيانات الشخصية';
        $data['content'] = 'Paidtraining_accept_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['type'] = 0 ;
        $data['action'] = 'edit_accept';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function create_accept(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->ser = $this->rmodel->insert('TRAIN_PAID_TB_INSERT', $this->_postedDataAccept());


            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                echo intval($this->ser);
            }
        } else {

            $data['title'] = 'متدرب جديد';
            $data['action'] = 'index';
            $data['isCreate'] = true;
            $data['content'] = 'Paidtraining_accept_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }
    }

    function _postedDataAccept($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'ID', 'value' => $this->p_id_check, 'type' => '', 'length' => -1),
            array('name' => 'NAME', 'value' => $this->p_name, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->p_branch, 'type' => '', 'length' => -1),
            array('name' => 'MANAGE', 'value' => $this->p_manage, 'type' => '', 'length' => -1),
            array('name' => 'INCENTIVE_VALUE', 'value' => $this->p_incentive_value, 'type' => '', 'length' => -1),
            array('name' => 'TRAINING_PERIOD', 'value' => $this->p_training_period, 'type' => '', 'length' => -1),
            array('name' => 'START_DATE', 'value' => $this->p_start_date, 'type' => '', 'length' => -1),
            array('name' => 'RESPONSIBLE_EMP', 'value' => $this->p_responsible_emp, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
        );
        if ($isCreate)
            array_shift($result);
        return $result;
    }

    function edit_accept(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser = $this->rmodel->update('TRAIN_PAID_TB_UPDATE', $this->_postedDataAccept(false));
            $exp_date = str_replace('/', '-', $this->p_end_date );
            if((strtotime($exp_date) < strtotime(date('d-m-Y')) )){
                for ($i = 0; $i < count($this->p_seq1); $i++)
                {
                    if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                        if ($this->p_incentive_val[$i] != 0  &&  $this->p_start_dat[$i] != 0   &&  $this->p_training_per[$i] != 0 ) {
                            $serDet=$this->rmodel->insert('TRAIN_PAID_DEAILS_TB_INSERT',$this->_posteddata_details
                                (null, $this->ser , $this->p_incentive_val[$i], $this->p_start_dat[$i] , $this->p_training_per[$i] ,'create') );
                        }
                        if (intval($serDet) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $serDet);
                        }
                    } else {
                        $x=$this->rmodel->update('TRAIN_PAID_DEAILS_TB_UPDATE',$this->_posteddata_details
                            ($this->p_seq1[$i],$this->ser , $this->p_incentive_val[$i], $this->p_start_dat[$i] , $this->p_training_per[$i],'edit') );
                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }
                    }
                }
            }

            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                echo intval($this->ser);
            }
        }
    }

    function _posteddata_details($ser = null, $trainee_ser = null, $incentive_val = null,
                                 $start_dat = null , $training_per = null ,$type)
    {
        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'TRAINEE_SER', 'value' => $trainee_ser, 'type' => '', 'length' => -1),
            array('name' => 'INCENTIVE_VAL', 'value' => $incentive_val, 'type' => '', 'length' => -1),
            array('name' => 'START_DAT', 'value' => $start_dat, 'type' => '', 'length' => -1),
            array('name' => 'TRAINING_PER', 'value' => $training_per, 'type' => '', 'length' => -1),
        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

    function public_get_train_period($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $result_period = $this->rmodel->get('TRAIN_ACCEPT_TB_GET_PERIOD', $id);
        if (count($result_period) > 0) {
            echo json_encode($result_period[0]);
        } else {
            echo '';
        }

    }


    function public_get_trainee_renew($id = 0,$adopt=1)
    {
        $data['details'] = $this->rmodel->get('TRAIN_ACCEPT_TB_GET_RENEW', $id);
        $data['allPeriod'] = $this->rmodel->get('GET_PER_ALL', $id);
        $data['adopt']=$adopt;
        $this->_lookup($data);
        $this->load->view('Paidtraining_accept_details', $data);
    }

    function public_candidate_get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Paidtraining/candidate_get_page/");

        $sql = '';

        $sql .= isset($this->p_manage) && $this->p_manage ? " AND MANAGE = {$this->p_manage} " : '';
        $sql .= isset($this->p_id) && $this->p_id ? " AND M.ID = {$this->p_id} " : '';
        $sql .= isset($this->p_name) && $this->p_name ? " AND TRAIN_PKG.GET_NAME (Q.FIRST_NAME,Q.SECOND_NAME,Q.THIRD_NAME,Q.LAST_NAME) LIKE '".add_percent_sign($this->p_name)."' " : "";
        $sql .= isset($this->p_field) && $this->p_field ? " AND FIELD like '".add_percent_sign($this->p_field)."' " : "";

        $count_rs = $this->get_table_count(" TRAIN_PAID_TB where 1 = 1 ");

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $data["rows"] = $this->rmodel->getList('TRANNING_CANDIDATE_TB_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('Paidtraining_candidates_page', $data);
    }


    function salary($page = 1){
        $data['title'] = 'الاستعلام عن رواتب متدربي مدفوع الأجر   ';
        $data['content'] = 'Paidtraining_salary_index';
        $data['offset']=1;
        $data['page']=$page;
        $data['action'] = 'index';

        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function public_salary_get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Paidtraining/public_salary_get_page/");

        $sql = '';

        $sql .= isset($this->p_branch) && $this->p_branch ? " AND M.BRANCH= {$this->p_branch} " : '';
        $sql .= isset($this->p_manage) && $this->p_manage ? " AND M.MANAGE = {$this->p_manage} " : '';
        $count_rs = $this->get_table_count(" TRAIN_PAID_TB where 1 = 1 ");
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $data["rows"] = $this->training_model->getList_Para('TRAINING_SALARY_LIST', " $sql ", $offset, $row , intval($this->p_for_month) );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['added'] = $this->constant_details_model->get_list(360);
        $data['discounts'] = $this->constant_details_model->get_list(361);

        $this->load->view('Paidtraining_salary_page', $data);
    }

    function adopt_sal()
    {
        $para = $this->input->post('param_no');
        $month = $this->input->post('month_adopt');
        $params = array(
            array('name' => 'MONTH_IN', 'value' => $month, 'type' => '', 'length' => -1),
            array('name' => 'SER_IN', 'value' => $para, 'type' => '', 'length' => -1),
        );
        $msg= $this->rmodel->update('CONTRACT_TRAIN_SALRY_TB_TRANS',$params);
        if ($msg < 1) {
            $this->print_error('لم يتم الاعتماد' . '<br>');
        } else {
            echo intval($msg);
        }
    }

    function supervision_salary($page = 1){
        $data['title'] = 'الاستعلام عن رواتب متدربي مدفوع الأجر - الرقابة   ';
        $data['content'] = 'Paidtraining_supervision_index';

        $data['offset']=1;
        $data['page']=$page;

        $data['action'] = 'index';

        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }


    function public_salary_supervision_get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Paidtraining/public_salary_supervision_get_page/");

        $sql = 'WHERE 1 =1 ';

        $sql .= isset($this->p_branch) && $this->p_branch ? " AND M.BRANCH_ID= {$this->p_branch} " : '';
        $sql .= isset($this->p_manage) && $this->p_manage ? " AND M.DEPARTEMENT = {$this->p_manage} " : '';
        $sql .= isset($this->p_for_month) && $this->p_for_month ? " AND M.FOR_MONTH = {$this->p_for_month} " : '';

        $count_rs = $this->get_table_count(" CONTRACT_TRAIN_SALRY_TB where 1 = 1 ");

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $data["rows"] = $this->rmodel->getList('CONTRACT_TRAIN_SALRY_TB_LIST', " $sql ", $offset, $row  );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('Paidtraining_supervision_page', $data);
    }

    function adopt_sal_supervision()
    {
        $para = $this->input->post('param_no');
        $params = array(
            array('name' => 'SER_IN', 'value' => $para, 'type' => '', 'length' => -1),
            array('name' => 'ADOPT_IN', 'value' => 2, 'type' => '', 'length' => -1),
        );

        //var_dump($params); die;
        $msg= $this->rmodel->update('CONTRACT_TRAIN_SALRY_TB_ADOPT',$params);
        if ($msg < 1) {
            $this->print_error('لم يتم الاعتماد' . '<br>');
        } else {
            echo intval($msg);
        }
    }

    function bank_transfer($page = 1){
        $data['title'] = 'حوالات مستحقات متدربي مدفوع الأجر في أقسام الشركة ';
        $data['content'] = 'Paidtraining_bank_index';
        $data['offset']=1;
        $data['page']=$page;
        $data['action'] = 'index';

        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function public_bank_salary_get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Paidtraining/public_bank_salary_get_page/");

        $sql = 'WHERE ADOPT = 2 ';
        $sql .= isset($this->p_branch) && $this->p_branch ? " AND M.BRANCH_ID= {$this->p_branch} " : '';
        $sql .= isset($this->p_manage) && $this->p_manage ? " AND M.DEPARTEMENT = {$this->p_manage} " : '';
        $sql .= isset($this->p_for_month) && $this->p_for_month ? " AND M.FOR_MONTH = {$this->p_for_month} " : '';
        $count_rs = $this->get_table_count(" CONTRACT_TRAIN_SALRY_TB WHERE ADOPT = 2 ");

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $data["rows"] = $this->rmodel->getList('CONTRACT_TRAIN_SALRY_TB_LIST', " $sql ", $offset, $row  );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['month'] = $this->p_for_month;
        $this->load->view('Paidtraining_bank_page', $data);

    }

    function public_get_added_discounts($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data["details"] = $this->rmodel->get('TRAIN_ATTEN_TB_CONTRACT_GET', $id);
        $data['type_salary'] = $this->constant_details_model->get_list(362);
        $this->load->view('Paidtraining_salary_details', $data);
    }

    public function public_get_twoDet($id){
        $a = $this->training_model->public_get_twoDet($id);
        echo json_encode($a) ;
    }

    function create_added(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $x = 0;

            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i] == '' ) {
					
                    if ($this->p_type[$i] != 0  &&  $this->p_add_minus[$i] != 0 && $this->p_value[$i] != 0 ) {
                        $serDet=$this->rmodel->insert('TRAIN_ADD_DISCOUNTS_INSERT',
                            $this->_posteddata_details_forAdded(null, $this->p_trainee_ser_ ,
                                $this->p_contract_ser_, $this->p_for_month_ ,
                                $this->p_type[$i] , $this->p_add_minus[$i] ,
                                intval($this->p_value[$i]),'create') );

                        if (intval($serDet) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $serDet);
                        }
                    }
                } else {
                    $x=$this->rmodel->update('TRAIN_ADD_DISCOUNTS_UPDATE',$this->_posteddata_details_forUpdate
                        ($this->p_seq1[$i], $this->p_type[$i] , $this->p_add_minus[$i] ,
                            intval($this->p_value[$i]),'edit') );

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }
                }
            }
        }
        echo intval($x)!= 0 ? intval($x) : intval($serDet) ;
    }

    function _posteddata_details_forAdded($ser = null, $trainee_ser = null, $contract_no = null,
                                          $for_month = null , $source_type = null,
                                          $source_id = null , $value = null ,$type)
    {
        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'TRAINEE_SER', 'value' => $trainee_ser, 'type' => '', 'length' => -1),
            array('name' => 'CONTRACT_NO', 'value' => $contract_no, 'type' => '', 'length' => -1),
            array('name' => 'FOR_MONTH', 'value' => $for_month, 'type' => '', 'length' => -1),
            array('name' => 'SOURCE_TYPE', 'value' => $source_type, 'type' => '', 'length' => -1),
            array('name' => 'SOURCE_ID', 'value' => $source_id, 'type' => '', 'length' => -1),
            array('name' => 'VALUE', 'value' => $value, 'type' => '', 'length' => -1),
        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

    function _posteddata_details_forUpdate($ser = null,  $source_type = null,
                                          $source_id = null , $value = null ,$type)
    {
        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'SOURCE_TYPE', 'value' => $source_type, 'type' => '', 'length' => -1),
            array('name' => 'SOURCE_ID', 'value' => $source_id, 'type' => '', 'length' => -1),
            array('name' => 'VALUE', 'value' => $value, 'type' => '', 'length' => -1),
        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

    function public_get_added_minus($id = 0, $con_no = 0, $for_month = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $con_no = isset($this->p_con_no) ? $this->p_con_no : $con_no;
        $for_month = isset($this->p_for_month) ? $this->p_for_month : $for_month;
        $data['type_salary'] = $this->constant_details_model->get_list(362);
        $data['source_type_one'] = $this->constant_details_model->get_list(360);
        $data['source_type_two'] = $this->constant_details_model->get_list(361);
        $data["details"] = $this->training_model->get_threeCol('TRAIN_ADD_DISCOUNTS_GET', $id, $con_no, $for_month);
        $this->load->view('Paidtraining_salary_details', $data);
    }

    function adoptAdded(){
        $id = $this->input->post('id');
        $params = array(
            array('name' => 'SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
        );
        $msg= $this->rmodel->update('TRAIN_ADD_DISCOUNTS_ADOPT',$params);

        if ($msg < 1) {
            $this->print_error('لم يتم الحفظ..' . '<br>');
        } else {
            echo intval($msg);
        }
    }




}
