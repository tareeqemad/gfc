<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/11/14
 * Time: 09:08 ص
 */
$create_url=base_url('payment/payroll/create');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run('payment/payroll/get_page',$page,$case);?>
        </div>

    </div>

</div>

