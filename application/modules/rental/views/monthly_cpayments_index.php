<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/09/17
 * Time: 11:29 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'monthly_cpayments';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
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
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم المطالبة الشهرية</label>
                    <div>
                        <select name="monthly_cpayments_id" id="dp_monthly_cpayments_id" class="form-control sel2" >
                            <?=modules::run($select_payments_url);?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الفرع </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> الشهر</label>
                    <div>
                        <input type="text" name="payroll_date" id="txt_payroll_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
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
            <?=modules::run($get_page_url, $page, $monthly_cpayments_id, $branch_id, $payroll_date, $adopt, $entry_user );?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('#txt_payroll_date').datetimepicker({
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
        var values= {page:1, monthly_cpayments_id:$('#dp_monthly_cpayments_id').val(), branch_id:$('#dp_branch_id').val(), payroll_date:$('#txt_payroll_date').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val() };
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
