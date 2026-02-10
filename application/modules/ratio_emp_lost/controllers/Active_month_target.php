<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 30/10/22
 * Time: 11:00 ص
 */
class Active_month_target extends MY_Controller {

    var $MODEL_NAME= 'Active_month_target_model';
    var $PAGE_URL= 'ratio_emp/Active_month_target/get_page';

    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('hr_attendance/hr_attendance_model');

        $this->ser = $this->input->post('ser');
        $this->branch_no = $this->input->post('branch_no');
        $this->the_month = $this->input->post('the_month');
        $this->activity_no = $this->input->post('activity_no');
        $this->activity_type = $this->input->post('activity_type');
        $this->monthly_target = $this->input->post('monthly_target');
        $this->discount_value = $this->input->post('discount_value');
        $this->added_value = $this->input->post('added_value');

        if( HaveAccess(base_url("ratio_emp/Active_month_target/all_branches")) )
            $this->all_branches= 1;
        else
            $this->all_branches= 0;
    }

    function index()
    {
        $data['content']='active_month_target_index';
        $data['title']='المستهدف الشهري للأنشطة';
        $data['isCreate']= true;
        $data['action'] = 'index';
        $data['emp_branch_selected'] = $this->user->branch;
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function get_page($page= 1){

        $this->load->library('pagination');

        $where_sql= "where 1 = 1";

        $where_sql.= ($this->branch_no!= null)? " AND M.BRANCH_NO= '{$this->branch_no}' " : '';
        $where_sql.= ($this->the_month!= null)? " AND M.THE_MONTH= '{$this->the_month}' " : '';
        $where_sql.= ($this->activity_no!= null)? " AND M.ACTIVITY_NO= '{$this->activity_no}' " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('MONTH_ACTIVE_TARGET_TB M '.$where_sql);
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
        $this->load->view('active_month_target_page',$data);
    }

    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
        $this->_post_validation(true);

        $items = array();
        for ($x = 0; $x < count($this->activity_no); $x++) {
            $items[] = $this->activity_no[$x];
        }

        $array = $items;
        $counts = array_count_values($array);
        foreach ($counts as $key => $count) {
            if ($count > 1) {
                echo $this->print_error('هناك طلبات مكررة ، الرجاء التأكد من البيانات ');
            }
        }

        for($i=0; $i<count($this->activity_no); $i++){
            if($this->ser[$i]== 0 and $this->branch_no!='' and $this->activity_no[$i]!=''  and $this->the_month != '' and $this->monthly_target[$i] != 0 and $this->discount_value[$i] >= 0 and $this->added_value[$i] >= 0){ // create
                $req_seq = $this->{$this->MODEL_NAME}->create($this->_postedData(null ,$this->branch_no ,$this->activity_no[$i] ,$this->the_month ,$this->monthly_target[$i] ,$this->discount_value[$i] ,$this->added_value[$i] ,'create'));
                if(intval($req_seq) <= 0){
                    $this->print_error($req_seq);
                }
            }
        }
        echo $this->branch_no.'/'.$this->the_month;

        }else{
            $data['content']='active_month_target_show';
            $data['title']='المستهدف الشهري للأنشطة';
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
            for ($x = 0; $x < count($this->activity_no); $x++) {
                $items[] = $this->activity_no[$x];
            }

            $array = $items;
            $counts = array_count_values($array);
            foreach ($counts as $key => $count) {
                if ($count > 1) {
                    echo $this->print_error('هناك طلبات مكررة ، الرجاء التأكد من البيانات ');
                }
            }

            for ($i = 0; $i < count($this->activity_no); $i++) {
                if ($this->ser[$i]== 0 and $this->branch_no!='' and $this->activity_no[$i]!=''  and $this->the_month != '') { // create
                    $req_seq = $this->{$this->MODEL_NAME}->create($this->_postedData(null ,$this->branch_no ,$this->activity_no[$i] ,$this->the_month ,$this->monthly_target[$i] ,$this->discount_value[$i] ,$this->added_value[$i] ,'create'));
                    if (intval($req_seq) <= 0) {
                        $this->print_error($req_seq);
                    }
                } elseif ($this->ser[$i] != 0 and $this->branch_no!='' and $this->activity_no[$i]!=''  and $this->the_month != '') { // edit
                    $req_seq = $this->{$this->MODEL_NAME}->edit($this->_postedData($this->ser[$i] ,$this->branch_no ,$this->activity_no[$i] ,$this->the_month ,$this->monthly_target[$i] ,$this->discount_value[$i] ,$this->added_value[$i] ,'edit'));
                    if (intval($req_seq) <= 0) {
                        $this->print_error($req_seq);
                    }
                }
            }
            echo $this->branch_no.'/'.$this->the_month ;

        }
    }

    function get( $branch, $month){
        $result= $this->{$this->MODEL_NAME}->get($branch,$month);
        if((count($result)==0))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['master_tb_data']=$result;
        $data['content']='active_month_target_show';
        $data['title']='بيانات المستهدف الشهري للأنشطة';
        $this->_look_ups($data);
        $this->load->view('template/template1',$data);
    }

    function _look_ups(&$data){

        $this->load->model('hr_attendance_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');

        $data['add_type'] = $this->constant_details_model->get_list(483);
        $data['activity_no'] = $this->constant_details_model->get_list(492);
        $data['branches'] = $this->gcc_branches_model->get_all();
        //$data['branches'] = $this->constant_details_model->get_list(429);
        $data['branches_options']='<option value="0">_________</option>';
        foreach ($data['branches'] as $row) :
            $data['branches_options']=$data['branches_options'].'<option value="'.$row['NO'].'">'.$row['NAME'].'</option>';
        endforeach;

        $data['activity_no_options']='<option value="0">_________</option>';
        foreach ($data['activity_no'] as $row) :
            $data['activity_no_options']=$data['activity_no_options'].'<option value="'.$row['CON_NO'].'">'.$row['CON_NAME'].'</option>';
        endforeach;


        $data['current_date'] = date("Ym");
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    function _post_validation($isEdit = false){

        for($i=0; $i<count($this->activity_no); $i++) {
            if ( $this->branch_no == 0 ){
                $this->print_error('يجب اختيار المقر ');
            }elseif($this->the_month == '' ){
                $this->print_error('يجب ادخال الشهر..');
            }elseif($this->activity_no[$i] == 0 ){
                $this->print_error('يجب اختيار النشاط..');
            }elseif($this->monthly_target[$i] == '' ){
                $this->print_error('يجب ادخال المستهدف الشهري..');
            }elseif($this->discount_value[$i] == '' ){
                $this->print_error('يجب ادخال قيمة الخصم..');
            }
        }

    }

    private function adopt($case){

        $res = $this->{$this->MODEL_NAME}->adopt($this->branch_no ,$this->the_month , $case);

        if(intval($res) <= 0){
            $this->print_error('لم تتم العملية '.'<br>'.$res);
        }
        return 1;
    }

    function adopt_10(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->branch_no!='' and  $this->the_month !='' ){
            echo $this->adopt(10);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }


    function adopt_11(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->branch_no!='' and  $this->the_month !='' ){
            echo $this->adopt(11);
        }else
            echo "لم يتم ارسال رقم الطلب";
    }

    function _postedData( $ser = null ,$branch_no = null ,$activity_no = null ,$the_month = null ,$monthly_target = null ,$discount_value = null  ,$added_value =null ,$typ= null){
        $result = array(
            array('name'=>'TARGET_NO','value'=> $ser,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_NO','value'=> $branch_no,'type'=>'','length'=>-1),
            array('name'=>'ACTIVITY_NO','value'=>$activity_no ,'type'=>'','length'=>-1),
            array('name'=>'THE_MONTH','value'=> $the_month,'type'=>'','length'=>-1),
            array('name'=>'MONTHLY_TARGET','value'=>$monthly_target ,'type'=>'','length'=>-1),
            array('name'=>'DISCOUNT_VALUE','value'=>$discount_value ,'type'=>'','length'=>-1),
            array('name'=>'ADDED_VALUE','value'=>$added_value ,'type'=>'','length'=>-1)
        );

        if($typ=='create')
            array_shift($result);
        return $result;
    }
}