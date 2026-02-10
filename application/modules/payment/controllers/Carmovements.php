<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 24/04/19
 * Time: 10:52 ص
 */
class Carmovements extends MY_Controller
{
    var $MODEL_NAME= 'root/rmodel';
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

        $this->order_no= $this->input->post('order_no');
        $this->emp_name= $this->input->post('emp_name');
        $this->movment_status= $this->input->post('movment_status');
        $this->task_cost= $this->input->post('task_cost');
        $this->office_id= $this->input->post('office_id');
        $this->car_type= $this->input->post('car_type');
        $this->distance= $this->input->post('distance');
        $this->car_owner= $this->input->post('car_owner');
        $this->the_date= $this->input->post('the_date');
        $this->driver_id= $this->input->post('driver_id');
        $this->token= $this->input->post('token');
        $this->emp_no= $this->input->post('emp_no');

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
        $data['emp_branch_selected'] = $this->user->branch;
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
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        //add_js('gmaps.js');
        $this->load->model('hr_attendance/hr_attendance_model');

        $data['car_drive_name_company'] = json_encode($this->rmodel->getAll('FLEET_PKG', 'CAR_DRIVE_NAME_COMPANY_LIST'));
        $data['car_drive_name_rented'] = json_encode($this->rmodel->getAll('FLEET_PKG', 'CAR_DRIVE_NAME_RENTED_LIST'));
        $data['car_owner'] = $this->rmodel->getAll('FLEET_PKG', 'CARS_OWNER_LIST');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['movement_purpose'] = $this->constant_details_model->get_list(268);
        $data['movement_type'] = $this->constant_details_model->get_list(269);
        $data['movement_status'] = $this->constant_details_model->get_list(270);
        $data['office_name'] = $this->constant_details_model->get_list(387);
        $data['car_type'] = $this->constant_details_model->get_list(386);
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child( '', 'hr_admin' );

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }

    function tracking()
    {
        //add_js('gmaps.js');
        add_js('stanzaio_8.4.3.bundle.js');
        add_js('jquery.xmpp.js');
        add_js('mainFunction.js');

        $data['title'] = 'تتبع الحركات المسجلة';
        $data['content'] = 'tracking_ost_index';
        $data['driver'] = $this->rmodel->getData('CAR_GPS_TRACHING_DRIVER_NAME');
        $this->_lookup($data);

        $this->load->view('template/template', $data);
    }

    function get_track($id){

        //add_js('gmaps.js');
        add_js('stanzaio_8.4.3.bundle.js');
        add_js('jquery.xmpp.js');
        add_js('mainFunction.js');

        $result = $this->rmodel->get('CARS_GPS_TRACING_GET', $id);

        $data['title'] = 'تتبع الحركات المسجلة';
        $data['content'] = 'tracking_ost_page';
        $data['result'] = $result;
        $data['driver'] = $this->rmodel->getData('CAR_GPS_TRACHING_DRIVER_NAME');
        $data['action'] = 'edit';
        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }


    function tracking_live()
    {
        add_js('stanzaio_8.4.3.bundle.js');
        add_js('jquery.xmpp.js');
        add_js('mainFunction.js');

        $data['driver'] = $this->rmodel->getData('CAR_GPS_TRACHING_DRIVER_NAME');
        $data['title'] = 'تتبع الحركات المباشرة';
        $data['content'] = 'tracking_live_index';
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

        $movment_time_from = $this->input->post('movment_time_from');
        $movment_time_to = $this->input->post('movment_time_to');
        $From = $movment_date.' '.$movment_time_from ;
        $To = $movment_date.' '.$movment_time_to ;

        if ($movment_time_from != null and $movment_time_to != null ){

            $sql="AND T.ENTRY_DATE BETWEEN To_date ('$From', 'DD/MM/YYYY HH24:MI') AND To_date ('$To', 'DD/MM/YYYY HH24:MI') AND T.EMP_NO = $driver_name" ;

        }else{

            $sql="AND TRUNC(T.ENTRY_DATE)  = '$movment_date' AND T.EMP_NO = $driver_name";

        }

        $result = $this->rmodel->getListBySQL('CARS_GPS_TRACING_MOVEMENT_GET',$sql);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /*********************************public_get_movment_live********************************/

    function public_get_movment_live(){

        $driver_name = $this->input->post('driver_name');
        date_default_timezone_set('Asia/Jerusalem');
        $current_date = date("d/m/Y H:i");

        if ($driver_name != null ){

            $sql="AND  TRUNC(T.ENTRY_DATE,'MI')  = To_date ('$current_date', 'DD/MM/YYYY HH24:MI') AND T.EMP_NO = $driver_name";

        }else{

            $sql="AND  TRUNC(T.ENTRY_DATE,'MI')  = To_date ('$current_date', 'DD/MM/YYYY HH24:MI')";

        }

        $result = $this->rmodel->getListBySQL('CARS_GPS_TRACING_MOVEMENT_GET',$sql);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /****************************public_get_car_movements_det********************************/
    function public_movements_det($id = 0)
    {
        $id = isset($this->order_no) ? $this->order_no : $id;
        $data['rows'] = $this->rmodel->get('CARS_MOVMENTS_DET_TB_GET_MOVES', $id);

        $this->load->view('car_movments_det_moves_page', $data);

    }

    /****************************public_get_car_movements_det********************************/

    function public_list_car_movements_det($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['rows'] = $this->rmodel->get('CARS_MOVMENTS_DET_TB_GET_ALL', $id);

        $this->load->view('car_movement_det_page', $data);
    }

    /****************************public_get_car_movements_det********************************/

    function public_list_car_movements_det_after($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['rows'] = $this->rmodel->get('CARS_MOVMENTS_DET_TB_GET_ALL', $id);

        $this->load->view('car_movement_det_page_after', $data);
    }

    /****************************public_get_car_movements_det_map********************************/

    function public_list_car_movements_det_map($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data['rows'] = $this->rmodel->get('CARS_MOVMENTS_DET_TB_GET_ALL', $id);

        $this->load->view('car_movement_det_page_map', $data);
    }

    /****************************get_page function****************************************/
    function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("payment/Carmovements/get_page/");

        $sql = isset($this->p_car_id) && $this->p_car_id ? " AND M.car_id= {$this->p_car_id} " : '';
        $sql .= isset($this->p_driver_id) && $this->p_driver_id ? " AND M.DRIVER_ID LIKE '%{$this->p_driver_id}%' " : "";
        $sql .= isset($this->p_movment_type) && $this->p_movment_type != null ? " AND  M.MOVMENT_TYPE ={$this->p_movment_type}" : "";
        $sql .= isset($this->order_no) && $this->order_no != null ? " AND  M.ORDER_NO ={$this->order_no}" : "";
        $sql .= isset($this->emp_name) && $this->emp_name ? " AND M.EMP_NAME LIKE '%{$this->emp_name}%' " : "";
        $sql .= isset($this->movment_status) && $this->movment_status != null ? " AND  M.STATUS ={$this->movment_status}" : "";
        $sql .= isset($this->car_type) && $this->car_type != null ? " AND  M.CAR_TYPE ={$this->car_type}" : "";
        $sql .= isset($this->p_branches) && $this->p_branches != null ? " AND  M.EMP_NO  IN (SELECT T.EMP_NO FROM USERS_PROG_TB T WHERE T.BRANCH = {$this->p_branches} )" : "";
        $sql .= ($this->emp_no!= null)? " and EMP_NO= '{$this->emp_no}' " : '';
        $sql.= ($this->p_the_date!= null or $this->p_to_the_date!= null)? " AND TRUNC(M.DATE_MOVE) between nvl('{$this->p_the_date}','01/01/1000') and nvl('{$this->p_to_the_date}','01/01/3000') " : '';


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
        die;
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

                $Token = substr($this->token,8);

                $to = $Token;
                $notify = array(
                    'title' => 'مهمة جديدة',
                    'body' => ' الرجاء التوجه من '.$this->p_from_address.' الى  '.$this->p_to_address.' بتوقيت '.$this->p_expected_leave_time

                );

                $this->sendNotify($to ,$notify);

//                $this->_notify_movement($rs);

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
        $id = 55;
        $to = 'eT7RCfMEKJs:APA91bFOmnLYjuj-koWE2ziMPk_67uJpMCQ7NGN9mulpy86Ol56MFL9YYfhYCteC7I86x1kIu4b0-UuOABiCo0w7Q6CGO6WNLC8nuDlRctK_HpKsP5UCsKRy6pYtFSZ0umjHX6UXd_MX';

        $notify = array(
            'title' => 'fahim'.' '.$id,
            'body' => 'welcome'

        );

        $this->sendNotify($to , $notify);

        $this->_notify_movement($id);
    }

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'CAR_ID', 'value' => $this->car_owner, 'type' => '', 'length' => -1),
            array('name' => 'DRIVER_ID', 'value' => $this->driver_id, 'type' => '', 'length' => -1),
            array('name' => 'MOVMENT_TYPE', 'value' => $this->p_movment_type, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
            array('name' => 'THE_DATE', 'value' => $this->p_the_date, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'CAR_OWNER', 'value' => $this->car_owner, 'type' => '', 'length' => -1),
            array('name' => 'TASK_COST', 'value' => $this->task_cost, 'type' => '', 'length' => -1),
            array('name' => 'OFFICE_ID', 'value' =>$this->office_id, 'type' => '', 'length' => -1),
        );

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
            array('name' => 'EXPECTED_DISTANCE', 'value' => $this->distance, 'type' => '', 'length' => -1),

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


    function changeStatus_move_det()
    {

        $rs = $this->rmodel->update('CARS_MOVMENTS_DET_TB_U_STATUS', $this->_postedDatastatus(false));
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

    function sendNotify($to,$notify){

        $apiKey = "AAAAAFXDV-U:APA91bGpaOl6S1tasY1nDbHTfZibSRx6NXhjv0gc-CNxa3yBrQb8BdMUZwJWlMyP1hiAB6BRZqAtj23MBRbO5tPZ8-2VH689Kw5lE6l-0bcHYqNRf8TRaMpHHryQ9nf0Cg8I7tcKV3am	";

        $ch = curl_init();
        $url="https://fcm.googleapis.com/fcm/send";
        $fields = json_encode(array('to'=> $to , 'notification'=> $notify));

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
        curl_setopt($ch, CURLOPT_POST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($fields));

        $headers = array();
        $headers[] = 'Authorization: key ='.$apiKey;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

    }

}

