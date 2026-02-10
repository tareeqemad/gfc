<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/1/14
 * Time: 1:16 PM
 */
class Sysmenus extends MY_Controller
{


    function  __construct()
    {
        parent::__construct();

        $this->load->model('sysmenus_model');
		//$this->load->library('scache');
    }

    /**
     *
     * index action perform all functions in view of system menus_show view
     * from this view , can show system menus tree , insert new system menu , update exists one and delete other ..
     *
     */

    function index()
    {
        $this->load->helper('generate_list');


        // add_css('jquery-hor-tree.css');
        add_js('jquery.tree.js');
        add_js('jquery.mjs.nestedSortable.js');


        $data['title'] = ' هيكلية قوائم النظام';
        $data['content'] = 'system_menus_index';
        $data['systems'] = $this->sysmenus_model->getAllSystems();


        $resource = $this->_get_structure(0, 'getList');

        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );

        $template = '<li data-id="{MENU_NO}" ><span data-id="{MENU_NO}" ondblclick="javascript:system_menu_get(\'{MENU_NO}\');"><i class="glyphicon glyphicon-minus-sign"></i>{MENU_ADD}</span>{SUBS}</li>';

        // $data['tree'] = '<ul class="tree" id="system_menus">'.generate_list($resource, $options, $template).'</ul>';
        $data['tree'] = '<ul class="tree" id="system_menus"><li><span data-id="0"><i class="glyphicon glyphicon-minus-sign"></i>القوائم</span><ul>' . generate_list($resource, $options, $template) . '</ul></li></ul>';

        $this->load->view('template/template', $data);
    }

    /**
     * get system menu by id ..
     */
    function get_id()
    {

        $id = $this->input->post('id');
        $result = $this->sysmenus_model->get($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }

    /**
     * @param int $parent
     * @return mixed
     *
     * get system menus tree structure ..
     *
     */
    function _get_structure($parent = 0, $fun, $view = -1, $main = -1, $user = -1, $MENU_C0DE = -1, $icon = false, $system_id = null)
    {

        $result = $this->sysmenus_model->{$fun}($parent, $view, $main, $user, $MENU_C0DE, $system_id);
        $i = 0;
        foreach ($result as $key => $item) {
            if ($icon)
                $result[$i]['ICONS'] = "<i class=\"icon icon-{$item['ICON']} \" ></i>";
            if ($item['MENU_FULL_CODE'] != 'javascript:;')
                $result[$i]['MENU_FULL_CODE'] = base_url('/') . $item['MENU_FULL_CODE'];

            $result[$i]['subs'] = $this->_get_structure($item['MENU_NO'], $fun, $view, $main, $user);
            $i++;
        }
        return $result;
    }


    /**
     * get system menu by id ..
     */
    function public_get_menu($view = -1, $main = -1, $MENU_C0DE = -1, $icon = false)
    {
		
		
        $_system_id = $this->session->userdata('system_id');

        $key = "{$this->FIN_YEAR}_{$this->user->id}_{$_system_id}_menu";

		/*
        if ($cache = $this->scache->read($key)) {
            return $cache;
        }
		*/

        $this->load->helper('generate_list');

 

        $_root_id = $this->sysmenus_model->getList(0,null,null,null,$MENU_C0DE,$_system_id)[0]["MENU_NO"];


        $resource = $this->_get_structure($_root_id, 'getList', $view, $main, $this->user->id, $MENU_C0DE, $icon,null );

		//print_r($this->sysmenus_model->getList(0,null,null,null,null,$_system_id));die;
		//echo $_system_id.' '.$_root_id .' '. $this->user->id;die;
		//print_r($resource); die;

        $options = array(
            'template_head' => '<ul>',
            'template_foot' => '</ul>',
            'use_top_wrapper' => false
        );


        $template = "<li><a href=\"{MENU_FULL_CODE}\" >{ICONS}{MENU_ADD}</a>{SUBS}</li>";

        $data['tree'] = '' . generate_list($resource, $options, $template) . '';

        $resource = $this->_get_structure(0, 'getList', $view, $main, $this->user->id, $MENU_C0DE, $icon,null );

        $data['tree'] .= '' . generate_list($resource, $options, $template) . '';
 
        return $data['tree'];
    }

    function public_get_menu1($view = -1, $main = -1, $MENU_C0DE = -1, $icon = false)
    {
        $_system_id = $this->session->userdata('system_id');
        $key = "{$this->FIN_YEAR}_{$this->user->id}_{$_system_id}_menu";
        $this->load->helper('generate_list');
        $_root_id = $this->sysmenus_model->getList(0,null,null,null,$MENU_C0DE,$_system_id)[0]["MENU_NO"];
        $resource = $this->_get_structure($_root_id, 'getList', $view, $main, $this->user->id, $MENU_C0DE, $icon,null );
        $menu_html='';

        foreach ($resource as $row){
            $menu_html.= '<li class="slide">
                            <a  class="side-menu__item" data-bs-toggle="slide" href="'. $row['MENU_FULL_CODE'].'">
                              <span class="side-menu__label">'.$row['MENU_ADD'].'</span><i class="angle fa fa-angle-right"></i></a>' ;
            if(count($row['subs']) > 0){
                $menu_html.= '<ul class="slide-menu">';
                foreach ($row['subs'] as $subs_1){
                    if(count($subs_1['subs']) > 0){
                        $menu_html.= '<li class="sub-slide">
                                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="'. $subs_1['MENU_FULL_CODE'].'"><span class="sub-side-menu__label">'.$subs_1['MENU_ADD'].'</span><i class="sub-angle fa fa-angle-right"></i></a>';
                        $menu_html.= '<ul  class="sub-slide-menu">';
                        foreach ($subs_1['subs'] as $subs_2){
                            if(count($subs_2['subs']) > 0){
                                $menu_html.= '<li class="sub-slide2">
                                            <a  class="sub-side-menu__item2" data-bs-toggle="sub-slide2" href="'. $subs_2['MENU_FULL_CODE'].'"><span class="sub-side-menu__label2">'.$subs_2['MENU_ADD'].'</span><i class="sub-angle2 fa fa-angle-right"></i></a>
                                            <ul class="sub-slide-menu2">';

                                foreach ($subs_2['subs'] as $subs_3){
                                    $menu_html.='<li><a href="'. $subs_3['MENU_FULL_CODE'].'" class="sub-slide-item2">'.$subs_3['MENU_ADD'].'</a></li>';
                                }

                                $menu_html.= '</ul>';
                                $menu_html.= '</li>';
                            }else{
                                $menu_html.= '<li><a  class="sub-side-menu__item" href="'. $subs_2['MENU_FULL_CODE'].'">'.$subs_2['MENU_ADD'].'</a></li>';
                            }

                        }
                        $menu_html.= '</ul>';
                        $menu_html.= '</li>';

                    }else{
                        $menu_html.= '<li><a href="'. $subs_1['MENU_FULL_CODE'].'" class="slide-item">'.$subs_1['MENU_ADD'].'</a></li>';
                    }
                }
                $menu_html.= '</ul>';
            }
            $menu_html.= '</li>';
        }

        return $menu_html;
    }

    function public_get_setting($view = -1, $main = -1, $MENU_C0DE = -1, $icon = false)
    {
        $_system_id = $this->session->userdata('system_id');
        $key = "{$this->FIN_YEAR}_{$this->user->id}_{$_system_id}_menu";
        $this->load->helper('generate_list');
        $resource = $this->_get_structure(0, 'getList', $view, $main, $this->user->id, $MENU_C0DE, $icon,null );
        $menu_html='';

        foreach ($resource as $row){
            $menu_html.= '<li class="slide">
                            <a  class="side-menu__item" data-bs-toggle="slide" href="'. $row['MENU_FULL_CODE'].'">
                              <span class="side-menu__label">'.$row['MENU_ADD'].'</span><i class="angle fa fa-angle-right"></i></a>' ;
            if(count($row['subs']) > 0){
                $menu_html.= '<ul class="slide-menu">';
                foreach ($row['subs'] as $subs_1){
                    if(count($subs_1['subs']) > 0){
                        $menu_html.= '<li class="sub-slide">
                                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="'. $subs_1['MENU_FULL_CODE'].'"><span class="sub-side-menu__label">'.$subs_1['MENU_ADD'].'</span><i class="sub-angle fa fa-angle-right"></i></a>';
                        $menu_html.= '<ul  class="sub-slide-menu">';
                        foreach ($subs_1['subs'] as $subs_2){
                            if(count($subs_2['subs']) > 0){
                                $menu_html.= '<li class="sub-slide2">
                                            <a  class="sub-side-menu__item2" data-bs-toggle="sub-slide2" href="'. $subs_2['MENU_FULL_CODE'].'"><span class="sub-side-menu__label2">'.$subs_2['MENU_ADD'].'</span><i class="sub-angle2 fa fa-angle-right"></i></a>
                                            <ul class="sub-slide-menu2">';

                                foreach ($subs_2['subs'] as $subs_3){
                                    $menu_html.='<li><a href="'. $subs_3['MENU_FULL_CODE'].'" class="sub-slide-item2">'.$subs_3['MENU_ADD'].'</a></li>';
                                }

                                $menu_html.= '</ul>';
                                $menu_html.= '</li>';
                            }else{
                                $menu_html.= '<li><a  class="sub-side-menu__item" href="'. $subs_2['MENU_FULL_CODE'].'">'.$subs_2['MENU_ADD'].'</a></li>';
                            }

                        }
                        $menu_html.= '</ul>';
                        $menu_html.= '</li>';

                    }else{
                        $menu_html.= '<li><a href="'. $subs_1['MENU_FULL_CODE'].'" class="slide-item">'.$subs_1['MENU_ADD'].'</a></li>';
                    }
                }
                $menu_html.= '</ul>';
            }
            $menu_html.= '</li>';
        }

        return $menu_html;


    }

    // MKilani - Canceled
    function public_get_system()
    {

        $data['systems'] = $this->sysmenus_model->getAllSystems();
        $base_url = base_url('/welcome/setSystem/');

        $template = '<li ><a class="system_list" href="javascript:;" >الأنظمة </a><ul>';

        foreach ($data['systems'] as $row)
            $template = $template . "<li><a href=\"{$base_url}/{$row['ID']}\">{$row['NAME']}</a></li>";
			
		$template .=  "<li><a href='".gh_gfc_domain()."/trading_2022/Cpanel'>النظام التجاري</a></li>";

        return $template.'</ul></li>';
    }


    /**
     * create action : insert new system menu data ..
     * receive post data of system menu
     *
     */
    function create()
    {

        $result = $this->sysmenus_model->create($this->_postedData('create'));
        $this->Is_success($result);
        $this->return_json($result);

    }

    /**
     * edit action : update exists system menu data ..
     * receive post data of system menu
     * depended on system menu prm key
     */
    function edit()
    {

        echo $this->sysmenus_model->edit($this->_postedData());

    }

    /**
     * delete action : delete system menu data ..
     * receive prm key as request
     *
     */
    function delete()
    {

        $id = $this->input->post('id');

        $this->IsAuthorized();

        if (is_array($id)) {
            foreach ($id as $val) {
                echo $this->sysmenus_model->delete($val);
            }
        } else {
            echo $this->sysmenus_model->delete($id);
        }

    }

    /**
     * delete action : delete system menu data ..
     * receive prm key as request
     *
     */
    function sort()
    {

        $id = $this->input->post('id');

        $this->IsAuthorized();

        if (is_array($id)) {
            $i = 0;
            foreach ($id as $val) {
                echo $this->sysmenus_model->sort($val, $i);
                $i++;
            }
        }
    }

    /**
     * @return array
     *
     *  pass posted data to vars ..
     *
     */
    function _postedData($create = null)
    {

        $MENU_PARENT_NO = $this->input->post('menu_parent_no');
        $MENU_NO = $this->input->post('menu_no');
        $MENU_ADD = $this->input->post('menu_add');
        $MENU_CODE = $this->input->post('menu_code');
        $MAIN_MENU = $this->input->post('main_menu');
        $VIEW_MENU = $this->input->post('view_menu');
        $RELATED_OBJECT = $this->input->post('related_object');
        $MENU_FULL_CODE_IN = $this->input->post('menu_full_code_in');
        $ICON = $this->input->post('icon');

        $MENU_CODE = str_replace('[removed];', 'javascript:;', $MENU_CODE);

        $result = array(
            array('name' => 'MENU_NO', 'value' => $MENU_NO, 'type' => '', 'length' => -1),
            array('name' => 'MENU_PARENT_NO', 'value' => $MENU_PARENT_NO, 'type' => '', 'length' => -1),
            array('name' => 'MENU_ADD', 'value' => $MENU_ADD, 'type' => '', 'length' => -1),
            array('name' => 'MENU_CODE', 'value' => $MENU_CODE, 'type' => '', 'length' => -1),
            array('name' => 'MAIN_MENU', 'value' => $MAIN_MENU, 'type' => '', 'length' => -1),
            array('name' => 'VIEW_MENU', 'value' => $VIEW_MENU, 'type' => '', 'length' => -1),
            array('name' => 'RELATED_OBJECT', 'value' => $RELATED_OBJECT, 'type' => '', 'length' => -1),
            array('name' => 'ICON', 'value' => $ICON, 'type' => '', 'length' => -1),
            array('name' => 'ID_SYSTEM_IN', 'value' => $this->p_id_system, 'type' => '', 'length' => -1),

        );


        if ($create == 'create') {
            array_shift($result);
        }

        return $result;
    }
}