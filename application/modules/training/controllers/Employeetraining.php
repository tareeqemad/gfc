<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 22/01/20
 * Time: 11:45 ص
 */

class Employeetraining extends MY_Controller
{
    var $PKG_NAME = "TRAIN_PKG";
    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRAIN_PKG';
		$this->load->model('training_model');
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
        $data['title'] = 'دورات الموظفين';
        $data['content'] = 'employeeTraining_index';
        $data['offset']=1;
        $data['page']=$page;
        $data['hide'] ='hidden';
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

        $data['trainee_type_con'] = $this->constant_details_model->get_list(354);
        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Employeetraining/get_page/");

        $sql = '  WHERE 1 = 1';

        $sql .= isset($this->p_branch_no_dp) && $this->p_branch_no_dp ? " AND BRANCH_NO= {$this->p_branch_no_dp} " : '';

        $sql .= isset($this->p_course_no) && $this->p_course_no ? " AND COURSE_NO= '{$this->p_course_no}'" : '';
        $sql .= isset($this->p_course_name) && $this->p_course_name ? " AND COURSE_NAME like '".add_percent_sign($this->p_course_name)."' " : "";
        $sql .= isset($this->p_course_name_eng) && $this->p_course_name_eng ? " AND COURSE_NAME_ENG like '".add_percent_sign($this->p_course_name_eng)."' " : "";
        $sql .= isset($this->p_course_date) && $this->p_course_date ? " AND COURSE_DATE= '{$this->p_course_date}' " : '';

        $count_rs =$this->get_table_count("TRAIN_COURSES_TB {$sql}");

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
        $data["rows"] = $this->rmodel->getList('TRAIN_COURSES_TB_LIST', " $sql ", $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('employeeTraining_page', $data);
    }


    /**************************************create*****************************************/

    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->ser = $this->rmodel->insert('TRAIN_COURSES_TB_INSERT', $this->_postedData());

            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                echo intval($this->ser);

            }

        } else {

            $data['title'] = 'اضافة دورة جديدة';
            $data['action'] = 'index';
            $data['isCreate'] = true;
            $data['result_com'] = array();
            $data['result_per'] = array();
            $data['result_gedco'] = array();
            $data['result_trainee'] = array();
            $data['content'] = 'employeeTraining_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    /*************************************_postedData*******************************************/

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'COURSE_NAME', 'value' => $this->p_course_name, 'type' => '', 'length' => -1),
            array('name' => 'COURSE_NAME_ENG', 'value' => $this->p_course_name_eng, 'type' => '', 'length' => -1),
            array('name' => 'COURSE_DATE', 'value' => $this->p_course_date, 'type' => '', 'length' => -1),
            array('name' => 'TARGET_GROUP', 'value' => $this->p_target_group, 'type' => '', 'length' => -1),
            array('name' => 'COURSE_HOUR', 'value' => $this->p_course_hour, 'type' => '', 'length' => -1),
            array('name' => 'REQUEST_SIDE', 'value' => $this->p_request_side, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
        );
        if ($isCreate)
            array_shift($result);
        return $result;
    }


    /********************************************edit*********************************************/

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= $this->rmodel->update('TRAIN_COURSES_TB_UPDATE', $this->_postedData(false));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }
            echo intval($this->ser);
        }
    }


    /****************************************adopt*************************************************/

    function adopt(){
        $res= $this->rmodel->update('TRAIN_COURSES_TB_ADOPT', $this->_postedData_adopt($this->p_id,2, false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;
    }


    /****************************************unadopt*************************************************/

    function unadopt(){
        $res= $this->rmodel->update('TRAIN_COURSES_TB_ADOPT', $this->_postedData_adopt($this->p_id,1, false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;
    }

    function _postedData_adopt($ser = null , $adopt = null, $isCreate = false)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser , 'type' => '', 'length' => -1),
            array('name' => 'ADOPT', 'value' => $adopt , 'type' => '', 'length' => -1),
        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }

    function get_attendance($id, $course_no){
        $result = $this->rmodel->get('TRAIN_COURSES_TB_GET', $course_no);
        $data['title'] = 'كشف الحضور والانصراف للموظفين';
        $data['content'] = 'employeeTraining_attendance_show';

        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( 0 , 'hr_admin' );
        $data['details_trainee'] = $this->training_model->getTwoID('TRAIN_EMP_ATTENDANCE_GET', $course_no, $id);
        $data['details_date'] = $this->rmodel->get('TRAIN_DATE_GET', $id);

        $data['result'] = $result;
        $data['id'] = $id;
        $data['course_no'] = $course_no;

        $data['isCreate'] = false;
        $data['hide'] = 'hidden';
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    function get($id)
    {

        $result = $this->rmodel->get('TRAIN_COURSES_TB_GET', $id);
        $data['title'] = 'تعديل بيانات الدورة';
        $data['content'] = 'employeeTraining_show';

        $data["details"] = $this->rmodel->getList('TRAIN_REQUESTS_TB_LIST', "  ", 0, 200);
        $data["details_company"] = $this->rmodel->getList('TRAIN_CORPO_REQUE_TB_LIST', "  ", 0, 2000);
        $data["trainee_gedco"] = $this->rmodel->getList('TRAIN_TRAINEE_GEDCO_LIST', "  ", 0, 2000);
		$data['emp_no_cons'] = $this->hr_attendance_model->get_child( 0 , 'hr_admin' );
		
		$result5 = $this->rmodel->getList('USERS_PROG_TB_ALL', " ", 0, 2000);
        $data['rows'] = $result5;

        $data['result'] = $result;
        $data['result_trainee'] = $this->rmodel->get('TRAIN_TRAINEES_DET_GET', $id);
        $data['result_com'] = $this->rmodel->get('TRAIN_CORPO_REQUE_COM_TB_GET', $id);
        $data['result_per'] = $this->rmodel->get('TRAIN_REQUESTS_TB_DET_GET', $id);
        $data['result_gedco'] = $this->rmodel->get('TRAIN_EMP_GEDCO_GET', $id);
        $data['result_gedco'] = $this->rmodel->get('TRAIN_EMP_GEDCO_GET', $id);
        $data['isCreate'] = false;
        $data['hide'] = 'hidden';
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);


    }

    function public_get_trainee_courses_dates($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['details_date'] = $this->rmodel->get('TRAIN_COURSE_DATES_TB_GET', $id);
        $this->_lookup($data);
        $this->load->view('traineeRequest_courses_date_details', $data);
    }

    function saveDates(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {

                    $serDet=$this->rmodel->insert('TRAIN_COURSE_DATES_TB_INSERT',$this->_posteddata_details
                        (null, $this->p_h_course_ser[$i], $this->p_day[$i], $this->p_start_hour[$i]
                            ,$this->p_end_hour[$i], 'create') );
                }
            }

            if ($serDet < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {

                echo intval($serDet);
            }
        }
    }

    function saveDates_update(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                    $serDet=$this->rmodel->insert('TRAIN_COURSE_DATES_TB_INSERT',$this->_posteddata_details
                        (null, $this->p_h_course_ser[$i], $this->p_day[$i], $this->p_start_hour[$i]
                            ,$this->p_end_hour[$i], 'create') );

                    if (intval($serDet) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $serDet);
                    }
                } else {

                    $serDet=$this->rmodel->update('TRAIN_COURSE_DATES_TB_UPDATE',$this->_posteddata_details
                        ($this->p_seq1[$i] ,$this->p_h_course_ser[$i], $this->p_day[$i], $this->p_start_hour[$i]
                            ,$this->p_end_hour[$i],'edit') );


                    if (intval($serDet) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $serDet);
                    }
                }
            }

            if ($serDet < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {

                echo intval($serDet);
            }
        }
    }

    function _posteddata_details($ser = null, $course_ser = null, $day = null,
                                 $start_hour = null,$end_hour=null,$type)
    {
        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'COURSE_SER', 'value' => $course_ser, 'type' => '', 'length' => -1),
            array('name' => 'DAY', 'value' => $day, 'type' => '', 'length' => -1),
            array('name' => 'START_HOUR', 'value' => $start_hour, 'type' => '', 'length' => -1),
            array('name' => 'END_HOUR', 'value' => $end_hour, 'type' => '', 'length' => -1),
        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

    function public_select_emp($txt = null)
    {
        $data['title'] = 'بيانات الموظفين';
        $data['content'] = 'emp_select_view';

        $result = $this->rmodel->getList('USERS_PROG_TB_ALL', " ", 0, 2000);
        $data['rows'] = $result;
        $data['txt'] = $txt;
        $this->load->view('template/view', $data);
    }


    function saveEmpTrain(){
        $id = $this->input->post('emps_ser');
        $course_id = $this->input->post('course_ser');
        $params = array(
            array('name' => 'SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'COURSE_SER_IN', 'value' => $course_id, 'type' => '', 'length' => -1),
        );

        $msg= $this->rmodel->insert('TRAIN_EMP_TRAINEE_INSERT',$params);

        if ($msg < 1) {
            $this->print_error('لم يتم اضافة المتدربين..' . '<br>');
        } else {
            echo intval($msg);
        }

    }
	
	
    function saveEmp()
    {
        $id = $this->input->post('emps_ser');
        $course_id = $this->input->post('course_ser');
        $params = array(
            array('name' => 'SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'COURSE_SER_IN', 'value' => $course_id, 'type' => '', 'length' => -1),
        );
        $msg= $this->rmodel->insert('TRAIN_EMP_TRAINEE_INSERT',$params);

        if ($msg < 1) {
            $this->print_error('لم يتم اضافة المتدربين..' . '<br>');
        } else {
            echo intval($msg);
        }

    }

    function updateName(){
        $id = $this->input->post('id');
        $emp_name_eng = $this->input->post('name');
        $params = array(
            array('name' => 'SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NAME_ENG_IN', 'value' => $emp_name_eng, 'type' => '', 'length' => -1),
        );
        $msg= $this->rmodel->update('TRAIN_EMP_TRAINEE_UPDATE',$params);

        if ($msg < 1) {
            $this->print_error('لم يتم الحفظ..' . '<br>');
        } else {
            echo intval($msg);
        }

    }
	
	function updateAttendance(){

        $id = $this->input->post('id');
        $notes = $this->input->post('notes');
        $start_time = $this->input->post('start_time');
        $end_time = $this->input->post('end_time');
        $course_no_ = $this->input->post('course_no_');
        $emp_no_ = $this->input->post('emp_no_');
        $date_no_ = $this->input->post('date_no_');
        $attendance = $this->input->post('attendance');
		$params = array();
		
		if($id == 0){
        $params = array(
					array('name' => 'COURSE_NO_IN', 'value' => $course_no_, 'type' => '', 'length' => -1),
					array('name' => 'EMP_NO_IN', 'value' => $emp_no_, 'type' => '', 'length' => -1),
					array('name' => 'DATE_NO_IN', 'value' => $date_no_, 'type' => '', 'length' => -1),
					array('name' => 'END_TIME_IN', 'value' => $end_time, 'type' => '', 'length' => -1),
					array('name' => 'START_TIME_IN', 'value' => $start_time, 'type' => '', 'length' => -1),
					array('name' => 'NOTES_IN', 'value' => $notes, 'type' => '', 'length' => -1),
					array('name' => 'ATTENDANCE_IN', 'value' => $attendance, 'type' => '', 'length' => -1),
				);
		}else{
				
		$params = array(
					array('name' => 'SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
					array('name' => 'END_TIME_IN', 'value' => $end_time, 'type' => '', 'length' => -1),
					array('name' => 'START_TIME_IN', 'value' => $start_time, 'type' => '', 'length' => -1),
					array('name' => 'NOTES_IN', 'value' => $notes, 'type' => '', 'length' => -1),
                    array('name' => 'ATTENDANCE_IN', 'value' => $attendance, 'type' => '', 'length' => -1),
				);
		}
		
		if($id == 0){
			$msg= $this->rmodel->insert('TRAIN_EMP_ATTENDANCE_INSERT',$params);
		}else{
			$msg= $this->rmodel->update('TRAIN_EMP_ATTENDANCE_UPDATE',$params);
		}

        if ($msg < 1) {
            $this->print_error('لم يتم الحفظ..' . '<br>');
        } else {
            echo intval($msg);
        }

    }

    function public_get_trainee_courses_trainee($id = 0)
    {
        $data['details_trainee'] = $this->rmodel->get('TRAIN_EMP_TRAINEE_GET', $id);
       // $this->_lookup($data);
        $this->load->view('traineeRequest_courses_trainee_details', $data);
    }

    function public_get_trainee_courses($id = 0)
    {
        $data['details_trainer'] = $this->rmodel->get('TRAIN_TRAINEES_DET_GET', $id);
        // $this->_lookup($data);
        $this->load->view('traineeRequest_trainers_details', $data);
    }
	
	function showCourses(){
		$data['title'] = 'الاستعلام عن دورات الموظف';
		$data['action'] = 'index';
        $data['isCreate'] = true;
		$data['result_com'] = array();
		$data['result_per'] = array();
		$data['result_gedco'] = array();
		$data['result_trainee'] = array();
		$data['content'] = 'employeeTraining_emp_index';
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( 0 , 'hr_admin' );
		$this->_lookup($data);
		$this->load->view('template/template', $data);
	}

    function public_get_emp_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Employeetraining/get_emp_page/");

        $sql = '  WHERE 1 = 1';
        $sqlWhere = '  WHERE 1 = 1';

        $sql .= isset($this->p_emp_id) && $this->p_emp_id ? " AND T.EMP_ID= {$this->p_emp_id} " : '';
        $sqlWhere .= isset($this->p_start_date) && $this->p_start_date ? " AND M.COURSE_DATE >= '{$this->p_start_date}' " : '';
        $sqlWhere .= isset($this->p_end_date) && $this->p_end_date ? " AND M.COURSE_DATE <= '{$this->p_end_date}' " : '';
        $count_rs =$this->get_table_count(" TRAIN_EMP_TRAINEE_TB where 1 = 1 ");

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
        $data["rows"] = $this->training_model->getList_twoColumn('TRAIN_EMP_COURSES_TB_LIST', " $sql ", " $sqlWhere ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('employeeTraining_emp_page', $data);

    }









}
