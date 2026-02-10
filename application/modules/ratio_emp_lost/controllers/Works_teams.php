<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 20/02/23
 * Time: 13:40
 */
class Works_teams extends MY_Controller {

    var $MODEL_NAME= 'Works_teams_model';
    var $PAGE_URL= 'ratio_emp_lost/Works_teams/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('Root/New_rmodel');

        $this->ser = $this->input->post('ser');
        $this->target = $this->input->post('target');
        $this->team = $this->input->post('team');
        $this->subscriber_no = $this->input->post('subscriber_no');
        $this->the_date = $this->input->post('the_date');
        $this->from_date = $this->input->post('from_date');
        $this->to_date = $this->input->post('to_date');

        $this->branch_no = $this->input->post('branch_no');
        $this->the_month = $this->input->post('the_month');
        $this->activity_no = $this->input->post('activity_no');


    }

    function index()
    {
        $data['content']='works_teams_index';
        $data['title']='أعمال الفرق';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";

        $where_sql.= ($this->target!= null)? " and M.TARGET= '{$this->target}' " : '';
        $where_sql.= ($this->team!= null)? "and M.TEAM = '{$this->team}' " : '';
        $where_sql.= ($this->branch_no!= null)? "and M.BRANCH_NO = '{$this->branch_no}' " : '';
        $where_sql.= ($this->the_month!= null)? "and M.THE_MONTH = '{$this->the_month}' " : '';
        $where_sql.= ($this->activity_no!= null)? "and M.ACTIVITY_NO = '{$this->activity_no}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' WORKS_TEAMS_TB  M '.$where_sql);
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
        $this->load->view('works_teams_page',$data);

    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);

            $this->target_no= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));

            if(intval($this->target_no) <= 0){
                $this->print_error($this->target_no);
            }else{
                echo intval($this->target_no);
            }
        }
        $data['content']='works_teams_show';
        $data['title']='اضافة أعمال الفرق';
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
        $data['content']='works_teams_show';
        $data['title']='بيانات اعمال الفرق ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
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
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _post_validation($isEdit = false){

        if( $this->target == ''){
            $this->print_error('يجب اختيار المستهدف');
        }else if( $this->team == '' ){
            $this->print_error('يجب اختيار الفريق');
        }else if( $this->the_date == '' ){
            $this->print_error('يجب ادخال التاريخ');
        }
    }

    function _look_ups(&$data){
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model');

        $data['activity_no'] = $this->constant_details_model->get_list(492);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['target'] = $this->{$this->MODEL_NAME}->get_all();
        $data['get_teams'] = $this->{$this->MODEL_NAME}->get_teams();
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){

        $result = array(
            array('name'=>'SER','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'TARGET','value'=> $this->target ,'type'=>'','length'=>-1),
            array('name'=>'TEAM','value'=> $this->team ,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIBER_NO','value'=> $this->subscriber_no ,'type'=>'','length'=>-1),
            array('name'=>'THE_DATE','value'=> $this->the_date ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            unset($result[0]);
        return $result;
    }

}