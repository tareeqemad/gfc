<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 20/02/2020
 * Time: 08:39 ص
 */
class bouns_equivalent_adopt extends MY_Controller {
    var $PKG_NAME = "TRANSACTION_PKG";
    var $MODEL_NAME= 'bouns_equivalent_model';
    var $PAGE_URL= 'payroll_data/bouns_equivalent_adopt/get_page';
    var $PAGE_ACT;

    function __construct()
    {
        parent::__construct();

        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRANSACTION_PKG';
        //this for constant using
        $this->load->model('stores/store_committees_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model("stores/receipt_class_input_group_model");
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');


        $this->rmodel->package = 'TRANSACTION_PKG';

        $this->branch_no= $this->input->post('branch_no');
        $this->branch_default= $this->input->post('branch_no');
        $this->month= $this->input->post('month');
        $this->emp_no= $this->input->post('emp_no');
        $this->agree_ma= $this->input->post('agree_ma');
        $this->p_ser= $this->input->post('p_ser');
        $this->adopt_no= $this->input->post('adopt_no');
        $this->adopt_stage= $this->input->post('adopt_stage');



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
        /*********************************اللجنة*******************************************/
        $this->committee_case= $this->input->post('committee_case');
        $this->committee_note= $this->input->post('committee_note');

     }
    function index($page= 1, $branch_no= -1,$branch_default = -1 ,$month= -1, $emp_no= -1,$adopt_stage = -1,$head_department =-1,$w_no = -1,$emp_type= -1){

        $data['title']='اعتماد - كشف المكافأت';

        $data['content'] = 'bouns_equivalent_adopt_index';
        $data['page']=$page;

        $data['branch_no']= $branch_no;
        $data['month']= $month;
        $data['emp_no']= $emp_no;



        $MODULE_NAME= 'payroll_data';
        $TB_NAME= 'bouns_equivalent_adopt';
        $url_555=  base_url("$MODULE_NAME/$TB_NAME/");
        if(1){
            if (HaveAccess($url_555.'ManagerAdopt_eq')){
                $adopt_stage = 3;
            }elseif (HaveAccess($url_555.'InternalObserver_eq')){
                $adopt_stage = 10 ;
            }
            elseif (HaveAccess($url_555.'FinicalDepart_eq')){
                $adopt_stage = 20 ;
            }
            else {
                $adopt_stage = -100;
            }
        }
        //مرحلة الاعتماد
        $data['adopt_stage']= $adopt_stage;
        //الدائرة
        $data['head_department']= $head_department;
        //طبيعة العمل
        $data['w_no']= $w_no;
        //نوع التعيين
        $data['emp_type']= $emp_type;
        $this->_lookup($data);
        $this->load->view('template/template',$data);
    }




    function get_page($page= 1, $branch_no= -1, $month= -1, $emp_no= -1, $adopt_stage= -1 , $head_department = -1 , $w_no = -1 ,$emp_type =-1){
        $this->load->library('pagination');

        $branch_no= $this->check_vars($branch_no,'branch_no');
        $month= $this->check_vars($month,'month');
        $emp_no= $this->check_vars($emp_no,'emp_no');
        $adopt_stage= $this->check_vars($adopt_stage,'adopt_stage');
        $head_department= $this->check_vars($head_department,'head_department');
        $w_no= $this->check_vars($w_no,'w_no');
        $emp_type= $this->check_vars($emp_type,'emp_type');

        $current_month = date('Ym') ;
        $where_sql = '';
        if(!$this->input->is_ajax_request()){
            $where_sql.= " and month= $current_month  ";
            $where_sql.= " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= {$this->user->branch}  ";
        }
        $where_sql.= " and M.AGREE_MA >= 3  ";
        $where_sql.= " and M.COMMITTEE_CASE= 1  ";
        $where_sql.= ($branch_no!= null)? " and EMP_PKG.GET_EMP_BRANCH(M.EMP_NO)= '{$branch_no}' " : '';
        $where_sql.= ($month!= null)? " and month= '{$month}' " : '';
        $where_sql.= ($emp_no!= null)? " and M.emp_no= '{$emp_no}' " : '';
        $where_sql.= ($adopt_stage!= null)? " and M.agree_ma= '{$adopt_stage}' " : '';
        $where_sql.= ($head_department!= null)? " and D.head_department= '{$head_department}' " : '';
        $where_sql.= ($w_no!= null)? " and D.w_no= '{$w_no}' " : '';
        $where_sql.= ($emp_type!= null)? " and D.emp_type= '{$emp_type}' " : '';

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
        $this->load->view('bouns_equivalent_adopt_page',$data);

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
    /****************************اعتماد********************************/
    function adopt()
    {
        $adopt_no= $this->input->post('ser');
        $agree_ma= $this->input->post('agree_ma');
        $note= $this->input->post('note');
        $x=0;
        foreach($adopt_no as $adopt_ser){
            $ret= $this->{$this->MODEL_NAME}->adopt($adopt_ser,$agree_ma,$note);
            if($ret==1) $x++;
        }
        if($x!=count($adopt_no)) {
            echo 'Error';
        } else
        {
            echo 1;
        }
    }
    /****************************جلب بيانات الاعتماد***************************/
     function public_get_adopt_detail(){
        $id = $this->input->post('ser');
        if(intval($id) > 0 ) {
            $ret = $this->{$this->MODEL_NAME}->get($id);
            echo  json_encode($ret);
        }
    }
    /*************الغاء الاعتماد*******************************************************/
    function unadopt()
    {
        $adopt_no= $this->input->post('ser');
        $agree_ma= $this->input->post('agree_ma');
        $note= $this->input->post('note');
        $x=0;
        foreach($adopt_no as $adopt_ser){
            $ret= $this->{$this->MODEL_NAME}->unadopt($adopt_ser,$agree_ma,$note);
            if($ret==1) $x++;
        }
        if($x!=count($adopt_no)) {
            echo 'Error';
        } else
        {
            echo 1;
        }
    }
    /********************************************************************************************/
    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['adopt_cons'] = $this->constant_details_model->get_list(322);
        $data['type_reward'] = $this->constant_details_model->get_list(320);
        $data['committee_case'] = $this->constant_details_model->get_list(321);
        $this->load->model('salary/constants_sal_model');
        $data['w_no_cons'] = $this->constants_sal_model->get_list(12);
        $data['w_no_admin_cons'] = $this->constants_sal_model->get_list(14);
        $data['emp_type_cons'] = $this->constants_sal_model->get_list(21);
        $data['bran_cons'] = $this->constants_sal_model->get_list(5);
        $data['head_department_cons'] = $this->constants_sal_model->get_list(7);
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no ,'hr_admin');
        $data['help'] = $this->help;
    }
}
