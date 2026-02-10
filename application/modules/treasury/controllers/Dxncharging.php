<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 5/2/2020
 * Time: 9:39 AM
 */

class  DxnCharging extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'DEXCEN_CHARGE_PKG';
    }

    function Index()
    {

        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['title'] = 'شحن الدكسن';
        $data['content'] = 'dxn_charging_index';
        $data["rows"] = $this->rmodel->getList('CHARGE_MONTH_TB_LIST', "  ", 0, 1000);

        $this->_generate_std_urls($data, true);
        $this->load->view('template/template', $data);
    }

    function Charge()
    {
        if ($this->_is_posted()) {

            $data_arr = array(
                array('name' => 'EMP_NO', 'value' => $this->p_emp_no, 'type' => '', 'length' => -1),
            );

            $rs = $this->rmodel->insert('DEXCEN_CHARGE', $data_arr);

            if (intval($rs) <= 0)
                $this->print_error('فشل بحفظ البيانات' . $rs);

            //get the employee data , subscriber no
            $employee = $this->rmodel->getList('DEXCEN_EMPLOYEES_TB_LIST', " and M.is_Active = 1 and M.emp_no =  " . $this->p_emp_no, 0, 1000);


            if (is_array($employee) && count($employee) > 0) {
                //send the value to meter by subscriber no
                $token = $this->sendToMeter($employee[0]['SUB_NO']);

                if ($token != null) {

                    $data_arr = array(
                        array('name' => 'SER', 'value' => $rs, 'type' => '', 'length' => -1),
                        array('name' => 'TOKEN', 'value' => $token, 'type' => '', 'length' => -1),
                    );
                    //update the token to database
                    $updateToken = $this->rmodel->insert('DEXCEN_CHARGE_UPDATE', $data_arr);
                    if (intval($updateToken) <= 0)
                        $this->print_error('فشل بحفظ TOKEN ');
                    $this->sendSms($employee[0]['JWAL_NO'], $token);
                }
            }


            echo $rs;

        } else {

            $data["employess"] = $this->rmodel->getList('DEXCEN_EMPLOYEES_TB_LIST', " and M.is_Active = 1 ", 0, 1000);
            $data['title'] = 'بيانات الشحنة';
            $this->load->view('dxn_charging_charge', $data);
        }
    }




    // Edit Function
    function sendToMeter($subscriber)
    {

        $some_data = array(
            'no' => $subscriber,
            'cost' => $subscriber == '9999999' ? 1 : 100,// in live should 100
        );


        $curl = curl_init();
        // Security Key
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer aU03NGQ3WkRGNW9wNkRCbnxZOVQ1Mmc4Z3pBZ0hrOHBaenBQRzRYbHQzakNZZGxlM2VjeW84N1B3Z1c5Q0diNkVVOVN1VFNtVWxUcEpHbDhS',
            'guid: dxn_api_gedco_2019',
            'Content-Type: application/json',
        ));
        // We POST the data
        curl_setopt($curl, CURLOPT_POST, 1);
        // Set the url path we want to call
        curl_setopt($curl, CURLOPT_URL, 'http://192.168.0.171:802/apis/v1/SendCostToDXNLocalEmployee');
        // Make it so the data coming back is put into a string
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Insert the data
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($some_data));
        // Send the request
        $result = curl_exec($curl);
        // Free up the resources $curl is using
        curl_close($curl);
        ///
        $obj = json_decode($result, true);

        log_message('info', $obj);

        foreach ($obj["Data"] as $key => $value) {

            return $value;
        }

        return null;
    }


    function Employee()
    {

        $data['title'] = 'قائمة الموظفين';
        $data['content'] = 'dxn_charging_employee';

        $data["rows"] = $this->rmodel->getList('DEXCEN_EMPLOYEES_TB_LIST', "  ", 0, 1000);

        $this->_generate_std_urls($data, true);
        $this->load->view('template/template', $data);
    }

    function Create_employee()
    {

        if ($this->_is_posted()) {

            $data_arr = array(
                array('name' => 'EMP_NO', 'value' => $this->p_emp_no, 'type' => '', 'length' => -1),
                array('name' => 'SUB_NO', 'value' => $this->p_sub_no, 'type' => '', 'length' => -1),
                array('name' => 'EMP_NAME', 'value' => $this->p_emp_name, 'type' => '', 'length' => -1),
                array('name' => 'JWAL_NO', 'value' => $this->p_jwal_no, 'type' => '', 'length' => -1),
                array('name' => 'IS_ACTIVE', 'value' => 1, 'type' => '', 'length' => -1),
            );

            $rs = $this->rmodel->insert('DEXCEN_EMPLOYEES_TB_INSERT', $data_arr);

            if (intval($rs) <= 0)
                $this->print_error('فشل بحفظ البيانات' . $rs);

            echo $rs;

        } else {
            $data['title'] = 'بيانات الموظف';
            $this->load->view('dxn_charging_create_employee', $data);
        }
    }

    function update_employee_status()
    {

        if ($this->_is_posted()) {

            $data_arr = array(
                array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
                array('name' => 'IS_ACTIVE', 'value' => $this->p_status, 'type' => '', 'length' => -1),
            );

            $rs = $this->rmodel->update('DEXCEN_EMPLOYEES_STATUS_UPDATE', $data_arr);

            if (intval($rs) <= 0)
                $this->print_error('فشل بحفظ البيانات' . $rs);

            echo $rs;

        }
    }

    function get_employee($id = 0)
    {
        if ($this->_is_posted()) {

            $data_arr = array(
                array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
                array('name' => 'EMP_NO', 'value' => $this->p_emp_no, 'type' => '', 'length' => -1),
                array('name' => 'SUB_NO', 'value' => $this->p_sub_no, 'type' => '', 'length' => -1),
                array('name' => 'EMP_NAME', 'value' => $this->p_emp_name, 'type' => '', 'length' => -1),
                array('name' => 'JWAL_NO', 'value' => $this->p_jwal_no, 'type' => '', 'length' => -1),
                array('name' => 'IS_ACTIVE', 'value' => null, 'type' => '', 'length' => -1),
            );

            $rs = $this->rmodel->update('DEXCEN_EMPLOYEES_TB_UPDATE', $data_arr);

            if (intval($rs) <= 0)
                $this->print_error('فشل بحفظ البيانات' . $rs);

            echo $rs;

        } else {
            $data['title'] = 'بيانات الموظف';
            $data['employee'] = $this->rmodel->get('DEXCEN_EMPLOYEES_TB_GET', $id);
            $this->load->view('dxn_charging_create_employee', $data);
        }
    }


}