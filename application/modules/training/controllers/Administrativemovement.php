<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 11/02/20
 * Time: 01:06 م
 */



class Administrativemovement extends MY_Controller
{

    var $PKG_NAME = "TRAIN_PKG";

    function __construct()
    {
        parent::__construct();

        $this->load->model('root/rmodel');
        $this->rmodel->package = 'TRAIN_PKG';
        //this for constant using
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
    }

    /************************************index*********************************************/

    function index($page = 1)
    {

        $data['title'] = 'الحركات الادارية';
        $data['content'] = 'administrativeMovement_index';

        $data['offset']=1;
        $data['page']=$page;

        $data['action'] = 'create';

        $this->_lookup($data);
        $this->load->view('template/template', $data);

    }

    /*************************************_lookup****************************************/

    function _lookup(&$data)
    {
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('combotree.css');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');

        $data['manage'] = $this->rmodel->getList('MANAGE_ALL', " ", 0, 2000);

        $data['contract_status'] = $this->constant_details_model->get_list(356);
        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }

    function get_page($page = 1)
    {

        $this->load->library('pagination');
        $config['base_url'] = base_url("training/administrativeMovement/get_page/");
        $sql = "";
        if(isset($this->p_contract_status)){
            if($this->p_contract_status == 2 ){
                $sql = "AND m.SER  in( SELECT SER FROM (select m.*,TO_CHAR(ADD_MONTHS(M.start_date, M.TRAINING_PERIOD)),TRAIN_PKG.GET_LAST_DATE(M.SER) from train_paid_tb m
                        where TO_CHAR(ADD_MONTHS(M.start_date, M.TRAINING_PERIOD), 'YYYYMMDD')<  TO_CHAR (Sysdate, 'YYYYMMDD')
                        MINUS
                        Select m.*,TO_CHAR(ADD_MONTHS(M.start_date, M.TRAINING_PERIOD)),TRAIN_PKG.GET_LAST_DATE(M.SER) from train_paid_tb m
                        where TO_CHAR(ADD_MONTHS(M.start_date, M.TRAINING_PERIOD), 'YYYYMMDD')<  TO_CHAR (Sysdate, 'YYYYMMDD')
                        and TO_CHAR(TRAIN_PKG.GET_LAST_DATE(M.SER), 'YYYYMMDD') >  TO_CHAR (Sysdate, 'YYYYMMDD')))" ;
            }else{
                $sql = "AND m.SER not in( SELECT SER FROM (select m.*,TO_CHAR(ADD_MONTHS(M.start_date, M.TRAINING_PERIOD)),TRAIN_PKG.GET_LAST_DATE(M.SER) from train_paid_tb m
                        where TO_CHAR(ADD_MONTHS(M.start_date, M.TRAINING_PERIOD), 'YYYYMMDD')<  TO_CHAR (Sysdate, 'YYYYMMDD')
                        MINUS
                        Select m.*,TO_CHAR(ADD_MONTHS(M.start_date, M.TRAINING_PERIOD)),TRAIN_PKG.GET_LAST_DATE(M.SER) from train_paid_tb m
                        where TO_CHAR(ADD_MONTHS(M.start_date, M.TRAINING_PERIOD), 'YYYYMMDD')<  TO_CHAR (Sysdate, 'YYYYMMDD')
                        and TO_CHAR(TRAIN_PKG.GET_LAST_DATE(M.SER), 'YYYYMMDD') >  TO_CHAR (Sysdate, 'YYYYMMDD')))" ;
            }

        }


        $sql .= isset($this->p_branch) && $this->p_branch ? " AND BRANCH= {$this->p_branch} " : '';
        $sql .= isset($this->p_manage) && $this->p_manage ? " AND MANAGE = {$this->p_manage} " : '';
        $sql .= isset($this->p_id) && $this->p_id ? " AND M.ID = {$this->p_id} " : '';
        $sql .= isset($this->p_name) && $this->p_name ? " AND TRAIN_PKG.GET_NAME (Q.FIRST_NAME,Q.SECOND_NAME,Q.THIRD_NAME,Q.LAST_NAME) LIKE '".add_percent_sign($this->p_name)."' " : "";
        $count_rs = $this->get_table_count(" TRAIN_PAID_TB where 1 = 1 ");


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

        $data["rows"] = $this->rmodel->getList('TRAIN_PAID_TB_LIST', " $sql ", $offset, $row);
        $data['rows_contract'] = $this->rmodel->getData('TRAIN_PAID_DEAILS_TB_GET_ALL');

        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('administrativeMovement_page', $data);
    }

    function get_attendance($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data["details"] = $this->rmodel->get('TRAIN_ATTEN_TB_CONTRACT_GET', $id);
        $this->load->view('administrativeMovement_details', $data);
    }

    function get_attendance_fin($id = 0)
    {
        $id = isset($this->p_id) ? $this->p_id : $id;
        $data["details"] = $this->rmodel->get('TRAIN_ATTEN_TB_CONTRACT_GET', $id);
        $this->load->view('administrativeMovement_fin_details', $data);
    }

    function edit(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $x = 0;
            for ($i = 0; $i < count($this->p_seq1); $i++)
            {

                if ($this->p_seq1[$i] <= 0 || $this->p_seq1[$i]=='' ) {
                    if ($this->p_attendees_days[$i] != 0  &&  $this->p_att_month[$i] != 0   ) {
                        $serDet=$this->rmodel->insert('TRAIN_ATTENDANCE_TB_INSERT', $this->_posteddata_details
                            (null, $this->p_trainee_ser , $this->p_absence_days[$i],
                                $this->p_attendees_days[$i] , $this->p_att_month[$i] ,
                                $this->p_absence_no_permission_days[$i] ,$this->p_notes[$i],'create') );

                        if (intval($serDet) <= 0) {
                            $this->print_error('لم يتم الحفظ' . '<br>' . $serDet);
                        }

                     }

                } else {
                    $x=$this->rmodel->update('TRAIN_ATTENDANCE_TB_UPDATE',$this->_posteddata_details
                        ($this->p_seq1[$i], $this->p_trainee_ser , $this->p_absence_days[$i],
                            $this->p_attendees_days[$i] , $this->p_att_month[$i] ,
                            $this->p_absence_no_permission_days[$i] ,$this->p_notes[$i],'edit') );

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }
                }
            }

                echo intval($x)!= 0 ? intval($x) : intval($serDet) ;

        }

    }

    function edit_fin(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $x = 0;
            for ($i = 0; $i < count($this->p_seq1); $i++)
            {
                $x=$this->rmodel->update('TRAIN_ATTENDANCE_FIN_UPDATE',$this->_posteddata_fin_details
                    ($this->p_seq1[$i],  $this->p_fin_absence_days[$i],
                        $this->p_fin_attendees_days[$i] ,$this->p_fin_absenceNoPermission_days[$i] ,
                        $this->p_fin_notes[$i],'edit') );
                if (intval($x) <= 0) {
                    $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                }
            }
        }
        echo intval($x) ;

    }

    function _posteddata_fin_details($ser = null,  $absence_days = null,
                                 $attendees_days = null ,$absence_days_no_permission = null,
                                 $notes=null, $type)
    {


        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'FIN_ABSENCE_DAYS', 'value' => $absence_days, 'type' => '', 'length' => -1),
            array('name' => 'FIN_ATTENDEES_DAYS', 'value' => $attendees_days, 'type' => '', 'length' => -1),
            array('name' => 'FIN_ABSENCE_DAYS_NO_PERMISSION', 'value' => $absence_days_no_permission, 'type' => '', 'length' => -1),
            array('name' => 'FIN_NOTES', 'value' => $notes, 'type' => '', 'length' => -1),

        );

        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

    function _posteddata_details($ser = null, $trainee_ser = null, $absence_days = null,
                                 $attendees_days = null , $att_month = null,
                                  $absence_days_no_permission = null,$notes=null, $type)
    {


        $result = array(
            array('name' => 'SEQ1', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'TRAINEE_SER', 'value' => $trainee_ser, 'type' => '', 'length' => -1),
            array('name' => 'ABSENCE_DAYS', 'value' => $absence_days, 'type' => '', 'length' => -1),
            array('name' => 'ATTENDEES_DAYS', 'value' => $attendees_days, 'type' => '', 'length' => -1),
            array('name' => 'ATT_MONTH', 'value' => $att_month, 'type' => '', 'length' => -1),
            array('name' => 'ABSENCE_DAYS_NO_PERMISSION', 'value' => $absence_days_no_permission, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $notes, 'type' => '', 'length' => -1),

        );

        if ($type == 'create')
            unset($result[0]);

        return $result;
    }

    function deleteAttendance(){

        $res= $this->rmodel->update('TRAIN_ATTENDANCE_TB_DELETE', $this->_postedData_delete($this->p_id, false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الحذف'.'<br>'.$res);
        }
        else
            echo intval($res);
    }

    function adoptAttendance(){

        $res= $this->rmodel->update('TRAIN_ATTENDANCE_TB_ADOPT', $this->_postedData_delete($this->p_id , false));
        if(intval($res) <= 0){
            $this->print_error('لم يتم الاعتماد'.'<br>'.$res);
        }
        else
            echo intval($res);
    }


    function _postedData_delete($ser = null , $isCreate = false)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser , 'type' => '', 'length' => -1),
        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }







}
