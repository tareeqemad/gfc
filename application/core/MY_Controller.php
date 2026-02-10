<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 7/7/14
 * Time: 9:29 AM
 */
class MY_Controller extends MX_Controller
{
	public  $conn;
    public $controller;
    public $action;
    public $module;

    protected $ora;
    protected $page_size = 200;
    protected $year;
    protected $month;

    protected $help;
    public $user;
    public $database_name;
	public $database_year;
    private  static $instance;

    protected $DATEFORMAT='d/m/Y';
    protected $SERVER_DATE_FORMAT = 'd/m/Y';
    protected $today;
    protected $prev_year;


    // Start Date , End Date ..

    protected $START_DATE;
    protected $END_DATE;
    protected $FIN_YEAR;
    public $notify_Count;

    function __construct()
    {
 
        parent::__construct();
 ini_set('max_execution_time', 15000);
 
		$this->load->library('DBConn');
 
 
        self::$instance =& $this;
		$this->conn = new DBConn();

        $this->load->model('settings/user_menus_model');
        $this->load->model('settings/system_info_model');

        $this->controller = $this->router->class;
        $this->action = $this->router->method;
        $this->module = $this->router->fetch_module();
        //////////////////// init data ///////////////////////



        if($this->session->userdata('budget_year') >= 2010 ){
            $this->budget_year = $this->session->userdata('budget_year');
        }else{
            $this->budget_year = date("Y")+1;
        }


        $this->START_DATE = "01/01/".(date("Y"));
        $this->END_DATE = "31/12/".(date("Y"));

        $this->FIN_YEAR = (date("Y"));


        $db_ins=$this->session->userdata('db_instance');
        if($db_ins and strlen($db_ins)>0 ){
            $this->database_name= $db_ins;
        }
		
		$this->database_year= 2020; // تحتاج تعديل بحيث تاخد السنة الخاصة بالداتا بيز حسب اسم الداتا بيز


        $this->year = date("Y");
        $this->month =date("m");
        $this->today  = date('d-m-Y');
        $this->prev_year = '31-12-'.(date("Y") -1);

        if($db_ins != 'T') {
            $this->START_DATE = "01/01/".(date("Y") -1);
            $this->END_DATE = "31/12/".(date("Y") -1);
            $this->FIN_YEAR =  (date("Y") -1);
            $this->prev_year = '31-12-'.(date("Y") -2);
        }

        if($this->module=='budget' and 0 ){
            die('budget is closed');
        }
		
        /////////////////////////////////////////////////////

        // mkilani- save current url in session to redirect after login
        $p_url= current_url();
        $p_url= substr($p_url, 2+ strpos(ltrim($p_url,'/'), '/' ));
        if( strpos($p_url,'login') === false and strpos($p_url,'public_check_session') === false and strlen($p_url) > 5 ){
            $this->session->set_userdata('curr_url', $p_url);
        }

        if(strpos($p_url,'public_check_session') === false and strpos($p_url,'public_send') === false){
            $check_session=false;
        }else{
            $check_session=true;
        }

        // not ajax or public or reports or archive or login
        if (!$this->input->is_ajax_request() and !preg_match('/'.'public_|reports|login|archive\/download'.'/i', $p_url)){
            $this->session->set_userdata('curr_page_url', $p_url);
        }

        $this->ora= new Oracle_error();
        $this->user = new User();
        //$this->user = $this->set_user_data();

 
        if ($this->session->userdata('user_data')) {

			if( $this->session->userdata('system_id') || strtolower($this->action) ==  'csystem' || strtolower($this->action) == 'setsystem' || strtolower($this->controller) =='login'  || strtolower($this->controller) =='reports' || strtolower($this->controller) =='bonds' ) {

            }else{
                redirect('/welcome/csystem/');
            }

            $this->user= $this->session->userdata('user_data');

            $this->session->sess_update();

            $this->load->model('settings/sys_sessions_model');
            $status= $this->sys_sessions_model->get_status($this->user->id);

            if($status[0]['STATUS']==1){

                $this->load->library('user_agent');
                $browser= $this->agent->browser();
                $version= strstr($this->agent->version(), '.', true);
                if($browser=='Firefox'){
                    $browser_v= 'FF'.$version;
                }elseif($browser=='Chrome'){
                    $browser_v= 'GC'.$version;
                }else{
                    $browser_v= $browser.$version;
                }

                $all_session= $this->session->all_userdata();

                  $session_array = array(
                    array('name'=>'SESSION_ID','value'=> session_id() ,'type'=>'','length'=>-1),
                    array('name'=>'IP_ADDRESS','value'=> $this->get_client_ip() ,'type'=>'','length'=>-1),
                    array('name'=>'USER_AGENT','value'=>$_SERVER['HTTP_USER_AGENT'] ,'type'=>'','length'=>-1),
                    array('name'=>'BROWSER','value'=>$browser_v ,'type'=>'','length'=>-1),
                    array('name'=>'USER_ID','value'=>$this->user->id ,'type'=>'','length'=>-1),
                    array('name'=>'LOCATION_URL','value'=>substr(isset($all_session['curr_page_url'])?$all_session['curr_page_url']:'',0,495) ,'type'=>'','length'=>-1),
                    array('name'=>'LAST_ACTION','value'=>substr(isset($all_session['curr_url'])?$all_session['curr_url']:'',0,495) ,'type'=>'','length'=>-1),
                    array('name'=>'LAST_LOGIN','value'=>isset($all_session['last_activity']) ? date('YmdHis', $all_session['last_activity']+(60*60)) : '' ,'type'=>'','length'=>-1)
                );

				
                if(!$check_session ){ 
                    $res= $this->sys_sessions_model->create_edit($session_array);
                    if(intval($res) <= 0){
                        $this->print_error('Error: '.$res);
                    } 
                }
				
            }else{
                $this->session->unset_userdata('user_data');
                redirect('/login/','location',302);
            }

        }else{

            if($this->controller !='login' && strtolower($this->action) !='download')
                redirect('/login/','location',302);
            echo '';
        }

        /**
         *
         *  check user permissions ..
         *
         */

        $action = $this->action == 'index'?'':'/'.$this->action;
        $controller =  $this->controller == 'cpanel'?'':'/'.$this->controller;
        $QueryString = $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING']:'';

        $QueryString = $this->remove_querystring_var($QueryString,'page');



        $Menu_Full_Code =$this->module.$controller.$action.$QueryString;
        $Menu_Full_Code_index = $this->module.'/'. $this->controller .'/'.$this->action.$QueryString;

        $file_path = FCPATH.APPPATH.'modules/'.$this->module.'/controllers'.$controller.EXT;

        $permission_count =  $this->user_menus_model->check_permission($this->user->id,$Menu_Full_Code,$Menu_Full_Code_index);

        //print_r($permission_count);
        //die;

        if(intval($permission_count) <= 0 && $this->_exclude_pages() && file_exists($file_path)){

            $this->UnAuthorized();
        }

        /***
         * Convert Query Strings To Vars ..
         */
        foreach ($_GET as $key => $value) {
            $key = 'q_' . $key;
            $this->$key = $value; //Creates a public variable of the key and sets it to value

        }

        /***
         * Convert Post Data To Vars ..
         */
        foreach ($_POST as $key => $value) {
            $key = 'p_' . $key;
            $this->$key = $value; //Creates a public variable of the key and sets it to value

        }

        $help_url = base_url('settings/help_ticket/public_get');
        $this->help="javascript:get_help('{$help_url}','{$this->module}/{$this->controller}');";

        //init Token ..
        $this->AntiForgeryToken();

        $this->system_info_model->user_notification_update($this->user->id,$this->_current_url());

        if($this->user->id > 0)
            $this->notify_Count= $this->get_table_count(" NOTIFICATION_USER_TB WHERE  IS_SHOW = 1   AND  USER_ID = {$this->user->id} AND NOTIFICATION_ID IN (SELECT SEQ FROM NOTIFICATION_TB WHERE BRACH = {$this->user->branch})")[0]['NUM_ROWS'];
    }

    function _current_url()
    {
        $url = $this->uri->uri_string();
        return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
    }
    function remove_querystring_var($url, $key) {
        $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        $url =str_replace('&page=','',$url);
        $url =str_replace('page=','',$url);
        return $url;
    }

    function _exclude_pages(){

        return strpos($this->action, 'public') ===false &&
        strtolower($this->action) !='get_page_archive' &&
        strtolower($this->action) !='get_archive_page' &&
        strtolower($this->action) !='get_page_archive_last' &&
        strtolower($this->action) !='get_page' &&
        strtolower($this->action) !='download' &&
        strtolower( $this->controller) !='login' &&
        strtolower( $this->controller) !='unauthorized' &&
        strtolower( $this->controller) !='welcome' &&
        strtolower( $this->controller) !='js' &&
        strtolower( $this->controller).'/'.strtolower( $this->action) !='users/profile' &&
        strtolower( $this->controller).'/'.strtolower( $this->action) !='users/change_year' &&
        strtolower( $this->controller).'/'.strtolower( $this->action) !='users/change_pass';
    }

    function  set_user_data(){

        $user = new User();
        $user->id =24;
        $user->username='ahmedb';
        $user->position='1';
        $user->branch = 1;

        $this->session->set_userdata('user_data',$user);

        return $user;
    }

    /**
     * @return int|string
     * Generate Random Token To prevent the potential for "login CSRF" attacks
     */
    function AntiForgeryToken()
    {

        if ($this->session->userdata('__AntiForgeryToken')) {
            return $this->session->userdata('__AntiForgeryToken');
        } else {


            $this->load->library('encrypt');

            $_token = random_string();

            $key = 'super-secret-key'; // Key Should be user name , login date ..

            $encrypted_string = $this->encrypt->encode($_token, $key);

            $this->session->set_userdata('__AntiForgeryToken',$encrypted_string);

            return $encrypted_string;
        }

    }

    function IsAuthorized(){
        $__AntiForgeryToken = $this->input->post('__AntiForgeryToken');


        if($this->AntiForgeryToken() != $__AntiForgeryToken){
            header('HTTP/1.0 401 Unauthorized');
            header('HTTP/1.1 401 Unauthorized');

            die();
        }
    }

    function UnAuthorized(){
		echo 'No Permission';
        header('HTTP/1.0 401 Unauthorized');
        header('HTTP/1.1 401 Unauthorized');
        redirect('UnAuthorized',null,401);
        die();
    }

    function print_error_msg($message,$print_message =null){

        header("HTTP/1.0 500 {$message}");
        header("HTTP/1.0 500 {$message}");
        print $print_message;
        die;
    }

    function return_json($result){
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    function Is_success($message){

        $success = false;

        if(strpos($message, '"msg":1') !==false){
            $success = true;
        }

        if(trim($message) == '1' ){
            $success = true;
        }

        if(!$success)
        {

            $this->print_error($message,$this->_oracle_error_message($message));
        }

    }

    function _oracle_error_message($message){

        preg_match('/\w+\s+(?P<name>\w+-\d+):[\s+\w+]+\(\"(?P<db>\w+)\".\"(?P<tbl>\w+)\".\"(?P<col>\w+)\"\)[\s+\w+]+/', $message, $match);

        if(count($match) > 0)
            if(array_key_exists($match["name"],$this->ora->ORA))
            {

                $result = array("message"=>$this->ora->ORA[$match["name"]],"col"=>strtolower($match["col"]));
                return json_encode($result);

            }else{
                $result = array("message"=>$message,"col"=>strtolower( $match["col"]));
                return json_encode($result);
            }
        else return $message;


    }

    public static function &get_instance()
    {
        return self::$instance;
    }



    /**
     * @param $params
     * @param $data
     *
     * extract data from param in array of params
     */
    function date_format(&$params,$cols){

        $i = 0;
        foreach($params as $array){

            if(is_array($cols)){

                foreach($cols as $col){
                    //$params[$i][$col] =DateTime::createFromFormat($this->SERVER_DATE_FORMAT, $array[$col])->format($this->DATEFORMAT);
                }

            }else{

                // $params[$i][$cols] =DateTime::createFromFormat($this->SERVER_DATE_FORMAT, $array[$cols])->format($this->DATEFORMAT);
            }
            $i++;

        }


    }
	
	function check_db_for_stores(){
		// 4/1/2018 -- change GFC to T 
        if($this->database_name=='T') // GFCTRANS(2016) Added in 3/1/2017 - hisham harara -- Removed in 5/1/2017 
            return true;
        else
            return false;
    }

    function get_user_info ($id){
        $this->load->model('settings/users_model');
        return $this->users_model->get_user_info($id);
    }

    function get_entry_users ($tb, $where= null){
        $this->load->model('settings/system_info_model');
        return $this->system_info_model->get_entry_users($tb, $where);
    }

    function get_emails_by_code ($code, $branch= null, $ret=2){
        $this->load->model('settings/system_info_model');
        if($ret==2){ // email only as char
            $all= $this->system_info_model->get_emails_by_code($code, $branch);
            $emails='';
            foreach($all as $row){
                $emails.=$row['EMAIL'].',';
            }
            return substr($emails, 0, -1);
        }else{ // all data as an array
            return $this->system_info_model->get_emails_by_code($code, $branch);
        }
    }

    function get_system_info ($col,$def){
        $this->load->model('settings/system_info_model');
        $rs =  $this->system_info_model->get_all();

        if(count($rs)> 0)
            return $rs[0][$col];
        else $def;
    }

    function get_table_count ($sql){
        $this->load->model('settings/system_info_model');
        $rs =  $this->system_info_model->get_table_count($sql);

        if(count($rs)> 0)
            return $rs;
        else array();
    }
	
	function get_table_count_bills ($sql){
        $this->load->model('settings/system_info_model');
        $rs =  $this->system_info_model->get_table_count_bills($sql);

        if(count($rs)> 0)
            return $rs;
        else array();
    }


    function _insert_php_log(){

        if(isset($_POST) && $_POST!=null){



            $this->load->model('settings/system_info_model');
            $result = array(
                array('name'=>'USER_ID','value'=>$this->user->id ,'type'=>'','length'=>-1),
                array('name'=>'IP_ADDERSS','value'=>$this->input->ip_address() ,'type'=>'','length'=>-1),
                array('name'=>'ACTION','value'=> json_encode($_POST) ,'type'=>'','length'=>-1),
            );

            // $this->system_info_model->insert_php_log($result);
        }

    }

    function print_error($msg){
        echo $msg;
        $this->print_error_msg('invalid data entry .. ');
    }


    function stringDateToDate($date){

        $year = substr($date,0,3);
        $month =  substr($date,4);
        return date("01/{$month}/{$year}");

    }

    function _loadDatePicker(){
        add_css('datepicker3.css');
        add_css('combotree.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
    }

    /**
     * this notification system for all app , allow to send notification to users
     * that have access on exists action ..
     * @param $action
     * @param $message
     */
    function _notifyMessage($action,$url,$message,$prev_url = null){



        $url_get = "{$this->module}/{$this->controller}/{$action}";

        $this->system_info_model->user_notification($url_get,$url,$message,$prev_url);



    }

    function  _user_show_notification($user_id,$offset,$row){


        return $this->system_info_model->user_show_notification($user_id,$offset,$row);
    }


    /** export excel file ..
     * @param $fileTitle
     * @param $header
     * @param $data
     */
    function ExportXLS($fileTitle , $header , $data){


        $filename = "{$fileTitle}.xlsx";
        header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');


        $writer = new XLSXWriter();
        $writer->writeSheet($data,'Sheet1', $header);
        $writer->writeToStdOut();
    }
	
	/**
     * generate stander urls , like get , create  ..
     */
      function _generate_std_urls(&$data, $HasJS = false , $HasCSS = false)
    {

        $data['create_url'] = base_url("{$this->module}/{$this->controller}/create");
        $data['edit_url'] =  base_url("{$this->module}/{$this->controller}/edit");
        $data['delete_url'] =  base_url("{$this->module}/{$this->controller}/delete");
        $data['select_url'] =  base_url("{$this->module}/{$this->controller}/public_index");
        $data['report_url'] = "report";
        $data['back_url'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        if ($HasJS)
            $data['js_file'] = "template/js/?view={$this->module}&js_action={$this->action}&controller={$this->controller}";

        if ($HasCSS)
            $data['css_file'] = "template/css/?view={$this->module}&css_action={$this->action}&controller={$this->controller}";

        return $data;
    }
	
	 function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
	
	 /*
     * filtering an array
     */
    function filter_by_value ($array, $index, $value){
		
		$newarray = array();
		
        if(is_array($array) && count($array)>0)
        {
            foreach(array_keys($array) as $key){
                 $arr_value = $array[$key][$index];
 
                if ($arr_value == $value){
                    $newarray[$key] = $array[$key];
                }
            }
        }
        return $newarray;
    }
	
	   function _is_posted()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

}
