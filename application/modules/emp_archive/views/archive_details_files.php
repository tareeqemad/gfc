<?php
$gfc_domain = gh_gfc_domain();
$count_folder = 1;
?>
<div class="example">

    <div class="row">
        <div class="form-group  col-md-2">
            <label> رقم الموظف </label>
            <input type="text" readonly class="form-control"
                   value="<?= $master_tb_data[0]['EMP_NO'] ?>">
        </div>
        <div class="form-group  col-md-3">
            <label> اسم الموظف </label>
            <input type="text" readonly class="form-control"
                   value="<?= $master_tb_data[0]['EMP_NO_NAME'] ?>">
        </div>
        <div class="form-group  col-md-2">
            <label>الباركود </label>
            <input type="text" readonly class="form-control"
                   value="<?= $master_tb_data[0]['BARCODE'] ?>">
        </div>
        <div class="form-group  col-md-4">
            <label>نوع الملف </label>
            <input type="text" readonly class="form-control"
                   value="<?= $master_tb_data[0]['TYPE_NAME'] ?>">
        </div>
    </div>
    <div class="row">
        <div class="form-group  col-md-4">
            <label>اسم الملف </label>
            <input type="text" readonly class="form-control"
                   value="<?= $master_tb_data[0]['FOLDER_NAME'] ?>">
        </div>
        <div class="form-group  col-md-2">
            <label> شهر القرار </label>
            <input type="text" readonly class="form-control"
                   value="<?= $master_tb_data[0]['MONTH'] ?>">
        </div>
        <div class="form-group  col-md-4 py-4">
            <button type="button" class="btn btn-primary-gradient" onclick="do_dl();">
                <i class="fa fa-download"></i>
                تحميل المرفقات
            </button>
        </div>
    </div>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered" id="attachment_file_tb">
                <thead class="table-light">
                <tr>
                    <th>م</th>
                    <th>عرض المرفق</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($array_folder as $r) : ?>
                    <tr>
                        <td><?= $count_folder ?></td>
                        <td>
                            <a target="_blank"
                               href="<?= $gfc_domain . '/Trading/DownloadsFileHRNew/' . $r['SER'] ?>"><i
                                        class="glyphicon glyphicon-paperclip"></i> عرض </a>
                            <input type="hidden" name="txt_h_url" id="txt_h_url_<?= $count_folder ?>"
                                   value="<?= $gfc_domain . '/Trading/DownloadsFileHRNew/' . $r['SER'] ?>">
                        </td>
                        <?php
                        $count_folder++;
                        ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    initFunctions();
</script>
