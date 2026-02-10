<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 08/01/2023
 * Time: 07:25 م
 */

class Interviews_result extends MY_Controller
{
    var $PAGE_URL = 'interviews/Interviews_result/get_page';

    function __construct()
    {
        parent::__construct();
        $this->load->model('settings/users_model');
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'INTERVIEWS_PKG';
    }

    function _lookup(&$data)
    {
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
    }

    function index($page = 1)
    {
        if (HaveAccess('interviews/Interviews_result/index') && HaveAccess('interviews/Interviews_result/code_verification')) {
            $data['title'] = ' نتائج مقابلات الوظائف الداخلية ';
            $data['content'] = 'interviews_result_index';
            $data['page'] = $page;
            $this->_lookup($data);
            $this->send_code();
            $this->load->view('template/template1', $data);
        } else {
            die('لا تملك صلاحيات للاستعلام');
        }
    }

    function query($page = 1, $code= null)
    {
        if (HaveAccess('interviews/Interviews_result/index') && HaveAccess('interviews/Interviews_result/query')) {
            $code = $this->input->post('code');
            $check_code = $this->rmodel->get('INTERVIEWS_VERFICATION_GET',  md5($code));
            if(count($check_code) > 0){
                $data['title'] = ' نتائج مقابلات الوظائف الداخلية ';
                $data['ads_arr'] = $this->rmodel->getAll('INTERVIEWS_PKG', 'JOB_ADS_TB_GET_ALL');
                $data['page'] = $page;
                $this->_lookup($data);
                $this->load->view('interviews_result_show', $data);
            }
        } else {
            die('حدث خطأ');
        }
    }

    function send_code()
    {
        if (HaveAccess('interviews/Interviews_result/index')){
            $code = mt_rand(100001, 999999);
            $return_data = $this->rmodel->getData('INTERVIEWS_RESULT_USERS_GET');
            if($return_data != null){
                $user_id = $return_data[0]['USER_ID'];
                $user_mobile = "0".$return_data[0]['MOBILE'];
                if($user_id == $this->user->id){
                    $insert_record = $this->rmodel->insert('INTERVIEWS_VERF_TB_INSERT', $this->_postedData_verification($this->user->id, md5($code)));
                    $mes = "CODE: " . $code;
                    $message = urlencode($mes);
                    if(intval($insert_record) > 0) {
                        $ret= $this->getHtml("https://im-server.gedco.ps:8005/apis/v1/SendSms?mobile=".$user_mobile."&message=".$message."&user=Auto-APPS-SMS&password=2118058&sender=GEDCO");
                    }
                    echo $ret;
                } else {
                    $this->print_error("حدث خطأ");
                }
            } else {
                $this->print_error("حدث خطأ");
            }
        } else {
            die('لا تملك صلاحيات للاستعلام');
        }
    }

    function _postedData_verification($user_id, $code, $isCreate = true)
    {
        $result = array(
            array('name' => 'USER_ID_IN', 'value' => $user_id, 'type' => '', 'length' => -1),
            array('name' => 'CODE_IN', 'value' => $code, 'type' => '', 'length' => -1),
        );
        if ($isCreate)
            array_shift($result);

        return $result;
    }

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
    }

    function code_verification()
    {
        if ( HaveAccess('interviews/Interviews_result/code_verification')) {
            $code = $this->input->post('code');
            $user_id = $this->input->post('user_id');
            if($user_id == $this->user->id) {
                $ret = $this->rmodel->update('INTERVIEWS_VERF_TB_UPDATE', $this->_postedData_verification($user_id, md5($code), false));

                if (intval($ret) <= 0) {
                    $this->print_error($ret);
                } else {
                    echo 1;
                }
            } else {
                $this->print_error("حدث خطأ");
            }
        } else {
            $this->print_error("لا يوجد صلاحيات كافية لاتمام العملية");
        }
    }

    function get_page($page = 1)
    {
        if (HaveAccess('interviews/Interviews_result/index') && HaveAccess('interviews/Interviews_result/code_verification')) {
            $code = $this->input->post('code');
            $check_code = $this->rmodel->get('INTERVIEWS_VERFICATION_GET', md5($code));
            if(count($check_code) > 0) {
                $this->load->library('pagination');
                $where_sql = 'where 1 = 1 ';
                $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != null ? " AND  EMP_NO = '{$this->p_emp_no}'  " : "";
                $where_sql .= isset($this->p_ads_ser) && $this->p_ads_ser != null ? " AND  DEPARTMENT = '{$this->p_ads_ser}'  " : "";
                $config['base_url'] = base_url($this->PAGE_URL);

                $count_rs = $this->get_table_count(' GFC.INTERVIEWS_RESULT_TB ' . $where_sql);
                $config['use_page_numbers'] = TRUE;
                $config['total_rows'] = is_array($count_rs) && count($count_rs) > 0 ? $count_rs[0]['NUM_ROWS'] : 0;
                $config['per_page'] =  $this->page_size;
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

                $data["page_rows"] = $this->rmodel->getList('INTERVIEWS_RESULT_TB_LIST', $where_sql, $offset, $row);
                $data['offset'] = $offset + 1;
                $data['page'] = $page;
                $this->load->model('settings/constant_details_model');
                $data['status_cons'] =  $this->constant_details_model->get_list(481);
                $this->load->view('interviews_result_page', $data);
            } else {
                die('حدث خطأ');
            }
        } else {
            die('حدث خطأ');
        }
    }


}


