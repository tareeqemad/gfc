<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 10/12/17
 * Time: 09:27 ص
 */

/**
 *
 */
class LoadFlow extends MY_Controller
{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('LoadFlow_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
    }


    function Index($page = 1)
    {

        $data['title'] = 'Load Flow';
        $data['content'] = 'loadflow_index';
        $data['page'] = $page;
        $data['action'] = 'index';

        $this->_lookUps_data($data);

        $this->load->view('template/template', $data);
    }

    function get_page($page = 1, $action = 'get')
    {

        $this->load->library('pagination');

        $sql = " AND (M.BRANCH_ID = {$this->user->branch} or {$this->user->branch} = 1) ";
        $sql .= isset($this->p_from_date) && $this->p_from_date != null ? " AND  trunc(M.ENTRY_DATE) >= '{$this->p_from_date}' " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  trunc(M.ENTRY_DATE) <= '{$this->p_to_date}' " : "";
        $sql .= isset($this->p_tec_num) && $this->p_tec_num != null ? " AND   LOWER(PROJECT_TEC) like LOWER('%{$this->p_tec_num}')  " : "";

        $config['base_url'] = base_url("technical/loadflow/get_page/");
        $count_rs = $this->get_table_count(' LOAD_FLOW_MEASURE_TB M WHERE 1 = 1 ' . $sql);

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

        $data["rows"] = $this->LoadFlow_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['action'] = $action;
        $data['can_delete'] =  count($data["rows"] ) > 0 ;
        $this->load->view('loadflow_page', $data);

    }

    /**
     * constants data
     */
    function _lookUps_data(&$data)
    {
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['feeder_name'] = $this->constant_details_model->get_list(90);
        $data['study_type'] = $this->constant_details_model->get_list(204);
        $data['donation_name'] = $this->constant_details_model->get_list(210);

        $data['help'] = $this->help;
        $this->_loadDatePicker();
    }

    /**
     * create new load flow
     * there is many type of load flow
     * 1- MV Reconductoring
     * 2- LV Reconductoring
     * 3- MV Connection
     * we can defferent between them by STUDY_TYPE
     */
    function Create()
    {

        //check if http request is post , that mean insert action 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->LoadFlow_model->create($this->_postedData());

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات' . $result);
            }

            for ($i = 0; $i < count($this->p_cross_section); $i++)
                if (isset($this->p_cross_section[$i]) && $this->p_cross_section[$i] != '' && $this->p_length[$i] != '') //check if data posted
                    $this->LoadFlow_model->create_cables($this->_postDataCables(0, $this->p_cross_section[$i],
                        $this->p_length[$i],
                        $this->p_period_of_service[$i],
                        $this->p_joints[$i],
                        $this->p_across_section[$i],
                        $this->p_alength[$i],
                        $this->p_alife_expectancy[$i],
                        $this->p_avgload[$i],
                        '', //$this->p_max_load[$i],
                        $result,
                        $this->p_v_ln[$i],
                        $this->p_av_ln[$i],

                        true));


            echo $result;

        } else { // show empty form for insert 
            $data['content'] = 'loadflow_show';
            $data['title'] = 'Load Flow ';
            $data['action'] = 'index';
            $this->_lookUps_data($data);

            $this->load->view('template/template', $data);
        }


    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->LoadFlow_model->edit($this->_postedData(false));

            for ($i = 0; $i < count($this->p_cross_section); $i++)
                if (isset($this->p_cross_section[$i]) && $this->p_cross_section[$i] != '' && $this->p_length[$i] != '') //check if data posted
                    if ($this->p_dser[$i] == 0) {
                        $this->LoadFlow_model->create_cables($this->_postDataCables(0, $this->p_cross_section[$i],
                            $this->p_length[$i],
                            $this->p_period_of_service[$i],
                            $this->p_joints[$i],
                            $this->p_across_section[$i],
                            $this->p_alength[$i],
                            $this->p_alife_expectancy[$i],
                            $this->p_avgload[$i],
                            '',
                            $this->p_ser,
                            $this->p_v_ln[$i],
                            $this->p_av_ln[$i],

                            true));
                    } else {
                        $this->LoadFlow_model->edit_cables($this->_postDataCables($this->p_dser[$i], $this->p_cross_section[$i],
                            $this->p_length[$i],
                            $this->p_period_of_service[$i],
                            $this->p_joints[$i],
                            $this->p_across_section[$i],
                            $this->p_alength[$i],
                            $this->p_alife_expectancy[$i],
                            $this->p_avgload[$i],
                            '',
                            $this->p_ser,
                            $this->p_v_ln[$i],
                            $this->p_av_ln[$i],

                            false));
                    }

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات' . $result);
            }

            echo $result;

        }
    }

    function Adopt()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->LoadFlow_model->adopt($this->p_tec, $this->p_case);

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات' . $result);
            }

            echo $result;

        }
    }

    function Delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->LoadFlow_model->delete($this->p_id);

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات' . $result);
            }

            echo $result;

        }
    }

    function delete_cables()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->LoadFlow_model->delete_cables($this->p_id);

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات' . $result);
            }

            echo $result;

        }
    }


    function get($id)
    {

        $result = $this->LoadFlow_model->get($id);

        $data['content'] = 'loadflow_show';

        $data['title'] = 'Load Flow';

        $data['result'] = $result;

        $data['can_edit'] = count($result) > 0 && $result[0]['ENTRY_USER'] == $this->user->id;
        $data['loadFlow'] = (count($result) <= 0 ? false : $result[0]['LOAD_FLOW_ADOPT'] != 1);
        $data['loadFlowAccess'] = HaveAccess('technical/loadflow/Adopt');

        $this->_lookUps_data($data, null);

        $data['action'] = 'edit';

        $this->load->view('template/template', $data);
    }

    /**
     * posted data from view , convert it to array of params for database
     * @param bool $isCreate
     * @return array
     */
    function _postedData($isCreate = true)
    {

        $result = array(
            array('name' => 'SER', 'value' => $this->p_ser, 'type' => '', 'length' => -1),
            array('name' => 'STUDY_TYPE', 'value' => $this->p_study_type, 'type' => '', 'length' => -1),
            array('name' => 'PROJECT_TEC', 'value' => $this->p_project_tec, 'type' => '', 'length' => -1),
            array('name' => 'FEEDER_NAME', 'value' => $this->p_feeder_name, 'type' => '', 'length' => -1),
            array('name' => 'NOTES', 'value' => $this->p_notes, 'type' => '', 'length' => -1),
            array('name' => 'LOSS', 'value' => $this->p_loss, 'type' => '', 'length' => -1),
            array('name' => 'LOSS_VAR', 'value' => $this->p_loss_var, 'type' => '', 'length' => -1),
            array('name' => 'VW_WEAKEST_NODE', 'value' => $this->p_vw_weakest_node, 'type' => '', 'length' => -1),
            array('name' => 'VD', 'value' => $this->p_vd, 'type' => '', 'length' => -1),
            array('name' => 'KW_SAVING', 'value' => $this->p_kw_saving, 'type' => '', 'length' => -1),
            array('name' => 'KVAR_SAVING', 'value' => $this->p_kvar_saving, 'type' => '', 'length' => -1),
            array('name' => 'ALOSS', 'value' => $this->p_aloss, 'type' => '', 'length' => -1),
            array('name' => 'ALOSS_VAR', 'value' => $this->p_aloss_var, 'type' => '', 'length' => -1),
            array('name' => 'AVW_WEAKEST_NODE', 'value' => $this->p_avw_weakest_node, 'type' => '', 'length' => -1),
            array('name' => 'AVD', 'value' => $this->p_avd, 'type' => '', 'length' => -1),
            array('name' => 'LOADFLOWSER', 'value' => null, 'type' => '', 'length' => -1),
            array('name' => 'RECONDUCTORING_COST', 'value' => $this->p_reconductoring_cost, 'type' => '', 'length' => -1),
            array('name' => 'ELECTRICITYH', 'value' => $this->p_electricityh, 'type' => '', 'length' => -1),
            array('name' => 'KWSAVING', 'value' => $this->p_kwsaving, 'type' => '', 'length' => -1),
            array('name' => 'PAYBACK', 'value' => $this->p_payback, 'type' => '', 'length' => -1),
            array('name' => 'DONATION_NAME', 'value' => $this->p_donation_name, 'type' => '', 'length' => -1),
            array('name' => 'BUDGET_YEAR', 'value' => $this->p_budget_year, 'type' => '', 'length' => -1),
            array('name' => 'VD_LN', 'value' => $this->p_vd_ln, 'type' => '', 'length' => -1),
            array('name' => 'VW_WEAKEST_NODE_LN', 'value' => $this->p_vw_weakest_node_ln, 'type' => '', 'length' => -1),
            array('name' => 'AVW_WEAKEST_NODE_LN', 'value' => $this->p_avw_weakest_node_ln, 'type' => '', 'length' => -1),
            array('name' => 'AVD_LN', 'value' => $this->p_avd_ln, 'type' => '', 'length' => -1),
            array('name' => 'customers', 'value' => $this->p_customers, 'type' => '', 'length' => -1),
            array('name' => 'LOSS_KW_COST', 'value' => $this->p_loss_kw_cost, 'type' => '', 'length' => -1),
            array('name' => 'AVERAGE_KWH', 'value' => $this->p_average_kwh, 'type' => '', 'length' => -1),
            array('name' => 'COST_KWH', 'value' => $this->p_cost_kwh, 'type' => '', 'length' => -1),
            array('name' => 'NET_PROFIT', 'value' => $this->p_net_profit, 'type' => '', 'length' => -1),
        );

        if ($isCreate) {
            array_shift($result);
        }

		
		//print_r($result);

        return $result;
    }

    /** details of cables
     * @param $ser
     * @param $cross_section
     * @param $length
     * @param $period_of_service
     * @param $joints
     * @param $across_section
     * @param $alength
     * @param $alife_expectancy
     * @param $avgload
     * @param $max_load
     * @param $loadflow_ser
     * @param bool $isCreate
     * @return array
     */
    function _postDataCables($ser,
                             $cross_section,
                             $length,
                             $period_of_service,
                             $joints,
                             $across_section,
                             $alength,
                             $alife_expectancy,
                             $avgload,
                             $max_load,
                             $loadflow_ser,
                             $v_ln,
                             $av_ln,
                             $isCreate = true)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'CROSS_SECTION', 'value' => $cross_section, 'type' => '', 'length' => -1),
            array('name' => 'LENGTH', 'value' => $length, 'type' => '', 'length' => -1),
            array('name' => 'PERIOD_OF_SERVICE', 'value' => $period_of_service, 'type' => '', 'length' => -1),
            array('name' => 'JOINTS', 'value' => $joints, 'type' => '', 'length' => -1),
            array('name' => 'ACROSS_SECTION', 'value' => $across_section, 'type' => '', 'length' => -1),
            array('name' => 'ALENGTH', 'value' => $alength, 'type' => '', 'length' => -1),
            array('name' => 'ALIFE_EXPECTANCY', 'value' => $alife_expectancy, 'type' => '', 'length' => -1),
            array('name' => 'AVGLOAD', 'value' => $avgload, 'type' => '', 'length' => -1),
            array('name' => 'MAX_LOAD', 'value' => $max_load, 'type' => '', 'length' => -1),
            array('name' => 'LOADFLOW_SER', 'value' => $loadflow_ser, 'type' => '', 'length' => -1),
            array('name' => 'V_LN', 'value' => $v_ln, 'type' => '', 'length' => -1),
            array('name' => 'AV_LN', 'value' => $av_ln, 'type' => '', 'length' => -1),

        );

        if ($isCreate) {
            array_shift($result);
        }


        return $result;
    }

    /**
     * return items of load flow project
     * @param $id
     */
    function public_get_tools($id)
    {
        $data['details'] = $this->LoadFlow_model->tools_list($id);
        $data['items'] = $this->LoadFlow_model->getProjectItem(0);
        $this->load->view('loadflow_tools', $data);
    }


    /**
     * get project items by tec number
     */
    function  publicGetProjectItem()
    {

        $result = $this->LoadFlow_model->getProjectItem($this->p_project_tec);

        if (count($result) > 0) echo json_encode($result);
        else echo '';
    }


} 