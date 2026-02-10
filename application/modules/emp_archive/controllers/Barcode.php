<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Barcode extends MY_Controller
{

    var $PACKAGE_NAME = 'ARCH_FILE';

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = $this->PACKAGE_NAME;

    }



    function managment_barcode_index($page = 1)
    {
        $data['title'] = ' الباركود المولد للموظف';
        $data['content'] = 'barcode_manage_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function public_get_page_barcode_list($page = 1)
    {
        $this->load->library('pagination');

        $where_sql = 'where 1 = 1';

        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.EMP_NO  ={$this->p_emp_no}  " : "";
        $where_sql .= isset($this->p_barcode) && $this->p_barcode != null ? " AND  LOWER(M.BARCODE) LIKE LOWER('%{$this->p_barcode}%')  " : "";
        $where_sql .= isset($this->p_type_no) && $this->p_type_no != null ? " AND  M.TYPE_NO  ={$this->p_type_no}  " : "";


        $config['base_url'] = base_url('emp_archive/Barcode/public_get_page_barcode_list');
        $count_rs = $this->get_table_count(" BARCODE_EMP_TB M  $where_sql ");
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = 200;
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
        $data['page_rows'] = $this->rmodel->getList('BARCODE_EMP_TB_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('barcode_page', $data);
    }

    function generate_barcode_index()
    {
        $data['title'] = 'توليد باركود';
        $data['content'] = 'barcode_index';
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function public_barcode_detail_grand()
    {
        $type_no = $this->input->post('type_no');
        $emp_no = $this->input->post('emp_no');
        $data['result_grand'] = $this->rmodel->getTwoColum($this->PACKAGE_NAME, 'SCAN_TYPE_CHILD_EMP_GET', $type_no, $emp_no);
        $data['emp_no'] = $emp_no;
        $this->_look_ups($data);
        $this->load->view('barcode_detail_grand', $data);

    }

    function public_barcode_detail_parent()
    {
        $type_no = $this->input->post('type_no');
        $emp_no = $this->input->post('emp_no');
        $data['result_parent'] = $this->rmodel->getTwoColum($this->PACKAGE_NAME, 'SCAN_TYPE_CHILD_EMP_GET', $type_no, $emp_no);
        $data['emp_no'] = $emp_no;
        $this->_look_ups($data);
        $this->load->view('barcode_detail_parent', $data);

    }

    function public_barcode_detail_son()
    {
        $type_no = $this->input->post('type_no');
        $emp_no = $this->input->post('emp_no');
        $data['result_son'] = $this->rmodel->getTwoColum($this->PACKAGE_NAME, 'SCAN_TYPE_CHILD_EMP_GET', $type_no, $emp_no);
        $data['emp_no'] = $emp_no;
        $this->_look_ups($data);
        $this->load->view('barcode_detail_son', $data);

    }

    function public_generate_barcode()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $emp_no = $this->input->post('emp_no');
            $type_no = $this->input->post('type_no');
            $ccount = $this->input->post('ccount');
            $data_arr = array(
                array('name' => 'EMP_NO_IN', 'value' => $emp_no, 'type' => '', 'length' => -1),
                array('name' => 'TYPE_NO_IN', 'value' => $type_no, 'type' => '', 'length' => -1),
                array('name' => 'CCOUNT_IN', 'value' => $ccount, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->insert('BARCODE_EMP_TB_INSERT', $data_arr);
            if (intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }


    function _look_ups(&$data)
    {
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child(NULL, 'hr_admin');
        $data['scan_type_arr'] = $this->rmodel->get('SCAN_TYPE_CHILD_GET', 0);
    }

    function public_get_details($package, $procd, $id = 0)
    {
        header("Content-type: application/json");
        $ret = $this->rmodel->getID($package, $procd, $id);
        echo json_encode($ret);
    }
}