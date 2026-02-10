<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User: mkilani
 * Date: 10/10/21
 */
class Archive_scan extends MY_Controller
{
    var $PAGE_URL = 'hr/Archive_scan/get_page';

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

        $emp_no = $this->check_vars($this->emp_no, 'emp_no');
        $barcode = $this->check_vars($this->barcode, 'barcode');
        $scan_type_grand = $this->check_vars($this->scan_type_grand, 'scan_type_grand');
        $scan_type_parent = $this->check_vars($this->scan_type_parent, 'scan_type_parent');
        $scan_type_son = $this->check_vars($this->scan_type_son, 'scan_type_son');
        $version_no = $this->check_vars($this->version_no, 'version_no');
        $folder_name_s = $this->check_vars($this->folder_name_s, 'folder_name_s');
        $from_month = $this->check_vars($this->from_month, 'from_month');
        $to_month = $this->check_vars($this->to_month, 'to_month');

        if ($emp_no == null and $scan_type_grand == null) {
            $where_sql = " where 1=2 ";
        } else {
            $where_sql = " where 1=1 ";
        }

        $where_sql .= ($emp_no != null) ? " AND M.EMP_NO = '{$emp_no}' " : '';
        $where_sql .= ($barcode != null) ? " AND  LOWER(M.BARCODE) LIKE LOWER('%{$barcode}%')  " : "";
        $where_sql .= ($scan_type_grand != null) ? " AND M.TYPE = '{$scan_type_grand}' " : '';
        $where_sql .= ($version_no != null) ? " AND M.VERSION_NO = '{$version_no}' " : '';
        $where_sql .= ($folder_name_s != null) ? " AND ARCH_FILE.FOLDER_NAME_GET_NAME(M.EMP_NO,M.BARCODE)  LIKE '%{$folder_name_s}%' " : "";
        $where_sql .= ($from_month != null) ? " AND   ARCH_FILE.FOLDER_MONTH_DES_GET(M.EMP_NO,M.BARCODE) >= '{$from_month}'  " : "";
        $where_sql .= ($to_month != null) ? " AND  ARCH_FILE.FOLDER_MONTH_DES_GET(M.EMP_NO,M.BARCODE) <= '{$to_month}'  " : "";
        $where_sql .= " AND M.BARCODE != 'undefined' ";
        /*echo $where_sql;*/
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' SCAN_BARCODE M ' . $where_sql);
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

        $data['page_rows'] = $this->rmodel->getList('SCAN_BARCODE_GROUP_LIST', $where_sql, $offset, $row);

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
        $barcode = $this->input->post('barcode');
        if ($barcode) {
            $data['array_folder'] = $this->rmodel->getID('ARCH_FILE', 'SCAN_BARCODE_FILE_GET', $barcode);
            $this->load->view('archive/archive_scan_files', $data);
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

    /******************Scan Type**Tree************************/
    function index_Tree(){
        $this->load->helper('generate_list');

        $data['title'] = 'شجرة التصنيفات';
        $data['content'] = 'scan_tree_index';
        $resource =  $this->_get_structure(0);
        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );
        $template = '<li ><span data-id="{TYPE_NO}" data-no="{TYPE_NO}"  data-title="{TYPE_NAME}" data-codetype="{TYPE_CODE}"  ondblclick="javascript:get_type();"><i class="fa fa-minus"></i> <input type="checkbox" class="checkboxes" value="{TYPE_NO}" />{TYPE_CODE} : {TYPE_NAME}  </span>{SUBS}</li>';
        $data['tree'] = '<ul class="tree" id="Tree_Type">'.generate_list($resource, $options, $template).'</ul>';
        $this->load->view('template/template1', $data);
    }

    function public_get_scan_type(){
        $result= $this->rmodel->get('SCAN_TYPE_GET', $this->p_type_no);
        $this->return_json($result[0]);
    }
    function  create_scan_type(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type_name = $this->input->post('type_name');
            $parent_no = $this->input->post('parent_no');
            $level_no = $this->input->post('level_no');
            $data_arr = array(
                array('name' => 'TYPE_NAME_IN', 'value' => $type_name, 'type' => '', 'length' => -1),
                array('name' => 'TYPE_NO_PARENT_IN', 'value' => $parent_no, 'type' => '', 'length' => -1),
                array('name' => 'LEVEL_NO_IN', 'value' => $level_no, 'type' => '', 'length' => -1),
            );
            $result = $this->rmodel->insert('SCAN_TYPE_INSERT', $data_arr);
            $this->Is_success($result);
            $this->return_json($result);
        }
    }


    function  update_scan_type(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type_no = $this->input->post('type_no_up');
            $type_code = $this->input->post('type_code_up');
            $type_name = $this->input->post('type_name_up');
            $data_arr = array(
                array('name' => 'TYPE_NO_IN', 'value' => $type_no, 'type' => '', 'length' => -1),
                array('name' => 'TYPE_CODE_IN', 'value' => $type_code, 'type' => '', 'length' => -1),
                array('name' => 'TYPE_NAME_IN', 'value' => $type_name, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('SCAN_TYPE_UPDATE', $data_arr);
            if (intval($res) >= 1) {
                echo $res;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }
    function _get_structure($parent= -1) {
        $result = $this->rmodel->getID($this->PACKAGE_NAME, 'SCAN_TYPE_CHILD_GET', $parent);
        $i = 0;
        foreach($result as $key => $item)
        {
            $result[$i]['subs'] = $this->_get_structure($item['TYPE_NO']);
            $i++;
        }
        return $result;
    }
    /*****************************************************/

    function generate_barcode_index()
    {
        $data['title'] = 'توليد باركود';
        $data['content'] = 'barcode_index';
        $this->_look_ups($data);
        $data['scan_type_arr'] = $this->rmodel->get('SCAN_TYPE_CHILD_GET', 0);
        $this->load->view('template/template1', $data);
    }

    function public_barcode_detail_grand(){
        $type_no = $this->input->post('type_no');
        $emp_no = $this->input->post('emp_no');
        $data['result_grand'] = $this->rmodel->getTwoColum($this->PACKAGE_NAME, 'SCAN_TYPE_CHILD_EMP_GET', $type_no,$emp_no);
        $data['emp_no'] = $emp_no;
        $this->_look_ups($data);
        $this->load->view('archive/barcode_detail_grand', $data);

    }
    function public_barcode_detail_parent(){
        $type_no = $this->input->post('type_no');
        $emp_no = $this->input->post('emp_no');
        $data['result_parent'] = $this->rmodel->getTwoColum($this->PACKAGE_NAME, 'SCAN_TYPE_CHILD_EMP_GET', $type_no,$emp_no);
        $data['emp_no'] = $emp_no;
        $this->_look_ups($data);
        $this->load->view('archive/barcode_detail_parent', $data);

    }

    function public_barcode_detail_son(){
        $type_no = $this->input->post('type_no');
        $emp_no = $this->input->post('emp_no');
        $data['result_son'] = $this->rmodel->getTwoColum($this->PACKAGE_NAME, 'SCAN_TYPE_CHILD_EMP_GET', $type_no,$emp_no);
        $data['emp_no'] = $emp_no;
        $this->_look_ups($data);
        $this->load->view('archive/barcode_detail_son', $data);

    }

    function public_generate_barcode(){
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


    function managment_barcode_index($page = 1){
        $data['title'] = 'ادارة الباركود';
        $data['content'] = 'barcode_manage_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function public_get_page_barcode_list($page = 1){
        $this->load->library('pagination');

        $where_sql = 'where 1 = 1';

        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no!= null ? " AND  M.EMP_NO  ={$this->p_emp_no}  " : "";
        $where_sql .= isset($this->p_barcode) && $this->p_barcode!= null ? " AND  LOWER(M.BARCODE) LIKE LOWER('%{$this->p_barcode}%')  " : "";
        $where_sql .= isset($this->p_type_no) && $this->p_type_no!= null ? " AND  M.TYPE_NO  ={$this->p_type_no}  " : "";


        $config['base_url'] = base_url('hr/Archive_scan/public_get_page_barcode_list');
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

    function public_get_details($package, $procd, $id = 0)
    {
        header("Content-type: application/json");
        $ret = $this->rmodel->getID($package, $procd, $id);
        echo json_encode($ret);
    }
}
