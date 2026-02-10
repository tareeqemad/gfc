<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 10/12/18
 * Time: 09:02 ص
 */
$MODULE_NAME='gis';
$TB_NAME="main";
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$poles_url=base_url("$MODULE_NAME/$TB_NAME/poles");
?>

<style>
    .container1 li a{
        margin-right: 10px;
        font-size: 15px;

    }
  h1,h2{
        text-align: center;
    }

</style>

<div class="row">
    <div class="container1">
        <div class="hor-menu  ">
            <ul class="nav navbar-nav">
                <li class="menu-dropdown classic-menu-dropdown active">
                    <a href="javascript:;"> الاعمدة

                    </a>
                    <ul class="dropdown-menu pull-left">
                        <li aria-haspopup="true" >
                            <a href="<?= $poles_url; ?>" class="nav-link ">
                                <i ></i>عرض عامود
                            </a>
                        </li>
                        <li aria-haspopup="true" class=" ">
                            <a href="<?= base_url('main/poles'); ?>" class="nav-link">
                                <i ></i> عرض الاعمدة </a>
                        </li>
                    </ul>
                </li>
                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown active">
                    <a href="javascript:;"> الشبكات
                        <span class="arrow"></span>
                    </a>
                    <ul class="dropdown-menu pull-left">
                        <li aria-haspopup="true" >
                            <a href="<?= base_url('main/add_network'); ?>" class="nav-link  active">
                                <i ></i> ادخال شبكة جديد
                            </a>
                        </li>
                        <li aria-haspopup="true" class=" ">
                            <a href="dashboard_2.html" class="nav-link  ">
                                <i class="icon-bulb"></i> عرض الشبكات </a>
                        </li>
                    </ul>
                </li>
                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown active">
                    <a href="javascript:;"> المحولات
                        <span class="arrow"></span>
                    </a>
                    <ul class="dropdown-menu pull-left">
                        <li aria-haspopup="true" >
                            <a href="<?= base_url('main/add_network'); ?>" class="nav-link active">
                                <i ></i> ادخال شبكة جديد
                            </a>
                        </li>
                        <li aria-haspopup="true" class=" ">
                            <a href="dashboard_2.html" class="nav-link  ">
                                <i class="icon-bulb"></i> عرض الشبكات </a>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
        <!-- END MEGA MENU -->
    </div>
    <div class="toolbar">
        <!--<div class="caption"><?=$title?></div>-->
        <ul>
            <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
        </ul>



    </div>
    <!------------------------------------------------------>
    <h1>GIS System</h1>
    <h2>قسم النظم الجغرافية</h2>


    <!------------------------------------------------------------------>

</div>



