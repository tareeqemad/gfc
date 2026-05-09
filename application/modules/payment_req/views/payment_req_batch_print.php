<?php
/**
 * 🖨️  Print report for a payment batch (A4, RTL, print-optimized)
 *      مصمّم للمحاسب: رأس الدفعة + تفصيل المقر + ملخص البنوك + التواقيع
 */
$bi = $batch_info;
$st = (int)($bi['STATUS'] ?? 0);
$batch_id = $bi['BATCH_ID'] ?? 0;
$batch_no = $bi['BATCH_NO'] ?? '';
$rows = isset($detail_rows) && is_array($detail_rows) ? $detail_rows : [];
$bs_rows = isset($bank_summary) && is_array($bank_summary) ? $bank_summary : [];
$reqs_list = isset($batch_reqs) && is_array($batch_reqs) ? $batch_reqs : [];

// ─── Status label ──────────────────────────────────────────────
$status_labels = [];
foreach (($batch_status_cons ?? []) as $s) { $status_labels[(int)$s['CON_NO']] = $s['CON_NAME']; }
$status_text = $status_labels[$st] ?? '';

// ─── Aggregations ──────────────────────────────────────────────
$total_amt = 0; $sum_t1 = 0; $sum_t2 = 0; $sum_t3 = 0; $sum_t4 = 0;
$by_branch = [];
$all_req_nos = [];
foreach ($rows as $r) {
    $eno = (int)($r['EMP_NO'] ?? 0);
    if (!$eno) continue;
    $total_amt += (float)($r['TOTAL_AMOUNT'] ?? 0);
    $sum_t1 += (float)($r['AMT_TYPE1'] ?? 0);
    $sum_t2 += (float)($r['AMT_TYPE2'] ?? 0);
    $sum_t3 += (float)($r['AMT_TYPE3'] ?? 0);
    $sum_t4 += (float)($r['AMT_TYPE4'] ?? 0);
    if (!empty($r['REQ_NOS'])) {
        foreach (explode(', ', $r['REQ_NOS']) as $rn) { $all_req_nos[trim($rn)] = 1; }
    }
    $emp_br = $r['BRANCH_NAME'] ?? 'غير محدد';
    if (!isset($by_branch[$emp_br])) $by_branch[$emp_br] = [
        'count' => 0, 'total' => 0,
        't1' => 0, 't2' => 0, 't3' => 0, 't4' => 0,
    ];
    $by_branch[$emp_br]['count']++;
    $by_branch[$emp_br]['total'] += (float)($r['TOTAL_AMOUNT'] ?? 0);
    $by_branch[$emp_br]['t1']    += (float)($r['AMT_TYPE1']   ?? 0);
    $by_branch[$emp_br]['t2']    += (float)($r['AMT_TYPE2']   ?? 0);
    $by_branch[$emp_br]['t3']    += (float)($r['AMT_TYPE3']   ?? 0);
    $by_branch[$emp_br]['t4']    += (float)($r['AMT_TYPE4']   ?? 0);
}
uasort($by_branch, function($a, $b) { return $b['total'] <=> $a['total']; });

$bs_total_amt = 0; $bs_total_emp = 0; $bs_total_trx = 0; $bs_total_branches = 0;
foreach ($bs_rows as $br) {
    $bs_total_amt += (float)($br['TOTAL_AMOUNT'] ?? 0);
    $bs_total_emp += (int)($br['EMP_COUNT']    ?? 0);
    $bs_total_trx += (int)($br['TRANSACTION_COUNT'] ?? 0);
    $bs_total_branches += (int)($br['BRANCH_COUNT'] ?? 0);
}

$all_months = []; $all_types = [];
foreach ($reqs_list as $rq) {
    $m = $rq['THE_MONTH'] ?? '';
    if ($m) $all_months[$m] = 1;
    $rt = $rq['REQ_TYPE_NAME'] ?? '';
    if ($rt) $all_types[$rt] = 1;
}
ksort($all_months);

$emp_count = (int)($bi['EMP_COUNT'] ?? 0);
$mov_count = (int)($bi['MOVEMENT_COUNT'] ?? 0);
$print_date = date('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الدفعة <?= htmlspecialchars($batch_no) ?></title>
    <style>
        @page { size: A4; margin: 12mm 10mm 14mm 10mm; }
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }
        body {
            font-family: 'Tahoma','Arial',sans-serif;
            font-size: 11pt;
            color: #1f2937;
            line-height: 1.45;
            background: #fff;
        }
        .page { width: 100%; padding: 0; }

        /* ─── Letterhead ─── */
        .letterhead {
            border-bottom: 2px solid #1e40af;
            padding-bottom: 8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .letterhead .lh-right { display: flex; align-items: center; gap: 10px; }
        .letterhead img { height: 56px; }
        .letterhead .co { font-weight: 800; font-size: 14pt; color: #1e293b; }
        .letterhead .co small { display: block; font-size: 9pt; font-weight: 500; color: #64748b; margin-top: 2px; }
        .letterhead .lh-left { text-align: left; font-size: 9pt; color: #64748b; }
        .letterhead .lh-left strong { color: #1e293b; font-size: 10pt; }

        /* ─── Title ─── */
        .doc-title {
            text-align: center;
            font-size: 16pt;
            font-weight: 800;
            color: #1e40af;
            margin: 6px 0 14px 0;
            letter-spacing: .5px;
        }
        .doc-title small { display: block; font-size: 10pt; color: #64748b; font-weight: 500; margin-top: 3px; }

        /* ─── Info grid ─── */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-bottom: 10px;
        }
        .info-card {
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 5px 8px;
            text-align: center;
            background: #f8fafc;
        }
        .info-card .lbl { font-size: 8.5pt; color: #64748b; font-weight: 600; margin-bottom: 1px; }
        .info-card .val { font-size: 11pt; font-weight: 800; color: #1e293b; }
        .info-card.highlight { background: #f0fdf4; border-color: #86efac; }
        .info-card.highlight .val { color: #15803d; }
        .info-card.dark { background: #1e293b; border-color: #1e293b; }
        .info-card.dark .lbl, .info-card.dark .val { color: #fff; }

        /* ─── Status pill ─── */
        .status-pill {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 10pt;
        }
        .status-pill.s0 { background: #dbeafe; color: #1e40af; }
        .status-pill.s2 { background: #dcfce7; color: #15803d; }
        .status-pill.s9 { background: #f1f5f9; color: #64748b; }

        /* ─── Section ─── */
        .section { margin-bottom: 10px; page-break-inside: avoid; }
        .section-title {
            font-size: 11pt;
            font-weight: 800;
            color: #1e40af;
            border-right: 4px solid #1e40af;
            padding: 2px 8px;
            margin-bottom: 5px;
            background: #eff6ff;
        }

        /* ─── Tags row ─── */
        .tags-row { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 4px; }
        .tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: 600;
            border: 1px solid;
        }
        .tag.month { background: #dbeafe; color: #1e40af; border-color: #93c5fd; }
        .tag.req   { background: #fff; color: #334155; border-color: #cbd5e1; }
        .tag.type  { background: #f5f3ff; color: #6d28d9; border-color: #c4b5fd; }
        .meta-line { font-size: 9pt; color: #64748b; margin-bottom: 3px; }
        .meta-line strong { color: #1e293b; }

        /* ─── Tables ─── */
        table.rpt {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 6px;
        }
        table.rpt th, table.rpt td {
            border: 1px solid #94a3b8;
            padding: 4px 6px;
            text-align: right;
        }
        table.rpt thead th {
            background: #1e40af;
            color: #fff;
            font-weight: 700;
            font-size: 10pt;
            text-align: center;
        }
        table.rpt tbody tr:nth-child(even) { background: #f8fafc; }
        table.rpt td.num { text-align: left; direction: ltr; font-variant-numeric: tabular-nums; }
        table.rpt td.ctr { text-align: center; }
        table.rpt tr.total-row {
            background: #1e293b !important;
            color: #fff;
            font-weight: 800;
        }
        table.rpt tr.total-row td { border-color: #1e293b; }
        table.rpt tr.total-row td.num { color: #fde68a; }
        .tbl-empty { text-align: center; color: #94a3b8; padding: 8px; font-style: italic; }

        /* ─── Signatures ─── */
        .sig-section {
            margin-top: 18px;
            page-break-inside: avoid;
        }
        .sig-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-top: 6px;
        }
        .sig-box {
            border-top: 1px dashed #94a3b8;
            padding-top: 4px;
            text-align: center;
            font-size: 9.5pt;
        }
        .sig-box .role { font-weight: 700; color: #1e293b; }
        .sig-box .name { font-size: 8.5pt; color: #64748b; min-height: 10pt; margin-top: 14px; }

        /* ─── Footer ─── */
        .footer {
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px solid #cbd5e1;
            font-size: 8pt;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }

        /* ─── Print toolbar (on-screen only) ─── */
        .print-toolbar {
            position: fixed;
            top: 8px;
            left: 8px;
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            z-index: 9999;
            font-family: 'Tahoma',sans-serif;
        }
        .print-toolbar button {
            background: #1e40af;
            color: #fff;
            border: 0;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 11pt;
            font-weight: 700;
            cursor: pointer;
            margin-left: 4px;
        }
        .print-toolbar button:hover { background: #1d4ed8; }
        .print-toolbar button.sec { background: #64748b; }
        .print-toolbar button.sec:hover { background: #475569; }

        @media print {
            .print-toolbar { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body>

<div class="print-toolbar">
    <button onclick="window.print()">🖨️ طباعة</button>
    <button class="sec" onclick="window.close()">إغلاق</button>
</div>

<div class="page">

    <!-- Letterhead -->
    <div class="letterhead">
        <div class="lh-right">
            <img src="<?= base_url() ?>assets-n/images/brand/gedco-logo.png" alt="logo">
            <div class="co">
                شركة توزيع كهرباء محافظات غزة
                <small>دائرة الموارد البشرية — وحدة الرواتب</small>
            </div>
        </div>
        <div class="lh-left">
            <strong>تقرير دفعة صرف</strong><br>
            تاريخ الطباعة: <?= $print_date ?><br>
            <?php if (!empty($user_name)): ?>طُبع بواسطة: <?= htmlspecialchars($user_name) ?><?php endif; ?>
        </div>
    </div>

    <div class="doc-title">
        تفاصيل الدفعة <?= htmlspecialchars($batch_no) ?>
        <small>كشف شامل للصرف على الموظفين والبنوك والمحافظ</small>
    </div>

    <!-- Top info grid -->
    <div class="info-grid">
        <div class="info-card dark">
            <div class="lbl">رقم الدفعة</div>
            <div class="val"><?= htmlspecialchars($batch_no) ?></div>
        </div>
        <div class="info-card">
            <div class="lbl">تاريخ الإنشاء</div>
            <div class="val" style="font-size:10pt"><?= htmlspecialchars($bi['ENTRY_DATE'] ?? '') ?></div>
            <div style="font-size:8pt;color:#64748b;margin-top:1px"><?= htmlspecialchars($bi['ENTRY_USER_NAME'] ?? '') ?></div>
        </div>
        <div class="info-card">
            <div class="lbl">عدد الموظفين / الحركات</div>
            <div class="val"><?= n_format($emp_count) ?> / <?= n_format($mov_count) ?></div>
        </div>
        <div class="info-card highlight">
            <div class="lbl">إجمالي مبلغ الصرف</div>
            <div class="val"><?= n_format($total_amt) ?></div>
        </div>
    </div>

    <!-- Status + Pay info -->
    <div class="meta-line">
        <strong>حالة الدفعة:</strong>
        <span class="status-pill s<?= $st ?>"><?= htmlspecialchars($status_text) ?></span>
        <?php if ($st == 2 && !empty($bi['PAY_DATE'])): ?>
            &nbsp;&nbsp;<strong>تاريخ التنفيذ:</strong> <?= htmlspecialchars($bi['PAY_DATE']) ?>
            <?php if (!empty($bi['PAY_USER_NAME'])): ?> — منفّذ بواسطة: <?= htmlspecialchars($bi['PAY_USER_NAME']) ?><?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Months / Requests / Types -->
    <div class="section">
        <div class="meta-line">
            <strong>الشهور المغطاة:</strong>
            <?php if (empty($all_months)): ?>
                <span style="color:#94a3b8">—</span>
            <?php else: foreach ($all_months as $m => $v): $mf = strlen($m)==6 ? substr($m,4,2).'/'.substr($m,0,4) : $m; ?>
                <span class="tag month"><?= htmlspecialchars($mf) ?></span>
            <?php endforeach; endif; ?>
        </div>
        <div class="meta-line">
            <strong>الطلبات (<?= count($all_req_nos) ?>):</strong>
            <?php if (empty($all_req_nos)): ?>
                <span style="color:#94a3b8">—</span>
            <?php else: foreach ($all_req_nos as $rn => $v): ?>
                <span class="tag req"><?= htmlspecialchars($rn) ?></span>
            <?php endforeach; endif; ?>
        </div>
        <div class="meta-line">
            <strong>أنواع الصرف:</strong>
            <?php if (empty($all_types)): ?>
                <span style="color:#94a3b8">—</span>
            <?php else: foreach ($all_types as $rt => $v): ?>
                <span class="tag type"><?= htmlspecialchars($rt) ?></span>
            <?php endforeach; endif; ?>
        </div>
    </div>

    <!-- Breakdown by location (مقر) -->
    <div class="section">
        <div class="section-title">
            تفصيل الصرف حسب المقر — تقسيم الأنواع
        </div>
        <table class="rpt">
            <thead>
                <tr>
                    <th style="width:30px">#</th>
                    <th>المقر</th>
                    <th style="width:60px">موظفين</th>
                    <th style="width:90px">راتب كامل</th>
                    <th style="width:90px">راتب جزئي</th>
                    <th style="width:100px">دفعة من المستحقات</th>
                    <th style="width:110px">استحقاقات وإضافات</th>
                    <th style="width:100px">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($by_branch)): ?>
                <tr><td colspan="8" class="tbl-empty">لا توجد بيانات</td></tr>
            <?php else: $i = 0; foreach ($by_branch as $brName => $brInfo): $i++; ?>
                <tr>
                    <td class="ctr"><?= $i ?></td>
                    <td><?= htmlspecialchars($brName) ?></td>
                    <td class="ctr"><?= n_format($brInfo['count']) ?></td>
                    <td class="num"><?= $brInfo['t1'] > 0 ? n_format($brInfo['t1']) : '—' ?></td>
                    <td class="num"><?= $brInfo['t2'] > 0 ? n_format($brInfo['t2']) : '—' ?></td>
                    <td class="num"><?= $brInfo['t3'] > 0 ? n_format($brInfo['t3']) : '—' ?></td>
                    <td class="num"><?= $brInfo['t4'] > 0 ? n_format($brInfo['t4']) : '—' ?></td>
                    <td class="num"><strong><?= n_format($brInfo['total']) ?></strong></td>
                </tr>
            <?php endforeach; endif; ?>
            <tr class="total-row">
                <td colspan="2">الإجمالي العام</td>
                <td class="ctr"><?= n_format(count($rows)) ?></td>
                <td class="num"><?= $sum_t1 > 0 ? n_format($sum_t1) : '—' ?></td>
                <td class="num"><?= $sum_t2 > 0 ? n_format($sum_t2) : '—' ?></td>
                <td class="num"><?= $sum_t3 > 0 ? n_format($sum_t3) : '—' ?></td>
                <td class="num"><?= $sum_t4 > 0 ? n_format($sum_t4) : '—' ?></td>
                <td class="num"><?= n_format($total_amt) ?></td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Bank / Wallet summary -->
    <div class="section">
        <div class="section-title">
            ملخص الصرف حسب البنك / المحفظة
        </div>
        <table class="rpt">
            <thead>
                <tr>
                    <th style="width:30px">#</th>
                    <th>الجهة المستلِمة</th>
                    <th style="width:60px">النوع</th>
                    <th style="width:60px">فروع</th>
                    <th style="width:70px">موظفين</th>
                    <th style="width:80px">الحركات</th>
                    <th style="width:110px">المبلغ</th>
                    <th style="width:60px">النسبة</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($bs_rows)): ?>
                <tr><td colspan="8" class="tbl-empty">لا توجد حركات بنكية</td></tr>
            <?php else: $i = 0; foreach ($bs_rows as $br): $i++;
                $is_wallet = ((int)($br['PROVIDER_TYPE'] ?? 1)) == 2;
                $type_label = $is_wallet ? 'محفظة' : 'بنك';
                $br_amt = (float)($br['TOTAL_AMOUNT'] ?? 0);
                $pct = $bs_total_amt > 0 ? ($br_amt / $bs_total_amt) * 100 : 0;
            ?>
                <tr>
                    <td class="ctr"><?= $i ?></td>
                    <td><strong><?= htmlspecialchars($br['BANK_NAME'] ?? '') ?></strong></td>
                    <td class="ctr"><?= $type_label ?></td>
                    <td class="ctr"><?= n_format((int)($br['BRANCH_COUNT'] ?? 0)) ?></td>
                    <td class="ctr"><?= n_format((int)($br['EMP_COUNT']    ?? 0)) ?></td>
                    <td class="ctr"><?= n_format((int)($br['TRANSACTION_COUNT'] ?? 0)) ?></td>
                    <td class="num"><strong><?= n_format($br_amt) ?></strong></td>
                    <td class="num"><?= number_format($pct, 1) ?>%</td>
                </tr>
            <?php endforeach; endif; ?>
            <tr class="total-row">
                <td colspan="3">الإجمالي العام</td>
                <td class="ctr"><?= n_format($bs_total_branches) ?></td>
                <td class="ctr"><?= n_format($emp_count) ?></td>
                <td class="ctr"><?= n_format($bs_total_trx) ?></td>
                <td class="num"><?= n_format($bs_total_amt) ?></td>
                <td class="num">100.0%</td>
            </tr>
            </tbody>
        </table>
        <?php if ($bs_total_emp > $emp_count): ?>
        <div style="font-size:8.5pt;color:#9a3412;margin-top:2px">
            * مجموع الموظفين في الجدول (<?= n_format($bs_total_emp) ?>) أكبر من الموظفين الفعليين (<?= n_format($emp_count) ?>)
            بسبب وجود <?= n_format($bs_total_emp - $emp_count) ?> موظف لديهم تقسيم على أكثر من بنك.
        </div>
        <?php endif; ?>
    </div>

    <!-- Signatures -->
    <div class="sig-section">
        <div class="section-title">التواقيع والاعتمادات</div>
        <div class="sig-grid">
            <div class="sig-box">
                <div class="role">المحاسب — أعدّ الدفعة</div>
                <div class="name"><?= htmlspecialchars($bi['ENTRY_USER_NAME'] ?? '') ?></div>
            </div>
            <div class="sig-box">
                <div class="role">المدير المالي — اعتماد</div>
                <div class="name">&nbsp;</div>
            </div>
            <div class="sig-box">
                <div class="role">المدير العام — موافقة</div>
                <div class="name">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <span>دفعة <?= htmlspecialchars($batch_no) ?> · <?= $emp_count ?> موظف · <?= n_format($total_amt) ?> ش.ج</span>
        <span>طُبع في <?= $print_date ?></span>
    </div>

</div>

<script>
// طباعة فورية لو URL يحوي ?auto=1
(function(){
    var qs = window.location.search || '';
    if (qs.indexOf('auto=1') !== -1) {
        window.addEventListener('load', function(){
            setTimeout(function(){ window.print(); }, 300);
        });
    }
})();
</script>

</body>
</html>
