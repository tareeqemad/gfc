<?php
/**
 * Payment Request — Index v2
 */
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';

$create_url   = base_url("$MODULE_NAME/$TB_NAME/create");
$batch_url    = base_url("$MODULE_NAME/$TB_NAME/batch");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$approve_url  = base_url("$MODULE_NAME/$TB_NAME/approve");
$pay_url      = base_url("$MODULE_NAME/$TB_NAME/do_pay");
$delete_url   = base_url("$MODULE_NAME/$TB_NAME/delete");

echo AntiForgeryToken();
?>

<style>
:root{--pr-a:#2563eb;--pr-s:#059669;--pr-w:#d97706;--pr-d:#dc2626;--pr-r:14px;--pr-sh:0 4px 24px rgba(15,23,42,.07)}
.pr-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-top:1.25rem;margin-bottom:1.25rem}
.pr-head h1{font-size:1.4rem;font-weight:800;color:#1e293b;margin:0;display:flex;align-items:center;gap:.6rem}
.pr-head .hico{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1rem;box-shadow:0 4px 14px rgba(37,99,235,.35)}
.pr-panel{background:#fff;border:1px solid #e2e8f0;border-radius:var(--pr-r);box-shadow:var(--pr-sh);overflow:hidden;margin-bottom:1.25rem}
.pr-panel-hd{display:flex;align-items:center;justify-content:space-between;padding:.75rem 1.25rem;background:linear-gradient(135deg,#1e293b,#334155);color:#fff}
.pr-panel-hd .t{font-weight:700;font-size:.9rem;display:flex;align-items:center;gap:.4rem}
.btn-new{background:linear-gradient(135deg,#059669,#10b981);color:#fff;border:0;border-radius:10px;padding:.4rem 1rem;font-weight:700;font-size:.82rem;display:inline-flex;align-items:center;gap:.35rem;box-shadow:0 3px 12px rgba(5,150,105,.35);transition:.2s}
.btn-new:hover{color:#fff;transform:translateY(-1px);box-shadow:0 5px 18px rgba(5,150,105,.45)}
.btn-batch{background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;border:0;border-radius:10px;padding:.4rem 1rem;font-weight:700;font-size:.82rem;display:inline-flex;align-items:center;gap:.35rem;box-shadow:0 3px 12px rgba(124,58,237,.35);transition:.2s}
.btn-batch:hover{color:#fff;transform:translateY(-1px);box-shadow:0 5px 18px rgba(124,58,237,.45)}
.pr-panel-bd{padding:1rem 1.25rem}
.pr-panel-bd label{font-weight:600;font-size:.75rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.3rem}
.pr-panel-ft{display:flex;gap:.5rem;padding:0 1.25rem 1rem}
.pr-panel-ft .btn{border-radius:10px;font-weight:600;padding:.4rem 1.1rem;font-size:.82rem}
.pr-tbl-wrap{background:#fff;border:1px solid #e2e8f0;border-radius:var(--pr-r);box-shadow:var(--pr-sh);overflow:hidden;min-height:80px}
.pr-tbl-wrap .ld{text-align:center;padding:2.5rem 1rem;color:#94a3b8}
.select2-container--default .select2-selection--single{border-radius:10px!important;border:1px solid #e2e8f0!important;height:38px!important}
.select2-container--default .select2-selection--single .select2-selection__arrow{top:5px!important}
.select2-dropdown{border-radius:10px!important;box-shadow:0 8px 40px rgba(15,23,42,.12)!important}
.select2-results__option--highlighted{background:var(--pr-a)!important}
</style>

<!-- HEAD -->
<div class="pr-head">
    <h1><span class="hico"><i class="fa fa-credit-card"></i></span> <?= $title ?></h1>
    <ol class="breadcrumb mb-0" style="font-size:.8rem">
        <li class="breadcrumb-item"><a href="javascript:void(0);">صرف الرواتب والمستحقات</a></li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>
</div>

<!-- SEARCH -->
<div class="pr-panel">
    <div class="pr-panel-hd">
        <span class="t"><i class="fa fa-filter"></i> بحث وتصفية</span>
        <?php if (HaveAccess($create_url)): ?>
            <a class="btn-batch" href="<?= $batch_url ?>"><i class="fa fa-clone"></i> دفعة جماعية</a>
            <a class="btn-new" href="<?= $create_url ?>"><i class="fa fa-plus-circle"></i> طلب جديد</a>
        <?php endif; ?>
    </div>
    <form id="<?= $TB_NAME ?>_form">
        <div class="pr-panel-bd">
            <div class="row g-3">
                <?php if ($this->user->branch == 1) { ?>
                <div class="col-md-2">
                    <label>المقر</label>
                    <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                        <option value="">— الكل —</option>
                        <?php foreach ($branches as $row): ?>
                            <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php } else { ?>
                    <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                <?php } ?>
                <div class="col-md-3">
                    <label>الموظف</label>
                    <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                        <option value="">— الكل —</option>
                        <?php foreach ($emp_no_cons as $row): ?>
                            <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'].' - '.$row['EMP_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>الشهر</label>
                    <input type="text" name="the_month" id="txt_the_month" class="form-control" placeholder="YYYYMM" maxlength="6" style="border-radius:10px">
                </div>
                <div class="col-md-2">
                    <label>الحالة</label>
                    <select name="status" id="dp_status" class="form-control" style="border-radius:10px">
                        <option value="">— الكل —</option>
                        <?php foreach ($status_cons as $row): ?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="pr-panel-ft">
            <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="fa fa-search"></i> استعلام</button>
            <button type="button" onclick="javascript:clear_form();" class="btn btn-outline-secondary"><i class="fa fa-eraser"></i> مسح</button>
        </div>
    </form>
</div>

<!-- TABLE -->
<div class="pr-tbl-wrap">
    <div id="container"><div class="ld"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i><p class="mt-2 mb-0" style="font-size:.82rem">جاري التحميل...</p></div></div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    function reBind(){
        if(typeof initFunctions == 'function') initFunctions();
        initTooltips();
    }

    function initTooltips(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
    }

    $(function(){
        $('.sel2:not("[id^=\'s2\']")').select2();
        initTooltips();
        loadData();

        $('#txt_the_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false
        });

        $('#{$TB_NAME}_form').on('keydown', function(e){
            if(e.keyCode === 13){ e.preventDefault(); search(); }
        });
    });

    function loadData(){
        get_data('{$get_page_url}',{
            page: 1,
            branch_no: $('#dp_branch_no').val(),
            emp_no: $('#dp_emp_no').val(),
            the_month: $('#txt_the_month').val(),
            status: $('#dp_status').val() || null
        }, function(data){
            $('#container').html(data);
            reBind();
        }, 'html');
    }

    function search(){ loadData(); }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val', '');
        $('#txt_the_month').val('');
        $('#dp_status').val('');
        loadData();
    }

    function approve_req(req_id){
        if(!confirm('هل تريد اعتماد هذا الطلب؟')) return;
        get_data('{$approve_url}',{req_id: req_id},function(data){
            if(data == '1'){
                success_msg('رسالة','تم الاعتماد بنجاح');
                loadData();
            } else {
                danger_msg('تحذير', data);
            }
        },'html');
    }

    function pay_req(req_id){
        if(!confirm('هل تريد صرف هذا الطلب؟')) return;
        get_data('{$pay_url}',{req_id: req_id},function(data){
            if(data == '1'){
                success_msg('رسالة','تم الصرف بنجاح');
                loadData();
            } else {
                danger_msg('تحذير', data);
            }
        },'html');
    }

    function cancel_req(a, req_id){
        var note = prompt('سبب الإلغاء (اختياري):', 'إلغاء');
        if(note === null) return;
        get_data('{$delete_url}',{req_id: req_id, cancel_note: note || 'إلغاء'},function(data){
            if(data == '1'){
                success_msg('رسالة','تم الإلغاء بنجاح');
                loadData();
            } else {
                danger_msg('تحذير', data);
            }
        },'html');
    }
</script>
SCRIPT;

sec_scripts($scripts);
?>
