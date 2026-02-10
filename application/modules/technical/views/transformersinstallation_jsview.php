<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 22/05/18
 * Time: 09:37 ص
 */

?>

//<script>

    reBind();

    function reBind() {
        updateExpectedLoad();
        updateExpectedBLM();
        updateExpectedALM();
        updateVd();
        updateVDMV();

        $('input',$('#lvnTable')).not('input[name="tld_name[]"]').css({'direction':'ltr'});

    }

    function reBindAfterInsert(tr) {


        if ($('table', $(tr)).length > 0) {

            var table = $('table', $(tr));

            if ($('tbody tr', $(table)).length > 0) {
                $("tbody", $(table)).find("tr:gt(0)").remove();
            }
        }

        $('td[data-expected]', $(tr)).text('');

        var td_d_ser = $('input[name*="td_d_ser"]', $(tr));
        var td_d_ser_table = $(td_d_ser).closest('#toolsTbl');
        var td_d_ser_tr = $(td_d_ser).closest('#toolsTbl tr[data-root]');

        if (td_d_ser.length > 0) {
            var td_d_ser_tr_index = $('#toolsTbl tr[data-root]').index(td_d_ser_tr) + 1;

            $('table input,table select', $(td_d_ser_tr)).each(function () {

                var indexOfPractise = $(this).attr('name').indexOf('[');
                $(this).attr('name', $(this).attr('name').substr(0, indexOfPractise) + '[' + td_d_ser_tr_index + '][]');
            });
        }


        var tbd_d_ser = $('input[name*="tbd_d_ser"]', $(tr));
        var tbd_d_ser_table = $(tbd_d_ser).closest('#eltTable');
        var tbd_d_ser_tr = $(tbd_d_ser).closest('#eltTable tr[data-root]');

        if (tbd_d_ser.length > 0) {
            var tbd_d_ser_tr_index = $('#eltTable tr[data-root]').index(tbd_d_ser_tr) + 1;

            $('table input,table select', $(tbd_d_ser_tr)).each(function () {

                var indexOfPractise = $(this).attr('name').indexOf('[');
                $(this).attr('name', $(this).attr('name').substr(0, indexOfPractise) + '[' + tbd_d_ser_tr_index + '][]');
            });

        }

        var tad_d_ser = $('input[name*="tad_d_ser"]', $(tr));
        var tad_d_ser_table = $(tad_d_ser).closest('#aeltTbl');
        var tad_d_ser_tr = $(tad_d_ser).closest('#aeltTbl tr[data-root]');

        if (tad_d_ser.length > 0) {
            var tad_d_ser_tr_index = $('#aeltTbl tr[data-root]').index(tad_d_ser_tr) + 1;

            $('table input,table select', $(tad_d_ser_tr)).each(function () {

                var indexOfPractise = $(this).attr('name').indexOf('[');
                $(this).attr('name', $(this).attr('name').substr(0, indexOfPractise) + '[' + tad_d_ser_tr_index + '][]');
            });
        }

    }

    function updateExpectedLoad() {

        $(' input[name*="td_expected_load"]').keyup(function () {

            updateExpectedLoadDoAction($(this), true);
        });

        $('select[name="td_kva_rating[]"]').change(function () {

            updateExpectedLoadDoAction($(this), false);
        });
    }

    function updateExpectedLoadDoAction(obj, isExpected) {

        var tr = /*$(this).attr('name') == 'td_expected_load[]'*/ isExpected ? $(obj).closest('table:first-child').closest('tr') : $(obj).closest('tr');
        var dataExpected = $('td[data-expected]', $(tr));

        var sumOfExpectedLoad = 0;
        var kvaRating = $('select[name="td_kva_rating[]"]', $(tr)).val();

        $('input[name*="td_expected_load"]', $(tr)).each(function () {
            sumOfExpectedLoad += $(this).val() == '' ? 0 : parseFloat($(this).val());

        });


        if (kvaRating == '')
            $(dataExpected).text('');
        else
            $(dataExpected).text(((sumOfExpectedLoad / (kvaRating * 1.44)) * 100).toFixed(2) + '%');
    }

    function updateExpectedBLMDoAction(obj, isExpected) {

        var tr = isExpected ? $(obj).closest('table:first-child').closest('tr') : $(obj).closest('tr');

        var dataExpected = $('td[data-expected]', $(tr));

        var sumOfr = 0;
        var sumOfs = 0;
        var sumOft = 0;
        var kvaRating = $('select[name="tbd_kva_rating[]"]', $(tr)).val();

        $('input[name*="tbd_r["]', $(tr)).each(function () {
            sumOfr += $(this).val() == '' ? 0 : parseFloat($(this).val());
        });

        $('input[name*="tbd_s["]', $(tr)).each(function () {
            sumOfs += $(this).val() == '' ? 0 : parseFloat($(this).val());
        });

        $('input[name*="tbd_t["]', $(tr)).each(function () {
            sumOft += $(this).val() == '' ? 0 : parseFloat($(this).val());
        });



        var maxOfrst = Math.max(sumOfr, sumOfs, sumOft);

        if (kvaRating == '')
            $(dataExpected).text('');
        else
            $(dataExpected).text(((maxOfrst / (kvaRating * 1.44)) * 100).toFixed(2) + '%');


    }

    function updateExpectedBLM() {
        $('select[name="tbd_kva_rating[]"]').change(function () {
            updateExpectedBLMDoAction($(this), false);
        });

        $('input[name*="tbd_r"], input[name*="tbd_s"], input[name*="tbd_t"]').keyup(function () {
            updateExpectedBLMDoAction($(this), true);
        });

    }

    function updateExpectedALM() {

        $('select[name="tad_kva_rating[]"]').change(function () {
            updateExpectedALMAction($(this), false);
        });

        $('input[name*="tad_r["], input[name*="tad_s["], input[name*="tad_t["]').keyup(function () {
            updateExpectedALMAction($(this), true);
        });


    }

    function updateExpectedALMAction(obj,isExpected) {

        var tr = /*$(this).attr('name') == 'td_expected_load[]'*/ isExpected ? $(obj).closest('table:first-child').closest('tr') : $(obj).closest('tr');

        var dataExpected = $('td[data-expected]', $(tr));

        var sumOfr = 0;
        var sumOfs = 0;
        var sumOft = 0;
        var kvaRating = $('select[name="tad_kva_rating[]"]', $(tr)).val();

        $('input[name*="tad_r["]', $(tr)).each(function () {
            sumOfr += $(this).val() == '' ? 0 : parseFloat($(this).val());
        });

        $('input[name*="tad_s["]', $(tr)).each(function () {
            sumOfs += $(this).val() == '' ? 0 : parseFloat($(this).val());
        });

        $('input[name*="tad_t["]', $(tr)).each(function () {
            sumOft += $(this).val() == '' ? 0 : parseFloat($(this).val());
        });

        var maxOfrst = Math.max(sumOfr, sumOfs, sumOft);

        if (kvaRating == '')
            $(dataExpected).text('');
        else
            $(dataExpected).text(((maxOfrst / (kvaRating * 1.44)) * 100).toFixed(2) + '%');

    }

    function updateVd() {
        $('input[name="tld_llv[]"], input[name="tlad_llv[]"]').keyup(function () {

            var tr = $(this).closest('tr');


            var llv = $('input[name="tld_llv[]"]', $(tr)).val();
            var allv = $('input[name="tlad_llv[]"]', $(tr)).val();

            if (llv == '')
                $('input[name="tld_vd[]"]', $(tr)).val('');
            else
                $('input[name="tld_vd[]"]', $(tr)).val(((1 - (llv / 400)) * 100).toFixed(2));

            if (allv == '')
                $('input[name="tlad_vd[]"]', $(tr)).val('');
            else
                $('input[name="tlad_vd[]"]', $(tr)).val(((1 - (allv / 400)) * 100).toFixed(2));

        });
    }


    function getProjectDetails() {

        var project_tec = $('#project_tec').val().toLowerCase();
        if (project_tec == '') return;


        if (!( project_tec.match("^t") || project_tec.match("^it") || project_tec.match("^pm") || project_tec.match("^p")|| project_tec.match("^rsp") )) {
            alert('رقم المشروع غير صحيح');
            return;
        }


        get_data('<?= base_url('projects/projects/publicGetProjectByTec') ?>', {project_tec: project_tec}, function (data) {

            $('#project_tec_name').val(data.PROJECT_NAME);
            $('#project_tec_branch').val(data.BRANCH_NAME);
            $('#project_tec_user').val(data.ENTRY_USER_NAME);
            $('#reconductoring_cost').val(data.TOTAL);
        });


    }

    function deleteTransformData(a, id, url) {

        var table = $(a).closest('table');
        var action = table.attr('action');

        if (id <= 0 && $('tbody:first>tr', $(table)).length > 1) {
            $(a).closest('tr').remove();
        } else {
            if (confirm('هل تريد حذف السند؟!')) {
                ajax_delete_any(url, {id: id, action: action}, function (data) {
                    if (data == '1') {
                        $(a).closest('tr').remove();
                        success_msg('رسالة', 'تم حذف سجل بنجاح ..');
                    }
                });
            }

        }

    }

    $('button[data-action="submit"]').click(function (e) {
        e.preventDefault();

        var project_tec = $('#project_tec').val();
        if (!( project_tec.match("^T") || project_tec.match("^IT") || project_tec.match("^PM") || project_tec.match("^P"))) {
            alert('رقم المشروع غير صحيح');
            return;
        }

        if (confirm('هل تريد حفظ السند ؟!')) {

            var form = $(this).closest('form');
            ajax_insert_update(form, function (data) {

                success_msg('رسالة', 'تم حفظ البيانات بنجاح ..');

                reload_Page();

            }, 'html');
        }
    });

    //calc Vd% before reconductoring
    function updateVDMV() {
        $('input[name="mc_vw_weakest_node[]"').keyup(function () {
            var tr = $(this).closest('tr');
            var divideBy = 22;
            $('input[name="mc_vd[]"]', $(tr)).val(((1 - ($(this).val() / divideBy)) * 100).toFixed(2));
        });
    }

    $('#electricityh').keyup(function () {

        //$('#kwsaving').val((($('#kw_saving').val() * $('#electricityh').val()) * 30 * 0.5).toFixed(2));

        if ($('#kwsaving').val() > 0 && $('#reconductoring_cost').val() > 0)
            $('#payback').val(($('#reconductoring_cost').val() / $('#kwsaving').val()).toFixed(0));
        else $('#payback').val(0);
    });


    if ('<?= $action ?>' == 'get') {
        $('select[name="td_kva_rating[]"], input[name*="td_expected_load"]').keyup();
        $('select[name="tbd_kva_rating[]"], input[name*="tbd_r"], input[name*="tbd_s"], input[name*="tbd_t"]').keyup();
        $('select[name="tad_kva_rating[]"], input[name*="tad_r"], input[name*="tad_s"], input[name*="tad_t"]').keyup();
        $('input[name="tld_llv[]"], input[name="tlad_llv[]"]').keyup();
        $('#vw_weakest_node').keyup();
        $('#electricityh').keyup();
        $('input[name="mc_vw_weakest_node[]"').keyup();



        $('#kw_saving,#electricityh,input[name*="tld_kw_loss[]"],input[name*="tlad_kw_loss[]"]').keyup(calcLossKWCost);
        $('#kw_saving,#electricityh,input[name*="tld_kw_loss[]"],input[name*="tlad_kw_loss[]"]').change(calcLossKWCost);


    }

    function calcLossKWCost() {

        var sumOftld_kw_loss = 0;
        $('input[name*="tld_kw_loss[]"]').each(function () {
            sumOftld_kw_loss += $(this).val() == '' ? 0 : parseFloat($(this).val());
        });

        var sumOftlad_kw_loss = 0;
        $('input[name*="tlad_kw_loss[]"]').each(function () {
            sumOftlad_kw_loss += $(this).val() == '' ? 0 : parseFloat($(this).val());
        });


        $('#kw_saving').val(sumOftld_kw_loss - sumOftlad_kw_loss);

        $('#kwsaving').val( $('#kw_saving').val()*$('#electricityh').val()*30);
        calcNetProfit();
    }

    function calcCostOfKWH() {
        $('#cost_kwh').val( $('#average_kwh').val()*0.5);
        calcNetProfit();
    }

    function calcNetProfit() {

        var val = $('#customers').val()*$('#cost_kwh').val()-$('#loss_kw_cost').val();
        $('#net_profit').val(isNaN(val) ? 0 :   val.toFixed(0));
        calcPayback();
    }

    function calcPayback() {

        var val = ($('#reconductoring_cost').val()/$('#net_profit').val());
        $('#payback').val( isNaN(val) ? 0 :   val.toFixed(0));
    }

    function print_report(ser) {

        _showReport('http://gs/gfc/JsperReport/showreport?sys=technical/loadFlow&report_type=pdf&report=technical_efficiency_developement_projects&p_ser=' + ser + '');
    }

    function print_report2(){

        var rep_type = $('input[name=rep_type_id]:checked').val();
        if(rep_type ==null) {rep_type='pdf';}

        _showReport('http://gs/gfc/JsperReport/showreport?sys=technical/loadFlow&report_type='+rep_type+'&report=technical_efficiency_developement_p&p_from_date='+$('#txt_from_date').val()+'&p_to_date='+$('#txt_to_date').val()+'&p_id='+$('#txt_request_code').val()+'');
    }

    //</script>