<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 10/01/15
 * Time: 11:19 ص
 */

$count = $offset;

?>
<style>
    .note{max-width:550px; max-height: 35px; overflow: hidden; text-align: right}
    .note2{max-width:550px;text-align: right}
</style>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>المخزن</th>
            <th>الحركة</th>
            <th>البيان</th>
            <th>تاريخ الحركة</th>
            <th>المصدر</th>
            <th>رقم المصدر</th>
            <th>فتح المصدر</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="8" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
            <script> show_notes(); </script>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr>
                <td><?=$count?></td>
                <td><?=$row['STORE_ID_NAME']?></td>
                <td><?=$row['ACTION_NAME']?></td>
                <td><div title="انقر مزدوجا لاظهار واخفاء البيان" class="note"><?=$row['NOTE']?></div></td>
                <td><?=$row['ADOPT_DATE']?></td>
                <td><?=$row['SOURCE_NAME']?></td>
                <td><?=$row['PK']?></td>
                <td><a target="_blank" href="<?=base_url("stores/".str_replace("{id}","{$row['PK']}","{$row['SOURCE_TB']}") )?>" ><i class='glyphicon glyphicon-share'></i></a></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
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
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }

    function show_notes(){
        $('div.note').dblclick(function(e){
            $(this).toggleClass('note note2');
        });
    }
    show_notes();

</script>
