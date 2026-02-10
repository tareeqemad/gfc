<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Achievement_follow_up_details extends MY_Controller
{

    var $MODEL_NAME = 'Achievement_follow_up_details_model';
    var $PAGE_URL = 'purchases/Achievement_follow_up_details/get_page';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
    }

    function get_page()
    {
        $data['master_ser'] = $this->input->post('ser');
        $data['get_list'] = $this->{$this->MODEL_NAME}->get_list($data['master_ser']);
        $this->_look_ups($data);
        $this->load->view('Achievement_follow_up_details_page', $data);
    }

    function _look_ups(&$data)
    {
        $this->load->model('settings/constant_details_model');
        $data['help'] = $this->help;
        $data['status'] = $this->constant_details_model->get_list(494);
        /*add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_js('jquery.hotkeys.js');*/


    }

    function public_get_id()
    {
        $tb_no = $this->input->post('tb_no');
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($tb_no, $id);
        $result = json_encode($result);

        echo $result;
    }

    function public_get_id_com()
    {
        $tb_no = $this->input->post('tb_no');
        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($tb_no, $id);
        $result = json_encode($result);

        $result = str_replace('subs', 'children', $result);
        $result = str_replace('ACCOUNT_ID', 'id', $result);
        $result = str_replace('ACOUNT_NAME', 'text', $result);

        echo $result;
    }

    function edit()
    {
        for ($i = 0; $i < count($this->p_ser_dets); $i++) {


            if ($this->p_ser_dets[$i] <= 0 || $this->p_ser_dets[$i] == '') {


                if ($this->p_achive_num[$i] != '') {


                    $x = $this->{$this->MODEL_NAME}->create($this->_postedData(null, $this->p_achive_ser[$i], $this->p_achive_num[$i], $this->p_status_dets[$i], 'create'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }

                }


            } else {

                if ($this->p_achive_num[$i] != '') {


                    $y = $this->{$this->MODEL_NAME}->edit($this->_postedData($this->p_ser_dets[$i], $this->p_achive_ser[$i], $this->p_achive_num[$i], $this->p_status_dets[$i], 'edit'));

                    if (intval($y) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $y);
                    }

                }


            }


        }
        echo 1;
        // $result = $this->{$this->MODEL_NAME}->edit($this->_postedData());
        //$this->Is_success($result);
        // echo  modules::run($this->PAGE_URL);
    }

    function create()
    {

        for ($i = 0; $i < count($this->p_ser_dets); $i++) {


           if ($this->p_ser_dets[$i] <= 0 || $this->p_ser_dets[$i] == '') {



                if ($this->p_achive_num[$i] != '') {


                    $x = $this->{$this->MODEL_NAME}->create($this->_postedData(null, $this->p_achive_ser[$i], $this->p_achive_num[$i], $this->p_status_dets[$i], 'create'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }

                }


            }

        }
        echo 1;
    }


    function _postedData($ser_dets, $achive_ser, $achive_num, $status_dets, $typ = null)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser_dets, 'type' => '', 'length' => 4),
            array('name' => 'ACHIVE_SER', 'value' => $achive_ser, 'type' => '', 'length' => 4),
            array('name' => 'ACHIVE_NUM', 'value' => $achive_num, 'type' => '', 'length' => 4),
            array('name' => 'STATUS', 'value' => $status_dets, 'type' => '', 'length' => -1),
        );
        if ($typ == 'create')
            unset($result[0]);
        return $result;
    }

    function delete()
    {
        $id = $this->input->post('id');
        $TB_NO = $this->input->post('tb_no');
        $this->IsAuthorized();
        $msg = 0;
        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->{$this->MODEL_NAME}->delete($TB_NO, $val);
            }
        } else {
            $msg = $this->{$this->MODEL_NAME}->delete($TB_NO, $id);
        }

        if ($msg == 1) {
            echo modules::run($this->PAGE_URL);
        } else {
            $this->print_error_msg($msg);
        }
    }
}

?>
