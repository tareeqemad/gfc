<?php
/**
 * Salary Dues - Index
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'dues';

$create_url   = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$delete_url   = base_url("$MODULE_NAME/$TB_NAME/delete");

echo AntiForgeryToken();
?>

    <style>
        #payTypeTreeModalIndex .modal-body > div {
            border-radius: 6px !important;
            background: transparent !important;
        }
        #pay_type_tree_loading_index .fa-spinner { font-size: 2rem !important; }
        #pay_type_tree_wrap_index #pay_type_tree_index,
        #pay_type_tree_wrap_index #pay_type_tree_index ul,
        #pay_type_tree_wrap_index #pay_type_tree_index li {
            list-style: none !important;
            padding-inline-start: 0;
            margin-inline-start: 0;
        }
        #pay_type_tree_wrap_index #pay_type_tree_index ul {
            padding-inline-start: 1.2rem;
        }
        #pay_type_tree_index .tree-node { padding: 6px 12px; border-radius: 6px; display: inline-block; margin: 2px 0; }
        #pay_type_tree_index .tree-node:hover { background: #e7f3ff; }
        #pay_type_tree_index .tree-icon { margin-left: 6px; }
        #pay_type_tree_index .lt-1 { border-right: 3px solid #2ecc71; }
        #pay_type_tree_index .lt-2 { border-right: 3px solid #e74c3c; }

        /* ═══ Modal ملخص الموظف ═══ */
        #empSummaryModal .modal-content { border:0; border-radius:12px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,.12); }
        #empSummaryModal .modal-header { background:#1e293b; padding:0.65rem 1rem; }
        #empSummaryModal .modal-title { color:#fff; font-weight:700; font-size:0.9rem; }
    </style>
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fa fa-search text-primary"></i> استعلام
                    </h3>
                    <div class="card-options">
                        <?php if (HaveAccess($create_url)): ?>
                            <button type="button" class="btn btn-warning me-2" onclick="openMigrateModal()" data-bs-toggle="tooltip" title="ترحيل مستحقات من جدول الرواتب">
                                <i class="fa fa-exchange"></i> ترحيل المستحقات من الرواتب
                            </button>
                            <a class="btn btn-success" href="<?= $create_url ?>" data-bs-toggle="tooltip" title="إضافة دفعة جديدة">
                                <i class="fa fa-plus"></i> جديد
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <form id="<?= $TB_NAME ?>_form">

                        <div class="row g-3">
                            <?php if ($this->user->branch == 1) { ?>
                                <div class="form-group col-md-2">
                                    <label>
                                        <i class="fa fa-building text-info"></i> المقر
                                    </label>
                                    <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                        <option value="">_______</option>
                                        <?php foreach ($branches as $row) : ?>
                                            <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php } else { ?>
                                <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                            <?php } ?>

                            <div class="form-group col-md-3">
                                <label>
                                    <i class="fa fa-user text-primary"></i> الموظف
                                </label>
                                <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($emp_no_cons as $row) : ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>
                                    <i class="fa fa-calendar text-success"></i> الشهر
                                    <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" title="صيغة: YYYYMM (مثال: 202501)"></i>
                                </label>
                                <input type="text"
                                       name="the_month"
                                       id="txt_the_month"
                                       class="form-control"
                                       placeholder="YYYYMM"
                                       maxlength="6"
                                       pattern="[0-9]{6}">
                            </div>

                            <div class="form-group col-md-3">
                                <label>
                                    <i class="fa fa-tag text-info"></i> نوع الدفع
                                </label>
                                <div class="input-group">
                                    <input type="hidden" name="pay_type" id="dp_pay_type" value="">
                                    <input type="text" id="dp_pay_type_display" class="form-control bg-white" readonly
                                           placeholder="الكل أو اختر من الشجرة..."
                                           value="">
                                    <button type="button" class="btn btn-outline-primary" id="btn_dp_pay_type_tree" title="فتح شجرة أنواع الدفع">
                                        <i class="fa fa-sitemap"></i> اختر من الشجرة
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 d-flex gap-2">
                                <button type="button" onclick="javascript:search();" class="btn btn-primary">
                                    <i class="fa fa-search"></i> إستعلام
                                </button>
                                <button type="button" onclick="javascript:clear_form();" class="btn btn-outline-secondary">
                                    <i class="fa fa-eraser"></i> مسح الحقول
                                </button>
                                <button type="button" onclick="javascript:exportExcel();" class="btn btn-outline-success">
                                    <i class="fa fa-file-excel-o"></i> تصدير Excel
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <hr class="my-4 border-2 border-secondary opacity-25">
            <div id="container" class="rounded p-3" style="background: #f1f5f9; min-height: 120px;">
                <div class="text-center text-muted py-5">
                    <i class="fa fa-spinner fa-spin fa-2x mb-3"></i>
                    <p>جاري التحميل...</p>
                </div>
            </div>

        </div>
    </div>

    <!-- مودال اختيار نوع الدفع من الشجرة (للبحث) -->
    <div class="modal fade" id="payTypeTreeModalIndex" tabindex="-1" aria-labelledby="payTypeTreeModalIndexLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="payTypeTreeModalIndexLabel">
                        <i class="fa fa-sitemap text-primary me-2"></i> اختر نوع الدفع للبحث
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body p-3 position-relative" style="min-height: 220px;">
                    <div id="pay_type_tree_loading_index" class="text-center py-4 text-muted" style="display:none;">
                        <i class="fa fa-spinner fa-spin fa-2x d-block mb-2"></i>
                        <p class="mb-0">جاري تحميل الشجرة...</p>
                    </div>
                    <div id="pay_type_tree_wrap_index" class="overflow-auto border rounded bg-light p-3" style="display:none; max-height: 65vh; min-height: 200px;">
                        <ul id="pay_type_tree_index" class="tree list-unstyled mb-0"></ul>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-outline-secondary" id="btn_clear_pay_type_index"><i class="fa fa-times"></i> إزالة الفلتر</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ Modal ترحيل المستحقات من الرواتب ═══ -->
    <div class="modal fade" id="migrateModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:0; border-radius:12px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,.12);">
                <div class="modal-header py-2" style="background:#92400e;">
                    <h6 class="modal-title text-white"><i class="fa fa-exchange me-2"></i> ترحيل مستحقات من الرواتب</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="migrateBody">
                    <!-- الخطوة 1: اختيار الشهر -->
                    <div id="migrateStep1">
                        <div class="form-group mb-3">
                            <label class="fw-bold mb-1"><i class="fa fa-tag text-primary"></i> نوع المستحق</label>
                            <select id="migrate_con_no" class="form-control">
                                <option value="260">دفعة من المستحقات</option>
                                <option value="261">مساعدة نقدية من المستحقات</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold mb-1"><i class="fa fa-calendar text-success"></i> الشهر</label>
                            <input type="text" id="migrate_month" class="form-control" placeholder="YYYYMM" maxlength="6">
                        </div>
                    </div>
                    <!-- الخطوة 2: نتيجة الفحص -->
                    <div id="migrateStep2" style="display:none;"></div>
                    <!-- Loading -->
                    <div id="migrateLoading" class="text-center py-4" style="display:none;">
                        <i class="fa fa-spinner fa-spin fa-2x text-warning"></i>
                        <p class="text-muted mt-2 mb-0">جاري الفحص...</p>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-info" id="btnMigrateCheck" onclick="migrateCheck()">
                        <i class="fa fa-search"></i> فحص
                    </button>
                    <button type="button" class="btn btn-warning" id="btnMigrateRun" onclick="migrateRun()" style="display:none;">
                        <i class="fa fa-check"></i> ترحيل الآن
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ Modal ملخص الموظف ═══ -->
    <div class="modal fade" id="empSummaryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title"><i class="fa fa-user-circle me-2"></i><span id="empModalTitle"></span></h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" id="empModalBody">
                    <div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin fa-lg"></i></div>
                </div>
            </div>
        </div>
    </div>

<?php
$pay_type_tree_url_js = isset($pay_type_tree_url) ? json_encode($pay_type_tree_url) : '""';
$summary_url = base_url("$MODULE_NAME/$TB_NAME/public_get_summary");
$migrate_check_url = base_url("$MODULE_NAME/$TB_NAME/migrate_check");
$migrate_run_url   = base_url("$MODULE_NAME/$TB_NAME/migrate_run");
$export_excel_url  = base_url("$MODULE_NAME/$TB_NAME/export_excel");
$scripts = <<<SCRIPT
<script>
    /* ═══ Infinite Scroll ═══ */
    var _scrollPage = 1;
    var _scrollLoading = false;
    var _scrollHasMore = true;

    function reBind(){
        if(typeof initFunctions == 'function') initFunctions();
        initTooltips();
        var meta = $('#scroll-meta');
        if (meta.length) {
            _scrollPage = parseInt(meta.data('page')) || 1;
            _scrollHasMore = parseInt(meta.data('has-more')) === 1;
        }
        $(window).off('scroll.dues').on('scroll.dues', function(){
            if (_scrollLoading || !_scrollHasMore) return;
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
                _loadMore();
            }
        });
    }

    function _loadMore(){
        _scrollLoading = true;
        _scrollPage++;
        $('#scroll-loading').show();
        var payTypeVal = ($('#dp_pay_type').val() || '').trim();
        get_data('{$get_page_url}', {
            page: _scrollPage,
            branch_no: $('#dp_branch_no').val(),
            emp_no: $('#dp_emp_no').val(),
            the_month: $('#txt_the_month').val(),
            pay_type: payTypeVal || null,
            mode: 'append'
        }, function(html){
            $('#scroll-loading').hide();
            html = $.trim(html);
            if (html.length > 10) {
                $('#page_tb tbody').append(html);
                initTooltips();
            } else {
                _scrollHasMore = false;
            }
            _scrollLoading = false;
        }, 'html');
    }

    function initTooltips(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    /* ═══ Employee Summary Modal ═══ */
    var _empModalCache = {};
    var _summaryUrl = '{$summary_url}';

    function showEmpModal(empNo, empName) {
        if (event) event.stopPropagation();
        $('#empModalTitle').text(empNo + ' — ' + empName);

        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('empSummaryModal'));
        modal.show();

        if (_empModalCache[empNo]) {
            $('#empModalBody').html(_empModalCache[empNo]);
            return;
        }

        $('#empModalBody').html('<div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin fa-lg"></i> جاري التحميل...</div>');

        get_data(_summaryUrl, { emp_no: empNo }, function(response) {
            var res = (typeof response === 'string') ? JSON.parse(response) : response;
            if (!res.ok || !res.data || res.data.length === 0) {
                $('#empModalBody').html('<div class="alert alert-warning m-3 text-center">لا توجد بيانات</div>');
                return;
            }

            // DEBUG — شوف شو راجع بالـ console (F12)
            console.log('Summary response for EMP ' + empNo + ':', res.data[0]);

            // قراءة البيانات — case insensitive (Oracle ممكن يرجع uppercase أو lowercase)
            var d = res.data[0];
            var base = _getVal(d, 'TOTAL_BASE_DUE');
            var add  = _getVal(d, 'TOTAL_ADD');
            var ded  = _getVal(d, 'TOTAL_DED');
            var bal  = _getVal(d, 'TOTAL_BALANCE');
            var entitled = base + add;
            var pct  = entitled > 0 ? Math.min(100, Math.round((ded / entitled) * 1000) / 10) : 0;

            // ═══ الملخص المالي ═══
            var html = '<div style="padding:1rem;">';

            // بطاقات الملخص
            html += '<div style="display:flex; gap:0.5rem; margin-bottom:1rem; flex-wrap:wrap;">';
            html += _card('المستحقات الأساسية', _nf(base), '#2563eb', '#eff6ff');
            html += _card('الإضافات', '+ ' + _nf(add), '#16a34a', '#f0fdf4');
            html += _card('الخصومات', '- ' + _nf(ded), '#dc2626', '#fef2f2');
            html += '</div>';

            // الرصيد المتبقي — كبير وبارز
            html += '<div style="background:#1e293b; color:#fff; border-radius:10px; padding:0.8rem 1rem; margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">';
            html += '<span style="color:#94a3b8; font-size:0.8rem;">الرصيد المتبقي</span>';
            html += '<span style="font-size:1.3rem; font-weight:800;">' + _nf(bal) + '</span>';
            html += '</div>';

            // نسبة التسديد
            if (entitled > 0) {
                html += '<div style="background:#faf5ff; border-radius:8px; padding:0.5rem 0.8rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.8rem;">';
                html += '<span style="color:#64748b; font-size:0.75rem;">نسبة التسديد</span>';
                html += '<div style="flex:1; background:#e9d5ff; border-radius:4px; height:6px;"><div style="width:' + pct + '%; background:#7c3aed; height:100%; border-radius:4px;"></div></div>';
                html += '<span style="color:#7c3aed; font-weight:700; font-size:0.85rem;">' + pct + '%</span>';
                html += '</div>';
            }

            // ═══ جدول الحركات ═══
            var allRows = [];
            $('#container #page_tb tbody tr').each(function(){
                var link = $(this).find('.emp-link');
                if (link.length && link.data('emp') == empNo) {
                    var c = $(this).find('td');
                    allRows.push({
                        month: c.eq(3).text().trim(),
                        date:  c.eq(4).text().trim(),
                        type:  c.eq(5).text().trim(),
                        lt:    c.eq(6).html(),
                        pay:   c.eq(7).html(),
                        st:    c.eq(8).html(),
                        cancelled: $(this).hasClass('cancelled')
                    });
                }
            });

            html += '<div style="font-weight:700; font-size:0.85rem; color:#334155; margin-bottom:0.5rem;">';
            html += '<i class="fa fa-list-alt text-primary me-1"></i> الحركات (' + allRows.length + ')';
            html += '</div>';

            if (allRows.length > 0) {
                html += '<div style="max-height:250px; overflow-y:auto; border:1px solid #e2e8f0; border-radius:8px;">';
                html += '<table class="table table-bordered table-sm mb-0"><thead class="table-light"><tr>';
                html += '<th>#</th><th>الشهر</th><th>التاريخ</th><th>نوع الدفع</th><th>النوع</th><th>المبلغ</th><th>الحالة</th>';
                html += '</tr></thead><tbody>';
                for (var i = 0; i < allRows.length; i++) {
                    var r = allRows[i], sty = r.cancelled ? ' style="opacity:0.4"' : '';
                    html += '<tr' + sty + '><td>' + (i+1) + '</td><td>' + r.month + '</td><td>' + r.date + '</td>';
                    html += '<td>' + r.type + '</td><td>' + r.lt + '</td><td>' + r.pay + '</td><td>' + r.st + '</td></tr>';
                }
                html += '</tbody></table></div>';
            }

            // Footer
            html += '<div style="margin-top:0.8rem; padding:0.5rem 0; border-top:1px solid #e2e8f0; font-size:0.8rem; display:flex; justify-content:space-between;">';
            html += '<span class="text-muted">' + allRows.length + ' حركة</span>';
            html += '<span><strong style="color:#16a34a">+ ' + _nf(add) + '</strong>';
            html += ' <span class="text-muted mx-1">|</span> ';
            html += '<strong style="color:#dc2626">- ' + _nf(ded) + '</strong>';
            html += ' <span class="text-muted mx-1">=</span> ';
            html += '<strong style="font-size:0.95rem;">' + _nf(bal) + '</strong></span>';
            html += '</div>';

            html += '</div>'; // end padding div

            _empModalCache[empNo] = html;
            $('#empModalBody').html(html);
        }, 'json');
    }

    function _card(label, value, color, bg) {
        return '<div style="flex:1; min-width:120px; text-align:center; padding:0.7rem 0.5rem; border-radius:8px; background:' + bg + ';">'
             + '<div style="font-size:0.68rem; color:#64748b; margin-bottom:0.2rem;">' + label + '</div>'
             + '<div style="font-size:1rem; font-weight:800; color:' + color + ';">' + value + '</div></div>';
    }

    // قراءة قيمة من الـ response — يدعم UPPER و lower case
    function _getVal(obj, key) {
        if (obj[key] !== undefined) return parseFloat(obj[key]) || 0;
        if (obj[key.toLowerCase()] !== undefined) return parseFloat(obj[key.toLowerCase()]) || 0;
        // جرب كل المفاتيح
        for (var k in obj) {
            if (k.toUpperCase() === key.toUpperCase()) return parseFloat(obj[k]) || 0;
        }
        return 0;
    }

    function _nf(n) { return typeof n_format==='function' ? n_format(n) : Number(n).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }
        

    $(function(){
        // Initialize select2
       $('.sel2:not("[id^=\'s2\']")').select2();
        
        // Initialize tooltips
        initTooltips();
        
        // Load initial data
        loadData();


         $('#txt_the_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
        });
    });

    var payTypeTreeUrlIndex = {$pay_type_tree_url_js};
    var payTypeTreeDataIndex = null;

    function buildPayTypeTreeHtmlIndex(nodes) {
        if (!nodes || nodes.length === 0) return '';
        var html = '';
        for (var i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            var hasChildren = n.children && n.children.length > 0;
            var lineType = (n.attributes && n.attributes.lineType) ? n.attributes.lineType : 1;
            var ltClass = 'lt-' + lineType;
            if (hasChildren) {
                html += '<li class="parent_li" data-id="' + n.id + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-parent" style="cursor:pointer;"><i class="fa fa-plus tree-icon"></i> ' + (n.text || '') + '</span>';
                html += '<ul class="list-unstyled ms-3" style="display:none;">' + buildPayTypeTreeHtmlIndex(n.children) + '</ul>';
                html += '</li>';
            } else {
                html += '<li data-id="' + n.id + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-leaf" style="cursor:pointer;"><i class="fa tree-icon" style="display:inline-block;width:16px;"></i> ' + (n.text || '') + '</span>';
                html += '</li>';
            }
        }
        return html;
    }

    function loadPayTypeTreeIndex() {
        var loading = jQuery('#pay_type_tree_loading_index');
        var wrap = jQuery('#pay_type_tree_wrap_index');
        var tree = jQuery('#pay_type_tree_index');
        wrap.hide();
        loading.show();
        if (payTypeTreeDataIndex && payTypeTreeDataIndex.length > 0) {
            loading.hide();
            tree.html(buildPayTypeTreeHtmlIndex(payTypeTreeDataIndex));
            wrap.show();
            bindPayTypeTreeEventsIndex();
            return;
        }
        if (!payTypeTreeUrlIndex) {
            loading.hide();
            tree.html('<li class="text-muted">لا يوجد مصدر للشجرة.</li>');
            wrap.show();
            return;
        }
        tree.empty();
        jQuery.get(payTypeTreeUrlIndex).done(function(data) {
            payTypeTreeDataIndex = Array.isArray(data) ? data : [];
            loading.hide();
            tree.html(buildPayTypeTreeHtmlIndex(payTypeTreeDataIndex));
            wrap.show();
            bindPayTypeTreeEventsIndex();
        }).fail(function() {
            loading.hide();
            tree.html('<li class="text-danger">فشل تحميل الشجرة.</li>');
            wrap.show();
        });
    }

    function bindPayTypeTreeEventsIndex() {
        jQuery('#pay_type_tree_index').off('click', '.pay-type-parent').on('click', '.pay-type-parent', function(e) {
            e.stopPropagation();
            var ul = jQuery(this).closest('li').children('ul');
            var icon = jQuery(this).find('.tree-icon');
            if (ul.is(':visible')) {
                ul.slideUp(200);
                icon.removeClass('fa-minus').addClass('fa-plus');
            } else {
                ul.slideDown(200);
                icon.removeClass('fa-plus').addClass('fa-minus');
            }
        });
        jQuery('#pay_type_tree_index').off('click', '.pay-type-leaf').on('click', '.pay-type-leaf', function(e) {
            e.stopPropagation();
            var li = jQuery(this).closest('li');
            var id = li.data('id');
            var text = li.find('.tree-node').text().trim();
            if (id) {
                jQuery('#dp_pay_type').val(id);
                jQuery('#dp_pay_type_display').val(text);
                var modal = bootstrap.Modal.getInstance(document.getElementById('payTypeTreeModalIndex'));
                if (modal) modal.hide();
            }
        });
    }

    jQuery(function() {
        jQuery('#btn_dp_pay_type_tree').on('click', function(e) {
            e.preventDefault();
            loadPayTypeTreeIndex();
            var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('payTypeTreeModalIndex'));
            modal.show();
        });
        jQuery('#btn_clear_pay_type_index').on('click', function() {
            jQuery('#dp_pay_type').val('');
            jQuery('#dp_pay_type_display').val('');
            var modal = bootstrap.Modal.getInstance(document.getElementById('payTypeTreeModalIndex'));
            if (modal) modal.hide();
        });
    });

    function loadData(){
        _empModalCache = {};
        _scrollPage = 1;
        _scrollHasMore = true;
        _scrollLoading = false;
        $(window).off('scroll.dues');

        var payTypeVal = ($('#dp_pay_type').val() || '').trim();

        get_data('{$get_page_url}',{
            page: 1,
            branch_no: $('#dp_branch_no').val(),
            emp_no: $('#dp_emp_no').val(),
            the_month: $('#txt_the_month').val(),
            pay_type: payTypeVal || null
        }, function(data){
            $('#container').html(data);
            reBind();
        }, 'html');
    }

    function search(){
        loadData();
    }
    
    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val', '');
        $('#txt_the_month').val('');
        $('#dp_pay_type').val('');
        $('#dp_pay_type_display').val('');
        loadData();
    }

    function exportExcel(){
        var payTypeVal = ($('#dp_pay_type').val() || '').trim();
        var params = {
            branch_no: $('#dp_branch_no').val() || '',
            emp_no:    $('#dp_emp_no').val() || '',
            the_month: $('#txt_the_month').val() || '',
            pay_type:  payTypeVal || ''
        };
        var query = [];
        for (var k in params) {
            if (params[k] !== '') query.push(k + '=' + encodeURIComponent(params[k]));
        }
        var url = '{$export_excel_url}';
        // نستخدم POST عبر form مخفي عشان يشتغل الـ download
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.style.display = 'none';
        for (var k in params) {
            if (params[k] !== '') {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = k;
                input.value = params[k];
                form.appendChild(input);
            }
        }
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    /* ═══ ترحيل المستحقات من الرواتب ═══ */
    var _migrateCheckUrl = '{$migrate_check_url}';
    var _migrateRunUrl   = '{$migrate_run_url}';
    var _migrateMonth    = null;
    var _migrateConNo    = null;
    var _migratePayType  = null;

    // ربط القيد بالبند
    var _migrateMap = { '260': 8, '261': 7 };

    function openMigrateModal() {
        $('#migrateStep1').show();
        $('#migrateStep2').hide();
        $('#migrateLoading').hide();
        $('#btnMigrateCheck').show();
        $('#btnMigrateRun').hide();
        $('#migrate_month').val('');
        $('#migrate_con_no').val('260');
        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('migrateModal'));
        modal.show();
    }

    function migrateCheck() {
        var month = $('#migrate_month').val().trim();
        if (month.length !== 6 || !/^\d{6}$/.test(month)) {
            danger_msg('تحذير', 'أدخل الشهر بصيغة YYYYMM');
            return;
        }
        var conNo = $('#migrate_con_no').val();
        var payType = _migrateMap[conNo];
        var conName = $('#migrate_con_no option:selected').text();

        _migrateMonth   = month;
        _migrateConNo   = conNo;
        _migratePayType = payType;

        $('#migrateStep1').hide();
        $('#migrateStep2').hide();
        $('#migrateLoading').show();
        $('#btnMigrateCheck').hide();
        $('#btnMigrateRun').hide();

        get_data(_migrateCheckUrl, { the_month: month, con_no: conNo, pay_type: payType }, function(data) {
            $('#migrateLoading').hide();
            var res = (typeof data === 'string') ? JSON.parse(data) : data;

            if (!res.ok || !res.data) {
                $('#migrateStep2').html('<div class="alert alert-danger text-center">' + (res.msg || 'خطأ في الفحص') + '</div>').show();
                $('#btnMigrateCheck').show();
                return;
            }

            var d       = res.data;
            var src     = parseInt(d.SRC_COUNT) || 0;
            var mig     = parseInt(d.MIGRATED_COUNT) || 0;
            var notMig  = parseInt(d.NOT_MIGRATED_COUNT) || 0;
            var notAmt  = parseFloat(d.NOT_MIGRATED_AMOUNT) || 0;

            var mn = month.substring(4,6) + '/' + month.substring(0,4);
            var html = '<div style="margin-bottom:0.8rem; font-size:0.82rem; color:#64748b;">';
            html += 'الشهر: <strong>' + mn + '</strong> &nbsp;|&nbsp; ' + conName + ' (القيد: <strong>' + conNo + '</strong> / البند: <strong>' + payType + '</strong>)';
            html += '</div>';

            if (src === 0) {
                html += '<div class="alert alert-light text-center py-3">';
                html += '<i class="fa fa-inbox fa-2x text-muted d-block mb-2"></i>';
                html += 'لا توجد بيانات قابلة للترحيل لهذا الشهر</div>';
                $('#btnMigrateCheck').show();
            } else if (notMig === 0) {
                html += '<div class="alert alert-success text-center py-3">';
                html += '<i class="fa fa-check-circle fa-2x d-block mb-2"></i>';
                html += 'تم ترحيل جميع البيانات مسبقاً<br><strong>' + src + '</strong> سجل</div>';
                $('#btnMigrateCheck').show();
            } else {
                // بطاقات الإحصائيات
                html += '<div style="display:flex; gap:0.4rem; margin-bottom:0.8rem;">';
                html += _migrateCard('المصدر', src, '#1e293b', '#f1f5f9');
                html += _migrateCard('مرحل', mig, '#16a34a', '#f0fdf4');
                html += _migrateCard('غير مرحل', notMig, '#dc2626', '#fef2f2');
                html += '</div>';

                // شريط التقدم
                var pct = Math.round((mig / src) * 100);
                html += '<div style="background:#e2e8f0; border-radius:4px; height:6px; margin-bottom:0.8rem;">';
                html += '<div style="width:' + pct + '%; background:#16a34a; height:100%; border-radius:4px;"></div></div>';

                html += '<div class="alert alert-warning text-center py-3" style="border-radius:8px;">';
                html += '<i class="fa fa-exclamation-triangle fa-lg d-block mb-2"></i>';
                html += 'يوجد <strong>' + notMig + '</strong> سجل غير مرحل';
                html += '<br><span style="font-size:0.8rem;">بإجمالي <strong>' + _nf(notAmt) + '</strong></span>';
                html += '<br><span style="font-size:0.75rem; color:#92400e;">هل تريد ترحيلهم الآن؟</span>';
                html += '</div>';
                $('#btnMigrateRun').show();
            }

            $('#migrateStep2').html(html).show();
        }, 'json');
    }

    function migrateRun() {
        if (!_migrateMonth) return;
        if (!confirm('هل أنت متأكد من ترحيل البيانات غير المرحلة؟')) return;

        $('#btnMigrateRun').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> جاري الترحيل...');

        get_data(_migrateRunUrl, { the_month: _migrateMonth, con_no: _migrateConNo, pay_type: _migratePayType }, function(data) {
            var res = (typeof data === 'string') ? JSON.parse(data) : data;
            $('#btnMigrateRun').hide();

            if (res.ok) {
                var html = '<div class="alert alert-success text-center py-3" style="border-radius:8px;">';
                html += '<i class="fa fa-check-circle fa-2x d-block mb-2"></i>';
                html += 'تم الترحيل بنجاح<br><strong>' + res.inserted + '</strong> سجل تم ترحيله';
                html += '</div>';
                $('#migrateStep2').html(html);
                loadData();
            } else {
                var html = '<div class="alert alert-danger text-center py-3">';
                html += '<i class="fa fa-times-circle fa-lg d-block mb-2"></i>' + (res.msg || 'فشل الترحيل');
                html += '</div>';
                $('#migrateStep2').html(html);
                $('#btnMigrateRun').prop('disabled', false).html('<i class="fa fa-check"></i> ترحيل الآن').show();
            }
        }, 'json');
    }

    function _migrateCard(label, value, color, bg) {
        return '<div style="flex:1; text-align:center; padding:0.5rem; background:' + bg + '; border-radius:8px;">'
             + '<div style="font-size:0.65rem; color:' + color + ';">' + label + '</div>'
             + '<div style="font-size:1.1rem; font-weight:800; color:' + color + ';">' + value + '</div></div>';
    }

    // صار Cancel وليس Delete
    function delete_prototype(a,serial){
        if(confirm('هل متأكد من عملية إلغاء الدفعة؟')){
            get_data('{$delete_url}',{serial:serial},function(data){
                if(data == '1' || parseInt(data) > 0){
                    success_msg('رسالة','تم الإلغاء بنجاح.');
                    $(a).closest('tr').fadeOut(300, function(){
                        $(this).remove();
                    });
                }else{
                    danger_msg('تحذير.',data);
                }
            },'html');
        }
    }
</script>
SCRIPT;

sec_scripts($scripts);
?>