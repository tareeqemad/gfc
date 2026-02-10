<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 10/07/16
 * Time: 12:44 م
 */

class Evaluation_emp_order extends MY_Controller {
    var $MODEL_NAME= 'evaluation_emp_order_model';
    var $DET_MODEL_NAME= 'evaluation_emp_order_det_model';
    var $AXES_MARKS_MODEL_NAME= 'evaluation_form_axes_marks_model';
    var $ORDER_MODEL_NAME= 'evaluation_order_model';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->DET_MODEL_NAME);
        $this->load->model($this->ORDER_MODEL_NAME);
        $this->load->model('employees/employees_model');
        $this->load->model('settings/constant_details_model');
        // master vars
        $this->evaluation_order_serial= $this->input->post('evaluation_order_serial');
        $this->eval_extra_order_serial= $this->input->post('eval_extra_order_serial'); // brothers and grandson sers..
        $this->evaluation_order_id= $this->input->post('evaluation_order_id');
        $this->emp_no= $this->input->post('emp_no');
        $this->emp_manager_no= $this->input->post('manager_no');
        $this->excellent_note= $this->input->post('excellent_edependency');
        $this->manager_note= $this->input->post('manager_note');
        $this->emp_transfer= $this->input->post('emp_transfer');

        $this->opinion= $this->input->post('opinion');
        $this->opinion_reason= $this->input->post('opinion_reason');

        // detail vars
        $this->ser= $this->input->post('ser');
        $this->eval_form_id= $this->input->post('eval_form_id');
        $this->eval_form_type= $this->input->post('eval_form_type');
        $this->efa_id= $this->input->post('efa_id');
        $this->element_id= $this->input->post('element_id');
        $this->mark= $this->input->post('mark');
        $this->objec_hint= $this->input->post('objection_hint');
        $this->gr_objection_hint= $this->input->post('gr_objection_hint');
        $this->audit_hint= $this->input->post('audit_hint');
        $this->old_mark_calc= $this->input->post('old_mark_calc');
        $this->course_ser= $this->input->post('course_ser');
        $this->course_id= $this->input->post('course_id');
    }


    function get($id,$action=null){
        $objection_url= base_url("hr/evaluation_employee_order/get");
        $audit_url= base_url("hr/evaluation_employee_order/index");
        $archive_url= base_url("hr/evaluation_order_archives/index");
        $show_before_end_url= base_url("hr/evaluation_order_archives/show_before_end");
        $admin_manager= base_url("hr/evaluation_order_archives/admin_manager");
        $obj=0;
        $data['result'] = $this->{$this->MODEL_NAME}->get($id);
        $eval_order_id= $data['result'][0]['EVALUATION_ORDER_ID'];
        $data['result_order'] = $this->{$this->ORDER_MODEL_NAME}->get($eval_order_id);
        if(count($data['result'])!=1)
            die('get');
        $res_ord = $data['result_order'][0];
        $emp_no = $data['result'][0]['EMP_NO'];
        $adopt = $data['result'][0]['ADOPT'];
        $grandson_order_id = $data['result'][0]['GRANDSON_ORDER_ID'];
        $form_type= $data['result'][0]['FORM_TYPE'];
        $get_extra= 0;

        if($action=='me' and $this->user->emp_no==$emp_no and $form_type==1 and $adopt==2 and $res_ord['STATUS']>=2 and $res_ord['LEVEL_ACTIVE']>=4){
            $get_extra=1;
        }elseif($action=='edit' and $data['result'][0]['ENTRY_USER']==$this->user->id and $res_ord['STATUS']>=2 and $res_ord['LEVEL_ACTIVE']>=1){
            $get_extra=0;
        }elseif($action=='audit' and $form_type==1 and $adopt==2 and $res_ord['STATUS']>=2 and $res_ord['LEVEL_ACTIVE']==3 and HaveAccess($audit_url)){
            $get_extra=1;
        }elseif($action=='obj' and $form_type==1 and $adopt==2 and $res_ord['STATUS']>=2 and $res_ord['LEVEL_ACTIVE']==5 and HaveAccess($objection_url)){
            $obj = 1;
            $get_extra=1;
        }elseif($action=='archive' and $form_type==1 and $adopt==2 and $res_ord['STATUS']>=2 and $res_ord['LEVEL_ACTIVE']==7 and HaveAccess($archive_url)){
            $get_extra=1;
        }elseif($action=='show_before_end' and $form_type==1 and $adopt==2 and HaveAccess($show_before_end_url)){
            $get_extra=1;
        }elseif($action=='MK' and $this->user->id==111){
            $get_extra=1;
        }elseif($action=='admin_manager' and $form_type==1 and $adopt==2 and $res_ord['STATUS']>=2 and $res_ord['LEVEL_ACTIVE']>=2 and $res_ord['LEVEL_ACTIVE']<7 and HaveAccess($admin_manager)){
            $get_extra=1;
        }elseif($action==null or $action!='me' or $action!='edit' or $action!='obj' or $action!='audit' or $action!='archive' or $action!='admin_manager' ){
            die('action');
        }else{
            die('else');
        }

        $data['action']=$action;
        $data['result_details'] = $this->{$this->DET_MODEL_NAME}->get_list($id,0,$obj);

        if($get_extra){
            $data['brothers_details'] = $this->{$this->DET_MODEL_NAME}->get_list($eval_order_id,$emp_no,$obj);
            $data['grandson_details'] = $this->{$this->DET_MODEL_NAME}->get_list($grandson_order_id,0,$obj);
        }else{
            $data['brothers_details']= array();
            $data['grandson_details']= array();
        }
        $data['courses_all'] = json_encode($this->constant_details_model->get_list(137));
        $data['cat_courses_all'] = json_encode($this->constant_details_model->get_list(140));
        $data['curr_emp_no'] = $this->user->emp_no;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        $data['title']='تقييم الموظف ';
        $data['help']=$this->help;
        $data['browser']= $this->agent->browser();
        $data['content']='evaluation_emps_order_show';
        $this->load->view('template/template',$data);
    }


    function objec_req(){
        if($this->ser!=null and $this->objec_hint!=null and strlen(trim($this->objec_hint))>10)
            echo $this->{$this->DET_MODEL_NAME}->objec_req($this->ser,$this->objec_hint);
        else $this->print_error('ادخل سبب التظلم');

    }
    /* update mark by Committee */
    function edit_objec_mark(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $res=0;
            for($i=0; $i<count($this->element_id); $i++){
                if($this->ser[$i]!=null and strlen(trim($this->gr_objection_hint[$i]))>10 and $this->mark[$i]!=null){
                    $res=$this->{$this->DET_MODEL_NAME}->edit_objec_mark($this->ser[$i],$this->mark[$i],$this->gr_objection_hint[$i]);
                }
                //else $this->print_error('ادخل رأي لجنة التظلم');
            }
            echo intval($res);
        }
    }

    /* update mark by Audit */
    function edit_audit_mark(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $extra_order_serial= implode(",",$this->eval_extra_order_serial);
            for($i=0; $i<count($this->element_id); $i++){
                if($this->ser[$i]!=null and $this->mark[$i]!=null){
                    $old_mark_calc= ($i+1 == count($this->element_id))?$this->old_mark_calc:0;
                    $res=$this->{$this->DET_MODEL_NAME}->edit_audit_mark($this->ser[$i],$this->element_id[$i],$this->mark[$i],$this->audit_hint[$i],$old_mark_calc,$this->evaluation_order_serial,$extra_order_serial);
                }else $this->print_error('ادخل رأي لجنة التدقيق');
            }
            echo intval($res);
        }
    }

    function adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_serial!=''){
            $res = $this->{$this->MODEL_NAME}->adopt($this->evaluation_order_serial,$this->excellent_note,$this->manager_note,$this->emp_transfer);
            if(intval($res) <= 0 and intval($res)!= -100 ){
                $this->print_error('لم يتم إجراء العملية !'.'<br>'.$res);
            }
            echo $res;
        }else
            echo "لم يتم ارسال الرقم المتسلسل";
    }

    function cancel_adopt(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_serial!=''){
            $res = $this->{$this->MODEL_NAME}->cancel_adopt($this->evaluation_order_serial);
            if(intval($res) <= 0 ){
                $this->print_error('لم يتم إجراء العملية !'.'<br>'.$res);
            }
            echo $res;
        }else
            echo "لم يتم ارسال الرقم المتسلسل";
    }

    function adopt_admin_manager(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->evaluation_order_serial!='' and ($this->opinion==1 or $this->opinion==2) ){
            $res = $this->{$this->MODEL_NAME}->adopt_admin_manager($this->evaluation_order_serial, $this->opinion, $this->opinion_reason);
            if(intval($res) <= 0 ){
                $this->print_error('لم يتم إجراء العملية !'.'<br>'.$res);
            }
            echo $res;
        }else
            echo "خطأ في ارسال البيانات";
    }

    /* Check if employee had evaluate or not */
    function public_get_serial($id,$type){
        return $this->{$this->MODEL_NAME}->get_serial($id,$type);
    }

    function get_ask_mark($ask_id=0){
        $this->load->model($this->AXES_MARKS_MODEL_NAME);
        $res= $this->{$this->AXES_MARKS_MODEL_NAME}->get_list($ask_id);
        echo json_encode($res);
    }

    function public_get_details($id){
        $data['result_details'] = $this->{$this->$DET_MODEL_NAME}->get($id);
        $data['content']='evaluation_emps_start_page';
        $this->load->view('template/template',$data);
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->evaluation_order_serial= $this->{$this->MODEL_NAME}->create($this->_postedData());
            if(intval($this->evaluation_order_serial) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$this->evaluation_order_serial);
            }else{
                for($i=0; $i<count($this->element_id); $i++){
                    if($this->eval_form_id[$i]!=null and $this->eval_form_type[$i]!=null and ($this->efa_id[$i]!=null or $this->eval_form_type[$i]==2) and $this->element_id[$i]!=null and $this->mark[$i]!=null){
                        $evaluation_form_id= $this->{$this->DET_MODEL_NAME}->create($this->_postedData_details($this->eval_form_id[$i],$this->eval_form_type[$i],$this->efa_id[$i],$this->element_id[$i],$this->mark[$i]));
                        if(intval($evaluation_form_id) <= 0){
                            $this->print_error_del($evaluation_form_id);
                        }
                    }
                }
            }
            echo intval($this->evaluation_order_serial);
        }
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->evaluation_order_serial);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ التقييم: '.$msg);
        else
            $this->print_error('لم يتم حذف التقييم: '.$msg);
    }

    function edit(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            for($i=0; $i<count($this->ser); $i++){
                if($this->ser[$i]!=null and $this->element_id[$i]!=null and $this->mark[$i]!=null){
                    $res= $this->{$this->DET_MODEL_NAME}->edit($this->_postedData_details_edit($this->ser[$i],$this->element_id[$i],$this->mark[$i]));
                    if(intval($res) <= 0){
                        $this->print_error($res);
                    }
                }
            }
            echo 1;
        }
    }
    function _postedData(){
        $result = array(
            array('name'=>'EVALUATION_ORDER_ID','value'=>$this->evaluation_order_id ,'type'=>'','length'=>-1),
            array('name'=>'EMP_NO','value'=>$this->emp_no ,'type'=>'','length'=>-1),
            array('name'=>'EMP_MANAGER_NO','value'=>$this->emp_manager_no ,'type'=>'','length'=>-1)
        );
        return $result;
    }

    function _postedData_details($eval_form_id,$eval_form_type,$efa_id,$element_id,$mark){
        $result = array(
            array('name'=>'EVALUATION_ORDER_SERIAL','value'=>$this->evaluation_order_serial ,'type'=>'','length'=>-1),
            array('name'=>'EVAL_FORM_TYPE','value'=>$eval_form_type ,'type'=>'','length'=>-1),
            array('name'=>'EVAL_FORM_ID','value'=>$eval_form_id ,'type'=>'','length'=>-1),
            array('name'=>'EFA_ID','value'=>$efa_id ,'type'=>'','length'=>-1),
            array('name'=>'ELEMENT_ID','value'=>$element_id ,'type'=>'','length'=>-1),
            array('name'=>'MARK','value'=>$mark ,'type'=>'','length'=>-1)
        );
        return $result;
    }

    function _postedData_details_edit($ser,$element_id,$mark){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'ELEMENT_ID','value'=>$element_id ,'type'=>'','length'=>-1),
            array('name'=>'MARK','value'=>$mark ,'type'=>'','length'=>-1)
        );
        return $result;
    }

    function not_evaluate_index(){
        $data['title']='متابعة حالة التقييمات';
        $data['content']='eval_start_not_evaluated_index';
        $data["employee"]=  $this->employees_model->get_all();
        $data['help']=$this->help;
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->view('template/template',$data);
    }

    function public_not_evaluate_page(){
        $where_sql='';
        $where_sql.= ($this->emp_no!= null)? " and a.EMPLOYEE_NO= '{$this->emp_no}' " : '';
        if( !$this->input->is_ajax_request() ){
            $where_sql= ' and hr_pkg.CHECK_EMPS_EVALUATE(null,a.EMPLOYEE_NO,null ) !=1 ';
            //$where_sql.= ' and nvl(a.tmp_type,0) !=1 '; // لا تعرض العقود
        }
        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_not_evaluated($where_sql);
        $this->load->view('eval_start_not_evaluated_page',$data);
    }

    function insert_not_evaluate(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $res = $this->{$this->MODEL_NAME}->insert_not_evaluate();
            if(intval($res) <= 0 ){
                $this->print_error('لم يتم إجراء العملية !'.'<br>'.$res);
            }
            echo $res;
        }
    }

    /* emps Courses */
    function get_courses(){
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get_courses_list($id);
        $this->return_json($result);
    }

    function save_courses(){
        if($this->evaluation_order_serial!=''){
            for($i=0; $i<count($this->course_ser); $i++){
                if($this->course_ser[$i]== 0 and $this->course_id[$i]!=''){ // create
                    $detail_seq= $this->{$this->MODEL_NAME}->create_courses($this->_postedDataCourses($this->evaluation_order_serial, $this->course_id[$i]));
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }

                }elseif($this->course_ser[$i]!= 0 and $this->course_id[$i]==''){ // delete
                    $detail_seq= $this->{$this->MODEL_NAME}->delete_courses($this->course_ser[$i]);
                    if(intval($detail_seq) <= 0){
                        $this->print_error($detail_seq);
                    }
                }
            }
            echo 1;
        }else{
            echo "لم يتم الحفظ";
        }
    }

    function _postedDataCourses($evaluation_order_serial,$course_id){
        $result = array(
            array('name'=>'EVALUATION_ORDER_SERIAL','value'=>$evaluation_order_serial ,'type'=>'','length'=>-1),
            array('name'=>'COURSE_ID','value'=>$course_id ,'type'=>'','length'=>-1)
        );
        return $result;
    }

}