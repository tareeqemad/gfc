<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/10/18
 * Time: 08:56 ص
 */
$count = $offset;
?>
<div >


</div>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th><input type="checkbox" name="checkall"  onclick="checks(this);" id="checkall?>"  value="1" ></th>
            <th>#</th>
            <th> رقم المورد </th>
            <th>اسم المورد</th>
            <th>رقم الجوال </th>
            <th>البريد الالكتروني</th>

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr >
                <td><input type="checkbox"  class="checkboxes" name="mobile_no[]" id="mobile_no_<?=$count?>" value="<?=$row['MOBIL'] ?>" /></td>
                <td><?=$count?></td>
                <td><?=$row['CUSTOMER_ID']?></td>
                <td><?=$row['CUSTOMER_NAME']?></td>
                <td><?=$row['MOBIL']?></td>
                <td><?=$row['EMAIL']?></td>

       <?PHP
                $count++;
                ?>
            </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot><input type="hidden" name="counterx" id="counterx" value="<?=($count)?>"></tfoot>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }

</script>



