<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 16/12/15
 * Time: 08:41 ص
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'feeder_groups';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

if(HaveAccess($edit_url))
    $edit= 'edit';
else
    $edit= '';

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
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

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المجموعة</label>
                    <div>
                        <input type="text" name="group_name" id="txt_group_name" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
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

                <div class="form-group col-sm-2">
                    <label class="control-label">الخط المغذي</label>
                    <div>
                        <select name="line_feeder" id="dp_line_feeder" class="form-control" />
                        <option></option>
                        <?php foreach($line_feeder_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $group_id, $group_ser, $group_name, $branch, $line_feeder);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/{$edit}');
    }

    function search(){
        var values= {page:1, group_id:$('#txt_group_id').val(), group_ser:$('#txt_group_ser').val(), group_name:$('#txt_group_name').val(), branch:$('#dp_branch').val(), line_feeder:$('#dp_line_feeder').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {group_id:$('#txt_group_id').val(), group_ser:$('#txt_group_ser').val(), group_name:$('#txt_group_name').val(), branch:$('#dp_branch').val(), line_feeder:$('#dp_line_feeder').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
