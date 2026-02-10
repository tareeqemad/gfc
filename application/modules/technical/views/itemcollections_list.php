<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 21/05/17
 * Time: 08:57 ص
 */

$count = 0;

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>


    </div>

    <div class="form-body">

        <div class="alert alert-info">

            قم بادراج الاصناف علي : <?= $rows[0]['TITLE'] ?>
        </div>


        <div class="tbl_container">

            <form action="<?= base_url("technical/ItemCollections/get/{$rows[0]['SER']}") ?>" method="post">
                <div class="form-body">

                    <input type="hidden" name="ser" value="<?= $rows[0]['SER'] ?>"/>

                    <div class="tbl_container">
                        <table class="table" id="projectTbl" data-container="container">
                            <thead>
                            <tr>

                                <th style="width: 10px;">#</th>
                                <th>الصنف</th>
                                <th style="width: 50px;"></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($rows as $row) : ?>
                                <tr>
                                    <td><?= $count + 1 ?></td>
                                    <td>
                                        <input name="class_name[]" readonly class="form-control"
                                               value="<?= $row['CLASS_NAME'] ?>"
                                               id="txt_class_id_<?= $count ?>"/>
                                        <input type="hidden" name="class_id[]" value="<?= $row['CLASS_ID'] ?>" id="h_txt_class_id_<?= $count ?>"/></td>
                                    <td>
                                        <a href="javascript:;" onclick="$(this).closest('tr').remove();"
                                           class="btn btn-xs red"> حذف</a>
                                    </td
                                    <?php $count++ ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th>

                                    <a onclick="javascript:add_row(this,'input',true);" href="javascript:;"><i
                                            class="glyphicon glyphicon-plus"></i>جديد</a>

                                </th>
                                <th></th>

                                <?php $count++; ?>
                            </tr>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                </div>

            </form>
        </div>
    </div>
</div>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>

<?php

$select_items_url = base_url("stores/classes/public_index");

$edit_script = <<<SCRIPT
    <script>

     reBind();

function do_after_select(){}
     function reBind(){
        $('input[name="class_name[]"]').click("focus",function(e){
        parent._showReport('$select_items_url/'+$(this).attr('id'),true);

    });
    }
    </script>
SCRIPT;


sec_scripts($edit_script);


?>
