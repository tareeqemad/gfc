<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 20/01/2016
 * Time: 08:34 ص
 */


?>
<?= AntiForgeryToken() ?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>

                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

            </ul>

        </div>

        <div class="form-body">

            <div id="msg_container"></div>
            <div id="container">
                <form class="form-vertical" id="chains_from" method="post" action="<?=base_url('financial/financial_chains/Currency_difference')?>" role="form" novalidate="novalidate">
                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-2">
                            <label class="control-label">  التاريخ</label>
                            <div class="">
                                <input type="text" name="date" data-type="date" data-val="true" data-val-required="حقل مطلوب" data-date-format="DD/MM/YYYY" value="<?= date('d/m/Y') ?>"  id="txt_date" class="form-control ">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group col-sm-2">
                            <label class="control-label"> من حساب  </label>
                            <div class="">
                                <input type="text" name="id"  data-val="true" data-val-required="حقل مطلوب"  id="h_txt_account_1" class="form-control ">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="control-label">اسم الحساب</label>
                            <input type="text" readonly="" id="txt_account_1" class="form-control">
                        </div>

                        <hr>

                            <div class="form-group col-sm-2">
                                <label class="control-label"> الي حساب  </label>
                                <div class="">
                                    <input type="text" name="idto"  data-val="true"  data-val-required="حقل مطلوب" id="h_txt_account_2" class="form-control ">
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <label class="control-label">اسم الحساب</label>
                                <input type="text" readonly="" id="txt_account_2" class="form-control">
                            </div>
                           <hr>





                        <hr/>
                        <div class="modal-footer">

                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>


<?php

$select_parent_url =base_url('financial/accounts/public_acounts_tb_get_not_cur_master');
$get_id_url =base_url('financial/accounts/public_get_id');

$scripts = <<<SCRIPT

<script>
    $(function(){
        $('#h_txt_account_1,#h_txt_account_2').keyup(function(){
                    get_account_name($(this));
        });

       $('#txt_account_1,#txt_account_2').click(function(e){
             _showReport('$select_parent_url/'+$(this).attr('id')+'/2' );
       });

 function get_account_name(obj){
        $(obj).closest('tr').find('input[name$="_name[]"]').val('');
        var _type = $(obj).closest('tr').find('select').val();

        if(_type == 1)
            if($(obj).val().length >6  || $(obj).val().match("^60")){
                get_data('{$get_id_url}',{id:$(obj).val()},function(data){

                    if(data.length > 0){

                        $(obj).closest('tr').find('input[name$="_name[]"]').val(data[0].ACOUNT_NAME+' ('+data[0].CURR_NAME+')');
                    }
                });
            }
    }

       $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ القيد ؟!')){
            var form = $(this).closest('form');

            ajax_insert_update(form,function(data){

                if(data=='1'){

                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                setTimeout(function(){

                    window.location.reload();

                }, 1000);
        }else{

        }
            },'html');
        }
    });

    });


</script>


SCRIPT;


sec_scripts($scripts);



?>