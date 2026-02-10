<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 07/05/23
 * Time: 10:00 ص
 */

$MODULE_NAME = 'stores';
$TB_NAME = 'Smart_meter_barcodes';

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
                <li class="breadcrumb-item"><a href="javascript:void(0);">المخازن</a></li>
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

                                <div class="form-group col-sm-1">
                                    <label>رقم الكشف</label>
                                    <?php if ($HaveRs){ ?>
                                        <input type="text" value="<?=$HaveRs?$rs['SER']:''?>"  name="ser" id="txt_ser" class="form-control" readonly onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                    <?php } else {  ?>
                                        <input type="text" value="<?=date("His")?>"  name="ser" id="txt_ser" class="form-control" readonly onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                    <?php } ?>
                                </div>

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

                                <div class="form-group col-sm-2">
                                    <label>الجهة المستلمة</label>
                                    <div>
                                        <select type="text" name="receiving_party" id="dp_receiving_party" class="form-control sel2" >
                                            <option value="">__________</option>
                                            <?php foreach ($receiving_party as $row) : ?>
                                                <option <?=$HaveRs?($rs['RECEIVING_PARTY']==$row['CON_NO']?'selected':''):''?>  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-2">
                                    <label>رقم سند الصرف</label>
                                    <div>
                                        <input type="text" value="<?=$HaveRs?$rs['CLASS_OUTPUT_ID']:''?>" name="class_output_id" id="txt_class_output_id" class="form-control"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                    </div>
                                </div>

                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered " id="meter_barcodes_tb" data-container="container">
                                    <thead class="table-primary">
                                    <tr style="text-align: center">
                                        <th style="width: 10%">م</th>
                                        <th>باركود العدادات</th>
                                        <th>الاجراء</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <?php if (count($rs) <= 0) {  // ادخال ?>
                                        <tr>
                                            <td>
                                                <input type="text" value="<?= $count ?>"  class="form-control" style="text-align: center" readonly>
                                            </td>
                                            <td>
                                                <input type="hidden" name="ser_d[]" id="ser_d<?= $count ?>" class="form-control" value="0" style="text-align: center" readonly>
                                                <input type="text"  name="barcode[]" id="txt_barcode<?= $count ?>" class="form-control" maxlength="11" style="text-align: center" />
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
                                                    <input type="text" value="<?= $d_count ?>"  class="form-control" style="text-align: center" readonly>
                                                </td>

                                                <td>
                                                    <input type="hidden" name="ser_d[]" id="ser_d<?= $count ?>" class="form-control" value="<?= $row['SER_D'] ?>" style="text-align: center" readonly>
                                                    <input type="hidden" name="txt_ser_d[]" id="txt_ser_d<?= $d_count ?>" class="form-control" value="<?= $d_count ?>" style="text-align: center" readonly>
                                                    <input type="text"  name="barcode[]" id="txt_barcode<?= $count ?>" value="<?= $row['BARCODE'] ?>" class="form-control" maxlength="11" style="text-align: center" />
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

                                            </th>
                                        <?php } ?>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="modal-footer">

                                <?php if (HaveAccess($post_url) && $isCreate ) : ?>
                                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
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
    //اضافة سجل جديد
    function addRow(){
        var rowCount = $('#meter_barcodes_tb tbody tr').length;

        if(rowCount == 0){
            count = count+1;
        }else {
            count = rowCount+1
        }
        var html ='<tr><td><input type="text" value="'+count+'"  class="form-control" style="text-align: center" readonly></td>' +
         '<td><input type="hidden" name="ser_d[]" id="ser_d'+count+'" class="form-control" value="0" style="text-align: center" readonly>' +
         ' <input  name="barcode[]"  class="form-control" id="txt_barcode'+count+'" maxlength="11" style="text-align: center" /></td>' +
         ' <td class="text-center"><a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a></td>' +
         '</tr>';
        $('#meter_barcodes_tb tbody').append(html);
         reBind(count);
         $('#txt_barcode'+count).focus();
         
         $('#txt_barcode'+count).keypress(function(){
            clearTimeout(timeoutId);
            timeoutId = setTimeout(addRow, 100);
        });

    }
    
    var timeoutId = 0;
    $("#txt_barcode1").keypress(function(){
        clearTimeout(timeoutId);
        timeoutId = setTimeout(addRow, 100);
    });
    

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
sec_scripts($scripts);
?>