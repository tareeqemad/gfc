<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/12/19
 * Time: 09:15 ص
 */

class Collection_companies extends MY_Controller
{

    var $PKG_NAME = "OUT_COLLECT_PKG";
	public $st_id;
	

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'OUT_COLLECT_PKG';
        //this for constant using
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
		$this->st_id = $this->generate_password();
		
		
		
		
    }

    /************************************index*********************************************/

    function index($page = 1)
    {
        $data['title'] = 'استعلام عن الشركات المتعاقدة';
        $data['content'] = 'collection_companies_index';

        $data['page'] = $page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

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

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['gender'] = $this->constant_details_model->get_list(158);
        $data['district'] = $this->constant_details_model->get_list(339);
        $data['social_status'] = $this->constant_details_model->get_list(159);
        $data['qualification'] = $this->constant_details_model->get_list(340);
        $data['section'] = $this->constant_details_model->get_list(341);
        $data['work_nature'] = $this->constant_details_model->get_list(342);


        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }

    function public_get_sections($id = 0,$adopt=1)

    {
        $data['details'] = $this->rmodel->get('OUT_SECTIONS_TB_GET', $id);
        $data['adopt']=$adopt;
        $this->_lookup($data);
        $this->load->view('sections_details', $data);
    }

    /***********************************get**********************************************/

    function get($id)
    {

        $result = $this->rmodel->get('COLLECTION_OFFICES_TB_GET', $id);
        $data['title'] = 'تعديل بيانات المكتب';
        $data['content'] = 'collection_companies_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    function get_employee($id)
    {

        $result = $this->rmodel->get('OUT_PERSONAL_INFO_TB_GET', $id);

        $data['title'] = 'تعديل بيانات الموظف';
        $data['content'] = 'employees_companies_show';
        $data['result'] = $result;
        $data['isCreate'] = false;
        $data['action'] = 'edit_emp';
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    /**************************************create*****************************************/

    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->ser = $this->rmodel->insert('COLLECTION_OFFICES_TB_INSERT', $this->_postedData());
            $this->sendMail = $this->send_email($this->p_email) ;



            if ($this->ser < 1) {
                $this->print_error('البريد الالكتروني مُدرج مسبقاً' . '<br>');
            } else {
                echo intval($this->ser);

            }

        } else {

            $data['title'] = 'اضافة شركة جديدة';
            $data['action'] = 'index';
            $data['isCreate'] = true;
            $data['content'] = 'collection_companies_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    function add($id){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            $this->ser = $this->rmodel->insert('OUT_PERSONAL_INFO_TB_INSERT', $this->_postedData_personal());
            $this->ser_admin = $this->rmodel->insert('OUT_ADMIN_INFO_TB_INSERT', $this->_postedData_admin($this->ser));
            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                if ($this->p_seq1[$i] <= 0 ) {
                    if ($this->p_section[$i] != 0  ) {
                        $serDet=$this->rmodel->insert('OUT_SECTIONS_TB_INSERT',$this->_posteddata_details
                            (null, $this->ser , $this->p_section[$i],'create') );
                        if (intval($serDet) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $serDet);
                        }
                    }
                }
            }


            if ($this->ser < 1) {
                $this->print_error('البريد الالكتروني مُدرج مسبقاً' . '<br>');
            } else {
                echo intval($this->ser);

            }

        } else {

            $data['title'] = 'اضافة موظف جديد';
            $data['action'] = 'index';
            $data['id'] = $id;
            $data['isCreate'] = true;
            $data['content'] = 'employees_companies_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    function public_get_employee($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data["details"] = $this->rmodel->get('OUT_PER_INFO_TB_COMPANY_GET', $id);
        $this->load->view('employees_companies_details', $data);
    }

    /*************************************_postedData*******************************************/

    function _postedData($isCreate = true)
    {
		
		$new_user_id = $this->generate_user_id($this->p_email);
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_NAME', 'value' => $this->p_company_name, 'type' => '', 'length' => -1),
            array('name' => 'LICENSE_NO', 'value' => $this->p_license_no, 'type' => '', 'length' => -1),
            array('name' => 'ADDRESS', 'value' => $this->p_address, 'type' => '', 'length' => -1),
            array('name' => 'TEL_NO', 'value' => $this->p_tel_no, 'type' => '', 'length' => -1),
            array('name' => 'ID_NUMBER', 'value' => $this->p_id_number, 'type' => '', 'length' => -1),
            array('name' => 'USER_NAME', 'value' => $this->p_user_name, 'type' => '', 'length' => -1),
            array('name' => 'MOBILE', 'value' => $this->p_mobile, 'type' => '', 'length' => -1),
            array('name' => 'EMAIL', 'value' => $this->p_email, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
            array('name' => 'USER_ID', 'value' => $new_user_id , 'type' => '', 'length' => -1),
            array('name' => 'USER_PWD', 'value' => md5($this->st_id), 'type' => '', 'length' => -1),


        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }

    function _postedData_personal($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'ID_NUMBER', 'value' => $this->p_id_number, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NAME', 'value' => $this->p_emp_name, 'type' => '', 'length' => -1),
            array('name' => 'MOBILE', 'value' => $this->p_mobile, 'type' => '', 'length' => -1),
            array('name' => 'BIRTH_DATE', 'value' => $this->p_birth_date, 'type' => '', 'length' => -1),
            array('name' => 'GENDER', 'value' => $this->p_gender, 'type' => '', 'length' => -1),
            array('name' => 'SOCIAL_STATUS', 'value' => $this->p_social_status, 'type' => '', 'length' => -1),
            array('name' => 'REGION', 'value' => $this->p_region, 'type' => '', 'length' => -1),
            array('name' => 'DISTRICT', 'value' => $this->p_district, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_SER', 'value' => $this->p_id, 'type' => '', 'length' => -1),


        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }

    function _postedData_personal2($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'ID_NUMBER', 'value' => $this->p_id_number, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NAME', 'value' => $this->p_emp_name, 'type' => '', 'length' => -1),
            array('name' => 'MOBILE', 'value' => $this->p_mobile, 'type' => '', 'length' => -1),
            array('name' => 'BIRTH_DATE', 'value' => $this->p_birth_date, 'type' => '', 'length' => -1),
            array('name' => 'GENDER', 'value' => $this->p_gender, 'type' => '', 'length' => -1),
            array('name' => 'SOCIAL_STATUS', 'value' => $this->p_social_status, 'type' => '', 'length' => -1),
            array('name' => 'REGION', 'value' => $this->p_region, 'type' => '', 'length' => -1),
            array('name' => 'DISTRICT', 'value' => $this->p_district, 'type' => '', 'length' => -1),


        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }


    function _postedData_admin($emp_no,$isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'EMP_SER', 'value' => $emp_no , 'type' => '', 'length' => -1),
            array('name' => 'HIRE_DATE', 'value' => $this->p_hire_date, 'type' => '', 'length' => -1),
            array('name' => 'QUALIFICATION', 'value' => $this->p_qualification, 'type' => '', 'length' => -1),
            array('name' => 'GRADUATION_YEAR', 'value' => $this->p_graduation_year, 'type' => '', 'length' => -1),
            array('name' => 'WORK_PLACE', 'value' => $this->p_work_place, 'type' => '', 'length' => -1),
            array('name' => 'WORK_NATURE', 'value' => $this->p_work_nature, 'type' => '', 'length' => -1),


        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }

    function _postedData_admin2($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'HIRE_DATE', 'value' => $this->p_hire_date, 'type' => '', 'length' => -1),
            array('name' => 'QUALIFICATION', 'value' => $this->p_qualification, 'type' => '', 'length' => -1),
            array('name' => 'GRADUATION_YEAR', 'value' => $this->p_graduation_year, 'type' => '', 'length' => -1),
            array('name' => 'WORK_PLACE', 'value' => $this->p_work_place, 'type' => '', 'length' => -1),
            array('name' => 'WORK_NATURE', 'value' => $this->p_work_nature, 'type' => '', 'length' => -1),


        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }


    /********************************************edit*********************************************/

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser= $this->rmodel->update('COLLECTION_OFFICES_TB_UPDATE', $this->_postedData2(false));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }

            echo intval($this->ser);
        }
    }

    function edit_emp(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->ser= $this->rmodel->update('OUT_PERSONAL_INFO_TB_UPDATE', $this->_postedData_personal2(false));

            if (intval($this->ser) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser);
            }else{
                $this->ser_admin= $this->rmodel->update('OUT_ADMINI_INFO_TB_UPDATE', $this->_postedData_admin2(false));
                if (intval($this->ser_admin) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $this->ser_admin);
                }

                for ($i = 0; $i < count($this->p_seq1); $i++)
                {

                    if ($this->p_seq1[$i] <= 0 ) {

                        if ($this->p_section[$i] != 0  ) {
                            $serDet=$this->rmodel->insert('OUT_SECTIONS_TB_INSERT',$this->_posteddata_details
                                (null, $this->ser , $this->p_section[$i],'create') );
                            if (intval($serDet) <= 0) {
                                $this->print_error('لم يتم الحفظ' . '<br>' . $serDet);
                            }
                        }
                    } else {
                        $x=$this->rmodel->insert('OUT_SECTIONS_TB_UPDATE',$this->_posteddata_details
                            ($this->p_seq1[$i],$this->ser , $this->p_section[$i], 'edit') );


                        if (intval($x) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                        }
                    }
                }
            }

            echo intval($this->ser);
        }
    }


    function _posteddata_details($ser = null, $emp_ser = null, $section = null, $type)
    {

        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'SECTION', 'value' => $section, 'type' => '', 'length' => -1),
            array('name' => 'EMP_SER', 'value' => $emp_ser, 'type' => '', 'length' => -1),


        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }
	
	function _postedData2($isCreate = true)
    {

        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_NAME', 'value' => $this->p_company_name, 'type' => '', 'length' => -1),
            array('name' => 'LICENSE_NO', 'value' => $this->p_license_no, 'type' => '', 'length' => -1),
            array('name' => 'ADDRESS', 'value' => $this->p_address, 'type' => '', 'length' => -1),
            array('name' => 'TEL_NO', 'value' => $this->p_tel_no, 'type' => '', 'length' => -1),
            array('name' => 'ID_NUMBER', 'value' => $this->p_id_number, 'type' => '', 'length' => -1),
            array('name' => 'USER_NAME', 'value' => $this->p_user_name, 'type' => '', 'length' => -1),
            array('name' => 'MOBILE', 'value' => $this->p_mobile, 'type' => '', 'length' => -1),
            array('name' => 'EMAIL', 'value' => $this->p_email, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),


        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }

    /****************************************adopt*************************************************/

    function adopt(){
        $res= $this->rmodel->update('COLLECTION_OFFICES_TB_ADOPT', $this->_postedData_delete($this->p_id,2, false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;
    }

    function deleteSection(){
       // echo $this->p_section_ser; die;
        $res= $this->rmodel->delete('OUT_SECTIONS_TB_DELETE',$this->p_section_ser);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الحذف'.'<br>'.$res);
        }
        else
            echo 1;
    }


    /****************************************unadopt*************************************************/

    function unadopt(){

        $res= $this->rmodel->update('COLLECTION_OFFICES_TB_UNADOPT', $this->_postedData_delete($this->p_id,1, false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo 1;
    }


    function _postedData_delete($ser = null, $adopt = null, $type)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'ADOPT', 'value' => $adopt, 'type' => '', 'length' => -1),
        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

    /*******************************************get_page***********************************************/

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("collection_offices/collection_companies/get_page/");


        //$sql = " AND ADOPT = 1 ";
        $sql = isset($this->p_license_no) && $this->p_license_no ? " AND LICENSE_NO= {$this->p_license_no} " : '';
        $sql .= isset($this->p_branch_no) && $this->p_branch_no ? " AND BRANCH_ID= {$this->p_branch_no} " : '';
        $sql .= isset($this->p_company_name) && $this->p_company_name ? " AND COMPANY_NAME like '%{$this->p_company_name}%' " : "";


        $count_rs = $this->get_table_count(" COLLECTION_OFFICES_TB M  WHERE 1 = 1  {$sql}");


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
        $data["rows"] = $this->rmodel->getList('COLLECTION_OFFICES_TB_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('collection_companies_page', $data);
    }



    function public_get_sub($id = 0,$adopt=1)

    {
        $data['details'] = $this->rmodel->get('OFFICES_OUT_SUB_COLLECT_TB_GET', $id);
        $data['adopt']=$adopt;
        $this->_lookup($data);

        $this->load->view('collection_companies_details', $data);
    }

    function transform($page = 1){
        $data['title'] = 'التحويل لمكاتب التحصيل';
        $data['content'] = 'collection_companies_trans_index';

        $data['page'] = $page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data["details"] = $this->rmodel->getList('COLLECTION_OFFICES_TB_LIST', " and adopt = 2 ", 0, 2000);
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    function public_get_detaild_name(){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 0'));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, 'http://192.168.0.171:801/apis/GetData/'.$this->input->post('id'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array()));
        $result = curl_exec($curl);
        curl_close($curl);
        echo ($result);
    }

    function trans_get_page($page = 1){

        $this->load->library('pagination');
        $config['base_url'] = base_url("collection_offices/collection_companies/trans_get_page/");


        $sql = " and ADOPT = 3 and NOTI_STATUS = 2 and SER_OFFICE = 0";
        $sql .= isset($this->p_branch_no) && $this->p_branch_no ? " AND BRANCH= {$this->p_branch_no} " : '';
        $sql .= isset($this->p_disclosure_ser) && $this->p_disclosure_ser ? " AND DISCLOSURE_SER= {$this->p_disclosure_ser} " : '';
        $sql .= isset($this->p_sub_no) && $this->p_sub_no ? " AND SUBSCRIBER = {$this->p_sub_no} " : ''; "";


        $count_rs = $this->get_table_count(" OUT_SUB_COLLECT_TB M  WHERE 1 = 1  {$sql}");


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
        $data["rows"] = $this->rmodel->getList('OUT_SUB_COLLECT_TB_LIST', " $sql ", $offset, $row);

        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('collection_companies_trans_page', $data);



    }

    function get_office(){

        $sub_no = isset($this->p_sub_ser) ? $this->p_sub_ser : 0;
        $office_no = isset($this->p_sub_ser) ? $this->p_sub_ser : 0;
        $result_det = $this->rmodel->get('OUT_SUB_COLLECT_TB_GET', $sub_no);
        if (count($result_det) > 0) {
            echo json_encode($result_det[0]);
        } else {
            echo '';
        }

    }



    function update_ser_office(){
        $res= $this->rmodel->update('SUB_COLLECT_SER_OFFICE_UPDATE', $this->_postedData_delete($this->p_sub_ser,
            $this->p_company_ser, false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاسناد للشركة'.'<br>'.$res);
        }
        else
            echo 1;
    }


    function _postedData_update($sub_ser = null, $company_ser = null, $type)
    {
        $result = array(
            array('name' => 'SUB_SER', 'value' => $sub_ser, 'type' => '', 'length' => -1),
            array('name' => 'COMPANY_SER', 'value' => $company_ser, 'type' => '', 'length' => -1),
        );
        if ($type == 'create')
            unset($result[0]);

        return $result;
    }



    function send_email($email)
    {
		
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $config['charset'] = "UTF-8";
        $this->email->set_newline('\r\n');
        $this->email->set_crlf('\r\n');
        $this->email->initialize($config);
        $this->email->from('info@gedco.ps', 'مكاتب التحصيل');
        $this->email->subject('معلومات خاصة بمكتب التحصيل');
        $this->email->to($email);
		
		$new_username = $this->generate_user_id($email);
		
		
		
		
		

        $text=urldecode("
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'/>
            </head>
            <body>
            <table style=\"width: 250px; direction: rtl; color: royalblue; font-size: 20px; font-weight: bold; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;\">
                <tr>
                    <td>اسم المستخدم</td>
                    <td><div style='text-align: left; color: darkgreen'>{$new_username}</div></td>
                </tr>
                <tr>
                    <td>كلمة المرور</td>
                    <td><div style='text-align: left; color: darkred'>{$this->st_id }</div></td>
                </tr>
            </table>
            </body>
            </html>
        ");

        $this->email->message(($text));
        return @$this->email->send();
    }
	
	
	public function generate_password(){
		
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	
	function generate_user_id($email){
		
		$username  = $email;
		$username_sub = explode("@", $username);
		return $username_sub[0];
		
	}
	
	






}
