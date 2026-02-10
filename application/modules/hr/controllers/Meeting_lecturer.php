<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/08/23
 * Time: 13:00
 */
class Meeting_lecturer extends MY_Controller {

    var $MODEL_NAME= 'Meeting_lecturer_model';
    var $PAGE_URL= 'hr/Meeting_lecturer/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'HR_PKG';
        $this->ser = $this->input->post('ser');

        //attendance
        $this->meeting_no = $this->input->post('meeting_no');
        $this->meeting_title = $this->input->post('meeting_title');
        $this->meeting_date = $this->input->post('meeting_date');
        $this->meeting_place = $this->input->post('meeting_place');
        $this->meeting_time = $this->input->post('meeting_time');
        $this->meeting_duration = $this->input->post('meeting_duration');
        $this->meeting_status = $this->input->post('meeting_status');
        $this->topics_included = $this->input->post('topics_included');
        $this->topics_novelties = $this->input->post('topics_novelties');
        $this->topics_deferred = $this->input->post('topics_deferred');
        $this->meeting_type = $this->input->post('meeting_type');
        $this->adopt_status = $this->input->post('adopt_status');

        //Attendance details
        $this->ser_d = $this->input->post('ser_d');
        $this->emp_no = $this->input->post('emp_no');
        $this->description = $this->input->post('description');
        $this->attendance_status = $this->input->post('attendance_status');

        //Schedule_work details
        $this->ser_s = $this->input->post('ser_s');
        $this->item_no_s = $this->input->post('item_no_s');
        $this->placement_party = $this->input->post('placement_party');
        $this->category = $this->input->post('category');
        $this->notes = $this->input->post('notes');

        //Recommendations details
        $this->ser_r = $this->input->post('ser_r');
        $this->item_no = $this->input->post('item_no');
        $this->rationale_discussion = $this->input->post('rationale_discussion');
        $this->decision = $this->input->post('decision');

        //
        $this->lecturer_no = $this->input->post('lecturer_no');
        $this->lecturer_title = $this->input->post('lecturer_title');
        $this->from_date = $this->input->post('from_date');
        $this->to_date = $this->input->post('to_date');


    }

    function index()
    {
        $data['content']='meeting_lecturer_index';
        $data['title']='محاضر الإجتماع';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $MODULE_NAME= 'hr';
        $TB_NAME= 'Meeting_lecturer';
        $get_adopt_status_url=base_url("$MODULE_NAME/$TB_NAME/get_adopt_status");

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->lecturer_no!= null)? " and M.MEETING_NO= '{$this->lecturer_no}' " : '';
        $where_sql .= ($this->lecturer_title!= null) ? " AND  M.MEETING_TITLE like '%{$this->lecturer_title}%' " :"" ;
        $where_sql .= ($this->from_date!= null)?  " AND  M.MEETING_DATE >= '{$this->from_date}' " : "";
        $where_sql .= ($this->to_date!= null)?" AND  M.MEETING_DATE <= '{$this->to_date}' " : "";
        $where_sql .= ($this->meeting_type!= null)?" AND  M.MEETING_TYPE <= '{$this->meeting_type}' " : "";

        if (HaveAccess($get_adopt_status_url)){
            $where_sql .= ($this->adopt_status!= null)?" AND  M.ADOPT = '{$this->adopt_status}' " : "";
        }else {
            $where_sql.= "AND  M.ADOPT = 10 ";
        }

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('MEETING_LECTURER_TB  M'.$where_sql);
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
        $this->load->view('meeting_lecturer_page',$data);

    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->ser = $this->{$this->MODEL_NAME}->create_attendance($this->_postedData_attendance('create'));

            if(intval($this->ser) <= 0){
                $this->print_error($this->ser);
            }else{

                for($i=0; $i<count($this->emp_no); $i++){
                    if ($this->ser_d[$i] == 0 ) { // create
                        $req_seq = $this->{$this->MODEL_NAME}->create_attendance_d($this->_postedData_attendance_D(null ,$this->ser ,$this->emp_no[$i], $this->description[$i] ,$this->attendance_status[$i],'create'));
                        if (intval($req_seq) <= 0) {
                            $this->print_error($req_seq);
                        }
                    }

                }
                echo intval($this->ser);
            }
        }
        $data['content']='meeting_lecturer_show';
        $data['title']='إضافة محضر اجتماع';
        $data['action'] = 'index';
        $data['isCreate']= true;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function edit_attendance(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $res = $this->{$this->MODEL_NAME}->edit_attendance($this->_postedData_attendance());

            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                for($i=0; $i<count($this->emp_no); $i++){
                    if ($this->ser_d[$i] == 0 ) { // create
                        $req_seq = $this->{$this->MODEL_NAME}->create_attendance_d($this->_postedData_attendance_D(null ,$this->ser ,$this->emp_no[$i], $this->description[$i] ,$this->attendance_status[$i],'create'));
                        if (intval($req_seq) <= 0) {
                            $this->print_error($req_seq);
                        }
                    } elseif ($this->ser_d[$i] != 0  ) { // edit

                        $req_seq = $this->{$this->MODEL_NAME}->edit_attendance_d($this->_postedData_attendance_D($this->ser_d[$i] ,$this->ser ,$this->emp_no[$i], $this->description[$i] ,$this->attendance_status[$i],'edit'));
                        if(intval($req_seq) <= 0){
                            $this->print_error($req_seq);
                        }
                    }

                }
                echo 1;
            }
        }
    }

    function edit_schedule_work(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                for($i=0; $i<count($this->item_no_s); $i++){

                    if ($this->ser_s[$i] == 0 ) { // create
                        $req_seq = $this->{$this->MODEL_NAME}->create_schedule_work_d($this->_postedData_schedule_work_D(null ,$this->ser ,$this->item_no_s[$i], $this->placement_party[$i] ,$this->category[$i],$this->notes[$i],'create'));
                        if (intval($req_seq) <= 0) {
                            $this->print_error($req_seq);
                        }
                    } elseif ($this->ser_s[$i] != 0  ) { // edit
                        $req_seq = $this->{$this->MODEL_NAME}->edit_schedule_work_d($this->_postedData_schedule_work_D($this->ser_s[$i] ,$this->ser ,$this->item_no_s[$i], $this->placement_party[$i] ,$this->category[$i],$this->notes[$i] ,'edit'));
                        if(intval($req_seq) <= 0){
                            $this->print_error($req_seq);
                        }
                    }

                }
                echo 1 ;
        }
    }

    function edit_recommendations(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                for($i=0; $i<count($this->item_no); $i++){

                    if ($this->ser_r[$i] == 0 ) { // create
                        $req_seq = $this->{$this->MODEL_NAME}->create_recommendations_d($this->_postedData_recommendations_D(null ,$this->ser ,$this->item_no[$i], $this->rationale_discussion[$i] ,$this->decision[$i],'create'));
                        if (intval($req_seq) <= 0) {
                            $this->print_error($req_seq);
                        }
                    } elseif ($this->ser_r[$i] != 0  ) { // edit
                        $req_seq = $this->{$this->MODEL_NAME}->edit_recommendations_d($this->_postedData_recommendations_D($this->ser_r[$i] ,$this->ser ,$this->item_no[$i], $this->rationale_discussion[$i] ,$this->decision[$i] ,'edit'));
                        if(intval($req_seq) <= 0){
                            $this->print_error($req_seq);
                        }
                    }

                }
                echo 1 ;
        }
    }

    function public_get_job_title(){
        $emp_no  = $this->input->post('emp_no');
        if(intval($emp_no) > 0 ) {
            $data = $this->rmodel->get('GET_JOB_TITLE_DESC', $emp_no);
            echo  json_encode($data);
        }
    }

    private function adopt($case){

        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);

        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(10);
        }else{
            echo "لم يتم ارسال رقم الطلب";
        }
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        $result_det_d= $this->{$this->MODEL_NAME}->get_attendance_d($id);
        $result_det_s= $this->{$this->MODEL_NAME}->get_schedule_work_d($id);
        $result_det_r= $this->{$this->MODEL_NAME}->get_recommendations_d($id);
        if(!(count($result)==1))
            die('get');
        $data['can_edit'] = 1;
        $data['master_tb_data']=$result;
        $data['action'] = 'edit_attendance';
        $data['master_det_data_d']=$result_det_d;
        $data['master_det_data_s']=$result_det_s;
        $data['master_det_data_r']=$result_det_r;
        $data['content']='meeting_lecturer_show';
        $data['title']='بيانات محضر الإجتماع ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }


    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');

        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('salary/constants_sal_model');

        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['attendance_status'] = $this->constant_details_model->get_list(528);
        $data['meeting_status'] = $this->constant_details_model->get_list(529);
        $data['meeting_type'] = $this->constant_details_model->get_list(530);
        $data['adopt_status'] = $this->constant_details_model->get_list(535);
        $data['current_date'] = date("d/m/Y");


        $data['emp_no_cons_options']='<option value="0">_________</option>';
        foreach ($data['emp_no_cons'] as $row) :
            $data['emp_no_cons_options']=$data['emp_no_cons_options'].'<option value="'.$row['EMP_NO'].'">'.$row['EMP_NAME'].'</option>';
        endforeach;

        $data['attendance_status_options']='<option value="0">_________</option>';
        foreach ($data['attendance_status'] as $row) :
            $data['attendance_status_options']=$data['attendance_status_options'].'<option value="'.$row['CON_NO'].'">'.$row['CON_NAME'].'</option>';
        endforeach;

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData_attendance($typ= null){

        $result = array(
            array('name'=>'SER','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'MEETING_NO','value'=> $this->meeting_no ,'type'=>'','length'=>-1),
            array('name'=>'MEETING_TITLE','value'=> $this->meeting_title ,'type'=>'','length'=>-1),
            array('name'=>'MEETING_DATE','value'=> $this->meeting_date ,'type'=>'','length'=>-1),
            array('name'=>'MEETING_PLACE','value'=> $this->meeting_place ,'type'=>'','length'=>-1),
            array('name'=>'MEETING_TIME','value'=>$this->meeting_time  ,'type'=>'','length'=>-1),
            array('name'=>'MEETING_DURATION','value'=> $this->meeting_duration ,'type'=>'','length'=>-1),
            array('name'=>'MEETING_STATUS','value'=> $this->meeting_status ,'type'=>'','length'=>-1),
            array('name'=>'TOPICS_INCLUDED','value'=> $this->topics_included ,'type'=>'','length'=>-1),
            array('name'=>'TOPICS_NOVELTIES','value'=> $this->topics_novelties ,'type'=>'','length'=>-1),
            array('name'=>'TOPICS_DEFERRED','value'=> $this->topics_deferred ,'type'=>'','length'=>-1),
            array('name'=>'MEETING_TYPE','value'=> $this->meeting_type ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            unset($result[0]);
        return $result;
    }


    function _postedData_attendance_D( $ser_d = null ,$ser = null ,$emp_no = null ,$description = null ,$attendance_status = null ,$typ= null){
        $result = array(
            array('name'=>'SER_D','value'=> $ser_d,'type'=>'','length'=>-1),
            array('name'=>'SER_M','value'=> $ser,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=> $emp_no,'type'=>'','length'=>-1),
            array('name'=>'DESCRIPTION','value'=>$description ,'type'=>'','length'=>-1),
            array('name'=>'ATTENDANCE_STATUS','value'=>$attendance_status ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            array_shift($result);
        return $result;
    }

    function _postedData_schedule_work_D( $ser_s = null ,$ser = null ,$item_no_s = null ,$placement_party = null ,$category = null ,$notes = null ,$typ= null){
        $result = array(
            array('name'=>'SER_S','value'=> $ser_s,'type'=>'','length'=>-1),
            array('name'=>'SER_M','value'=> $ser,'type'=>'','length'=>-1),
            array('name'=>'ITEM_NO','value'=> $item_no_s,'type'=>'','length'=>-1),
            array('name'=>'PLACEMENT_PARTY','value'=> $placement_party,'type'=>'','length'=>-1),
            array('name'=>'CATEGORY','value'=>$category ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$notes ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            array_shift($result);
        return $result;
    }

    function _postedData_recommendations_D( $ser_r = null ,$ser = null ,$item_no = null ,$rationale_discussion = null ,$decision = null ,$typ= null){
        $result = array(
            array('name'=>'SER_R','value'=> $ser_r,'type'=>'','length'=>-1),
            array('name'=>'SER_M','value'=> $ser,'type'=>'','length'=>-1),
            array('name'=>'ITEM_NO','value'=> $item_no,'type'=>'','length'=>-1),
            array('name'=>'RATIONALE_DISCUSSION','value'=> $rationale_discussion,'type'=>'','length'=>-1),
            array('name'=>'DECISION','value'=>$decision ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            array_shift($result);
        return $result;
    }

}