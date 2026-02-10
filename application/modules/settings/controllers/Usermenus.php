<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/4/14
 * Time: 9:29 AM
 */
class Usermenus extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('user_menus_model');
        $this->load->model('users_model');
        $this->load->model('sysmenus_model');

        $this->user_no= $this->input->post('user_no');
        $this->system_id= $this->input->post('system_id');

    }

    /**
     *
     * index action perform all functions in view of user menus_show view
     * from this view , can show user menus tree , insert new system menu , update exists one and delete other ..
     *
     */

    function index($id = 0)
    {

        $this->_generate_std_urls($data, true);

        add_css('select2_metro_rtl.css');
        add_js('jquery.tree.js');
        add_js('select2.min.js');

        $data['title'] = 'صلاحيات مستخدم النظام';
        $data['content'] = 'user_menus_index';
		
		$this->load->model('settings/gcc_branches_model');
        $data['branches']= $this->gcc_branches_model->get_all();

        $data['users'] = $this->users_model->get_all(" and USERS_PROG_TB.IS_ADMIN = 0 and (USERS_PROG_TB.BRANCH = {$this->user->branch} or {$this->user->branch} = 1) " ); // and USERS_PROG_TB.ID <> {$this->user->id}


        $this->load->view('template/template', $data);
    }

    function get_menus()
    {

        $user = ($this->input->post('user')) ? $this->input->post('user') : 0;

        $this->load->helper('generate_list');

        $resource = $this->_get_structure(0, 'getList', $user, $this->session->userdata('system_id'));

        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );


        $template = '<li><span data-id="{MENU_NO}"><input type="checkbox" name="menu_no" {IS_CHECKED} value="{MENU_NO}" />{MENU_ADD}</span>{SUBS}</li>';

        // $data['tree'] = '<ul class="tree" id="user_menus">'.generate_list($resource, $options, $template).'</ul>';
        $data['tree'] = '<ul class="tree" id="user_menus"><li><span>القوائم</span><ul>' . generate_list($resource, $options, $template) . '</ul></li></ul>';
        $data['user'] = $user;
        $this->load->view('user_menus_tree', $data);

    }


    function user_systems()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($this->p_action)) {
                $rs = $this->users_model->user_system_admin_delete($this->p_id);
                echo $rs;
            } else {

                $rs = $this->users_model->user_system_admin_insert($this->p_user_no, $this->p_id_system);
                echo $rs;
            }

        } else {
            add_css('select2_metro_rtl.css');

            add_js('select2.min.js');

            $data['title'] = 'إدارة مستخدمي الانظمة';
            $data['content'] = 'users_systems_index';
            $data['users'] = $this->users_model->get_all(' and USERS_PROG_TB.IS_ADMIN = 0 and USERS_PROG_TB.ID <> ' . $this->user->id);
            $data['systems'] = $this->sysmenus_model->getAllSystems();
            $data['rows'] = $this->users_model->get_user_system();
            $this->load->view('template/template', $data);
        }
    }

    /**
     * get system menu by id ..
     */
    function get_id()
    {

        $id = $this->input->post('id');
        $result = $this->user_menus_model->get($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get user menus tree structure ..
     *
     */
    function _get_structure($parent = 0, $fun, $user, $id_system = null)
    {
        $result = $this->user_menus_model->{$fun}($parent, $user, $id_system);
        $i = 0;
        foreach ($result as $key => $item) {
            $result[$i]['subs'] = $this->_get_structure($item['MENU_NO'], $fun, $user);
            $i++;
        }
        return $result;
    }

    /**
     * edit action : insert exists system menu data ..
     * receive post data of system menu
     * depended on system menu prm key
     */
    function create()
    {


        if ($this->session->userdata('system_id')) {

            $result = array(
                array('name' => 'USER_NO', 'value' => $this->p_user_id, 'type' => '', 'length' => -1),
                array('name' => 'MENU_NO', 'value' => $this->p_menu_no, 'type' => '', 'length' => -1),
                array('name' => 'DELETED', 'value' => $this->p_deleted, 'type' => '', 'length' => -1)
            );

            $output = $this->user_menus_model->create($result);

            echo $output;
        } else {
            $this->print_error('يجب تحديد النظام المراد العمل عليه');
        }
    }

    /**
     * delete action : delete system menu data ..
     * receive prm key as request
     *
     */
    function delete($USER_ID)
    {


        $this->IsAuthorized();

        $system_id = $this->user->id == 584 || $this->user->id == 111 || $this->user->id == 593 || $this->user->id == 105 ? null : $this->session->userdata('system_id');

        $this->user_menus_model->delete($USER_ID, $system_id);

    }

    function user_menus($id = 0)
    {

        add_css('select2_metro_rtl.css');
        add_js('jquery.tree.js');
        add_js('select2.min.js');

        $data['title'] = 'عرض صلاحيات الموظف';
        $data['content'] = 'get_user_menus_index';

        $this->load->model('settings/gcc_branches_model');
        $data['branches']= $this->gcc_branches_model->get_all();
        $data['users'] = $this->users_model->get_all(" and USERS_PROG_TB.IS_ADMIN = 0 and (USERS_PROG_TB.BRANCH = {$this->user->branch} or {$this->user->branch} = 1) " );
        $data['systems'] = $this->sysmenus_model->getAllSystems();

        $this->load->view('template/template', $data);
    }

    function get_page_user_menus(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and $this->user_no!='' and $this->system_id!=''){
            $data['page_rows'] = $this->user_menus_model->get_user_menus($this->user_no,$this->system_id);
            $this->load->view('get_user_menus_page',$data);
        }else{
            $this->print_error('اختر الموظف والنظام');
        }

    }
}