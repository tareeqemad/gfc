<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 10/07/19
 * Time: 10:14 ص
 */

class REC_DIS extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'DMG_PKG';
        $this->load->model('customer_elect_details_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model("stores/receipt_class_input_group_model");
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('stores/store_committees_model');
    }
    function index($page = 1)
    {

        $data['title'] = 'اتلاف سندات القبض وسندات الصرف';
        $data['content'] = 'REC_DIS_index.php';
        $data['page'] = $page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }
    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        $data['TYPE'] = $this->constant_details_model->get_all(281);
        $data['MODEL_TYPE'] = $this->constant_details_model->get_all(275);
        $data['class_input_class_type'] = $this->store_committees_model->get_all_by_type(5);
        $data['help'] = $this->help;
    }
    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("Destruction/rec_dis/get_page/");
        $sql = isset($this->p_RE_DIS_SER) && $this->p_RE_DIS_SER ? " AND RE_DIS_SER like '%{$this->p_RE_DIS_SER}%' " : "";
        $sql .= isset($this->p_RE_DIS_DATE) && $this->p_RE_DIS_DATE ? " AND RE_DIS_DATE LIKE '%{$this->p_RE_DIS_DATE}%' " : "";
        $sql .= isset($this->p_TYPE) && $this->p_TYPE ? " AND TYPE LIKE '%{$this->p_TYPE}%' " : "";
        $sql .= isset($this->p_BODY) && $this->p_BODY ? " AND BODY LIKE '%{$this->p_BODY}%' " : "";
        $sql .= isset($this->p_AMOUNT) && $this->p_AMOUNT ? " AND AMOUNT LIKE '%{$this->p_AMOUNT}%' " : "";
        $sql .= isset($this->p_ELECTRONIC_ARCHIVE) && $this->p_ELECTRONIC_ARCHIVE ? " AND ELECTRONIC_ARCHIVE LIKE '%{$this->p_ELECTRONIC_ARCHIVE}%' " : "";
        $sql .= isset($this->p_MODEL_NO) && $this->p_MODEL_NO ? " AND MODEL_NO LIKE '%{$this->p_MODEL_NO}%' " : "";
        $sql .= isset($this->p_BRANCH_NO) && $this->p_BRANCH_NO ? " AND BRANCH_NO LIKE '%{$this->p_BRANCH_NO}%' " : "";
        $sql .= isset($this->p_ENTRY_DATE) && $this->p_ENTRY_DATE ? " AND ENTRY_DATE LIKE '%{$this->p_ENTRY_DATE}%' " : "";
        $sql .= isset($this->p_ENTRY_USER) && $this->p_ENTRY_USER ? " AND ENTRY_USER LIKE '%{$this->p_ENTRY_USER}%' " : "";
        $count_rs = $this->get_table_count(" DMG_RECEIPTS_DISBURSEMENTS_TB where 1 = 1 {$sql}");
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
        $data["rows"] = $this->rmodel->getList('DMG_REC_DIS_TB_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('REC_DIS_page', $data);
    }

    function get_page_REC_DIS($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("Destruction/rec_dis/get_page_REC_DIS/");

        $sql = isset($this->p_ser) && $this->p_ser ? " AND M.SER= {$this->p_ser} " : '';
        $sql .= isset($this->p_model_date) && $this->p_model_date ? " AND M.MODEL_DATE like '%{$this->p_model_date}%' " : "";
        $sql .= isset($this->p_model_type) && $this->p_model_type ? " AND M.MODEL_TYPE LIKE '%{$this->p_model_type}%' " : "";

        $count_rs = $this->get_table_count(" DMG_BONDS_TB M  where 1=1 {$sql}");
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
        $data["rows"] = $this->rmodel->getList('DMG_REC_DIS_TB_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('page_REC_DIS_page', $data);
    }
    function get_id($id)
    {
        $result = $this->rmodel->get('DMG_MODELS_TB_GET', $id);
        $data['title'] = 'عرض بيانات السند';
        $data['content'] = 'REC_DIS_show';
        $data['result'] = $result;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }



    function public_list_REC_DIS_det($id = 0)
    {

        $result = $this->rmodel->get('DMG_REC_DIS_TB_GET', $id);
        $data['result'] = $result;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $data['rows'] = $this->rmodel->get('DMG_REC_DIS_TB_GET', $id);

        $this->load->view('REC_DIS_page', $data);
    }


    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'MODEL_DATE', 'value' => $this->p_model_date, 'type' => '', 'length' => -1),
            array('name' => 'MODEL_TYPE', 'value' => 3, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'DOCUMENT_YEAR', 'value' =>'', 'type' => '', 'length' => -1),
            array('name' => 'DOCUMENT_TYPE', 'value' => '', 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH_NO', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
            array('name' => 'COMMITTEES_NO', 'value' =>'', 'type' => '', 'length' => -1),


        );


        if ($isCreate)
            array_shift($result);

        return $result;
    }
    function _posteddata_details($ser = null, $re_dis_ser = null, $re_dis_date = null, $type = null,
                                 $body = null, $amount = null, $electronic_archive = null,
                                 $isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'RE_DIS_SER', 'value' => $re_dis_ser, 'type' => '', 'length' => -1),
            array('name' => 'RE_DIS_DATE', 'value' => $re_dis_date, 'type' => '', 'length' => -1),
            array('name' => 'TYPE', 'value' => $type, 'type' => '', 'length' => -1),
            array('name' => 'BODY', 'value' => $body, 'type' => '', 'length' => -1),
            array('name' => 'AMOUNT', 'value' => $amount, 'type' => '', 'length' => -1),
            array('name' => 'ELECTRONIC_ARCHIVE', 'value' => $electronic_archive, 'type' => '', 'length' => -1),


        );
        if ($isCreate)

            array_shift($result);



        return $result;
    }
    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->ser = $this->rmodel->insert('DMG_MODELS_TB_INSERT', $this->_postedData());

            for ($i = 0; $i < count($this->p_re_dis_ser); $i++) {

                echo  $this->rmodel->insert('DMG_REC_DIS_TB_INSERT', $this->_posteddata_details(
                    $this->ser[$i],
                    $this->p_re_dis_ser[$i],
                    $this->p_re_dis_date[$i],
                    $this->p_type[$i],
                    $this->p_body[$i],
                    $this->p_amount[$i],
                    $this->p_electronic_archive[$i]
                ));

            }


            if (intval(
                    $this->ser ) <= 0)
                $this->print_error('فشل في حفظ البيانات ' . $this->ser);

            echo
            $this->ser ;

        }

        else {
                $data['title'] = '  بيانات السند  ';
                $data['action'] = 'index';
                $data['isCreate'] = true;
                $this->_lookup($data);
                $data['content'] = 'REC_DIS_show';
                $this->_lookup($data);
                $this->load->view('template/template', $data);
        }

    }




     function edit()
     {

         if ($_SERVER['REQUEST_METHOD'] == 'POST') {

             $rs = $this->rmodel->update('DMG_MODELS_TB_UPDATE', $this->_postedData(false));

             for ($i = 0; $i < count($this->p_re_dis_ser); $i++) {

                echo $this->rmodel->update('DMG_REC_DIS_TB_UPDATE',$this->_posteddata_details(null,

                    $this->p_re_dis_ser[$i],
                    $this->p_re_dis_date[$i],
                    $this->p_type[$i],
                    $this->p_body[$i],
                    $this->p_amount[$i],
                    $this->p_electronic_archive[$i] , false
                ));
            }


            if (intval($rs) <= 0) {
                $this->print_error(' فشل بحفظ البيانات ' . $rs);
            }

            echo $rs;
        }

    }


    function unadopt(){
        $res= $this->rmodel->update('DMG_MODELS_TB_ADOPT', $this->_postedData_adopt($this->p_id,1,false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;
    }

    function adopt(){
        $res= $this->rmodel->update('DMG_MODELS_TB_ADOPT', $this->_postedData_adopt($this->p_id,2,false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;
    }



    function public_get_group_receipt($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['rec_groups'] = $this->receipt_class_input_group_model->get_details_all($id, 5);
        $this->load->view('commitees_member',$data);
    }


    function _postGroupsData($ser, $id, $group_person_id, $group_person_date, $emp_no, $status, $member_note, $ty = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'RECEIPT_CLASS_INPUT_ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'GROUP_PERSON_ID', 'value' => $group_person_id, 'type' => '', 'length' => -1),
            array('name' => 'GROUP_PERSON_DATE', 'value' => $group_person_date, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
            array('name' => 'SOURCE', 'value' => 5, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $status, 'type' => '', 'length' => -1),
            array('name' => 'MEMBER_NOTE', 'value' => $member_note, 'type' => '', 'length' => -1)
        );

        if ($ty == 'create') {
            array_shift($result);
        }

        return $result;

    }





}
