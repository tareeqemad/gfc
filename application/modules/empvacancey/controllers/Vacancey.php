<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 24/01/2019
 * Time: 12:10 م
 */
class Vacancey extends MY_Controller
{
    var $MODEL_NAME = 'Vacancey_model';
    var $PAGE_URL = 'empvacancey/vacancey/get_page';
    var $PAGE_URL_T = 'empvacancey/vacancey/get_page_d';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'HR_VACANCY_PKG';
        // vars
        $this->id_vacancy = $this->input->post('id_vacancy');
        $this->emp_no = $this->input->post('emp_no');
        $this->emp_name = $this->input->post('emp_name');
        $this->emp_id = $this->input->post('emp_id');
        $this->emp_job = $this->input->post('emp_job');
        $this->branch = $this->input->post('branch');
        $this->v_note = $this->input->post('v_note');
        $this->adopt_note = $this->input->post('adopt_note');
        $this->emp_end_date = $this->input->post('emp_end_date');
        $this->emp_end_reason = $this->input->post('emp_end_reason');
    }

    /**************  ////استعلام عن بيانات موظف*************/
    function index()
    {
        $data['content'] = 'emp_view_index';
        $data['title'] = 'انشاء شهادة خلو طرف - استعلام عن موظف';
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    /************************جلب بيانات الموظف************************************/
    function get_page()
    {
        $emp_no = $this->input->post('emp_no');
        $data['page_rows'] = $this->rmodel->get('EMPLOYEES_GET_ALL', $emp_no);
        $this->load->view('emp_view_page', $data);
    }


    /*********************************استعلام عن طلبات خلو الطرف*************************************/
    function index_page($page = 1)
    {
        ////استعلام عن بيانات طلب شهادة خلو طرف
        $data['content'] = 'emp_view_request_index';
        $data['page'] = $page;
        $data['help'] = $this->help;
        $data['title'] = 'شهادات خلو الطرف - استعلام';
        $data['entry_user_all'] = $this->get_entry_users('EMP_VACANCY_TB');
        $this->_look_ups($data);
        $this->load->view('template/template1', $data);
    }

    function get_page_d($page = 1)
    {
        $this->load->library('pagination');
        $MODULE_NAME = 'empvacancey';
        $TB_NAME = "vacancey";
        $adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");//old adopt
        $adopt_main_url = base_url("$MODULE_NAME/$TB_NAME/adopt_main_");//اعتماد الرئيسي
        $adopt_sub_url = base_url("$MODULE_NAME/$TB_NAME/adopt_sub_");//اعتمادات الفرع
        $where_sql = " WHERE 1=1 ";
        if (!$this->input->is_ajax_request()) {
            if (HaveAccess($adopt_url . '2')) {
                $where_sql .= " AND M.ADOPT = 1 AND M.ENTRY_USER = '{$this->user->id}'";
            }elseif (HaveAccess($adopt_sub_url . '3')) {
                $where_sql .= " AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,2) = 1  AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,3) = 0 AND M.BRANCH = '{$this->user->branch}'";
            }elseif (HaveAccess($adopt_sub_url . '4')) {
                $where_sql .= " AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,2) = 1 AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,4) = 0 AND M.BRANCH = '{$this->user->branch}'";
            }elseif (HaveAccess($adopt_sub_url . '5')) {
                $where_sql .= " AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,2) = 1 AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,5) = 0 AND M.BRANCH = '{$this->user->branch}'";
            }elseif (HaveAccess($adopt_sub_url . '6')) {
                $where_sql .= " AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,2) = 1 AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,6) = 0 AND M.BRANCH = '{$this->user->branch}'";
            }elseif (HaveAccess($adopt_sub_url . '10')) {
                $where_sql .= " AND  HR_VACANCY_PKG.GET_COUNT_OF_ADOPT(M.ID_VACANCY) = 5 AND M.BRANCH = '{$this->user->branch}'";
            } elseif (HaveAccess($adopt_sub_url . '30')) {
                $where_sql .= " AND M.ADOPT  = 10 AND M.BRANCH = '{$this->user->branch}'";
            } elseif (HaveAccess($adopt_sub_url . '50')) {
                $where_sql .= " AND M.ADOPT  = 30 AND M.BRANCH = '{$this->user->branch}'";
            } elseif (HaveAccess($adopt_sub_url . '70')) {
                $where_sql .= " AND M.ADOPT  = 50 ";
            } elseif (HaveAccess($adopt_sub_url . '90')) {
                $where_sql .= " AND M.ADOPT  = 70 ";
            } elseif (HaveAccess($adopt_main_url . '10')) {
                $where_sql .= " AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,2) = 1  AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,10) = 0 AND M.BRANCH = 1";
            } elseif (HaveAccess($adopt_main_url . '30')) {
                $where_sql .= "  AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,2) = 1  AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,30) = 0  AND M.BRANCH = 1";
            } elseif (HaveAccess($adopt_main_url . '40')) {
                $where_sql .= "  AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,2) = 1  AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,40) = 0  AND M.BRANCH = 1";
            } elseif (HaveAccess($adopt_main_url . '50')) {
                $where_sql .= "  AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,2) = 1  AND HR_VACANCY_PKG.GET_COUNT_OF_NOT_ADOPT(M.ID_VACANCY,50) = 0  AND M.BRANCH = 1";
            } elseif (HaveAccess($adopt_main_url . '70')) {
                $where_sql .= " AND  HR_VACANCY_PKG.GET_COUNT_OF_ADOPT(M.ID_VACANCY) = 5 AND M.BRANCH = 1";
            } elseif (HaveAccess($adopt_main_url . '90')) {
                $where_sql .= " AND M.ADOPT  = 70 AND M.BRANCH = 1";
            } else {
                $where_sql .= " AND M.ADOPT = -100";
            }
        }
        $where_sql .= isset($this->p_id_vacancy) && $this->p_id_vacancy != null ? " AND M.ID_VACANCY  = {$this->p_id_vacancy} " : '';
        $where_sql .= isset($this->p_branch_no) && $this->p_branch_no != null ? " AND M.BRANCH  = {$this->p_branch_no} " : '';
        $where_sql .= isset($this->p_emp_no) && $this->p_emp_no != "" ? " AND M.EMP_NO = {$this->p_emp_no}" : "";
        $where_sql .= isset($this->p_type_adopt) && $this->p_type_adopt != "" ? " AND M.TYPE_ADOPT = {$this->p_type_adopt}" : "";
        $where_sql .= isset($this->p_entry_user) && $this->p_entry_user != "" ? " AND M.ENTRY_USER = {$this->p_entry_user}" : "";
        $where_sql .= " AND M.TYPE_ADOPT = 2";
        $config['base_url'] = base_url($this->PAGE_URL_T);
        $count_rs = $this->get_table_count(" EMP_VACANCY_TB M   {$where_sql}");
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
        $data['page_rows'] = $this->rmodel->getList('EMP_VACANCY_TB_LIST1', $where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->load->view('emp_view_request_page', $data);
    }

    /***************************************************************************************/
    function get($emp_no = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ID_VACANCY = $this->{$this->MODEL_NAME}->create($this->_postedData_1('create'));
            if (intval($this->ID_VACANCY) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ID_VACANCY);
            } else {
                echo intval($this->ID_VACANCY);
            }
        } else {

            $rs = $this->{$this->MODEL_NAME}->get($emp_no);
            if (!(count($rs) == 1))
                die('get');
            $data['get_data'] = $rs;
            $data['title'] = ' اضافة شهادة خلو طرف ';
            $data['content'] = 'emp_view_create';
            $this->_look_ups($data);
            $this->load->view('template/template1', $data);
        }
    }

    /*********************************************************************************************************/
    function status_create($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->ID_VACANCY = $this->{$this->MODEL_NAME}->create($this->_postedData_1('create'));
            if (intval($this->ID_VACANCY) <= 0) {
                $this->print_error('لم يتم الحفظ' . '<br>' . $this->ID_VACANCY);
            } else {
                echo intval($this->ID_VACANCY);
            }
        } else {
            $result = $this->rmodel->get('EMP_VACANCY_TB_GET', $id);
            if (!(count($result) == 1))
                die('get');
            $adopt_rs = $this->rmodel->get('EMP_VACANCY_TB_ADOPT_GET_1', $id);
            $path_adopt = $this->rmodel->get('PATH_ADOPT_CONSTANT_ALL', $id);
            $data['master_tb_data'] = $result;
            $data['get_adopt'] = $adopt_rs;
            $data['path_list'] = $path_adopt;
            $data['can_edit'] = count($result) > 0 ? ($this->user->id == $result[0]['ENTRY_USER']) ? true : false : false;
            if ($result[0]['TYPE_ADOPT'] == 1) {
                $adoptNo = array(2 => 11, 10 => 12, 11 => 13, 12 => 20, 13 => 21, 20 => 30, 21 => 40, 30 => 41, 40 => 42, 41 => 43, 42 => 50, 43 => 60, 50 => 62, 60 => 70, 62 => 80, 70 => 90, 1 => 0, 90 => 0);
                $data['next_adopt_email'] = $this->get_emails_by_code('7.' . ($adoptNo[$result[0]['ADOPT']]));
            }


        /************************************اعتمادات المقر الفرعي **************************************************/
            elseif ($result[0]['TYPE_ADOPT'] == 2  && $result[0]['BRANCH'] != 1 && $result[0]['ADOPT'] == 1  && $result[0]['CNT_ADOPT'] < 4) {
                $adoptNo = array(
                    1 => 11,
                );
                $data['next_adopt_email'] = $this->get_emails_by_code('14.' . ($adoptNo[$result[0]['ADOPT']]), $result[0]['BRANCH']);
            } elseif ($result[0]['TYPE_ADOPT'] == 2  && $result[0]['BRANCH'] != 1 && $result[0]['CNT_ADOPT'] == 4 ) {
                $data['next_adopt_email'] = $this->get_emails_by_code('14.10', $result[0]['BRANCH']); //اعتماد الشؤون المالية والادارية بالفرع
            }elseif ($result[0]['TYPE_ADOPT'] == 2  && $result[0]['BRANCH'] != 1 && $result[0]['CNT_ADOPT'] > 4 && $result[0]['ADOPT'] >= 4  ) {
                $adoptNo = array(
                    4 => 30,
                    10 => 50,
                    30 => 70,
                    50 => 90
                );

                $data['next_adopt_email'] = $this->get_emails_by_code('14.' . ($adoptNo[$result[0]['ADOPT']]), $result[0]['BRANCH']);
            }
            /*************************************اعتمادات المقرالرئيسيي*********************************************/
            elseif ($result[0]['TYPE_ADOPT'] == 2  && $result[0]['BRANCH'] == 1 && $result[0]['ADOPT'] == 1  && $result[0]['CNT_ADOPT'] < 4) {
                $adoptNo = array(
                    1 => 10,
                );
                $data['next_adopt_email'] = $this->get_emails_by_code('14.' . ($adoptNo[$result[0]['ADOPT']]), $result[0]['BRANCH']);
            }elseif ($result[0]['TYPE_ADOPT'] == 2  && $result[0]['BRANCH'] == 1 && $result[0]['CNT_ADOPT'] == 4) {
                $data['next_adopt_email'] = $this->get_emails_by_code('14.70', $result[0]['BRANCH']); //اعتماد الرقابة
            }elseif ($result[0]['TYPE_ADOPT'] == 2  && $result[0]['BRANCH'] == 1 && $result[0]['CNT_ADOPT'] > 4 && $result[0]['ADOPT'] == 50) {
                $data['next_adopt_email'] = $this->get_emails_by_code('14.90', $result[0]['BRANCH']); //اعتماد الموارد البشرية
            }
            /*echo $result[0]['ADOPT'];
             echo $data['next_adopt_email'] ;*/
           $data['title'] = ' بيانات شهادة خلو طرف ';
            $data['content'] = 'emp_view_request_show';
            $this->_look_ups($data);
            $this->load->view('template/template1', $data);
        }
    }

    /************************************************************************************/
    function _look_ups(&$data)
    {
        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();
        $this->load->model('settings/constant_details_model');
        $data['is_active_cons'] = $this->constant_details_model->get_list(335);
        $this->load->model('hr_attendance/hr_attendance_model');
        $data['emp_no_cons'] = $this->hr_attendance_model->get_child($this->user->emp_no, 'hr_admin');
    }

    function _postedData_1($typ = null)
    {
        $result = array(
            array('name' => 'ID_VACANCY', 'value' => $this->id_vacancy, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NAME', 'value' => $this->emp_name, 'type' => '', 'length' => -1),
            array('name' => 'EMP_ID', 'value' => $this->emp_id, 'type' => '', 'length' => -1),
            array('name' => 'EMP_JOB', 'value' => $this->emp_job, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->branch, 'type' => '', 'length' => -1),
            array('name' => 'V_NOTE', 'value' => $this->v_note, 'type' => '', 'length' => -1),
            array('name' => 'EMP_END_DATE', 'value' => $this->emp_end_date, 'type' => '', 'length' => -1),
            array('name' => 'EMP_END_REASON', 'value' => $this->emp_end_reason, 'type' => '', 'length' => -1),
        );
        if ($typ == 'create')
            unset($result[0]);
        return $result;
    }

    function _postedData_f($typ = null)
    {
        $result = array(
            array('name' => 'ID_VACANCY', 'value' => $this->id_vacancy, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NO', 'value' => $this->emp_no, 'type' => '', 'length' => -1),
            array('name' => 'EMP_NAME', 'value' => $this->emp_name, 'type' => '', 'length' => -1),
            array('name' => 'EMP_ID', 'value' => $this->emp_id, 'type' => '', 'length' => -1),
            array('name' => 'EMP_JOB', 'value' => $this->emp_job, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $this->branch, 'type' => '', 'length' => -1),
            array('name' => 'V_NOTE', 'value' => $this->v_note, 'type' => '', 'length' => -1),
            //array('name' => 'EMP_END_DATE', 'value' => $this->emp_end_date, 'type' => '', 'length' => -1),
            //array('name' => 'EMP_END_REASON', 'value' => $this->emp_end_reason, 'type' => '', 'length' => -1),
        );
        if ($typ == 'create')
            unset($result[0]);
        return $result;
    }


    /*******************************************************/
    function adopt($case)
    {
        $res = $this->{$this->MODEL_NAME}->adopt($this->id_vacancy, $case, $this->adopt_note);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        return 1;
    }

    function adoptNew($case, $branch)
    {
        $res = $this->{$this->MODEL_NAME}->adoptNew($this->id_vacancy, $case, $this->adopt_note, $branch);
        if (intval($res) <= 0) {
            $this->print_error('لم يتم الاعتماد' . '<br>' . $res);
        }
        return 1;
    }


    function adopt_2()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(2);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_10()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(10);
        } else
            echo "لم يتم الاعتماد";
    }

    //اعتماد الادارة الفنية 1//////
    function adopt_11()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(11);
        } else
            echo "لم يتم الاعتماد";
    }

    //اعتماد الادارة الفنية 2
    function adopt_12()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(12);
        } else
            echo "لم يتم الاعتماد";
    }

    //اعتماد الادارة الفنية 3
    function adopt_13()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(13);
        } else
            echo "لم يتم الاعتماد";
    }

    /////////////////////////////////////////////////////////////

    function adopt_20()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(20);
        } else
            echo "لم يتم الاعتماد";
    }

    /////////////////////اللوازم والخدمات/////////////////////////////////
    function adopt_21()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(21);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_22()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(22);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_23()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(23);
        } else
            echo "لم يتم الاعتماد";
    }

    ///////////////////////////اللوازم والخدمات/////////////////////////////
    function adopt_30()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(30);
        } else
            echo "لم يتم الاعتماد";
    }


    function adopt_40()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(40);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_41()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(41);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_42()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(42);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_43()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(43);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_50()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(50);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_60()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(60);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_61()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(61);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_62()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(62);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_63()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(63);
        } else
            echo "لم يتم الاعتماد";
    }


    function adopt_70()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(70);
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_80()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(80);
        } else
            echo "لم يتم الاعتماد";
    }


    function adopt_90()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adopt(90);
        } else
            echo "لم يتم الاعتماد";
    }


    function adopt_sub_3()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(3, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_sub_4()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(4, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_sub_5()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(5, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_sub_6()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(6, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    //********اعتماد دائرة الشؤون المالية بالفرع****************/
    function adopt_sub_10()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(10, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_sub_30()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(30, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_sub_50()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(50, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_sub_70()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(70, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_sub_90()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(90, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }


    function adopt_main_10()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(10, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_main_30()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(30, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_main_40()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(40, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_main_50()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(50, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_main_70()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(70, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function adopt_main_90()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->id_vacancy != '') {
            echo $this->adoptNew(90, $this->input->post('h_branch'));
        } else
            echo "لم يتم الاعتماد";
    }

    function delete_request()
    {
        echo $this->{$this->MODEL_NAME}->delete($this->p_id);

    }

}
