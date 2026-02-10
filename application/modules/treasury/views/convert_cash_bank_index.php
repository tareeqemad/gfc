<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:35 ص
 */
$create_url=base_url('treasury/convert_cash_bank/create');
$delete_url=base_url('treasury/convert_cash_bank/delete');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php  if( HaveAccess($delete_url)):  ?><li><a  onclick="javascript:user_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li> <?php endif; ?>
            <li><a  onclick="$('#cash_bank_Tbl').tableExport({type:'excel',escape:'false'});" href="javascript:;"><i class="glyphicon glyphicon-file"></i>اكسل</a> </li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('treasury/convert_cash_bank/get_page',$page); ?>
        </div>

    </div>

</div>
