<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/06/18
 * Time: 11:56 ص
 */

class Grant_user_tree extends MY_Controller
{

    var $MODEL_NAME= 'tree_model';

    function __construct()
    {
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/user_menus_model');
        $this->load->model('settings/users_model');
        $this->load->model('settings/sysmenus_model');
    }

    /**
     *
     * index action perform all functions in view of user menus_show view
     * from this view , can show user menus tree , insert new system menu , update exists one and delete other ..
     *
     */

    function grant_to_user($id = 0)
    {

        $this->_generate_std_urls($data, true);

        add_css('select2_metro_rtl.css');
        add_js('jquery.tree.js');
        add_js('select2.min.js');

        $data['title'] = 'تنسيب المستخدمي لنظام المعلومات';
        $data['content'] = 'indecatior_grant_menus_index';

        $data['users'] = $this->users_model->get_all(' and USERS_PROG_TB.IS_ADMIN = 0 and USERS_PROG_TB.ID <> ' . $this->user->id);


        $this->load->view('template/template', $data);
    }

    function get_tree()
    {

        $user = ($this->input->post('user')) ? $this->input->post('user') : 0;

        $this->load->helper('generate_list');

       // $resource = $this->_get_structure(0, 'getList', $user, $this->session->userdata('system_id'));
        $resource =  $this->_get_structure(0);
      /*  $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );


        $template = '<li><span data-id="{MENU_NO}"><input type="checkbox" name="menu_no" {IS_CHECKED} value="{MENU_NO}" />{MENU_ADD}</span>{SUBS}</li>';
*/
        // $data['tree'] = '<ul class="tree" id="user_menus">'.generate_list($resource, $options, $template).'</ul>';
       /* $data['tree'] = '<ul class="tree" id="user_menus"><li><span>القوائم</span><ul>' . generate_list($resource, $options, $template) . '</ul></li></ul>';
        $data['user'] = $user;
       */
        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li ><span data-id="{ID}" ondblclick="javascript:system_menu_get(\'{ID}\');"><input type="checkbox" name="menu_no" {IS_CHECKED} value="{MENU_NO}" /> {ID_NAME}</span>{SUBS}</li>';
        //$data['tree'] = '<ul class="tree" id="user_menus"><li><span>القوائم</span><ul>' . generate_list($resource, $options, $template) . '</ul></li></ul>';
        $data['tree'] = '<ul class="tree" id="plan_tree"><li><span>شجرة القطاعات و التصنيفات الرئسية و الفرعية</span><ul>'.generate_list($resource, $options, $template).'</ul>';
        //$data['tree'] = '<ul class="tree"><li class="parent_li"><span data-id="0" >شركة الكهرباء</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';
        $data['content']='prem_create_user_menus_tree';

        $data['help']=$this->help;
       // $this->_look_ups($data);
       // $this->load->view($data);
	   $data['user'] = $user;
       $this->load->view('prem_create_user_menus_tree', $data);



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
    /******************************************************************************************************************************************************/
    /*                                                             Manage tree Indicator                                                                      */
    /******************************************************************************************************************************************************/

    function _get_structure($parent) {


        $result = $this->{$this->MODEL_NAME}->get_tree_sectors($parent);


        $i = 0;

        foreach($result as $key => $item)
        {


            $result[$i]['subs'] = $this->_get_structure($item['ID']);
            // var_dump($result[$i]['subs']);


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


}