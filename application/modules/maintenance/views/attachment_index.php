<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 16/12/14
 * Time: 03:33 م
 */

$count = 0;

?>


<a href="javascript:;" onclick="javascript:_showReport('<?= base_url("attachments/attachment/public_upload/{$id}/{$categories}") ?>');" class="icon-btn">
    <i class="fa fa-file"></i>
    <div>
        المرفقات
    </div>
    <span class="badge bg-danger">
<?= $rows[0]['NUM_ROWS'] ?>
</span>
</a>

