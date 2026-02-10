<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 23/01/20
 * Time: 11:31 ص
 */


class Traineerequest extends MY_Controller
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
    }

    /************************************index*********************************************/

    function index($page = 1)
    {

        $data['title'] = 'الاستعلام عن طلبات المدربين';
        $data['content'] = 'traineeRequest_index';

        $data['offset']=1;
        $data['page']=$page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['hide'] ='hidden';

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();
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

        $data['trainee_type'] = $this->constant_details_model->get_list(354);
        $data['advers'] = $this->rmodel->getList('GET_ALL_ADVERS', " ", 0, 2000);

        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Traineerequest/get_page/");

        $sql = ' ';
        if($this->p_trainee_type == 1){

            $sql .= isset($this->p_license_number) && $this->p_license_number ? " AND LICENSE_NUM= {$this->p_license_number} " : '';
            $sql .= isset($this->p_company_type) && $this->p_company_type ? " AND COMPANY_TYPE like '".add_percent_sign($this->p_company_type)."' " : "";
            $sql .= isset($this->p_company_name) && $this->p_company_name ? " AND COMPANY_NAME like '".add_percent_sign($this->p_company_name)."' " : "";
        }else{
            $sql .= isset($this->p_id_number) && $this->p_id_number ? " AND Q.ID= {$this->p_id_number} " : '';
            $sql .= isset($this->p_name) && $this->p_name ? " AND TRAIN_PKG.GET_NAME (Q.FIRST_NAME,Q.SECOND_NAME,Q.THIRD_NAME,Q.LAST_NAME) LIKE '".add_percent_sign($this->p_name)."' " : "";
            $sql .= isset($this->p_adver_id) && $this->p_adver_id ? " AND ADVER_ID =  '".$this->p_adver_id."' " : "";
        }

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = 2000;//count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        if($this->p_trainee_type == 1){
            $data["rows"] = $this->rmodel->getList('TRAIN_CORPO_REQUE_TB_LIST', " $sql ", $offset, $row);
        }else{
            $data["rows"] = $this->rmodel->getList('TRAIN_REQUESTS_TB_LIST', " $sql ", $offset, $row);
        }

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $data['trainee_source'] = $this->p_trainee_type;
        $this->load->view('traineeRequest_page', $data);
    }



    function get_person($id)
    {
        $result = $this->rmodel->get('TRAIN_REQUESTS_TB_GET', $id);
        $data['details'] = $this->rmodel->getList('TRAIN_COURSES_TB_LIST', " WHERE ATTACHED_TRAINEE_SOURCE = 0 AND ADOPT = 2 ", 0, 2000);
        $data['financial'] = $this->rmodel->get('FINANCIAL_OFFERS_TB_LIST',$id);
        $data['technical'] = $this->rmodel->get('TECHNICAL_OFFERS_LIST',$id);
        $data['title'] = 'البيانات الشخصية';
        $data['content'] = 'traineeRequest_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['type'] = 2 ;
        $data['action'] = 'edit';
        $data['check'] = 1;
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function getData($id)
    {
        $result = $this->rmodel->get('TRAIN_REQUESTS_COMPANY_TB_GET', $id);

        $data['title'] = 'البيانات الشخصية';
        $data['content'] = 'traineeRequest_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['type'] = 2 ;
        $data['action'] = 'edit';
        $data['check'] = 0;
        $this->_lookup($data);
        $this->load->view('template/template', $data);


    }


    function get_company($id)
    {
        $data['details'] = $this->rmodel->getList('TRAIN_COURSES_TB_LIST', " WHERE ATTACHED_TRAINEE_SOURCE = 0 AND ADOPT = 2 ", 0, 2000);
        $result = $this->rmodel->get('TRAIN_CORPO_REQUE_TB_GET', $id);
        $data['title'] = 'بيانات الشركة';
        $data['content'] = 'traineeRequest_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['type'] = 1 ;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);


    }


    function public_get_ac_qualifications($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['details'] = $this->rmodel->get('TRAIN_AC_QUALIFICATIONS_GET', $id);
        $this->_lookup($data);
        $this->load->view('traineeRequest_details', $data);
    }

    function public_get_financial_offer($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['details'] = $this->rmodel->get('FINANCIAL_OFFERS_TB_LIST', $id);

        $this->_lookup($data);
        $this->load->view('traineeRequest_fin_details', $data);
    }

    function public_get_technical_offer($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['details'] = $this->rmodel->get('TECHNICAL_OFFERS_LIST', $id);
        $this->_lookup($data);
        $this->load->view('traineeRequest_tec_details', $data);
    }

    function public_get_pract_exper($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['details'] = $this->rmodel->get('TRAIN_PRACT_EXPER_GET', $id);
        $this->_lookup($data);
        $this->load->view('traineeRequest_experience_details', $data);
    }

    function public_get_trainee_course($id = 0)
    {
        $data['details'] = $this->rmodel->get('TRAIN_TRAINEE_COURS_GET', $id);
        $this->_lookup($data);
        $this->load->view('traineeRequest_courses_details', $data);
    }

    function public_get_trainee_details($id = 0)
    {

        $data['details'] = $this->rmodel->get('COMPNAY_DETAILS_TB_GET', $id);
        $this->_lookup($data);
        $this->load->view('traineeRequest_trainee_details', $data);
    }

    function updateCourse(){

        $rs = $this->rmodel->update('TRAIN_COURSES_TRAINEE_UPDATE', $this->_postedDataCourse(false));
        if (intval($rs) <= 0) {
            $this->print_error('error' . '<br>' . $rs);
        }
        echo 1;
    }

    /*function updateCourse(){

        $rs = $this->rmodel->update('TRAIN_COURSES_TRAINEE_UPDATE', $this->_postedDataCourse(false));
        if (intval($rs) <= 0) {
            $this->print_error('error' . '<br>' . $rs);
        }
        echo 1;
    }*/

    function _postedDataCourse($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser_course , 'type' => '', 'length' => -1),
            array('name' => 'ATTACHED_TRAINEE_SOURCE', 'value' => $this->p_trainee_type, 'type' => '', 'length' => -1),
            array('name' => 'ATTACHED_TRAINEE_SER', 'value' => $this->p_trainee_ser, 'type' => '', 'length' => -1),
        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }

    /**************************************create*****************************************/

    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->ser = $this->rmodel->insert('TRAIN_TRAINEE_GEDCO_INSERT', $this->_postedData());

            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {
                echo intval($this->ser);

            }

        } else {

            $data['title'] = 'اضافة مدرب';
            $data['action'] = 'index';
            $data['isCreate'] = true;
            $data['content'] = 'traineeRequest_new_trainee_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }
    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'NAME', 'value' => $this->p_name, 'type' => '', 'length' => -1),
            array('name' => 'ID', 'value' => $this->p_id, 'type' => '', 'length' => -1),
            array('name' => 'BIRTH_DATE', 'value' => $this->p_birth_date, 'type' => '', 'length' => -1),
            array('name' => 'SPEC', 'value' => $this->p_spec, 'type' => '', 'length' => -1),
            array('name' => 'MOBILE', 'value' => $this->p_mobile, 'type' => '', 'length' => -1),
            array('name' => 'EMAIL', 'value' => $this->p_email, 'type' => '', 'length' => -1),
            array('name' => 'NAME_ENG', 'value' => $this->p_name_eng, 'type' => '', 'length' => -1),
        );

        if ($isCreate)
            array_shift($result);
        return $result;
    }


    function gedco($page = 1){
        $data['title'] = 'الاستعلام عن مدربين من الشركة';
        $data['content'] = 'traineeRequest_new_trainee_index';
        $data['offset']=1;
        $data['page']=$page;
        $data['action'] = 'index';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function gedco_get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("training/Traineerequest/gedco_get_page/");

        $sql = ' ';
        $sql .= isset($this->p_id_number) && $this->p_id_number ? " AND ID= {$this->p_id_number} " : '';
        $sql .= isset($this->p_name) && $this->p_name ? " AND NAME LIKE '".add_percent_sign($this->p_name)."' " : "";

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = 2000;//count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $data["rows"] = $this->rmodel->getList('TRAIN_TRAINEE_GEDCO_LIST', " $sql ", $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('traineeRequest_new_trainee_page', $data);
    }

    function get_trainee($id)
    {
        $result = $this->rmodel->get('TRAIN_TRAINEE_GEDCO_GET', $id);
        $data['title'] = 'البيانات الشخصية';
        $data['content'] = 'traineeRequest_new_trainee_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['type'] = 0 ;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= $this->rmodel->update('TRAIN_TRAINEE_GEDCO_UPDATE', $this->_postedData(false));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }
            echo intval($this->ser);
        }
    }








}
