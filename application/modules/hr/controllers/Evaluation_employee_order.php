<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 17/07/16
 * Time: 08:53 ص
 */
class Evaluation_employee_order extends MY_Controller {
    var $MODEL_NAME= 'evaluation_emp_order_model';
    var $DET_MODEL_NAME= 'evaluation_emp_order_det_model';
    var $FORM_MODEL_NAME= 'evaluation_form_model';
    var $ORDER_MODEL_NAME= 'evaluation_order_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DET_MODEL_NAME);
        $this->load->model($this->FORM_MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('employees/employees_model');

        $this->EVALUATION_ORDER_SERIAL = $this->input->post('EVALUATION_ORDER_SERIAL');
        $this->EMP_NO_NAME = $this->input->post('EMP_NO_NAME');
        $this->EMP_MANAGER_ID_NAME = $this->input->post('EMP_MANAGER_ID_NAME');
        $this->EVALUATION_ORDER_ID = $this->input->post('EVALUATION_ORDER_ID');
        $this->EVALUATION_ORDER_MARKS = $this->input->post('EVALUATION_ORDER_MARKS');
        $this->EVAL_FORM = $this->input->post('EVAL_FORM');

        $this->load->model($this->ORDER_MODEL_NAME);
        $where_sql= " where status>= '2' and level_active >= '3' ";
        $this->order_row = $this->{$this->ORDER_MODEL_NAME}->get_list($where_sql, 0, 10);
        if(count($this->order_row) != 1)
            $this->res= '';
        else
            $this->res=$this->order_row[0]['CONDITIONS'];
    }
    function index($EVALUATION_ORDER_SERIAL= -1, $EMP_NO_NAME= -1, $EMP_MANAGER_ID_NAME= -1 , $EVALUATION_ORDER_ID= -1, $EVALUATION_ORDER_MARKS=-1, $EVAL_FORM=-1){
        $data['content']='evaluation_emp_order_audit_index';
        $data['title']='طلبات التدقيق';
        add_js('jquery.hotkeys.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        /// GET Params And Pass To View
        $data['EVALUATION_ORDER_SERIAL']=$EVALUATION_ORDER_SERIAL;
        $data['EMP_NO_NAME']= $EMP_NO_NAME;
        $data['EMP_MANAGER_ID_NAME']= $EMP_MANAGER_ID_NAME;
        $data['EVALUATION_ORDER_ID']= $EVALUATION_ORDER_ID;
        $data['EVALUATION_ORDER_MARKS']= $EVALUATION_ORDER_MARKS;
        $data['EVAL_FORM']= $EVAL_FORM;
        $data['effective_order']=$this->res;

        ///مراتب التقدير//
        $data["grad_form"]=  $this->constant_details_model->get_list(123);
        /////
        $data['form_row'] = $this->{$this->FORM_MODEL_NAME}->get_all();
        /////

        $data["employee"]=  $this->employees_model->get_all();
        $data["manager"]=  $this->employees_model->get_all();
        $this->load->view('template/template',$data);
    }
    function get_page($EVALUATION_ORDER_SERIAL= -1, $EMP_NO_NAME= -1, $EMP_MANAGER_ID_NAME= -1 , $EVALUATION_ORDER_ID= -1, $EVALUATION_ORDER_MARKS=-1, $EVAL_FORM=-1){

        $EVALUATION_ORDER_SERIAL= $this->check_vars($EVALUATION_ORDER_SERIAL,'EVALUATION_ORDER_SERIAL');
        $EMP_NO_NAME= $this->check_vars($EMP_NO_NAME,'EMP_NO_NAME');
        $EMP_MANAGER_ID_NAME= $this->check_vars($EMP_MANAGER_ID_NAME,'EMP_MANAGER_ID_NAME');
        $EVALUATION_ORDER_ID= $this->check_vars($EVALUATION_ORDER_ID,'EVALUATION_ORDER_ID');
        $EVALUATION_ORDER_MARKS= $this->check_vars($EVALUATION_ORDER_MARKS,'EVALUATION_ORDER_MARKS');
        $EVAL_FORM= $this->check_vars($EVAL_FORM,'EVAL_FORM');

        $where_sql = ' and V.adopt=2 ';
        $default_where_sql= $where_sql;
        $where_sql.= ($EVALUATION_ORDER_SERIAL!= null)? " and V.EVALUATION_ORDER_SERIAL= '{$EVALUATION_ORDER_SERIAL}' " : '';
        $where_sql.= ($EMP_NO_NAME!= null)? " and V.EMP_NO= '{$EMP_NO_NAME}' " : '';
        $where_sql.= ($EMP_MANAGER_ID_NAME!= null)? " and V.EMP_MANAGER_ID= '{$EMP_MANAGER_ID_NAME}' " : '';
        $where_sql.= ($EVALUATION_ORDER_ID!= null)? " and V.EVALUATION_ORDER_ID= '{$EVALUATION_ORDER_ID}' " : '';
        $where_sql.= ($EVALUATION_ORDER_MARKS!= null)? " and HR_PKG.GET_DEGREE_ID(MARK_SUM)= '{$EVALUATION_ORDER_MARKS}' " : '';
        $where_sql.= ($EVAL_FORM!= null)? " and V.EVAL_FROM_ID= '{$EVAL_FORM}' " : '';

        if( !$this->input->is_ajax_request() ){
            $audit_where_sql= " and ( HR_PKG.GET_DEGREE_ID(MARK_SUM) in (1,5) or opinion=2 or HR_PKG.check_auto_evaluate(v.emp_no,v.evaluation_order_id)=1 ) ";
            $default_where_sql.= $audit_where_sql;
            $where_sql= $default_where_sql;
        }

        //echo $where_sql;
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_Audit_List($where_sql);
        $this->load->view('evaluation_emp_order_audit_page',$data);
    }
    public function get($EVALUATION_ORDER_SERIAL= -1, $EMP_NO_NAME= -1, $EMP_MANAGER_ID_NAME= -1 , $EVALUATION_ORDER_ID= -1, $EVAL_FORM=-1){
        $data['content']='evaluation_emp_order_objection';
        $data['title']='تظلمات الموظفين';
        add_js('jquery.hotkeys.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data["employee"]=  $this->employees_model->get_all();
        $data["manager"]=  $this->employees_model->get_all();
        $data['form_row'] = $this->{$this->FORM_MODEL_NAME}->get_all();
        /////

        /// GET Params And Pass To View
        $data['EVALUATION_ORDER_SERIAL']=$EVALUATION_ORDER_SERIAL;
        $data['EMP_NO_NAME']= $EMP_NO_NAME;
        $data['EMP_MANAGER_ID_NAME']= $EMP_MANAGER_ID_NAME;
        $data['EVALUATION_ORDER_ID']= $EVALUATION_ORDER_ID;
        $data['EVAL_FORM']= $EVAL_FORM;

        $this->load->view('template/template',$data);
    }
    public function get_objection($EVALUATION_ORDER_SERIAL= -1, $EMP_NO_NAME= -1, $EMP_MANAGER_ID_NAME= -1 , $EVALUATION_ORDER_ID= -1, $EVAL_FORM=-1){
        /// GET Params And Pass To View
        $EVALUATION_ORDER_SERIAL= $this->check_vars($EVALUATION_ORDER_SERIAL,'EVALUATION_ORDER_SERIAL');
        $EMP_NO_NAME= $this->check_vars($EMP_NO_NAME,'EMP_NO_NAME');
        $EMP_MANAGER_ID_NAME= $this->check_vars($EMP_MANAGER_ID_NAME,'EMP_MANAGER_ID_NAME');
        $EVALUATION_ORDER_ID= $this->check_vars($EVALUATION_ORDER_ID,'EVALUATION_ORDER_ID');
        $EVAL_FORM= $this->check_vars($EVAL_FORM,'EVAL_FORM');

        $where_sql = 'and 1=1';
        $where_sql.= ($EVALUATION_ORDER_SERIAL!= null)? " and M.EVALUATION_ORDER_SERIAL= '{$EVALUATION_ORDER_SERIAL}' " : '';
        $where_sql.= ($EMP_NO_NAME!= null)? " and M.EMP_NO= '{$EMP_NO_NAME}' " : '';
        $where_sql.= ($EMP_MANAGER_ID_NAME!= null)? " and M.EMP_MANAGER_ID= '{$EMP_MANAGER_ID_NAME}' " : '';
        $where_sql.= ($EVALUATION_ORDER_ID!= null)? " and M.EVALUATION_ORDER_ID= '{$EVALUATION_ORDER_ID}' " : '';
        $where_sql.= ($EVAL_FORM!= null)? " and D.EVAL_FROM_ID= '{$EVAL_FORM}' " : '';

        //echo $where_sql;
        $data['result'] = $this->{$this->MODEL_NAME}->get_objection($where_sql);
        $this->load->view('evaluation_emp_order_objection_page',$data);
    }
    public function get_emp_grad($branch=''){
        $this->load->model('settings/gcc_branches_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['branch']= $branch;

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_count_marke($branch);
        $data['effective_order']=$this->res;
        $data['content']='evaluation_emp_order_grad';
        $data['title']='احصائيات مراتب تقييم الأداء';
        $this->load->view('template/template',$data);
    }
    /* Test Function Return Evaluation Where User Log  */
    function getEvalULog(){
        $ret = $this->{$this->MODEL_NAME}->get_eval_user();
        if(count($ret) ==1){
            $serial =  $ret[0]['EVALUATION_ORDER_SERIAL'];
            redirect('hr/evaluation_emp_order/get/'.$serial.'/me');
        }else{
            die('Error ORDER SERIAL');
        }
    }
    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }
    function edit_audit_marke(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            for($i=0; $i<count($this->ser); $i++){
                if($this->ser[$i]!=null and $this->element_id[$i]!=null and $this->mark[$i]!=null and $this->hint[$i]!=null){
                    $res= $this->{$this->DET_MODEL_NAME}->edit($this->edit_audit_marke($this->ser[$i],$this->element_id[$i],$this->mark[$i],$this->hint[$i]));
                    if(intval($res) <= 0){
                        $this->print_error($res);
                    }
                }
            }
            echo intval($res);
        }
    }

    function public_get_emps(){
        $id = $this->input->post('id');
        $branch = $this->input->post('branch');
        $result = $this->{$this->MODEL_NAME}->get_emps_list($id, $branch);
        $this->return_json($result);
    }
}
?>