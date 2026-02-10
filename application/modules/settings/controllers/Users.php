<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:33 AM
 */
class Users extends MY_Controller
{

    var $USER_ID;
    var $USER_NAME;
    var $USER_POSITION;
    var $USER_PWD;


    function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
        $this->load->model('login_model');


        $this->load->model('root/rmodel');
        $this->rmodel->package = 'HR_PKG';

    }

    /**
     *
     * index action perform all functions in view of users_show view
     * from this view , can show users tree , insert new user , update exists one and delete other ..
     *
     */
    function index($page = 1, $no = -1, $name = -1, $branch = -1)
    {

        add_css('combotree.css');
        $data['title'] = 'إدارة المستخدمين';
        $data['content'] = 'users_index';
        $data['page'] = $page;


        $this->load->model('settings/gcc_branches_model');

        $data['branches'] = $this->gcc_branches_model->get_all();

        $data['no'] = $no;
        $data['name'] = $name;
        $data['branch'] = $branch;

        $this->load->view('template/template', $data);

    }

    function get_page($page = 1, $no = -1, $name = -1, $branch = -1)
    {

        $this->load->library('pagination');


        $no = $this->input->post('no') ? $this->input->post('no') : $no;
        $name = $this->input->post('name') ? $this->input->post('name') : $name;
        $branch = $this->input->post('branch') ? $this->input->post('branch') : $branch;

        $config['base_url'] = base_url("settings/users/get_page/");

        $no = $no == -1 ? null : $no;
        $name = $name == -1 ? null : $name;
        $branch = $branch == -1 ? null : $branch;


        $count_rs = $this->users_model->get_count($no, $name, $branch);


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

        $data["users"] = $this->users_model->get_list($no, $name, $branch, $offset, $row);


        $data['offset'] = $offset + 1;
        $data['page'] = $page;

        $this->load->view('users_page', $data);

    }


    /**
     * get user by id ..
     */
    function get_id()
    {

        $id = $this->input->post('id');
        $result = $this->users_model->get($id);

        $this->return_json($result);
    }

    // mkilani- data of user
    function profile()
    {
        $data['title'] = 'بياناتي';
        $data['content'] = 'users_profile';
        $data['year'] = $this->budget_year;
        $data['can_change_pass'] = ($this->database_name == 'T') ? 1 : 0;
        $data['data'] = $this->users_model->get_user_info($this->user->id);
        $this->load->view('template/template', $data);
    }

    // mkilani- login by any user- only for admins..
    function login_by_user($id = 0)
    {

        //$ayman= array(774,754,140,141,118,526,770,755,531,530,759,745,765,145,147,746,751,485,773,493,555,753,505,474,514,522,747,752);
        //$tekrayem= array(783,482,156,782,153,546,545,592,472,556);  and in_array($id, $tekrayem)

        if ($id and ($this->user->id == 111)) {
            $user_data = $this->users_model->get_user_info($id);

            $user = new User();
            $user->id = $user_data[0]['ID'];
            $user->username = $user_data[0]['USER_ID'];
            $user->fullname = $user_data[0]['USER_NAME'];
            $user->last_login = date('d/m/Y H:i');
            $user->position = $user_data[0]['USER_POSITION'];
            $user->db_pwd = $user_data[0]['USER_DB_PWD'];
            $user->branch = $user_data[0]['BRANCH'];
            $user->emp_no = $user_data[0]['EMP_NO'];
            $this->session->set_userdata('user_data', $user);

            /****** Task session ******/

            $Task_Emp_Id = $user_data[0]['EMP_ID'];
            $user_task_data = $this->login_model->get_task_user($Task_Emp_Id);

            $info_array = array(
                'FULLNAME' => $user_task_data[0]['EMP_NAME'],
                'EMP_ID' => $user_task_data[0]['EMPLOYEE_ID'],
                'NAME' => $user_task_data[0]['EMP_FULL_NAME'],
                'JOB_NO' => $user_task_data[0]['EMP_JOB_NO'],
                'BRANCH_NAME' => $user_data[0]['BRANCH_NAME']
            );
            $this->session->set_userdata('logged_in', $info_array);


            $user_menu = $this->login_model->get_task_user_menu($Task_Emp_Id);
            $test = array();
            for ($i = 0; sizeof($user_menu) > $i; $i++) {
                array_push($test, 'index.php/' . $user_menu[$i]['ITEM_CONTROLLER'] . '/' . $user_menu[$i]['ITEM_FUNCTION']);
            }
            $this->session->set_userdata('userlist', $test);


            $style = $this->login_model->get_task_user_profile($Task_Emp_Id);
            $info_style = array(
                'ACCOUNT_STYLE' => $style[0]['ACCOUNT_STYLE']
            );
            $this->session->set_userdata('style', $info_style);

            /****** Task session ******/


        }
        redirect('/welcome/');
    }

    // mkilani- change password
    function change_pass()
    {
        $id = $this->user->id;
        $curr_pwd = trim($this->input->post('curr_pwd'));
        $user_pwd = trim($this->input->post('user_pwd'));
        $user_cpwd = trim($this->input->post('user_cpwd'));

        if ($id != '' and $curr_pwd != '' and $user_pwd != '' and $user_cpwd != ''
            and $curr_pwd != $user_pwd and $user_pwd == $user_cpwd and strlen($user_pwd) >= 8) {
            if ($user_pwd != 'Aa#12345') {
                $result = $this->users_model->change_pass($id, md5($curr_pwd), md5($user_pwd));
				//echo $id.'-'. md5($curr_pwd).'-'. md5($user_pwd);
                if ($result == 0)
                    echo "لم يتم تغيير كلمة المرور";
                else
                    echo $result;
            } else
                echo "لا يمكن استخدام هذه الكلمة اختر كلمة مرور جديدة اخرى";
        } else {
            echo "يجب ادخال البيانات بطريقة صحيحة";
        }
    }

    // MKilani
    function public_get_name_gov()
    {
        if ($this->user->id != 111) {
            die('E');
        }
        $id = $this->input->post('id');
        $n1 = $this->input->post('n1');
        $n2 = $this->input->post('n2');
        $n3 = $this->input->post('n3');
        $n4 = $this->input->post('n4');
        $result = $this->users_model->get_name_gov($id, $n1, $n2, $n3, $n4);
        echo 'END:' . $result;
    }

    // mkilani- change budget year
    function change_year()
    {
        $year = intval(trim($this->input->post('year')));
        if ($year >= 2010 and $year <= 3000) {
            $this->session->set_userdata('budget_year', $year);
            echo 1;
        } else
            echo 0;
    }

    /**
     * create action : insert new user data ..
     * receive post data of user
     *
     */
    function create()
    {

        $result = $this->users_model->create($this->_postedData());
        $this->Is_success($result);
        echo modules::run('settings/users/get_page', 1);

    }

    /**
     * edit action : update exists user data ..
     * receive post data of user
     * depended on user prm key
     */
    function edit()
    {

        $result = $this->users_model->edit($this->_postedData());

        $this->Is_success($result);

        echo modules::run('settings/users/get_page', 1);

    }

    /**
     * delete action : delete user data ..
     * receive prm key as request
     *
     */
    function delete()
    {

        $id = $this->input->post('id');

        $this->IsAuthorized();

        $msg = 0;

        if (is_array($id)) {
            foreach ($id as $val) {
                $msg = $this->users_model->delete($val);
            }
        } else {
            $msg = $this->users_model->delete($id);
        }

        if ($msg == 1) {
            echo modules::run('settings/users/get_page', 1);
        } else {

            $this->print_error_msg($msg);
        }
    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData()
    {

        //$this->load->library('encrypt');

        $this->USER_ID = $this->input->post('user_id');
        $this->USER_NAME = $this->input->post('user_name');
        $this->USER_POSITION = $this->input->post('user_position');
        $this->USER_PWD = $this->input->post('user_pwd');


        $EMP_NO = $this->input->post('emp_no');
        $EMAIL = $this->input->post('email');
        $BRANCH = $this->input->post('branch');


        if ($this->USER_PWD != '' && $this->USER_PWD != null) {
            //$this->USER_PWD = $this->encrypt->encode($this->USER_PWD);
            $this->USER_PWD = md5($this->USER_PWD);
        }


        $result = array(
            array('name' => 'USER_ID', 'value' => $this->USER_ID, 'type' => '', 'length' => 15),
            array('name' => 'USER_NAME', 'value' => $this->USER_NAME, 'type' => '', 'length' => 90),
            array('name' => 'USER_POSITION', 'value' => $this->USER_POSITION, 'type' => '', 'length' => 15),
            array('name' => 'USER_PWD', 'value' => $this->USER_PWD, 'type' => '', 'length' => 32),
            array('name' => 'EMP_NO', 'value' => $EMP_NO, 'type' => '', 'length' => -1),
            array('name' => 'EMAIL', 'value' => $EMAIL, 'type' => '', 'length' => -1),
            array('name' => 'BRANCH', 'value' => $BRANCH, 'type' => '', 'length' => -1)
        );

        return $result;
    }


    function public_emp_data()
    {

        $data['title'] = 'تحديث بياناتي';
        $data_info = $this->users_model->get_user_info($this->user->id);
        $result = $this->rmodel->get('EMP_INFO_UPDATE_TB_GET', $data_info[0]['EMP_NO']);
        $data['result'] = $result;
        $data['data_info'] = $data_info;
        $data['content'] = 'users_emp_data';
        $this->load->view('template/template', $data);

    }

    function public_create_emp_data()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $data_arr = array(
                array('name' => 'EMP_NO_IN', 'value' => $this->p_h_emp_no, 'type' => '', 'length' => -1),
                array('name' => 'EMP_ID_IN', 'value' => $this->p_txt_emp_id, 'type' => '', 'length' => -1),
                array('name' => 'JAWAL_NO_IN', 'value' => $this->p_txt_jawal_no, 'type' => '', 'length' => -1)
            );

            $res = $this->rmodel->insert('EMP_INFO_UPDATE_TB_INSERT', $data_arr);
            if (intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error('Error_' . $res);
            }
        }

    }

    function public_edit_emp_data(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->_post_validation();
            $data_arr = array(
                array('name' => 'EMP_NO_IN', 'value' => $this->p_h_emp_no, 'type' => '', 'length' => -1),
                array('name' => 'EMP_ID_IN', 'value' => $this->p_txt_emp_id, 'type' => '', 'length' => -1),
                array('name' => 'JAWAL_NO_IN', 'value' => $this->p_txt_jawal_no, 'type' => '', 'length' => -1)
            );

            $res = $this->rmodel->update('EMP_INFO_UPDATE_TB_UPDATE', $data_arr);
            if (intval($res) >= 1) {
                echo 1;
            } else {
                $this->print_error('Error_' . $res);
            }
        }
    }


    function _post_validation(){ //////////////////////////////////
        //$this->print_error('يجب ادخال جميع البيانات');
        if( $this->p_txt_jawal_no==''){
            $this->print_error('ادخل رقم الجوال');
        }else if( strlen( $this->p_txt_jawal_no ) != 10 ){
            $this->print_error('الرجاء كتابة رقم الجوال بشكل صحيح');
        }else if( substr($this->p_txt_jawal_no, 0, 3) != '059' and substr($this->p_txt_jawal_no, 0, 3) != '056'){
            $this->print_error('يجب ان يبدأ رقم الجوال ب059 أو 056');
        }
    }
}

