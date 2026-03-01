<?php
/**
 * Payment Request — Page v2 (table rows)
 */
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';
$count       = $offset;

$get_url     = base_url("$MODULE_NAME/$TB_NAME/get");
$approve_url = base_url("$MODULE_NAME/$TB_NAME/approve");
$pay_url     = base_url("$MODULE_NAME/$TB_NAME/do_pay");
$delete_url  = base_url("$MODULE_NAME/$TB_NAME/delete");

$status_map = [
    0 => ['label'=>'ملغي',   'icon'=>'fa-ban',    'bg'=>'#fee2e2','color'=>'#991b1b'],
    1 => ['label'=>'مسودة',  'icon'=>'fa-pencil', 'bg'=>'#fef3c7','color'=>'#92400e'],
    2 => ['label'=>'معتمد',  'icon'=>'fa-check',  'bg'=>'#dbeafe','color'=>'#1e40af'],
    3 => ['label'=>'مدفوع', 'icon'=>'fa-money',  'bg'=>'#d1fae5','color'=>'#065f46'],
];
$wallet_map = [
    1 => ['label'=>'راتب',       'bg'=>'linear-gradient(135deg,#3b82f6,#2563eb)','icon'=>'fa-money'],
    2 => ['label'=>'مستحقات 48', 'bg'=>'linear-gradient(135deg,#8b5cf6,#7c3aed)','icon'=>'fa-archive'],
];
?>

<style>
.prt td,.prt th{vertical-align:middle;font-size:.82rem;white-space:nowrap}
.prt thead th{background:#f8fafc;color:#64748b;font-weight:700;font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;padding:.65rem .75rem}
.prt tbody tr{transition:background .15s;cursor:pointer}
.prt tbody tr:hover{background:#f0f7ff!important}
.prt tbody tr.row-cancel{opacity:.55}
.prt tbody td{padding:.55rem .75rem;border-bottom:1px solid #f1f5f9}
.st-chip{display:inline-flex;align-items:center;gap:.25rem;font-size:.7rem;font-weight:700;padding:.2rem .6rem;border-radius:20px}
.wlt-chip{display:inline-flex;align-items:center;gap:.25rem;font-size:.68rem;font-weight:600;padding:.2rem .55rem;border-radius:6px;color:#fff}
.act-btn{width:28px;height:28px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:.72rem;transition:.15s;cursor:pointer;color:#64748b}
.act-btn:hover{background:#f1f5f9;transform:scale(1.08)}
.act-btn.a-approve{color:#2563eb;border-color:#bfdbfe}.act-btn.a-approve:hover{background:#dbeafe}
.act-btn.a-pay{color:#059669;border-color:#a7f3d0}.act-btn.a-pay:hover{background:#d1fae5}
.act-btn.a-cancel{color:#dc2626;border-color:#fecaca}.act-btn.a-cancel:hover{background:#fee2e2}
.act-btn.a-view{color:#7c3aed;border-color:#ddd6fe}.act-btn.a-view:hover{background:#ede9fe}
.emp-cell .eno{font-weight:700;color:#1e293b;font-size:.82rem}
.emp-cell .enm{color:#94a3b8;font-size:.72rem}
.amt-cell{font-weight:800;color:#1e293b;font-size:.9rem;font-feature-settings:'tnum'}
.no-data{text-align:center;padding:2.5rem;color:#94a3b8;font-size:.9rem}
</style>

<?php if (!is_array($page_rows) || count($page_rows) == 0): ?>
<div class="no-data"><i class="fa fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i>لا توجد طلبات</div>
<?php else: ?>

<?php if ($page > 1): ?>
<div id="page-<?= $page ?>" class="page-sector mb-3" data-page="<?= $page ?>"></div>
<?php endif; ?>

<div id="page_tb_wrap" data-container="container">
<div class="table-responsive">
<table class="table mb-0 prt">
<thead>
<tr>
    <th style="width:36px">#</th>
    <th>رقم الطلب</th>
    <th>الموظف</th>
    <th>الشهر</th>
    <th>نوع الطلب</th>
    <th>المحفظة</th>
    <th class="text-end">المبلغ</th>
    <th class="text-center">الحالة</th>
    <th>التاريخ</th>
    <th class="text-center" style="width:140px">إجراءات</th>
</tr>
</thead>
<tbody>
<?php foreach ($page_rows as $row):
    $st  = (int)($row['STATUS'] ?? 1);
    $stm = $status_map[$st] ?? $status_map[1];
    $wt  = (int)($row['WALLET_TYPE'] ?? 1);
    $wtm = $wallet_map[$wt] ?? $wallet_map[1];
    $rid = $row['REQ_ID'] ?? '';

    $thm = $row['THE_MONTH'] ?? '';
    if (strlen($thm)==6) { $mn=(int)substr($thm,4,2); $thm=months($mn).' '.substr($thm,0,4); }

    $amt = isset($row['REQ_AMOUNT']) ? n_format((float)$row['REQ_AMOUNT']) : '0';
    $ed  = '';
    if (!empty($row['ENTRY_DATE'])) { $ts=@strtotime(str_replace('/','-',$row['ENTRY_DATE'])); $ed=$ts?date('d/m/Y',$ts):$row['ENTRY_DATE']; }
?>
<tr class="<?= $st==0?'row-cancel':'' ?>"
    ondblclick="get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/$rid") ?>')" title="نقرتان للتفاصيل">
    <td class="text-center text-muted"><?= $count ?></td>
    <td><span style="font-weight:600;color:#64748b"><?= $row['REQ_NO'] ?? '' ?></span></td>
    <td class="emp-cell">
        <span class="eno"><?= $row['EMP_NO'] ?? '' ?></span>
        <span class="enm"><?= $row['EMP_NAME'] ?? '' ?></span>
    </td>
    <td><?= $thm ?></td>
    <td><?= $row['REQ_TYPE_NAME'] ?? '' ?></td>
    <td>
        <span class="wlt-chip" style="background:<?= $wtm['bg'] ?>">
            <i class="fa <?= $wtm['icon'] ?>"></i> <?= $row['WALLET_TYPE_NAME'] ?? $wtm['label'] ?>
        </span>
    </td>
    <td class="text-end amt-cell"><?= $amt ?></td>
    <td class="text-center">
        <span class="st-chip" style="background:<?= $stm['bg'] ?>;color:<?= $stm['color'] ?>">
            <i class="fa <?= $stm['icon'] ?>"></i> <?= $row['STATUS_NAME'] ?? $stm['label'] ?>
        </span>
    </td>
    <td style="color:#94a3b8;font-size:.75rem"><?= $ed ?></td>
    <td class="text-center">
        <div class="d-inline-flex gap-1">
        <?php if ($st==1 && HaveAccess($approve_url)): ?>
            <button class="act-btn a-approve" title="اعتماد" onclick="event.stopPropagation();approve_req(<?=$rid?>)"><i class="fa fa-check"></i></button>
        <?php endif; ?>
        <?php if ($st==2 && HaveAccess($pay_url)): ?>
            <button class="act-btn a-pay" title="صرف" onclick="event.stopPropagation();pay_req(<?=$rid?>)"><i class="fa fa-money"></i></button>
        <?php endif; ?>
        <?php if ($st!=0 && HaveAccess($delete_url)): ?>
            <button class="act-btn a-cancel" title="إلغاء" onclick="event.stopPropagation();cancel_req(this,<?=$rid?>)"><i class="fa fa-ban"></i></button>
        <?php endif; ?>
            <a class="act-btn a-view" title="عرض" href="<?= base_url("$MODULE_NAME/$TB_NAME/get/$rid") ?>" onclick="event.stopPropagation()"><i class="fa fa-eye"></i></a>
        </div>
    </td>
</tr>
<?php $count++; endforeach; ?>
</tbody>
</table>
</div>
</div>
<?php endif; ?>

<?= $this->pagination->create_links(); ?>

<script>
if(typeof initFunctions==='function')initFunctions();
if(typeof ajax_pager==='function')ajax_pager();
</script>
