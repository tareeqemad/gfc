<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 01/03/23
 * Time: 10:20 ص
 */

$MODULE_NAME = 'ratio_emp_lost';
$TB_NAME = 'Active_month_target';

$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$back_url = base_url("$MODULE_NAME/$TB_NAME/index");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
$isCreate = isset($master_tb_data) && count($master_tb_data) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $master_tb_data[0];

$date_attr = " data-type='date' data-date-format='YYYYMM' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$count=1;
$d_count=0;

if ($HaveRs) {
    $edit = 1;
} else {
    $edit = 0;
}

?>
    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">مشروع الفاقد</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex py-3">
                    <div class="mb-0 flex-grow-1 card-title">
                        الطلب
                    </div>
                    <div class="flex-shrink-0">
                        <a class="btn btn-info" href="<?= $back_url ?>"><i class="fa fa-reply"></i> </a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="example">
                        <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" role="form"
                              action="<?= $post_url ?>" novalidate="novalidate">

                            <div class="row">

                                <div class="form-group col-md-2">
                                    <label>المقر </label>
                                    <select name="branch_no" id="dp_branch_no" class="form-control " >
                                        <option value="">_________</option>
                                        <?php foreach($branches as $row) :?>
                                            <?php if ($HaveRs){ ?>
                                                <option <?=$HaveRs?($rs['BRANCH_NO']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                                            <?php } else {  ?>
                                                <option <?= $this->user->branch == $row['NO'] ? 'selected' : '' ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                            <?php } ?>
                                        <?php endforeach;?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-1">
                                    <label class="control-label">الشهر</label>
                                    <div>
                                        <?php if ($HaveRs){ ?>
                                            <input type="text" value="<?=$HaveRs?$rs['THE_MONTH']:''?>" name="the_month" id="txt_the_month" class="form-control" readonly>
                                        <?php } else {  ?>
                                            <input type="text"  value="<?= $current_date ?>" name="the_month" id="txt_the_month" class="form-control" readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered " id="month_target_tb" data-container="container">
                                    <thead class="table-primary">
                                    <tr style="text-align: center">
                                        <th style="width: 10%">م</th>
                                        <th style="width: 20%">النشاط</th>
                                        <th style="width: 20%">نوع النشاط</th>
                                        <th style="width: 20%">المستهدف</th>
                                        <th style="width: 10%">قيمة الخصم</th>
                                        <th style="width: 10%">قيمة الاضافة</th>
                                        <th style="width: 10%">الاجراء</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <?php if (count($rs) <= 0) {  // ادخال ?>
                                        <tr>
                                            <td>
                                                <input name="ser[]" id="ser<?= $count ?>" class="form-control" value="0" style="text-align: center" readonly>
                                            </td>

                                            <td>
                                                <select type="text" name="activity_no[]" id="dp_activity_no<?= $count ?>" class="form-control sel2" >
                                                    <option value="">__________</option>
                                                    <?php foreach ($activity_no as $row) : ?>
                                                        <option <?=$HaveRs?($rs['ACTIVITY_NO']==$row['CON_NO']?'selected':''):''?>  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>

                                            <td>
                                                <input type="text"  name="activity_type[]" id="txt_activity_type<?= $count ?>" class="form-control" style="text-align: center" readonly/>
                                            </td>

                                            <td>
                                                <input type="text"  name="monthly_target[]" id="txt_monthly_target<?= $count ?>" class="form-control" style="text-align: center" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                            </td>

                                            <td>
                                                <input type="text" name="discount_value[]" id="txt_discount_value<?= $count ?>" class="form-control" style="text-align: center" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                            </td>

                                            <td>
                                                <input type="text"  name="added_value[]" id="txt_added_value<?= $count ?>" class="form-control" style="text-align: center" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                            </td>

                                            <td></td>

                                        </tr>

                                        <?php
                                    } else if (count($rs) > 0) { // تعديل
                                        $count = -1;

                                        foreach ($master_tb_data as $row) {
                                            $count++;
                                            $d_count++;
                                            ?>

                                            <tr>
                                                <td>
                                                    <input type="hidden" name="ser[]" id="ser<?= $count ?>" class="form-control" value="<?= $row['TARGET_NO'] ?>" style="text-align: center" readonly>
                                                    <input name="txt_ser[]" id="txt_ser<?= $d_count ?>" class="form-control" value="<?= $d_count ?>" style="text-align: center" readonly>
                                                </td>

                                                <td>
                                                    <select name="activity_no[]" id="dp_activity_no<?= $count ?>" data-curr="false" class="form-control sel2" data-val="false" data-val-required="required">
                                                        <option value="">_________</option>
                                                        <?php foreach ($activity_no as $row2) : ?>
                                                            <option value="<?= $row2['CON_NO'] ?>" <?PHP if ($row['ACTIVITY_NO'] == $row2['CON_NO']) echo " selected"; ?>> <?= $row2['CON_NAME'] ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="text"  name="activity_type[]" id="txt_activity_type<?= $count ?>" value="<?= $row['ACTIVITY_TYPE_NAME'] ?>" class="form-control" style="text-align: center" readonly/>
                                                </td>

                                                <td>
                                                    <input name="monthly_target[]" class="form-control" id="txt_monthly_target<?= $count ?>" value="<?= $row['MONTHLY_TARGET'] ?>" style="text-align: center" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                                                </td>

                                                <td>
                                                    <input name="discount_value[]" class="form-control" id="txt_discount_value<?= $count ?>" value="<?= $row['DISCOUNT_VALUE'] ?>" style="text-align: center"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                                </td>

                                                <td>
                                                    <input type="text"  name="added_value[]" id="txt_added_value<?= $count ?>" value="<?= $row['ADDED_VALUE'] ?>" class="form-control" style="text-align: center" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                                </td>

                                                <td></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <?php if (count($rs) >= 0) { ?>
                                            <th>
                                                <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                                            </th>
                                        <?php } ?>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="modal-footer">

                                <?php if ( HaveAccess($post_url) && ($isCreate || $rs['ADOPT']==1 )) : ?>
                                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                                <?php endif; ?>

                                <?php if ( HaveAccess($adopt_url.'10') and $HaveRs and $rs['ADOPT']==1  ) : ?>
                                    <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد</button>
                                <?php endif; ?>


                                <?php if ( HaveAccess($adopt_url.'11') and $HaveRs and $rs['ADOPT']==10 ) : ?>
                                    <button type="button" id="btn_adopt_11" onclick='javascript:adopt_(11);' class="btn btn-success">اعتماد مدير التفتيش </button>
                                <?php endif; ?>

                            </div>
                        </form>

                        <div id="container">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
     var count = 0;
    $('.sel2').select2();
     
    $('#txt_the_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });
    
    $('#dp_branch_no').prop('readonly', true);
    $('#dp_branch_no').attr('readonly', true);
    
    function reBind(s) {
        if (s == undefined) {
            s = 0;
        }
        if (s) {
            $('.sel22:not("[id^=\'s2\']")').select2();

            $('.month').datetimepicker({
                format: 'YYYYMM',
                minViewMode: "months",
                pickTime: false
            });

        }
    }
    
    var branches_options = '{$branches_options}';
    var activity_no_options = '{$activity_no_options}';
    //اضافة سجل جديد
    function addRow(){
        var rowCount = $('#month_target_tb tbody tr').length;

        if(rowCount == 0){
            count = count+1;
        }else {
            count = rowCount+1
        }
        var html ='<tr><td><input name="ser[]" id="ser'+count+'" class="form-control" value="0" style="text-align: center" readonly> </td>' +
         ' <td><select name="activity_no[]" id="dp_activity_no'+count+'" class="form-control sel22">'+activity_no_options+'</select></td>' +
         ' <td><input  name="activity_type[]"  class="form-control" id="txt_activity_type'+count+'"  style="text-align: center" readonly /></td>' +
         ' <td><input  name="monthly_target[]"  class="form-control" id="txt_monthly_target'+count+'"  style="text-align: center" /></td>' +
         ' <td><input  name="discount_value[]" class="form-control" id="txt_discount_value'+count+'"  style="text-align: center" /></td>' +
         ' <td><input  name="added_value[]"  class="form-control" id="txt_added_value'+count+'"  style="text-align: center" /></td>' +
         ' <td class="text-center"><a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a></td>' +
         '</tr>';
        $('#month_target_tb tbody').append(html);
         reBind(count);

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
                 //console.log(data);
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+data);
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

if($HaveRs){
    $scripts = <<<SCRIPT
    {$scripts}
    
    <script type="text/javascript">

    function adopt_(no){
        if(no==10 ||no==11) var msg= 'هل تريد تأكيد الطلب ؟!';

        if(confirm(msg)){
            var values= {branch_no: "{$rs['BRANCH_NO']}" , the_month: "{$rs['THE_MONTH']}"};
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