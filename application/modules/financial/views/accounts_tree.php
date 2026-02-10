<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/7/14
 * Time: 8:02 AM
 */

if(isset($user))
    $h_val= $user;
elseif(isset($branch))
    $h_val= $branch;
else
    $h_val= null;
?>


    <input type="hidden" name="user_no" value="<?= $h_val?>">
<?= $tree ?>

<script>
    if (typeof checkBoxChanged == 'function') {
        checkBoxChanged();

    }
</script>