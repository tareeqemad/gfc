<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class activity extends MY_Controller
{

    var $MODEL_NAME = 'activity_model';
    var $PAGE_URL = 'purchases/activity/get_page';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function index()
    {
        $data['title'] = 'فهرس الأنشطة';
        $data['content'] = 'activity_index';
        $data['get_all'] = $this->{$this->MODEL_NAME}->get_all();
        $data['count_all'] = $data['get_all'][0]['SER'];
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        $this->load->view('template/template', $data);
    }

    function get_page()
    {
        $data['get_all'] = $this->{$this->MODEL_NAME}->get_all();
        $this->load->view('activity_page', $data);
    }

    function get_id()
    {
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);
        $this->return_json($result);
    }

    function create()
    {
        $result = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
        if (intval($result) <= 0) {
            $this->print_error('لم يتم الحفظ' . '<br>' . $result);
        }
        echo modules::run($this->PAGE_URL);
    }

    function _postedData($typ = null)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->input->post('ser'), 'type' => '', 'length' => 5),
            array('name' => 'ACTIVITY_NAME', 'value' => $this->input->post('activity_name'), 'type' => '', 'length' => -1)
        );
        if ($typ == 'create') {
            array_shift($result);
        }
        return $result;
    }

    function edit()
    {
        $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
        $this->Is_success($result);
        echo modules::run($this->PAGE_URL);
    }

    function delete()
    {
        $id = $this->input->post('id');
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
            echo modules::run($this->PAGE_URL);
        } else {
            $this->print_error_msg($msg);
        }
    }
}

?>
