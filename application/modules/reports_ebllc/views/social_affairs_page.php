<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/01/23
 * Time: 11:10 م
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Social_affairs';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="social_affairs_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الملف</th>
            <th>رقم الهوية</th>
            <th>رقم الاشتراك</th>
            <th>اسم المشترك</th>
            <th>نوع الاشتراك</th>
            <th>التصنيف الرئيسي</th>
            <th>حالة الاشتراك</th>
            <th>رقم الجوال</th>
            <th>له شكوى</th>
            <th>الشكاوى</th>
            <th>إضافة شكوى</th>

        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <input type="hidden" id="subscriber_no" name="subscriber_no" value="<?= $row['SUB_NO'] ?>">
                <input type="hidden" id="complaint" name="complaint" value="<?= $row['COMPLAINT'] ?>">
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['FILE_SER_NO'] ?></td>
                <td><?= $row['SSN'] ?></td>
                <td><?= $row['SUB_NO'] ?></td>
                <td><?= $row['SUB_NAME'] ?></td>
                <td><?= $row['TYPE_NAME'] ?></td>
                <td><?= $row['USE_TYPE_NAME'] ?></td>
                <td><?= $row['AGREE_SOCIAL'] ?></td>
                <td><?= $row['TEL'] ?></td>
                <td><?= $row['COMPLAINT_STATUS_NAME'] ?></td>
                <td><?= $row['COMPLAINT'] ?></td>
                <td style="text-align: center">
                <?php if ( $row['AGREE_STATUS'] == 0 ) :  ?>
                        <a onclick="show_detail_row(this);" data-id="<?= $row['SUB_NO'] ?>" title="تعديل" style="color: #2075f8"><i class="fa fa-edit"></i> </a>
                <?php endif; ?>
                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
