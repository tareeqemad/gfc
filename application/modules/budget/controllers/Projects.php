<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 21/12/14
 * Time: 09:05 ص
 */

class Projects extends MY_Controller{

    var $MODEL_NAME= 'projects_model';
    var $url = "budget/projects/get";
    var $PAGE_URL= 'budget/projects/get_page';
    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);

        $this->load->model('settings/currency_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('budget_section_model');


    }

    function _lookUps_data(&$data){
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['project_tec_type'] = $this->constant_details_model->get_list(51);
        $data['currency'] = $this->currency_model->get_list();
        $data['sections'] = $this->budget_section_model->get_list(13);


    }
    /**
     *
     * index action perform all functions in view of projects_show view
     * from this view , can show projects tree , insert new project , update exists one and delete other ..
     *
     */
    function index($page = 1){



        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id,1);

            //$this->_notify("tec","المشروع : {$this->p_title}");

        }else{

            $data['title']='إدارة المشاريع';
            $data['content']='projects_index';
            $data['page']=$page;
            $data['case']= 1;
            $data['action'] = 'index';

            $this->_loadDatePicker();

            $this->_lookUps_data($data);

            $this->load->view('template/template',$data);
        }
    }
    /**
     *
     * index action perform all functions in view of projects_show view
     * from this view , can show projects tree , insert new project , update exists one and delete other ..
     *
     */

    function adopted($page = 1){


        $data['title']='إدارة المشاريع';
        $data['content']='projects_index';
        $data['page']=$page;
        $data['case']= 5;
        $data['action'] = 'index';

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $this->load->view('template/template',$data);

    }

    function archive($page = 1){


        $data['title']='إدارة المشاريع';
        $data['content']='projects_index';
        $data['page']=$page;
        $data['case']= 6;
        $data['action'] = 'index';

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $this->load->view('template/template',$data);

    }

    function _look_ups(&$data,$date = null){

        $data['project_type'] = $this->constant_details_model->get_list(47);
        $data['power_adapter_direction'] = $this->constant_details_model->get_list(48);
        $data['power_adapter_network'] = $this->constant_details_model->get_list(49);
        $data['company_value_type'] = $this->constant_details_model->get_list(50);
        $data['project_tec_type'] = $this->constant_details_model->get_list(51);
        $data['design_cost'] =  $this->get_system_info('DESIGN_FOLLOW_COST','1');
        $data['supervision_cost'] = $this->get_system_info('IMP_INSTALLATION_FEES','1');
        $data['POWER_TYPE'] = $this->constant_details_model->get_list(64);
        $data['sections'] = $this->budget_section_model->get_list(13);


        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['help']=$this->help;

        $data['currency'] =  $this->currency_model->get_all_date($date);

        $this->_loadDatePicker();

    }

    function prices($page = 1){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for($i= 0 ;$i<count($this->p_class_id);$i++){

                if( intval($this->p_ser[$i]) <= 0)
                    $this->projects_model->insert_price($this->_postData_prices(true,0,$this->p_class_id[$i],$this->p_sale_price[$i]));
                else
                    $this->projects_model->update_price($this->_postData_prices(false,$this->p_ser[$i],$this->p_class_id[$i],$this->p_sale_price[$i]));
            }

            echo 1;

        }else{
            $data['content']='projects_prices';
            $data['title']='إدارة المشاريع : بيانات الأسعار';
            //$data['rows'] = $this->projects_model->get_project_items();
            $this->_look_ups($data);
            $data['page']=$page;
            $this->load->view('template/template',$data);
        }


    }

    function delete_price(){
        // $this->IsAuthorized();
        echo $this->projects_model->delete_price($this->p_id);
    }

    function get_page($page = 1,$case = 1,$action = 'index'){

        $this->load->library('pagination');

        $case = isset($this->p_case)?$this->p_case:$case;
        $data['case']=$case;
        $action = isset($this->p_action)?$this->p_action:$action;

        $sql =$case == 6? "":" AND ((PROJECT_CASE + 1) = {$case} or ({$case} = 1 and PROJECT_CASE = -1) )";



        $sql .= isset($this->p_tec_num) && $this->p_tec_num !=null ? " AND  LOWER(PROJECT_TEC_CODE) like LOWER('%{$this->p_tec_num}')  " :"" ;
        $sql .= isset($this->p_project_name) && $this->p_project_name !=null ? " AND  PROJECT_NAME LIKE '%{$this->p_project_name}%' " :"" ;
        $sql .= isset($this->p_from_date) && $this->p_from_date !=null ? " AND  PROJECT_DATE >= '{$this->p_from_date}' " :"" ;
        $sql .= isset($this->p_to_date) && $this->p_to_date !=null ? " AND  PROJECT_DATE <= '{$this->p_to_date}' " :"" ;
        $sql .= isset($this->p_branch) && $this->p_branch !=null ? " AND  BRANCH={$this->p_branch}  " :"" ;
        $sql .= isset($this->p_project_tec_type) && $this->p_project_tec_type !=null ? " AND  PROJECT_TEC_TYPE={$this->p_project_tec_type}  " :"" ;
        $sql .= isset($this->p_section_no) && $this->p_section_no !=null ? " AND  section_no={$this->p_section_no}  " :"" ;

        $sql .= $this->user->branch != 1 ? " AND  BRANCH ={$this->user->branch}  " :"" ;

        $config['base_url'] = base_url("budget/projects/get_page/");


        $count_rs = $this->get_table_count(" BUDGET_PROJECTS_FILE_TB WHERE 1=1 {$sql} ");


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

        $data["rows"] = $this->projects_model->get_list($sql, $offset , $row );


        $data['offset']=$offset+1;
        $data['page']=$page;
        $data['action'] = $action;

        $this->load->view('projects_page',$data);

    }


    function get_items_page($page = 1){

        $this->load->library('pagination');



        $sql = isset($this->p_id) && $this->p_id !=null ? " AND  CLASS_ID= {$this->p_id} " :"" ;
        $sql .= isset($this->p_name) && $this->p_name !=null ? " AND  STORES_PKG.CLASS_TB_GET_NAME_AR(CLASS_ID) LIKE '%{$this->p_name}%' " :"" ;

        $config['base_url'] = base_url("budget/projects/prices/");


        $count_rs = $this->get_table_count(" PROJECTS_ITEM WHERE 1=1 $sql");


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

        $data["rows"] = $this->projects_model->get_project_items($sql, $offset , $row );


        $data['offset']=$offset+1;
        $data['page']=$page;


        $this->load->view('project_prices_page',$data);

    }

    /**
     * get project by id ..
     */
    function get($id,$action = 'index'){


        $result = $this->projects_model->get($id);

        if(count($result) <= 0)
            redirect($_SERVER['HTTP_REFERER']);


        $data['content']='projects_show';
        $data['title']='إدارة المشاريع : بيانات المشروع ';
        $data['action'] = $action;
        $data['result'] =$result;

        $data['can_edit']= count($result) > 0 && ($result[0]['PROJECT_CASE'] == -1  && $result[0]['ENTRY_USER'] == $this->user->id );


        $this->_look_ups($data,count($result) > 0? $result[0]['ENTRY_DATE']:null);



        $this->load->view('template/template',$data);
    }

    /**
     * get project by id ..
     */
    function get_last($id,$action = 'archive_last'){


        $result = $this->projects_model->get_last($id);

        $data['content']='projects_show';
        $data['title']='إدارة المشاريع : بيانات المشروع ';
        $data['action'] = $action;
        $data['result'] =$result;

        $data['can_edit']= count($result) > 0 && $result[0]['PROJECT_CASE'] == 1 && $result[0]['ENTRY_USER'] == $this->user->id;

        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    /**
     * create action : insert new project data ..
     * receive post data of project
     *
     */
    function create(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result= $this->projects_model->create($this->_postedData());

            if(intval($result) <=0){
                $this->print_error('فشل في حفظ المشروع');
            }


            for($i = 0;$i< count($this->p_class_id);$i++)
                $this->projects_model->create_details($this->_postData_details(true,null,$result,
                    $this->p_class_id[$i],
                    $this->p_class_unit[$i],
                    $this->p_amount[$i],
                    $this->p_price[$i],
                    $this->p_price[$i],
                    $this->p_notes[$i],
                    $this->p_class_type[$i]));

            echo 1;

        }else{
            $data['content']='projects_show';
            $data['title']='إدارة المشاريع : بيانات المشروع ';
            $data['action'] = 'index';

            $this->_look_ups($data);

            $this->load->view('template/template',$data);
        }


    }

    /**
     * edit action : update exists project data ..
     * receive post data of project
     * depended on project prm key
     */
    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result= $this->projects_model->edit($this->_postedData(false));


            if(intval($result) <=0){
                $this->print_error('فشل في حفظ المشروع');
            }


            for($i = 0;$i< count($this->p_class_id);$i++){
                if($this->p_SER[$i] <= 0)
                    $this->projects_model->create_details($this->_postData_details(true,null,$this->p_project_serial,
                        $this->p_class_id[$i],
                        $this->p_class_unit[$i],
                        $this->p_amount[$i],
                        $this->p_price[$i],
                        $this->p_price[$i],
                        $this->p_notes[$i],
                        $this->p_class_type[$i]
                    ));
                else
                    $this->projects_model->edit_details($this->_postData_details(false,$this->p_SER[$i],$this->p_project_serial,
                        $this->p_class_id[$i],
                        $this->p_class_unit[$i],
                        $this->p_amount[$i],
                        $this->p_price[$i],
                        $this->p_price[$i],
                        $this->p_notes[$i],
                        $this->p_class_type[$i]
                    ));
            }

            echo 1;

        }
    }

    //case 2
    function tec($page = 1){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id,3);

        }else{
            $data['title']='موزانة المشاريع : اعتماد الإدارة الفنية';
            $data['content']='projects_index';
            $data['page']=$page;
            $data['case']= 2;
            $data['action'] = 'tec';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template',$data);
        }
    }

    //case 3
   /* function branch_admin($page = 1){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id,3);


        }else{
            $data['title']='موازنة المشاريع : اعتماد مدير المقر';
            $data['content']='projects_index';
            $data['page']=$page;
            $data['case']= 3;
            $data['action'] = 'branch_admin';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template',$data);
        }
    }
*/
    //case 4
    function specialist_admin($page = 1){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo $this->projects_model->adopt($this->p_id,4);
            $this->_notify("com","المشروع : {$this->p_title}",'plan_admin');
        }else{
            $data['title']='موازنة المشاريع : اعتماد الجهة المختصة';
            $data['content']='projects_index';
            $data['page']=$page;
            $data['case']= 4;
            $data['action'] = 'specialist_admin';
            $this->_loadDatePicker();
            $this->_lookUps_data($data);
            $this->load->view('template/template',$data);
        }
    }
    function adopt()
{
    $id = $this->input->post('id');
    $this->IsAuthorized();
    $msg = 0;
    if (is_array($id)) {
        foreach ($id as $val) {
            $msg = $this->{$this->MODEL_NAME}->adopt($val,3);
        }
    } else {
        $msg = $this->{$this->MODEL_NAME}->adopt($id,3);
    }

    if ($msg == 1) {
        echo "1" ;
        // echo modules::run($this->PAGE_URL);
    } else {
        $this->print_error_msg($msg);
    }
}

    function public_adopt4()
    {
        $id = $this->input->post('id');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->adopt($val,4);
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->adopt($id,4);
        }

        if ($msg == 1) {
            echo "1" ;
            // echo modules::run($this->PAGE_URL);
        } else {
            $this->print_error_msg($msg);
        }
    }

    /**
     * delete action : delete project data ..
     * receive prm key as request
     *
     */
    function delete_details(){
        $result = $this->projects_model->delete_details($this->p_id);
        if(intval($result) > 0)
            echo 1;
        else $this->print_error('حدث مشكلة قد يكون الصنف صرف منه!');
    }


    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData($isCreate = true){

        $result = array(
            array('name'=>'PROJECT_SERIAL','value'=>$this->p_project_serial ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_NAME','value'=>$this->p_project_name ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_DESIGN_DATE','value'=>$this->p_project_design_date,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_DESIGN_VALID_DATE','value'=>$this->p_project_design_valid_date,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_TYPE','value'=>$this->p_project_type,'type'=>'','length'=>-1),
            array('name'=>'POWER_ADAPTER_DIRECTION','value'=>$this->p_power_adapter_direction,'type'=>'','length'=>-1),
            array('name'=>'POWER_ADAPTER_NETWORK','value'=>$this->p_power_adapter_network,'type'=>'','length'=>-1),
            array('name'=>'ADDRESS','value'=>$this->p_address,'type'=>'','length'=>-1),
            array('name'=>'POWER_CONNECTION_TYPE','value'=>$this->p_power_connection_type,'type'=>'','length'=>-1),
            array('name'=>'COMPANY_VALUE_TYPE','value'=>$this->p_company_value_type,'type'=>'','length'=>-1),
            array('name'=>'COMPANY_VALUE','value'=>$this->p_company_value,'type'=>'','length'=>-1),
            array('name'=>'DESIGN_COST','value'=>$this->p_design_cost,'type'=>'','length'=>-1),
            array('name'=>'SUPERVISION_COST','value'=>$this->p_supervision_cost,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_TEC_TYPE','value'=>$this->p_project_tec_type,'type'=>'','length'=>-1),
            array('name'=>'CURR_ID','value'=>$this->p_curr_id,'type'=>'','length'=>-1),
            array('name'=>'CURR_VALUE','value'=>$this->p_curr_value,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$this->p_hints,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$this->user->branch,'type'=>'','length'=>-1),
            array('name'=>'POWER_TYPE','value'=>$this->p_power_type,'type'=>'','length'=>-1),
            array('name'=>'DONOR_NAME','value'=>$this->p_donor_name,'type'=>'','length'=>-1),
            array('name'=>'SECTION_NO','value'=>$this->p_section_no,'type'=>'','length'=>-1),
            array('name'=>'YYEAR','value'=>$this->budget_year,'type'=>'','length'=>-1),
            array('name'=>'MONTH','value'=>$this->p_month,'type'=>'','length'=>-1),
            array('name' => 'EXTRA_COST', 'value' => $this->p_extra_cost, 'type' => '', 'length' => -1),
            array('name' => 'RETURN_COST', 'value' => $this->p_return_cost, 'type' => '', 'length' => -1),
        );

        if($isCreate){
            array_shift($result);
        }


        return $result;
    }

    function public_get_details($id = 0,$can_edit =false,$action = 'index'){

        if($action != 'archive_last'){
            $data['details'] =$this->projects_model->get_details($id);
            $data['can_edit']=$can_edit;
        }else{
            $data['details'] =$this->projects_model->get_details_last($id);
            $data['can_edit']=false;
        }

        $this->load->view('projects_details',$data);
    }

    function _postData_prices($create = true,$ser,$class_id,$price){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>'SALE_PRICE','value'=>$price ,'type'=>'','length'=>-1),
        );

        if($create){
            array_shift($result);
        }

        return $result;
    }

    function _postData_details($create = true,$ser,$project_serial,$class_id,$class_unit,$amount,$price,$sal_price,$notes,$class_type,$inuse = 1){


        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_SERIAL','value'=>$project_serial ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$class_unit ,'type'=>'','length'=>-1),
            array('name'=>'AMOUNT','value'=>$amount ,'type'=>'','length'=>-1),
            array('name'=>'PRICE','value'=>$price ,'type'=>'','length'=>-1),
            array('name'=>'SAL_PRICE','value'=>$sal_price ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$notes ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_TYPE','value'=>$class_type ,'type'=>'','length'=>-1),

        );

        if($create){
            array_shift($result);

        }else
        {
            unset($result[1]);

        }

        return $result;
    }

    function _postData_accounts($ser,$account_id){
        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'PROJECT_ACCOUNT_ID','value'=>$account_id ,'type'=>'','length'=>-1),

        );
        return $result;
    }

    function public_return(){
        echo $this->projects_model->adopt($this->p_id,-1);
    }

    function delete(){
      //  $this->print_error('معطلة');
        echo $this->projects_model->delete($this->p_id);
    }

    // MKilani - 29/6/2020 - حذف المشروع قبل الاعتماد
    function new_delete(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_project_serial!=''){
            $res = $this->projects_model->new_delete($this->p_project_serial, $this->budget_year);
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحذف'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم السند";
    }


    function table($page = 1){

        $data['title']='فهرس المشاريع';
        $data['content']='projects_table';
        $data['page']=$page;
        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $this->load->view('template/template',$data);
    }

    function copyFromProjects($page = 1){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rs =  $this->projects_model->BUDGET_PROJECTS_FILE_TB_COPY($this->p_tec_num,$this->p_section_no,$this->budget_year,$this->p_month);
            if(intval($rs) > 0 )
                echo 1;
            else $this->print_error('فشل في نسخ المشروع');
        }else{
            $data['title']='نسخ مشروع من نظام المشاريع الي الموازنة';
            $data['content']='projects_copy';
            $data['page']=$page;
            $this->_loadDatePicker();

            $this->_lookUps_data($data);
            $this->_look_ups($data);

            $this->load->view('template/template',$data);
        }
    }

    function get_page_table($page = 1){

        $this->load->library('pagination');

        $sql =" AND (P.PROJECT_CASE = 11  )";

        $sql .= isset($this->p_tec_num) && $this->p_tec_num !=null ? " AND  LOWER(P.PROJECT_TEC_CODE) like LOWER('%{$this->p_tec_num}')  " :"" ;
        $sql .= isset($this->p_project_name) && $this->p_project_name !=null ? " AND  P.PROJECT_NAME LIKE '%{$this->p_project_name}%' " :"" ;
        $sql .= isset($this->p_from_date) && $this->p_from_date !=null ? " AND  P.PROJECT_DATE >= '{$this->p_from_date}' " :"" ;
        $sql .= isset($this->p_to_date) && $this->p_to_date !=null ? " AND  P.PROJECT_DATE <= '{$this->p_to_date}' " :"" ;
        $sql .= isset($this->p_branch) && $this->p_branch !=null ? " AND  P.BRANCH={$this->p_branch}  " :"" ;
        $sql .= isset($this->p_project_tec_type) && $this->p_project_tec_type !=null ? " AND  PROJECT_TEC_TYPE={$this->p_project_tec_type}  " :"" ;
        $sql .= isset($this->p_account_id) && $this->p_account_id !=null ? " AND  AC.PROJECT_ID={$this->p_account_id}  " :"" ;
        $sql .= $this->user->branch != 1 ? " AND  P.BRANCH={$this->user->branch}  " :"" ;

        $config['base_url'] = base_url("projects/projects/get_page_table/");


        $count_rs = $this->get_table_count("  PROJECTS_ACCOUNTS_TB AC JOIN PROJECTS_FILE_TB P ON AC.PROJECT_SERIAL = P.PROJECT_SERIAL WHERE 1=1 {$sql}");


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

        $data["rows"] = $this->projects_model->projects_accounts_fina_list($sql, $offset , $row );

        $data['offset']=$offset+1;

        $data['page']=$page;

        $this->load->view('table_page',$data);

    }

    function  _notify($action,$message,$prev_action = null){
        $prev_action = $prev_action != null ? "{$this->url}/{$this->p_id}/{$prev_action}" : null;
        $this->_notifyMessage($action,"{$this->url}/{$this->p_id}/{$action}",$message,$prev_action);
    }

}
