<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * adapter: Ahmed Barakat
 * Date: 18/01/15
 * Time: 09:22 ص
 */

class Adapter extends MY_Controller{

    var $MODEL_NAME= 'adapter_model';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
    }

    /**
     *
     * index action perform all functions in view of adapters_show view
     * from this view , can show adapters tree , insert new adapter , update exists one and delete other ..
     *
     */
    function index($page = 1, $show_only= 0){


        $data['title']='إدارة المحولات';
        $data['content']='adapter_index';
        $data['page']=$page;
        $data['show_only']=$show_only;

        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->gcc_branches_model->get_all();

        $this->load->view('template/template',$data);

    }

    function get_page($page = 1, $show_only= 0){

        $this->load->library('pagination');
        $data['show_only']=  isset($this->p_show_only)?$this->p_show_only:$show_only;

        $config['base_url'] = base_url("projects/adapter/get_page/");

        $sql =($this->user->branch != 1? " AND  BRANCH = {$this->user->branch} ":"");

        $sql .= (isset($this->p_no) && $this->p_no !=null?" and ADAPTER_SERIAL ={$this->p_no} ":"");
        $sql .= (isset($this->p_name) && $this->p_name !=null?" and ADAPTER_NAME like '%{$this->p_name}%' ":"");
        $sql .= (isset($this->p_branch) && $this->p_branch !=null?" and BRANCH ={$this->p_branch} ":"");
        $sql .= (isset($this->p_adapter_load_percent) && $this->p_adapter_load_percent !=null?" and TECHNICAL_PKG.ADAPTER_LOAD_PERCENT(ADAPTER_SERIAL) {$this->p_adapter_load_percent_op} {$this->p_adapter_load_percent} ":"");
        $sql .= (isset($this->p_power_adapter) && $this->p_power_adapter !=null?" and POWER_ADAPTER ={$this->p_power_adapter} ":"");

        $count_rs = $this->adapter_model->get_count($sql);

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

        $data["adapters"] = $this->adapter_model->get_list($sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('adapter_page',$data);

    }


    function public_index($txt,$page = 1){


        $data['title']='إدارة المحولات';
        $data['content']='adapter_public_index';
        $data['page']=$page;

        $this->load->model('settings/gcc_branches_model');

        $data['txt'] = $txt;

        $data['branches'] = $this->gcc_branches_model->get_all();

        $this->load->view('template/view',$data);

    }

    function public_get_page($page = 1){

        $this->load->library('pagination');

        $config['base_url'] = base_url("projects/adapter/public_get_page/");

        $sql =($this->user->branch != 1? " AND  BRANCH = {$this->user->branch} ":"");

        $sql .= (isset($this->p_no) && $this->p_no !=null?" and ADAPTER_SERIAL ={$this->p_no} ":"");
        $sql .= (isset($this->p_name) && $this->p_name !=null?" and ADAPTER_NAME like '%{$this->p_name}%' ":"");
        $sql .= (isset($this->p_branch) && $this->p_branch !=null?" and BRANCH ={$this->p_branch} ":"");

        $count_rs = $this->adapter_model->get_count($sql);

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

        $data["adapters"] = $this->adapter_model->get_list($sql, $offset , $row );

        $data['offset']=$offset+1;
        $data['page']=$page;

        $this->load->view('adapter_public_page',$data);

    }

    /**
     * get adapter by id ..
     */
    function get_id($id = 0){

        $id = isset($this->p_id) ? $this->p_id : $id;
        $result = $this->adapter_model->get($id);

        $data['content']='adapter_show';
        $data['title']='إدارة المحولات - بيانات المحول';

        $data['result'] =$result;

        $data['can_edit']= count($result) > 0 && ($result[0]['BRANCH'] == $this->user->branch );
        $data['locations'] = $this->adapter_model->adapter_location_get($id);

        $this->_look_ups($data,null);

        $this->load->view('template/template',$data);

    }

    // mkilani- data of adapter
    function profile(){
        $data['title']='بياناتي';
        $data['content']='adapters_profile';
        $data['data']= $this->adapter_model->get_adapter_info($this->adapter->id);
        $this->load->view('template/template',$data);
    }



    /**
     * create action : insert new adapter data ..
     * receive post data of adapter
     *
     */
    function create(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result= $this->adapter_model->create($this->_postedData());
            if(intval($result) <= 0)
                $this->print_error('فشل في حفظ البيانات');

            echo  modules::run('projects/adapter/get_page',1);
        }else{

            $data['content']='adapter_show';
            $data['title']='إدارة المحولات - بيانات المحول';
            $data['action'] = 'index';

            $this->_look_ups($data);

            $this->load->view('template/template',$data);
        }
    }

    function _look_ups(&$data,$date = null){

        $data['help']=$this->help;
        $data['OIL_ID_TYPE'] = $this->constant_details_model->get_list(81);
        $data['ADAPTER_VOLTAGE'] =  $this->constant_details_model->get_list(97);
        $data['POWER_ADAPTER'] =  $this->constant_details_model->get_list(138);
        $data['LINE_FEEDER'] =  $this->constant_details_model->get_list(90);
        $data['COMPANY_NAME'] =  $this->constant_details_model->get_list(139);

        $this->_loadDatePicker();

    }

    /**
     * edit action : update exists adapter data ..
     * receive post data of adapter
     * depended on adapter prm key
     */
    function edit(){

        $result = $this->adapter_model->edit($this->_postedData(false));

        for($i = 0;$i< count($this->p_partition_id);$i++){
            if($this->p_SER[$i] <= 0)
                $this->adapter_model->partition_create($this->_postedPartitionData(
                    $this->p_adapter_serial,
                    $this->p_partition_direction[$i],
                    $this->p_partition_id[$i],
                    $this->p_partition_power[$i],
                    $this->p_partition_capacity[$i],
                    $this->p_d_installation_date[$i],
                    $this->p_hint[$i],
                    true
                ));
            else
                $this->adapter_model->partition_edit($this->_postedPartitionData(
                    $this->p_adapter_serial,
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

        echo  modules::run('projects/adapter/get_page',1);

    }

    /**
     * delete action : delete adapter data ..
     * receive prm key as request
     *
     */
    function delete(){

        $id = $this->input->post('id');

        $this->IsAuthorized();

        $msg = 0;

        if(is_array($id)){
            foreach($id as $val){
                $msg=  $this->adapter_model->delete($val);
            }
        }else{
            $msg=   $this->adapter_model->delete($id);
        }

        if($msg == 1){
            echo  modules::run('projects/adapter/get_page',1);
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
            array('name'=>'ADAPTER_SERIAL','value'=>$this->p_adapter_serial ,'type'=>'','length'=>-1),
            array('name'=>'ADAPTER_NAME','value'=>$this->p_adapter_name,'type'=>'','length'=>-1),
            array('name'=>'POWER_ADAPTER','value'=>$this->p_power_adapter,'type'=>'','length'=>-1),
            array('name'=>'POWER_ADAPTER_SC','value'=>$this->p_power_adapter_sc,'type'=>'','length'=>-1),
            array('name'=>'GIS_ID','value'=>$this->p_gis_id,'type'=>'','length'=>-1),
            array('name'=>'GIS_X','value'=>$this->p_gis_x,'type'=>'','length'=>-1),
            array('name'=>'GIS_Y','value'=>$this->p_gis_y,'type'=>'','length'=>-1),
            array('name'=>'FACTORY_SERIAL','value'=>$this->p_factory_serial,'type'=>'','length'=>-1),
            array('name'=>'POSITION_TYPE','value'=>$this->p_position_type,'type'=>'','length'=>-1),
            array('name'=>'COMPANY_NAME','value'=>$this->p_company_name,'type'=>'','length'=>-1),
            array('name'=>'MANUFACTORING_DATE','value'=>$this->p_manufactoring_date,'type'=>'','length'=>-1),
            array('name'=>'OIL_ID','value'=>$this->p_oil_id,'type'=>'','length'=>-1),
            array('name'=>'INSTALLATION_DATE','value'=>$this->p_installation_date,'type'=>'','length'=>-1),
            array('name'=>'LINE_FEEDER','value'=>$this->p_line_feeder,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$this->p_hints,'type'=>'','length'=>-1),
            array('name'=>'TAP_CHANGER_POSITION','value'=>$this->p_tap_changer_position,'type'=>'','length'=>-1),
            array('name'=>'ADAPTER_VOLTAGE','value'=>$this->p_adapter_voltage,'type'=>'','length'=>-1),
            array('name'=>'ADDRESS_ID','value'=>$this->p_address_id,'type'=>'','length'=>-1),
            array('name'=>'TAP_CHANGER_COUNT','value'=>$this->p_tap_changer_count,'type'=>'','length'=>-1),
            array('name'=>'REGISTRATION_DATE','value'=>$this->p_registration_date,'type'=>'','length'=>-1),

        );


        if($isCreate)
            array_shift($result);


        return $result;
    }

    function _postedPartitionData($adapter_serial,$partition_direction,$partition_id,$partition_power,$partition_capacity,$installation_date,$hint,$isCreate ,$ser = null){

        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'ADAPTER_SERIAL','value'=>$adapter_serial ,'type'=>'','length'=>-1),
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
        $data['power_adapter_direction'] = $this->constant_details_model->get_list(48);
        $data['details'] = $this->adapter_model->partitions_list($id);

        $this->load->view('adapter_partitions',$data);
    }


    function delete_partition(){
        echo  $this->adapter_model->partition_delete($this->p_id);
    }


}