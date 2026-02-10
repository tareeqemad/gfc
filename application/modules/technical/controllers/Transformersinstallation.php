<?php

/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 08/04/18
 * Time: 08:54 ص
 */
class TransformersInstallation extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'LOAD_FLOW_PKG';
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
    }

    function index($page = 1)
    {
        $data['content'] = 'transformers_installation_index';
        $data['title'] = 'Technical Efficiency & Development Projects';
        $data['page'] = $page;
        $data['action'] = 'index';

        $this->_lookUps_data($data);

        $this->load->view('template/template', $data);
    }

    function get_page($page = 1, $action = 'get')
    {

        $this->load->library('pagination');

        $sql = " AND (M.BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  M.MONTH >= {$this->p_from_date} " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  M.MONTH <= {$this->p_to_date} " : "";


        $config['base_url'] = base_url("technical/transformersinstallation/get_page/");
        $count_rs = $this->get_table_count(' TRANSFORMERS_INSTALLATION_TB M WHERE 1 = 1 ' . $sql);

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

        $data["rows"] = $this->rmodel->getList('TRANSFORMERS_INSTALLATION_LIST', " $sql ", 0, 100);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['action'] = $action;
        $data['can_delete'] = '';

        $this->load->view('transformers_installation_page', $data);

    }

    function get($id)
    {

        $result = $this->rmodel->get('TRANSFORMERS_INSTALLATION_GET', $id);

        $data['title'] = 'Technical Efficiency & Development Projects ';
        $data['content'] = 'transformers_installation_show';
        $data['result'] = $result;
        $data['can_edit'] = count($result) > 0 && $result[0]['ENTRY_USER'] == $this->user->id;
        $data['action'] = 'update';

        $this->_lookUps_data($data);


        $this->load->view('template/template', $data);
    }

    /**
     * constants data
     */
    function _lookUps_data(&$data)
    {
        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);

        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['feeder_name'] = $this->constant_details_model->get_list(90);
        $data['donation_name'] = $this->constant_details_model->get_list(210);
        $data['lv_cb_direction'] = $this->constant_details_model->get_list(48);
        $data['POWER_ADAPTER'] = $this->constant_details_model->get_list(138);
        $data['items'] = $this->rmodel->get('PROJECT_ITEMS_GET', 1);

        //print_r($data['items']);

        //only height voltage items
        $data['items_lower'] = $this->filter_by_value($data['items'], 'TYPE', '2');

        $data['items'] = $this->filter_by_value($data['items'], 'TYPE', '1');

        $this->_loadDatePicker();
    }

    function create()
    {


        $data['content'] = 'transformers_installation_show';
        $data['title'] = 'Technical Efficiency & Development Projects ';
        $data['action'] = 'create';
        $this->_lookUps_data($data);

        $this->load->view('template/template', $data);
    }

    /**
     * actions :
     * create ,
     * update ,
     * delete ,
     */
    function action()
    {

        //check if http request is post , that mean insert action
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = null;

            $params = array(array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),);

            switch ($this->p_action) {

                case 'create':
                    $result = $this->_create();
                    echo $this->rmodel->update('COPY_TABLE', $params);
                    break;

                case 'update':
                    $result = $this->_edit();
                    echo $this->rmodel->update('COPY_TABLE', $params);

                    break;

                case 'delete':

                    break;


            }

            if (intval($result) <= 0) {
                $this->print_error($result);
            }
            //$this->print_error($result);
            echo $result;


        }


    }

    function _create()
    {
        $rs = $this->rmodel->insert('TRANSFORMERS_INSTALLATION_INS', $this->_postedData());

        if (intval($rs) > 0) {

            //insert transfer data
            for ($i = 0; $i < count($this->p_td_name); $i++) {

                $td_rs = $this->rmodel->insert('NTRANSFORMERDATA_TB_INSERT', $this->_postedDataTransformerData(
                    $this->p_td_name[$i],
                    $this->p_td_kva_rating[$i],
                    $this->p_td_mv_feeder_name[$i],
                    $rs,
                    0
                ));

                if (intval($td_rs) > 0) {
                    $index = $i + 1;

                    for ($ix = 0; $ix < count($this->p_td_length[$index]); $ix++) {
                        if ($this->p_td_length[$index][$ix] != null) {

                            $this->rmodel->insert('NTRANSFORMER_DETAILS_TB_INSERT', $this->_postedDataTransformerDataDetails(
                                $this->p_td_lv_cb_direction[$index][$ix],
                                $this->p_td_status[$index][$ix],
                                $this->p_td_cross_sections[$index][$ix],
                                $this->p_td_length[$index][$ix],
                                $this->p_td_expected_load[$index][$ix],
                                $td_rs,
                                0
                            ));


                        }
                    }
                }

            }
            //end:- insert transfer data


            //insert  Existing Loaded Transformers Before Load Mitigation
            for ($i = 0; $i < count($this->p_tbd_name); $i++) {

                $tbd_rs = $this->rmodel->insert('EXISTINGBEFORLDMITIGATION_INS', $this->_postedDataExistingLoadTransformer(
                    $this->p_tbd_name[$i],
                    $this->p_tbd_kva_rating[$i],
                    $this->p_tbd_mv_feeder_name[$i],
                    $rs,
                    0
                ));


                if (intval($tbd_rs) > 0) {


                    $index = $i + 1;

                    for ($ix = 0; $ix < count($this->p_tbd_length[$index]); $ix++) {
                        if ($this->p_tbd_lv_cb_direction[$index][$ix] != null
                            && $this->p_tbd_length[$index][$ix] != null
                            && $this->p_tbd_cross_sections[$index][$ix] != null)

                            $this->rmodel->insert('EXISTINGBLM_DETAILS_TB_INSERT', $this->_postedDataExistingLoadTransformerDetails(
                                $this->p_tbd_lv_cb_direction[$index][$ix],
                                $this->p_tbd_cross_sections[$index][$ix],
                                $this->p_tbd_length[$index][$ix],
                                $this->p_tbd_r[$index][$ix],
                                $this->p_tbd_s[$index][$ix],
                                $this->p_tbd_t[$index][$ix],
                                $this->p_tbd_n[$index][$ix],
                                $this->p_tbd_rs[$index][$ix],
                                $this->p_tbd_st[$index][$ix],
                                $this->p_tbd_rt[$index][$ix],
                                $tbd_rs,
                                0
                            ));

                    }

                }
            }

            //end:- insert  Existing Loaded Transformers Before Load Mitigation

            //insert    MV Conductors Data & Real Measurements
            for ($i = 0; $i < count($this->p_mc_b_length); $i++) {

                if ($this->p_mc_b_cross_section[$i] != null && $this->p_mc_b_length[$i] != null && $this->p_joints[$i] != null)
                    $this->rmodel->insert('MV_CONDUCTORS_DATA_TB_INSERT', $this->_postedMVConductors(
                        $this->p_mc_b_cross_section[$i],
                        $this->p_mc_b_length[$i],
                        $this->p_joints[$i],
                        $this->p_mc_b_avg_load[$i],
                        null,//$this->p_mc_a_avg_load[$i],
                        null,//$this->p_mc_a_v_ll_kv[$i],
                        $this->p_mc_loss[$i],
                        $this->p_mc_vd[$i],
                        $this->p_mc_vw_weakest_node[$i],
                        $this->p_mc_vd[$i],
                        $rs,
                        0
                    ));


            }
            //end:- insert    MV Conductors Data & Real Measurements

        }

        return $rs;
    }

    function _edit()
    {

        $rs = $this->rmodel->update('TRANSFORMERS_INSTALLATION_UP', $this->_postedData(false));
        //
        if (intval($rs) > 0) {


            //insert transfer data
            for ($i = 0; $i < count($this->p_td_name); $i++) {

                $td_rs = isset($this->p_td_ser) ? intval($this->p_td_ser[$i]) : 0;
                if ($td_rs == 0) {

                    $td_rs = $this->rmodel->insert('NTRANSFORMERDATA_TB_INSERT', $this->_postedDataTransformerData(
                        $this->p_td_name[$i],
                        $this->p_td_kva_rating[$i],
                        $this->p_td_mv_feeder_name[$i],
                        $this->p_ser,
                        0
                    ));
                } else {

                    $this->rmodel->update('NTRANSFORMERDATA_TB_UPDATE', $this->_postedDataTransformerData(
                        $this->p_td_name[$i],
                        $this->p_td_kva_rating[$i],
                        $this->p_td_mv_feeder_name[$i],
                        $this->p_ser,
                        $td_rs
                    ));
                }

                if (intval($td_rs) > 0) {

                    $index = $i + 1;

                    //first delete all details and insert new
                    $this->rmodel->delete('NTRANSFORMER_DETAILS_TB_DELETE', $td_rs);

                    if (isset($this->p_td_length[$index]))
                        for ($ix = 0; $ix < count($this->p_td_length[$index]); $ix++) {
                            if ($this->p_td_length[$index][$ix] != null) {

                                $this->rmodel->insert('NTRANSFORMER_DETAILS_TB_INSERT', $this->_postedDataTransformerDataDetails(
                                    $this->p_td_lv_cb_direction[$index][$ix],
                                    $this->p_td_status[$index][$ix],
                                    $this->p_td_cross_sections[$index][$ix],
                                    $this->p_td_length[$index][$ix],
                                    $this->p_td_expected_load[$index][$ix],
                                    $td_rs,
                                    0
                                ));


                            }
                        }
                }

            }
            //end:- insert transfer data

            //insert  Existing Loaded Transformers Before Load Mitigation
            for ($i = 0; $i < count($this->p_tbd_name); $i++) {

                $tbd_ser = isset($this->p_tbd_ser) ? intval($this->p_tbd_ser[$i]) : 0;

                if ($tbd_ser == 0) {

                    $tbd_ser = $this->rmodel->insert('EXISTINGBEFORLDMITIGATION_INS', $this->_postedDataExistingLoadTransformer(
                        $this->p_tbd_name[$i],
                        $this->p_tbd_kva_rating[$i],
                        $this->p_tbd_mv_feeder_name[$i],
                        $this->p_ser,
                        0
                    ));
                } else {

                    $this->rmodel->update('EXISTINGBEFORLDMITIGATION_UP', $this->_postedDataExistingLoadTransformer(
                        $this->p_tbd_name[$i],
                        $this->p_tbd_kva_rating[$i],
                        $this->p_tbd_mv_feeder_name[$i],
                        $this->p_ser,
                        $tbd_ser
                    ));
                }

                if (intval($tbd_ser) > 0) {
                    //first delete all details and insert new
                    //$this->rmodel->delete('EXISTINGBLM_DETAILS_TB_DELETE', $tbd_ser);

                    $index = $i + 1;

                    for ($ix = 0; $ix < count($this->p_tbd_length[$index]); $ix++) {
                        if ($this->p_tbd_lv_cb_direction[$index][$ix] != null && $this->p_tbd_length[$index][$ix] != null && $this->p_tbd_cross_sections[$index][$ix] != null)

                            $tbd_d_ser = $this->p_tbd_d_ser[$index][$ix];
                        $tbd_d_ser = isset($tbd_d_ser) ? $tbd_d_ser : 0;

                        $this->rmodel->insert($tbd_d_ser == 0 ? 'EXISTINGBLM_DETAILS_TB_INSERT' : 'EXISTINGBLM_DETAILS_TB_UPDATE', $this->_postedDataExistingLoadTransformerDetails(
                            $this->p_tbd_lv_cb_direction[$index][$ix],
                            $this->p_tbd_cross_sections[$index][$ix],
                            $this->p_tbd_length[$index][$ix],
                            $this->p_tbd_r[$index][$ix],
                            $this->p_tbd_s[$index][$ix],
                            $this->p_tbd_t[$index][$ix],
                            $this->p_tbd_n[$index][$ix],
                            $this->p_tbd_rs[$index][$ix],
                            $this->p_tbd_st[$index][$ix],
                            $this->p_tbd_rt[$index][$ix],
                            $tbd_ser,
                            $tbd_d_ser
                        ));

                    }
                }

            }
            //end:- insert  Existing Loaded Transformers Before Load Mitigation

            //insert   Existing Loaded Transformers After Load Mitigation
            if (isset($this->p_tad_name))
                for ($i = 0; $i < count($this->p_tad_name); $i++) {

                    $tad_ser = isset($this->p_tad_ser) ? intval($this->p_tad_ser[$i]) : 0;


                    $this->rmodel->update('EXISTINGAFTERLDMITIGATION_UP', $this->_postedDataExistingLoadTransformer(
                        $this->p_tad_name[$i],
                        $this->p_tad_kva_rating[$i],
                        $this->p_tad_mv_feeder_name[$i],
                        $this->p_ser,
                        $tad_ser
                    ));


                    if (intval($tad_ser) > 0) {


                        $index = $i + 1;

                        for ($ix = 0; $ix < count($this->p_tad_length[$index]); $ix++) {

                            if ($this->p_tad_lv_cb_direction[$index][$ix] != null
                                && $this->p_tad_length[$index][$ix] != null
                                && $this->p_tad_cross_sections[$index][$ix] != null)
                                $this->rmodel->insert('EALDM_DETAILS_TB_UPDATE', $this->_postedDataExistingALoadTransformerDetails(
                                    $this->p_tad_lv_cb_direction[$index][$ix],
                                    $this->p_tad_cross_sections[$index][$ix],
                                    $this->p_tad_length[$index][$ix],
                                    $this->p_tad_r[$index][$ix],
                                    $this->p_tad_s[$index][$ix],
                                    $this->p_tad_t[$index][$ix],
                                    $this->p_tad_n[$index][$ix],
                                    $tad_ser,
                                    $this->p_tad_d_ser[$index][$ix]
                                ));

                        }
                    }
                }
            //end:- insert   Existing Loaded Transformers After Load Mitigation

            //insert    MV Conductors Data & Real Measurements
            for ($i = 0; $i < count($this->p_mc_b_length); $i++) {

                if ($this->p_mc_b_cross_section[$i] != null && $this->p_mc_b_length[$i] != null && $this->p_joints[$i] != null)

                    if ($this->p_mc_ser[$i] == 0) {

                        $this->rmodel->insert('MV_CONDUCTORS_DATA_TB_INSERT', $this->_postedMVConductors(
                            $this->p_mc_b_cross_section[$i],
                            $this->p_mc_b_length[$i],
                            $this->p_joints[$i],
                            $this->p_mc_b_avg_load[$i],
                            null,//$this->p_mc_a_avg_load[$i],
                            null,//$this->p_mc_a_v_ll_kv[$i],
                            $this->p_mc_loss[$i],
                            $this->p_mc_vd[$i],
                            $this->p_mc_vw_weakest_node[$i],
                            $this->p_mc_vd[$i],
                            $this->p_ser,
                            0
                        ));

                    } else {

                        $this->rmodel->update('MV_CONDUCTORS_DATA_TB_UPDATE', $this->_postedMVConductors(
                            $this->p_mc_b_cross_section[$i],
                            $this->p_mc_b_length[$i],
                            $this->p_joints[$i],
                            $this->p_mc_b_avg_load[$i],
                            null,//$this->p_mc_a_avg_load[$i],
                            null,//$this->p_mc_a_v_ll_kv[$i],
                            $this->p_mc_loss[$i],
                            $this->p_mc_vd[$i],
                            $this->p_mc_vw_weakest_node[$i],
                            $this->p_mc_vd[$i],
                            $this->p_ser,
                            $this->p_mc_ser[$i]
                        ));
                    }

            }
            //end:- insert    MV Conductors Data & Real Measurements

            //insert   LV Networks

            if (isset($this->p_tld_name))
                for ($i = 0; $i < count($this->p_tld_name); $i++) {


                    $this->rmodel->update('LV_NETWORK_TB_UPDATE', $this->_postedDataLvNetworks(
                        $this->p_tld_name[$i],
                        $this->p_tld_kw_loss[$i],
                        $this->p_tld_kva_loss[$i],
                        $this->p_tld_llv[$i],
                        $this->p_tld_vd[$i],
                        $this->p_tlad_kw_loss[$i],
                        $this->p_tlad_kva_loss[$i],
                        $this->p_tlad_llv[$i],
                        $this->p_tlad_vd[$i],
                        $this->p_ser,
                        $this->p_tld_ser[$i]
                    ));


                }
            //end:- insert   LV Networks
        }

        return $rs;
    }

    function _postedData($isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC', 'value' => $this->p_project_tec, 'type' => '', 'length' => -1),
            array('name' => 'FEEDER_NAME', 'value' => $this->p_feeder_name, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_NAME', 'value' => $this->p_donation_name, 'type' => '', 'length' => -1),
            array('name' => 'BUDGET_YEAR', 'value' => $this->p_budget_year, 'type' => '', 'length' => -1),

            array('name' => 'RECONDUCTORING_COST', 'value' => $this->p_reconductoring_cost, 'type' => '', 'length' => -1),
            array('name' => 'ELECTRICITYH', 'value' => $this->p_electricityh, 'type' => '', 'length' => -1),
            array('name' => 'KWSAVING', 'value' => $this->p_kw_saving, 'type' => '', 'length' => -1),
            array('name' => 'PAYBACK', 'value' => $this->p_payback, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMERS', 'value' => $this->p_customers, 'type' => '', 'length' => -1),
            array('name' => 'LOSS_VAR', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'LOSS_KW_COST', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'AVERAGE_KWH', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'COST_KWH', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'NET_PROFIT', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'KWH_SAVING', 'value' => $this->p_kwsaving, 'type' => '', 'length' => -1),

        );

        if ($isCreate)
            array_shift($result);


        return $result;
    }

    function _postedDataTransformerData($name, $kva_rating, $mv_feeder_name, $project_ser, $ser)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'NAME', 'value' => $name, 'type' => '', 'length' => -1),
            array('name' => 'KVA_RATING', 'value' => $kva_rating, 'type' => '', 'length' => -1),
            array('name' => 'MV_FEEDER_NAME', 'value' => $mv_feeder_name, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_SER', 'value' => $project_ser, 'type' => '', 'length' => -1),
        );

        if ($ser == 0)
            array_shift($result);

        return $result;
    }

    function _postedDataTransformerDataDetails($lv_cb_direction, $status, $cross_sections, $length, $expected_load, $ntd_ser, $ser)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'NTDSER', 'value' => $ntd_ser, 'type' => '', 'length' => -1),
            array('name' => 'LV_CB_DIRECTION', 'value' => $lv_cb_direction, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $status, 'type' => '', 'length' => -1),
            array('name' => 'CROSS_SECTIONS', 'value' => $cross_sections, 'type' => '', 'length' => -1),
            array('name' => 'LENGTH', 'value' => $length, 'type' => '', 'length' => -1),
            array('name' => 'EXPECTED_LOAD', 'value' => $expected_load, 'type' => '', 'length' => -1),
        );

        if ($ser == 0)
            array_shift($result);

        return $result;
    }

    function _postedDataExistingLoadTransformer($name, $kva_rating, $mv_feeder_name, $project_ser, $ser)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'NAME', 'value' => $name, 'type' => '', 'length' => -1),
            array('name' => 'KVA_RATING', 'value' => $kva_rating, 'type' => '', 'length' => -1),
            array('name' => 'MV_FEEDER_NAME', 'value' => $mv_feeder_name, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT', 'value' => $project_ser, 'type' => '', 'length' => -1),
        );

        if ($ser == 0)
            array_shift($result);

        return $result;
    }

    function _postedDataExistingLoadTransformerDetails($lv_cb_direction, $cross_sections, $length, $r, $s, $t, $n, $rs, $st, $rt, $eblm_ser, $ser)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'NTDSER', 'value' => $eblm_ser, 'type' => '', 'length' => -1),
            array('name' => 'LV_CB_DIRECTION', 'value' => $lv_cb_direction, 'type' => '', 'length' => -1),
            array('name' => 'CROSS_SECTIONS', 'value' => $cross_sections, 'type' => '', 'length' => -1),
            array('name' => 'LENGTH', 'value' => $length, 'type' => '', 'length' => -1),
            array('name' => 'R', 'value' => $r, 'type' => '', 'length' => -1),
            array('name' => 'S', 'value' => $s, 'type' => '', 'length' => -1),
            array('name' => 'T', 'value' => $t, 'type' => '', 'length' => -1),
            array('name' => 'N', 'value' => $n, 'type' => '', 'length' => -1),
            array('name' => 'RS', 'value' => $rs, 'type' => '', 'length' => -1),
            array('name' => 'ST', 'value' => $st, 'type' => '', 'length' => -1),
            array('name' => 'RT', 'value' => $rt, 'type' => '', 'length' => -1),

        );

        if ($ser == 0)
            array_shift($result);


        return $result;
    }

    function _postedDataExistingALoadTransformer($name, $kva_rating, $mv_feeder_name, $project_ser, $ser)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC', 'value' => $project_ser, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC', 'value' => $name, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC', 'value' => $kva_rating, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC', 'value' => $mv_feeder_name, 'type' => '', 'length' => -1),
        );

        if ($ser == 0)
            array_shift($result);

        return $result;
    }

    function _postedDataExistingALoadTransformerDetails($lv_cb_direction, $cross_sections, $length, $r, $s, $t, $n, $ealm_ser, $ser)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'NTDSER', 'value' => $ealm_ser, 'type' => '', 'length' => -1),
            array('name' => 'LV_CB_DIRECTION', 'value' => $lv_cb_direction, 'type' => '', 'length' => -1),
            array('name' => 'CROSS_SECTIONS', 'value' => $cross_sections, 'type' => '', 'length' => -1),
            array('name' => 'LENGTH', 'value' => $length, 'type' => '', 'length' => -1),
            array('name' => 'R', 'value' => $r, 'type' => '', 'length' => -1),
            array('name' => 'S', 'value' => $s, 'type' => '', 'length' => -1),
            array('name' => 'T', 'value' => $t, 'type' => '', 'length' => -1),
            array('name' => 'N', 'value' => $n, 'type' => '', 'length' => -1),
        );

        if ($ser == 0)
            array_shift($result);

        return $result;
    }

    function _postedDataLvNetworks($name, $kw_loss, $kva_loss, $llv, $a_vd, $ad_kw_loss, $ad_kva_loss, $ad_llv, $ad_vd, $project_ser, $ser)
    {

        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC', 'value' => $project_ser, 'type' => '', 'length' => -1),
            array('name' => 'NAME', 'value' => $name, 'type' => '', 'length' => -1),
            array('name' => 'KW_LOSS', 'value' => $kw_loss, 'type' => '', 'length' => -1),
            array('name' => 'KVA_LOSS', 'value' => $kva_loss, 'type' => '', 'length' => -1),
            array('name' => 'LLV', 'value' => $llv, 'type' => '', 'length' => -1),
            array('name' => 'A_VD', 'value' => $a_vd, 'type' => '', 'length' => -1),
            array('name' => 'AD_KW_LOSS', 'value' => $ad_kw_loss, 'type' => '', 'length' => -1),
            array('name' => 'AD_KVA_LOSS', 'value' => $ad_kva_loss, 'type' => '', 'length' => -1),
            array('name' => 'AD_LLV', 'value' => $ad_llv, 'type' => '', 'length' => -1),
            array('name' => 'AD_VD', 'value' => $ad_vd, 'type' => '', 'length' => -1),
        );

        if ($ser == 0)
            array_shift($result);

        //print_r($result);

        return $result;
    }

    function _postedMVConductors($mc_b_cross_section,
                                 $mc_b_length,
                                 $mc_b_avg_load,
                                 $mc_b_v_ll_kv,
                                 $mc_a_avg_load,
                                 $mc_a_v_ll_kv,

                                 $loss,
                                 $loss_var,
                                 $llvk,
                                 $vd,
                                 $project_ser,
                                 $ser)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),

            array('name' => 'MC_B_CROSS_SECTION', 'value' => $mc_b_cross_section, 'type' => '', 'length' => -1),
            array('name' => 'MC_B_LENGTH', 'value' => $mc_b_length, 'type' => '', 'length' => -1),
            array('name' => 'B_AVG_LOAD', 'value' => $mc_b_avg_load, 'type' => '', 'length' => -1),
            array('name' => 'MC_B_V_LL_KV', 'value' => $mc_b_v_ll_kv, 'type' => '', 'length' => -1),
            array('name' => 'MC_A_AVG_LOAD', 'value' => $mc_a_avg_load, 'type' => '', 'length' => -1),
            array('name' => 'MC_A_V_LL_KV', 'value' => $mc_a_v_ll_kv, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_SER', 'value' => $project_ser, 'type' => '', 'length' => -1),
            array('name' => 'LOSS', 'value' => $loss, 'type' => '', 'length' => -1),
            array('name' => 'LOSS_VAR', 'value' => $loss_var, 'type' => '', 'length' => -1),
            array('name' => 'llvk', 'value' => $llvk, 'type' => '', 'length' => -1),
            array('name' => 'VD', 'value' => $vd, 'type' => '', 'length' => -1),
        );

        if ($ser == 0)
            array_shift($result);

        return $result;
    }

    function public_transformer_data($id)
    {

        $rs = $this->rmodel->getList('NTRANSFORMERDATA_TB_LIST', "  WHERE PROJECTSER = {$id} ", 0, 100);

        for ($i = 0; $i < count($rs); $i++) {

            $rs[$i]['details'] = $this->rmodel->getList('NTRANSFORMER_DETAILS_TB_LIST', "  WHERE NTDSER = {$rs[$i]['SER']} ", 0, 100);

        }

        $data['details'] = $rs;

        $this->_lookUps_data($data);

        $this->load->view('transformer_data', $data);
    }

    function public_mv_conductor($id)
    {
        $data['details'] = $this->rmodel->getList('MV_CONDUCTORS_DATA_TB_LIST', "  WHERE PROJECTSER = {$id} ", 0, 100);
        $this->_lookUps_data($data);
        $this->load->view('mv_conductor', $data);
    }

    function public_existing_load_transformer($id)
    {

        $rs = $this->rmodel->getList('EXISTINGBEFORLDMITIGATION_LIST', "  WHERE PROJECTSER = {$id} ", 0, 100);

        for ($i = 0; $i < count($rs); $i++) {
            $rs[$i]['details'] = $this->rmodel->getList('EXISTINGBLM_DETAILS_TB_LIST', "  WHERE EBLMSER = {$rs[$i]['SER']} ", 0, 100);
        }

        $data['details'] = $rs;
        $this->_lookUps_data($data);
        $this->load->view('existing_load_transformer', $data);
    }

    function public_after_existing_load_transformer($id)
    {

        $rs = $this->rmodel->getList('EXISTINGAFTERLDMITIGATION_LIST', "  WHERE PROJECTSER = {$id} ", 0, 100);

        for ($i = 0; $i < count($rs); $i++) {
            $rs[$i]['details'] = $this->rmodel->getList('EALDM_DETAILS_TB_LIST', "  WHERE EALMSER = {$rs[$i]['SER']} ", 0, 100);
        }

        $data['details'] = $rs;

        $this->_lookUps_data($data);
        $this->load->view('after_existing_load_transformer', $data);
    }

    function public_lv_networks($id)
    {

        $data['details'] = $this->rmodel->getList('LV_NETWORK_TB_LIST', "  WHERE PROJECT_SER = {$id} ", 0, 100);
        $this->_lookUps_data($data);
        $this->load->view('lv_networks', $data);
    }

    function delete()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = null;
            $procedure = '';
            switch ($this->p_action) {

                case 'transformer_data':
                    $procedure = 'NTRANSFORMERDATA_TB_DELETE';
                    break;

                case 'mv_conductor':
                    $procedure = 'MV_CONDUCTORS_DATA_TB_DELETE';
                    break;

                case 'existing_load_transformer':
                    $procedure = 'EXISTINGBEFORLDMITIGATION_DEL';
                    break;

                case 'after_existing_load_transformer':
                    $procedure = 'EXISTINGAFTERLDMITIGATION_DEL';
                    break;

                case 'lv_networks':
                    $procedure = 'NTRANSFORMERDATA_TB_DELETE';
                    break;

                case 'transformers_installation':
                    $procedure = 'TRANSFORMERS_INSTALLATION_DEL';
                    break;

                case 'existingblm_details_tb':
                    $procedure = 'EXISTINGBLM_DETAILS_TB_DELETE';
                    break;
            }

            $result = $this->rmodel->delete($procedure, $this->p_id);

            if (intval($result) <= 0) {
                $this->print_error($result);
            }

            echo $result;


        }
    }
}