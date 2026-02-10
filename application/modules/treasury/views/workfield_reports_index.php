<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 01/08/2022
 * Time: 12:43 PM
 */

$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';
$report_url = base_url("JsperReport/showreport?sys=financial/field_collection");
$get_users_by_branch = base_url("$MODULE_NAME/$TB_NAME/public_get_users_by_branch");
echo AntiForgeryToken();
?>
<style>
    .rep_warning { border: 2px solid red;}
</style>

<div class="row">
    <div class="toolbar">
        <div class="caption"> تقارير التحصيل الميداني </div>
    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >

            <fieldset>
                <legend>التقارير</legend>
                <div class="modal-body inline_form">
                    <div class="form-group rp col-sm-2" id="rep_id">
                        <label class="control-label">اسم التقرير</label>
                        <div>
                            <select name="rep_id" class="form-control" id="dp_rep_id">
                                <option value="0">_______________</option>
                                <option value="1">تفاصيل الإيصالات (مرحل - ملغي)</option>
                                <option value="2">إجمالي الإيصالات</option>
                                <option value="3">الإيصالات الملغية</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label"> شركة خارجية / موظف </label>
                        <div class="">
                            <select name="user_type" id="dp_user_type" class="form-control select2" >
                                <option value="">----------</option>
                                <?php foreach($user_type as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

            </fieldset>

            <fieldset>
                <legend>معايير البحث</legend>
                <div class="modal-body inline_form">

                    <?php if($user_branch == 1) { ?>
                        <div class="form-group rp col-sm-1 op" id="branch_id" style="display:none;">
                            <label class="control-label">المقر</label>
                            <div>
                                <select name="branch" class="form-control branch" id="dp_branch">
                                    <option value="">جميع المقرات</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php }else { ?>
                        <input type="hidden" class="branch" id="dp_branch" value="<?=$user_branch?>" />
                    <?php } ?>


                    <?php if($user_branch == 1) { ?>
                        <div class="form-group col-sm-2 op" id="user_id" style="display:none;">
                            <label class="control-label">المحصل</label>
                            <div>
                                <select name="user" id="dp_user" class="form-control">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    <?php }else { ?>
                        <div class="form-group col-sm-2 op" id="user_id" style="display:none;">
                            <label class="control-label">المحصل</label>
                            <div>
                                <select name="user" id="dp_user" class="form-control" >
                                    <option value="">...............</option>
                                    <?php foreach($users as $row) :?>
                                        <option value="<?=$row['NO']?>"><?=$row['NO'].': '.$row['NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group col-sm-2 op" id="date_from_id" style="display:none;">
                        <label class="control-label">من تاريخ</label>
                        <div>
                            <input type="text" name="from_date" data-type="date" data-date-format="YYYY/MM/DD" data-val="true" id="txt_from_date" class="form-control" data-val-regex="التاريخ غير صحيح!" />
                            <span class="field-validation-valid" data-valmsg-for="to_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2 op" id="date_to_id" style="display:none;">
                        <label class="control-label">الى تاريخ</label>
                        <div>
                            <input type="text" name="to_date" data-type="date" data-date-format="YYYY/MM/DD" data-val="true" id="txt_to_date" class="form-control" data-val-regex="التاريخ غير صحيح!" />
                            <span class="field-validation-valid" data-valmsg-for="to_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group rp col-sm-1 op" id="from_hour_id" style="display:none;">
                        <label class="control-label">وقت التحصيل  من</label>
                        <div>
                            <input type="text" placeholder="08:00" name="from_hour" id="txt_from_hour" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group rp col-sm-1 op" id="to_hour_id" style="display:none;">
                        <label class="control-label">إلى</label>
                        <div>
                            <input type="text" placeholder="15:00"  name="to_hour" id="txt_to_hour" class="form-control" />
                        </div>
                    </div>

                    <br/><br/>

                    <div class="form-group rp col-sm-2 op" id="rep_type" style="display:none;">
                        <label class="control-label">نوع المستند</label>
                        <div>
                            <input type="radio"  name="rep_type" value="" checked="checked">
                            <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio"  name="rep_type" value="xls">
                            <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
                        </div>
                    </div>

                    <div class="form-group rp col-sm-3 op" id="show_det" style="display:none;">
                            <input type="checkbox" name="show_det" value="1" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px">عرض التفاصيل</span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                    </div>

                </div>

            </fieldset>

            <div class="modal-footer">
                <button type="button" onclick="javascript:print_rep(<?=$this->user->id?>);" class="btn btn-success">عرض التقرير<span class="glyphicon glyphicon-print"></span></button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">تفريغ الحقول</button>
            </div>

        </form>

        <div id="msg_container"></div>



        <div id="container">

        </div>

    </div>

</div>

<?php

$scripts = <<<SCRIPT
<script type="text/javascript">

    $('#dp_user').select2();

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_user').select2('val',0);
    }
    
    $('#dp_rep_id').change(function() {
        if($('#dp_user_type').val() == 0 ){
            danger_msg('تنبيه','يرجى اختيار نوع المحصل');
        } else{
            showOptions();
        }
    });
    
    $('#dp_user_type').change(function() {
        var no_val = $('#dp_branch').val();
        if($(this).val() == '0' ){
            $(".op").fadeOut();
        } else {
            
            if($(this).val() == '2'){
                var no_val = 1;
            }
            
            get_data('{$get_users_by_branch}',{no: no_val},function(data){
                    $('#dp_user').html('');
                    $('#dp_user').append('<option></option>');
                    $("#dp_user").select2('val','');
                    $.each(data,function(index, item){
                        $('#dp_user').append('<option value=' + item.NO + '>' + item.NO + ':' +item.NAME + '</option>');
                    });
            });
            
            showOptions();
        }
    });
    
    
    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#user_id,#date_from_id,#date_to_id,#from_hour_id,#to_hour_id,#branch_id,#rep_type").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#user_id,#date_from_id,#date_to_id,#from_hour_id,#to_hour_id,#branch_id,#show_det,#rep_type").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
            $("#user_id,#date_from_id,#date_to_id,#from_hour_id,#to_hour_id,#branch_id,#rep_type").fadeIn();
            break;
        }
    }
    
    function getReportUrl(user_session){
        var branch = $('#dp_branch').val();
        
        if($('#dp_user_type').val() == '2'){
             branch = 1;
        }
        
        var id=$('#dp_rep_id').val();
        var user_id = $("#dp_user").val();
		var from_time = $("#txt_from_hour").val();  
		var to_time = $("#txt_to_hour").val();  
        var from_date = $("#txt_from_date").val().replaceAll('/', '');
        var to_date = $("#txt_to_date").val().replaceAll('/', '');
        var rep_type = $('input[name=rep_type]:checked').val();
        var show_det = have_no_val($('input[name=show_det]:checked').val());
        
        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=field_collection'+rep_type+'&p_branch='+branch+'&p_time_from='+from_time+'&p_time_to='+to_time+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_user_id='+user_id+'&p_user_session='+user_session+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=field_collection_totals'+rep_type+'&p_branch='+branch+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_time_from='+from_time+'&p_time_to='+to_time+'&p_show_det='+show_det+'&p_user_session='+user_session+'';
                break;
            case "3":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=cancel_field_collection'+rep_type+'&p_branch='+branch+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_time_from='+from_time+'&p_time_to='+to_time+'&p_user_id='+user_id+'&p_user_session='+user_session+'';
                break;
        }
        if(from_date != '' && to_date != '' ){
            $('#txt_from_date').removeClass("rep_warning");
            $('#txt_to_date').removeClass("rep_warning");
            return repUrl;
            }else   {danger_msg('تنبيه','أدخل من تاريخ إلى تاريخ');
                    $('#txt_from_date').addClass("rep_warning");
                    $('#txt_to_date').addClass("rep_warning");}
    }

    function print_rep(user_session){
            var rep_url = getReportUrl(user_session);
            _showReport(rep_url);   
    }
    
    // check if var have value or null //
    function have_no_val(v) {
        if(v == null) {
            return v = '';
        }else {
            return v;
        }
    }
    
    $('#dp_branch').select2().on('change',function() {
        get_data('{$get_users_by_branch}',{no: $(this).val()},function(data){
            $('#dp_user').html('');
            $('#dp_user').append('<option></option>');
            $("#dp_user").select2('val','');
            $.each(data,function(index, item){
                $('#dp_user').append('<option value=' + item.NO + '>' + item.NAME + '</option>');
            });
        });
    });
    

</script>

SCRIPT;

sec_scripts($scripts);

?>
