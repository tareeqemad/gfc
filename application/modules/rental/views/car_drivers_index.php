<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 30/08/17
 * Time: 11:48 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'car_drivers';

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
                    <label class="control-label">م</label>
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
                    <label class="control-label">رقم هوية السائق</label>
                    <div>
                        <input type="text" name="driver_id" id="txt_driver_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">اسم السائق</label>
                    <div>
                        <input type="text" name="driver_name" id="txt_driver_name" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع رخصة السائق</label>
                    <div>
                        <select name="license_type" id="dp_license_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($license_type_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ انتهاء الرخصة</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" name="license_end_date" id="txt_license_end_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة عمل السائق مع المتعاقد</label>
                    <div>
                        <select name="driver_work_case" id="dp_driver_work_case" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($driver_work_case_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
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
            <?=modules::run($get_page_url, $page, $ser, $contractor_file_id, $driver_id, $driver_name, $license_type, $license_end_date, $driver_work_case, $entry_user );?>
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
        var values= {page:1, ser:$('#txt_ser').val(), contractor_file_id:$('#dp_contractor_file_id').val(), driver_id:$('#txt_driver_id').val(), driver_name:$('#txt_driver_name').val(), license_type:$('#dp_license_type').val(), license_end_date:$('#txt_license_end_date').val(), driver_work_case:$('#dp_driver_work_case').val(), entry_user:$('#dp_entry_user').val() };
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
