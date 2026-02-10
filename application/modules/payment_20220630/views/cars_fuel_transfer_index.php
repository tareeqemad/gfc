<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url = base_url('payment/cars_fuel_transfer/delete');
$get_url = base_url('payment/cars_fuel_transfer/get_id');
$edit_url = base_url('payment/cars_fuel_transfer/edit');
$create_url = base_url('payment/cars_fuel_transfer/create');
$get_page_url = base_url('payment/cars_fuel_transfer/get_page');


$adopt_url = base_url('payment/cars_fuel_transfer/paid');
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
                    <label class="control-label">من سيارة</label>

                    <div>
                        <input type="text" name="from_file_id" id="txt_from_file_id" class="form-control">

                    </div>
                </div>



                <div class="form-group col-sm-1">
                    <label class="control-label"> الي السيارة </label>

                    <div>
                        <input type="text" name="to_file_id" id="txt_to_file_id" class="form-control">

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
            <?php echo modules::run('payment/cars_fuel_transfer/get_page', $page); ?>
        </div>

    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>



    function do_search(){

        get_data('{$get_page_url}',{page: 1,from_file_id:$('#txt_from_file_id').val() ,to_file_id:$('#txt_to_file_id').val()},function(data){
            $('#container').html(data);
        },'html');
    }

  function LoadingData(){

    ajax_pager_data('#carsTbl > tbody',{from_file_id:$('#txt_from_file_id').val() ,to_file_id:$('#txt_to_file_id').val()});

    }


</script>
SCRIPT;

sec_scripts($scripts);

?> 