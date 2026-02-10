<?php

//if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 20/04/21
 * Time: 11:56 ص
 */

class Covid extends MY_Controller
{

    var $PKG_NAME = "DMG_PKG";

    function __construct()
    {
        parent::__construct();


        $this->load->model('root/rmodel');
        $this->rmodel->package = 'DMG_PKG';
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
    }

    /************************************index*********************************************/

    function index($page = 1)
    {
        $data['title'] = 'استعلام عن الزائرين';
        $data['content'] = 'Covid_index';

        $data['page'] = $page;

        $data['branches'] = $this->gcc_branches_model->get_all();
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
		add_js('date_functions.js');

        $data['branches'] = $this->gcc_branches_model->get_all();
		//$this->load->model('Covid/Covid_model');
        //$data['emp_no_cons'] = $this->Covid_model->get_child( 0 , 0);
        $data['emp_no_cons'] = $this->rmodel->get('EVA_EMPS_STRUCTURE_CHILD_GET', 0);


        $data['help'] = $this->help;
        $this->_generate_std_urls($data, true);
    }

    /**************************************create*****************************************/
	
	
	  
	  function getHtml($url, $post = null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		if(!empty($post)) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
		//return json_decode(json_encode($result));

	  }

    function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$url = 'https://im-server.gedco.ps:8008/api/downloadData/addCitizenAttendance';
			$ch = curl_init($url);
			$data = array(
				'visitorId' => $this->p_id,
				'date' => date("d-m-Y"),
				'time' => date("h.i"),
				'mobile' => $this->p_mobile
				
			);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array());
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			//echo $result ; //$result ; //json_decode(json_encode($result));
			$JSON = json_decode($result, true);
			
			if($JSON['status'] == 'success'){
				$status = 2;
			}else{
				$status = 1;
			}
			
			$this->ser = $this->rmodel->insert('COVID_VISIT_HISTORY_INSERT', $this->_postedData($status));
			if ($this->ser < 1) {
				$this->print_error('لم يتم الحفظ' . '<br>');
			} else {
				echo intval($status);
			}
        } else {

            $data['title'] = 'زائر جديد';
            $data['action'] = 'index';
            $data['hide'] = 'hidden';
            $data['isCreate'] = true;
            $data['content'] = 'Covid_show';
            $this->_lookup($data);
            $this->load->view('template/template', $data);

        }

    }

    /*************************************_postedData*******************************************/

    function _postedData($status, $isCreate = true)
    {
        $result = array(
            array('name' => 'ID', 'value' => $this->p_id, 'type' => '', 'length' => -1),
            array('name' => 'VISIT_TIME', 'value' => date("h.i"), 'type' => '', 'length' => -1),
			array('name' => 'MOBILE', 'value' => $this->p_mobile, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $status , 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
        );

        return $result;
    }

    /*************************************get_page*******************************************/
	function get_page($page = 1)
    {
        $this->load->library('pagination');
        $config['base_url'] = base_url("Covid/Covid/get_page/");


        $sql = 'WHERE 1 = 1 ';

        $sql .= isset($this->p_id) && $this->p_id ? " AND ID= {$this->p_id} " : '';
        $sql .= isset($this->p_visit_date) && $this->p_visit_date ? " AND VISIT_DATE like '%{$this->p_visit_date}%' " : "";
		$sql .= isset($this->p_branch_no) && $this->p_branch_no ? " AND BRANCH= {$this->p_branch_no} " : '';
        $sql .= isset($this->p_branch_no_dp) && $this->p_branch_no_dp ? " AND BRANCH= {$this->p_branch_no_dp} " : '';
		
		

        $count_rs = $this->get_table_count("COVID_VISIT_HISTORY_TB M  {$sql}");
		
		


        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] =  200 ; //count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
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
        $data["rows"] = $this->rmodel->getList('COVID_VISIT_HISTORY_LIST', " $sql ", $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('Covid_page', $data);
    }
	
	
	function get(){
		
		$data['title'] = 'نتيجة فحص كورونا';
		$data['action'] = 'index';
        $data['isCreate'] = true;
        $data['content'] = 'Covid_hr_show';
        $this->_lookup($data);
        $this->load->view('template/template', $data);
            
	}
	
	function create_hr()
    {
		$url = 'https://im-server.gedco.ps:8008/api/downloadData/covidResult';
			$ch = curl_init($url);
			$data = array(
				'patientId' => $this->p_patientId
			);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array());
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			//echo $result ; //$result ; //json_decode(json_encode($result));
			$JSON = json_decode($result, true);
			
			print_r($JSON); die;
			
			
			
			
        $this->ser = $this->rmodel->insert('COVID_HR_HISTORY_TB_INSERT', $this->_postedData_hr());
		for ($i = 0; $i < $this->p_count; $i++)
        {
			$serDet=$this->rmodel->insert('COVID_HR__DET_HIS_TB_INSERT',$this->_posteddata_details($this->ser, $this->p_id[$i], $this->p_result_date[$i], $this->p_request_date[$i], $this->p_result_[$i] ,'create')) ;
			
        }   

    }
	
	function _postedData_hr($isCreate = true)
    {
        $result = array(
            array('name' => 'ID', 'value' => $this->p_id, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $this->p_emp_no, 'type' => '', 'length' => -1),
            array('name' => 'ENTRY_USER', 'value' => $this->user->id, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->user->branch, 'type' => '', 'length' => -1),
        );

        return $result;
    }
	
	function _posteddata_details( $master_ser = null, $id = null,$result_date = null, $request_date = null, $result_ = null)
    {

        $result = array(
            array('name' => 'MASTER_SER', 'value' => $master_ser, 'type' => '', 'length' => -1),
            array('name' => 'ID', 'value' => $id, 'type' => '', 'length' => -1),
            array('name' => 'REQUEST_DATE', 'value' => $request_date, 'type' => '', 'length' => -1),
            array('name' => 'RESULT_DATE', 'value' => $result_date, 'type' => '', 'length' => -1),
            array('name' => 'RESULT', 'value' => $result_, 'type' => '', 'length' => -1),


        );

        return $result;
    }


   



}
