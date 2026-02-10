<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 19/10/2022
 * Time: 10:40 ص
 */
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Job_ads_request extends MY_Controller
{


    var $PAGE_URL = 'internal_jobs/job_ads_request/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model('settings/users_model');
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'INTERNAL_JOBS_PKG';


        $this->ser = $this->input->post('ser');
    }


    function all_request($page = 1, $action = 1)
    {
        if (HaveAccess('internal_jobs/job_ads_request/all_request')){
            $data['title'] = 'المتقدمين على الوظائف';
            $data['content'] = 'job_ads_request_index';
            $data['ads_arr'] = $this->rmodel->getAll('INTERNAL_JOBS_PKG', 'JOB_ADS_TB_GET_ALL');
            $data['page'] = $page;
            $data['action'] = $action;
            $this->_lookup($data);
            $this->load->view('template/template1', $data);
        }else{
            die('لا تملك صلاحيات للاستعلام');
        }

    }

    function my_request_job($page = 1, $action = 0)
    {
        $data['title'] = 'وظائفي';
        $data['content'] = 'job_ads_request_index';
        $data['ads_arr'] = $this->rmodel->getAll('INTERNAL_JOBS_PKG', 'JOB_ADS_TB_GET_ALL');
        $data['page'] = $page;
        $data['action'] = $action;
        $this->_lookup($data);
        $this->load->view('template/template1', $data);

    }

    function get_page($page = 1, $action = -1)
    {
        $this->load->library('pagination');
        $where_sql = 'where 1 = 1 ';
        if ($action == 0) {
            $where_sql .= " AND  EMP_NO = {$this->user->emp_no} ";
        } else {
            $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND  EMP_PKG.GET_EMP_BRANCH(EMP_NO) = '{$this->p_branch_no}'  " : "";
            $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  EMP_NO = '{$this->p_emp_no}'  " : "";
            $where_sql .= isset($this->p_ads_ser) && $this->p_ads_ser != null ? " AND  ADS_SER = '{$this->p_ads_ser}'  " : "";
            $where_sql .= isset($this->p_status) && $this->p_status != null ? " AND  STATUS = '{$this->p_status}'  " : "";
            $where_sql .= isset($this->p_grievance_status) && $this->p_grievance_status != null ? " AND  GRIEVANCE_STATUS = '{$this->p_grievance_status}'  " : "";
        }
        if ($this->user->id == '882'){
            $where_sql .= " AND  ADS_SER = '23'";
        }
        /*if ($this->user->id == '499' ){
            $where_sql .= " AND  ADS_SER = '26'";
        }*/
       /* if ($this->user->id == '881' ){
            $where_sql .= " AND  ADS_SER = '32'";
        }*/
        /*if ($this->user->id == '142' ){ /*********ذكي القرعة***********/
           /* $where_sql .= " AND  ADS_SER in ('26','37') ";/**********التفتيش*********/
            //$where_sql .= " OR  ADS_SER = '37' ";/**********التفتيش*********/
        /*}*/
         if ($this->user->id == '580' ){ /*********الرقب***********/
            $where_sql .= " AND  ADS_SER = '28' ";/**********ادارة اللوازم والخدمات*********/
        }
        


       /* if ($this->user->id == '116' ){ /*********اسامة محيسن**********/
            /*$where_sql .= " AND  ADS_SER  = 31 ";/********** مدير دائرة الشؤون المالية والادارية في الفروع*********/
       /* }*/


             if ($this->user->id == '1676' ){ /*********احمد شعت***********/
                     $where_sql .= " AND  ADS_SER  in (21, 25) ";
             }

            //echo $where_sql;
             $config['base_url'] = base_url($this->PAGE_URL);
             $count_rs = $this->get_table_count(' JOB_ADS_REQUEST_TB ' . $where_sql);
             $config['use_page_numbers'] = TRUE;
             $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
             $config['per_page'] = 400; // $this->page_size
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
             $data["page_rows"] = $this->rmodel->getList('JOB_ADS_REQUEST_TB_LIST', $where_sql, $offset, $row);
             $data['offset'] = $offset + 1;
             $data['page'] = $page;
             $this->load->model('settings/constant_details_model');
             $data['status_cons'] =  $this->constant_details_model->get_list(481);
             $this->load->view('job_ads_request_page', $data);
         }

         function export_excel()
         {

             /*$where_sql = 'where 1 = 1 ';

             $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND  EMP_PKG.GET_EMP_BRANCH(EMP_NO) = '{$this->q_branch_no}'  " : "";
             $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  EMP_NO = '{$this->q_emp_no}'  " : "";
             $where_sql .= isset($this->q_ads_ser) && $this->p_ads_ser != null ? " AND  ADS_SER = '{$this->q_ads_ser}'  " : "";
             $where_sql .= isset($this->q_status) && $this->p_status != null ? " AND  STATUS = '{$this->p_status}'  " : "";
             $where_sql .= isset($this->q_grievance_status) && $this->p_grievance_status != null ? " AND  GRIEVANCE_STATUS = '{$this->p_grievance_status}'  " : "";


             $count_rs = $this->get_table_count(' JOB_ADS_REQUEST_TB ' . $where_sql);
             $count_rs = $count_rs[0]['NUM_ROWS'];
             $excelData = $this->rmodel->getList('JOB_ADS_REQUEST_TB_LIST', $where_sql, 0, $count_rs);
             //PHP 7 gs2
             $spreadsheet = new Spreadsheet();
             // Set document properties
             $spreadsheet->getProperties()->setCreator("النظام الاداري");
             // Create a first sheet
             $spreadsheet->setActiveSheetIndex(0);
             // set Header*/
        /*a b c d e f g h i j k l m n o p q r s t u v w x y z*/
       /* $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('A1', 'م');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('B1', 'المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('C1', 'الرقم الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('D1', 'الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'الوظيفة المقدم عليها');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'تاريخ التقديم');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'الحالة');


        $from = "A1"; // or any value
        $to = "G1"; // or any value
        $spreadsheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);

        // Set auto size and
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);

        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['EMP_NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['ADS_NAME'])
                ->setCellValue('F' . $rows, $val['ENTRY_DATE_TIME'])
                ->setCellValue('G' . $rows, $val['STATUS_NAME']);
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "كشف الطلبات الوظيفية" . date('d/m/y', time()) . ".xlsx";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        //ob_end_clean();
        set_time_limit(500);
        ini_set('memory_limit', '-1');
        $object_writer->save('php://output');*/
    }

    /*******تقديم طلب*********/
    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ads_ser = $this->input->post('ads_ser');
            $emp_no = $this->input->post('emp_no');
            $x = 0;
            foreach ($ads_ser as $row) {
                $res = $this->rmodel->insert('JOB_ADS_REQUEST_TB_INSERT', $this->_postedData($row, $emp_no));
                if ($res >= 1) $x++;
            }
            if ($x != count($ads_ser)) {
                echo $res;
            } else {
                echo 1;
            }
        }
    }

    function _postedData($row, $emp_no)
    {
        $result = array(
            array('name' => 'ADS_SER', 'value' => $row, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $emp_no, 'type' => '', 'length' => -1),
        );
        return $result;
    }


    function indexInlineno($id, $categories)
    {
        add_js('ajax_upload_file.js');
        $count_rs = $this->get_table_count(" GFC.GFC_ATTACHMENT_TB T WHERE UPPER(T.CATEGORY) = '{$categories}' AND T.IDENTITY ='{$id}'  ");
        $data['cnt_row'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $data['id'] = $id;
        $data['categories'] = $categories;
        $data['can_upload_priv'] = 1;
        $this->load->view('attachment_num_inline', $data);
    }


    function indexInlineAction($id, $categories,$action)
    {
        add_js('ajax_upload_file.js');
        $count_rs = $this->get_table_count(" GFC.GFC_ATTACHMENT_TB T WHERE UPPER(T.CATEGORY) = '{$categories}' AND T.IDENTITY ='{$id}'  ");
        $data['cnt_row'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $data['id'] = $id;
        $data['categories'] = $categories;
        $data['can_upload_priv'] = 1;
        if ($action == 1){ //cv
            $this->load->view('attachment_num_inline_cv', $data);
        }elseif ($action == 2) { //achievement
            $this->load->view('attachment_num_inline_achievement', $data);
        }elseif ($action == 3) { //plan
            $this->load->view('attachment_num_inline_plan', $data);
        }

    }

    function edit_status(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ser = $this->input->post('ser');
            $note = '';
            $status = $this->input->post('status');
            $res = $this->rmodel->update('JOB_ADS_REQUEST_TB_UPDATE', $this->_postedDataStatus($ser, $note,$status));
            if (intval($res) >= 1) {
                echo intval($res);
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }
    function _postedDataStatus($ser, $note,$status)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $note, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $status, 'type' => '', 'length' => -1),
        );
        return $result;
    }

    function _lookup(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $this->load->model('settings/constant_details_model');
        $data['status_cons'] =  $this->constant_details_model->get_list(481);

    }
    /******************عرض تظلم****************/
    function public_get_entry_grievance(){
        $id = $this->input->post('ser');
        // جلب بيانات
        $rertMain = $this->rmodel->getID('INTERNAL_JOBS_PKG', 'JOB_ADS_REQUEST_TB_GET', $id);
        $ser =    $rertMain[0]['SER'];
        $ads_name =    $rertMain[0]['ADS_NAME'];
        $status_name =    $rertMain[0]['STATUS_NAME'];
        $notes =    $rertMain[0]['NOTES'];
        $grievance_note =    $rertMain[0]['GRIEVANCE_NOTE'];
        $html = '<div class="card">
                        <div class="card-body">
                             <div class="row">
                                <!-------->
                                <input   class="form-control" id="h_grievance_ser" name="h_grievance_ser" hidden  value="'.$ser.'" />
                               
                                <!-------->
                                <div class="form-group col-md-4"><label>الوظيفة</label>
                                        <input type="text" class="form-control" readonly value="'.$ads_name.'" />
                                </div>
                                 <!-------->
                                <div class="form-group col-md-2"><label>الحالة</label>
                                        <input type="text" class="form-control" readonly value="'.$status_name.'" />
                                </div>
                                
                                 <!-------->
                                <div class="form-group col-md-6"><label>سبب الرفض</label>
                                        <input type="text" class="form-control" readonly value="'.$notes.'" />
                                </div>
                               
                           </div>
                           <div class="row">
                            <!-------->
                                <div class="form-group col-md-12"><label>التظلم</label>
                                    <textarea class="form-control" name="grievance_note" id="txt_grievance_note"  rows="8">'.$grievance_note.'</textarea>
                                </div>
                           </div>
                     </div> ';
        $html .= '</div>';
        echo $html;
    }
    /*******************ادخال التظلم**************/
    function public_entry_grievance(){
        $grievance_ser = $this->input->post('grievance_ser');
        $grievance_note = $this->input->post('grievance_note');
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation_g($grievance_ser,$grievance_note);
            $data_arr = array(
                array('name' => 'SER_IN', 'value' => $grievance_ser, 'type' => '', 'length' => -1),
                array('name' => 'GRIEVANCE_STATUS', 'value' => 1, 'type' => '', 'length' => -1),
                array('name' => 'GRIEVANCE_NOTE', 'value' => $grievance_note, 'type' => '', 'length' => -1),

            );
            $res = $this->rmodel->update('JOB_ADS_REQUEST_TB_GRIEVANCE', $data_arr);
            if (intval($res) >= 1) {
                echo intval($res);
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }

    function _post_validation_g($grievance_ser,$grievance_note){
        if( $grievance_ser == '' ){
            $this->print_error('يجب التأكد من تحديث الشاشة');
        }elseif($grievance_note  == ''){
            $this->print_error('ادخل الدرجة بشكل صحيح');
        }
    }


    function public_get_request_data(){
        $id = $this->input->post('id');
        $data['rertMain'] = $this->rmodel->getID('INTERNAL_JOBS_PKG', 'JOB_ADS_REQUEST_TB_GET', $id);
        $this->load->model('settings/constant_details_model');
        $data['status_cons'] =  $this->constant_details_model->get_list(481);
        $data['status_interview_cons'] =  $this->constant_details_model->get_list(486);
        $this->load->view('internal_jobs/edit_request_data', $data);


    }
    function public_update_status(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $h_ser = $this->input->post('h_ser');
            $notes = $this->input->post('txt_notes');
            $dl_status = $this->input->post('dl_status');
            $interview_date = $this->input->post('interview_date');
            $interview_hour = $this->input->post('interview_hour');
            $interview_result = $this->input->post('interview_result');
            $interview_notes = $this->input->post('interview_notes');
            $data_arr = array(
                array('name' => 'SER_IN', 'value' => $h_ser, 'type' => '', 'length' => -1),
                array('name' => 'NOTES_IN', 'value' => $notes, 'type' => '', 'length' => -1),
                array('name' => 'STATUS_IN', 'value' => $dl_status, 'type' => '', 'length' => -1),
                array('name' => 'INTERVIEW_DATE_IN', 'value' => $interview_date, 'type' => '', 'length' => -1),
                array('name' => 'INTERVIEW_HOUR_IN', 'value' => $interview_hour, 'type' => '', 'length' => -1),
                array('name' => 'INTERVIEW_RESULT_IN', 'value' => $interview_result, 'type' => '', 'length' => -1),
                array('name' => 'INTERVIEW_NOTES_IN', 'value' => $interview_notes, 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('JOB_ADS_REQUEST_TB_UPDATE', $data_arr);
            if (intval($res) >= 1) {
                echo intval($res);
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }
}


