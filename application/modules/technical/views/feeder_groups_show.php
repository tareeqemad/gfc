<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 26/12/15
 * Time: 09:45 ص
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'feeder_groups';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");

$adapters_url=base_url("projects/adapter/public_index");
$print_url= 'http://itdev:801/gfc.aspx';

$isCreate =isset($group_data) && count($group_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $group_data[0];

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المجموعة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['GROUP_ID']:''?>" id="txt_group_id" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false) ) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['GROUP_ID']:''?>" name="group_id" id="h_group_id">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">مسلسل المجموعة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['GROUP_SER']:''?>" name="group_ser" id="txt_group_ser" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">  الفرع </label>
                    <div>
                        <select name="branch" id="dp_branch" <?=$HaveRs?'disabled':''?> class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">اسم المجموعة</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['GROUP_NAME']:''?>" name="group_name" id="txt_group_name" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الخط المغذي</label>
                    <div>
                        <select name="line_feeder" id="dp_line_feeder" class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($line_feeders as $row) :?>
                                <option <?=$HaveRs?($rs['LINE_FEEDER']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="clear: both"></div>
                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", ($HaveRs)?$rs['GROUP_ID']:0, ($HaveRs)?1:0 ); ?>
            </div>

            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) && ($isCreate || ( isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( !$isCreate  and 0 ) : ?>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>

                <?php if ($isCreate and 0 ): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    var count = 0;
    reBind();

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ السند ؟!';
        if(confirm(msg)){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data)+'/edit');
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

    function addRow(){
        count = count+1;
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="ser[]" value="0" /> <input name="adapter_serial_name[]" readonly class="form-control" id="txt_adapter_serial'+count+'" /> <input type="hidden" name="adapter_serial[]" id="h_txt_adapter_serial'+count+'" /></td> <td><i class="glyphicon glyphicon-remove delete_adapter"></i></td> <td></td> </tr>';
        $('#details_tb tbody').append(html);
        reBind(1);
        return count;
    }

    $('input[type="text"],body').bind('keydown', 'down', function() {
        addRow();
        return false;
    });

    $('.delete_adapter').click(function(){
        var tr = $(this).closest('tr');
        tr.find('input[name="adapter_serial_name[]"]').val('');
        tr.find('input[name="adapter_serial[]"]').val('');
    });

    function reBind(s){
        if(s==undefined)
            s=0;
        $('input[name="adapter_serial_name[]"]').click("focus",function(e){
            _showReport('$adapters_url/'+$(this).attr('id'));
        });

        $('.delete_adapter').click(function(){
        var tr = $(this).closest('tr');
        tr.find('input[name="adapter_serial_name[]"]').val('');
        tr.find('input[name="adapter_serial[]"]').val('');
    });
    }

SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    if( $("#dp_branch").val()=='' )
        $("#dp_branch").val('$branch');

    $(function() {
        $( "#details_tb tbody" ).sortable();
    });

    </script>
SCRIPT;

}else{
    $scripts = <<<SCRIPT
    {$scripts}

    count = {$rs['COUNT_DET']} -1;

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
