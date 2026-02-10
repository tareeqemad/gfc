<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/06/15
 * Time: 09:33 ص
 */

$MODULE_NAME= 'budget';
$TB_NAME= 'budget_amendment';

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

        <div class="caption"><?= $title.' موازنة عام '.$year ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" name="amendment_id" id="txt_amendment_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2" style="display: none">
                    <label class="control-label"> الفرع </label>
                    <div>
                        <select name="branch" id="dp_branch" class="form-control" />
                        <option></option>
                        <?php foreach($branch_all as $row) :?>
                            <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2" style="display: none">
                    <label class="control-label"> الفرع المنقول اليه </label>
                    <div>
                        <select name="to_branch" id="dp_to_branch" class="form-control" />
                        <option></option>
                        <?php foreach($branch_all as $row) :?>
                            <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> نوع السند </label>
                    <div>
                        <select name="amendment_type" id="dp_amendment_type" class="form-control" />
                        <option></option>
                        <?php foreach($amendment_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الطلب</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control" />
                        <option></option>
                        <?php foreach($adopt_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المدخل</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control" />
                        <option></option>
                        <?php foreach($entry_user_all as $row) :?>
                            <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الفصل</label>
                    <div>
                        <select name="section" class="form-control" id="dp_section">
                            <option ></option>
                            <option  value="0" >--</option>
                            <?php foreach($sectionss as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" value="" name="note" id="txt_note" class="form-control" />
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
            <?=modules::run($get_page_url, $page, $amendment_id, $branch, $to_branch, $amendment_type, $adopt, $entry_user,$section,$note );?>
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>
 $('select[name="section"]').select2();

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/{$edit}');
    }

    function search(){
        var values= {page:1, amendment_id:$('#txt_amendment_id').val(), branch:$('#dp_branch').val(), to_branch:$('#dp_to_branch').val(), amendment_type:$('#dp_amendment_type').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val(), section: $('#dp_section').select2('val'),note:$('#txt_note').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {amendment_id:$('#txt_amendment_id').val(), branch:$('#dp_branch').val(), to_branch:$('#dp_to_branch').val(), amendment_type:$('#dp_amendment_type').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val(), section: $('#dp_section').select2('val'),note:$('#txt_note').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
