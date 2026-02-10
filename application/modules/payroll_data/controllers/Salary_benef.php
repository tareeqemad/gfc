<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/06/22
 * Time: 09:00 ص
 */
class Salary_benef extends MY_Controller {

    var $MODEL_NAME= 'Salary_benef_model';
    var $PAGE_URL= 'payroll_data/Salary_benef/get_page';
    var $DETAILS_MODEL_NAME = 'Salary_benef_detail_model';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DETAILS_MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';

        //Master
        $this->ser = $this->input->post('ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->badl_typ = $this->input->post('badl_typ');
        $this->con_no = $this->input->post('con_no');
        $this->is_taxed = $this->input->post('is_taxed');
        $this->txt_value = $this->input->post('txt_value');
        $this->from_month = $this->input->post('from_month');
        $this->to_month = $this->input->post('to_month');
        $this->adopt = $this->input->post('adopt');
        $this->note = $this->input->post('note');
        $this->get_case = $this->input->post('get_case');

        //Details
        $this->ser_det= $this->input->post('txt_bill_ser_det');
        $this->inst_month= $this->input->post('txt_inst_month');
        $this->install_value= $this->input->post('txt_install_value');
//        $this->note= $this->input->post('txt_bill_det_note');
        $this->emp_no_det= $this->input->post('emp_no_det');
        $this->number_inst= $this->input->post('number_inst');
        $this->calculation_type= $this->input->post('calculation_type');
        $this->txt_to_month= $this->input->post('txt_to_month');
        $this->txt_price_part= $this->input->post('txt_price_part');
    }

    function index()
    {
        $data['content']='salary_benef_index';
        $data['title']='طلبات الإستحقاق أوالإستقطاع';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = '';

        $where_sql .= isset($this->p_ser) && $this->p_ser != null ? " AND  M.SER = '{$this->p_ser}' " : "";
//        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  D.EMP_NO = '{$this->p_emp_no}' " : "";
//        $where_sql .= isset($this->p_branch_id) && $this->p_branch_id != null ? " AND  EMP_PKG.GET_EMP_BRANCH(D.EMP_NO) = '{$this->p_branch_id}' " : "";
        $where_sql .= isset($this->p_con_no) && $this->p_con_no != null ? " AND  M.BAND = '{$this->p_con_no}' " : "";
        $where_sql .= isset($this->p_badl_typ) && $this->p_badl_typ != null ? " AND  M.BADL_TYPE = '{$this->p_badl_typ}' " : "";
//        $where_sql .= isset($this->p_from_month) && $this->p_from_month != null ? " AND  D.INST_MONTH >= '{$this->p_from_month}' " : "";
//        $where_sql .= isset($this->p_to_month) && $this->p_to_month != null ? " AND  D.TO_MONTH <= '{$this->p_to_month}' " : "";
//        $where_sql .= isset($this->p_value) && $this->p_value != null ? " AND  D.INSTALL_VALUE $this->p_op {$this->p_value}  " : "";
        $where_sql .= isset($this->p_is_taxed) && $this->p_is_taxed != null ? " AND  M.IS_TAXED = '{$this->p_is_taxed}' " : "";
        $where_sql .= isset($this->p_adopt) && $this->p_adopt != null ? " AND  M.ADOPT = '{$this->p_adopt}' " : "";

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(" SALARY_BENEFITS_TB M  WHERE 1 = 1  {$where_sql}");
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
        $data["page_rows"] = $this->rmodel->getList('SALARY_BENEFITS_TB_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('salary_benef_page', $data);
    }

    function public_get_badl_type()
    {
        $badl_typ = $this->input->post('badl_typ');
        if (intval($badl_typ) > 0) {
            $res = $this->rmodel->get('BADL_TYPE_GET', $badl_typ);
            echo json_encode($res);
        }
    }

/*
    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);

            $Sub_Total_Bill = 0 ;
            for ($x = 0; $x < count($this->install_value); $x++) {
                $Sub_Total_Bill = $Sub_Total_Bill + $this->install_value[$x];
            }

            if($Sub_Total_Bill == $this->txt_value) {
                $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
                if(intval($this->ser) <= 0){
                    $this->print_error($this->ser);
                }else{
                    for($i=0; $i<count($this->inst_month); $i++){
                        if($this->ser_det[$i]== 0 and $this->inst_month[$i]!='' and $this->install_value[$i]> 0 ){ // create
                            $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->ser  ,$this->inst_month[$i], $this->install_value[$i], $this->note[$i],'' , $this->txt_to_month[$i] ,$this->number_inst[$i] ,$this->calculation_type[$i],'create'));
                            if(intval($detail_seq) <= 0){
                                $this->print_error_del($detail_seq);
                            }
                        }
                    }
                    echo intval($this->ser);
                }
            }else {
                if (count($this->inst_month) == 0){
                    $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
                    echo intval($this->ser);
                }else{
                    echo $this->print_error('القيمة المدخلة لا تطابق القيمة المطلوبة');
                }
            }
        }

        date_default_timezone_set('Asia/Jerusalem');
        $data['current_date'] = date("Ym");
        $data['content']='salary_benef_show';
        $data['title']='طلب إستحقاق أو إستقطاع';
        $data['action'] = 'index';
        $data['isCreate']= true;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);

            $Sub_Total_Bill = 0 ;
            for ($x = 0; $x < count($this->install_value); $x++) {
                $Sub_Total_Bill = $Sub_Total_Bill + $this->install_value[$x];
            }

            if($Sub_Total_Bill == $this->txt_value) {
                $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
                if (intval($res) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $res);
                } else{
                    for ($i = 0; $i < count($this->inst_month); $i++) {
                        if ($this->ser_det[$i] == 0 and $this->inst_month[$i] != '' and $this->install_value[$i] > 0) { // create
                            $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->ser, $this->inst_month[$i], $this->install_value[$i], $this->note[$i],'' , $this->txt_to_month[$i],$this->number_inst[$i] ,$this->calculation_type[$i], 'create'));
                            if (intval($detail_seq) <= 0) {
                                $this->print_error_del($detail_seq);
                            }
                        } elseif ($this->ser_det[$i] != 0 and $this->inst_month[$i] != '' and $this->install_value[$i] > 0) { // edit
                            $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser_det[$i], $this->ser, $this->inst_month[$i], $this->install_value[$i], $this->note[$i],'' , $this->txt_to_month[$i],$this->number_inst[$i] ,$this->calculation_type[$i], 'edit'));
                            if (intval($detail_seq) <= 0) {
                                $this->print_error($detail_seq);
                            }
                        }
                    }
                echo 1;
                }
            }else {
                if (count($this->inst_month) == 0){
                    $this->{$this->MODEL_NAME}->edit($this->_postedData());
                    echo intval($this->ser);
                }else{
                    echo $this->print_error('القيمة المدخلة لا تطابق القيمة المطلوبة');
                }
            }
        }
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        date_default_timezone_set('Asia/Jerusalem');
        $data['current_date'] = date("Ym");
        $data['content']='salary_benef_show';
        $data['title']='بيانات طلب الإستحقاق أوالإستقطاع ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }
*/

    function create(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);

            $items = array();
            for ($x = 0; $x < count($this->emp_no_det); $x++) {
                $items[] = $this->emp_no_det[$x];
            }

            $array = $items;
            $counts = array_count_values($array);
            foreach ($counts as $key => $count) {
                if ($count > 1) {
                    echo $this->print_error('الرجاء التأكد من ادخال الموظفين ، هناك موظفين مكررين ');
                }
            }

            $this->ser = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->ser) <= 0){
                $this->print_error($this->ser);
            }else{
                for($i=0; $i<count($this->emp_no_det); $i++){
                    if($this->ser_det[$i]== 0 and $this->inst_month[$i]!='' and $this->txt_to_month[$i]!=''  and $this->emp_no_det[$i] != '' and $this->calculation_type[$i] != 0 and $this->install_value[$i] != 0){ // create
                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->ser  ,$this->inst_month[$i], $this->install_value[$i],$this->emp_no_det[$i], $this->txt_to_month[$i] ,$this->number_inst[$i] ,$this->calculation_type[$i],$this->txt_price_part[$i], 'create'));
                        if(intval($detail_seq) <= 0){
                            $this->print_error_del($detail_seq);
                        }
                    }
                }
                echo intval($this->ser);
            }
        }

        $data['content']='salary_benef_show';
        $data['title']='طلب إستحقاق أو إستقطاع';
        $data['current_date'] = date("Ym");
        $data['action'] = 'index';
        $data['isCreate']= true;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);

            $items = array();
            for ($x = 0; $x < count($this->emp_no_det); $x++) {
                $items[] = $this->emp_no_det[$x];
            }

            $array = $items;
            $counts = array_count_values($array);
            foreach ($counts as $key => $count) {
                if ($count > 1) {
                    echo $this->print_error('الرجاء التأكد من ادخال الموظفين ، هناك موظفين مكررين ');
                }
            }

            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            }else{
                for ($i = 0; $i < count($this->emp_no_det); $i++) {
                    if ($this->ser_det[$i] == 0 and $this->inst_month[$i] != '' and $this->txt_to_month[$i]!=''  and $this->emp_no_det[$i] != '') { // create
                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $this->ser, $this->inst_month[$i], $this->install_value[$i],$this->emp_no_det[$i] , $this->txt_to_month[$i] ,$this->number_inst[$i] ,$this->calculation_type[$i],$this->txt_price_part[$i], 'create'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    } elseif ($this->ser_det[$i] != 0 and $this->inst_month[$i] != '' and $this->txt_to_month[$i]!=''  and $this->emp_no_det[$i] != '' ) { // edit
                        $detail_seq = $this->{$this->DETAILS_MODEL_NAME}->edit($this->_postedData_details($this->ser_det[$i], $this->ser, $this->inst_month[$i], $this->install_value[$i],$this->emp_no_det[$i], $this->txt_to_month[$i] ,$this->number_inst[$i] ,$this->calculation_type[$i],$this->txt_price_part[$i], 'edit'));
                        if (intval($detail_seq) <= 0) {
                            $this->print_error($detail_seq);
                        }
                    }
                }
                echo 1;
            }
        }
    }

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        date_default_timezone_set('Asia/Jerusalem');
        $data['current_date'] = date("Ym");
        $data['content']='salary_benef_show';
        $data['title']='بيانات طلب الإستحقاق أوالإستقطاع ';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo $this->{$this->DETAILS_MODEL_NAME}->delete($this->p_id);
        }
    }


    function public_adopt($id ,$case ,$note)
    {
        $res = $this->{$this->MODEL_NAME}->adopt($id, $case ,$note);
        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        echo 1;
    }

    function public_adopt_2(){
        $id = $this->input->post('ser');
        $note = $this->input->post('note');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){

            echo $this->public_adopt($id ,2 ,$note);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function public_adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            $id = $this->input->post('ser');
            $note = $this->input->post('note');
            echo $this->public_adopt($id ,10 ,$note);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function public_adopt_20(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            $id = $this->input->post('ser');
            $note = $this->input->post('note');
            echo $this->public_adopt($id ,20 ,$note );
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function public_adopt_30(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            $id = $this->input->post('ser');
            $note = $this->input->post('note');
            echo $this->public_adopt($id,30 ,$note);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function public_adopt_35(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            $id = $this->input->post('ser');
            $note = $this->input->post('note');
            echo $this->public_adopt($id ,35 ,$note);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function public_unadopt()
    {
        $id = $this->input->post('ser');
        $case = $this->input->post('case');
        $note = $this->input->post('note');

        $res = $this->{$this->MODEL_NAME}->unadopt($id, $case ,$note);
        if(intval($res) <= 0){
            return $res;
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        echo 1;
    }

    public function public_get_adopt_detail(){
        $id = $this->input->post('ser');
        if (intval($id) > 0) {
            $rertMainAdopt = $this->rmodel->get('SALARY_BENEFITS_TB_ADOPT_GET', $id);
            $html = '<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="taskSignal_tbl">
                                <thead class="table-active">
                                <tr>
                                      <th>الجهة المعتمدة</th>
                                      <th>اسم المعتمد</th>
                                      <th>تاريخ الإعتماد</th>
                                      <th>ملاحظة الإعتماد</th>
                                      <th>الجهة المرجعة</th>
                                      <th>اسم المرجع</th>
                                      <th>تاريخ الإرجاع</th>
                                      <th>ملاحظة الارجاع</th>
                                </tr>
                                </thead>
                                <tbody>';
            foreach ($rertMainAdopt as $rows) {
                $html .= '<tr>
                                <td>' . $rows['ADOPT_NAME'] . '</td>
                                <td>' . $rows['ADOPT_USER_NAME'] . '</td>
                                <td>' . $rows['ADOPT_DATE'] . '</td>
                                <td>' . $rows['NOTE'] . '</td>
                                <td>' . $rows['CLOSED_NAME'] . '</td>
                                <td>' . $rows['CLOSED_USER_NAME'] . '</td>
                                <td>' . $rows['CLOSED_DATE'] . '</td>
                                <td>' . $rows['CLOSED_NOTE'] . '</td>
                            </tr>';
            }
            $html .= '</tbody></table></div></div></div>';
            echo $html;
        }
    }

    //فحص عدد الاقساط
    function public_cntInstall()
    {
        $bill_ser = $this->input->post('bill_ser');
        $resultBill = $this->rmodel->getID('TRANSACTION_PKG', 'SALARY_BENEFITS_TB_CNT_INSTALL', $bill_ser);

        if (count($resultBill) == 0) {
            $cnt_install = 0;
            echo $cnt_install;
        } else {
            $cnt_install = $resultBill[0]['CNT_INSTALL'];
            echo $cnt_install;
        }
    }

    //لتصحيح صيغة التاريخ بالاشهر والسنوات
    function public_get_next_month(){
        $month = $this->input->post('month');
        $rertMain = $this->{$this->MODEL_NAME}->getIDCalc('TRANSACTION_PKG' , 'GET_NEX_MONTH_VAL' , $month);
        $next_month= array_slice($rertMain, 1, 1, true);
        echo reset($next_month);
    }

    //جلب قيمة البند عن طريق رقم البند
    function public_get_val_constant(){
        $value_in = $this->input->post('value');
        $rertMain = $this->{$this->MODEL_NAME}->getValue('TRANSACTION_PKG' , 'VAL_CONSTANT_GET' , $value_in);
        $value= array_slice($rertMain, 1, 1, true);
        echo reset($value);
    }


    //جلب تاريخ التقاعد عن طريق رقم الموظف
    function public_get_retirement_date(){
        $emp_no_in = $this->input->post('emp_no');
        $rertMain = $this->{$this->MODEL_NAME}->getRetirementDate('TRANSACTION_PKG' , 'RETIREMENT_DATE_GET' , $emp_no_in);
        $retirement_date= array_slice($rertMain, 1, 1, true);
        echo reset($retirement_date);
    }



    function public_get_details($id= 0,$adopt_det){
        $data['adopt_det'] = $adopt_det;
        $data['can_edit'] = 1;
        $data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_list($id);
        $data['current_date'] = date("Ym");
        $this->load->view('salary_benef_details',$data);
    }

    function _look_ups(&$data)
    {
        $data['data_cons'] = $this->rmodel->getData('CONSTANT_DATA_GET_ALL');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('salary/constants_sal_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
        $data['badl_typ_cons'] = $this->constant_details_model->get_list(343);
        $data['is_taxed_cons'] = $this->constant_details_model->get_list(344);
        $data['adopt_cons'] = $this->constants_sal_model->get_list(28);
        $data['calculation_type'] = $this->constants_sal_model->get_list(29);
        $data['unadopt_cons'] = $this->constants_sal_model->get_list(30);
        $data['emp_no_cons_options']='<option value="0">_________</option>';
        foreach ($data['emp_no_cons'] as $row) :
            $data['emp_no_cons_options']=$data['emp_no_cons_options'].'<option value="'.$row['EMP_NO'].'">'.$row['EMP_NO'] . ': ' . $row['EMP_NAME'].'</option>';
        endforeach;

        $data['calculation_type_options']='<option>_________</option>';
        foreach ($data['calculation_type'] as $row2) :
            $data['calculation_type_options']=$data['calculation_type_options'].'<option value="'.$row2['CON_NO'].'">' . $row2['CON_NAME'].'</option>';
        endforeach;
    }

    /*
    function _post_validation($isEdit = false){
        if( $this->emp_no==''){
            $this->print_error('يجب ادخال رقم الموظف');
        }elseif($this->badl_typ == 0 ){
            $this->print_error('يجب اختيار نوع البدل..');
        }elseif($this->con_no == 0 ){
            $this->print_error('يجب اختيار البند..');
        }elseif($this->is_taxed == '' ){
            $this->print_error('يجب اختيار نوع الضريبة..');
        }elseif($this->txt_value <= 0 || $this->txt_value == '' ){
            $this->print_error('يجب ادخال المبلغ..');
        }elseif($this->from_month > $this->to_month ){
            $this->print_error('الرجاء التأكد من  إدخال الشهور بالشكل الصحيح..');
        }
    }
*/

    function _post_validation($isEdit = false){

        if($this->badl_typ == 0 ){
            $this->print_error('يجب اختيار نوع البدل..');
        }elseif($this->con_no == 0 ){
            $this->print_error('يجب اختيار البند..');
        }elseif($this->is_taxed == '' ){
            $this->print_error('يجب اختيار نوع الضريبة..');
        }

        for($i=0; $i<count($this->emp_no_det); $i++) {
            if ( $this->emp_no_det[$i] == 0 ){
                $this->print_error('يجب ادخال اسم الموظف ');
            }elseif($this->calculation_type[$i] == 0 ){
                $this->print_error('يجب اختيار آلية الاحتساب..');
            }elseif($this->install_value[$i] == '' || $this->install_value[$i] <= 0 ){
                $this->print_error('الرجاء ادخال المبلغ..');
            }elseif($this->number_inst[$i] == '' || $this->number_inst[$i] <= 0 ){
                $this->print_error('الرجاء ادخال عدد الاقساط..');
            }elseif(fmod($this->number_inst[$i], 1) != 0 ){
                $this->print_error('الرجاء ادخال عدد الاقساط بالشكل الصحيح..');
            }elseif($this->inst_month[$i] > $this->txt_to_month[$i] || strlen($this->inst_month[$i]) < 6  || strlen($this->txt_to_month[$i]) < 6 ){
                $this->print_error('الرجاء التأكد من إدخال الشهور بالشكل الصحيح..');
            }
        }

    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER','value'=> $this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=> $this->emp_no,'type'=>'','length'=>-1),
            array('name'=>'FROM_MONTH','value'=> $this->from_month ,'type'=>'','length'=>-1),
            array('name'=>'TO_MONTH','value'=>$this->to_month,'type'=>'','length'=>-1),
            array('name'=>'BADL_TYPE','value'=> $this->badl_typ ,'type'=>'','length'=>-1),
            array('name'=>'BAND','value'=>$this->con_no ,'type'=>'','length'=>-1),
            array('name'=>'IS_TAXED','value'=>$this->is_taxed ,'type'=>'','length'=>-1),
            array('name'=>'VALUE','value'=>$this->txt_value ,'type'=>'','length'=>-1),
            array('name'=>'GET_CASE','value'=>$this->get_case ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }

    function _postedData_details($ser= null,$res = null, $inst_month = null, $install_value= null , $emp_no= null, $txt_to_month= null ,$number_inst= null ,$calculation_type= null ,$txt_price_part= null ,$typ= null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'REQUEST_NO','value'=>$res ,'type'=>'','length'=>-1),
            array('name'=>'INST_MONTH','value'=>$inst_month ,'type'=>'','length'=>-1),
            array('name'=>'INSTALL_VALUE','value'=>$install_value ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>'' ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$emp_no ,'type'=>'','length'=>-1),
            array('name'=>'TO_MONTH','value'=>$txt_to_month ,'type'=>'','length'=>-1),
            array('name'=>'NUMBER_INST','value'=>$number_inst ,'type'=>'','length'=>-1),
            array('name'=>'CALCULATION_TYPE','value'=>$calculation_type ,'type'=>'','length'=>-1),
            array('name'=>'PRICE_PART','value'=>$txt_price_part ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            array_shift($result);
        return $result;
    }
}