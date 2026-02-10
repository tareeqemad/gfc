<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 11/09/17
 * Time: 01:04 م
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'contractor_deduction';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$select_contractors_url= ("$MODULE_NAME/rental_contractors/public_get_all_for_select");

if(HaveAccess($edit_url))
    $edit= 'edit';
else
    $edit= '';

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم مسلسل الحركة</label>
                    <div>
                        <input type="text" name="contractor_deduction_id" id="txt_contractor_deduction_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم سند ملف التعاقد</label>
                    <div>
                        <select name="contractor_file_id" id="dp_contractor_file_id" class="form-control sel2" >
                            <?=modules::run($select_contractors_url);?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">حالة السند</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($adopt_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم قرار الاستقطاع</label>
                    <div>
                        <input type="text" name="deduction_id" id="txt_deduction_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ بداية الاستقطاع(من شهر)</label>
                    <div>
                        <input type="text" name="deduction_sdate" id="txt_deduction_sdate" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ نهاية الاستقطاع(إلى شهر)</label>
                    <div>
                        <input type="text" name="deduvtion_edate" id="txt_deduvtion_edate" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الاستقطاع</label>
                    <div>
                        <select name="deduction_bill_id" id="dp_deduction_bill_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($deduction_bill_id_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NO'].': '.$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">قيمة الاستقطاع</label>
                    <div>
                        <input type="text" name="deduction_value" id="txt_deduction_value" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-8">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" name="note" id="txt_note" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مدخل الطلب</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($entry_user_all as $row) :?>
                                <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $contractor_deduction_id, $contractor_file_id, $adopt, $deduction_id, $deduction_sdate, $deduvtion_edate, $deduction_bill_id, $deduction_value, $note, $entry_user );?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('#txt_deduction_sdate,#txt_deduvtion_edate').datetimepicker({
        format: 'MM/YYYY',
        minViewMode: "months",
        pickTime: false
    });

    $('.sel2').select2();

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/{$edit}');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function values_search(add_page){
        var values= {page:1, contractor_deduction_id:$('#txt_contractor_deduction_id').val(), contractor_file_id:$('#dp_contractor_file_id').val(), adopt:$('#dp_adopt').val(), deduction_id:$('#txt_deduction_id').val(), deduction_sdate:$('#txt_deduction_sdate').val(), deduvtion_edate:$('#txt_deduvtion_edate').val(), deduction_bill_id:$('#dp_deduction_bill_id').val(), deduction_value:$('#txt_deduction_value').val(), note:$('#txt_note').val(), entry_user:$('#dp_entry_user').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }

    </script>
SCRIPT;
sec_scripts($scripts);
?>
