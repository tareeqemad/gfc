<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/02/20
 * Time: 09:28 ص
 */


class Unpaidtrain extends MY_Controller
{

    var $PKG_NAME = "TRAIN_PKG";

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRAIN_PKG';
        //this for constant using
        $this->load->model('stores/store_committees_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model("stores/receipt_class_input_group_model");
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('hr_attendance/hr_attendance_model');
    }

    /************************************index*********************************************/

    function index($page = 1)
    {
        $data['title'] = 'الاستعلام عن  المتدربين';
        $data['content'] = 'unPaidTrain_index';
        $data['offset']=1;
        $data['page']=$page;
        $data['manage'] = $this->rmodel->getList('MANAGE_ALL', " ", 0, 2000);
        $data['action'] = 'create';
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    /*************************************_lookup****************************************/

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( 0 , 'hr_admin' );
        $data['trainee_type'] = $this->constant_details_model->get_list(312);
        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['manage'] = $this->rmodel->getList('MANAGE_ALL', " ", 0, 2000);
        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }


    function get_page($page = 1)
    {

        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Unpaidtrain/get_page/");

        $sql = 'WHERE 1 = 1';

        $sql .= isset($this->p_branch) && $this->p_branch ? " AND BRANCH= {$this->p_branch} " : '';
        $sql .= isset($this->p_manage) && $this->p_manage ? " AND MANAGE = {$this->p_manage} " : '';
        $sql .= isset($this->p_id) && $this->p_id ? " AND M.ID = {$this->p_id} " : '';
        $sql .= isset($this->p_start_date) && $this->p_start_date ? " AND START_DATE  >= '".($this->p_start_date)."' " : "";
        $sql .= isset($this->p_end_date) && $this->p_end_date ? " AND END_DATE  <= '".($this->p_end_date)."' " : "";



        //$sql .= isset($this->p_field) && $this->p_field ? " AND FIELD like '".add_percent_sign($this->p_field)."' " : "";
        $sql .= isset($this->p_name) && $this->p_name ? " AND NAME  LIKE '".add_percent_sign($this->p_name)."' " : "";
        $count_rs = 2000;//$this->get_table_count("gedcoapps.TRANNING_TB@TT.GEDCO.COM {$sql}");


        $config['use_page_numbers'] = TRUE;
		$config['total_rows'] = 2000; // count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $data["rows"] = $this->rmodel->getList('TRAIN_UNPAID_TB_LIST', " $sql ", $offset, $row);



        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('unPaidTrain_page', $data);
    }

    function get($id)
    {

        $result = $this->rmodel->get('TRAIN_UN_PAID_TB_GET', $id);
        $data['title'] = 'بيانات المتدرب';
        $data['content'] = 'unPaidTrain_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['type'] = 0 ;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }


    function create(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->ser = $this->rmodel->insert('TRAIN_UN_PAID_TB_INSERT', $this->_postedData());
            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                echo intval($this->ser);
            }
        } else {

            $data['title'] = 'متدرب جديد';
            $data['action'] = 'index';
            $data['isCreate'] = true;
            $data['content'] = 'unPaidTrain_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }
    }


    function edit(){

            $this->ser = $this->rmodel->update('TRAIN_UN_PAID_TB_UPDATE', $this->_postedData(false));
            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                echo intval($this->ser);
            }

    }

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'ID', 'value' => $this->p_id_check, 'type' => '', 'length' => -1),
            array('name' => 'NAME', 'value' => $this->p_name, 'type' => '', 'length' => -1),
            array('name' => 'BIRTH_DATE', 'value' => $this->p_birth_date, 'type' => '', 'length' => -1),
            array('name' => 'SPEC', 'value' => $this->p_spec, 'type' => '', 'length' => -1),
            array('name' => 'UNIVERSITY', 'value' => $this->p_university, 'type' => '', 'length' => -1),
            array('name' => 'GRADUATE_DATE', 'value' => $this->p_graduate_date, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->p_branch, 'type' => '', 'length' => -1),
            array('name' => 'MANAGE', 'value' => $this->p_manage, 'type' => '', 'length' => -1),
            array('name' => 'RESPONSIBLE_EMP', 'value' => $this->p_responsible_emp, 'type' => '', 'length' => -1),
            array('name' => 'START_DATE', 'value' => $this->p_start_date, 'type' => '', 'length' => -1),
            array('name' => 'END_DATE', 'value' => $this->p_end_date, 'type' => '', 'length' => -1),
            array('name' => 'MOBILE', 'value' => $this->p_mobile, 'type' => '', 'length' => -1),
            array('name' => 'EMAIL', 'value' => $this->p_email, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
        );

        //var_dump($result); die;
        if ($isCreate)
            array_shift($result);
        return $result;
    }


}
