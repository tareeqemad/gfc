<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 11/02/15
 * Time: 04:19 م
 */
$sys__year = ($this->session->userdata('db_instance') == 'GFCARCH') ? date('Y') - 1 : date('Y');
?>
<script src="<?= base_url() ?>assets/js/functions.js"></script>

    <li class="first"><a href="<?= base_url('/') ?>" title=""><i class="icon icon-user"></i><?= get_curr_user()->fullname.' '.$sys__year ?>  </a>
        <ul>
            <li><a href="<?= base_url('/settings/users/profile') ?>">بياناتي</a></li>
            <li><a href="<?= base_url('/login/logout') ?>">تسجيل الخروج</a> </li>
        </ul>

    </li>
    <li><a href="<?= base_url('/') ?>" title="">الرئيسية</a> </li>
    <?= modules::run('settings/sysmenus/public_get_menu',1) ?>
