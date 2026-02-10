<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm. 
 * User: mkilani
 * Date: 07/09/14
 * Time: 09:18 ص
 */
class Login extends MY_Controller
{

    var $MODEL_NAME = 'login_model';
    var $user_id;
    var $user_pwd;

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->user_id = strtolower(trim($this->input->post('user_id')));
        $this->user_pwd = trim($this->input->post('user_pwd'));
        $this->db_year = intval($this->input->post('db_year'));


        $this->session->set_userdata('db_instance', 'T');

        $paths = array(
            'GS' => trim(base_url(), '/'),
            'TASK' => 'task'
        );
        $this->session->set_userdata('sys_paths', $paths);
    }

    function index($msg = null, $reset = 0)
    {
        $data['title'] = 'دخول المستخدمين';
        $data['content'] = 'login_index';
        $data['msg'] = $msg;
        $data['reset'] = $reset;
        $data['browser'] = $this->check_browser();
        $this->check_session();
        $this->load->view('login_index', $data);
    }

    function check_session()
    {
        if (isset($this->user->id) or $this->user->id != '') {
            $this->logout();
        }
    }

    function check_browser()
    {
        $this->load->library('user_agent');
        $browser = $this->agent->browser();

        if ($this->agent->is_browser() and ($browser == 'Firefox' or $browser == 'Chrome' or $browser == 'Safari')) {
            $version = strstr($this->agent->version(), '.', true);
            if (($browser == 'Firefox' and $version >= 30) or ($browser == 'Chrome' and $version >= 35) or ($browser == 'Safari' and $version >= 500) ) {
                return 'true';
            } else
                return 'version';
        } else
            return 'browser';
    }

    function check_user()
    {
        $this->check_session();
        $min = 3;
        if (!$this->session->userdata('count_login')) {
            $this->session->set_userdata('count_login', 0);
        }

        if ($this->session->userdata('count_login') >= 6) { // 7 tries
            $this->index("يجب الانتظار لمدة $min دقائق لتتمكن من تسجيل الدخول");
            if (!$this->session->userdata('last_login')) {
                $this->session->set_userdata('last_login', time());
            }

            if ($this->session->userdata('last_login') and $this->session->userdata('last_login') + ($min * 60) < time()) {
                $this->session->set_userdata('count_login', 0);
                $this->session->unset_userdata('last_login');
            }

        } else {
            $user_data = $this->{$this->MODEL_NAME}->get($this->user_id, md5($this->user_pwd));

            if (count($user_data) == 1 and $user_data[0]['STATUS'] == 1 and strtolower(trim($user_data[0]['USER_ID'])) == $this->user_id) {

                $user = new User();
                $user->id = $user_data[0]['ID'];
                $user->username = $user_data[0]['USER_ID'];
                $user->fullname = $user_data[0]['USER_NAME'];
                $user->last_login = date('d/m/Y H:i');
                $user->position = $user_data[0]['USER_POSITION'];
                $user->db_pwd = $user_data[0]['USER_DB_PWD'];
                $user->branch = $user_data[0]['BRANCH'];
                $user->emp_no = $user_data[0]['EMP_NO'];
				$user->dep_side = $user_data[0]['DEP_SIDE'];
				$user->conflict_interest = $user_data[0]['CONFLICT_INTEREST'];
                $this->session->set_userdata('user_data', $user);
				
				/*** vacation alarm ***/
                if( in_array($user->id, array(111, 906) ) ){
                    $dt1= date('d/m/Y',(strtotime ( '-9 day' , strtotime ( date('Y-m-d') ) ) ));
                    $dt2= date('d/m/Y',(strtotime ( '-1 day' , strtotime ( date('Y-m-d') ) ) ));

                    $this->load->model('hr_attendance/emps_absence_model');
                    $absence_data= $this->emps_absence_model->get_list(' and e.no= '.$user_data[0]['EMP_NO'], 0, 99, $dt1, $dt2 , 'DATA', 2);
                    $ms_txt= 'يوجد لديك غياب غير مسجل كإجازة.
 عدد الايام '.count($absence_data);

                    if( count($absence_data) > 0 ){
                        modules::run('settings/mail/public_send', $user_data[0]['EMAIL'], 'vacation alarm', $ms_txt , 0, 1, (date('Ymdi'))*3  );
                    }
                }
                /*** vacation alarm ***/
				

                /****** Task session ******/

                $Task_Emp_Id = $user_data[0]['EMP_ID'];
                $user_task_data = $this->{$this->MODEL_NAME}->get_task_user($Task_Emp_Id);

                $info_array = array(
                    'FULLNAME' => $user_task_data[0]['EMP_NAME'],
                    'EMP_ID' => $user_task_data[0]['EMPLOYEE_ID'],
                    'NAME' => $user_task_data[0]['EMP_FULL_NAME'],
                    'JOB_NO' => $user_task_data[0]['EMP_JOB_NO'],
                    'BRANCH_NAME' => $user_data[0]['BRANCH_NAME']
                );
                $this->session->set_userdata('logged_in', $info_array);


                $user_menu = $this->{$this->MODEL_NAME}->get_task_user_menu($Task_Emp_Id);
                $test = array();
                for ($i = 0; sizeof($user_menu) > $i; $i++) {
                    array_push($test, 'index.php/' . $user_menu[$i]['ITEM_CONTROLLER'] . '/' . $user_menu[$i]['ITEM_FUNCTION']);
                }
                $this->session->set_userdata('userlist', $test);


                $style = $this->{$this->MODEL_NAME}->get_task_user_profile($Task_Emp_Id);
                $info_style = array(
                    'ACCOUNT_STYLE' => $style[0]['ACCOUNT_STYLE']
                );
                $this->session->set_userdata('style', $info_style);

                //print_r($this->session->userdata);
                //$this->session->userdata('sys_paths')['GS'];

                /****** Task session ******/

                //redirect('../'.$this->session->userdata('sys_paths')['TASK'].'/index.php');
                redirect('/welcome/csystem');

                /*
                $p_url= $this->session->userdata('curr_page_url');
                if( 1 and isset($p_url) and $p_url and strlen($p_url) > 5 ){
                    redirect($p_url);
                }else{
                    redirect('/welcome/csystem'); // '/welcome/home'
                }
                */

            } elseif (count($user_data) == 1 and $user_data[0]['STATUS'] == 0 and strtolower(trim($user_data[0]['USER_ID'])) == $this->user_id) {
                $this->index('لا تستطيع الدخول على هذا الحساب في الوقت الحالي، يرجى مراجعة ادارة الحاسوب قسم البرمجة');
            } else { //$this->UnAuthorized();
                $this->session->set_userdata('count_login', $this->session->userdata('count_login') + 1);
                $this->index('اسم المستخدم او كلمة المرور المدخلة خاطئة');
            }
        }
    }

    function reset()
    {
        $this->session->set_userdata('db_instance', 'T'); // Old #GFC# 08/01/2018
        $email = strtolower(trim($this->input->post('email')));
        $this->load->model('settings/users_model');
        $row = $this->users_model->get_user_info('', $email);
        if (count($row) == 1) {
            $new_pass = generate_pass();
            $result = $this->users_model->change_pass($row[0]['ID'], $row[0]['USER_PWD'], md5($new_pass));
            $this->Is_success($result);
            if ($this->send_email($row[0]['USER_ID'], $email, $new_pass)) {
                $this->index('تم ارسال كلمة المرور الى بريدك الالكتروني');
            } else
                $this->index('لم يتم الارسال، يرجى المحاولة في وقت لاحق', 1);
        } else
            $this->index('الايميل المدخل غير صحيح', 1);
    }


    function send_email($user_id,$email,$pass){
        //smtp_host 192.168.0.11 in php.ini
		
		$this->load->library('email');
		
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => '10.184.3.202',
			'smtp_port' => 25,
			'charset'   => 'utf-8',
			'newline' => "\r\n",
			'crlf'    => "\r\n",
			'mailtype' => 'html',
			'smtp_crypto' => '',
			'wordwrap'  => true,
			//'smtp_user' => 'gfc@gedco.ps',
			//'smtp_pass' => 'Gf##320230',
			);
			

		$this->email->initialize($config);
	
        //if(strtolower($_SERVER['HTTP_HOST'])== 'gs2.gedco.ps'){  //OLD: web87   -  $html_mail= 0;
            
        $this->email->from('gfc@gedco.ps', 'النظام المالي الموحد');
        $this->email->to($email);
        $this->email->subject('كلمة المرور الجديدة');
        $text= "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'/>
            </head>
            <body>
            <table style=\"width: 250px; direction: rtl; color: royalblue; font-size: 20px; font-weight: bold; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;\">
                <tr>
                    <td>اسم المستخدم</td>
                    <td><div style='text-align: left; color: darkgreen'> {$user_id} </div></td>
                </tr>
                <tr>
                    <td>كلمة المرور</td>
                    <td><div style='text-align: left; color: darkred'> {$pass} </div></td>
                </tr>
            </table>
            </body>
            </html>
        ";

        //if(!$html_mail){ $text= strip_tags($text); }

        $this->email->message($text);
        return @$this->email->send();
		//echo $this->email->print_debugger();
    }
	
	
    function send_emails_by_admin()
    {
        die();
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from('admin@gedco.ps', 'النظام المالي الموحد');
        $emails = array(
            'mkilani@gedco.ps'
        );
        $this->email->to('admin@gedco.ps');
        $this->email->bcc($emails);
        $this->email->subject('تغيير كلمة المرور');
        $text = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'/>
            </head>
            <body>
            <div style=\"width: 100%; direction: rtl; color: royalblue; font-size: 20px; font-weight: bold; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;\">


<span style='color:#ff0000'>تنبيه: </span>
<span>انت تستخدم كلمة المرور الافتراضية (رقم الهوية)، يجب تغيير كلمة المرور الخاصة بك قبل نهاية الشهر الحالي.</span>
<br/>
<span>يمكنك تغيير كلمة المرور من شاشة بياناتي او من الرابط</span>
<a>http://gs.gedco.com/gfc/settings/users/profile</a>
<br/>
<span>ثم اضغط على تغيير كلمة المرور.</span>
<br/>
<span style='color:blue'>ملاحظة: </span>
<span>في حال عدم تغيير كلمة المرور، سيتم ايقاف المستخدم الخاص بك، او تغيير كلمة المرور آليا.</span>

            </div>
            </body>
            </html>
        ";

        $this->email->message($text);
        $this->email->send();
        echo $this->email->print_debugger();
    }

    function logout()
    {
        $this->session->sess_destroy();
        $this->login_model->closeConnection();
        redirect('/login/');
    }
	
	function check_sys()
    {
        echo '-gfc_online-';
    }

}

