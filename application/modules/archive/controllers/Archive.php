<?php

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 10/02/15
 * Time: 09:56 ص
 */
class Archive extends MY_Controller
{

    var $DETAILS_MODEL_NAME = 'archive_det_model';
    var $MODEL_NAME = 'archive_model';
    var $TB_NAME = 'archive_tb';
    var $PAGE_URL = 'archive/archive/get_page';


    function __construct()
    {
        parent::__construct();

        $this->load->model('archive/archive_model');
        $this->load->model('archive/archive_det_model');

        $this->load->model('attachments/attachment_model');

        $this->file_id = $this->input->post('file_id');
        $this->subject = $this->input->post('subject');
        $this->file_type = $this->input->post('file_type');
        $this->file_no = $this->input->post('file_no');
        $this->customer = $this->input->post('customer');
        $this->file_date = $this->input->post('file_date');
        $this->branch = $this->input->post('branch');
        $this->note = $this->input->post('note');
        $this->account_type = $this->input->post('account_type');
        add_js('ajax_upload_file.js');

    }


    function index($page = 1)
    {


        $data['help'] = $this->help;

        $data['content'] = 'archive_index';
        $data['title'] = ' أرشيف';


        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_css('combotree.css');
        add_css('jquery.dataTables.css');
        add_js('jquery.dataTables.js');

        $data['page'] = $page;
        $data['action'] = 'edit';
        //   $data['case'] = 1;
        $this->_look_ups($data);
        $this->load->view('template/template', $data);

    }

    function get_page($page = 1)
    {

        $this->load->library('pagination');
        $sql = ' ';
        //   $sql = $case == 1? " where   ENTRY_USER={$this->user->id} " : " where 1=1 ";
        //   $sql .= " and FINANCIAL_PAYMENT_CASE between {$case}-1 and {$case}  ";
        $count_rs = $this->{$this->MODEL_NAME}->get_count(' archive_tb R' . $sql);


        $config['base_url'] = base_url('payment/financial_payment/index');
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

        $result = $this->{$this->MODEL_NAME}->get_list($sql, $offset, $row);

        //  echo "fff".$page." ".$offset ." ". $row;
        $data['get_all'] = $result;
        $this->date_format($data['get_all'], 'FILE_DATE');


        $this->load->view('archive_page', $data);

    }

    function _postedData($typ = null)
    {

        $result = array(
            array('name' => 'FILE_ID', 'value' => $this->file_id, 'type' => '', 'length' => -1),
            array('name' => 'SUBJECT', 'value' => $this->subject, 'type' => '', 'length' => -1),
            array('name' => 'FILE_TYPE', 'value' => $this->file_type, 'type' => '', 'length' => -1),
            array('name' => 'CUSTOMER', 'value' => $this->customer, 'type' => '', 'length' => -1),
            array('name' => 'FILE_DATE', 'value' => $this->file_date, 'type' => '', 'length' => -1),
            array('name' => 'FILE_BRANCH', 'value' => $this->branch, 'type' => '', 'length' => -1),
            array('name' => 'NOTE', 'value' => $this->note, 'type' => '', 'length' => -1),
            array('name' => 'FILE_NO', 'value' => $this->file_no, 'type' => '', 'length' => -1),
            array('name' => 'ACCOUNT_TYPE', 'value' => $this->account_type, 'type' => '', 'length' => -1)

        );
        if ($typ == 'create')
            unset($result[0]);

        return $result;
    }

    function public_get_details($serial = 0)
    {
        $this->load->model('archive/archive_model');
        $serial = $this->input->post('file_id') ? $this->input->post('file_id') : $serial;
        $data['ID'] = $serial;
        //$data['user_id']=$this->$user->id;
        $data['get_list'] = $this->{$this->MODEL_NAME}->get_all($serial);

        $this->load->view('attachment_page', $data);

    }

    function create()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->file_id = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
            echo $this->file_id;
        } else {

            $data['content'] = 'archive_show';
            $data['title'] = 'الارشيف';
            $data['isCreate'] = true;
            $data['action'] = 'index';
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }

        // $result= $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
        //   $this->Is_success($result);
        //   echo  modules::run($this->PAGE_URL);
    }

    function get_id($id)
    {

        $result = $this->{$this->MODEL_NAME}->get($id);

        $data['result'] = $result;
        $data['action'] = 'edit';
        $data['isCreate'] = true;
        $data['content'] = 'archive_show';
        $data['title'] = 'الارشيف';
        $this->_look_ups($data);
        $this->load->view('template/template', $data);
    }

    function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $res = $this->{$this->MODEL_NAME}->edit($this->_postedData());
            //print_r($res);

            if (intval($res) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $res);
            } else {
                echo $res;
            }

        } else {

            $data['content'] = 'archive_show';
            $data['title'] = 'الارشيف';
            $data['isCreate'] = true;
            $data['action'] = 'edit';
            $this->_look_ups($data);
            $this->load->view('template/template', $data);
        }
    }

    function _look_ups(&$data)
    {

        $this->load->model('archive/archive_model');
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $data['file_type1'] = $this->constant_details_model->get_list(63);
        $data['customer_type'] = $this->constant_details_model->get_list(18);
        $data['help'] = $this->help;
        add_css('datepicker3.css');
        add_css('combotree.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_css('select2_metro_rtl.css');
        add_js('select2.min.js');
        add_js('ajax_upload_file.js');
    }


    function show_form()
    {
        add_js('ajax_upload_file.js');
        $data['id'] = $this->input->get_post('FILE_ID');
        // echo $this->input->get_post('FILE_ID').'mmm';
        $this->load->view('archive/attachment_form', $data);

    }

    function upload_file($id = 0)
    {
        $this->load->library('ftp');
        $config['hostname'] = '192.168.0.16';
        $config['username'] = 'arch';
        $config['password'] = 'Gs$2010';
        $config['debug'] = TRUE;
        $this->ftp->connect($config);

        //$file_path=$id.'_'.time().".".explode(".", strtolower($_FILES['file']['name']))[1];
        $file_path = '2021_' . time() . "." . explode(".", strtolower($_FILES['file']['name']))[1];
        $file_name = $_FILES['file']['name'];
 
//print_r($_FILES);die;

        $rs = $this->ftp->upload($_FILES['file']['tmp_name'], '/narchive/upload/' . $file_path);
        if (intval($rs)) {
            if (isset($this->p_upload_type) && $this->p_upload_type == 'attachment') {
                $result = $this->attachment_model->create($this->_postedData_Attachment($file_name, $file_path, $this->p_identity, $this->p_category));
                echo $result;


            } else {
                $res1 = $this->{$this->DETAILS_MODEL_NAME}->create($this->_postedData_details(null, $file_path, $file_name, $id, 'create'));
                if (!intval($res1))

                    echo "فشل في حفظ بيانات الملف";

                echo "تم رفع الملف بنجاح";
            }


        } else {
            echo "فشل في حفظ بيانات الملف";
        }
        $this->ftp->close();

    }

    function _postedData_Attachment($file_name, $file_path, $identity, $category)
    {
        $result = array(
            array('name' => 'FILE_NAME', 'value' => $file_name, 'type' => '', 'length' => -1),
            array('name' => 'FILE_PATH', 'value' => $file_path, 'type' => '', 'length' => -1),
            array('name' => 'IDENTITY', 'value' => $identity, 'type' => '', 'length' => -1),
            array('name' => 'CATEGORY', 'value' => $category, 'type' => '', 'length' => -1)
        );


        return $result;
    }

   function download($path, $file_name)
	{
		// تحديد المسار الكامل للملف
		$filePath = '/mnt/upload/' . $path;

		// التحقق من وجود الملف
		if (file_exists($filePath)) {
			// اسم الملف الذي سيتم تحميله
			$fileName = basename($filePath);

			// إعداد الهيدر للتحميل
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $fileName . '"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filePath));

			// قراءة الملف وإرساله للمتصفح
			readfile($filePath);
			exit;
		} else {
			// إذا لم يتم العثور على الملف
			echo "File not found: $filePath";
		}
	 }
    function delete_file()
    {

        $this->load->library('ftp');
        $config['hostname'] = '192.168.0.16';
        $config['username'] = 'arch';
        $config['password'] = 'Gs$2010';
        $config['debug'] = TRUE;
        $this->ftp->connect($config);

        $row = $this->attachment_model->get($this->p_id);
        $path = $row[0]['FILE_PATH'];

        $this->attachment_model->delete($this->p_id);

        if (substr($path, 0, 5) === '2021_')
            $this->ftp->delete_file("/narchive/upload/{$path}");
        else
            $this->ftp->delete_file("/upload/{$path}");

        echo 1;
    }


    function archive_doc()
    {
        $file_type = $this->input->get_post('file_type');
        $file_no = $this->input->get_post('file_no');
        $res = $this->{$this->MODEL_NAME}->archive_doc($file_type, $file_no);
        echo $res;
    }

    function delete()
    {
        $id = $this->input->post('id');

        $this->IsAuthorized();
        $msg = 0;


        if (is_array($id)) {
            foreach ($id as $val) {
                // $msg= $this->{$this->MODEL_NAME}->delete($val);

                $msg = $this->{$this->MODEL_NAME}->delete_index($val);
            }
        } else {
            // $msg= $this->{$this->MODEL_NAME}->delete($id);
            $msg = $this->{$this->MODEL_NAME}->delete_index($id);
        }

        if ($msg == 1) {
            echo modules::run($this->PAGE_URL);
        } else {
            $this->print_error_msg($msg);
        }
    }

    function delete_details()
    {
        $id = $this->input->post('id');

        //  $this->IsAuthorized();
        // echo $id.'dddd';
        $msg = 0;

        $row = $this->{$this->DETAILS_MODEL_NAME}->get_follow($id);

        $path = $row[0]['FILE_PATH'];

//echo strpos('123'.$path,'3uploads/')."jjj";
        if (count($row) == 1 and $path != '' and strpos('123' . $path, '3uploads/') == 2) {
            $path = './' . $path;

            if (is_array($id)) {
                foreach ($id as $val) {
                    $msg = $this->{$this->DETAILS_MODEL_NAME}->delete($val);
                    //  echo $msg;
                }
            } else {
                $msg = $this->{$this->DETAILS_MODEL_NAME}->delete($id);

            }

            if ($msg == 1) {
                if (@unlink($path))
                    echo $msg;
                else
                    echo "تم حذف بيانات الملف، ولم يتم العثور عليه";
            } else {
                $this->print_error_msg($msg);
            }
        } else
            echo "لم يتم العثور على الملف";
    }


    function _postedData_details($ser = null, $file_path = null, $file_name = null, $file_id = null, $typ = null)
    {
        $result = array(
            array('name' => 'SER', 'value' => $ser, 'type' => '', 'length' => -1),
            array('name' => 'FILE_PATH', 'value' => $file_path, 'type' => '', 'length' => -1),
            array('name' => 'FILE_NAME', 'value' => $file_name, 'type' => '', 'length' => -1),
            array('name' => 'FILE_ID', 'value' => $file_id, 'type' => '', 'length' => -1)

        );
        if ($typ == 'create')
            unset($result[0]);
        elseif ($typ == 'edit')
            unset($result[2]);
        // print_r($result);
        return $result;


    }
} ?>