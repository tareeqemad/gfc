<?php

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 02/11/15
 * Time: 11:31 ص
 */
class Worker_Order_Loads extends MY_Controller
{
    var $MODEL_NAME = 'Worker_Order_model';

    function  __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('WorkOrder_model');
    }


    function index($id = 0)
    {


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


            for ($i = 0; $i < count($this->p_adapter_serial); $i++) {

                if ($this->p_partition_id[$i] == '') {
                    $this->print_error('يجب ادخال رقم ترتيب السكينة في المحول');
                } else if ($this->p_power_adapter_direction[$i] == '') {
                    $this->print_error('يجب  ادخال الاتجاه');
                } else if ($this->p_measure_r[$i] == '') {
                    $this->print_error('يجب  ادخال قياس التيار على مل فاز');
                } else if ($this->p_measure_s[$i] == '') {
                    $this->print_error('sيجب ادخال ال ');

                } else if ($this->p_measure_t[$i] == '') {
                    $this->print_error('tيجب ادخال ال ');

                } else if ($this->p_measure_n[$i] == '') {
                    $this->print_error('nيجب ادخال ال ');

                } else if ($this->p_voltage_rs[$i] == '') {
                    $this->print_error('rsيجب ادخال ال ');

                } else if ($this->p_voltage_rt[$i] == '') {
                    $this->print_error('rtيجب ادخال ال ');

                } else if ($this->p_voltage_st[$i] == '') {
                    $this->print_error('stيجب ادخال ال ');

                } else if ($this->p_voltage_rn[$i] == '') {
                    $this->print_error('rnيجب ادخال ال ');

                } else if ($this->p_voltage_sn[$i] == '') {
                    $this->print_error('snيجب ادخال ال ');

                } else if ($this->p_voltage_tn[$i] == '') {
                    $this->print_error('tnيجب ادخال ال ');

                }
                if ($this->p_ser[$i] <= 0) {

                    $x = $this->{$this->MODEL_NAME}->create($this->_postData(null, $this->p_work_order_id, $this->p_adapter_serial[$i],
                        $this->p_partition_id[$i],
                        $this->p_power_adapter_direction[$i],
                        $this->p_measure_r[$i],
                        $this->p_measure_s[$i],
                        $this->p_measure_t[$i],
                        $this->p_measure_n[$i],
                        $this->p_voltage_rs[$i],
                        $this->p_voltage_rt[$i],
                        $this->p_voltage_st[$i],
                        $this->p_voltage_rn[$i],
                        $this->p_voltage_sn[$i],
                        $this->p_voltage_tn[$i],
                        'create'
                    ));
                } else {


                    $x = $this->{$this->MODEL_NAME}->edit($this->_postData($this->p_ser[$i],null,$this->p_adapter_serial[$i],
                        $this->p_partition_id[$i],
                        $this->p_power_adapter_direction[$i],
                        $this->p_measure_r[$i],
                        $this->p_measure_s[$i],
                        $this->p_measure_t[$i],
                        $this->p_measure_n[$i],
                        $this->p_voltage_rs[$i],
                        $this->p_voltage_rt[$i],
                        $this->p_voltage_st[$i],
                        $this->p_voltage_rn[$i],
                        $this->p_voltage_sn[$i],
                        $this->p_voltage_tn[$i],
                        'edit'
                    ));

                    echo $x;
                }


            }
            // echo $x;
            echo intval($x);


        } else {


            $data['details'] = $this->{$this->MODEL_NAME}->get($id);
            $data['workOrderData'] = count($data['details']) <= 0 ? $this->WorkOrder_model->get($id) : null;
            $data['workOrderData'] = count( $data['workOrderData']) >0 ? $data['workOrderData'][0] : null;

            $data['content'] = 'Worker_Order_Loads_index';
            $data['work_order_id'] = $id;
            $data['title'] = 'قياس الاحمال';
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $data['help'] = $this->help;
            $this->_look_ups($data);
            $this->load->view('template/view', $data);

        }


    }



    function get($id)
    {
        $result = $this->{$this->MODEL_NAME}->get($id);
        //$data['details'] = $this->{$this->DETAILS_MODEL_NAME}->get_details_all($id);
        //  $this->print_error($id);
        if (!(count($result) == 1))
            die();
        $data['details'] = $result;
        $data['content'] = 'Worker_Order_Loads_index';
        $data['action'] = 'index';

        $data['title'] = 'عرض بيانات قياس الاحمال';

        $data['isCreate'] = false;

        $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['ENTRY_USER'] && $data['action'] == 'edit') ? true : false : false;
        $data['help'] = $this->help;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);

    }
    function delete_details()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->p_id != '') {
            $res = $this->{$this->MODEL_NAME}->delete($this->p_id, $this->p_work_id,$this->p_user_entry);

            /* if (intval($res) < 0) {
                 //$this->print_error('لم يتم الحذف'.'<br>'.$res);
                 //echo $res;
                 echo $res;
             } else if (intval($res) == 0) {
                 echo $res;
             }else echo 1;*/
            echo intval($res);
        } else
            echo "لم يتم ارسال رقم المحول";
    }


    function _look_ups(&$data)
    {


        $data['power_adapter_direction'] = $this->constant_details_model->get_list(48);

    }

    function _postData($ser = null, $work_order_id = null, $adapter_serial, $partition_id = null, $partition_direction = null, $measure_r = null, $measure_s = null, $measure_t = null,
                       $measure_n = null, $voltage_rs = null, $voltage_rt = null, $voltage_st = null, $voltage_rn = null, $voltage_sn = null, $voltage_tn = null,
                       $typ = null)
    {


        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'WORK_ORDER_ID', 'value' => $work_order_id, 'type' => '', 'length' => -1),
            array('name' => 'ADAPTER_SERIAL', 'value' => $adapter_serial, 'type' => '', 'length' => -1),
            array('name' => 'PARTITION_ID', 'value' => $partition_id, 'type' => '', 'length' => -1),
            array('name' => 'PARTITION_DIRECTION', 'value' => $partition_direction, 'type' => '', 'length' => -1),
            array('name' => 'MEASURE_R', 'value' => $measure_r, 'type' => '', 'length' => -1),
            array('name' => 'MEASURE_S', 'value' => $measure_s, 'type' => '', 'length' => -1),
            array('name' => 'MEASURE_T', 'value' => $measure_t, 'type' => '', 'length' => -1),
            array('name' => 'MEASURE_N', 'value' => $measure_n, 'type' => '', 'length' => -1),
            array('name' => 'VOLTAGE_RS', 'value' => $voltage_rs, 'type' => '', 'length' => -1),
            array('name' => 'VOLTAGE_RT', 'value' => $voltage_rt, 'type' => '', 'length' => -1),
            array('name' => 'VOLTAGE_ST', 'value' => $voltage_st, 'type' => '', 'length' => -1),
            array('name' => 'VOLTAGE_RN', 'value' => $voltage_rn, 'type' => '', 'length' => -1),
            array('name' => 'VOLTAGE_SN', 'value' => $voltage_sn, 'type' => '', 'length' => -1),
            array('name' => 'VOLTAGE_TN', 'value' => $voltage_tn, 'type' => '', 'length' => -1)

        );

        if ($typ == 'create')
            unset($result[0]);
        else {
            unset($result[1]);
            //unset($result[2]);
        }


        return $result;

    }

}