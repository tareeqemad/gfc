<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 04/08/19
 * Time: 09:40 ص
 */

class Carrequest extends MY_Controller
{

    var $PKG_NAME = "HR_ATTENDANCE_PKG";
    var $MODEL_NAME= 'Carrequest';

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/Rmodel');
        $this->Rmodel->package = 'HR_ATTENDANCE_PKG';
        //this for constant using
        $this->load->model('customer_elect_details_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');

        $this->order_no= $this->input->post('order_no');
        $this->car_id= $this->input->post('car_id');
        $this->driver_id= $this->input->post('driver_id');
        $this->movment_type= $this->input->post('movment_type');
        $this->notes= $this->input->post('notes');
        $this->car_owner= $this->input->post('car_owner');
        $this->car_type= $this->input->post('car_type');
        $this->office_id= $this->input->post('office_id');
        $this->task_cost= $this->input->post('task_cost');
        $this->emp_name= $this->input->post('emp_name');
        $this->status= $this->input->post('status');
        $this->car_type= $this->input->post('car_type');
        $this->date_move= $this->input->post('date_move');

        $this->ser= $this->input->post('ser');
        $this->emp_no= $this->input->post('emp_no');
        $this->branch_id= $this->input->post('branch_id');
        $this->ass_start_date= $this->input->post('ass_start_date');
        $this->ass_end_date= $this->input->post('ass_end_date');
        $this->h_emp_no= $this->input->post('h_emp_no');

        if( HaveAccess(base_url("hr_attendance/assigning_work_car/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;

    }

    function index($page = 1)
    {
        $data['title'] = 'طلبات حجز السيارات';
        $data['content'] = 'carrequest_show';
        $data['page'] = $page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->model('hr_attendance/hr_attendance_model');

        $data['car_drive_name_company'] = json_encode($this->Rmodel->getAll('FLEET_PKG', 'CAR_DRIVE_NAME_COMPANY_LIST'));
        $data['car_drive_name_rented'] = json_encode($this->Rmodel->getAll('FLEET_PKG', 'CAR_DRIVE_NAME_RENTED_LIST'));
        $data['car_owner'] = $this->Rmodel->getAll('FLEET_PKG', 'CARS_OWNER_LIST');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['movement_type'] = $this->constant_details_model->get_list(269);
        $data['car_type'] = $this->constant_details_model->get_list(386);
        $data['office_name'] = $this->constant_details_model->get_list(387);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( '', 'hr_admin' );

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }


    function get_list($page=1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("payment/carrequest/get_list/");

        $where_sql= " where 1=1 ";
        if(!$this->all_branches)
            $where_sql.= " and branch_id= {$this->user->branch} ";
        $where_sql.= " and car_adopt = 10 ";
        $count_rs = $this->get_table_count(" ASSIGNING_WORK_TB M  {$where_sql}");

        $where_sql.= ($this->ser!= null)? " and ser= '{$this->ser}' " : '';
        $where_sql.= ($this->emp_no!= null)? " and emp_no= '{$this->emp_no}' " : '';
        $where_sql.= ($this->branch_id!= null)? " and branch_id= '{$this->branch_id}' " : '';
        $where_sql.= ($this->ass_start_date!= null or $this->ass_end_date!= null)? " and TRUNC(ass_start_time) between nvl('{$this->ass_start_date}','01/01/1000') and nvl('{$this->ass_end_date}','01/01/3000') " : '';

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
        $data["rows"] = $this->Rmodel->getList('ASSIGNING_WORK_TB_LIST', " $where_sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('carrequest_page', $data);

    }

    function public_select_driver_company($txt = null)
    {
        $this->Rmodel->package = 'FLEET_PKG';
        $data['title'] = 'فهرس السائقين';
        $data['content'] = 'driver_select_view';
        $offset = 0;
        $row = 2000;
        $result = $this->Rmodel->getList('CAR_DRIVE_NAME_LIST', " AND EMP_TYPE != 20 ", $offset, $row);
        $data['rowsw'] = $result;
        $data['txt'] = $txt;
        $this->load->view('template/view', $data);
    }

    function public_select_driver($txt = null)
    {
        $this->Rmodel->package = 'FLEET_PKG';
        $data['title'] = 'فهرس السائقين';
        $data['content'] = 'driver_select_view';
        $offset = 0;
        $row = 2000;
        $result = $this->Rmodel->getList('CAR_DRIVE_NAME_LIST', " AND EMP_TYPE = 20 ", $offset, $row);
        $data['rows'] = $result;
        $data['txt'] = $txt;
        $this->load->view('template/view', $data);
    }


    function insert() {

        $params = array(
            array('name' => 'CAR_ID', 'value' => $this->car_id, 'type' => '', 'length' => -1),
            array('name' => 'DRIVER_ID', 'value' => $this->driver_id, 'type' => '', 'length' => -1),
            array('name' => 'MOVMENT_TYPE', 'value' => $this->movment_type, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->notes, 'type' => '', 'length' => -1),
            array('name' => 'THE_DATE', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'CAR_OWNER', 'value' => $this->car_owner, 'type' => '', 'length' => -1),
            array('name' => 'ORDER_NO', 'value' => $this->order_no, 'type' => '', 'length' => -1),
            array('name' => 'CAR_TYPE', 'value' => $this->car_type, 'type' => '', 'length' => -1),
            array('name' => 'OFFICE_ID', 'value' =>$this->office_id, 'type' => '', 'length' => -1),
            array('name' => 'TASK_COST', 'value' => $this->task_cost, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NAME', 'value' => $this->emp_name, 'type' => '', 'length' => -1),
            array('name' => 'DATE_MOVE', 'value' => $this->date_move, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $this->h_emp_no, 'type' => '', 'length' => -1),
        );

        $this->Rmodel->package = 'FLEET_PKG';
        $this->ser = $this->Rmodel->insert('CARS_MOVMENTS_TB_INSERT', $params);


        if ( intval($this->ser) < 1) {
            $this->print_error('لم يتم الحفظ' . '<br>'.$this->ser);
        } else {
            echo intval($this->ser);
        }

    }

}
