<?php
$count = $offset;
$gfc_domain = gh_gfc_domain();
$MODULE_NAME = 'hr';
$TB_NAME = 'Archive_scan';
$edit_folder_name_url = base_url("$MODULE_NAME/$TB_NAME/edit_folder_name");
$change_barcode_type_url = base_url("$MODULE_NAME/$TB_NAME/change_barcode_type");
$delete_row_url = base_url("$MODULE_NAME/$TB_NAME/delete_row");

?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>رقم الباركود</th>
            <th>نوع الملف</th>
            <th>اسم الملف</th>
            <th>شهر القرار</th>
            <th>عرض المرفق</th>
            <th>اجراءات</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="6" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) : ?>
            <?php if (HaveAccess($edit_folder_name_url)) { ?>
                <tr ondblclick="javascript:update_folder_name('<?= $row['EMP_NO'] ?>','<?= $row['BARCODE'] ?>')">
            <?php } else { ?>
                <tr>
            <?php } ?>
            <td><?= $count ?></td>
            <td><?= $row['EMP_NO'] ?></td>
            <td><?= $row['EMP_NO_NAME'] ?></td>
            <td><?= $row['BARCODE'] ?></td>
            <td><?= $row['TYPE_NAME'] ?></td>
            <td><?= $row['FOLDER_NAME'] ?></td>
            <td><?= $row['MONTH_DES_'] ?></td>
            <td>
                <a href="javascript:;" class="btn btn-azure"
                   onclick="javascript:show_attachment_files('<?= $row['BARCODE'] ?>')">
                    <i class="glyphicon glyphicon-paperclip"></i>
                    <span><?= $row['CNT_FILE'] ?></span>
                    عرض
                </a>
            </td>
            <td>
                <?php if (HaveAccess($change_barcode_type_url)) { ?>
                    <a href="javascript:;" class="modal-effect"
                       onclick="javascript:edit_detail_row('<?= $row['BARCODE'] ?>')">
                        <i class="fa fa-edit" style="color: #6bd7aa;"></i> </a>
                <?php } ?>

                <?php if (HaveAccess($delete_row_url)) { ?>
                    <a href="javascript:;" onclick="javascript:delete_row(this,'<?= $row['BARCODE'] ?>')")>
                        <i
                            class="fa fa-trash" style="color: red;"></i> </a>
                <?php } ?>

            </td>
            <?php
            $count++;
            ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
