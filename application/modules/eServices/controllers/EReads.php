<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/08/18
 * Time: 08:42 ص
 */
class EReads extends MY_Controller{

    var $MODEL_NAME= 'EReads_model';
    var $PAGE_URL= 'eServices/EReads/get_page';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
         $this->load->model('settings/gcc_branches_model');
                          }


    function index()
    {


        $data['title']='القراءات الإلكترونية';
        $data['content']='eReads_index';
        $data['help']=$this->help;
        //$data['year_indicator']=$this->year;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);




    }
    /***************************************************************************************************************************************/

    function get_page($page = 1)
    {

        $this->load->library('pagination');

         $where_sql = "";
 //echo $this->input->post('BRANCH');
         $where_sql .= isset($this->p_BRANCH) && $this->p_BRANCH != 0 ? " AND  R.BRANCH  ={$this->p_BRANCH}  " : "";
         $where_sql .= isset($this->p_FOR_MONTH) && $this->p_FOR_MONTH != null ? " AND R.FOR_MONTH ={$this->p_FOR_MONTH} " :" AND R.FOR_MONTH =".date("Ym");
         $where_sql .= isset($this->p_SUBSCRIBER) && $this->p_SUBSCRIBER != null ? " AND  R.SUBSCRIBER  ={$this->p_SUBSCRIBER}  " : "";
         $where_sql .= isset($this->p_ID) && $this->p_ID != null ? " AND  M.ID  ={$this->p_ID}  " : "";
         $where_sql .= isset($this->p_NAME) && $this->p_NAME != null ? " and T.NAME LIKE '%{$this->p_NAME}%'"  : '';
         $where_sql .= isset($this->p_BENIFICARY_NAME) && $this->p_BENIFICARY_NAME != null ? " and T.BENIFICARY_NAME LIKE '%{$this->p_BENIFICARY_NAME}%'"  : '';
       /* $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count(' SUBSCRIBER_READS R, SUBSCRIBERS@ebllc.ps T WHERE R.SUBSCRIBER = T.NO '.$where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] =  $page;

       $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open']= $config['next_tag_open']= $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close']= $config['next_tag_close']= $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = (((($page) - 1) * $config['per_page']));
        $row = ((($page) * $config['per_page']));*/
        $data['page_rows']=$this->{$this->MODEL_NAME}->SUBSCRIBER_READS_GET_LIST($where_sql, 0,100000);
        //$data['offset'] = $offset + 1;
        //$data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('eRead_page', $data);


    }

    /********************************************************************************************************************************/

    /********************************************************************************************************************************/
    function _look_ups(&$data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
add_js('bootstrap.min.js');
add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');

        if ($this->user->branch==1)
        {


            $data['branches'] = $this->gcc_branches_model->get_all();
        }
        else
        {

            $data['branches'] =$this->gcc_branches_model->user_branch($this->user->id);

        }

    }



}