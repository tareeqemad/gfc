<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 27/07/2019
 * Time: 10:29 ص
 */

class maintenance extends MY_Controller
{
    var $MODEL_NAME = 'maintenance_model';
    var $DETAILS_MODEL_NAME = 'maintenance_det_model';
    var $PAGE_URL = 'maintenance/maintenance/get_page';
    var $PAGE_ACT;

    //var $PAGE_URL= 'maintenance/maintenance/get_page';


    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('root/rmodel');

        $this->rmodel->package = 'MAINTENANCE_PKG';
        $this->load->model('Root/New_rmodel');

        // vars
        $this->ser = $this->input->post('ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->service_type = $this->input->post('service_type');
        $this->service_property = $this->input->post('service_property');
        $this->note_problem = $this->input->post('desc_problem');
        $this->entry_user = $this->input->post('entry_user');
        $this->branch_id = $this->input->post('branch_id');
        $this->class_id = $this->input->post('class_id');

        //////////////////////////////////////////////////////
        $this->entry_date = $this->input->post('entry_date');
        $this->end_entry_date = $this->input->post('end_entry_date');
        $this->status = $this->input->post('status');
    }

    function index($page = 1)
    {
        $data['content'] = 'maintenance_index';
        $data['entry_user_all'] = $this->get_entry_users('maintenance_req_tb');
        $data['page'] = $page;
        $data['title'] = 'طلبات الصيانة';
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');

        $where_sql = " WHERE 1 = 1 ";
        $where_sql .= isset($this->p_branch) && $this->p_branch != null ? " AND  M.BRANCH= '{$this->p_branch}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.EMP_NO= '{$this->p_emp_no}'  " : "";
        $where_sql .= isset($this->p_ser) && $this->p_ser != null ? " AND  M.SER= '{$this->p_ser}'  " : "";
        $where_sql .= isset($this->p_entry_user) && $this->p_entry_user != null ? " AND  M.ENTRY_USER= '{$this->p_entry_user}'  " : "";
        $where_sql .= isset($this->p_from_entry_date) && $this->p_from_entry_date != null ? " AND  TO_DATE(M.ENTRY_DATE) >= '{$this->p_from_entry_date}'  " : "";
        $where_sql .= isset($this->p_to_entry_date) && $this->p_to_entry_date != null ? " AND  TO_DATE(M.ENTRY_DATE) <= '{$this->p_to_entry_date}'  " : "";
        $where_sql .= isset($this->p_status) && $this->p_status != null ? " AND  M.STATUS= '{$this->p_status}'  " : "";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' MAINTENANCE_REQ_TB M ' . $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['lastatus_class_id_sst_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data["page_rows"] = $this->rmodel->getList('MAINTENANCE_REQ_TB_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('maintenance_page', $data);
    }

    //FOR CREATE REQUEST MAINTENANCE انشاء الطلب
    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $data_arr = array(
                array('name' => 'EMP_NO', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
                array('name' => 'SERVICE_TYPE', 'value' => $this->service_type, 'type' => '', 'length' => -1),
                array('name' => 'SERVICE_PROPERTY', 'value' => $this->service_property, 'type' => '', 'length' => -1),
                array('name' => 'NOTE_PROBLEM', 'value' => $this->note_problem, 'type' => '', 'length' => -1),
            );
            $this->rmodel->package = 'MAINTENANCE_PKG';
            $res = $this->rmodel->insert('MAINTENANCE_REQ_TB_INSERT', $data_arr);
            if (intval($res) >= 1) {
                for ($i = 0; $i < count($this->class_id); $i++) {
                    if ($this->class_id[$i] != '') {
                        $data_det_arr = array(
                            array('name' => 'CLASS_ID', 'value' => $this->class_id[$i], 'type' => '', 'length' => -1),
                            array('name' => 'SER_REQ', 'value' => $res, 'type' => '', 'length' => -1)
                        );
                        $this->rmodel->package = 'MAINTENANCE_PKG';
                        $res_det = $this->rmodel->insert('MAINTENANCE_REQ_CLAS_TB_INSERT', $data_det_arr);
                    }
                }
                echo intval($res);
            } else {
                $this->print_error('Error_' . $res);
            }
        } else {
            $data['content'] = 'maintenance_show';
            $data['title'] = 'انشاء طلب صيانة';
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template1', $data);
        }
    }

    /////////////for get request maintenance///////////////////////////////////
    function get($id)
    {

        //GET DETAIL OF REQUEST BESIDE USER
        $rs = $this->{$this->MODEL_NAME}->get_req($id);
        $data['get_data'] = $rs;


        //FOR GET DATA CLASS_ID FOR REQUEST MAINTENANCE ID
        $rs_class_id = $this->{$this->DETAILS_MODEL_NAME}->get_class_id($id);
        $data['get_data_class'] = $rs_class_id;

        $data['content'] = 'maintenance_request_show';
        $data['title'] = ' بيانات طلب الصيانة';
        $data['isCreate'] = true;
        $data['action'] = 'edit';
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);

    }



    //function update_solve for each class_id من ناحية الصنف الواحد حل المشكلة
    public function addProcedure()
    {
        $ser_class_id = $this->input->post('ser_class_id');
        $class_id = $this->input->post('class_id');
        $comp_name = $this->input->post('comp_name');
        $cost_main = $this->input->post('cost_main');
        $solve_problem = $this->input->post('solve_problem');
        $status_class_id = $this->input->post('status_class_id');
        $msg = $this->{$this->DETAILS_MODEL_NAME}->update_det_classid($ser_class_id, $class_id, $comp_name, $cost_main, $solve_problem, $status_class_id);
        if (intval($msg) <= 0) {
            $this->print_error($msg);
        } else {
            echo "1";
        }
    }

    //CLOSED THE REQUEST BASED CHANGE STATUS
    public function AddFinalSolve()
    {
        $ser = $this->input->post('ser');
        $solve_problem1 = $this->input->post('solve_problem1');
        $status = $this->input->post('status');
        $msg = $this->{$this->MODEL_NAME}->AddFinalSolve($ser, $solve_problem1, $status);
        if (intval($msg) <= 0) {
            $this->print_error($msg);
        } else {
            echo "1";
        }
    }

    //انشاء وصل استلام
    public function insert_Receipt()
    {
        $ser = $this->input->post('ser');
        $receipt_user = $this->input->post('receipt_user');
        $receipt_date = $this->input->post('receipt_date');
        $receipt_note = $this->input->post('receipt_note');
        $msg = $this->{$this->MODEL_NAME}->insert_Receipt($ser, $receipt_user, $receipt_date, $receipt_note);
        if (intval($msg) <= 0) {
            $this->print_error($msg);
        } else {
            echo "1";
        }
    }

    //post data functions
    function _postedData($typ = null)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->ser, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
            array('name' => 'SERVICE_TYPE', 'value' => $this->service_type, 'type' => '', 'length' => -1),
            array('name' => 'SERVICE_PROPERTY', 'value' => $this->service_property, 'type' => '', 'length' => -1),
            array('name' => 'NOTE_PROBLEM', 'value' => $this->note_problem, 'type' => '', 'length' => -1),
        );
        if ($typ == 'create')
            unset($result[0]);
        return $result;
    }

    //get pledges by onchange function using json
    function public_get_pledges()
    {
        $emp_no = $this->input->post('emp_no') ? $this->input->post('emp_no') : 0;
        $data['rows'] = $this->rmodel->get('CUSTOMERS_PLEDGES_GET_B_EMP_NO', $emp_no);
        $this->load->view('pledges_data', $data);
    }

    function delete_class_id()
    {
        $id = $this->input->post('ser_class_id');
        //$this->IsAuthorized();
        if (is_array($id)) {
            foreach ($id as $val) {
                echo $this->{$this->DETAILS_MODEL_NAME}->delete_class_id($val);
            }
        } else {
            echo $this->{$this->DETAILS_MODEL_NAME}->delete_class_id($id);
        }
    }


    /*********************************************************/
    function adopt($case)
    {
        $res = $this->{$this->MODEL_NAME}->adopt($this->ser, $case);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        return 1;
    }

    /**********تحويل الطلب لقسم الدعم الفني***********************/
    function adopt_2()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser != '') {
            echo $this->adopt(2);
        } else
            echo "لم يتم الاعتماد";
    }


    function _post_validation($isEdit = false)
    {
        if ($this->emp_no == '' or $this->service_type == '' or $this->service_property == '' or $this->note_problem == '' /*or $this->class_id == ''*/) {
            $this->print_error('يجب ادخال جميع البيانات');
        }
    }


    function _look_ups(&$data)
    {

        $this->load->model('settings/users_model');
        $data['employee_arr'] = $this->users_model->get_all(" and USERS_PROG_TB.IS_ADMIN = 0 and (USERS_PROG_TB.BRANCH = {$this->user->branch} or {$this->user->branch} = 1) "); // and USERS_PROG_TB.ID <> {$this->user->id}
        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('settings/constant_details_model');
        $data['service_type'] = $this->constant_details_model->get_list(285);
        $data['service_property'] = $this->constant_details_model->get_list(286);
        $data['status_type_con'] = $this->constant_details_model->get_list(287);
        $data['status_class_type'] = $this->constant_details_model->get_list(289);
        $this->_generate_std_urls($data, true);
    }

    function public_process_request()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $h_ser = $this->input->post('h_ser');
            $status = $this->input->post('status');
            $solve_problem_final = $this->input->post('solve_problem_final');
            $receipt_user = $this->input->post('receipt_user');
            $receipt_date = $this->input->post('receipt_date');
            $receipt_note = $this->input->post('receipt_note');
            $check_classId_process = $this->rmodel->getID('MAINTENANCE_PKG', 'MAINTENANCE_REQ_CLAS_TB_GET', $h_ser);
            $ck = 0;
            foreach ($check_classId_process as $k => $r) {
                if ($check_classId_process[$k]['STATUS_CLASS_ID'] == 1 && $status == 20) {
                    $ck = 1;
                    break;
                }
            }
            if ($ck == 1) {
                $this->print_error('يرجى التأكد من حالة جميع الاجهزة المطلوب صيانتها انها بحالة غير مدخلة');
                return -1;
            } else {
                $data_arr = array(
                    array('name' => 'SER_IN', 'value' => $h_ser, 'type' => '', 'length' => -1),
                    array('name' => 'STATUS_IN', 'value' => $status, 'type' => '', 'length' => -1),
                    array('name' => 'SOLVE_PROBLEM_IN', 'value' => trim($solve_problem_final), 'type' => '', 'length' => -1),
                    array('name' => 'RECEIPT_USER_IN', 'value' => $receipt_user, 'type' => '', 'length' => -1),
                    array('name' => 'RECEIPT_DATE_IN', 'value' => $receipt_date, 'type' => '', 'length' => -1),
                    array('name' => 'RECEIPT_NOTE_IN', 'value' => $receipt_note, 'type' => '', 'length' => -1),
                );
                $this->rmodel->package = 'MAINTENANCE_PKG';
                $res = $this->rmodel->update('FINAL_PROCESS_REQUEST_UPDATE', $data_arr);
                if (intval($res) >= 1) {
                    echo 1;
                } else {
                    $this->print_error('Error_' . $res);
                }
            }
        }
    }



    function public_get_notes($source_Id, $source)
    {

        $data['rows'] = $this->get_notes($source_Id, $source);

        $this->load->view('notes_page', $data);
    }


    function get_notes($source_Id, $source)
    {

        $params = array(
            array('name' => ':SOURCE_ID_IN', 'value' => $source_Id, 'type' => '', 'length' => -1),
            array('name' => ':SOURCE_IN', 'value' => $source, 'type' => '', 'length' => -1),
            array('name' => ':REF_CUR_OUT', 'value' => 'cursor', 'type' => OCI_B_CURSOR),

            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->New_rmodel->general_get('ATTACHMENT_PKG', 'NOTES_TB_GET_ALL', $params);
        return $result;
    }

    function indexLine($id, $categories)
    {

        add_js('ajax_upload_file.js');

        $data['rows'] = $this->get_table_count(" GFC_ATTACHMENT_TB WHERE (CATEGORY='{$categories}' or CATEGORY like '{$categories}_sub%') AND IDENTITY ='{$id}'  ");
        $data['id'] = $id;
        $data['categories'] = $categories;
        $this->load->view('attachment_index', $data);
    }

    function indexInline($id, $categories, $can_upload_priv = 1)
    {

        add_js('ajax_upload_file.js');
        $data['rows'] = $this->get_table_count(" GFC_ATTACHMENT_TB WHERE CATEGORY='{$categories}' AND IDENTITY ='{$id}'  ");
        $data['id'] = $id;
        $data['categories'] = $categories;
        $data['can_upload_priv'] = $can_upload_priv;

        $this->load->view('attachment_inline_index', $data);
    }
}
