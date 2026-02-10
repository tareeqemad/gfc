<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class customers_activity extends MY_Controller
{

    var $MODEL_NAME = 'customers_activity_model';
    var $PAGE_URL = 'purchases/customers_activity/get_page';


    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('payment/customer_activities_model');
        $this->load->model('activity_model');

        $this->act_ser = $this->input->post('act_ser');
        $this->activity_no = $this->input->post('activity_no');
    }

    function index($page=1)
    {

        $data['title'] = 'أنشطة الموردون';
        $data['content'] = 'customer_activities_index';
        $data['activities'] = $this->activity_model->get_all();
        $data['page']=$page;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }
//////////////////////////////////////
    function get_page($page=1)
    {

        $this->load->library('pagination');
        $where_sql = " where 1=1  ";
        $where_sql .= isset($this->p_customer_id) && $this->p_customer_id != null ? " AND  M.CUSTOMER_ID ='{$this->p_customer_id}' " : "";
        $where_sql .= isset($this->p_activity_no) && $this->p_activity_no != null ? " AND  M.ACTIVITY_NO ='{$this->p_activity_no}'  " : "";
        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('CUSTOMER_ACTIVITIES_TB', $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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

        $offset = (((($page) - 1) * $config['per_page']));
        $row = ((($page) * $config['per_page']));
        $data['page_rows']=$this->{$this->MODEL_NAME}->get_list_all($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);

        $this->load->view('customer_activities_page', $data);

    }

    //////////////////////////////////////////////////////////
    /*function public_get_activites($id = 0)
    {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        $data['activities_details'] = $this->customer_activities_model->get_list($id);
        $data['activities_index'] = $this->activity_model->get_all();
        $this->_look_ups($data);
        $this->load->view('payment/customer_activites_details_page', $data);
    }*/
    ///////////////////////////////////////////////////////////////

    /*function get_activity_page()
    {
        $data['get_all'] = $this->{$this->MODEL_NAME}->get_cust_all();
        $this->load->view('customer_activities_page', $data);
    }*/

    function get($id){
        $result= $this->{$this->MODEL_NAME}->get_1($id);
        if((count($result)==0))
            die('get');
        $data['can_edit'] = 1;
        $data['action'] = 'edit';
        $data['result']=$result;
        $data['content']='customer_activities_show';
        $data['title'] = 'تنسيب الموردون للأنشطة   ';
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
    }

    /*function get_id()
    {
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get_1($id);
        $this->return_json($result);
    }*/


    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->activity_no); $i++) {

                if ($this->activity_no[$i] != '' ) { // create
                    $detail_seq = $this->customer_activities_model->create($this->_postedData_activite_details(null, $this->activity_no[$i], $this->p_customer_id_name, 'create'));
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                }

            }
            echo $this->p_customer_id_name;


        } ///////////////////////
        else {
            $data['content'] = 'customer_activities_show';
            $data['title'] = 'تنسيب الموردون للأنشطة   ';
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }

//////////////////////////////////////////////////////////////////////////////////
    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->activity_no); $i++) {

                if ($this->activity_no[$i] != '' and ($this->act_ser[$i] == 0 || $this->act_ser[$i] == '')) { // create
                    $detail_seq = $this->customer_activities_model->create($this->_postedData_activite_details(null, $this->activity_no[$i],$this->p_customer_id_name, 'create'));
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                } elseif ($this->activity_no[$i] != '' and ($this->act_ser[$i] != 0 || $this->act_ser[$i] != '')) { // edit

                    $detail_seq = $this->customer_activities_model->edit($this->_postedData_activite_details($this->act_ser[$i], $this->activity_no[$i],$this->p_customer_id_name, 'edit'));
                    if (intval($detail_seq) <= 0) {
                        $this->print_error($detail_seq);
                    }
                }
            }

            echo $this->p_customer_id_name;
        }
    }
/// //////////////////////////////////////////////////////////////////////////////
   /* function _postedData($typ = null)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->input->post('ser'), 'type' => '', 'length' => 5),
            array('name' => 'ACTIVITY_NAME', 'value' => $this->input->post('activity_name'), 'type' => '', 'length' => -1)
        );
        if ($typ == 'create') {
            array_shift($result);
        }
        return $result;
    }*/
///////////////////////////////////////////////////////

    function delete()
    {
        $id = $this->input->post('ser');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->delete($val);
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->delete($id);
        }

        if ($msg == 1) {
            echo 1;
        } else {
            $this->print_error_msg($msg);
        }
    }

    function _postedData_activite_details($ser = null, $act_no,$customer_id, $typ = null)
    {
        $result = array(
            array('name' => 'SER_SEQ', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER_ID', 'value' => $customer_id, 'type' => '', 'length' => -1),
            array('name' => 'ACTIVITY_NO', 'value' => $act_no, 'type' => '', 'length' => -1)
        );
        if ($typ == 'create')
            unset($result[0]);
        elseif ($typ == 'edit')
            unset($result[1]);

        return $result;
    }

    function _look_ups(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap.min.js');
        add_js('bootstrap-datetimepicker.js');
        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $data['customer_id'] = $this->{$this->MODEL_NAME}->get_customer_type(1);
        $data['help'] = $this->help;


    }
}

?>
