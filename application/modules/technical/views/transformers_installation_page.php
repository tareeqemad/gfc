<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 27/06/18
 * Time: 11:21 ص
 */

$count = $offset;
?>


<div class="tbl_container">
    <table class="table" id="projectTbl" action="transformers_installation" data-container="container">
        <thead>
        <tr>

            <th style="width: 20px">#</th>
            <th style="width: 100px"> Project Tec</th>
            <th> Project</th>
            <th style="width: 80px"> Date</th>
            <th style="width: 150px"> Entry User</th>
            <th style="width: 80px">Options</th>
            <th style="width: 50px"></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr ondblclick="javascript:get_to_link('<?= base_url("technical/TransformersInstallation/get/{$row['SER']}/{$action}") ?>');">

                <td><?= $count ?></td>
                <td><?= $row['PROJECT_TEC'] ?></td>
                <td><?= $row['PROJECT_TEC_NAME'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
                <td>
                    <button type="button" onclick="javascript:print_report('<?= $row['SER'] ?>');" class="btn btn-sm btn-primary">Show Report</button>
                </td>
                <td>
                    <?php if ($row['ENTRY_USER'] == get_curr_user()->id ) : ?>
                        <a href="javascript:;"
                           onclick="javascript:deleteTransformData(this,<?= $row['SER'] ?>,'<?= base_url('technical/TransformersInstallation/delete') ?>');"><i
                                    class="icon icon-trash delete-action"></i> </a>
                    <?php endif; ?>
                </td>
                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>

<script type="text/javascript">

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }

</script>