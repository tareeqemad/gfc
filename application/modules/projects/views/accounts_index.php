<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 21/01/15
 * Time: 01:59 م
 */

$back_url='';
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$get_id_url =base_url('financial/accounts/public_get_id');
$count = 0;
?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>

                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

            </ul>

        </div>

        <div class="form-body">
            <form class="form-vertical" id="accounts_from" method="post" action="<?=base_url('projects/projects/accounts')?>" role="form" novalidate="novalidate">

                <div class="tbl_container">
                    <table class="table" id="accounts_detailsTbl" data-container="container">
                        <thead>
                        <tr>
                            <th style="width: 150px;" >  رقم الحساب </th>
                            <th>الرقم الفني</th>
                            <th >  اسم الحساب  </th>

                            <th style="width: 100px"  > التاريخ</th>
                            <th style="width: 500px"  > الحساب المتبوع</th>
                            <th></th>

                        </tr>
                        </thead>

                        <tbody>



                        <?php foreach($rows as $row) :?>


                            <tr>
                                <td>
                                    <input type="hidden" name="SER[]" value="<?= $row['SER'] ?>">
                                    <?= $row['PROJECT_ID'] ?>

                                </td>
                                <td><?= $row['PROJECT_TEC_CODE'] ?></td>
                                <td>
                                    <?= $row['PROJECT_ACCOUNT_NAME'] ?>
                                </td>

                                <td >
                                    <?= $row['PROJECT_DATE'] ?>
                                </td>

                                <td>
                                    <input name="project_account_id[]" value="<?= $row['PROJECT_ACCOUNT_ID'] ?>" id="h_txt_project_account_id<?= $count ?>" class="form-control col-sm-2"   >
                                    <input name="project_account_id_name[]" readonly value="<?= $row['PROJECT_ACCOUNT_ID_NAME'] ?>" id="txt_project_account_id<?= $count ?>"  class="form-control col-sm-8"   >
                                </td>
                                <td></td>

                            </tr>
                            <?php $count++; ?>

                        <?php endforeach;?>

                        </tbody>

                    </table>
                </div>

                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>


                </div>
            </form>
            <script>
                if (typeof initFunctions == 'function') {
                    initFunctions();
                }


            </script>

        </div>
    </div>

<?php

$script=<<<SCRIPT
<script>
    $('input[name="project_account_id_name[]"]').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/10106|20106/0' );
    });

    $('input[name="project_account_id[]"]').keyup(function(){
        get_account_name($(this));
    });

    function get_account_name(obj){
        $(obj).closest('tr').find('input[name$="_name[]"]').val('');

        if($(obj).val().length >6 ){
            get_data('{$get_id_url}',{id:$(obj).val()},function(data){

                if(data.length > 0){

                    $(obj).closest('tr').find('input[name$="_name[]"]').val(data[0].ACOUNT_NAME);
                }
            });
        }
    }



    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();

        if($('input:text:visible',$('#accounts_detailsTbl')).filter(function() { return this.value == ""; }).length > 0){


            bootbox.dialog({
                message: "يجب إدخال جميع البياتات في جدول الكميات",
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


        if(confirm('هل تريد حفظ الحسابات ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(data=='1'){
                   success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    reload_Page();
                }

            },'html');
        }
    });

</script>
SCRIPT;


sec_scripts($script);

?>

