<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 03/11/18
 * Time: 12:30 م
 */

$MODULE_NAME='hr_attendance';
$TB_NAME="official_vacations";
$status_url=base_url("$MODULE_NAME/$TB_NAME/status");

$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>تاريخ الاجازة</th>
            <th>يوم الاجازة</th>
            <th>بيان الاجازة</th>
            <th>اسم المدخل </th>
            <th>تاريخ الادخال </th>
            <?php if( HaveAccess($status_url)):  ?>
            <th>حذف</th>
            <?php endif; ?>

            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr >
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr  id="tr_<?=$row['SER']?>">
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['V_DATE']?></td>
                <td><?=$row['DAY_AR']?></td>
                <td><?=$row['V_NOTE']?></td>
                <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_short_user_name($row['ENTRY_USER_NAME'])?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <?php if( HaveAccess($status_url)):  ?>
                    <td><button type="button" onclick="javascript:status_('<?=$row['SER']?>');" class=" btn-danger"> حذف</button></td>
                <?php endif; ?>
                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>

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
</script>
