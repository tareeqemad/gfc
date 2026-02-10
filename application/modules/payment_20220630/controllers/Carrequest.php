<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 04/08/19
 * Time: 09:40 ص
 */


class CarRequest extends MY_Controller
{

    var $PKG_NAME = "HR_ATTENDANCE_PKG";

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'HR_ATTENDANCE_PKG';
        //this for constant using
        $this->load->model('customer_elect_details_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
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
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');


        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['movement_type'] = $this->constant_details_model->get_list(269);

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }


    function get_list($page)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("payment/carrequest/get_list/");


        $sql = "WHERE CAR_REQUEST=1 and  ASSIGN_DRIVER = 0 and adopt < 40 AND M.ASS_START_TIME >= '". date('d/m/Y')."'";

        $count_rs = $this->get_table_count(" ASSIGNING_WORK_TB M  {$sql}");


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
        $data["rows"] = $this->rmodel->getList('ASSIGNING_WORK_TB_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('carrequest_page', $data);

    }



    function update()
    {


    /*        $this->rmodel->package = 'FLEET_PKG';
            $this->ser = $this->rmodel->insert('CARS_MOVMENTS_TB_INSERT', $this->_postedData());

            $this->rmodel->package = 'HR_ATTENDANCE_PKG';
            $id = $this->input->post('assign_work_no');

            $params = array(
                array('name' => 'SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
                array('name' => 'CAR_MOV_ID_IN', 'value' => $this->ser, 'type' => '', 'length' => -1),
            );



             $msg= $this->rmodel->update('ASSIGNING_WORK_TB_UPDATE_REQ',$params);


            if ($msg < 1) {
              $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
               echo intval($msg);
            }
*/


        $id = $this->input->post('assign_work_no');

        $params = array(
            array('name' => 'SER_IN', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'CAR_ID', 'value' => $this->p_car_id, 'type' => '', 'length' => -1),
            array('name' => 'DRIVER_ID', 'value' => $this->p_driver_id, 'type' => '', 'length' => -1),
            array('name' => 'MOVMENT_TYPE', 'value' => $this->p_movment_type, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'CAR_OWNER', 'value' => $this->p_car_owner, 'type' => '', 'length' => -1),
        );

        $msg= $this->rmodel->update('ASSIGNING_WORK_TB_UPDATE_REQ',$params);
        if ($msg < 1) {
            $this->print_error('لم يتم الحفظ' . '<br>');
        } else {
            echo intval($msg);
        }



    }






}
