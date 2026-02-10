<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * Breakers: Ahmed Barakat
 * Date: 18/01/15
 * Time: 09:22 ص
 */

class Breakers extends MY_Controller{

    var $MODEL_NAME= 'Breakers_model';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
    }

    /**
     *
     * index action perform all functions in view of Breakerss_show view
     * from this view , can show Breakerss tree , insert new Breakers , update exists one and delete other ..
     *
     */
    function index($page = 1, $show_only= 0){


        $data['title']='إدارة القواطع';
        $data['content']='Breakers_index';
        $data['page']=$page;
        $data['show_only']=$show_only;

        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->gcc_branches_model->get_all();

        $this->load->view('template/template',$data);

    }

    function get_page($page = 1, $show_only= 0){

        $this->load->library('pagination');
        $data['show_only']=  isset($this->p_show_only)?$this->p_show_only:$show_only;

        $config['base_url'] = base_url("projects/breakers/get_page/");

        $sql =($this->user->branch != 1? " AND  BRANCH = {$this->user->branch} ":"");

        $sql .= (isset($this->p_no) && $this->p_no !=null?" and Breakers_SERIAL ={$this->p_no} ":"");
        $sql .= (isset($this->p_name) && $this->p_name !=null?" and Breakers_NAME like '%{$this->p_name}%' ":"");
        $sql .= (isset($this->p_branch) && $this->p_branch !=null?" and BRANCH ={$this->p_branch} ":"");
        $sql .= (isset($this->p_Breakers_load_percent) && $this->p_Breakers_load_percent !=null?" and TECHNICAL_PKG.Breakers_LOAD_PERCENT(Breakers_SERIAL) {$this->p_Breakers_load_percent_op} {$this->p_Breakers_load_percent} ":"");
        $sql .= (isset($this->p_power_Breakers) && $this->p_power_Breakers !=null?" and POWER_Breakers ={$this->p_power_Breakers} ":"");

        $count_rs = $this->get_table_count(' BREAKERS_TB M ');


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

        $data["Breakerss"] = $this->Breakers_model->get_list($sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('Breakers_page',$data);

    }


    function public_index($txt,$page = 1){


        $data['title']='إدارة القواطع';
        $data['content']='Breakers_public_index';
        $data['page']=$page;

        $this->load->model('settings/gcc_branches_model');

        $data['txt'] = $txt;

        $data['branches'] = $this->gcc_branches_model->get_all();

        $this->load->view('template/view',$data);

    }

    function public_get_page($page = 1){

        $this->load->library('pagination');

        $config['base_url'] = base_url("projects/breakers/public_get_page/");

        $sql =($this->user->branch != 1? " AND  BRANCH = {$this->user->branch} ":"");

        $sql .= (isset($this->p_no) && $this->p_no !=null?" and Breakers_SERIAL ={$this->p_no} ":"");
        $sql .= (isset($this->p_name) && $this->p_name !=null?" and Breakers_NAME like '%{$this->p_name}%' ":"");
        $sql .= (isset($this->p_branch) && $this->p_branch !=null?" and BRANCH ={$this->p_branch} ":"");

        $count_rs = $this->Breakers_model->get_count($sql);

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

        $data["Breakerss"] = $this->Breakers_model->get_list($sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('Breakers_public_page',$data);

    }

    /**
     * get Breakers by id ..
     */
    function get_id($id = 0){

        $id = isset($this->p_id) ? $this->p_id : $id;
        $result = $this->Breakers_model->get($id);

        $data['content']='Breakers_show';
        $data['title']='إدارة القواطع - بيانات القاطع';

        $data['result'] =$result;

        $data['can_edit']= count($result) > 0 && ($result[0]['BRANCH_ID'] == $this->user->branch );


        $this->_look_ups($data,null);

        $this->load->view('template/template',$data);

    }

    // mkilani- data of Breakers
    function profile(){
        $data['title']='بياناتي';
        $data['content']='Breakerss_profile';
        $data['data']= $this->Breakers_model->get_Breakers_info($this->Breakers->id);
        $this->load->view('template/template',$data);
    }



    /**
     * create action : insert new Breakers data ..
     * receive post data of Breakers
     *
     */
    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result= $this->Breakers_model->create($this->_postedData());
            if(intval($result) <= 0)
                $this->print_error('فشل في حفظ البيانات');

            echo  modules::run('projects/breakers/get_page',1);
        }else{

            $data['content']='Breakers_show';
            $data['title']='إدارة القواطع - بيانات القاطع';
            $data['action'] = 'index';

            $this->_look_ups($data);

            $this->load->view('template/template',$data);
        }
    }

    function _look_ups(&$data,$date = null){

        $data['help']=$this->help;
        $data['OIL_ID_TYPE'] = $this->constant_details_model->get_list(81);
        $data['Breakers_VOLTAGE'] =  $this->constant_details_model->get_list(97);
        $data['POWER_Breakers'] =  $this->constant_details_model->get_list(138);
        $data['BREAKER_TYPE'] =  $this->constant_details_model->get_list(146);
        $data['COMPANY_NAME'] =  $this->constant_details_model->get_list(139);
        $data['COMPANY_PRODUCED'] =  $this->constant_details_model->get_list(147);

        $data['BREAKER_USED'] =  $this->constant_details_model->get_list(150);
        $data['ADAPTER_DIGIT_FUES'] =  $this->constant_details_model->get_list(151);
        $data['DIGITS_USED_TYPE'] =  $this->constant_details_model->get_list(148);
        $data['BREAKER_ISOLATE'] =  $this->constant_details_model->get_list(149);
        $data['DIGITS_UNUSED_TYPE'] =  $this->constant_details_model->get_list(148);
        $data['branches'] = $this->gcc_branches_model->get_all();



        $this->_loadDatePicker();

    }

    /**
     * edit action : update exists Breakers data ..
     * receive post data of Breakers
     * depended on Breakers prm key
     */
    function edit(){

        $result = $this->Breakers_model->edit($this->_postedData(false));

        for($i = 0;$i< count($this->p_partition_id);$i++){
            if($this->p_SER[$i] <= 0)
                $this->Breakers_model->partition_create($this->_postedPartitionData(
                    $this->p_Breakers_serial,
                    $this->p_partition_direction[$i],
                    $this->p_partition_id[$i],
                    $this->p_partition_power[$i],
                    $this->p_partition_capacity[$i],
                    $this->p_d_installation_date[$i],
                    $this->p_hint[$i],
                    true
                ));
            else
                $this->Breakers_model->partition_edit($this->_postedPartitionData(
                    $this->p_Breakers_serial,
                    $this->p_partition_direction[$i],
                    $this->p_partition_id[$i],
                    $this->p_partition_power[$i],
                    $this->p_partition_capacity[$i],
                    $this->p_d_installation_date[$i],
                    $this->p_hint[$i],
                    false,
                    $this->p_SER[$i]
                ));
        }

        $this->Is_success($result);

        echo  modules::run('projects/breakers/get_page',1);

    }

    /**
     * delete action : delete Breakers data ..
     * receive prm key as request
     *
     */
    function delete(){

        $id = $this->input->post('id');

        $this->IsAuthorized();

        $msg = 0;

        if(is_array($id)){
            foreach($id as $val){
                $msg=  $this->Breakers_model->delete($val);
            }
        }else{
            $msg=   $this->Breakers_model->delete($id);
        }

        if($msg == 1){
            echo  modules::run('projects/breakers/get_page',1);
        }else{

            $this->print_error_msg($msg);
        }
    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData($isCreate = true){

        $result = array(
            array('name'=>'BREAKER_ID','value'=>$this->p_breaker_id ,'type'=>'','length'=>-1),
            array('name'=>'BRANCH_ID','value'=>$this->p_branch_id,'type'=>'','length'=>-1),
            array('name'=>'BREAKER_NAME','value'=>$this->p_breaker_name,'type'=>'','length'=>-1),
            array('name'=>'BREAKER_CODE','value'=>$this->p_breaker_code,'type'=>'','length'=>-1),
            array('name'=>'BREAKER_TYPE','value'=>$this->p_breaker_type,'type'=>'','length'=>-1),
            array('name'=>'COMPANY_PRODUCED','value'=>$this->p_company_produced,'type'=>'','length'=>-1),
            array('name'=>'FACTOR_CODE','value'=>$this->p_factor_code,'type'=>'','length'=>-1),
            array('name'=>'PRODUCT_YEAR','value'=>$this->p_product_year,'type'=>'','length'=>-1),
            array('name'=>'WORK_INDATE','value'=>$this->p_work_indate,'type'=>'','length'=>-1),
            array('name'=>'BREAKER_USED','value'=>$this->p_breaker_used,'type'=>'','length'=>-1),
            array('name'=>'ADAPTER_DIGIT_FUES','value'=>$this->p_adapter_digit_fues,'type'=>'','length'=>-1),
            array('name'=>'COORDINATION','value'=>$this->p_coordination,'type'=>'','length'=>-1),
            array('name'=>'GPSX','value'=>$this->p_gpsx,'type'=>'','length'=>-1),
            array('name'=>'GPSY','value'=>$this->p_gpsy,'type'=>'','length'=>-1),
            array('name'=>'DIGITS_USED','value'=>$this->p_digits_used,'type'=>'','length'=>-1),
            array('name'=>'DIGITS_UNUSED','value'=>$this->p_digits_unused,'type'=>'','length'=>-1),
            array('name'=>'DIGITS_USED_TYPE','value'=>$this->p_digits_used_type,'type'=>'','length'=>-1),
            array('name'=>'DIGITS_UNUSED_TYPE','value'=>$this->p_digits_unused_type,'type'=>'','length'=>-1),
            array('name'=>'BREAKER_ISOLATE','value'=>$this->p_breaker_isolate,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$this->p_hints,'type'=>'','length'=>-1),

        );


        if($isCreate)
            array_shift($result);


        return $result;
    }

    function _postedPartitionData($Breakers_serial,$partition_direction,$partition_id,$partition_power,$partition_capacity,$installation_date,$hint,$isCreate ,$ser = null){

        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'Breakers_SERIAL','value'=>$Breakers_serial ,'type'=>'','length'=>-1),
            array('name'=>'PARTITION_DIRECTION','value'=>$partition_direction,'type'=>'','length'=>-1),
            array('name'=>'PARTITION_ID','value'=>$partition_id,'type'=>'','length'=>-1),
            array('name'=>'PARTITION_POWER','value'=>$partition_power,'type'=>'','length'=>-1),
            array('name'=>'PARTITION_CAPACITY','value'=>$partition_capacity,'type'=>'','length'=>-1),
            array('name'=>'INSTALLATION_DATE','value'=>$installation_date,'type'=>'','length'=>-1),
            array('name'=>'HINT','value'=>$hint,'type'=>'','length'=>-1),

        );

        if($isCreate)
            array_shift($result);



        return $result;
    }


    function public_get_partitions($id){
        $data['power_Breakers_direction'] = $this->constant_details_model->get_list(48);
        $data['details'] = $this->Breakers_model->partitions_list($id);

        $this->load->view('Breakers_partitions',$data);
    }


    function delete_partition(){
        echo  $this->Breakers_model->partition_delete($this->p_id);
    }


}