<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/25/14
 * Time: 10:01 AM
 */

class Employees extends MY_Controller{

    function  __construct(){
        parent::__construct();

        $this->load->model('employees_model');

    }

    /**
     *
     * index action perform all functions in view of users_show view
     * from this view , can show users tree , insert new user , update exists one and delete other ..
     *
     */
    function index($page = 1){



        add_css('combotree.css');
        add_css('datepicker3.css');

        add_js('jquery.hotkeys.js');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        $data['title']='إدارة الموظفين';
        $data['content']='employees_index';
        $data['page']=$page;

        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['Grads'] = $this->employees_model->get_gradesn();
        $data['Kaders'] = $this->employees_model->get_kader();

        $data['W_NO_DATA'] = $this->employees_model->get_lookUps('W_NO_DATA');
        $data['Q_NO_DATA'] = $this->employees_model->get_lookUps('Q_NO_DATA');
        $data['W_NO_ADMIN_DATA'] = $this->employees_model->get_lookUps('W_NO_ADMIN_DATA');
        $data['S_P_NO'] = $this->employees_model->get_lookUps('S_P_NO');
        $data['gradesn_DATA'] = $this->employees_model->get_lookUps('gradesn_DATA');

        $data['help']=$this->help;

        $this->load->view('template/template',$data);

    }

    function get_page($page = 1){

        $this->load->library('pagination');


        $count_rs = $this->employees_model->get_count(null);

        $config['base_url'] = base_url('employees/employees/index');
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs)? $count_rs[0]['NUM_ROWS']:0 ;
        $config['per_page'] = 1;
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

        $data["employees"] = $this->employees_model->get_list(null, $offset , $row );

        $this->load->view('employees_page',$data);

    }


    /**
     * get user by id ..
     */
    function get_id(){


        $page = $this->input->post('page');
        $no = $this->input->post('no');
        $name = $this->input->post('name');
        $branch = $this->input->post('branch');
        $gcc_st_no= $this->input->post('gcc_st_no');

        $sql  = ($no)?" and no ={$no} ": '';
        $sql  .= ($name)?" and name like '%{$name}%' ": '';
        $sql  .= ($gcc_st_no)?" and gcc_st_no ={$gcc_st_no} ": '';

        $count = $this->employees_model->get_count($branch,$sql);

        $count =count($count)? $count[0]['NUM_ROWS']:0;

        $result = $this->employees_model->get_list($branch,$page,$page +1,$sql);

        $this->date_format($result,'BIRTH_DATE');

        $rs = array('count'=>$count ,'result'=>$result);

        $this->return_json($rs);
    }

    /**
     * create action : insert new user data ..
     * receive post data of user
     *
     */
    function create(){

        $result= $this->employees_model->create($this->_postedData());
        $this->Is_success($result);
        echo  modules::run('employees/employees/get_page',1);

    }

    /**
     * edit action : update exists user data ..
     * receive post data of user
     * depended on user prm key
     */
    function edit(){

        $result = $this->employees_model->edit($this->_postedData());

        $this->Is_success($result);

        echo  $result;

    }

    /**
     * delete action : delete user data ..
     * receive prm key as request
     *
     */
    function delete(){

        $id = $this->input->post('id');

        $this->IsAuthorized();

        $msg = 0;

        if(is_array($id)){
            foreach($id as $val){
                $msg=  $this->employees_model->delete($val);
            }
        }else{
            $msg=   $this->employees_model->delete($id);
        }

        if($msg == 1){
            echo  modules::run('employees/employees/get_page',1);
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
    function _postedData(){

        //$this->load->library('encrypt');

        $no = $this->input->post('no');
        $name = $this->input->post('name');
        $birth_date =DateTime::createFromFormat($this->DATEFORMAT, $this->input->post('birth_date'))->format($this->SERVER_DATE_FORMAT);
        $emp_type = $this->input->post('emp_type');
        $kad_no = $this->input->post('kad_no');
        $degree = $this->input->post('degree');
        $periodical_allownces = $this->input->post('periodical_allownces');
        $hire_years= $this->input->post('hire_years');
        $premium_supervisory = $this->input->post('premium_supervisory');


        $job_allownce_pct= $this->input->post('job_allownce_pct');
        $job_allownce_pct_extra= $this->input->post('job_allownce_pct_extra');
        $comoany_alternative= $this->input->post('comoany_alternative');
        $gcc_st_no= $this->input->post('gcc_st_no');
        $branch= $this->input->post('branch');
        $w_no= $this->input->post('w_no');
        $q_no= $this->input->post('q_no');
        $sp_no= $this->input->post('sp_no');
        $w_no_admin= $this->input->post('w_no_admin');

        $result = array(
            array('name'=>'NO','value'=>$no ,'type'=>'','length'=>-1),
            array('name'=>'NAME','value'=>$name,'type'=>'','length'=>-1),
            array('name'=>'BIRTH_DATE','value'=>$birth_date,'type'=>'','length'=>-1),
            array('name'=>'EMP_TYPE','value'=>$emp_type,'type'=>'','length'=>-1),
            array('name'=>'KAD_NO','value'=>$kad_no,'type'=>'','length'=>-1),
            array('name'=>'DEGREE','value'=>$degree,'type'=>'','length'=>-1),
            array('name'=>'PERIODICAL_ALLOWNCES','value'=>$periodical_allownces,'type'=>'','length'=>-1),
            array('name'=>'HIRE_YEARS','value'=>$hire_years,'type'=>'','length'=>-1),
            array('name'=>'PREMIUM_SUPERVISORY','value'=>$premium_supervisory,'type'=>'','length'=>-1),
            array('name'=>'JOB_ALLOWNCE_PCT','value'=>$job_allownce_pct,'type'=>'','length'=>-1),
            array('name'=>'JOB_ALLOWNCE_PCT_EXTRA','value'=>$job_allownce_pct_extra,'type'=>'','length'=>-1),
            array('name'=>'COMOANY_ALTERNATIVE','value'=>$comoany_alternative,'type'=>'','length'=>-1),
            array('name'=>'GCC_ST_NO','value'=>$gcc_st_no,'type'=>'','length'=>-1),
            array('name'=>'BRANCH','value'=>$branch,'type'=>'','length'=>-1),
            array('name'=>'W_NO','value'=>$w_no,'type'=>'','length'=>-1),
            array('name'=>'Q_NO','value'=>$q_no,'type'=>'','length'=>-1),
            array('name'=>'SP_NO','value'=>$sp_no,'type'=>'','length'=>-1),
            array('name'=>'W_NO_ADMIN','value'=>$w_no_admin,'type'=>'','length'=>-1),

        );

        return $result;
    }

}