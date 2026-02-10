<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 03/07/17
 * Time: 10:08 ص
 */
class CustomerAccountInterface extends MY_Controller
{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('constant_details_model');
        $this->load->model('CustomerAccountInterface_model');

    }


    function _lookUps_data(&$data)
    {
        $data['INTERFACEs'] = $this->constant_details_model->get_list(155);
        $data['ACCCOUNTs'] = $this->constant_details_model->get_list(154);


    }


    function index($message = null)
    {

        $data['title'] = 'ربط الحسابات مع الشاشات';
        $data['content'] = 'customer_account_interface_index';
        $data['message'] = $message;
        $data['rows'] = $this->CustomerAccountInterface_model->getAll();

        $this->_lookUps_data($data);

        $this->load->view('template/template', $data);
    }


    function Create()
    {
        $rs = $this->CustomerAccountInterface_model->create($this->p_interface, $this->p_acccount);

        if (intval($rs) <= 0)
            $rs = 'error';
        else $rs = 'success';
 
        redirect('/settings/customeraccountinterface/index/' . $rs);

    }

    function Delete($id = 0)
    {

        $rs = $this->CustomerAccountInterface_model->delete($id);

        if (intval($rs) <= 0)
            $rs = 'error';
        else $rs = 'success';

        redirect('/settings/customeraccountinterface/index/' . $rs);
    }
}