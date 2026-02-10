<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/7/14
 * Time: 8:02 AM
 */
?>
    <input type="hidden" name="user_no" value="<?= $user?>">

<?= $tree ?>

<script>
    if (typeof checkBoxChanged == 'function') {
        checkBoxChanged();
    }
</script>