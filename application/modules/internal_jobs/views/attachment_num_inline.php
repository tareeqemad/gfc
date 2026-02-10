<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$count = 0;
?>
<a href="javascript:;" onclick="javascript:_showReport('<?= base_url("attachments/attachment/public_upload/{$id}/{$categories}/1") ?>');">
    <span class="">
    <i class="fa fa-paperclip"></i>
    <?= $cnt_row ?>
    </span>
</a>
