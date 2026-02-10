<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Archive_scan extends MY_Controller
{
    var $PAGE_URL = 'emp_archive/Archive_scan/get_page';

    var $PACKAGE_NAME = 'ARCH_FILE';

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = $this->PACKAGE_NAME;

        $this->ser = $this->input->post('ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->barcode = $this->input->post('barcode');
        $this->scan_type = $this->input->post('scan_type');
        $this->version_no = $this->input->post('version_no');
        $this->folder_name_s = $this->input->post('folder_name_s');
        $this->from_month = $this->input->post('from_month');
        $this->to_month = $this->input->post('to_month');
    }

    function index($page = 1)
    {
        $data['title'] = 'الأرشفة الالكترونية';
        $data['content'] = 'archive_scan_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');

        $where_sql = " where 1=1 ";


        $where_sql .= ($this->p_emp_no != null) ? " AND M.EMP_NO = '{$this->p_emp_no}' " : '';
        $where_sql .= ($this->p_barcode != null) ? " AND  LOWER(M.BARCODE) LIKE LOWER('%{$this->p_barcode}%')  " : "";
        $where_sql .= ($this->p_source_entry != null) ? " AND M.SOURCE_ENTRY = '{$this->p_source_entry}' " : '';
        $where_sql .= ($this->p_from_month != null) ? " AND M.MONTH >= '{$this->p_from_month}' " : '';
        $where_sql .= ($this->p_to_month != null) ? " AND M.MONTH <= '{$this->p_to_month}' " : '';
        $where_sql .= ($this->p_folder_name_s != null) ? " AND M.FOLDER_NAME LIKE '%{$this->p_folder_name_s}%' " : "";
        $where_sql .= ($this->p_entry_user_scan != null) ? " AND M.ENTRY_USER_SCAN LIKE '%{$this->p_entry_user_scan}%' " : "";
        /*echo $where_sql;*/
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' SCAN_HR_TB M ' . $where_sql);
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

        $data['page_rows'] = $this->rmodel->getList('SCAN_HR_TB_LIST', $where_sql, $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('archive_scan_page', $data);

    }

    function check_vars($var, $c_var)
    {
        // if post take it, else take the parameter
        $var = ($this->{$c_var}) ? $this->{$c_var} : $var;
        // if val is -1 then null, else take the val
        $var = $var == -1 ? null : $var;
        return $var;
    }

    function _look_ups(&$data)
    {
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child(NULL, 'hr_admin');
        $data['scan_type_arr'] = $this->rmodel->getID('ARCH_FILE', 'SCAN_TYPE_CHILD_GET', 0);
    }

    function public_show_scan_files()
    {
        $ser = $this->input->post('ser');
        if ($ser) {
            $data['master_tb_data'] = $this->rmodel->getID('ARCH_FILE', 'SCAN_HR_TB_GET', $ser);
            $data['array_folder'] = $this->rmodel->getID('ARCH_FILE', 'SCAN_HR_DET_TB_GET', $ser);
            $this->load->view('archive_details_files', $data);
        } else {
            echo 'يوجد خطأ';
        }
    }

    function public_get_folder_name()
    {
        $emp_no = $this->input->post('emp_no');
        $barcode = $this->input->post('barcode');
        $data['data_folder'] = $this->rmodel->getTwoColum('ARCH_FILE', 'SCAN_BARCODE_HR_FILE_TB_GET', $emp_no, $barcode);
        $data['emp_no'] = $emp_no;
        $data['barcode'] = $barcode;
        if (count($data['data_folder']) == 0) {
            $data['action'] = '1';
            /****insert***/
        } else {
            $data['action'] = '2';
            /****update***/
        }
        $this->load->view('archive/archive_scan_name_folder', $data);

    }

    function edit_folder_name()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $no = $this->input->post('no');
            $barcode = $this->input->post('barcode');
            $folder_name = $this->input->post('folder_name');
            $notes = $this->input->post('notes');
            $month_des = $this->input->post('month_des');
            $h_action = $this->input->post('h_action');
            $data_arr = array(
                array('name' => 'EMP_NO_IN', 'value' => $no, 'type' => '', 'length' => -1),
                array('name' => 'BARCODE_IN', 'value' => $barcode, 'type' => '', 'length' => -1),
                array('name' => 'FOLDER_NAME_IN', 'value' => $folder_name, 'type' => '', 'length' => -1),
                array('name' => 'NOTES_IN', 'value' => $notes, 'type' => '', 'length' => -1),
                array('name' => 'MONTH_DES_IN', 'value' => $month_des, 'type' => '', 'length' => -1),
            );
            if ($h_action == 1) {
                $res = $this->rmodel->insert('SCAN_BARCODE_HR_FILE_TB_INSERT', $data_arr);
            } else if ($h_action == 2) {
                $res = $this->rmodel->update('SCAN_BARCODE_HR_FILE_TB_UPDATE', $data_arr);
            } else {
                $res = 0;
            }
            if (intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }

    function public_get_edit_detail()
    {
        $barcode = $this->input->post('barcode');
        $data['barcode'] = $barcode;
        $data['rertMain'] = $this->rmodel->get('SCAN_BARCODE_GET1', $barcode);
        $this->load->view('archive/edit_detail_file', $data);

    }


    function public_update_detail()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $barcode = $this->input->post('barcode');
            $scan_type = $this->input->post('scan_type');
            $version_no = $this->input->post('version_no');
            $data_arr = array(
                array('name' => 'BARCODE_IN', 'value' => $barcode, 'type' => '', 'length' => -1),
                array('name' => 'TYPE_IN', 'value' => $scan_type, 'type' => '', 'length' => -1),
                array('name' => 'VERSION_NO_IN', 'value' => $version_no, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('SCAN_BARCODE_UPDATE', $data_arr);
            if (intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }


    function delete_row()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->rmodel->delete('SCAN_BARCODE_TB_DELETE', $this->p_barcode);
        }
    }


}