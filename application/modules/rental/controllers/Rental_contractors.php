<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 27/07/17
 * Time: 10:43 ص
 */

// contractor_file_id,service_type,adopt,branch_id,contractor_id,contractor_name,subscriber_id,subscriber_name,subscriber_branch_id,bank_id,bank_branch,account_id,iban,contract_case,contract_start_date,contract_end_date,notes,daily_cost_mo,daily_cost_ev,h_overtime_cost  ,  notes_for_finance,overtime_limit_rate,subscriber_id_2,subscriber_name_2,subscriber_branch_id_2,contract_type,contractor_name_to_bank,contractor_to_bank_id
// car_num,car_class,car_model,car_fuel_type,car_passenger_count,car_insurance_num,car_insurance_company,car_insurance_type,car_insurance_start_date,car_insurance_end_date,car_license_start,car_license_end

class Rental_contractors extends MY_Controller{
    var $MODEL_NAME= 'rental_contractors_model';
    var $INFO_1_CAR_MODEL= 'car_contractors_model';
    var $PAGE_URL= 'rental/rental_contractors/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model($this->INFO_1_CAR_MODEL);

        $this->contractor_file_id= $this->input->post('contractor_file_id');
        $this->service_type= $this->input->post('service_type');
        $this->adopt= $this->input->post('adopt');
        $this->branch_id= $this->input->post('branch_id');
        $this->contractor_id= $this->input->post('contractor_id');
        $this->contractor_name= $this->input->post('contractor_name');
        $this->subscriber_id= $this->input->post('subscriber_id');
        $this->subscriber_name= $this->input->post('subscriber_name');
        $this->subscriber_branch_id= $this->input->post('subscriber_branch_id');
        $this->bank_id= $this->input->post('bank_id');
        $this->bank_branch= $this->input->post('bank_branch');
        $this->account_id= $this->input->post('account_id');
        $this->iban= $this->input->post('iban');
        $this->contract_case= $this->input->post('contract_case');
        $this->contract_start_date= $this->input->post('contract_start_date');
        $this->contract_end_date= $this->input->post('contract_end_date');
        $this->notes= $this->input->post('notes');
        $this->daily_cost_mo= $this->input->post('daily_cost_mo');
        $this->daily_cost_ev= $this->input->post('daily_cost_ev');
        $this->h_overtime_cost= $this->input->post('h_overtime_cost');
        $this->entry_user= $this->input->post('entry_user');
        $this->notes_for_finance= $this->input->post('notes_for_finance');
        $this->overtime_limit_rate= $this->input->post('overtime_limit_rate');
        $this->subscriber_id_2= $this->input->post('subscriber_id_2');
        $this->subscriber_name_2= $this->input->post('subscriber_name_2');
        $this->subscriber_branch_id_2= $this->input->post('subscriber_branch_id_2');
        $this->contract_type= $this->input->post('contract_type');
        $this->contractor_name_to_bank= $this->input->post('contractor_name_to_bank');
        $this->contractor_to_bank_id= $this->input->post('contractor_to_bank_id');

        $this->car_num= $this->input->post('car_num');
        $this->car_class= $this->input->post('car_class');
        $this->car_model= $this->input->post('car_model');
        $this->car_fuel_type= $this->input->post('car_fuel_type');
        $this->car_passenger_count= $this->input->post('car_passenger_count');
        $this->car_insurance_num= $this->input->post('car_insurance_num');
        $this->car_insurance_company= $this->input->post('car_insurance_company');
        $this->car_insurance_type= $this->input->post('car_insurance_type');
        $this->car_insurance_start_date= $this->input->post('car_insurance_start_date');
        $this->car_insurance_end_date= $this->input->post('car_insurance_end_date');
        $this->car_license_start= $this->input->post('car_license_start');
        $this->car_license_end= $this->input->post('car_license_end');
        $this->mobile_no= $this->input->post('mobile_no');

        if( HaveAccess(base_url("rental/rental_contractors/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;

        if( HaveAccess(base_url("rental/rental_contractors/all_branches_for_select")) )
            $this->all_branches_for_select= 1;
        else
            $this->all_branches_for_select= 0;
    }

    function index($page= 1, $contractor_file_id= -1, $service_type= -1, $adopt= -1, $branch_id= -1, $contractor_id= -1, $contractor_name= -1, $contract_case= -1, $contract_start_date= -1, $contract_end_date= -1, $notes= -1, $entry_user= -1){

        $data['title']='التعاقدات ';
        $data['content']='rental_contractors_index';

        $data['entry_user_all'] = $this->get_entry_users('RENTAL_CONTRACTORS_TB');

        $data['page']=$page;
        $data['contractor_file_id']= $contractor_file_id;
        $data['service_type']= $service_type;
        $data['adopt']= $adopt;
        $data['branch_id']= $branch_id;
        $data['contractor_id']= $contractor_id;
        $data['contractor_name']= $contractor_name;
        $data['contract_case']= $contract_case;
        $data['contract_start_date']= $contract_start_date;
        $data['contract_end_date']= $contract_end_date;
        $data['notes']= $notes;
        $data['entry_user']= $entry_user;

        $data['help'] = $this->help;
        $data['action'] = 'edit';
        $this->_look_ups($data);

        $this->load->view('template/template',$data);
    }

    function get_page($page= 1, $contractor_file_id= -1, $service_type= -1, $adopt= -1, $branch_id= -1, $contractor_id= -1, $contractor_name= -1, $contract_case= -1, $contract_start_date= -1, $contract_end_date= -1, $notes= -1, $entry_user= -1){
        $this->load->library('pagination');

        $contractor_file_id= $this->check_vars($contractor_file_id,'contractor_file_id');
        $service_type= $this->check_vars($service_type,'service_type');
        $adopt= $this->check_vars($adopt,'adopt');
        $branch_id= $this->check_vars($branch_id,'branch_id');
        $contractor_id= $this->check_vars($contractor_id,'contractor_id');
        $contractor_name= $this->check_vars($contractor_name,'contractor_name');
        $contract_case= $this->check_vars($contract_case,'contract_case');
        $contract_start_date= $this->check_vars($contract_start_date,'contract_start_date');
        $contract_end_date= $this->check_vars($contract_end_date,'contract_end_date');
        $notes= $this->check_vars($notes,'notes');
        $entry_user= $this->check_vars($entry_user,'entry_user');

        $where_sql= " where 1=1 ";

        if(!$this->all_branches)
            $where_sql.= " and branch_id= ".(  ($this->user->branch ==8)? 2: $this->user->branch  );

        $where_sql.= ($contractor_file_id!= null)? " and contractor_file_id= '{$contractor_file_id}' " : '';
        $where_sql.= ($service_type!= null)? " and service_type= '{$service_type}' " : '';
        $where_sql.= ($adopt!= null)? " and adopt= '{$adopt}' " : '';
        $where_sql.= ($branch_id!= null)? " and branch_id= '{$branch_id}' " : '';
        $where_sql.= ($contractor_id!= null)? " and contractor_id= '{$contractor_id}' " : '';
        $where_sql.= ($contractor_name!= null)? " and contractor_name like '".add_percent_sign($contractor_name)."' " : '';
        $where_sql.= ($contract_case!= null)? " and contract_case= '{$contract_case}' " : '';
        $where_sql.= ($contract_start_date!= null)? " and contract_start_date= '{$contract_start_date}' " : '';
        $where_sql.= ($contract_end_date!= null)? " and contract_end_date= '{$contract_end_date}' " : '';
        $where_sql.= ($notes!= null)? " and notes like '".add_percent_sign($notes)."' " : '';
        $where_sql.= ($entry_user!= null)? " and entry_user= '{$entry_user}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' RENTAL_CONTRACTORS_TB '.$where_sql);
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

        $data['page_rows'] = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('rental_contractors_page',$data);
    }

    function check_vars($var, $c_var){
        // if post take it, else take the parameter
        $var= ($this->{$c_var})? $this->{$c_var}:$var;
        // if val is -1 then null, else take the val
        $var= $var == -1 ?null:$var;
        return $var;
    }

    function public_get_service_info(){
        if($this->service_type==1){
            $service_view='rental_contractors_info_1_cars';
        }else{
            $service_view='error';
        }
        $data['HaveRs']=$this->p_HaveRs;
        if($this->p_HaveRs==1 and $this->contractor_file_id > 0 ){
            $res= $this->{$this->INFO_1_CAR_MODEL}->get($this->contractor_file_id);
            $data['cnt_rs']= count($res);
            $data['rs']= $res[0];
        }else{
            $data['cnt_rs']= 1;
            $data['rs']= array();
        }
        $this->_look_ups($data);
        $this->load->view($service_view,$data);
    }

    function public_get_all_for_select($val=0,$all_branches=0,$contract_case=1){
        $branch='';
        if(!$this->all_branches_for_select and !$all_branches)
            $branch= ($this->user->branch ==8)? 2:$this->user->branch;
        $all= $this->{$this->MODEL_NAME}->get_all($branch,$contract_case);
        $select= '<option value="">_________</option>';
        foreach($all as $row){
            $select.= '<option '.(($row['EXPIRED'])?' class="select2_opt_bg_mk" ':'').' data-FNotes="'.$row['NOTES_FOR_FINANCE'].'" '.' data-subscriptions="'.trim($row['SUBSCRIPTIONS'],',').'" '. (($val?($val==$row['CONTRACTOR_FILE_ID']?'selected':''):'')) .' value="'.$row['CONTRACTOR_FILE_ID'].'" >'. (($row['CONTRACTOR_FILE_ID'].': '.$row['CONTRACTOR_NAME'])) .'</option>';
        }
        return $select;
    }

    function create(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $this->contractor_file_id= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            if(intval($this->contractor_file_id) <= 0){
                $this->print_error(' لم يتم الحفظ الجدول الاساسي '.'<br>'.$this->contractor_file_id);
            }else{
                $info_id= $this->{$this->INFO_1_CAR_MODEL}->create($this->_postedDataInfo_1('create'));
                if(intval($info_id) <= 0){
                    $this->print_error_del($info_id);
                }
                echo intval($this->contractor_file_id);
            }
        }else{
            $data['content']='rental_contractors_show';
            $data['title']='اضافة تعاقدات';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template',$data);
        }
    }

    function _post_validation($isEdit = false){ //////////////////////////////////
        if( !check_identity($this->contractor_id) ){
            $this->print_error('رقم هوية المتعاقد غير صحيح');
        }else if( $this->mobile_no==''){
            $this->print_error('ادخل رقم الجوال');
        }else if( strlen( $this->mobile_no) != 10 ){
            $this->print_error('الرجاء كتابة رقم الجوال بشكل صحيح');
        }else if( substr($this->mobile_no, 0, 3) != '059' and substr($this->mobile_no, 0, 3) != '056'){
            $this->print_error('يجب ان يبدأ رقم الجوال ب059 أو 056');
        }
    }

    function print_error_del($msg=''){
        $ret= $this->{$this->MODEL_NAME}->delete($this->contractor_file_id);
        if(intval($ret) > 0)
            $this->print_error('لم يتم حفظ السند: '.$msg);
        else
            $this->print_error('لم يتم حذف السند: '.$msg);
    }

    function get($id, $action= 'index'){
        $result= $this->{$this->MODEL_NAME}->get($id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['can_edit'] =count($result) > 0?  ($this->user->id == $result[0]['ENTRY_USER'] && $action == 'edit')?true : false : false;
        $data['action'] = $action;
        $data['content']='rental_contractors_show';
        $data['title']='بيانات التعاقد ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation(true);
            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            if(intval($res) <= 0){
                $this->print_error('لم يتم الحفظ'.'<br>'.$res);
            }else{
                $info_id= $this->{$this->INFO_1_CAR_MODEL}->edit($this->_postedDataInfo_1());
                if(intval($info_id) <= 0){
                    $this->print_error($info_id);
                }
                echo 1;
            }
        }
    }

    private function adopt($case){
        $res = $this->{$this->MODEL_NAME}->adopt($this->contractor_file_id, $case);
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        return 1;
    }

    function adopt_2(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->contractor_file_id!=''){
            echo $this->adopt(2);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function contract_case(){
        $case= $this->p_case;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->contractor_file_id!='' and $case > 0){
            $res = $this->{$this->MODEL_NAME}->contract_case($this->contractor_file_id, $case);
            if(intval($res) <= 0){
                $this->print_error('لم تتم العملية'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function edit_branch(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->contractor_file_id!='' and $this->branch_id > 0){
            $res = $this->{$this->MODEL_NAME}->edit_branch($this->contractor_file_id, $this->branch_id);
            if(intval($res) <= 0){
                $this->print_error('لم تتم العملية'.'<br>'.$res);
            }
            echo 1;
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _look_ups(&$data){
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
        $data['branch']= ($this->user->branch ==8)? 2:$this->user->branch;
        $data['branch_id_cons']= $this->gcc_branches_model->get_all();
        $data['car_class_cons'] = $this->constant_details_model->get_list(43);
        $data['car_fuel_type_cons'] = $this->constant_details_model->get_list(58);
        //$data['car_model_cons'] = $this->constant_details_model->get_list(57);
        $data['adopt_cons'] = $this->constant_details_model->get_list(176);
        $data['service_type_cons'] = $this->constant_details_model->get_list(170);
        $data['bank_id_cons'] = $this->constant_details_model->get_list(9);
        $data['bank_branch_cons'] = $this->constant_details_model->get_list(196);
        $data['contract_case_cons'] = $this->constant_details_model->get_list(175);
        $data['car_insurance_company_cons'] = $this->constant_details_model->get_list(177);
        $data['car_insurance_type_cons'] = $this->constant_details_model->get_list(178);
        $data['contract_type_cons'] = $this->constant_details_model->get_list(216);

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _postedData($typ= null){
        $result = array(
            array('name'=>'SERVICE_TYPE','value'=>$this->service_type ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_FILE_ID','value'=>$this->contractor_file_id ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_ID','value'=>$this->contractor_id ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_NAME','value'=>$this->contractor_name ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_ID','value'=>$this->branch_id ,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIBER_ID','value'=>$this->subscriber_id ,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIBER_NAME','value'=>$this->subscriber_name ,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIBER_BRANCH_ID','value'=>$this->subscriber_branch_id ,'type'=>'','length'=>-1),
            array('name'=>'BANK_ID','value'=>$this->bank_id ,'type'=>'','length'=>-1),
            array('name'=>'BANK_BRANCH','value'=>$this->bank_branch ,'type'=>'','length'=>-1),
            array('name'=>'ACCOUNT_ID','value'=>$this->account_id ,'type'=>'','length'=>-1),
            array('name'=>'IBAN','value'=>$this->iban ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACT_CASE','value'=>$this->contract_case ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACT_START_DATE','value'=>$this->contract_start_date ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACT_END_DATE','value'=>$this->contract_end_date ,'type'=>'','length'=>-1),
            array('name'=>'NOTES','value'=>$this->notes ,'type'=>'','length'=>-1),
            array('name'=>'DAILY_COST_MO','value'=>$this->daily_cost_mo ,'type'=>'','length'=>-1),
            array('name'=>'DAILY_COST_EV','value'=>$this->daily_cost_ev ,'type'=>'','length'=>-1),
            array('name'=>'H_OVERTIME_COST','value'=>$this->h_overtime_cost ,'type'=>'','length'=>-1),
            array('name'=>'NOTES_FOR_FINANCE','value'=>$this->notes_for_finance ,'type'=>'','length'=>-1),
            array('name'=>'OVERTIME_LIMIT_RATE','value'=>$this->overtime_limit_rate ,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIBER_ID_2','value'=>$this->subscriber_id_2 ,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIBER_NAME_2','value'=>$this->subscriber_name_2 ,'type'=>'','length'=>-1),
            array('name'=>'SUBSCRIBER_BRANCH_ID_2','value'=>$this->subscriber_branch_id_2 ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACT_TYPE','value'=>$this->contract_type ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_NAME_TO_BANK','value'=>$this->contractor_name_to_bank ,'type'=>'','length'=>-1),
            array('name'=>'CONTRACTOR_TO_BANK_ID','value'=>$this->contractor_to_bank_id ,'type'=>'','length'=>-1),
            array('name'=>'MOBILE_NO','value'=>$this->mobile_no ,'type'=>'','length'=>-1),
        );
        if($typ=='create')
            unset($result[1]);
        else
            unset($result[0]);
        return $result;
    }

    function _postedDataInfo_1($typ= null){
        $result = array(
            array('name'=>'CONTRACTOR_FILE_ID','value'=>$this->contractor_file_id ,'type'=>'','length'=>-1),
            array('name'=>'CAR_NUM','value'=>$this->car_num ,'type'=>'','length'=>-1),
            array('name'=>'CAR_CLASS','value'=>$this->car_class ,'type'=>'','length'=>-1),
            array('name'=>'CAR_MODEL','value'=>$this->car_model ,'type'=>'','length'=>-1),
            array('name'=>'CAR_FUEL_TYPE','value'=>$this->car_fuel_type ,'type'=>'','length'=>-1),
            array('name'=>'CAR_PASSENGER_COUNT','value'=>$this->car_passenger_count ,'type'=>'','length'=>-1),
            array('name'=>'CAR_INSURANCE_NUM','value'=>$this->car_insurance_num ,'type'=>'','length'=>-1),
            array('name'=>'CAR_INSURANCE_COMPANY','value'=>$this->car_insurance_company ,'type'=>'','length'=>-1),
            array('name'=>'CAR_INSURANCE_TYPE','value'=>$this->car_insurance_type ,'type'=>'','length'=>-1),
            array('name'=>'CAR_INSURANCE_START_DATE','value'=>$this->car_insurance_start_date ,'type'=>'','length'=>-1),
            array('name'=>'CAR_INSURANCE_END_DATE','value'=>$this->car_insurance_end_date ,'type'=>'','length'=>-1),
            array('name'=>'CAR_LICENSE_START','value'=>$this->car_license_start ,'type'=>'','length'=>-1),
            array('name'=>'CAR_LICENSE_END','value'=>$this->car_license_end ,'type'=>'','length'=>-1),
        );
        return $result;
    }

    /*
    function public_get_car_model(){
        $this->load->model('settings/constant_details_model');
        if ($this->p_id != ''){
            $car_model_js= $this->constant_details_model->get_acc($this->p_table,$this->p_id);
        }
        else{
            $car_model_js= $this->constant_details_model->get_list($this->p_table);
        }
        $this->return_json($car_model_js);
    }
    */

}