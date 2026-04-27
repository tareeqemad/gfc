<?php
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';
$history_url = base_url("$MODULE_NAME/$TB_NAME/batch_history");
$export_url  = base_url("$MODULE_NAME/$TB_NAME/export_bank_file");

$bi = $batch_info;
$st = (int)($bi['STATUS'] ?? 0);
$batch_id = $bi['BATCH_ID'] ?? 0;
$batch_no = $bi['BATCH_NO'] ?? '';
$rows = isset($detail_rows) && is_array($detail_rows) ? $detail_rows : [];

$status_labels = [];
foreach ($batch_status_cons as $s) { $status_labels[(int)$s['CON_NO']] = $s['CON_NAME']; }

$can_pay     = HaveAccess(base_url("$MODULE_NAME/$TB_NAME/batch_pay_action"));
$can_cancel  = HaveAccess(base_url("$MODULE_NAME/$TB_NAME/batch_cancel_action"));
$can_reverse = HaveAccess(base_url("$MODULE_NAME/$TB_NAME/batch_reverse_pay_action"));

echo AntiForgeryToken();
?>

<style>
.pr-row{display:flex;gap:.5rem;margin-bottom:.75rem;flex-wrap:wrap}
.pr-card{flex:1;min-width:130px;text-align:center;padding:.6rem .5rem;border-radius:10px;border:1px solid #e2e8f0;background:#fff}
.pr-card .c-label{font-size:.65rem;color:#64748b;margin-bottom:.15rem}
.pr-card .c-val{font-size:1rem;font-weight:800;direction:ltr;display:inline-block}
.pr-card .c-cnt{font-size:.7rem;font-weight:600;color:#94a3b8;margin-top:.1rem}
.pr-card.c-total{background:#1e293b;border-color:#1e293b}.pr-card.c-total .c-label,.pr-card.c-total .c-val{color:#fff}
.bh-status{font-weight:600;font-size:.78rem;padding:.3em .7em;border-radius:6px}
.bh-status.s0{background:#dbeafe;color:#1e40af}
.bh-status.s2{background:#d1fae5;color:#065f46}
.bh-status.s9{background:#f1f5f9;color:#94a3b8}
.bank-section{margin-bottom:1rem;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden}
.bank-header{background:#f8fafc;padding:.6rem 1rem;display:flex;justify-content:space-between;align-items:center;font-weight:600;font-size:.85rem;cursor:pointer}
.bank-header:hover{background:#f1f5f9}
.bank-body table{margin:0;font-size:.8rem}
.bank-body table th{background:#f8fafc;font-size:.72rem;color:#64748b}
.amt{font-weight:700;color:#1e293b}
</style>

<div class="page-header">
    <div><h1 class="page-title"><?= $title ?></h1></div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url("$MODULE_NAME/$TB_NAME") ?>">صرف الرواتب</a></li>
            <li class="breadcrumb-item"><a href="<?= $history_url ?>">سجل الدفعات</a></li>
            <li class="breadcrumb-item active"><?= $batch_no ?></li>
        </ol>
    </div>
</div>

<div class="row"><div class="col-lg-12"><div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fa fa-bank me-2"></i><?= $title ?></h3>
        <div class="ms-auto d-flex gap-1 flex-wrap align-items-center">
            <?php if ($st == 0 && $can_pay): ?>
                <button class="btn btn-success btn-sm" onclick="payBatch()"><i class="fa fa-money me-1"></i> تنفيذ الصرف</button>
                <button class="btn btn-outline-danger btn-sm" onclick="cancelBatch()"><i class="fa fa-undo me-1"></i> فك الاحتساب</button>
                <div class="vr mx-1 d-none d-md-block"></div>
            <?php elseif ($st == 2 && $can_reverse): ?>
                <button class="btn btn-outline-warning btn-sm" onclick="reverseBatch()"><i class="fa fa-reply-all me-1"></i> عكس الصرف</button>
                <div class="vr mx-1 d-none d-md-block"></div>
            <?php endif; ?>
            <a class="btn btn-outline-success btn-sm" href="<?= $export_url ?>?batch_id=<?= $batch_id ?>"><i class="fa fa-file-excel-o"></i> تصدير كل البنوك</a>
            <button class="btn btn-primary btn-sm" onclick="printReport()"><i class="fa fa-print"></i> طباعة</button>
            <a class="btn btn-light btn-sm" href="<?= $history_url ?>"><i class="fa fa-arrow-right"></i> رجوع</a>
        </div>
    </div>
    <div class="card-body">

        <!-- معلومات الدفعة -->
        <div class="pr-row">
            <div class="pr-card c-total">
                <div class="c-label"><i class="fa fa-hashtag"></i> رقم الدفعة</div>
                <div class="c-val"><?= $batch_no ?></div>
            </div>
            <div class="pr-card">
                <div class="c-label"><i class="fa fa-calendar"></i> تاريخ الإنشاء</div>
                <div class="c-val" style="font-size:.85rem"><?= $bi['ENTRY_DATE'] ?? '' ?></div>
                <div class="c-cnt"><?= $bi['ENTRY_USER_NAME'] ?? '' ?></div>
            </div>
            <div class="pr-card">
                <div class="c-label"><i class="fa fa-users"></i> الموظفين</div>
                <div class="c-val"><?= $bi['EMP_COUNT'] ?? 0 ?></div>
                <div class="c-cnt"><?= $bi['MOVEMENT_COUNT'] ?? 0 ?> حركة</div>
            </div>
            <div class="pr-card" style="background:#f0fdf4;border-color:#86efac">
                <div class="c-label"><i class="fa fa-money"></i> إجمالي الصرف</div>
                <div class="c-val" style="color:#059669"><?= n_format((float)($bi['TOTAL_AMOUNT'] ?? 0)) ?></div>
            </div>
            <div class="pr-card">
                <div class="c-label"><i class="fa fa-info-circle"></i> الحالة</div>
                <div class="c-val"><span class="bh-status s<?= $st ?>"><?= $status_labels[$st] ?? '' ?></span></div>
                <?php if ($st == 2 && !empty($bi['PAY_DATE'])): ?>
                    <div class="c-cnt"><?= $bi['PAY_DATE'] ?> — <?= $bi['PAY_USER_NAME'] ?? '' ?></div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (count($rows) > 0):
            // تجميع حسب البنك + حساب الملخصات
            $banks = []; $total_amt = 0;
            $sum_t1 = 0; $sum_t2 = 0; $sum_t3 = 0; $sum_t4 = 0;
            $all_months = []; $all_req_nos = []; $all_types = [];
            $by_branch = [];

            foreach ($rows as $r) {
                $bk = $r['MASTER_BANK_NAME'] ?? $r['BANK_NAME'] ?? 'غير محدد';
                $br = $r['BANK_NAME'] ?? 'غير محدد';
                $mbno = $r['MASTER_BANK_NO'] ?? 0;
                if (!isset($banks[$bk])) $banks[$bk] = ['branches' => [], 'total' => 0, 'count' => 0, 'master_bank_no' => $mbno];
                if (!isset($banks[$bk]['branches'][$br])) $banks[$bk]['branches'][$br] = ['rows' => [], 'total' => 0];
                $amt = (float)($r['TOTAL_AMOUNT'] ?? 0);
                $banks[$bk]['branches'][$br]['rows'][] = $r;
                $banks[$bk]['branches'][$br]['total'] += $amt;
                $banks[$bk]['total'] += $amt;
                $banks[$bk]['count']++;
                $total_amt += $amt;

                $sum_t1 += (float)($r['AMT_TYPE1'] ?? 0);
                $sum_t2 += (float)($r['AMT_TYPE2'] ?? 0);
                $sum_t3 += (float)($r['AMT_TYPE3'] ?? 0);
                $sum_t4 += (float)($r['AMT_TYPE4'] ?? 0);

                // طلبات (من REQ_NOS بالصف)
                if (!empty($r['REQ_NOS'])) {
                    foreach (explode(', ', $r['REQ_NOS']) as $rn) { $all_req_nos[trim($rn)] = 1; }
                }
                // مقرات
                $emp_br = $r['BRANCH_NAME'] ?? 'غير محدد';
                if (!isset($by_branch[$emp_br])) $by_branch[$emp_br] = ['count' => 0, 'total' => 0];
                $by_branch[$emp_br]['count']++;
                $by_branch[$emp_br]['total'] += $amt;
            }
            uasort($banks, function($a, $b) { return $b['total'] <=> $a['total']; });
            uasort($by_branch, function($a, $b) { return $b['total'] <=> $a['total']; });

            // الشهور والأنواع من الطلبات
            $all_months = []; $all_types = [];
            $reqs_list = isset($batch_reqs) ? $batch_reqs : [];
            foreach ($reqs_list as $rq) {
                $m = $rq['THE_MONTH'] ?? '';
                if ($m) $all_months[$m] = 1;
                $rt = $rq['REQ_TYPE_NAME'] ?? '';
                if ($rt) $all_types[$rt] = 1;
            }
            ksort($all_months);
        ?>

        <!-- ملخص الطلبات والشهور -->
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:.75rem 1rem;margin-bottom:.75rem;font-size:.82rem">
            <div class="row">
                <div class="col-md-4">
                    <div class="text-muted mb-1"><i class="fa fa-calendar me-1"></i> <strong>الشهور:</strong></div>
                    <?php foreach ($all_months as $m => $v): $mf = strlen($m)==6 ? substr($m,4,2).'/'.substr($m,0,4) : $m; ?>
                        <span class="badge bg-primary me-1"><?= $mf ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-4">
                    <div class="text-muted mb-1"><i class="fa fa-file-text-o me-1"></i> <strong>الطلبات:</strong> (<?= count($all_req_nos) ?>)</div>
                    <?php foreach ($all_req_nos as $rn => $v): ?>
                        <span class="badge bg-light text-dark me-1" style="border:1px solid #e2e8f0"><?= $rn ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-4">
                    <div class="text-muted mb-1"><i class="fa fa-tags me-1"></i> <strong>الأنواع:</strong></div>
                    <?php foreach ($all_types as $rt => $v): ?>
                        <span class="badge bg-info text-white me-1"><?= $rt ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- تفصيل حسب المقر -->
        <div class="row mb-3">
            <div class="col-md-6">
                <table class="table table-bordered table-sm" style="font-size:.8rem">
                    <thead class="table-light"><tr><th><i class="fa fa-building me-1"></i> المقر</th><th class="text-center">موظفين</th><th class="text-end">المبلغ</th></tr></thead>
                    <tbody>
                    <?php foreach ($by_branch as $brName => $brInfo): ?>
                    <tr><td><?= $brName ?></td><td class="text-center"><?= $brInfo['count'] ?></td><td class="text-end amt"><?= n_format($brInfo['total']) ?></td></tr>
                    <?php endforeach; ?>
                    <tr style="background:#1e293b;color:#fff;font-weight:800"><td>الإجمالي</td><td class="text-center"><?= count($rows) ?></td><td class="text-end"><?= n_format($total_amt) ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ملخص البنوك (جدول سريع) -->
        <h6 class="fw-bold mb-2" style="font-size:.85rem"><i class="fa fa-bank me-1"></i> ملخص حسب البنك</h6>
        <div class="table-responsive mb-3">
        <table class="table table-bordered table-sm" style="font-size:.82rem">
            <thead class="table-light">
            <tr><th style="width:30px">#</th><th>البنك</th><th class="text-center">الفروع</th><th class="text-center">الموظفين</th>
                <th class="text-end">راتب كامل</th><th class="text-end">راتب جزئي</th><th class="text-end">مستحقات</th><th class="text-end">إضافات</th>
                <th class="text-end">الإجمالي</th><th style="width:40px"></th></tr>
            </thead>
            <tbody>
            <?php $bIdx = 0; foreach ($banks as $bankName => $bankData): $bIdx++;
                // حساب مبالغ الأنواع لهذا البنك
                $bt1=0;$bt2=0;$bt3=0;$bt4=0;
                foreach ($bankData['branches'] as $brD) {
                    foreach ($brD['rows'] as $rr) {
                        $bt1+=(float)($rr['AMT_TYPE1']??0); $bt2+=(float)($rr['AMT_TYPE2']??0);
                        $bt3+=(float)($rr['AMT_TYPE3']??0); $bt4+=(float)($rr['AMT_TYPE4']??0);
                    }
                }
            ?>
            <tr>
                <td class="text-muted"><?= $bIdx ?></td>
                <td class="fw-bold"><a href="#bank_<?= $bIdx ?>" style="color:inherit"><?= $bankName ?></a></td>
                <td class="text-center"><?= count($bankData['branches']) ?></td>
                <td class="text-center"><?= $bankData['count'] ?></td>
                <td class="text-end"><?= $bt1 > 0 ? n_format($bt1) : '-' ?></td>
                <td class="text-end"><?= $bt2 > 0 ? n_format($bt2) : '-' ?></td>
                <td class="text-end"><?= $bt3 > 0 ? n_format($bt3) : '-' ?></td>
                <td class="text-end"><?= $bt4 > 0 ? n_format($bt4) : '-' ?></td>
                <td class="text-end amt"><?= n_format($bankData['total']) ?></td>
                <td><a href="<?= $export_url ?>?batch_id=<?= $batch_id ?>&master_bank_no=<?= $bankData['master_bank_no'] ?>" class="btn btn-sm btn-outline-success p-0 px-1" title="تصدير"><i class="fa fa-file-excel-o"></i></a></td>
            </tr>
            <?php endforeach; ?>
            <tr style="background:#1e293b;color:#fff;font-weight:800">
                <td colspan="3">الإجمالي</td>
                <td class="text-center"><?= count($rows) ?></td>
                <td class="text-end"><?= $sum_t1 > 0 ? n_format($sum_t1) : '-' ?></td>
                <td class="text-end"><?= $sum_t2 > 0 ? n_format($sum_t2) : '-' ?></td>
                <td class="text-end"><?= $sum_t3 > 0 ? n_format($sum_t3) : '-' ?></td>
                <td class="text-end"><?= $sum_t4 > 0 ? n_format($sum_t4) : '-' ?></td>
                <td class="text-end"><?= n_format($total_amt) ?></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        </div>

        <!-- تفصيل الموظفين حسب البنك -->
        <h6 class="fw-bold mb-2" style="font-size:.85rem"><i class="fa fa-users me-1"></i> تفصيل الموظفين</h6>
        <?php $bIdx = 0; foreach ($banks as $bankName => $bankData): $bIdx++; ?>
        <div class="bank-section" id="bank_<?= $bIdx ?>">
            <div class="bank-header">
                <div onclick="$(this).parent().next().toggle()" style="flex:1;cursor:pointer">
                    <i class="fa fa-bank me-1"></i>
                    <strong><?= $bankName ?></strong>
                    <span class="text-muted ms-2" style="font-size:.75rem"><?= $bankData['count'] ?> موظف — <?= count($bankData['branches']) ?> فرع</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="amt"><?= n_format($bankData['total']) ?></span>
                    <a href="<?= $export_url ?>?batch_id=<?= $batch_id ?>&master_bank_no=<?= $bankData['master_bank_no'] ?>" class="btn btn-sm btn-outline-success" title="تصدير <?= $bankName ?>"><i class="fa fa-file-excel-o"></i></a>
                </div>
            </div>
            <div class="bank-body">
                <?php foreach ($bankData['branches'] as $brName => $brData): ?>
                <?php if (count($bankData['branches']) > 1): ?>
                <div style="background:#f1f5f9;padding:.3rem .8rem;font-size:.75rem;font-weight:600;color:#475569;border-bottom:1px solid #e2e8f0">
                    <?= $brName ?> <span class="text-muted">(<?= count($brData['rows']) ?> موظف — <?= n_format($brData['total']) ?>)</span>
                </div>
                <?php endif; ?>
                <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead><tr><th style="width:30px">#</th><th>رقم الموظف</th><th>الاسم</th><th>المقر</th><th>البنك/الفرع</th><th>الطلبات</th><th class="text-end">المبلغ</th></tr></thead>
                    <tbody>
                    <?php $n = 0; foreach ($brData['rows'] as $r): $n++; ?>
                    <tr>
                        <td class="text-muted"><?= $n ?></td>
                        <td class="fw-bold"><?= $r['EMP_NO'] ?></td>
                        <td><?= $r['EMP_NAME'] ?? '' ?></td>
                        <td style="font-size:.75rem"><?= $r['BRANCH_NAME'] ?? '' ?></td>
                        <td style="font-size:.75rem"><?= $r['BANK_NAME'] ?? '' ?></td>
                        <td style="font-size:.72rem"><?= $r['REQ_NOS'] ?? '' ?></td>
                        <td class="text-end amt"><?= n_format((float)($r['TOTAL_AMOUNT'] ?? 0)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($brData['rows']) > 1): ?>
                    <tr style="background:#f0fdf4;font-weight:700"><td colspan="6" class="text-end">إجمالي <?= count($bankData['branches']) > 1 ? $brName : $bankName ?></td><td class="text-end"><?= n_format($brData['total']) ?></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php else: ?>
        <div class="alert alert-light text-center text-muted py-4">
            <i class="fa fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i> لا توجد تفاصيل
        </div>
        <?php endif; ?>

    </div>
</div></div></div>

<?php
$pay_url_js     = base_url("$MODULE_NAME/$TB_NAME/batch_pay_action");
$cancel_url_js  = base_url("$MODULE_NAME/$TB_NAME/batch_cancel_action");
$reverse_url_js = base_url("$MODULE_NAME/$TB_NAME/batch_reverse_pay_action");

$scripts = <<<SCRIPT
<script type="text/javascript">
function nf(n){ return parseFloat(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }

function payBatch(){
    if(!confirm('تنفيذ صرف الدفعة {$batch_no}؟\\n\\nسيتم ترحيل المبالغ لجدول المستحقات.\\nهل أنت متأكد؟')) return;
    get_data('{$pay_url_js}', {batch_id: {$batch_id}}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg || 'تم تنفيذ الصرف'); location.reload(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

function cancelBatch(){
    if(!confirm('فك احتساب الدفعة {$batch_no}؟\\n\\nسيتم إرجاع الموظفين لحالة معتمد.')) return;
    get_data('{$cancel_url_js}', {batch_id: {$batch_id}}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', 'تم فك الاحتساب'); window.location = '{$history_url}'; }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

function reverseBatch(){
    if(!confirm('عكس صرف الدفعة {$batch_no}؟\\n\\nسيتم حذف سجلات المستحقات وإرجاع الموظفين.\\nهل أنت متأكد؟')) return;
    if(!confirm('تأكيد نهائي — متابعة؟')) return;
    get_data('{$reverse_url_js}', {batch_id: {$batch_id}}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg); location.reload(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

function printReport(){
    window.open('{$history_url}', '_self');
}
</script>
SCRIPT;
sec_scripts($scripts);
?>
