<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/03/16
 * Time: 08:45 ص
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'electrical_loads';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");

$adapters_url=base_url("projects/adapter/public_index");
$group_url=base_url("$MODULE_NAME/feeder_groups/public_index");

$isCreate =isset($eload_data) && count($eload_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $eload_data[0];

if($isCreate and $eload_type==3){
    $select_url= $group_url;
    $change_name=1;
}else{
    $select_url= $adapters_url;
    $change_name=0;
}

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url.'/'.($HaveRs?$rs['ELOAD_TYPE']:$eload_type)?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url.'/1/'.($HaveRs?$rs['ELOAD_TYPE']:$eload_type) ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
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
                    <label class="control-label"> رقم السند </label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['ELOAD_ID']:''?>" id="txt_eload_id" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false) ) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['ELOAD_ID']:''?>" name="eload_id" id="h_eload_id">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">  الفرع </label>
                    <div>
                        <select name="branch" id="dp_branch" disabled class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> نوع التسوية </label>
                    <div>
                        <select name="eload_type000" id="dp_eload_type" disabled class="form-control" />
                        <option value="">_________</option>
                        <?php foreach($eload_types as $row) :?>
                            <option <?=$HaveRs?($rs['ELOAD_TYPE']==$row['CON_NO']?'selected':''):($eload_type==$row['CON_NO']?'selected':'')?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                        <input type="hidden" value="<?=$HaveRs?$rs['ELOAD_TYPE']:$eload_type?>" name="eload_type" id="h_eload_type">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> تاريخ التسوية </label>
                    <div>
                        <input type="text" name="eload_date" value="<?=$HaveRs?$rs['ELOAD_DATE']:date('d/m/Y')?>" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_eload_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                        <span class="field-validation-valid" data-valmsg-for="eload_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة السند </label>
                    <div>
                        <select id="dp_adopt" disabled class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($adopts as $row) :?>
                                <option <?=$HaveRs?($rs['ADOPT']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-8">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTE']:''?>" name="note" id="txt_note" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المدخل</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['ENTRY_USER_NAME']:''?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الادخال </label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['ENTRY_DATE']:''?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المعتمد</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['ADOPT_USER_NAME']:''?>" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الاعتماد </label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['ADOPT_DATE']:''?>" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", ($HaveRs)?$rs['ELOAD_ID']:0, ($HaveRs)?$rs['ADOPT']:0 ); ?>
            </div>


            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url) and !$isCreate and $rs['ADOPT']==1 and $rs['BRANCH']>0 ) : ?>
                    <button type="button" onclick='javascript:adopt(2);' class="btn btn-success"> اعتماد </button>
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
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="ser[]" value="0" /><input type="hidden" name="adapter_serial[]" id="h_txt_adapter_serial'+count+'" /><input name="adapter_name[]" readonly class="form-control"  id="txt_adapter_serial'+count+'" /></td> <td><input name="from_hour[]" class="form-control"  id="txt_from_hour'+count+'" maxlength="5" /></td> <td><input name="to_hour[]" class="form-control" id="txt_to_hour'+count+'" maxlength="5" /></td> <td><input name="notes[]" class="form-control" id="txt_notes'+count+'" /></td> <td><i class="glyphicon glyphicon-remove delete_adapter"></i></td> </tr>';
        $('#details_tb tbody').append(html);
        reBind(1);
    }

    function reBind(s){
        if(s==undefined)
            s=0;

        $('input[name="adapter_name[]"]').click("focus",function(e){
            _showReport('$select_url/'+$(this).attr('id'));
        });

        $('.delete_adapter').click(function(){
            var tr = $(this).closest('tr');
            tr.find('input[name="adapter_name[]"]').val('');
            tr.find('input[name="adapter_serial[]"]').val('');
            tr.find('input[name="from_hour[]"]').val('');
            tr.find('input[name="to_hour[]"]').val('');
            tr.find('input[name="notes[]"]').val('');
        });
    }

SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    $(function() {
        $( "#details_tb tbody" ).sortable();
    });

    if($change_name){
        $('th#adapter_name').text('المجموعة');
    }

    </script>
SCRIPT;

}else{
    $scripts = <<<SCRIPT
    {$scripts}

    count = {$rs['COUNT_DET']} -1;

    function adopt(no){
        if(no!=2) return 0;
        var msg= 'هل تريد اعتماد السند ؟!';
        if(confirm(msg)){
            var values= {eload_id: "{$rs['ELOAD_ID']}" };
            get_data('{$adopt_url}', values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم اعتماد السند بنجاح ..');
                    $('button').attr('disabled','disabled');
                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },700);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    </script>
SCRIPT;
}
sec_scripts($scripts);
?>
