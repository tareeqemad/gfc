<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/08/23
 * Time: 14:40
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'Meeting_lecturer';

$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$back_url= base_url("$MODULE_NAME/$TB_NAME/index");
$get_job_title_url= base_url("$MODULE_NAME/$TB_NAME/public_get_job_title");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

if ($HaveRs){
    $edit = 1;
}else{
    $edit = 0;
}

?>

    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">النظام الإداري</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex py-3">
                    <div class="mb-0 flex-grow-1 card-title">
                        <?= $title ?>
                    </div>
                    <div class="flex-shrink-0">
                        <a class="btn btn-info" href="<?= $back_url ?>"><i class="fa fa-reply"></i> </a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">


                    <div class="panel panel-primary">
                        <div class="tab-menu-heading border-bottom-0">
                            <div class="tabs-menu4 border-bottomo-sm">
                                <!-- Tabs -->
                                <nav class="nav d-sm-flex d-block">
                                    <a class="nav-link border border-bottom-0 br-sm-5 me-2 active" data-bs-toggle="tab" href="#tab25">
                                        الحضور
                                    </a>
                                    <a class="nav-link border border-bottom-0 br-sm-5 me-2" data-bs-toggle="tab" href="#tab26">
                                        جدول الأعمال
                                    </a>
                                    <a class="nav-link border border-bottom-0 br-sm-5 me-2" data-bs-toggle="tab" href="#tab27">
                                        التوصيات
                                    </a>
                                </nav>
                            </div>
                        </div>

                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane active " id="tab25">
                                    <?php require_once ('tabs_meeting_lecturer/attendance.php')?>
                                </div>
                                <div class="tab-pane" id="tab26">
                                    <?php require_once ('tabs_meeting_lecturer/schedule_work.php')?>
                                </div>
                                <div class="tab-pane" id="tab27">
                                    <?php require_once ('tabs_meeting_lecturer/recommendations.php')?>
                                </div>
                            </div>

                        </div>

                    </div>

                    </form>

                    <div id="container">
                    </div>

                </div>
            </div>
        </div>
    </div>


<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    var count_d = 0;
    var count_s = 0;
    var count_r = 0;
    

    
    $('.sel2').select2();
    reBind(1);
    
    $('#txt_injury_date ,#txt_work_start_date,#txt_meeting_date').datetimepicker({
        format: 'DD/MM/YYYY',
        minViewMode: "days",
        pickTime: false
    });

    $('#txt_meeting_time').datetimepicker({
        datepicker:false,
        format:'hh:mm'
    });

    function reBind(s) {
        if (s == undefined) {
            s = 0;
        }
        
        if (s) {
            
        $('.sel22:not("[id^=\'s2\']")').select2();

        $('select[name="emp_no[]"]').select2();
        
        $('select[name="emp_no[]"]').change(function(){
          
            var emp_no =$(this).val();

            var description= $(this).closest('tr').find('input[name="description[]"]');

        
            if(emp_no == 0 ){
                description.val('');
                return 0;
            }
            
            get_data('{$get_job_title_url}',{emp_no:emp_no},function(data){
                
                if (data.length == 1){
                    var item= data[0];
                    description.val(item.JOB_TITLE);
                }else{
                    description.val('');
                }
            });            
        
        });
        
        }
    }

    //اضافة سجل جديد
    function addRow_attendance(){
        var emp_no_cons_options = '{$emp_no_cons_options}';
        var attendance_status_options = '{$attendance_status_options}';
        var rowCount = $('#attendance tbody tr').length;

        if(rowCount == 0){
            count_d = count_d+1;
        }else {
            count_d = rowCount+1
        }
        var html ='<tr><td><input name="ser_d[]" id="ser_d'+count_d+'" class="form-control" value="0" style="text-align: center" readonly> </td>' +
         ' <td><select name="emp_no[]" id="emp_no'+count_d+'" class="form-control sel22">'+emp_no_cons_options+'</select></td>' +
         ' <td><input  name="description[]"  class="form-control" id="description'+count_d+'" style="text-align: center"/></td>' +
         ' <td><select name="attendance_status[]" id="attendance_status'+count_d+'" class="form-control sel22">'+attendance_status_options+'</select></td>' +
         ' <td class="text-center"><a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a></td>' +
         '</tr>';
        $('#attendance tbody').append(html);
         reBind(count_d);
    }

    function addRow_schedule_work(){
        var rowCount = $('#schedule_work tbody tr').length;

        if(rowCount == 0){
            count_s = count_s+1;
        }else {
            count_s = rowCount+1
        }
        var html ='<tr><td><input name="ser_s[]" id="ser_s'+count_s+'" class="form-control" value="0" style="text-align: center" readonly> </td>' +
         ' <td><textarea  name="item_no_s[]" rows="4" class="form-control" id="item_no_s'+count_s+'" ></textarea></td>' +
         ' <td><input  name="placement_party[]" class="form-control" id="placement_party'+count_s+'"  style="text-align: center" /></td>' +
         ' <td><input  name="category[]"  class="form-control month" id="category'+count_s+'" style="text-align: center"/></td>' +
         ' <td><textarea  name="notes[]" rows="4" class="form-control" id="notes'+count_s+'" ></textarea></td>' +
         ' <td class="text-center"><a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a></td>' +
         '</tr>';
        $('#schedule_work tbody').append(html);
         reBind(count_s);
    }

    function addRow_recommendations(){
        var rowCount = $('#recommendations tbody tr').length;

        if(rowCount == 0){
            count_r = count_r+1;
        }else {
            count_r = rowCount+1
        }
        var html ='<tr><td><input name="ser_r[]" id="ser_r'+count_r+'" class="form-control" value="0" style="text-align: center" readonly> </td>' +
         ' <td><input  name="item_no[]" class="form-control" id="item_no'+count_r+'"  style="text-align: center" /></td>' +
         ' <td><textarea  name="rationale_discussion[]" rows="10" class="form-control" id="rationale_discussion'+count_r+'" ></textarea></td>' +
         ' <td><textarea  name="decision[]" rows="10" class="form-control" id="decision'+count_r+'" ></textarea></td>' +
         ' <td class="text-center"><a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a></td>' +
         '</tr>';
        $('#recommendations tbody').append(html);
         reBind(count_r);
    }

    function  remove_tr(obj){
        var tr = obj.closest('tr');
        $(tr).closest('tr').css('background','tomato');
        $(tr).closest('tr').fadeOut(800,function(){
            $(this).remove();
        });
    }
    
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الطلب ؟!';
        if(confirm(msg)){
    
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data));
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

    </script>
SCRIPT;

if($HaveRs) {
    $scripts = <<<SCRIPT
    {$scripts}

<script type="text/javascript">
    function adopt_(no){
        if(no==10 ) var msg= 'هل تريد بالتأكيد اعتماد المحضر ؟!';

        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}"};
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');

                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }else{

        }
    }

    </script>
SCRIPT;
}
sec_scripts($scripts);
?>