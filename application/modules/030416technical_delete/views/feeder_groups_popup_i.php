<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 04/01/16
 * Time: 10:02 ص
 */

$get_page_url = base_url('technical/feeder_groups/public_get_page');
echo AntiForgeryToken();
?>


<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>

    <div class="form-body">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المجموعة</label>
                    <div>
                        <input type="text" name="group_id" id="txt_group_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">مسلسل المجموعة</label>
                    <div>
                        <input type="text" name="group_ser" id="txt_group_ser" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">اسم المجموعة</label>
                    <div>
                        <input type="text" name="group_name" id="txt_group_name" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم الفرع </label>
                    <div>
                        <select name="branch" id="dp_branch" class="form-control" />
                        <option></option>
                        <?php foreach($branch_all as $row) :?>
                            <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('technical/feeder_groups/public_get_page',$page); ?>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    function group_select(id, name ){
        parent.$('#$txt').val(name);
        parent.$('#h_$txt').val(id);
        parent.$('#report').modal('hide');
    }

    function search(){
        get_data('{$get_page_url}',{page:1, group_id:$('#txt_group_id').val(), group_ser:$('#txt_group_ser').val(), group_name:$('#txt_group_name').val(), branch:$('#dp_branch').val() },function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        ajax_pager_data('#page_tb > tbody',{group_id:$('#txt_group_id').val(), group_ser:$('#txt_group_ser').val(), group_name:$('#txt_group_name').val(), branch:$('#dp_branch').val()});
    }

</script>
SCRIPT;
sec_scripts($scripts);

?>
