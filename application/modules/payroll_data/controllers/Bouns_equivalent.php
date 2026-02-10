<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 10/03/2020
 * Time: 12:43 م
 */
class bouns_equivalent extends MY_Controller {
    var $PKG_NAME = "TRANSACTION_PKG";
    var $MODEL_NAME= 'bouns_equivalent_model';
    var $PAGE_URL= 'payroll_data/bouns_equivalent/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';
        //this for constant using
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('stores/store_committees_model');
        $this->load->model('stores/store_members_model');
        $this->load->model('stores/receipt_class_input_group_model');


        $this->rmodel->package = 'TRANSACTION_PKG';


        $this->ser= $this->input->post('ser');
        $this->branch_no= $this->input->post('branch_no');
        $this->month= $this->input->post('month');
        $this->emp_no= $this->input->post('emp_no');
        $this->agree_ma= $this->input->post('agree_ma');
        $this->adopt_no= $this->input->post('adopt_no');
         //الدائرة
        $this->head_department= $this->input->post('head_department');
        //طبيعة العمل
        $this->w_no= $this->input->post('w_no');
        //نوع التعيين
        $this->emp_type= $this->input->post('emp_type');
        /*****************************Request Parameter*****************************/
        $this->emp_no= $this->input->post('emp_no');
        $this->type_reward= $this->input->post('type_reward');
        $this->value_ma= $this->input->post('value_ma');
        $this->work_detail= $this->input->post('work_detail');
        $this->committee_no= $this->input->post('committee_no');
        /*********************************اللجنة*******************************************/
        $this->committee_case = $this->input->post('committee_case');
        $this->committee_note = $this->input->post('committee_note');

        //Group
        $this->group_person_id= $this->input->post('group_person_id');
        $this->group_person_date= $this->input->post('group_person_date');
        $this->g_ser= $this->input->post('h_group_ser');
        $this->emp_no= $this->input->post('emp_no');
        $this->status= $this->input->post('status');
        $this->member_note= $this->input->post('member_note');

    }

    function index($page= 1, $branch_no= -1 ,$month= -1, $emp_no= -1,$committee_case = -1,$head_department =-1,$w_no = -1,$emp_type= -1,$committee_no = -1 ,$agree_ma = -1 ){

        $data['title'] = 'كشف طلبات المكافأت';
        $data['content'] = 'bouns_equivalent_index';
        $data['page']=$page;
        $data['branch_no']= $branch_no;
        $data['month']= $month;
        $data['emp_no']= $emp_no;
        $data['agree_ma']= $agree_ma;
        $data['committee_no']= $committee_no;
        $data['committee_case']= $committee_case;
        //الدائرة
        $data['head_department']= $head_department;
        //طبيعة العمل
        $data['w_no']= $w_no;
        //نوع التعيين
        $data['emp_type']= $emp_type;
        $this->_lookup($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $branch_no= -1, $month= -1, $emp_no= -1, $committee_case= -1 , $head_department = -1 , $w_no = -1 ,$emp_type =-1,$committee_no = -1,$agree_ma = -1){
        $this->load->library('pagination');

        $branch_no= $this->check_vars($branch_no,'branch_no');
        $month= $this->check_vars($month,'month');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $committee_case= $this->check_vars($committee_case,'committee_case');
        $head_department= $this->check_vars($head_department,'head_department');
        $w_no= $this->check_vars($w_no,'w_no');
        $emp_type= $this->check_vars($emp_type,'emp_type');
        $agree_ma= $this->check_vars($agree_ma,'agree_ma');

        $current_month = date('Ym') ;
        $where_sql = '';

        if(!$this->input->is_ajax_request()){
            $where_sql.= " and month= $current_month  ";
            $where_sql.= " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= {$this->user->branch}  ";
        }


        $where_sql.= ($branch_no!= null)? " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$branch_no}' " : '';
        $where_sql.= ($month!= null)? " and month= '{$month}' " : '';
        $where_sql.= ($emp_no!= null)? " and M.emp_no= '{$emp_no}' " : '';
        $where_sql.= ($committee_case!= null)? " and M.committee_case= '{$committee_case}' " : '';
        $where_sql.= ($head_department!= null)? " and D.head_department= '{$head_department}' " : '';
        $where_sql.= ($w_no!= null)? " and D.w_no= '{$w_no}' " : '';
        $where_sql.= ($emp_type!= null)? " and D.emp_type= '{$emp_type}' " : '';
        $where_sql.= ($agree_ma!= null)? " and M.agree_ma= '{$agree_ma}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' REWARD_REQUESTS_TB  M , DATA.EMPLOYEES D '.' where 1=1 '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page']=$page;
        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";
        $this->pagination->initialize($config);
        $offset = ((($page-1) * $config['per_page']) );
        $row = (($page * $config['per_page'])  );
        // echo $where_sql;
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );
        $data['offset']=$offset+1;
        $data['page']=$page;
        $this->load->view('bouns_equivalent_page',$data);

    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        if($c_var=='sex')
            $var= isset($this->{$c_var})? $this->{$c_var}:$var;
        else
            $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }


    function create()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->ser= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->ser) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->ser);
            }else{
                echo intval($this->ser);
            }
        }else{
            $data['title'] = 'اضافة طلب مكافأة ';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['content'] = 'bouns_equivalent_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);
        }

    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                echo 1;
            }
        }
    }

    //تحويل الطلب  الى اللجنة
    function transfer_comm(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $ser = $this->input->post('ser');
            $committee_no = $this->input->post('committee_no');
            $res= $this->{$this->MODEL_NAME}->transfer_comm($ser,$committee_no);
            if(intval($res) == 1 ){
                echo 1;
            }else {
                $this->print_error('Error_'.$res);
            }
        }
    }

   // اعتماد اللجنة
    private function adopt($case){
        $res = $this->{$this->MODEL_NAME}->adopt_comm($this->ser,$this->committee_case,$this->committee_note,$case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_2(){ // اعتماد اللجنة
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->ser!=''){
            echo $this->adopt(2);
        }else
            echo "لم يتم ارسال رقم السند";
    }


    //For table Group //Finish
    function public_edit_award_Grouptb()
    {
        //    print_r($this->g_ser);
         //  die;
        for($c = 0;$c<count($this->group_person_id);$c++) {
            $status = (isset($this->status[$c])) ? 1 : 2;
            //  echo $status."j";
            //    if ($this->group_person_id[$c]!='' ){

            if (intval($this->g_ser[$c]) == 0) {
                $f = $this->receipt_class_input_group_model->create($this->_postGroupsDataAward(null, $this->ser, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'create'));

                if (intval($f) <= 0) {
                    $this->print_error($f);
                }
            } else {
                $e = $this->receipt_class_input_group_model->edit($this->_postGroupsDataAward($this->g_ser[$c], $this->ser, $this->group_person_id[$c], $this->group_person_date[$c], $this->emp_no[$c], $status, $this->member_note[$c], 'edit'));
                if (intval($e) <= 0) {
                    $this->print_error($e);
                }
            }
        }
        echo "1" ;
    }


    function  _postGroupsDataAward($ser ,$id,$group_person_id,$group_person_date,$emp_no,$status,$member_note,$ty =null){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'RECEIPT_CLASS_INPUT_ID','value'=>$id ,'type'=>'','length'=>-1),
            array('name'=>'GROUP_PERSON_ID','value'=>$group_person_id,'type'=>'','length'=>-1),
            array('name'=>'GROUP_PERSON_DATE','value'=>$group_person_date,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$emp_no,'type'=>'','length'=>-1),
            array('name'=>'SOURCE','value'=>6,'type'=>'','length'=>-1),
            array('name'=>'STATUS','value'=>$status,'type'=>1,'length'=>-1),
            array('name'=>'MEMBER_NOTE','value'=>$member_note,'type'=>1,'length'=>-1)
        );
        if($ty == 'create'){
            array_shift($result);
        }
        // print_r ($result);
        return $result;
    }



    function public_get_group_receipt($id = 0){
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['rec_groups'] = $this->receipt_class_input_group_model->get_details_all($id,6);
        $this->load->view('reward_group_page',$data);
    }

    function _post_validation($isEdit = false){
        if( $this->emp_no ==''){
            $this->print_error('يجب ادخال رقم الموظف');
        }elseif ($this->type_reward =='') {
            $this->print_error('يجب ادخال نوع المكافأة');
        }elseif ($this->value_ma =='') {
            $this->print_error('يجب ادخال قيمة المكافأة');
        }elseif ($this->work_detail =='') {
            $this->print_error('يجب ادخال الأعمال');
        }elseif ($this->committee_no =='') {
            $this->print_error('يجب ادخال اللجنة المتابعة للطلب');
        }
    }

    function get($id)
    {

        $result = $this->rmodel->get('REWARD_REQUESTS_TB_GET', $id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['content']='bouns_equivalent_show';
        $data['title']='بيانات الطلب الخاص بالموظف  ';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SER','value'=>$this->ser ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$this->p_emp_no ,'type'=>'','length'=>-1),
            array('name'=>'MONTH','value'=>$this->p_month ,'type'=>'','length'=>-1),
            array('name'=>'TYPE_REWARD','value'=>$this->p_type_reward ,'type'=>'','length'=>-1),
            array('name'=>'NOTE','value'=>$this->p_note ,'type'=>'','length'=>-1),
            array('name'=>'VALUE_MA','value'=>$this->p_value_ma ,'type'=>'','length'=>-1),
            array('name'=>'WORK_DETAIL','value'=>$this->p_work_detail,'type'=>'','length'=>-1),
            array('name'=>'COMMITTEE_NO','value'=>$this->p_committee_no,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[0]);
        return $result;
    }
    /**********************************************************/
    function public_get_emp_data(){
        $no  = $this->input->post('no');
        if(intval($no) > 0 ) {
            $data = $this->rmodel->get('EMPLOYEE_DETAIL_GET', $no);
            echo  json_encode($data);
        }
    }

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $this->load->model('salary/constants_sal_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['adopt_cons'] = $this->constant_details_model->get_list(322);
        $data['type_reward'] = $this->constant_details_model->get_list(320);
        $data['committee_case'] = $this->constant_details_model->get_list(321);
        $this->load->model('salary/constants_sal_model');
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);
        $data['head_department_cons'] = $this->constants_sal_model->get_list(7);
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no ,'hr_admin');
        $data['class_input_class_type'] = $this->store_committees_model->get_all_by_type(6);

        $data['help'] = $this->help;
    }

}

