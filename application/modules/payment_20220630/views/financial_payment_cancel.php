<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/12/14
 * Time: 09:50 ص
 */

$TB_NAME= 'financial_payment';
$get_url=base_url('payment/financial_payment/cancel');

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <div class="modal-body inline_form">
            <form  id="<?=$TB_NAME?>_form"  method="post" action="<?=$get_url?>" role="form" novalidate="novalidate">
                <input type="hidden" name="action" id="h_action" value="get">

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الايصال</label>
                    <div>

                        <input type="text" name="fid" id="txt_id" value="" class="form-control">
                        <input type="hidden"  name="id" id="txt_f_id" value="" class="form-control">
                    </div>
                </div>

            </form>
        </div>

        <div id="msg_container"></div>

        <div id="container">

            <div id="payment_data" class="checks_div">
                <div class="form-group col-sm-2">
                    <label class="control-label" >اجمالي المبلغ</label>
                    <div id="total">
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label" >البيان</label>
                    <div id="hints">
                    </div>
                </div>
            </div>

            <div id="for_checks" class="checks_div">
                <div class="form-group col-sm-2">
                    <label class="control-label" id="lb_check_id">رقم الشيك</label>
                    <div id="check_id">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label" id="lb_check_customer">اسم صاحب الشيك</label>
                    <div id="check_customer">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label" id="lb_check_bank_id">البنك المسحوب عليه</label>
                    <div id="check_bank_id">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label" id="lb_check_date">تاريخ استحقاق الشيك</label>
                    <div id="check_date">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">العملة</label>
                    <div id="check_curr">
                    </div>
                </div>
                <hr>
                <div class="form-group col-sm-12">
                    <label class="control-label">السبب الالغاء</label>
                    <div>

                        <input type="text" name="notes" id="txt_notes" value=""  data-val="true"  data-val-required="حقل مطلوب"  class="form-control">

                    </div>
                </div>


            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" id="btn_search" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success"> إستعلام</button>
        <button type="submit" data-action="submit" class="btn btn-danger">الغاء سند الصرف</button>
        <button type="reset"  onclick="javascript:<?=$TB_NAME?>_clear();"  class="btn btn-default"> تفريغ الحقول</button>
    </div>

</div>


<?php
$notes_url = base_url('settings/notes/public_create');
$scripts = <<<SCRIPT
<script>

    var payment_page= 0;
    var count=0;
    var data=[];
    $('#txt_id').focus();

    $(function(){
        $('input[type="text"],body').bind('keydown', 'f3', function() {
            {$TB_NAME}_search();
            return false;
        });

        $('input[type="text"],body').bind('keydown', 'f2', function() {
            {$TB_NAME}_clear();
            return false;
        });
    });

    function {$TB_NAME}_search(){
        var values= $('#{$TB_NAME}_form').serialize();
        if( $('#txt_id').val()!='' && $('#h_action').val()=='get' ){
            get_data('{$get_url}',values,function(ret){
                data= ret;
                count= data.length;
                if(count == 0){
                    alert('لم يتم العثور على اي نتائج');
                    return;
                }
                $('#h_action').val('');
                $('#btn_search').attr('disabled', 'disabled');
                {$TB_NAME}_show(0);
            });
        }else
            alert('ادخل رقم الايصال');
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();

        if($('#txt_notes').val() == ''){


                  bootbox.dialog({
                          message:  'تحذير / يجب إدخال سبب الإلغاء',
                          title: "تحذير",
                          className:'danger',
                          buttons: {

                            main: {
                              label: "إغلاق",
                              className: "btn-default",
                              callback: function() {

                              }
                            }
                          }
                });
            return;
        }

        if($('#txt_id').val()!='' && $('#h_action').val()=='' && confirm('هل تريد بالتأكيد الغاء سند الصرف؟!')){
            $('#h_action').val('cancel');
            var form = $('#{$TB_NAME}_form');
            ajax_insert_update(form,function(res){
                if(res==1){
                    $('button[data-action="submit"]').attr('disabled', 'disabled');
                    $('#h_action').val('');
                       success_msg('رسالة','تم الغاء سند الصرف بنجاح ..');

                       get_data('{$notes_url}',{source_id:$('#txt_f_id').val(),source:'financial_payment',notes:$('#txt_notes').val()},function(data){
                                 $('#txt_notes').val('');
                        },'html');
                }
                else
                    warning_msg('تنبيه','لم يتم الغاء سند الصرف ..');
            },"html");
        }
    });

    function {$TB_NAME}_show(i){
        if(!(i>=0 && i<count)){
            return;
        }
        payment_page= i;
        var item= data[i];
        $('button[data-action="submit"]').removeAttr('disabled');
        $('#payment_data').show();

        if(item.PAYMENT_TYPE==3){ // حوالة
            $('label#lb_check_id').text('رقم الحوالة');
            $('label#lb_check_bank_id').text('البنك المحول اليه');
            $('label#lb_check_date').text('تاريخ الحوالة');
            $('label#lb_check_customer').text('اسم صاحب الحوالة');
            $('#for_checks').show();
        }else if(item.PAYMENT_TYPE==2){ // شيك
            $('label#lb_check_id').text('رقم الشيك');
            $('label#lb_check_bank_id').text('البنك المسحوب عليه');
            $('label#lb_check_date').text('تاريخ استحقاق الشيك');
            $('label#lb_check_customer').text('اسم صاحب الشيك');
            $('#for_checks').show();
        }else{
            $('#for_checks').hide();
        }

        $('#txt_id').prop('readonly',true);
        //$('#txt_id').val();
        $('#txt_customer_name').val(item.CUSTOMER_ID_NAME);
        $('#txt_f_id').val(item.FINANCIAL_PAYMENT_ID);

        $('#total').text(item.TOTAL);
        if(item.TOTAL== null)
            $('#total').text(0);
        $('#hints').text(item.HINTS);

        $('#check_id').text(item.CHECK_ID);
        $('#check_customer').text(item.CHECK_CUSTOMER);
        $('#check_bank_id').text(item.CHECK_BANK_ID_NAME);
        $('#check_date').text(item.CHECK_DATE);
        $('#check_curr').text(item.CURR_ID_NAME);
    }

    function {$TB_NAME}_clear(){
        clearForm($('#{$TB_NAME}_form'));
        $('#h_action').val('get');
        $('#txt_id').prop('readonly',false);
        $('#payment_data, #for_checks').hide();
        $('#txt_id').focus();
        $('button[data-action="submit"], #btn_search').removeAttr('disabled');
        payment_page= 0;
        count=0;
        data=[];
    }

</script>
SCRIPT;
sec_scripts($scripts);

?>
