<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/6/14
 * Time: 8:09 AM
 */

?>

<div class="row">

    <ul class="cpanel-menu">


        <?= modules::run('settings/sysmenus/public_get_menu',1,1,$module) ?>

    </ul>

</div>
<hr/>
<div class="row cpanel">

    <div class="form-body">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green">
                <div class="visual">
                    <i class="icon icon-shekel"></i>
                </div>
                <div class="details">
                    <div class="number">
$9999.99
                    </div>
                    <div class="desc">
إحصاءات مالية
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red">
                <div class="visual">
                    <i class="icon icon-shekel"></i>
                </div>
                <div class="details">
                    <div class="number">
                        $9999.99
                    </div>
                    <div class="desc">
                        إحصاءات مالية
                    </div>
                </div>

            </div>
        </div>


        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="icon icon-shekel"></i>
                </div>
                <div class="details">
                    <div class="number">
                        $9999.99
                    </div>
                    <div class="desc">
                        إحصاءات مالية
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat yellow">
                <div class="visual">
                    <i class="icon icon-shekel"></i>
                </div>
                <div class="details">
                    <div class="number">
                        $9999.99
                    </div>
                    <div class="desc">
                        إحصاءات مالية
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>