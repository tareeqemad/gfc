<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 18/10/2022
 * Time: 11:30 ص
 */
class Job_ads extends MY_Controller {


    var $PAGE_URL = 'internal_jobs/job_ads/get_page';

    function __construct()
    {
        parent::__construct();

        $this->load->model('settings/users_model');
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'INTERNAL_JOBS_PKG';


        $this->ser = $this->input->post('ser');
        $this->ads_name = $this->input->post('ads_name');
        $this->attachment_ser = $this->input->post('attachment_ser');
        $this->deadline = $this->input->post('deadline');
        $this->ads_description = $this->input->post('ads_description');

    }

    function index($page = 1)
    {

        $data['title'] = 'اعلانات الوظائف';
        $data['content'] = 'job_ads_index';
        $emp_data = $this->get_user_info($this->user->id);
        $data['emp_email'] = $emp_data[0]['EMAIL'];
        $data['page'] = $page;
        $this->load->view('template/template1', $data);
    }

     function get_page($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = 'where 1 = 1 ';
        $where_sql .= isset($this->p_ads_name) && $this->p_ads_name != null ? " AND  M.ADS_NAME like '%{$this->p_ads_name}%'  " : "";
        $where_sql .= 'AND GROUP_ADS = 7  ';



        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' JOB_ADS_TB M ' . $where_sql);
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
        $data["page_rows"] = $this->rmodel->getList('JOB_ADS_TB_LIST', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('job_ads_page', $data);
    }



    function create()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $data_arr = array(
                array('name' => 'ADS_NAME', 'value' => $this->ads_name, 'type' => '', 'length' => -1),
                array('name' => 'ADS_DESCRIPTION', 'value' => trim($this->ads_description), 'type' => '', 'length' => -1),
                array('name' => 'NOTES', 'value' => '', 'type' => '', 'length' => -1),
                array('name' => 'ATTACHMENT_SER', 'value' => $this->attachment_ser, 'type' => '', 'length' => -1),
                array('name' => 'DEADLINE', 'value' => $this->deadline , 'type' => '', 'length' => -1),

            );
            $res = $this->rmodel->insert('JOB_ADS_TB_INSERT', $data_arr);
            if (intval($res) >= 1) {
                echo intval($res);
            } else {
                $this->print_error('Error_' . $res);
            }
        }else{
            $data['title'] = 'اضافة اعلان جديد  ';
            $data['isCreate']= true;
            $data['action'] = 'index';
            $data['content'] = 'job_ads_show';
            $this->load->view('template/template1', $data);
        }

    }


    function edit()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_post_validation();
            $data_arr = array(
                array('name' => 'SER_IN', 'value' => $this->ser, 'type' => '', 'length' => -1),
                array('name' => 'ADS_NAME', 'value' => $this->ads_name, 'type' => '', 'length' => -1),
                array('name' => 'ADS_DESCRIPTION', 'value' => trim($this->ads_description), 'type' => '', 'length' => -1),
                array('name' => 'DEADLINE', 'value' => $this->deadline , 'type' => '', 'length' => -1),
                array('name' => 'STATUS', 'value' => 1 , 'type' => '', 'length' => -1),
            );
            $res = $this->rmodel->update('JOB_ADS_TB_UPDATE', $data_arr);
            if (intval($res) >= 1) {
                echo intval($res);
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }


    function get($id)
    {

        $result = $this->rmodel->get('JOB_ADS_TB_GET', $id);
        if(!(count($result)==1))
            die('get');
        $data['master_tb_data']=$result;
        $data['action'] = 'edit';
        $data['content']='job_ads_show';
        $data['title']='بيانات الاعلان  ';
        $this->load->view('template/template1', $data);
    }


    function _post_validation($isEdit = false){
        if( $this->ads_name ==''){
            $this->print_error('يجب ادخال الوظيفة');
        }elseif ($this->deadline =='') {
            $this->print_error('يجب ادخال تاريخ تاريخ نهاية التقديم على الاعلان الوظيفي');
        }/*elseif (trim($this->ads_description) =='') {
            $this->print_error('يجب ادخال الشروط');
        }*/
    }

    function indexUpload($id , $categories){
        add_js('ajax_upload_file.js');
        $data['rows']= $this->get_table_count(" GFC_ATTACHMENT_TB WHERE (UPPER(CATEGORY)='{$categories}' or UPPER(CATEGORY) like '{$categories}_sub%') AND IDENTITY ='{$id}'  ");
        $data['id'] = $id;
        $data['categories'] = $categories;
        $this->load->view('attachment_index',$data);
    }




}