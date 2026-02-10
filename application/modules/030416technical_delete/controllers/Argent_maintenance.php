<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 03/08/15
 * Time: 08:32 ص
 */


class Argent_Maintenance extends MY_Controller{
    function  __construct(){
        parent::__construct();
        $this->load->model('Argent_Maintenance_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
    }


    function _lookUps_data(&$data){
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['PROBLEM_TYPE'] = $this->constant_details_model->get_list(92);
        $data['help']=$this->help;
        $this->_loadDatePicker();
    }
    /**
     *
     * index action perform all functions in view of Argent_Maintenance_show view
     */
    function index($page = 1){


        $data['title']='إدارة الصيانة الطارئة ';
        $data['content']='Argent_Maintenance_index';
        $data['page']=$page;

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $this->load->view('template/template',$data);

    }

    function get_page($page = 1){

        $this->load->library('pagination');


        $sql =isset($this->p_branch) && $this->p_branch != null ?" AND M.BRANCH_ID = {$this->p_branch} " : '';
        $sql .=isset($this->p_problem_description) && $this->p_problem_description != null ?" AND M.PROBLEM_DESCRIPTION LIKE '%{$this->p_problem_description}%' " : '';
        $sql .=isset($this->p_customer_name) && $this->p_customer_name != null ?" AND M.CUSTOMER_NAME = {$this->p_customer_name} " : '';
        $sql .=isset($this->p_problem_type) && $this->p_problem_type!= null ?" AND M.PROBLEM_TYPE = {$this->p_problem_type} " : '';

        $sql .=isset($this->p_date_from) && $this->p_date_from != null && isset($this->p_date_to) && $this->p_date_to != null?" AND M.MISSION_PROCESS_DATE between  {$this->p_date_from}  AND  {$this->p_date_to}  " : '';

        $config['base_url'] = base_url("technical/argent_maintenance/get_page/");


        $count_rs = $this->get_table_count(' Argent_Maintenance_TB M ');


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

        $data["rows"] = $this->Argent_Maintenance_model->get_list($sql, $offset , $row );


        $data['offset']=$offset+1;
        $data['page']=$page;


        $this->load->view('Argent_Maintenance_page',$data);

    }

    function public_index($text= null,$page = 1){


        $data['title']='إدارة الصيانة الطارئة ';
        $data['content']='Argent_Maintenance_select';
        $data['page']=$page;
        $data['text']=$text;

        $this->_loadDatePicker();

        $this->_lookUps_data($data);

        $this->load->view('template/view',$data);

    }

    function public_get_page($text= null,$page = 1){

        $this->load->library('pagination');


        $sql =isset($this->p_branch) && $this->p_branch != null ?" AND M.BRANCH_ID = {$this->p_branch} " : '';
        $sql .=isset($this->p_problem_description) && $this->p_problem_description != null ?" AND M.PROBLEM_DESCRIPTION LIKE '%{$this->p_problem_description}%' " : '';
        $sql .=isset($this->p_customer_name) && $this->p_customer_name != null ?" AND M.CUSTOMER_NAME = {$this->p_customer_name} " : '';
        $sql .=isset($this->p_problem_type) && $this->p_problem_type!= null ?" AND M.PROBLEM_TYPE = {$this->p_problem_type} " : '';

        $sql .=isset($this->p_date_from) && $this->p_date_from != null && isset($this->p_date_to) && $this->p_date_to != null?" AND M.MISSION_PROCESS_DATE between  {$this->p_date_from}  AND  {$this->p_date_to}  " : '';

        $config['base_url'] = base_url("technical/argent_maintenance/public_index/$text");


        $count_rs = $this->get_table_count(' Argent_Maintenance_TB M ');


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

        $data["rows"] = $this->Argent_Maintenance_model->get_list($sql, $offset , $row );


        $data['offset']=$offset+1;
        $data['page']=$page;


        $this->load->view('Argent_Maintenance_page_select',$data);

    }

    /**
     * create action : insert new Argent_Maintenance data ..
     * receive post data of Argent_Maintenance
     *
     */
    function create(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result= $this->Argent_Maintenance_model->create($this->_postedData());

            if(intval($result) <=0){
                $this->print_error('فشل في حفظ البيانات');
            }

            for($i = 0;$i< count($this->p_d_class_id);$i++){
                if($this->p_SER[$i] <= 0)
                    $this->Argent_Maintenance_model->tools_create($this->_postedData_TOOLs(
                        $result,
                        $this->p_d_class_id[$i],
                        $this->p_d_class_unit[$i],
                        $this->p_d_class_count[$i],
                        $this->p_d_class_used_case[$i],

                        true
                    ));
            }
            ///********************************************

            for($i = 0;$i< count($this->p_d_employee_id);$i++){
                if($this->p_WSER[$i] <= 0)
                    $this->Argent_Maintenance_model->works_create($this->_postedData_works(
                        $result,
                        $this->p_d_employee_id[$i],
                        $this->p_d_hints[$i],
                        true
                    ));

            }

            echo $result;

        }else{
            $data['content']='Argent_Maintenance_show';
            $data['title']='إدارة الصيانة الطارئة';

            $this->_lookUps_data($data);

            $this->load->view('template/template',$data);
        }


    }

    /**
     * get project by id ..
     */
    function get($id){


        $result =  $this->Argent_Maintenance_model->get($id);


        $data['content']='Argent_Maintenance_show';
        $data['title']='إدارة الصيانة الطارئة';

        $data['result'] =$result;



        $data['can_edit']= count($result) > 0 && $result[0]['ENTRY_USER'] == $this->user->id && HaveAccess(base_url('technical/argent_maintenance/edit') );


        $this->_lookUps_data($data,null);



        $this->load->view('template/template',$data);
    }

    /**
     * edit action : update exists Argent_Maintenance data ..
     * receive post data of Argent_Maintenance
     * depended on Argent_Maintenance prm key
     */
    function edit(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result= $this->Argent_Maintenance_model->edit($this->_postedData(false));


            if(intval($result) <=0){
                $this->print_error('فشل في حفظ البيانات');
            }



            for($i = 0;$i< count($this->p_d_class_id);$i++){
                if($this->p_SER[$i] <= 0)
                    echo $this->Argent_Maintenance_model->tools_create($this->_postedData_TOOLs(
                        $this->p_argent_maintenance_id,
                        $this->p_d_class_id[$i],
                        $this->p_d_class_unit[$i],
                        $this->p_d_class_count[$i],
                        $this->p_d_class_used_case[$i],

                        true
                    ));
                else

                    $this->Argent_Maintenance_model->tools_edit($this->_postedData_TOOLs(
                        $result,
                        $this->p_d_class_id[$i],
                        $this->p_d_class_unit[$i],
                        $this->p_d_class_count[$i],
                        $this->p_d_class_used_case[$i],

                        false,
                        $this->p_SER[$i]
                    ));

            }
            ///********************************************

            for($i = 0;$i< count($this->p_d_employee_id);$i++){
                if($this->p_WSER[$i] <= 0)
                  echo  $this->Argent_Maintenance_model->works_create($this->_postedData_works(
                        $this->p_argent_maintenance_id,
                        $this->p_d_employee_id[$i],
                        $this->p_d_hints[$i],
                        true
                    ));
                else
                    $this->Argent_Maintenance_model->works_edit($this->_postedData_works(
                        $result,
                        $this->p_d_employee_id[$i],
                        $this->p_d_hints[$i],
                        false,
                        $this->p_WSER[$i]
                    ));

            }



            echo $result;

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
            array('name'=>'ARGENT_MAINTENANCE_ID','value'=>$this->p_argent_maintenance_id ,'type'=>'','length'=>-1),
            array('name'=>'PROBLEM_DESCRIPTION_','value'=>$this->p_problem_description ,'type'=>'','length'=>-1),
            array('name'=>'CUSTOMER_NAME','value'=>$this->p_customer_name ,'type'=>'','length'=>-1),
            array('name'=>'ADDRESS','value'=>$this->p_address ,'type'=>'','length'=>-1),
            array('name'=>'MOBILE','value'=>$this->p_mobile ,'type'=>'','length'=>-1),
            array('name'=>'TEL','value'=>$this->p_tel ,'type'=>'','length'=>-1),
            array('name'=>'MISSION_PROCESS_DATE','value'=>$this->p_mission_process_date ,'type'=>'','length'=>-1),
            array('name'=>'PROBLEM_DIAGONISTIC','value'=>$this->p_problem_diagonistic ,'type'=>'','length'=>-1),
            array('name'=>'PROBLEM_TYPE','value'=>$this->p_problem_type ,'type'=>'','length'=>-1),


        );

        if($isCreate){
            array_shift($result);
        }

        return $result;
    }


    function _postedData_TOOLs($argent_maintenance_id,$class_id,$class_unit,$class_count,$class_used_case,$isCreate ,$ser = null){

        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'ARGENT_MAINTENANCE_ID','value'=>$argent_maintenance_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_ID','value'=>$class_id ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_UNIT','value'=>$class_unit ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_COUNT','value'=>$class_count ,'type'=>'','length'=>-1),
            array('name'=>'CLASS_USED_CASE','value'=>$class_used_case ,'type'=>'','length'=>-1),


        );

        if(!$isCreate){
           unset($result[1]);
        }

        return $result;
    }


    function _postedData_works($argent_maintenance_id,$employee_id,$hints,$isCreate ,$ser = null){

        $result = array(
            array('name'=>'SER','value'=>$ser ,'type'=>'','length'=>-1),
            array('name'=>'ARGENT_MAINTENANCE_ID','value'=>$argent_maintenance_id ,'type'=>'','length'=>-1),
            array('name'=>'EMPLOYEE_ID','value'=>$employee_id ,'type'=>'','length'=>-1),
            array('name'=>'HINTS','value'=>$hints ,'type'=>'','length'=>-1),


        );

        if(!$isCreate){
            unset($result[1]);
        }
        return $result;
    }


    function public_get_tools($id){
        $data['CLASS_TYPE'] = $this->constant_details_model->get_list(93);
        $data['details'] = $this->Argent_Maintenance_model->tools_list($id);

        $this->load->view('Argent_Maintenance_tools',$data);
    }

    function public_get_works($id){
        $data['CLASS_TYPE'] = $this->constant_details_model->get_list(91);
        $data['details'] = $this->Argent_Maintenance_model->works_list($id);

        $this->load->view('Argent_Maintenance_works',$data);
    }

    function delete_tools(){
        echo  $this->Argent_Maintenance_model->delete_tools($this->p_id);
    }

    function delete_works(){
        echo  $this->Argent_Maintenance_model->delete_works($this->p_id);
    }
}