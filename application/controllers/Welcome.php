<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */

    function  __construct(){
        parent::__construct();

        $this->load->model('settings/sysmenus_model');
		 $this->load->library('scache');

    }

    public function index()
    {
        $data['title']='GS';
        $data['content']='welcome_message';
        $data['notifications'] = $this->_user_show_notification($this->user->id,0,10);

        $data['data_counts']=$this->system_info_model->total_sat_rep_tb_list();

        $data['income_voucher_counts']=$this->system_info_model->income_voucher_tb_sat();
        $data['payment_counts']=$this->system_info_model->financial_payment_tb_sat();

        add_js('flot/jquery.flot.js');
        add_js('flot/jquery.flot.resize.js');
        add_js('flot/jquery.flot.pie.js');
        add_js('flot/jquery.flot.stack.js');
        add_js('flot/jquery.flot.crosshair.js');
        add_js('jshashtable-2.1.js');
        add_js('jquery.formatNumber-0.1.1.min.js');

        $this->load->view('template/template',$data);
    }

    public function CSystem(){
        $data['title']='النظام الموحد';
        $data['systems'] = $this->sysmenus_model->getAllSystems();

        $this->load->view('choose_system',$data);
    }

    public function setSystem($id){

        $this->session->set_userdata('system_id', $id);
		$key = "{$this->FIN_YEAR}_{$this->user->id}_{$id}_menu";

        if (!$cache = $this->scache->read($key)) {

            $tree = modules::run('settings/sysmenus/public_get_menu', 1);
            $this->scache->write($key, $tree);
        }
        /****tareq**/
        if($id == 9 ){
            $syss_url = base_url();
            $sys_replace =  str_replace("$syss_url","/Technical/Cpanel","/Technical/Cpanel");
            $ark_root  = "http://".$_SERVER['HTTP_HOST'];
            $tech_url =  $ark_root.''.$sys_replace;
            redirect($tech_url);
            /* redirect($sys_replace);*/
            // '/Technical/cpanel'
        }else{
            redirect('/welcome/'); // '/welcome/home'
        }
     }



    public function home()
    {
        $this->load->view('home_page');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */