<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 03/04/23
 * Time: 09:00 ص
 */

class Pull_billing_data extends MY_Controller {

    var $MODEL_NAME= 'Pull_billing_data_model';
    var $PAGE_URL= 'salary/Pull_billing_data/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('hr_attendance/hr_attendance_model');
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'SALARY_EMP_PKG';

        $this->ser = $this->input->post('ser');
        $this->emp_no = $this->input->post('emp_no');
        $this->bill_amount = $this->input->post('bill_amount');
        $this->the_month = $this->input->post('the_month');
        $this->emp_id = $this->input->post('emp_id');
        $this->emp_name = $this->input->post('emp_name');
        $this->bill_no = $this->input->post('bill_no');
        $this->category = $this->input->post('category');
        $this->category_amount = $this->input->post('category_amount');
        $this->program_name = $this->input->post('program_name');
        $this->approved_cost = $this->input->post('approved_cost');
        $this->status = $this->input->post('status');
        $this->branch_no = $this->input->post('branch_no');

    }

    function index()
    {
        $data['content']='pull_billing_data_index';
        $data['title']='سحب بيانات الفواتير';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= "where 1 = 1";

        $where_sql.= ($this->the_month!= null)? "and M.THE_MONTH= '{$this->the_month}' " : '';
        $where_sql.= ($this->emp_no!= null)? "and M.EMP_NO= '{$this->emp_no}' " : '';
        $where_sql.= ($this->branch_no!= null)? "AND EMP_PKG.GET_EMP_BRANCH (M.EMP_NO) = '{$this->branch_no}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('BILLING_DATA_TB M '.$where_sql);
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
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('pull_billing_data_page',$data);
    }

    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
            $this->_post_validation(true);

            $items = array();
            for ($x = 0; $x < count($this->emp_no); $x++) {
                $items[] = $this->emp_no[$x];
            }

            $array = $items;
            $counts = array_count_values($array);
            foreach ($counts as $key => $count) {
                if ($count > 1) {
                    echo $this->print_error('هناك طلبات مكررة ، الرجاء التأكد من البيانات ');
                }
            }

            for($i=0; $i<count($this->emp_no); $i++){
                if($this->ser[$i]== 0 and $this->emp_no[$i]!='' and $this->bill_amount[$i]!=''  and $this->the_month != '' and $this->emp_id[$i] != '' and $this->emp_name[$i] != '' and $this->bill_no[$i] != '' and $this->category[$i] != '' and $this->category_amount[$i] != '' and $this->program_name[$i] != '' and $this->approved_cost[$i] != '' and $this->status[$i] != ''){ // create
                    $req_seq = $this->{$this->MODEL_NAME}->create($this->_postedData(null ,$this->emp_no[$i],$this->bill_amount[$i],$this->the_month ,$this->emp_id[$i] ,$this->emp_name[$i] ,$this->bill_no[$i] ,$this->category[$i] ,$this->category_amount[$i] ,$this->program_name[$i],$this->approved_cost[$i],$this->status[$i] ,'create'));
                    if(intval($req_seq) <= 0){
                        $this->print_error($req_seq);
                    }
                }
            }
            echo $this->the_month;

        }else{
            $data['content']='pull_billing_data_show';
            $data['title']='سحب بيانات الفواتير';
            $data['action'] = 'index';
            $data['isCreate']= true;
            $this->_look_ups($data);
            $this->load->view('template/template1',$data);
        }
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
           $this->_post_validation(true);

            $items = array();
            for ($x = 0; $x < count($this->emp_no); $x++) {
                $items[] = $this->emp_no[$x];
            }

            $array = $items;
            $counts = array_count_values($array);
            foreach ($counts as $key => $count) {
                if ($count > 1) {
                    echo $this->print_error('هناك طلبات مكررة ، الرجاء التأكد من البيانات ');
                }
            }

            for ($i = 0; $i < count($this->emp_no); $i++) {
                if ($this->ser[$i]== 0  and $this->bill_amount[$i]!=''  and $this->the_month != '' and $this->emp_id[$i] != '' and $this->emp_name[$i] != '' and $this->bill_no[$i] != '' and $this->category[$i] != '' and $this->category_amount[$i] != '' and $this->program_name[$i] != '' and $this->approved_cost[$i] != '' and $this->status[$i] != '') { // create
                    $req_seq = $this->{$this->MODEL_NAME}->create($this->_postedData(null ,$this->emp_no[$i],$this->bill_amount[$i],$this->the_month ,$this->emp_id[$i] ,$this->emp_name[$i] ,$this->bill_no[$i] ,$this->category[$i] ,$this->category_amount[$i] ,$this->program_name[$i],$this->approved_cost[$i],$this->status[$i] ,'create'));

                    if (intval($req_seq) <= 0) {
                        $this->print_error($req_seq);
                    }
                } elseif ($this->ser[$i] != 0 and $this->bill_amount[$i]!=''  and $this->the_month != '' and $this->emp_id[$i] != '' and $this->emp_name[$i] != '' and $this->bill_no[$i] != '' and $this->category[$i] != '' and $this->category_amount[$i] != '' and $this->program_name[$i] != '' and $this->approved_cost[$i] != '' and $this->status[$i] != '') { // edit
                    $req_seq = $this->{$this->MODEL_NAME}->edit($this->_postedData($this->ser[$i] ,$this->emp_no[$i],$this->bill_amount[$i],$this->the_month ,$this->emp_id[$i] ,$this->emp_name[$i] ,$this->bill_no[$i] ,$this->category[$i] ,$this->category_amount[$i] ,$this->program_name[$i],$this->approved_cost[$i],$this->status[$i] ,'edit'));

                    if (intval($req_seq) <= 0) {
                        $this->print_error($req_seq);
                    }
                }
            }
           echo $this->the_month ;

        }
    }

    function get($month ){
        $result= $this->{$this->MODEL_NAME}->get($month );
        if((count($result)==0))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['content']='pull_billing_data_show';
        $data['title']='بيانات الفواتير';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }


    function trans_data()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $month = $this->input->post('month');
            $ChkCount = $this->{$this->MODEL_NAME}->get($month);
            if (count($ChkCount) > 0) {
                echo 'سجل موجود';
                return -1;
            } else {
                $data_arr = array(
                    array('name' => 'MONTH', 'value' => $month, 'type' => '', 'length' => -1),
                );
                $res = $this->rmodel->update('BILLING_DATA_TRANS', $data_arr);
                if (intval($res) >= 1) {
                    echo 1;
                } else {
                    $this->print_error('Error_' . $res);
                }
            }

        }
    }

    function _look_ups(&$data){

        $this->load->model('hr_attendance_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');

        $data['current_date'] = date("Ym");
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _post_validation($isEdit = false){

        for($i=0; $i<count($this->emp_no); $i++) {
            if ( $this->the_month == '' ){
                $this->print_error('يجب ادخال الشهر');
            }elseif($this->emp_no[$i] == '' ){
                $this->print_error('يجب ادخال الموظف..');
            }elseif($this->emp_id[$i] == '' ){
                $this->print_error('يجب ادخال رقم الهوية..');
            }elseif($this->emp_name[$i] == '' ){
                $this->print_error('يجب ادخال اسم الموظف..');
            }elseif($this->bill_no[$i] == '' ){
                $this->print_error('يجب ادخال رقم الفاتورة..');
            }elseif($this->category[$i] == '' ){
                $this->print_error('يجب ادخال الفئة..');
            }elseif($this->category_amount[$i] == '' ){
                $this->print_error('يجب ادخال مبلغ الفئة..');
            }elseif($this->bill_amount[$i] == '' ){
                $this->print_error('يجب ادخال مبلغ الفاتورة..');
            }elseif($this->program_name[$i] == '' ){
                $this->print_error('يجب ادخال اسم البرنامج..');
            }elseif($this->approved_cost[$i] == '' ){
                $this->print_error('يجب ادخال التكلفة المعتمدة..');
            }elseif($this->status[$i] == '' ){
                $this->print_error('يجب ادخال الحالة..');
            }
        }

    }

    private function adopt($case){

        $res = $this->{$this->MODEL_NAME}->adopt($this->the_month , $case);

        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and  $this->the_month !='' ){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ser = $this->input->post('ser');
            echo $this->{$this->MODEL_NAME}->delete($ser);
        }
    }

    function _postedData( $ser = null ,$emp_no = null ,$bill_amount = null ,$the_month = null ,$emp_id = null ,$emp_name = null  ,$bill_no =null,$category =null,$category_amount =null,$program_name =null,$approved_cost =null,$status =null,$typ= null){
        $result = array(
            array('name'=>'SER','value'=> $ser,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=> $emp_no,'type'=>'','length'=>-1),
            array('name'=>'BILL_AMOUNT','value'=>$bill_amount ,'type'=>'','length'=>-1),
            array('name'=>'THE_MONTH','value'=> $the_month,'type'=>'','length'=>-1),
            array('name'=>'EMP_ID','value'=>$emp_id ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NAME','value'=>$emp_name ,'type'=>'','length'=>-1),
            array('name'=>'BILL_NO','value'=>$bill_no ,'type'=>'','length'=>-1),
            array('name'=>'CATEGORY','value'=>$category ,'type'=>'','length'=>-1),
            array('name'=>'CATEGORY_AMOUNT','value'=>$category_amount ,'type'=>'','length'=>-1),
            array('name'=>'PROGRAM_NAME','value'=>$program_name ,'type'=>'','length'=>-1),
            array('name'=>'APPROVED_COST','value'=>$approved_cost ,'type'=>'','length'=>-1),
            array('name'=>'STATUS','value'=>$status ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            array_shift($result);
        return $result;
    }
}