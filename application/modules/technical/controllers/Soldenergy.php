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
class SoldEnergy extends MY_Controller
{

    function  __construct()
    {
        parent::__construct();
        $this->load->model('SoldEnergy_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/constant_details_model');
    }


    function Index($page = 1)
    {

        $data['title'] = 'Sold Energy';
        $data['content'] = 'soldenergy_index';
        $data['page'] = $page;
        $data['action'] = 'index';

        $this->_lookUps_data($data);

        $this->load->view('template/template', $data);
    }

    function get_page($page = 1, $action = 'get')
    {

        $this->load->library('pagination');

        $sql = isset($this->p_from_date) && $this->p_from_date != null ? "AND  MONTH >= {$this->p_from_date} " : "";
        $sql .= isset($this->p_to_date) && $this->p_to_date != null ? " AND  MONTH <= {$this->p_to_date} " : "";



        $config['base_url'] = base_url("technical/soldenergy/get_page/");
        $count_rs = $this->get_table_count(' SOLD_ENERGY_TB M WHERE 1 = 1 ' . $sql);

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

        $data["rows"] = $this->SoldEnergy_model->get_list($sql, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $data['action'] = $action;

        $this->load->view('soldenergy_page', $data);

    }

    /**
     * constants data
     */
    function _lookUps_data(&$data)
    {
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['feeder_name'] = $this->constant_details_model->get_list(209);

        $data['help'] = $this->help;
        $this->_loadDatePicker();
    }

    /**
     * create new SoldEnergy
     */
    function Create()
    {

        //check if http request is post , that mean insert action 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->SoldEnergy_model->create($this->_postedData());

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات'.$result);
            }
 

            echo $result;

        } else { // show empty form for insert 
            $data['content'] = 'soldenergy_show';
            $data['title'] = 'Sold Energy ';
            $data['action'] = 'index';
            $this->_lookUps_data($data);

            $this->load->view('template/template', $data);
        }


    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $result = $this->SoldEnergy_model->edit($this->_postedData(false));

            if (intval($result) <= 0) {
                $this->print_error('فشل في حفظ البيانات');
            }

            echo $result;

        }
    }

    function get($id)
    {

        $result = $this->SoldEnergy_model->get($id);

        $data['content'] = 'soldenergy_show';
        $data['title'] = 'Sold Energy';
        $data['result'] = $result;


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
            array('name' => 'PAID', 'value' => $this->p_paid, 'type' => '', 'length' => -1),
            array('name' => 'PREPAID', 'value' => $this->p_prepaid, 'type' => '', 'length' => -1),
            array('name' => 'MONTH', 'value' => $this->p_month, 'type' => '', 'length' => -1),
            array('name' => 'MAIN_FEEDER', 'value' => null, 'type' => '', 'length' => -1),
        );

        if ($isCreate) {
            array_shift($result);
        }


        return $result;
    }



} 