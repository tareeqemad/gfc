<?php
/**
 * Created by PhpStorm.
 * User: fmamluk - mkilani
 * Date: 23/08/21
 * Time: 12:50 م
 */


class Assigning_work_car extends MY_Controller{

    var $MODEL_NAME= 'assigning_work_model';
    var $PAGE_URL= 'hr_attendance/Assigning_work_car/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'fleet_pkg';

        $this->ser= $this->input->post('ser');
        $this->emp_no= $this->input->post('emp_no');
        $this->ass_start_date= $this->input->post('ass_start_date');
        $this->ass_end_date= $this->input->post('ass_end_date');
        $this->adopt= $this->input->post('adopt');
        $this->entry_user= $this->input->post('entry_user');
        $this->branch_id= $this->input->post('branch_id');
        $this->car_adopt= $this->input->post('car_adopt');
        $this->cancel_reason= $this->input->post('cancel_reason');
        $this->emp_department= $this->input->post('emp_department');



        if( HaveAccess(base_url("hr_attendance/assigning_work_car/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;

    }

    function index(){
        $data['title']=' تكاليف بحاجة الى سيارة';
        $data['content']='assigning_work_car_index';
        $data['entry_user_all'] = $this->get_entry_users('ASSIGNING_WORK_TB');
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $this->ser= $this->check_vars($this->ser,'ser');
        $this->emp_no= $this->check_vars($this->emp_no,'emp_no');
        $this->ass_start_date= $this->check_vars($this->ass_start_date,'ass_start_date');
        $this->ass_end_date= $this->check_vars($this->ass_end_date,'ass_end_date');
        $this->adopt= $this->check_vars($this->adopt,'adopt');
        $this->entry_user= $this->check_vars($this->entry_user,'entry_user');
        $this->branch_id= $this->check_vars($this->branch_id,'branch_id');
        $this->car_adopt= $this->check_vars($this->car_adopt,'car_adopt');
        $this->emp_department= $this->check_vars($this->emp_department,'emp_department');

        $where_sql= " where 1=1 ";
        $where_sql.= " and car_request= 1 and ( adopt >= 10 or adopt = 0) ";
        if(!$this->all_branches)
            $where_sql.= " and branch_id= {$this->user->branch} ";

        $where_sql.= ($this->ser!= null)? " and ser= '{$this->ser}' " : '';
        $where_sql.= ($this->emp_no!= null)? " and emp_no= '{$this->emp_no}' " : '';
        $where_sql.= ($this->adopt!= null)? " and adopt= '{$this->adopt}' " : '';
        $where_sql.= ($this->entry_user!= null)? " and entry_user= '{$this->entry_user}' " : '';
        $where_sql.= ($this->branch_id!= null)? " and branch_id= '{$this->branch_id}' " : '';
        $where_sql.= ($this->car_adopt!= null)? " and car_adopt= '{$this->car_adopt}' " : '';
        $where_sql.= ($this->ass_start_date!= null or $this->ass_end_date!= null)? " and TRUNC(ass_start_time) between nvl('{$this->ass_start_date}','01/01/1000') and nvl('{$this->ass_end_date}','01/01/3000') " : '';
        $where_sql.= ($this->emp_department!= null)? " and emp_department= '{$this->emp_department}' " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' ASSIGNING_WORK_TB '.$where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );

        $this->load->view('assigning_work_car_page',$data);

    }

    function check_vars($var, $c_var){
        if($c_var=='adopt')
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        $var= $var == -1 ?null:$var;
        return $var;
    }

    private function car_adopt($case){

        $res = $this->{$this->MODEL_NAME}->car_adopt($this->ser, $case ,$this->cancel_reason);

        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        return 1;
    }

    function car_adopt_0(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->car_adopt(0);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function car_adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->car_adopt(10);
        }else
            echo "لم يتم ارسال رقم السند";
    }

    function _look_ups(&$data){

        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('salary/constants_sal_model');
        $this->load->model('hr_attendance_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['adopt_cons'] = $this->constant_details_model->get_list(222);

        $data['task_type'] = $this->constant_details_model->get_list(383);
        $data['governorate'] = $this->constant_details_model->get_list(339);
        $data['destination_type'] = $this->constant_details_model->get_list(384);
        $data['car_adopt_cons'] = $this->constant_details_model->get_list(385);
        $data['cancel_reason'] = $this->constant_details_model->get_list(388);

        $data['emp_department'] = $this->constants_sal_model->get_list(7);
        $data['car_type'] = $this->constant_details_model->get_list(386);
       // constants_sal_det_get_name

        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( '', 'hr_admin' );

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }


    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['content']='assigning_work_car_show';
        $data['hidden']= 'hidden';
        $data['checked']= '';
        $data['title']='بيانات التكليف ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function reports (){

        $data['content']='reports_cars';
        $data['title']='تقارير ادارة المركبات';
        $data['emp_branch_selected'] = $this->user->branch;
        $data['driver'] = $this->rmodel->getData('CAR_GPS_TRACHING_DRIVER_NAME');
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

}