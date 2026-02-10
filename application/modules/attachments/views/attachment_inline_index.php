<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 16/12/14
 * Time: 03:33 م
 */

$count = 0;
//echo $can_upload_priv."hhhh";
?>


<a href="javascript:;"
   onclick="javascript:_showReport('<?= base_url("attachments/attachment/public_upload/{$id}/{$categories}/{$can_upload_priv}") ?>');"
   class="">
    <i class="icon icon-file"></i>

</a>

