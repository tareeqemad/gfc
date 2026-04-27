<?php
/**
 * Salary Dues - Rows Only (for infinite scroll append)
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME     = 'dues';
$count       = $offset;
$delete_url  = base_url("$MODULE_NAME/$TB_NAME/delete");

if (!is_array($page_rows) || count($page_rows) === 0) return;

foreach ($page_rows as $row):
    $is_active = (!isset($row['STATUS']) || $row['STATUS'] == 1);
    $line_type = $row['LINE_TYPE_NAME'] ?? '';
    $lt_badge = ($line_type == 'ADD')
        ? '<span class="b-add"><i class="fa fa-plus"></i> إضافة</span>'
        : '<span class="b-ded"><i class="fa fa-minus"></i> خصم</span>';
    $st_badge = $is_active
        ? '<span class="b-ok"><i class="fa fa-check"></i></span>'
        : '<span class="b-no"><i class="fa fa-ban"></i></span>';
    $the_month_display = '';
    if (!empty($row['THE_MONTH']) && strlen($row['THE_MONTH']) == 6) {
        $mn = (int)substr($row['THE_MONTH'], 4, 2);
        $the_month_display = months($mn) . ' ' . substr($row['THE_MONTH'], 0, 4);
    } else {
        $the_month_display = $row['THE_MONTH'] ?? '';
    }
    $the_date_display = '—';
    if (!empty($row['THE_DATE'])) {
        $ts = @strtotime(str_replace('/', '-', $row['THE_DATE']));
        $the_date_display = $ts ? date('d/m/Y', $ts) : $row['THE_DATE'];
    }
    $pay = (isset($row['PAY']) && $row['PAY'] !== '') ? n_format((float)$row['PAY']) : '';
    $pay_class = ($line_type == 'ADD') ? 'add' : 'ded';
    $row_class = $is_active ? '' : 'cancelled';
    $emp_no   = $row['EMP_NO'] ?? '';
    $emp_name = $row['EMP_NO_NAME'] ?? '';
    $branch   = $row['BRANCH_NAME'] ?? '';
?>
<tr class="<?= $row_class ?>" ondblclick="javascript:get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/{$row['SERIAL']}") ?>');">
    <td><?= $count ?></td>
    <td><a class="emp-link" data-emp="<?= $emp_no ?>" onclick="showEmpModal(<?= $emp_no ?>, '<?= addslashes($emp_name) ?>')"><?= $emp_no ?> - <?= $emp_name ?></a></td>
    <td><?= $branch ?></td>
    <td><?= $the_month_display ?></td>
    <td><?= $the_date_display ?></td>
    <td><span class="b-type"><?= $row['PAY_TYPE_NAME'] ?? '' ?></span></td>
    <td><?= $lt_badge ?></td>
    <td><span class="amt <?= $pay_class ?>"><?= $pay ?></span></td>
    <td><?= $st_badge ?></td>
    <?php if (HaveAccess($delete_url)): ?>
    <td>
        <?php if ($is_active): ?>
        <a href="javascript:;" title="إلغاء" onclick="javascript:delete_prototype(this,<?= $row['SERIAL'] ?>);"><i class="glyphicon glyphicon-trash" style="color: #a43540"></i></a>
        <?php endif; ?>
    </td>
    <?php endif; ?>
</tr>
<?php $count++; endforeach; ?>
