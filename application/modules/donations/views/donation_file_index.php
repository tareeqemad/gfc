<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/10/15
 * Time: 01:20 م
 */

$MODULE_NAME= 'donations';
$TB_NAME= 'donation';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
echo AntiForgeryToken();


?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <?php if(HaveAccess($delete_url)):?><li><a onclick='javascript:donation_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المسلسل</label>
                    <div>
                        <input type="text" name="donation_file_id"  id="txt_donation_file_id" class="form-control ">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">رقم المنحة - من الجهة المانحة</label>
                    <div>
                        <input type="text" name="donation_id"  id="txt_donation_id" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الجهة المانحة</label>
                    <div>
                        <input type="text" name="donation_name"  id="txt_donation_name" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ اعتماد المنحة</label>
                    <div>
                        <input type="text" name="donation_approved_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" id="txt_donation_approved_date" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="donation_approved_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ نهاية المنحة</label>
                    <div>
                        <input type="text" name="donation_end_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" id="txt_donation_end_date" class="form-control " >
                        <span class="field-validation-valid" data-valmsg-for="donation_end_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المنحة</label>
                    <div>
                        <input type="text" name="donation_label"  id="txt_donation_label" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">حساب المنحة</label>
                    <div id="div_donation_account">
                        <input type="text" data-val="false" readonly data-val-required="required" class="form-control" id="txt_donation_account" />
                        <input type="hidden" name="donation_account" id="h_txt_donation_account" />
                        <span class="field-validation-valid" data-valmsg-for="donation_account" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الجهة الممولة</label>
                    <div>
                        <input type="text" name="donor_name"  id="txt_donor_name" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع الممولة</label>
                    <div>
                        <select  name="donation_type" id="dp_donation_type"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($donation_types as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="donation_type" data-valmsg-replace="true">
                    </div>
                </div>


                <div class="form-group col-sm-1">
                    <label class="control-label">طبيعة المنحة</label>
                    <div>
                        <select  name="donation_kind" id="dp_donation_kind"  data-curr="false"  class="form-control" >
                            <option value="">-</option>
                            <?php foreach($donation_kinds as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="donation_kind" data-valmsg-replace="true">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">  عملة المنحة  </label>
                    <div class="">
                        <select  name="curr_id" id="dp_curr_id"  data-curr="false"  class="form-control">
                            <option value="">-</option>
                            <?php foreach($currency as $row) :?>
                                <option  data-val="<?= $row['VAL'] ?>" value="<?= $row['CURR_ID'] ?>"><?php echo $row['CURR_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">

                    <label class="control-label">مخزن المنحة</label>
                    <div>

                        <select  name="store_id" id="dp_store_id"  data-curr="false"  class="form-control" >
                            <option value="">-</option>
                            <?php foreach($stores_all as $row) :?>
                                <option  value="<?= $row['STORE_ID'] ?>" ><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="store_id" data-valmsg-replace="true">


                    </div>
                </div>

                <div class="form-group col-sm-1">

                    <label class="control-label">شروط المنحة</label>
                    <div>
                        <select  name="conditions" id="dp_conditions"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($conditions as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="conditions" data-valmsg-replace="true">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">حالة المنحة</label>
                    <div>
                        <select  name="donation_file_case" id="dp_donation_adopt"  data-curr="false"  class="form-control" >
                            <option value="">-</option>
                            <?php foreach($donation_adopts as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="donation_kind" data-valmsg-replace="true">
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
            <?=modules::run($get_page_url,$page);?>
        </div>
        </div>

</div>
<?php


$notes_url =notes_url();

$scripts = <<<SCRIPT
<script type="text/javascript">


 $('.pagination li').click(function(e){
        e.preventDefault();
    });


    function show_row_details(id,order_purpose){

            get_to_link('{$get_url}/'+id+'/'+order_purpose);
           }

function donation_delete(){
        var url = '{$delete_url}';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
         var val = [];
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });

        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد حذف '+val.length+' سجلات وحذف تفاصيلها ؟!!')){
              ajax_delete(url, val ,function(data){
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                    container.html(data);
                      window.location.reload();
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }
 function clear_form(){
      clearForm($('#{$TB_NAME}_form'));
          }
          function search(){

       var values= {page:1,donation_file_id:$('#txt_donation_file_id').val(), donation_id:$('#txt_donation_id').val(),  donation_name:$('#txt_donation_name').val(), donation_approved_date:$('#txt_donation_approved_date').val(), donation_end_date:$('#txt_donation_end_date').val(), donation_label:$('#txt_donation_label').val(), donation_account:$('#h_txt_donation_account').val(), donor_name:$('#txt_donor_name').val(), donation_type:$('#dp_donation_type').val(), donation_kind:$('#dp_donation_kind').val(), curr_id:$('#dp_curr_id').val(), store_id:$('#dp_store_id').val(),donation_file_case:$('#dp_donation_adopt').val(),conditions:$('#dp_conditions').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
      var values= {page:1,donation_file_id:$('#txt_donation_file_id').val(), donation_id:$('#txt_donation_id').val(),  donation_name:$('#txt_donation_name').val(), donation_approved_date:$('#txt_donation_approved_date').val(), donation_end_date:$('#txt_donation_end_date').val(), donation_label:$('#txt_donation_label').val(), donation_account:$('#h_txt_donation_account').val(), donor_name:$('#txt_donor_name').val(), donation_type:$('#dp_donation_type').val(), donation_kind:$('#dp_donation_kind').val(), curr_id:$('#dp_curr_id').val(), store_id:$('#dp_store_id').val(),donation_file_case:$('#dp_donation_adopt').val(),conditions:$('#dp_conditions').val()};
        ajax_pager_data('#page_tb > tbody',values);
    }
 $(document).ready(function() {
 $('#dp_store_id').select2().on('change',function(){

        });
$('#txt_donation_account').click(function(e){

            _showReport('$select_accounts_url/'+$(this).attr('id') );

    });
});



</script>

SCRIPT;

sec_scripts($scripts);

?>