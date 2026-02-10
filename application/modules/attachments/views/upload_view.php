<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 30/03/15
 * Time: 08:32 ص
 */
$count = 0;

?>


    <div class="modal-body">
        <div class="tbl_container">
            <table class="table" id="attachTbl" data-container="container">
                <thead>
                <tr>
                    <th>م</th>
                    <th>اسم الملف</th>
                    <th>اسم المستخدم</th>
                    <th>تاريخ الرفع</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>



                <?php foreach($rows as $row) :?>
                    <?php $count++; ?>
                    <tr class="<?=$row['ID']?>">
                        <td>
                            <?= $count ?>
                        </td>
                        <td>
                            <?= "<a href='".base_url("archive/download/{$row['FILE_PATH']}/".clear_url($row['FILE_NAME'] )."")."'>{$row['FILE_NAME']}</a>" ?>
                        </td>
                        <td>
                            <?= get_user_name($row['ENTRY_USER']) ?>
                        </td>

                        <td>  <?= $row['ENTRY_DATE'] ?> </td>
                        <td>

                            <?php if (($row['ENTRY_USER'] == get_curr_user()->id) && ($can_upload_priv==1)) : ?>
                                <a href="javascript:;" onclick="javascript:delete_attachment(this,<?= $row['ID'] ?>);"><i class="icon icon-trash delete-action"></i> </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach;?>

                </tbody>
                <tfoot>
                <tr>
                    <th></th>
                    <th> <?php  if(HaveAccess(base_url('archive/archive/upload_file')) && ($can_upload_priv==1) ) : ?><a onclick='javascript:$("#attach_formModal").modal();' href='javascript:;'><i class='glyphicon glyphicon-upload'></i>رفع ملف جديد</a><?php endif; ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>
            </table>

        </div>

    </div>



<div class="modal fade" id="attach_formModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> المرفقات</h4>
            </div>

            <div class="modal-body">
                <div id="uploadModal" class="tbl_container">
                    <div class='form-group'>يمكن رفع عدد غير محدود من الملفات</div>
                    <div class='form-group'>الحد الاقصى المسموح به لكل ملف هو 150 ميجا بايت</div>
                    <div class='form-group'>الملفات المسموح رفعها هي word | excel | powerpoint | outlook | image | pdf | zip | rar </div>
                    <form id='upload_file' action='<?=base_url("archive/archive/upload_file")?>' method='post' accept-charset='utf-8' enctype='multipart/form-data'>
                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>اختر الملف</label>
                            <input type="hidden" name="identity" value="<?= $id ?>">
                            <input type="hidden" name="category" value="<?= $categories ?>">
                            <input type="hidden" name="upload_type" value="attachment">
                            <div class='col-sm-7'><input type='file' name='file' value=''  /></div>
                            <div class='col-sm-3'><button type='submit' class='btn btn-primary'><i class='glyphicon glyphicon-upload'></i>رفع</button></div>
                        </div>
                        <div class='form-group'>
                            <label class='col-sm-12 control-label'><div id='msg'></div></label>
                        </div>
                        <div class='form-group'>
                            <div class='col-sm-12' id='progress' style='display: none'><progress></progress></div>
                        </div>
                    </form>

                </div></div></div></div>

    <script>
        if (typeof initFunctions == 'function') {
            initFunctions();
        }


    </script>

<?php

$delete_details_url = base_url('archive/archive/delete_file');

$shared_js = <<<SCRIPT
<script>
    function delete_attachment(a,id){
         if(confirm('هل تريد بالتأكيد حذف سجلات وحذف تفاصيلها ؟!!')){
                    var values= {id:id};
                    get_data('{$delete_details_url}',values ,function(data){

                        if (data=='1'){
                           $(a).closest('tr').remove();
                           alert('تم حذف الملف ..');
                        }
                    },'html');

         }

    }

</script>
SCRIPT;
sec_scripts($shared_js);
?>