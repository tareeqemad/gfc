<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/06/18
 * Time: 11:56 ص
 */

?>
    <input type="hidden" name="user_no" value="<?= $user?>">

<?= $tree ?>

<script>
    if (typeof checkBoxChanged == 'function') {
        checkBoxChanged();
    }
</script>