<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/04/19
 * Time: 09:10 ص
 */

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'assigning_work_trans';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$def_month_date= date('d/m/Y', strtotime("first day of -1 month"));

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم السند </label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                            <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">المقر </label>
                        <div>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($branches as $row) :?>
                                    <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">الشهر</label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['MONTH']:$def_month_date?>" name="month" id="txt_month" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">حالة السند</label>
                        <div>
                            <select name="adopt" id="dp_adopt" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($adopt_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['ADOPT']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">

                    <?php if ( HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($adopt_url.'10') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                        <button type="button" onclick='javascript:adopt_(10);' class="btn btn-success"> ترحيل</button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($adopt_url.'_10') and !$isCreate and $rs['ADOPT']==10 ) : ?>
                        <button type="button" onclick='javascript:adopt_("_10");' class="btn btn-danger">الغاء الترحيل </button>
                    <?php endif; ?>

                </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

$(function(){

    change_date_format( $('#txt_month') , 'DEL');

    $('#txt_month').datetimepicker({
        format: 'MM/YYYY',
        minViewMode: "months",
        pickTime: false
    });

});

// add (01/) to date and delete it..
function change_date_format(obj,act){
    if(act=='ADD' && obj.val()!='')
        obj.val('01/'+obj.val());
    else if(act=='DEL')
        obj.val(obj.val().substr(3));
}


reBind();

function reBind(){
    $('.sel2:not("[id^=\'s2\']")').select2();
    $('#dp_adopt').select2('readonly',1);
    if(!{$all_branches})
        $('#dp_branch_id').select2('readonly',1);
} // reBind

$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var msg= 'هل تريد حفظ الطلب ؟!';
    if(confirm(msg)){
        $(this).attr('disabled','disabled');
        change_date_format( $('#txt_month') , 'ADD');
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(parseInt(data)>1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                get_to_link('{$get_url}/'+parseInt(data));
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

SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#dp_branch_id').select2('val','{$user_branch}');

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}else{ // get or edit
    $scripts = <<<SCRIPT
    {$scripts}

    function adopt_(no){
        var msg= 'هل تريد اعتماد الترحيل ؟!';
        if( no=='_10' ) msg= 'هل تريد بالتأكيد الغاء الترحيل بشكل نهائي؟!';
        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}" };
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');
                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
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
