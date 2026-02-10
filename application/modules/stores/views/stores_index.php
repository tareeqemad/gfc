<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 20/11/14
 * Time: 09:37 ص
 */


$MODULE_NAME= 'stores';
$TB_NAME= 'stores';
$TB_NAME2= 'store_usres';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_details_url =base_url("$MODULE_NAME/$TB_NAME/get_page_users");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$get_id_url =base_url('financial/accounts/public_get_id');
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption">إدارة المخازن </div>

        <ul><?php
            if(HaveAccess($create_url)) echo "<li><a onclick='javascript:{$TB_NAME}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد </a> </li>";
            if(HaveAccess($get_url) and HaveAccess($edit_url)) echo "<li><a onclick='javascript:{$TB_NAME}_get(get_id());' href='javascript:;'><i class='glyphicon glyphicon-edit'></i>تحرير</a> </li>";
            if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>";
            ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
        </div>
    </div>

</div>


<div class="modal fade" id="<?=$TB_NAME?>Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات المخزن</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/create")?>" role="form" novalidate="novalidate">
              <div class="modal-body inline_form">

                 <!--     <label class="col-sm-1 control-label"> رقم المخزن  </label>
                    <div class="col-sm-2">
                        <input type="number" data-val="true"   data-val-required="حقل مطلوب" name="store_id" id="txt_store_id" class="form-control">
                        <span class="field-validation-valid" data-valmsg-for="txt_store_id" data-valmsg-replace="true"></span>
                    </div> -->
                  <div class="form-group">
                    <label class="col-sm-4 control-label"> اسم المخزن  </label>
                    <div class="col-sm-7">
                       <input type="text" data-val="true"   data-val-required="حقل مطلوب" name="store_name" id="txt_store_name" class="form-control">
                         <span class="field-validation-valid" data-valmsg-for="store_name" data-valmsg-replace="true"></span>
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="store_id" id="txt_store_id" class="form-control">

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label"> العنوان </label>
                    <div class="col-sm-7">
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="address" id="txt_address" class="form-control" dir="rtl">
                        <span class="field-validation-valid" data-valmsg-for="address" data-valmsg-replace="true"></span>


                    </div>

                </div>
                  <div class="form-group">
                      <label class="col-sm-4 control-label"> المستخدم</label>
                      <div class="col-sm-5">
                          <select name="store_employee" style="width: 250px" id="txt_store_employee">
                              <option></option>
                              <?php foreach($users as $row) :?>
                                  <option value="<?= $row['ID'] ?>"><?= $row['USER_NAME'] ?></option>
                              <?php endforeach; ?>
                          </select>

                      </div>
                  </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">رقم الحساب</label>

                    <div  class="col-sm-2">
                       <input type="text" name="account_id" id="h_txt_account_id" data-val="true"  data-val-required="required" class="form-control"/>
                        <span class="field-validation-valid" data-valmsg-for="account_id" data-valmsg-replace="true"></span>
                    </div>
</div>
                  <div class="form-group">
                <label class="col-sm-4 control-label">   اسم الحساب   </label>

                    <div  class="col-sm-6">
                        <input type="text"   data-val="false" readonly data-val-required="required" class="form-control" id="txt_account_id" />
                     </div>
                </div>


                <div class="form-group">

                    <label class="col-sm-4 control-label"> تلفون  </label>
                    <div class="col-sm-2">
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="tel" id="txt_tel" class="form-control" dir="rtl">
                        <span class="field-validation-valid" data-valmsg-for="tel" data-valmsg-replace="true"></span>

                    </div>

                    <label class="col-sm-1 control-label"> فاكس</label>
                    <div class="col-sm-2">
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="fax" id="txt_fax" class="form-control" dir="rtl">
                        <span class="field-validation-valid" data-valmsg-for="fax" data-valmsg-replace="true"></span>

                    </div>

                </div>


                <div class="form-group">

                    <label class="col-sm-4 control-label">    ايميل   </label>
                    <div class="col-sm-7">
                         <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="email" id="txt_email" class="form-control" dir="rtl">
                        <span class="field-validation-valid" data-valmsg-for="email" data-valmsg-replace="true"></span>

                    </div>
                </div>
                  <div class="form-group">

                      <label class="col-sm-4 control-label"> نوع المخزن  </label>
                      <div class="col-sm-2">
                          <select name="store_type" id="dp_store_type" class="form-control">
                              <option value="2">فرعي</option>
                              <option value="1">رئيسي</option>
                          </select>


                      </div>

                      <label class="col-sm-1 control-label"> رقم موقع المخزن</label>
                      <div class="col-sm-2">
                          <select name="store_pos" id="dp_store_pos" class="form-control">
                              <?php foreach($store_pos as $row) :?>
                                  <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>

                  </div>
                  <div class="form-group">

                      <label class="col-sm-4 control-label"> رقم دفتر إدخال  </label>
                      <div class="col-sm-2">
                          <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="enter_book" id="txt_enter_book" class="form-control" dir="rtl">
                          <span class="field-validation-valid" data-valmsg-for="enter_book" data-valmsg-replace="true"></span>

                      </div>

                      <label class="col-sm-1 control-label"> رقم دفتر صرف</label>
                      <div class="col-sm-2">
                          <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="payment_book" id="txt_payment_book" class="form-control" dir="rtl">
                          <span class="field-validation-valid" data-valmsg-for="payment_book" data-valmsg-replace="true"></span>

                      </div>


                  </div>
                  <div class="form-group">

                      <label class="col-sm-4 control-label">رقم مخزن الأصيل</label>
                      <div class="col-sm-2">
                          <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="aseel_no" id="txt_aseel_no" class="form-control" dir="rtl">
                          <span class="field-validation-valid" data-valmsg-for="aseel_no" data-valmsg-replace="true"></span>

                      </div>
                      <label class="col-sm-1 control-label">  رقم المخزن الجديد</label>
                      <div class="col-sm-2">
                          <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="store_no" id="txt_store_no" class="form-control" dir="rtl">
                          <span class="field-validation-valid" data-valmsg-for="store_no" data-valmsg-replace="true"></span>

                      </div>
                      </div>
                  <div class="form-group">

                      <label class="col-sm-4 control-label">فرع المخزن</label>
                      <div class="col-sm-2">
                          <select name="branch" id="dp_branch" class="form-control">
                              <?php foreach($branches as $row) :?>
                                  <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>


                      <label class="col-sm-1 control-label">إمكانية الصرف؟</label>
                      <div class="col-sm-2">
                          <select name="can_output" id="dp_can_output" class="form-control">
                              <?php foreach($is_can_outputs as $row) :?>
                                  <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>


                  </div>
                  <div class="form-group">

                      <label class="col-sm-4 control-label">نوع المخزن من حيث المواد</label>
                      <div class="col-sm-2">
                          <select name="isdonation" id="dp_isdonation" class="form-control">
                              <?php foreach($is_donationss as $row) :?>
                                  <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                              <?php endforeach; ?>
                           </select>
                      </div>


                      <label class="col-sm-1 control-label">يحتاج إعتماد الجهة الطالبة؟</label>
                      <div class="col-sm-2">
                          <select name="need_tec_adopt" id="dp_need_tec_adopt" class="form-control">
                              <?php foreach($is_need_tech as $row) :?>
                                  <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>

                  </div>

                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
              </div>
            </form>
      </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="<?=$TB_NAME2?>Modal">
    <div class="modal-dialog" style="width: 850px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME2?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME2/edit")?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

 $('input[name="account_id"]').change(function(){
            get_account_name($(this));
        });

          function get_account_name(obj){

            if($(obj).val().length >6  ){
                get_data('{$get_id_url}',{id:$(obj).val()},function(data){
                    if(data.length > 0){
$('#txt_account_id').val(data[0].ACOUNT_NAME+' ('+data[0].CURR_NAME+')');
                    }else{
  $(obj).val('');
  $('#txt_account_id').val('');
                    }
                });
            }
    }

    $(document).ready(function() {
     $('#txt_store_employee').select2().on('change',function(){

       //  checkBoxChanged();

        });
 $('#txt_account_id').click(function(e){

            _showReport('$select_accounts_url/'+$(this).attr('id') );

    });

        $('#{$TB_NAME}_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });

    });

    $(function () {
        $('#{$TB_NAME}Modal').on('shown.bs.modal', function () {
            $('#txt_store_name').focus();
        });
    });

    function get_id(){
        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

    function {$TB_NAME}_create(){
        clearForm($('#{$TB_NAME}_from'));

        $('#{$TB_NAME}_from').attr('action','{$create_url}');

        $('#{$TB_NAME}Modal').modal();
    }

    function {$TB_NAME}_get(id){
        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_store_id').val('');
                $('#txt_store_name').val( '');
                $('#txt_address').val( '');
                $('#txt_store_employee').select2('val', '');
                $('#txt_account_id').val( '');
                $('#h_txt_account_id').val( '');
                $('#txt_tel').val( '');
                $('#txt_fax').val( '');
                $('#txt_email').val('');
                $('#dp_store_type').val('');
                $('#dp_store_pos').val( '');
                $('#txt_enter_book').val( '');
                $('#txt_payment_book').val( '');
                $('#txt_aseel_no').val( '');
$('#txt_store_no').val(  '');
  $('#dp_branch').val( '');
  $('#dp_isdonation').val( '');
   $('#dp_can_output').val( '');
    $('#dp_need_tec_adopt').val( '');




                $('#txt_store_id').val(item.STORE_ID);
                $('#txt_store_name').val( item.STORE_NAME);
                $('#txt_address').val( item.ADDRESS);
                $('#txt_store_employee').select2('val',  item.STORE_EMPLOYEE);
                $('#txt_account_id').val( item.ACCOUNT_ID_NAME);
                $('#h_txt_account_id').val( item.ACCOUNT_ID);
                $('#txt_tel').val( item.TEL);
                $('#txt_fax').val( item.FAX);
                $('#txt_email').val( item.EMAIL);
                $('#dp_store_type').val( item.STORE_TYPE);
                $('#dp_store_pos').val( item.STORE_POS);
                $('#txt_enter_book').val( item.ENTER_BOOK);
                $('#txt_payment_book').val( item.PAYMENT_BOOK);
                $('#txt_aseel_no').val( item.ASEEL_NO);
                $('#txt_store_no').val( item.STORE_NO);
                $('#dp_branch').val( item.BRANCH);
                $('#dp_isdonation').val( item.ISDONATION);
                 $('#dp_can_output').val( item.CAN_OUTPUT);
                 $('#dp_need_tec_adopt').val( item.NEED_TEC_ADOPT);
                $('#{$TB_NAME}_from').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_from'));
                $('#{$TB_NAME}Modal').modal();
            });
        });
    }

    function {$TB_NAME}_delete(){
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
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }
    function {$TB_NAME2}_get(id){
        clearForm($('#{$TB_NAME2}_from'));
        $("#{$TB_NAME2}_from .modal-body").text('');
        $('#{$TB_NAME2}Modal .modal-title').text(name);

        get_data('{$get_details_url}', {store_id:id}, function(ret){
            $('#{$TB_NAME2}Modal').modal();
            $("#{$TB_NAME2}_from .modal-body").html(ret);
        }, 'html');
    }
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            container.html(data);
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
    });


</script>

SCRIPT;

sec_scripts($scripts);

?>
