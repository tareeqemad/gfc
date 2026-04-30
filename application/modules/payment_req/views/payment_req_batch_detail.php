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

/* Badges في رأس البنك */
.bsum{font-size:.7rem;padding:.15em .55em;border-radius:5px;font-weight:700}
.bsum-benef{background:#f5f3ff;color:#6d28d9;border:1px solid #c4b5fd}
.bsum-multi{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa}
.bsum-warn{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
.bsum-inactive{background:#f1f5f9;color:#475569;border:1px solid #cbd5e1}

/* Badges على صف الموظف */
.b-tag{font-size:.65rem;padding:.1em .4em;border-radius:4px;font-weight:700;margin-inline-start:.3rem;display:inline-block}
.b-tag i{margin-left:2px}
.b-tag.b-benef{background:#f5f3ff;color:#6d28d9}
.b-tag.b-multi{background:#fff7ed;color:#9a3412}
.b-tag.b-inact{background:#fee2e2;color:#991b1b}
.b-tag.b-warn{background:#fef2f2;color:#991b1b;font-weight:600}
.b-tag.b-ok{background:#dcfce7;color:#166534}

/* صف يحتاج انتباه */
.emp-tbl tr.row-attention{background:#fffbeb}
.emp-tbl tr.row-attention:hover{background:#fef3c7}

/* زر expand */
.btn-expand{background:none;border:1px solid #cbd5e1;border-radius:4px;width:24px;height:24px;font-size:.7rem;color:#64748b;padding:0;cursor:pointer}
.btn-expand:hover{background:#e0f2fe;color:#0369a1;border-color:#7dd3fc}
.btn-expand.expanded{background:#1e40af;color:#fff;border-color:#1e40af}

/* صف التفاصيل */
.emp-detail-row td{background:#f8fafc !important;padding:.5rem .85rem !important}
.acc-mini{display:flex;flex-wrap:wrap;gap:.5rem}
.acc-card{flex:1;min-width:280px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:.55rem .75rem;font-size:.78rem}
.acc-card.is-default{border-color:#fbbf24;background:#fffbeb}
.acc-card.is-inactive{opacity:.55;background:#f1f5f9}
.acc-card .ac-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:.3rem}
.acc-card .ac-prov{font-weight:700;color:#1e293b}
.acc-card .ac-prov i{margin-left:3px}
.acc-card .ac-prov.bank i{color:#1e40af}
.acc-card .ac-prov.wallet i{color:#6d28d9}
.acc-card .ac-amt{font-weight:800;font-size:.95rem;color:#059669;direction:ltr}
.acc-card .ac-amt.zero{color:#94a3b8}
.acc-card .ac-line{font-size:.7rem;color:#475569;direction:ltr;font-family:monospace;letter-spacing:.3px;margin-top:.15rem}
.acc-card .ac-owner{font-size:.7rem;color:#7c3aed;margin-top:.15rem}
.acc-card .ac-owner i{margin-left:2px}
.acc-card .ac-split{font-size:.65rem;color:#64748b;margin-top:.2rem}
.acc-card .ac-split b{color:#1e293b}
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
                if (!isset($banks[$bk])) $banks[$bk] = [
                    'branches' => [], 'total' => 0, 'count' => 0, 'master_bank_no' => $mbno,
                    'with_benef' => 0, 'with_multi_acc' => 0, 'with_no_split' => 0, 'with_inactive_acc' => 0,
                ];
                if (!isset($banks[$bk]['branches'][$br])) $banks[$bk]['branches'][$br] = ['rows' => [], 'total' => 0];
                $amt = (float)($r['TOTAL_AMOUNT'] ?? 0);
                $banks[$bk]['branches'][$br]['rows'][] = $r;
                $banks[$bk]['branches'][$br]['total'] += $amt;
                $banks[$bk]['total'] += $amt;
                $banks[$bk]['count']++;
                // إحصاء حالات المستفيدين/الحسابات المتعددة
                $bc = (int)($r['BENEF_COUNT']       ?? 0);
                $ac = (int)($r['ACTIVE_ACC_COUNT']  ?? 0);
                $sc = (int)($r['SPLIT_COUNT']       ?? 0);
                $ic = (int)($r['INACTIVE_ACC_COUNT']?? 0);
                if ($bc > 0)  $banks[$bk]['with_benef']++;
                if ($ac > 1)  $banks[$bk]['with_multi_acc']++;
                if ($bc > 0 && $sc == 0) $banks[$bk]['with_no_split']++;
                if ($ic > 0)  $banks[$bk]['with_inactive_acc']++;
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
                    <?php
                    $b_alerts = [];
                    if (!empty($bankData['with_benef']))         $b_alerts[] = '<span class="bsum bsum-benef">'    . $bankData['with_benef']        . ' بورث</span>';
                    if (!empty($bankData['with_multi_acc']))     $b_alerts[] = '<span class="bsum bsum-multi">'    . $bankData['with_multi_acc']    . ' بحسابات متعددة</span>';
                    if (!empty($bankData['with_no_split']))      $b_alerts[] = '<span class="bsum bsum-warn">⚠ '    . $bankData['with_no_split']     . ' بدون توزيع</span>';
                    if (!empty($bankData['with_inactive_acc']))  $b_alerts[] = '<span class="bsum bsum-inactive">' . $bankData['with_inactive_acc']. ' بحساب موقوف</span>';
                    if ($b_alerts) echo '<span class="ms-2 d-inline-flex gap-1 flex-wrap">' . implode('', $b_alerts) . '</span>';
                    ?>
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
                <table class="table table-bordered table-sm mb-0 emp-tbl">
                    <thead><tr>
                        <th style="width:30px">#</th>
                        <th style="width:80px">رقم الموظف</th>
                        <th>الاسم</th>
                        <th style="width:160px">حالة الحساب</th>
                        <th style="width:130px">المقر</th>
                        <th style="width:170px">البنك/الفرع</th>
                        <th>الطلبات</th>
                        <th class="text-end" style="width:100px">المبلغ</th>
                    </tr></thead>
                    <tbody>
                    <?php $n = 0; foreach ($brData['rows'] as $r): $n++;
                        $emp = (int)($r['EMP_NO'] ?? 0);
                        $bcnt  = (int)($r['BENEF_COUNT']      ?? 0);
                        $acnt  = (int)($r['ACTIVE_ACC_COUNT'] ?? 0);
                        $icnt  = (int)($r['INACTIVE_ACC_COUNT']?? 0);
                        $scnt  = (int)($r['SPLIT_COUNT']      ?? 0);
                        $needs_attention = ($bcnt > 0 && $scnt == 0) || $acnt > 1 || $icnt > 0;
                        $row_class = $needs_attention ? 'row-attention' : '';
                    ?>
                    <tr class="emp-row <?= $row_class ?>" data-emp="<?= $emp ?>">
                        <td class="text-center">
                            <?php if ($acnt > 1 || $bcnt > 0): ?>
                                <button type="button" class="btn-expand" title="عرض التوزيع">
                                    <i class="fa fa-plus-square-o"></i>
                                </button>
                            <?php else: ?>
                                <span class="text-muted"><?= $n ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?= $emp ?></td>
                        <td>
                            <?= $r['EMP_NAME'] ?? '' ?>
                            <?php if ($bcnt > 0): ?>
                                <span class="b-tag b-benef" title="الموظف عنده <?= $bcnt ?> ورث"><i class="fa fa-users"></i> <?= $bcnt ?> ورث</span>
                            <?php endif; ?>
                            <?php if ($acnt > 1): ?>
                                <span class="b-tag b-multi" title="<?= $acnt ?> حسابات نشطة"><i class="fa fa-bank"></i> <?= $acnt ?> حسابات</span>
                            <?php endif; ?>
                            <?php if ($icnt > 0): ?>
                                <span class="b-tag b-inact" title="<?= $icnt ?> حساب موقوف"><i class="fa fa-pause"></i> <?= $icnt ?> موقوف</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($bcnt > 0 && $scnt == 0): ?>
                                <span class="b-tag b-warn" title="موظف عنده ورثة لكن المبلغ على الحساب القديم — راجع التوزيع">
                                    ⚠ على الحساب القديم
                                </span>
                            <?php elseif ($scnt > 0): ?>
                                <span class="b-tag b-ok" title="مبلغ موزّع على <?= $scnt ?> حساب">
                                    <i class="fa fa-check-circle"></i> توزيع (<?= $scnt ?>)
                                </span>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:.7rem">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:.75rem"><?= $r['BRANCH_NAME'] ?? '' ?></td>
                        <td style="font-size:.75rem"><?= $r['BANK_NAME'] ?? '' ?></td>
                        <td style="font-size:.72rem"><?= $r['REQ_NOS'] ?? '' ?></td>
                        <td class="text-end amt"><?= n_format((float)($r['TOTAL_AMOUNT'] ?? 0)) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($brData['rows']) > 1): ?>
                    <tr style="background:#f0fdf4;font-weight:700"><td colspan="7" class="text-end">إجمالي <?= count($bankData['branches']) > 1 ? $brName : $bankName ?></td><td class="text-end"><?= n_format($brData['total']) ?></td></tr>
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
$emp_acc_url    = base_url("$MODULE_NAME/$TB_NAME/batch_emp_accounts_json");

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

// ════════════ توسيع صف الموظف لعرض توزيع الحسابات ════════════
var _empAccUrl = "{$emp_acc_url}";
var _accCache  = {};
var _batchId   = {$batch_id};

function escHtml(s){
    return String(s == null ? '' : s).replace(/[&<>"']/g, function(c){
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
    });
}

function _splitLabel(t, v){
    t = parseInt(t) || 3;
    if(t === 1) return 'نسبة ' + (parseFloat(v)||0) + '%';
    if(t === 2) return 'مبلغ ثابت ' + nf(v);
    return 'كامل الباقي';
}

function _renderAccCards(rows){
    if(!rows || rows.length === 0){
        return '<div class="text-muted text-center py-2" style="font-size:.78rem">لا توجد حسابات للموظف في هذا النظام</div>';
    }
    var html = '<div class="acc-mini">';
    rows.forEach(function(a){
        var isWallet = parseInt(a.PROVIDER_TYPE) === 2;
        var isInact  = parseInt(a.IS_ACTIVE) !== 1;
        var isDef    = parseInt(a.IS_DEFAULT) === 1;
        var amt      = parseFloat(a.BATCH_AMOUNT) || 0;

        var cls = 'acc-card';
        if(isInact) cls += ' is-inactive';
        if(isDef)   cls += ' is-default';

        html += '<div class="' + cls + '">';
        html += '<div class="ac-head">';
        html += '<span class="ac-prov ' + (isWallet ? 'wallet' : 'bank') + '"><i class="fa fa-' +
                (isWallet ? 'mobile' : 'bank') + '"></i>' + escHtml(a.PROVIDER_NAME || '');
        if(isDef)   html += ' <span class="b-tag b-ok">⭐ افتراضي</span>';
        if(isInact) html += ' <span class="b-tag b-inact">موقوف</span>';
        html += '</span>';
        html += '<span class="ac-amt' + (amt <= 0 ? ' zero' : '') + '">' + nf(amt) + '</span>';
        html += '</div>';

        var accNum = a.ACCOUNT_DISP || a.ACCOUNT_NO || a.WALLET_NUMBER || '—';
        html += '<div class="ac-line"><i class="fa fa-hashtag"></i> ' + escHtml(accNum);
        if(a.BANK_BRANCH_NAME) html += ' — ' + escHtml(a.BANK_BRANCH_NAME);
        html += '</div>';

        if(a.IBAN && !isWallet){
            html += '<div class="ac-line">' + escHtml(a.IBAN) + '</div>';
        }

        if(a.OWNER_NAME){
            var ownerLine = a.OWNER_NAME;
            if(a.REL_NAME) ownerLine += ' (' + escHtml(a.REL_NAME) + ')';
            if(a.OWNER_ID_NO) ownerLine += ' — هوية ' + escHtml(a.OWNER_ID_NO);
            html += '<div class="ac-owner"><i class="fa fa-user-o"></i> ' + ownerLine + '</div>';
        }

        html += '<div class="ac-split">التوزيع: <b>' + _splitLabel(a.SPLIT_TYPE, a.SPLIT_VALUE) +
                '</b> · ترتيب: <b>' + (a.SPLIT_ORDER || 0) + '</b>';
        if(amt <= 0 && !isInact){
            html += ' · <span style="color:#dc2626;font-weight:600">⚠ لم يستلم في هذه الدفعة</span>';
        }
        html += '</div>';

        html += '</div>'; // .acc-card
    });
    html += '</div>'; // .acc-mini
    return html;
}

\$(document).on('click', '.btn-expand', function(e){
    e.stopPropagation();
    var \$btn = \$(this);
    var \$row = \$btn.closest('tr.emp-row');
    var emp  = \$row.data('emp');
    var \$next = \$row.next('.emp-detail-row');

    // toggle
    if(\$next.length){
        \$next.toggle();
        \$btn.toggleClass('expanded');
        \$btn.find('i').toggleClass('fa-plus-square-o fa-minus-square-o');
        return;
    }

    var colspan = \$row.find('td').length;
    var \$detailRow = \$('<tr class="emp-detail-row"><td colspan="' + colspan +
        '"><div class="text-muted text-center py-2"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div></td></tr>');
    \$row.after(\$detailRow);
    \$btn.addClass('expanded');
    \$btn.find('i').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');

    if(_accCache[emp]){
        \$detailRow.find('td').html(_renderAccCards(_accCache[emp]));
        return;
    }

    get_data(_empAccUrl, { batch_id: _batchId, emp_no: emp }, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        var rows = (j && j.data) ? j.data : [];
        _accCache[emp] = rows;
        \$detailRow.find('td').html(_renderAccCards(rows));
    }, 'json');
});
</script>
SCRIPT;
sec_scripts($scripts);
?>
