<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 4/10/2019
 * Time: 8:03 AM
 */

class DataMigration extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');

        $this->rmodel->package = 'DATA_MIGRATION';

    }


    /**
     * constants data
     */
    function _lookUps_data(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }

    function index()
    {

        $this->_lookUps_data($data);

        $data['content'] = 'data_migration_index';
        $data['title'] = ' افتتاح السنة المالية';
        $this->load->view('template/template', $data);

    }


    function financialAccounts()
    {

         $result = $this->rmodel->insert('TRANSFER_FIN_INIT_BALANCE', array());

        echo $result;
    }


    function transferOutcomeCheck()
    {

        $result = $this->rmodel->insert('TRANSFER_OUTCOME_CHECKS', array());

        echo $result;
    }

    function transferIncomeCheck()
    {

        $result = $this->rmodel->insert('TRANSFER_INCOME_CHECKS', array());

        echo $result;
    }

    function fixProblem()
    {

        $this->_lookUps_data($data);

        $data['content'] = 'fix_problem_index';
        $data['title'] = ' معالجة الاخطاء';
        $this->load->view('template/template', $data);

    }

    function changeIncomeCheck()
    {
        $result = $this->rmodel->update('INCOME_VOUCHER_UP_CHECK_ID', array(
            array('name' => 'VOUCHER_ID_IN', 'value' => $this->p_voucher_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_ID_IN', 'value' => $this->p_old_check, 'type' => '', 'length' => -1),
            array('name' => 'NEW_CHECK_ID_IN', 'value' => $this->p_new_check, 'type' => '', 'length' => -1),
            array('name' => 'NEW_DATE_IN', 'value' => $this->p_new_date, 'type' => '', 'length' => -1),

        ));

        echo $result;
    }


    function changeOutcomeCheck()
    {
        $result = $this->rmodel->update('OUTCOME_VOUCHER_UP_CHECK_ID', array(
            array('name' => 'VOUCHER_ID_IN', 'value' => $this->p_payment_id, 'type' => '', 'length' => -1),
            array('name' => 'CHECK_ID_IN', 'value' => $this->p_old_check, 'type' => '', 'length' => -1),
            array('name' => 'NEW_CHECK_ID_IN', 'value' => $this->p_new_check, 'type' => '', 'length' => -1),

        ));

        echo $result;
    }

}