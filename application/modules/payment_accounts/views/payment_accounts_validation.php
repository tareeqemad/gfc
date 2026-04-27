<?php
$MODULE_NAME = 'payment_accounts';
$TB_NAME     = 'payment_accounts';

$back_url = base_url("$MODULE_NAME/$TB_NAME");
$emp_url  = base_url("$MODULE_NAME/$TB_NAME/emp");
$auto_fix_url = base_url("$MODULE_NAME/$TB_NAME/auto_fix_splits");

$issues_arr = is_array($issues ?? null) ? $issues : [];

// تجميع الإحصائيات
$stats = ['NO_REMAINDER' => 0, 'PCT_OVER' => 0, 'NO_DEFAULT' => 0, 'NO_IBAN' => 0];
foreach ($issues_arr as $iss) {
    foreach ($iss['ERRORS'] as $err) {
        $code = $err['code'] ?? '';
        if (isset($stats[$code])) $stats[$code]++;
    }
}
?>

<style>
.val-stats{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem}
.val-stat{flex:1;min-width:150px;padding:.7rem .85rem;border-radius:10px;border:1px solid #e2e8f0;background:#fff}
.val-stat .vs-label{font-size:.7rem;color:#64748b;margin-bottom:.15rem}
.val-stat .vs-val{font-size:1.4rem;font-weight:800;color:#1e293b}
.val-stat.danger {background:#fee2e2;border-color:#fca5a5}.val-stat.danger  .vs-val{color:#991b1b}
.val-stat.warning{background:#fef3c7;border-color:#fcd34d}.val-stat.warning .vs-val{color:#92400e}
.val-stat.info   {background:#e0e7ff;border-color:#a5b4fc}.val-stat.info    .vs-val{color:#3730a3}

.val-tbl td, .val-tbl th { vertical-align: middle; font-size:.82rem }
.err-pill{display:inline-block;padding:.15em .55em;border-radius:5px;font-size:.7rem;font-weight:700;margin-inline-end:.25rem;margin-bottom:.15rem}
.err-pill.NO_REMAINDER{background:#fee2e2;color:#991b1b}
.err-pill.PCT_OVER    {background:#fed7aa;color:#9a3412}
.err-pill.NO_DEFAULT  {background:#fef3c7;color:#92400e}
.err-pill.NO_IBAN     {background:#e0e7ff;color:#3730a3}
.id-mono{font-family:monospace;direction:ltr;font-weight:600}
</style>

<div class="page-header">
    <div><h1 class="page-title"><i class="fa fa-stethoscope me-2"></i> <?= htmlspecialchars($title) ?></h1></div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $back_url ?>">إدارة حسابات الصرف</a></li>
            <li class="breadcrumb-item active">تحقّق إعداد التوزيع</li>
        </ol>
    </div>
</div>

<div class="row"><div class="col-lg-12"><div class="card">
    <div class="card-header d-flex align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fa fa-exclamation-triangle me-2"></i> الموظفون الذين يحتاجون مراجعة</h3>
        <span class="text-muted" style="font-size:.78rem">(<?= count($issues_arr) ?> موظف)</span>
        <div class="ms-auto">
            <a href="<?= $back_url ?>" class="btn btn-light btn-sm"><i class="fa fa-arrow-right me-1"></i> رجوع</a>
        </div>
    </div>
    <div class="card-body">

    <!-- إحصائيات سريعة -->
    <div class="val-stats">
        <div class="val-stat danger">
            <div class="vs-label"><i class="fa fa-exclamation-circle"></i> لا يوجد حساب "الباقي"</div>
            <div class="vs-val"><?= $stats['NO_REMAINDER'] ?></div>
        </div>
        <div class="val-stat warning">
            <div class="vs-label"><i class="fa fa-percent"></i> مجموع النسب > 100%</div>
            <div class="vs-val"><?= $stats['PCT_OVER'] ?></div>
        </div>
        <div class="val-stat warning">
            <div class="vs-label"><i class="fa fa-star-o"></i> لا يوجد افتراضي</div>
            <div class="vs-val"><?= $stats['NO_DEFAULT'] ?></div>
        </div>
        <div class="val-stat info">
            <div class="vs-label"><i class="fa fa-bank"></i> حسابات بنكية بدون IBAN</div>
            <div class="vs-val"><?= $stats['NO_IBAN'] ?></div>
        </div>
    </div>

    <?php if (count($issues_arr) === 0): ?>
        <div class="alert alert-success py-3 text-center">
            <i class="fa fa-check-circle fa-2x d-block mb-2"></i>
            ممتاز — جميع الموظفين عندهم إعداد توزيع سليم
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered val-tbl">
                <thead class="table-light">
                <tr>
                    <th style="width:40px">#</th>
                    <th>الموظف</th>
                    <th style="width:130px">رقم الهوية</th>
                    <th>المقر</th>
                    <th class="text-center" style="width:60px">حسابات</th>
                    <th>المشاكل</th>
                    <th class="text-center" style="width:160px">إجراءات</th>
                </tr>
                </thead>
                <tbody>
                <?php $cnt = 1; foreach ($issues_arr as $iss): ?>
                <tr data-emp="<?= (int)$iss['EMP_NO'] ?>">
                    <td class="text-center text-muted"><?= $cnt++ ?></td>
                    <td>
                        <span class="fw-bold"><?= (int)$iss['EMP_NO'] ?></span>
                        <span class="text-muted d-block" style="font-size:.78rem"><?= htmlspecialchars($iss['EMP_NAME']) ?></span>
                    </td>
                    <td><span class="id-mono"><?= htmlspecialchars($iss['ID_NO'] ?: '—') ?></span></td>
                    <td><?= htmlspecialchars($iss['BRANCH'] ?: '—') ?></td>
                    <td class="text-center"><?= (int)$iss['ACC_CNT'] ?></td>
                    <td>
                        <?php foreach ($iss['ERRORS'] as $err): ?>
                            <span class="err-pill <?= htmlspecialchars($err['code']) ?>"><?= htmlspecialchars($err['label']) ?></span>
                        <?php endforeach; ?>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-primary" data-action="auto-fix" data-emp="<?= (int)$iss['EMP_NO'] ?>" title="إصلاح تلقائي">
                            <i class="fa fa-magic"></i> إصلاح
                        </button>
                        <a href="<?= $emp_url ?>/<?= (int)$iss['EMP_NO'] ?>" class="btn btn-sm btn-outline-primary" title="فتح الموظف">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    </div>
</div></div></div>

<?php echo AntiForgeryToken(); ?>

<script>
(function waitJQ(){
    if(typeof jQuery === 'undefined'){ setTimeout(waitJQ, 50); return; }
    jQuery(function($){

    var AUTO_FIX_URL = "<?= $auto_fix_url ?>";
    var _busy = {};

    $(document).on('click', '[data-action="auto-fix"]', function(){
        var emp = $(this).data('emp');
        if(!emp) return;
        if(_busy[emp]) return; _busy[emp] = true;
        var $row = $(this).closest('tr');
        if(!confirm('سيتم إصلاح إعداد التوزيع لهذا الموظف تلقائياً. متابعة؟')){ _busy[emp] = false; return; }
        get_data(AUTO_FIX_URL, {emp_no: emp}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _busy[emp] = false;
            if(!j.ok){ danger_msg('خطأ', j.msg); return; }
            success_msg('تم', j.msg);
            if((j.fixed||0) > 0){
                $row.fadeOut(250, function(){ $(this).remove(); });
            }
        }, 'json');
    });

    });
})();
</script>
