<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 16/09/17
 * Time: 11:56 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'contractor_attendance';

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
                    <label class="control-label">مسلسل الحركة</label>
                    <div>
                        <input type="text" name="ser" id="txt_ser" class="form-control">
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
                    <label class="control-label">رقم السيارة</label>
                    <div>
                        <input type="text" name="car_num" id="txt_car_num" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> تاريخ التوقيع من </label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" name="signature_date" id="txt_signature_date" value="<?=date('01/m/Y')?>" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> الى </label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" name="signature_date_2" id="txt_signature_date_2" value="<?=date('t/m/Y')?>" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">مصدر التوقيع</label>
                    <div>
                        <select name="signature_source" id="dp_signature_source" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($signature_source_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">ملاحظات</label>
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
            <?//=modules::run($get_page_url, $page, $ser, $contractor_file_id, $car_num, $signature_date, $signature_date_2, $signature_source, $note, $branch_id, $entry_user );?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

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
        var values= {page:1, ser:$('#txt_ser').val(), contractor_file_id:$('#dp_contractor_file_id').val(), car_num:$('#txt_car_num').val(), signature_date:$('#txt_signature_date').val(), signature_date_2:$('#txt_signature_date_2').val(), signature_source:$('#dp_signature_source').val(), note:$('#txt_note').val(), branch_id:$('#dp_branch_id').val(), entry_user:$('#dp_entry_user').val() };
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

    function show_notes(){
        $('div.note').click(function(e){
            $(this).toggleClass('note note2');
        });
    }

    show_notes();

    </script>
SCRIPT;
sec_scripts($scripts);
?>
