<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:35 ص
 */
$create_url=base_url('payment/checks_portfolio/index?type=2');
$delete_url=base_url('payment/Out_checks_processing/delete');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php  if( HaveAccess($delete_url)):  ?><li><a  onclick="javascript:user_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li> <?php endif; ?>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('payment/out_checks_processing/get_page',$page); ?>
        </div>

    </div>

</div>
