<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 24/04/19
 * Time: 10:52 ص
 */
class carMovements extends MY_Controller
{

    var $PKG_NAME = "FLEET_PKG";

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'fleet_pkg';
        //this for constant using
        $this->load->model('customer_elect_details_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/currency_model');
        $this->load->model('settings/gcc_branches_model');
    }

    function index($page = 1)
    {
        $data['title'] = 'حركة السيارات';
        $data['content'] = 'cars_movments_index';
        $data['page'] = $page;
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        $data['currency'] = $this->currency_model->get_list();
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('../leaflet/leaflet/dist/leaflet.css');
        add_css('../leaflet/leaflet-routing-machine/dist/leaflet-routing-machine.css');
        add_css('../leaflet/fullscreen/leaflet.fullscreen.css');

        add_js('../leaflet/leaflet/dist/leaflet.js');
        add_js('../leaflet/leaflet-routing-machine/dist/leaflet-routing-machine.js');
        add_js('../leaflet/polyline/src/polyline.js');
        add_js('../leaflet/fullscreen/Leaflet.fullscreen.min.js');
        add_js('../leaflet/lrm-google.js');
        //add_js('gmaps.js');

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['movement_purpose'] = $this->constant_details_model->get_list(268);
        $data['movement_type'] = $this->constant_details_model->get_list(269);

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }

    function tracking()
    {
        //add_js('gmaps.js');
        add_js('stanzaio_8.4.3.bundle.js');
        add_js('jquery.xmpp.js');
        add_js('mainFunction.js');

        $data['title'] = 'تتبع الحركة';
        $data['content'] = 'tracking_ost_index';
        $data['driver'] = $this->rmodel->getData('CAR_GPS_TRACHING_DRIVER_NAME');
        $this->_lookup($data);

        $this->load->view('template/template', $data);
    }

    /**********************************public_get_speed********************************/

    function public_get_speed(){

        $driver_name = $this->input->post('driver_name');
        $movment_date = $this->input->post('movment_date');

        $sql="WHERE TRUNC(ENTRY_DATE)  = '$movment_date' AND EMP_NO = $driver_name";

        $result = $this->rmodel->getListBySQL('CARS_GPS_TRACING_SPEED_GET',$sql);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /*********************************public_get_movment********************************/

    function public_get_movment(){
        $driver_name = $this->input->post('driver_name');
        $movment_date = $this->input->post('movment_date');
        $sql="WHERE TRUNC(ENTRY_DATE)  = '$movment_date' AND EMP_NO = $driver_name";
        $result = $this->rmodel->getListBySQL('CARS_GPS_TRACING_MOVEMENT_GET',$sql);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }



    /****************************public_get_car_movements_det********************************/

    function public_list_car_movements_det($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['rows'] = $this->rmodel->get('CARS_MOVMENTS_DET_TB_GET_ALL', $id);

        $this->load->view('car_movement_det_page', $data);
    }

    /****************************get_page function****************************************/
    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("payment/carmovements/get_page/");

        $sql = isset($this->p_car_id) && $this->p_car_id ? " AND M.car_id= {$this->p_car_id} " : '';
        $sql .= isset($this->p_the_date) && $this->p_the_date ? " AND M.THE_DATE like '%{$this->p_the_date}%' " : "";
        $sql .= isset($this->p_driver_id) && $this->p_driver_id ? " AND M.DRIVER_ID LIKE '%{$this->p_driver_id}%' " : "";
        $sql .= isset($this->p_movment_type) && $this->p_movment_type != null ? " AND  M.MOVMENT_TYPE ={$this->p_movment_type}" : "";


        $count_rs = $this->get_table_count(" CARS_MOVMENTS_TB M  where 1=1 {$sql}");


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
        $offset = ((($page - 1) * $config['per_page']));
        $row = (($page * $config['per_page']));
        $data["rows"] = $this->rmodel->getList('CARS_MOVMENTS_TB_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('car_movment_page', $data);
    }

    /*******************************************************************************/
    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ser = $this->rmodel->insert('CARS_MOVMENTS_TB_INSERT', $this->_postedData());

            if ($this->ser < 1) {
                $this->print_error('لم يتم الحفظ' . '<br>');
            } else {

                echo intval($this->ser);
            }


        } //  echo $this->cars_model->create($this->_postedData());

        else {

            $data['title'] = 'تعديل بيانات السيارة';
            $data['action'] = 'index';
            $data['isCreate'] = true;
            $data['content'] = 'car_movment_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    function edit()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $rs = $this->rmodel->update('CARS_MOVMENTS_TB_UPDATE', $this->_postedData(false));

            if (intval($rs) <= 0) {
                $this->print_error(' فشل بحفظ البيانات ' . $rs);
            }

            echo $rs;
        }

    }

    function create_details()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $rs = $this->rmodel->insert('CARS_MOVMENTS_DET_TB_INSERT', $this->_posteddata_details(null, $this->p_car_mov_id,
                $this->p_expected_leave_time, $this->p_expected_arrival_time, $this->p_from_address, $this->p_to_address,
                $this->p_predefine_start_gps, $this->p_predefine_finished_gps, $this->p_purpose_type,
                $this->p_notes));

            if (intval($rs) <= 0) {
                $this->print_error('فشل بحفظ البيانات ' . $rs);
            } else {

                $this->_notify_movement($rs);
            }

            echo $rs;

        } else {

            $data['title'] = 'تعديل بيانات السيارة';
            $data['action'] = 'index';
            $data['isCreate'] = true;
            $data['content'] = 'car_movment_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    function _notify_movement($id)
    {
        // Prepare new cURL resource
        $ch = curl_init('http://apps.gedco.ps/apis/fleet/NotifyMovement?id=' . $id);
        // Submit the POST request
        $result = curl_exec($ch);
        // Close cURL session handle
        curl_close($ch);
    }

    function public_test($id)
    {
        $this->_notify_movement($id);
    }

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'CAR_ID', 'value' => $this->p_car_id, 'type' => '', 'length' => -1),
            array('name' => 'DRIVER_ID', 'value' => $this->p_driver_id, 'type' => '', 'length' => -1),
            array('name' => 'MOVMENT_TYPE', 'value' => $this->p_movment_type, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
            array('name' => 'THE_DATE', 'value' => $this->p_the_date, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'CAR_OWNER', 'value' => $this->p_car_owner, 'type' => '', 'length' => -1),
        );
        ///

        if ($isCreate)
            array_shift($result);

        return $result;
    }

    function _posteddata_details($ser = null, $car_mov_id = null, $expected_leave_time = null, $expected_arrival_time = null,
                                 $from_address = null, $to_address = null, $predefine_start_gps = null,
                                 $predefine_finished_gps = null, $purpose_type = null, $notes = null,
                                 $isCreate = true)
    {


        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CAR_MOV_ID', 'value' => $car_mov_id, 'type' => '', 'length' => -1),
            array('name' => 'EXPECTED_LEAVE_TIME', 'value' => $expected_leave_time, 'type' => '', 'length' => -1),
            array('name' => 'EXPECTED_ARRIVAL_TIME', 'value' => $expected_arrival_time, 'type' => '', 'length' => -1),
            array('name' => 'FROM_ADDRESS', 'value' => $from_address, 'type' => '', 'length' => -1),
            array('name' => 'TO_ADDRESS', 'value' => $to_address, 'type' => '', 'length' => -1),
            array('name' => 'PREDEFINE_START_GPS', 'value' => $predefine_start_gps, 'type' => '', 'length' => -1),
            array('name' => 'PREDEFINE_FINISHED_GPS', 'value' => $predefine_finished_gps, 'type' => '', 'length' => -1),
            array('name' => 'PURPOSE_TYPE', 'value' => $purpose_type, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $notes, 'type' => '', 'length' => -1),

        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }

    function get($id)
    {

        $result = $this->rmodel->get('CARS_MOVMENTS_TB_GET', $id);

        $data['title'] = 'بيانات الحركة';
        $data['content'] = 'car_movment_show';
        $data['result'] = $result;
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);


    }

    function public_get_car_movements_det($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $result_det = $this->rmodel->get('CARS_MOVMENTS_DET_TB_GET', $id);
        if (count($result_det) > 0) {
            echo json_encode($result_det[0]);
        } else {
            echo '';
        }

    }

    function public_select_driver($txt = null)
    {

        $data['title'] = 'فهرس السائقين';
        $data['content'] = 'driver_select_view';
        $offset = 0;
        $row = 2000;
        $result = $this->rmodel->getList('CAR_DRIVE_NAME_LIST', "", $offset, $row);
        $data['rows'] = $result;
        $data['txt'] = $txt;
        $this->load->view('template/view', $data);

    }

    function changeStatus()
    {

        $rs = $this->rmodel->update('CARS_MOVMENTS_TB_UPDATE_STATUS', $this->_postedDatastatus(false));
        if (intval($rs) <= 0) {
            $this->print_error('error' . '<br>' . $rs);
        }
        echo 1;

    }

    function _postedDatastatus($isCreate = true)
    {
        $newStatus = 0;
        $result = array(
            array('name' => 'SER', 'value' => $this->p_id, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $newStatus, 'type' => '', 'length' => -1),
        );

        if ($isCreate)
            array_shift($result);

        return $result;
    }


}
