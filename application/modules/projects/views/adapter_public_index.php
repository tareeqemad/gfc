<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url = base_url('projects/adapter/delete');
$get_url = base_url('projects/adapter/get_id');
$edit_url = base_url('projects/adapter/edit');
$create_url = base_url('projects/adapter/create');
$get_page_url = base_url('projects/adapter/public_get_page');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>


    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label"> الرقم </label>

                    <div>
                        <input type="text" name="adapter_serial" id="txt_adapter_serial" class="form-control">

                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">الاسم المحول</label>

                    <div>
                        <input type="text" name="power_adapter" id="txt_power_adapter" class="form-control ltr" "="">


                    </div>


                </div>
                <?php if (get_curr_user()->branch == 1): ?>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> الفرع</label>

                        <div>
                            <select type="text" name="branch" id="dp_branch" class="form-control">
                                <option></option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search_adapter();" class="btn btn-success"> إستعلام</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>
        <div>
            <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i
                    class="icon icon-download"></i> إدراج المختار </a>
        </div>
        <div id="container">
            <?php echo modules::run('projects/adapter/public_get_page', $page); ?>
        </div>
        <div>
            <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i
                    class="icon icon-download"></i> إدراج المختار </a>
        </div>
    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>


    function adapter_select(id , name,m ){

        if('$txt' == 'adapterList'){

            parent.$('#adapterList tbody').append('<tr><td class=""><input type="hidden" name="adapter[]" value ="'+id+'"/><input type="hidden" name="adapter_name[]" value =\'{"id" : '+id+',"name" : "'+name+'" }\'/>'+name+'</td><td data-action="delete"><a data-action="delete" href="javascript:;" onclick="javascript:$(this).closest(\'tr\').remove();"><i class="icon icon-trash delete-action"></i> </a></td></tr>');

            if(!m){
                parent.$('#report').modal('hide');
            }

        } // mkilani
        else if(m && '$txt'.indexOf('txt_adapter_serial') != -1) {
            var cnt= parent.addRow();
            parent.$('#txt_adapter_serial'+cnt).val(name);
            parent.$('#h_txt_adapter_serial'+cnt).val(id);

        } else {
            parent.$('#$txt').val(name);
            parent.$('#h_$txt').val(id);
            parent.$('#report').modal('hide');
        }
    }

    function search_adapter(){

        get_data('{$get_page_url}',{page: 1,no : $('#txt_adapter_serial').val(),name:$('#txt_power_adapter').val(),branch:$('#dp_branch').val()},function(data){
            $('#container').html(data);
        },'html');
    }

    function select_choose(){
        $('.checkboxes:checked').each(function(i){
            var obj = jQuery.parseJSON($(this).attr('data-val'));
            adapter_select(obj.ADAPTER_SERIAL , obj.ADAPTER_NAME ,true )
        });

       parent.$('#report').modal('hide');
    }

  function LoadingData(){

    ajax_pager_data('#adapterTbl > tbody',{no : $('#txt_adapter_serial').val(),name:$('#txt_power_adapter').val(),branch:$('#dp_branch').val()});

    }


</script>
SCRIPT;

sec_scripts($scripts);



?>

