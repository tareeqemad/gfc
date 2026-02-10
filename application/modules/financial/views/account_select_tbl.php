<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 01/12/14
 * Time: 09:19 ص
 */

$refresh_page_url = base_url("financial/accounts/public_delete_cache_files");

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li style="display: none"><a onclick="refresh_page();" href="javascript:;"><i
                            class="glyphicon glyphicon-refresh"></i>تحديث</a></li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-4">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tbl" data-set="accountsTbl" class="form-control" placeholder="بحث">
            </div>
        </div>
        <div id="container">
            <table class="table selected-red" id="accountsTbl" data-container="container">
                <thead>
                <tr>
                    <th>رقم الحساب</th>
                    <th>اسم الحساب</th>

                    <th>عملة الحساب</th>


                </tr>
                </thead>
                <tbody>

                <?php foreach ($accounts as $row) : ?>
                    <tr class="level-<?= strlen($row['ACOUNT_ID']) ?>"
                        ondblclick="javascript:select_account('<?= $row['ACOUNT_ID'] ?>','<?= $row['ACOUNT_NAME'] ?>',<?= $row['CURR_ID'] ?>,<?= $row['ACOUNT_CASH_FLOW'] ?>);">

                        <td class="align-left"><?= $row['ACOUNT_ID'] ?></td>
                        <td class="align-right"><?= replace_d_dsh($row['ACOUNT_ID']) . $row['ACOUNT_NAME'] ?></td>

                        <td><?= $row['CURR_NAME'] ?></td>


                    </tr>


                <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    </div>
</div>

<?php


$scripts = <<<SCRIPT

<script>
    $(function () {

        $('#accountsModal').on('shown.bs.modal', function () {
            $('#txt_acount_name').focus();
        });

        $('#accounts').tree();
    });

    function select_account(id, name, curr,cash_flow) {
        var level = 5;

        if (level >= 5) {
            var text = name;
            var cr = parent.$('#dp_curr_id');

            if (parent.$('#dp_curr_id').attr('data-curr') != 'false')
                if (cr != undefined && cr.length > 0)
                    if ($(cr).val() != curr) {
                        alert('تحذير عملة الحساب تختلف عن عملة القيد ؟!');
                        return;
                    }

            parent.$('#$txt').val(text);
            parent.$('#h_$txt').val(id);
            if (parent.$('#$txt').attr('data-balance') != 'false')

                if (typeof  parent.update_balance == 'function') {
                    parent.update_balance(parent.$('#h_$txt'));
                }

            if (typeof  parent.after_selected == 'function') {
                parent.after_selected();
            }


            var tr = parent.$('#$txt').closest('tr');
            
            if(cash_flow == 5) {
                parent.$('select[name="bk_fin_id[]"]',$(tr)).show();
            } else {
				parent.$('select[name="bk_fin_id[]"]',$(tr)).val(0);
				parent.$('select[name="bk_fin_id[]"]',$(tr)).find(":selected").removeProp('selected');
                parent.$('select[name="bk_fin_id[]"]',$(tr)).hide();
				
				console.log('',parent.$('select[name="bk_fin_id[]"] option:selected',$(tr)).text());
          
            } 
			
			
            parent.$('#report').modal('hide');
            
            
        }
    }

    // mkilani- refresh view..
    function refresh_page() {
        if (confirm('سيتم اغلاق الشاشة ! هل تريد بالتأكيد التحديث ؟!')) {
            get_data('{$refresh_page_url}', {action: 'del'}, function (data) {
                if (data == 1)
                    parent.$('#report').modal('hide');
                else
                    alert('خطأ');
            }, 'html');
        }
    }

</script>


SCRIPT;

sec_scripts($scripts);


?>

