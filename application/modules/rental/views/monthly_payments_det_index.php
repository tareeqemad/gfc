<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 26/09/17
 * Time: 01:00 م
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'monthly_payments_det';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$select_contractors_url= ("$MODULE_NAME/rental_contractors/public_get_all_for_select");
$select_payments_url= ("$MODULE_NAME/monthly_cpayments/public_get_all_for_select");

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
            <?php if(0):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
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
                    <label class="control-label">رقم المطالبة الشهرية</label>
                    <div>
                        <select name="monthly_cpayments_id" id="dp_monthly_cpayments_id" class="form-control sel2" >
                            <?=modules::run($select_payments_url);?>
                        </select>
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
                    <label class="control-label">رقم هوية المتعاقد</label>
                    <div>
                        <input type="text" name="contractor_id" id="txt_contractor_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المتعاقد</label>
                    <div>
                        <input type="text" name="contractor_name" id="txt_contractor_name" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السيارة</label>
                    <div>
                        <input type="text" name="car_num" id="txt_car_num" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">صافي الراتب ≈</label>
                    <div>
                        <input type="text" placeholder="تقريبي" name="net_salary" id="txt_net_salary" class="form-control">
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
            <?=modules::run($get_page_url, $page, $ser, $monthly_cpayments_id, $contractor_file_id, $contractor_id, $contractor_name, $car_num, $net_salary );?>
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
        var values= {page:1, ser:$('#txt_ser').val(), monthly_cpayments_id:$('#dp_monthly_cpayments_id').val(), contractor_file_id:$('#dp_contractor_file_id').val(), contractor_id:$('#txt_contractor_id').val(), contractor_name:$('#txt_contractor_name').val(), car_num:$('#txt_car_num').val(), net_salary:$('#txt_net_salary').val() };
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
