<?php
require('./application/third_party/phpoffice/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Morning_delay_adopt extends MY_Controller
{

    var $PAGE_URL = 'payroll_data/morning_delay_adopt/get_page';
    var $PAGE_DEALYEMP_URL = 'payroll_data/morning_delay_adopt/public_get_page_dealyemp';


    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';

    }

    function index($page = 1)
    {

        $data['title'] = 'كشف التأخير الصباحي المعتمد اداريا ';
        $data['content'] = 'morning_delay_adopt_index';
        $data['page'] = $page;
        $is_active = -1;
        $MODULE_NAME = 'payroll_data';
        $TB_NAME = 'morning_delay_adopt';
        $ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial"); //1
        $HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice"); //3
        $InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver"); //4
        $GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector"); //10
        $FinancialToPay = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay"); //15
        if (1) {
            //اعتماد المدير المالي
            if (HaveAccess($ChiefFinancial)) {
                $is_active = 0;
            }elseif (HaveAccess($HeadOffice)) {//اعتماد مدير المقر
                $is_active = 1;
            }elseif (HaveAccess($InternalObserver)) {//اعتماد الرقابة
                $is_active = 3;
            }elseif (HaveAccess($GeneralDirector)) {//اعتماد المدير العام
                $is_active = 4;
            }elseif (HaveAccess($FinancialToPay)) {//اعتماد المالية للصرف
                $is_active = 4; ///temp return 10
            }
        }
        //قيمة الاعتماد بناء على الصلاحية
        $data['is_active'] = $is_active;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);

    }


    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->p_branch_no}' ) " : '';
        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.THE_MONTH = '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_month_act) && $this->p_month_act != null ? " AND  M.MONTH_ACT = '{$this->p_month_act}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.EMP_NO = '{$this->p_emp_no}'  " : "";
        $where_sql .= isset($this->p_adopt_stage) && $this->p_adopt_stage != null ? " AND  M.IS_ACTIVE = '{$this->p_adopt_stage}'  " : "";
        $where_sql .= isset($this->p_agree_fi) && $this->p_agree_fi != null ? " AND  TRANSACTION_PKG.CHK_IN_DEALYEMP(M.MONTH_ACT,M.EMP_NO) = '{$this->p_agree_fi}'  " : "";

        $total_hours_rs = $this->get_sum_coloum('TOTAL','TOTAL_HOURS','DATA.DELAY_SALARY M WHERE 1 = 1',$where_sql);
        $total_days_rs = $this->get_sum_coloum('DAY','TOTAL_DAYS','DATA.DELAY_SALARY M WHERE 1 = 1',$where_sql);

        $hour_calculated_sal_rs = $this->get_sum_coloum('ROUND(TRANSACTION_PKG.DELAY_EMP_HOUR_CALCULATED_SAL(M.THE_MONTH,M.EMP_NO),2)','A_HOUR_CALCULATED_SAL','DATA.DELAY_SALARY M WHERE 1 = 1',$where_sql);
        $day_calculated_sal_rs = $this->get_sum_coloum('ROUND(TRANSACTION_PKG.DELAY_EMP_DAY_CALCULATED_SAL(M.THE_MONTH,M.EMP_NO),2)','A_DAY_CALCULATED_SAL','DATA.DELAY_SALARY M WHERE 1 = 1',$where_sql);

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' DATA.DELAY_SALARY M WHERE 1 = 1 ' . $where_sql);

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $data["page_rows"] = $this->rmodel->getList('DELAY_SALARY_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['v_count_row'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $data['v_total_hours'] = is_array($total_hours_rs) && count($total_hours_rs) > 0 ? $total_hours_rs[0]['TOTAL_HOURS'] : 0; //اجمالي عدد الساعات
        $data['v_total_days'] = is_array($total_days_rs) && count($total_days_rs) > 0 ? $total_days_rs[0]['TOTAL_DAYS'] : 0;
        $data['v_hour_calculated_sal'] = is_array($hour_calculated_sal_rs) && count($hour_calculated_sal_rs) > 0 ? $hour_calculated_sal_rs[0]['A_HOUR_CALCULATED_SAL'] : 0;
        $data['v_day_calculated_sal'] = is_array($day_calculated_sal_rs) && count($day_calculated_sal_rs) > 0 ? $day_calculated_sal_rs[0]['A_DAY_CALCULATED_SAL'] : 0;
        $data['param_branch'] = $this->p_branch_no;
        $this->load->view('morning_delay_adopt_page', $data);

    }


    function excel_adopt_hr_report(){
        $where_sql = '';
        //$where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND  EMP_PKG.GET_EMP_BRANCH(M.EMP_NO) = '{$this->q_branch_no}'  " : "";
        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->q_branch_no}' ) " : '';
        $where_sql .= isset($this->q_month) && $this->q_month != null ? " AND  M.THE_MONTH = '{$this->q_month}'  " : "";
        $where_sql .= isset($this->q_month_act) && $this->q_month_act != null ? " AND  M.MONTH_ACT = '{$this->q_month_act}'  " : "";
        $where_sql .= isset($this->q_emp_no) && $this->q_emp_no != null ? " AND  M.EMP_NO = '{$this->q_emp_no}'  " : "";
        $where_sql .= isset($this->q_adopt_stage) && $this->q_adopt_stage != null ? " AND  M.IS_ACTIVE = '{$this->q_adopt_stage}'  " : "";
        $where_sql .= isset($this->q_agree_fi) && $this->q_agree_fi != null ? " AND  TRANSACTION_PKG.CHK_IN_DEALYEMP(M.THE_MONTH,M.EMP_NO) = '{$this->q_agree_fi}'  " : "";


        $count_rs = $this->get_table_count(' DATA.DELAY_SALARY M WHERE 1 = 1 ' . $where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $excelData = $this->rmodel->getList('DELAY_SALARY_LIST', $where_sql, 0, $count_rs);
        //PHP 7 gs2
        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("النظام الاداري");
        // Create a first sheet
        $spreadsheet->setActiveSheetIndex(0);
        // set Header
        /*a b c d e f g h i j k l m n o p q r s t u v w x y z*/


        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('A1', 'م');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('B1', 'المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('C1', 'الرقم الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('D1', 'الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'شهر التأخير');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'عدد أيام التأخير الكلية');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'عدد أيام الاعفاء حسب القانون');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'عدد أيام الاعفاء بعذر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'عدد أيام الغياب');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'عدد ايام الخصم المعتمدة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('K1', 'عدد الساعات المخصومة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('L1', 'الراتب الاساسي ');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('M1', 'قيمة الاستقطاع');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('N1', 'شهر الاحتساب');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('O1', 'الاعتماد الاداري');


        $from = "A1"; // or any value
        $to = "O1"; // or any value
        $spreadsheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );

        // Set auto size and
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(15);

        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['EMP_NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['THE_MONTH'])
                ->setCellValue('F' . $rows, $val['COUNT_TOTAL_ALL'])
                ->setCellValue('G' . $rows, $val['COUNT_LAW'])
                ->setCellValue('H' . $rows, $val['COUNT_EXCUSE'])
                ->setCellValue('I' . $rows, $val['VAC'])
                ->setCellValue('J' . $rows, $val['DAY'])
                ->setCellValue('K' . $rows, $val['TOTAL'])
                ->setCellValue('L' . $rows, $val['BASIC_SAL'])
                ->setCellValue('M' . $rows, number_format($val['HOUR_CALCULATED_SAL'] + $val['DAY_CALCULATED_SAL'],2))
                ->setCellValue('N' . $rows, $val['MONTH_ACT'])
                ->setCellValue('O' . $rows, $val['IS_ACTIVE_NAME'])
            ;
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "كشف التأخير الصباحي المعتمد ادارياً" . date('d/m/y', time()) . ".xlsx";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $object_writer->save('php://output');
    }



    function public_dealyemp_index($page = 1)
    {

        $data['title'] = 'كشف التأخير الصباحي المعتمد مالياَ للصرف ';
        $data['content'] = 'morning_delay_adopt_t2_index';
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);

    }


    function public_get_page_dealyemp($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';
        /*$where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND  EMP_PKG.GET_EMP_BRANCH(M.EMP_NO) = '{$this->p_branch_no}'  " : "";*/
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->p_branch_no}' ) " : '';
        $where_sql .= isset($this->p_month) && $this->p_month != null ? " AND  M.MONTH = '{$this->p_month}'  " : "";
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  M.EMP_NO = '{$this->p_emp_no}'  " : "";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('DATA.DEALYEMP M WHERE 1 = 1 ' . $where_sql);

        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $data["dealyemp_rows"] = $this->rmodel->getList('DEALYEMP_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('morning_delay_adopt_t2_page', $data);

    }
    //التأخير الصباحي المعتمد مالياً
    function excel_adopt_financial_report(){

        $where_sql = '';
        //$where_sql .= isset($this->q_branch_no) && $this->q__branch_no != null ? " AND  EMP_PKG.GET_EMP_BRANCH(M.EMP_NO) = '{$this->q__branch_no}'  " : "";

        $where_sql .= isset($this->q_branch_no) && $this->q_branch_no != null ? " AND  TRANSACTION_PKG.DATA_EMPLOYEES_BRAN(M.EMP_NO) IN ( SELECT  M.BRANCH_NO   FROM  DATA.BRANCH  M
             WHERE    GCC_BRAN = '{$this->q_branch_no}' ) " : '';
        $where_sql .= isset($this->q__month) && $this->q__month != null ? " AND  M.MONTH = '{$this->q__month}'  " : "";
        $where_sql .= isset($this->q__emp_no) && $this->q__emp_no != null ? " AND  M.EMP_NO = '{$this->q__emp_no}'  " : "";


        $count_rs = $this->get_table_count('DATA.DEALYEMP M WHERE 1 = 1 ' . $where_sql);
        $count_rs = $count_rs[0]['NUM_ROWS'];
        $excelData = $this->rmodel->getList('DEALYEMP_LIST', $where_sql, 0, $count_rs);
        //PHP 7 gs2
        $spreadsheet = new Spreadsheet();
        // Set document properties
        $spreadsheet->getProperties()->setCreator("النظام الاداري");
        // Create a first sheet
        $spreadsheet->setActiveSheetIndex(0);
        // set Header
        /*a b c d e f g h i j k l m n o p q r s t u v w x y z*/


        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('A1', 'م');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('B1', 'المقر');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('C1', 'الرقم الوظيفي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('D1', 'الموظف');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('E1', 'تاريخ الترحيل');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('F1', 'شهر الراتب');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('G1', 'الراتب الاساسي');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('H1', 'عدد الساعات المخصومة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('I1', 'عدد ايام الخصم المعتمدة');
        $spreadsheet->getActiveSheet()->setRightToLeft(true)->setCellValue('J1', 'قيمة الاستقطاع');



        $from = "A1"; // or any value
        $to = "J1"; // or any value
        $spreadsheet->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );

        // Set auto size and
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);


        $count = 1;
        $rows = 2;
        foreach ($excelData as $val) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $rows, $count++)
                ->setCellValue('B' . $rows, $val['BRANCH_NAME'])
                ->setCellValue('C' . $rows, $val['EMP_NO'])
                ->setCellValue('D' . $rows, $val['EMP_NAME'])
                ->setCellValue('E' . $rows, $val['DATE_R'])
                ->setCellValue('F' . $rows, $val['MONTH'])
                ->setCellValue('G' . $rows, $val['BASIC_SAL'])
                ->setCellValue('H' . $rows, $val['CALCULATED_HOURS'])
                ->setCellValue('I' . $rows, $val['DAY'])
                ->setCellValue('J' . $rows, number_format($val['TOTAL_DEDUCTION'],2))
            ;
            $rows++;
        }
        //php 7 call
        $object_writer = new Xlsx($spreadsheet);
        date_default_timezone_set('Asia/Gaza');
        $filename = "كشف التأخير الصباحي المعتمد مالياً للصرف" . date('d/m/y', time()) . ".xlsx";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=$filename");
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $object_writer->save('php://output');
    }

    /**********اعتماد التأخير الصباحي********/
    function public_adopt()
    {
        $adopt_no = $this->input->post('pp_ser');
        $is_active = $this->input->post('is_active');
        $month_act_sal = $this->input->post('month_act_sal');  //راتب شهر
        $note = $this->input->post('note');
        foreach ($adopt_no as $adopt_add) {
            $ret = $this->rmodel->update('DELAY_SALARY_ADOPT', $this->_postedData_adopt($adopt_add,$is_active,$month_act_sal, $note));
        }
        if (intval($ret) <= 0) {
            $this->print_error($ret);
        }
        echo 1;
    }

    /****************الغاء اعتماد التأخير الصباحي****************/
    function public_unadopt()
    {
        $adopt_no = $this->input->post('pp_ser');
        $is_active = $this->input->post('is_active');
        $note = $this->input->post('note');
        foreach ($adopt_no as $adopt_add) {
            $ret = $this->rmodel->update('DELAY_SALARY_UNADOPT', $this->_postedData_unadopt($adopt_add,$is_active,$note));
        }
        if (intval($ret) <= 0) {
            echo $ret ;
        }
        echo 1;
    }



    function _postedData_adopt($adopt_add, $is_active,$month_act_sal, $note)
    {
        $result = array(
            array('name' => 'P_SER_IN', 'value' => $adopt_add, 'type' => '', 'length' => -1),
            array('name' => 'IS_ACTIVE_IN', 'value' => $is_active, 'type' => '', 'length' => -1),
            array('name' => 'MONTH_ACT_IN', 'value' => $month_act_sal, 'type' => '', 'length' => -1), //راتب شهر
            array('name' => 'NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),
        );
        return $result;
    }

    function _postedData_unadopt($adopt_add,$is_active,$note)
    {
        $result = array(
            array('name' => 'P_SER_IN', 'value' => $adopt_add, 'type' => '', 'length' => -1),
            array('name' => 'IS_ACTIVE_IN', 'value' => $is_active, 'type' => '', 'length' => -1), //شهر التاخير
            array('name' => 'NOTE_IN', 'value' => $note, 'type' => '', 'length' => -1),

        );
        return $result;
    }

    public function public_get_adopt_detail()
    {
        $emp_no = $this->input->post('emp_no');
        $month = $this->input->post('month');
        $rertMainAdopt =$this->rmodel->getTwoColum('TRANSACTION_PKG', 'DELAY_EMP_ADOPT_TB_GET', $emp_no,$month);
        $html = '<div class="container"> <div class="tr_border">
                            <div class="table-responsive">
                                <table class="table  table-bordered" id="adopt_detail_tb">
                                <thead class="table-light">
                                <tr>
                                      <th>الجهة المعتمدة</th>
                                      <th>اسم المعتمد</th>
                                      <th>تاريخ الاعتماد</th>
                                      <th>ملاحظة الاعتماد</th>
                                      <th>الحركة</th>
                                </tr>
                                </thead>
                                <tbody>';
        foreach ($rertMainAdopt as $rows) {
            $html .= '<tr>
                                <td>'.$rows['ADOPT_NAME'].'</td>
                                <td>'.$rows['ADOPT_USER_NAME'].'</td>
                                <td>'.$rows['ADOPT_DATE_TIME'].'</td>
                                <td>'.$rows['NOTE'].'</td>
                                <td>'.$rows['STATUS_NAME'].'</td>
                       </tr>';
        }
        $html .= '</tbody></table></div></div>';
        echo $html;
    }

    function _look_ups(&$data)
    {

        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('settings/constant_details_model');
        $data['adopt_cons'] = $this->constant_details_model->get_list(440);
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
    }

    function get_sum_coloum($coloum_name,$alias_name,$tb_name,$where_sql)
    {
        //$cursor = $this->db->get_cursor();
        $params = array(
            array('name' => ':COLOUM_NAME', 'value' => $coloum_name, 'type' => '', 'length' => -1),
            array('name' => ':ALIAS_NAME', 'value' => $alias_name, 'type' => '', 'length' => -1),
            array('name' => ':TB_NAME', 'value' => $tb_name, 'type' => '', 'length' => -1),
            array('name' => ':WHERE_SQL', 'value' => $where_sql, 'type' => '', 'length' => -1),
            array('name'=> ':REF_CURSOR_OUT', 'value'=> 'CUR_RES', 'type'=> 'cursor'),
            array('name' => ':MSG_OUT', 'value' => 'MSG_OUT', 'type' => SQLT_CHR, 'length' => -1)
        );
        $result = $this->conn->excuteProcedures('TRANSACTION_PKG', 'GET_SUM_COLOUM', $params);

        return $result['CUR_RES'];
    }
}