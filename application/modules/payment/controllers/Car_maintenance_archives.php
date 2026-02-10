<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/03/22
 * Time: 08:10 ص
 */
class Car_maintenance_archives extends MY_Controller {

    var $MODEL_NAME= 'Car_maintenance_archives_model';
    var $PAGE_URL= 'payment/Car_maintenance_archives/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'FLEET_PKG';

        $this->maintain_no = $this->input->post('maintain_no');
        $this->car_id = $this->input->post('car_id');
        $this->req_start_date = $this->input->post('req_start_date');
        $this->req_end_date = $this->input->post('req_end_date');
        $this->branch_id = $this->input->post('branch_id');
        $this->entry_user = $this->input->post('entry_user');

    }

    function index()
    {
        $data['content']='car_maintenance_archives_index';
        $data['title']='أرشيف طلبات صيانة السيارات';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['entry_user_all'] = $this->get_entry_users('CAR_MAINTENANCE_REQUEST_TB');
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }


    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= " where 1=1 ";
        $where_sql.= ($this->maintain_no!= null)? " and M.MAINTAIN_NO= '{$this->maintain_no}' " : '';
        $where_sql.= ($this->car_id!= null)? " and M.CAR_ID= '{$this->car_id}' " : '';
        $where_sql.= ($this->branch_id!= null)? " and M.BRAN= '{$this->branch_id}' " : '';
        $where_sql.= ($this->entry_user!= null)? " and M.EMP_NO= '{$this->entry_user}' " : '';
        $where_sql.= ($this->req_start_date!= null or $this->req_end_date!= null)? " and TRUNC(M.DATE_ORDER) between nvl('{$this->req_start_date}','01/01/1000') and nvl('{$this->req_end_date}','01/01/3000') " : '';


        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('FIXED.MASTER_CAR_MAINTAIN  M'.$where_sql);
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
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('car_maintenance_archives_page',$data);

    }
    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('root/Rmodel');

        $data['branches']= $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( '', 'hr_admin' );
        $data['entry_user_all'] = $this->get_entry_users('ASSIGNING_WORK_TB');
        $data['car_owner'] = $this->Rmodel->getAll('FLEET_PKG', 'CARS_OWNER_LIST');
        $data['car_class'] = $this->constant_details_model->get_list(43);
        $data['adopt_cons'] = $this->constant_details_model->get_list(418);
        $data['car_ownership_list'] = $this->constant_details_model->get_list(272);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

}