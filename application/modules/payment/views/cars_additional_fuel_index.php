<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url = base_url('payment/cars_additional_fuel/delete');
$get_url = base_url('payment/cars_additional_fuel/get_id');
$edit_url = base_url('payment/cars_additional_fuel/edit');
$create_url = base_url('payment/cars_additional_fuel/create');
$get_page_url = base_url('payment/cars_additional_fuel/get_page');


$report_url = base_url('reports?type=31');
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if (HaveAccess($delete_url)): ?>
                <li><a onclick="javascript:user_delete();" href="javascript:;"><i
                            class="glyphicon glyphicon-remove"></i>حذف</a></li><?php endif; ?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label"> رقم السيارة </label>

                    <div>
                        <input type="text" name="car_no" id="txt_car_no" class="form-control">

                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> صاحب العهدة</label>

                    <div>
                        <input type="text" name="car_owner" id="txt_car_owner" class="form-control ltr" "="">


                    </div>


                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> البيان </label>

                    <div>
                        <input type="text" name="hints" id="txt_hints" class="form-control ltr" "="">


                    </div>


                </div>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();"
                        class="btn btn-default">تفريغ الحقول
                </button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('payment/cars_additional_fuel/get_page', $page, $action, $case); ?>
        </div>

    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>


    function user_delete(){

        if(confirm('هل تريد حذف الحساب ؟!!!')){

            var url = '{$delete_url}';

            var tbl = '#carsTbl';

            var container = $('#container');

            var val = [];

            $(tbl + ' .checkboxes:checked').each(function (i) {
                val[i] = $(this).val();

            });

            ajax_delete(url, val ,function(data){

                success_msg('رسالة','تم حذف السجلات بنجاح ..');
                container.html(data);

            });
        }

    }


    function do_search(){

        get_data('{$get_page_url}',{page: 1,action : '$action' ,hints:$('#txt_hints').val(),no : $('#txt_car_no').val(),name:$('#txt_car_owner').val(),branch:$('#dp_branch').val()},function(data){
            $('#container').html(data);
        },'html');
    }

  function LoadingData(){

    ajax_pager_data('#carsTbl > tbody',{action : '$action' ,hints:$('#txt_hints').val(),no : $('#txt_car_no').val(),name:$('#txt_car_owner').val(),branch:$('#dp_branch').val()});

    }


    function select_account(id,name,curr){
           selected = id;


           $('#menuModal').modal();
    }



</script>
SCRIPT;

sec_scripts($scripts);

?> 