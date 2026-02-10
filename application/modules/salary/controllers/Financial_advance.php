<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 12/16/21
 * Time: 5:41 AM
 */
class Financial_advance extends MY_Controller
{

    var $MODEL_NAME = 'Financial_advance_model';
    var $PAGE_URL = 'salary/Financial_advance/get_page';
    //var $PAGE_ACT;

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->PAGE_ACT = $this->uri->segment(5);
        if (isset($this->PAGE_ACT) and $this->PAGE_ACT != '' and ($this->uri->segment(3) == 'index')) {

            if (!HaveAccess(base_url("salary/Financial_advance/index/1/" . $this->PAGE_ACT))) {
                die('Error: No Permission ' . $this->PAGE_ACT);
            }

            if ($this->PAGE_ACT == 'my') {

            } elseif ($this->PAGE_ACT == 'manager') {

            } elseif ($this->PAGE_ACT == 'admin') {

            } else {
                die('PAGE_ACT');
            }
        } elseif ($this->uri->segment(3) == 'index') {
            die('index');
        }

    }

    function index($page = 1)
    {

        if (isset($this->PAGE_ACT)) {
            if ($this->PAGE_ACT == 'my') {
                $data['title'] = 'سلف مالية - موظف';
            } elseif ($this->PAGE_ACT == 'manager') {
                $data['title'] = 'سلف مالية - المدير';
            } elseif ($this->PAGE_ACT == 'admin') {
                $data['title'] = 'سلف مالية - اعتمادات السلف';
            } else {
                $data['title'] = '??سلف مالية';
            }
        }

        $data['content'] = 'Financial_advance_index';

        $data['page']     = $page;
        $data['page_act'] = $this->PAGE_ACT;

        $this->_look_ups($data);

        $this->load->view('template/template', $data);
    }


    function get_page($page = 1,$page_act ='')
    {
        $this->load->library('pagination');

	    if (!isset($this->p_page_act)){
            $this->p_page_act=$page_act;
        }

        $this->PAGE_ACT   = $this->p_page_act;
        $data['page_act'] = $this->PAGE_ACT;
        $where_sql        = " where 1=1 ";

        if (isset($this->PAGE_ACT)) {
            if ($this->PAGE_ACT == 'my') {
                $where_sql .= " AND EMP_NO= {$this->user->emp_no} ";
            } elseif ($this->PAGE_ACT == 'manager') {
                $where_sql .= " AND ENTRY_USER= {$this->user->id} ";
            } elseif ($this->PAGE_ACT == 'admin') {
                $where_sql .= " and adopt > 1 ";
				if($this->user->branch!=1)
					$where_sql .= " and BRANCH = {$this->user->branch}";
            } else {
                $where_sql .= " and 1= 2 ";
            }
        } else {
            $where_sql .= " and 1= 2 ";
        }

		if(!$this->input->is_ajax_request() and $this->PAGE_ACT == 'admin'){
            $adopt_url= 'salary/Financial_advance';
            $adopt_where_sql=' ';
            $default_where_sql= $where_sql;
            $i=10;

            while($i<=70)
            {
                if(HaveAccess(base_url("$adopt_url/adopt_".$i)))
                {
                    if ($this->PAGE_ACT == 'admin' and $i >= 20)
                    {
                        $j=$i-10;
                        $adopt_where_sql=" and adopt= {$j} ";
                    }
                }
                $i= $i+10;
            }
            $default_where_sql.= $adopt_where_sql;
            $where_sql= $default_where_sql;
		}
        //echo $where_sql;

        $where_sql .= isset($this->p_ser) && $this->p_ser != null ? " AND  SER  ={$this->p_ser}  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  EMP_NO  ={$this->p_emp_no}  " : "";
        $where_sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  BRANCH  ={$this->p_branch}  " : "";
        $where_sql .= isset($this->p_emp_type) && $this->p_emp_type != null ? " AND  EMP_TYPE  ={$this->p_emp_type}  " : "";
        $where_sql .= isset($this->p_installments_no) && $this->p_installments_no != null ? " AND  INSTALLMENTS_NO  ={$this->p_installments_no}  " : "";
        $where_sql .= isset($this->p_advance_type) && $this->p_advance_type != null ? " AND  ADVANCE_TYPE  ={$this->p_advance_type}  " : "";
        $where_sql .= isset($this->p_reason_id) && $this->p_reason_id != null ? "  AND  REASON_ID  ={$this->p_reason_id}  " : "";
        $where_sql .= isset($this->p_advance_balance_allow) && $this->p_advance_balance_allow != null ? "  AND  ADVANCE_BALANCE_ALLOW  ={$this->p_advance_balance_allow}  " : "";
        $where_sql .= isset($this->p_adopt) && $this->p_adopt != null ? "  AND  ADOPT  ={$this->p_adopt}  " : "";

		//echo $where_sql;


        $config['base_url']         = base_url($this->PAGE_URL);
        $count_rs                   = $this->get_table_count('FINANCIAL_ADVANCE_TB', $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows']       = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page']         = $this->page_size;
        $config['num_links']        = 20;
        $config['cur_page']         = $page;
        $config['full_tag_open']    = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close']   = '</ul></div>';
        $config['first_tag_open']   = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close']  = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open']     = '<li class="active"><span><b>';
        $config['cur_tag_close']    = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = (((($page) - 1) * $config['per_page']));
        $row    = ((($page) * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);
        $data['offset']    = $offset + 1;
        $data['page']      = $page;
        $this->_look_ups($data);
        $this->load->view('Financial_advance_page', $data);
    }


    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
			$this->print_error('ادخال السلف معطل حاليا لعدم توفر رصيد في الموازنة');
		
            $this->_post_validation();
            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if (intval($this->ser) <= 0) {
                $this->print_error(' لم يتم الحفظ الجدول الاساسي ' . '<br>' . $this->ser);
            } else {
                echo intval($this->ser);
            }
        } else {

            $data['content']         = 'Financial_advance_show';
            $data['title']           = 'اضافة سلفة مالية';
            $data['isCreate']        = true;
            $data['action']          = 'index';
            $data['emp_no_selected'] = $this->user->emp_no;
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }

    function get($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        if (!(count($result) == 1))
            die('get');
        $data['master_tb_data'] = $result;
        $data['next_adopt_email']= $this->get_emails_by_code('10.'.($result[0]['ADOPT']+20));
        $data['can_edit']       = 1;
        $data['action']         = 'edit';
        $data['content']        = 'Financial_advance_show';
        $data['title']          = 'بيانات سلفة مالية  ';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData(''));
            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            } else {
                echo 1;
            }
        }
    }

    function _post_validation()
    {
        $result = $this->{$this->MODEL_NAME}->get_emp_info($this->p_emp_no);
        if ($result[0]['EMP_TYPE'] != 1) {
            /*if(($this->p_emp_sponsor_1 ==0 || $this->p_emp_sponsor_1 =='') || ( $this->p_emp_sponsor_2 =='') && ($this->p_emp_sponsor_2 =='')  )
            {
            $this->print_error('يتوجب عليك ادخال كفيل واحد على الاقل!!');
            }
            else
            {*/
            if ($this->p_emp_sponsor_1 == $this->p_emp_sponsor_2) {
                $this->print_error('الكفيل الثاني هو نفس الكفيل الاول!!');
            }
            //}
        }
        $result_1 = $this->{$this->MODEL_NAME}->get($this->p_emp_no);
        if (count($result_1) == 1) {
            if ($result_1[0]['ADOPT'] == 10) {
                if (($this->p_advance_balance_allow == 0 || $this->p_advance_balance_allow == '') && ($this->p_bank_commitment == '') && ($this->p_net_salary_trad == ''))
                    $this->print_error('يتوجب عليك ادخال جميع الحقول التالية:رصيد السلفة,قيمة الالتزامات البنكية,قيمة صافي الراتب لأغراض الإئتمان التجاري!!');
            }
        }

    }



    function public_get_id()
    {


        $id     = $this->input->post('id');
        // $this->print_error($id);
        $result = $this->{$this->MODEL_NAME}->get_emp_info($id);
        //print_r($result);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }
    ///////////////////////////////////////////////////////////////
    function adopt_10()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 1 && HaveAccess(base_url("salary/Financial_advance/adopt_10"))) {

                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, $info[0]['ADOPT'],$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt_20()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 10 && HaveAccess(base_url("salary/Financial_advance/adopt_20"))) {
                //$this->_post_validation();
                if (($this->p_advance_balance_allow == 0 || $this->p_advance_balance_allow == '') && ($this->p_bank_commitment == '') && ($this->p_net_salary_trad == ''))
                    $this->print_error('يتوجب عليك ادخال جميع الحقول التالية:رصيد السلفة,قيمة الالتزامات البنكية,قيمة صافي الراتب لأغراض الإئتمان التجاري!!');

                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, $info[0]['ADOPT'],$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt_30()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 20 && HaveAccess(base_url("salary/Financial_advance/adopt_30"))) {
                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, $info[0]['ADOPT'],$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt_40()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 30 && HaveAccess(base_url("salary/Financial_advance/adopt_40"))) {
                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, $info[0]['ADOPT'],$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt_50()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 40 && HaveAccess(base_url("salary/Financial_advance/adopt_50"))) {
                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, $info[0]['ADOPT'],$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt_60()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 50 && HaveAccess(base_url("salary/Financial_advance/adopt_60"))) {
                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, $info[0]['ADOPT'],$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt_70()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 60 && HaveAccess(base_url("salary/Financial_advance/adopt_70"))) {
                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, $info[0]['ADOPT'],$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    //////////////////////////////////////////////////////////////
    function adopt_0(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 1 && HaveAccess(base_url("salary/Financial_advance/adopt_0"))) {
                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, -1 ,$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الغاء الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الغاء الاعتماد' . '<br>');
            }
        } else
            echo "لم يتم الغاء الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt__10()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 10 && HaveAccess(base_url("salary/Financial_advance/adopt__10"))) {

                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, -10 ,$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt__20()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 20 && HaveAccess(base_url("salary/Financial_advance/adopt__20"))) {

                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, -20 ,$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt__30()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 30 && HaveAccess(base_url("salary/Financial_advance/adopt__30"))) {

                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, -30 ,$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt__40()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 40 && HaveAccess(base_url("salary/Financial_advance/adopt__40"))) {

                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, -40 ,$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt__50()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 50 && HaveAccess(base_url("salary/Financial_advance/adopt__50"))) {

                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, -50 ,$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }
    ///////////////////////////////////////////////////////////////
    function adopt__60()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $info = $this->{$this->MODEL_NAME}->get($this->p_id);
            if ($info[0]['ADOPT'] == 60 && HaveAccess(base_url("salary/Financial_advance/adopt__60"))) {

                $res = $this->{$this->MODEL_NAME}->adopt_info($this->p_id, -60 ,$this->p_notes);

                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
                }
                echo 1;
            } else {
                $this->print_error(' ليس من صلاحياتك !!,, لم يتم الاعتماد' . '<br>');
            }
        } else
            echo "لم يتمم الاعتماد";
    }

    /////////////////////////////////////////////////////////////////
    function _look_ups(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('constants_sal_model');
        $this->load->model('settings/constant_details_model');
        //$data['branches']= $this->gcc_branches_model->get_all();

        $this->load->model('hr_attendance/hr_attendance_model');
        $data['sponsor_no_cons']            = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['emp_no_cons']                = $this->hr_attendance_model->get_child($this->user->emp_no, 'manager');
        //$data['bran_cons']                  = $this->constants_sal_model->get_list(5);
        $data['bran_cons']                  = $this->gcc_branches_model->get_all();
        $data['emp_type_cons']              = $this->constants_sal_model->get_list(21);
        $data['reason_id_cons']             = $this->constant_details_model->get_list(400);
        $data['advance_balance_allow_cons'] = $this->constant_details_model->get_list(401);
        $data['advance_type_cons']          = $this->constant_details_model->get_list(403);
        $data['adopt_cons']                 = $this->constant_details_model->get_list(402);




        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($isCreate)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO','value' => $this->p_emp_no,'type' => '','length' => -1),
            array('name' => 'ADVANCE_VALUE','value' => $this->p_advance_value,'type' => '','length' => -1),
            array('name' => 'INSTALLMENTS_NO','value' => $this->p_installments_no,'type' => '','length' => -1),
            array('name' => 'REASON_ID','value' => $this->p_reason_id,'type' => '','length' => -1),
            array('name' => 'ADVANCE_BALANCE_ALLOW','value' => $this->p_advance_balance_allow,'type' => '','length' => -1),
            array('name' => 'EMP_SPONSOR_1','value' => $this->p_emp_sponsor_1,'type' => '','length' => -1),
            array('name' => 'EMP_SPONSOR_2','value' => $this->p_emp_sponsor_2,'type' => '','length' => -1),
            array('name' => 'NOTES','value' => $this->p_notes,'type' => '','length' => -1),
            array('name' => 'BANK_COMMITMENT','value' => $this->p_bank_commitment,'type' => '','length' => -1),
            array('name' => 'NET_SALARY_TRAD','value' => $this->p_net_salary_trad,'type' => '','length' => -1),
	        array('name' => 'ADOPT_NOTES','value' => $this->p_adopt_notes,'type' => '','length' => -1)
        );
        if ($isCreate == 'create') {
            array_shift($result);
        }
        return $result;
    }

}