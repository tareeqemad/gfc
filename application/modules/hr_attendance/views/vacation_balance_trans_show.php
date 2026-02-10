<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/02/20
 * Time: 10:21 ص
 */

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'vacation_balance_trans';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$get_balance_url= base_url("$MODULE_NAME/vacation_request/public_get_balance");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title.(($HaveRs)?' - '.$rs['EMP_NO_NAME']:'')?></div>
        <ul>
            <?php if( 0 and HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="col-sm-9">

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم السند </label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                            <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label">الموظف</label>
                        <div>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($emp_no_cons as $row) :?>
                                    <option <?=$HaveRs?($rs['EMP_NO']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">السنة المرحل منها</label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['YEAR_FROM']:""?>" name="year_from" id="txt_year_from" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">السنة المرحل اليها</label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['YEAR_TO']:""?>" name="year_to" id="txt_year_to" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الرصيد المرحل</label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['BALANCE_VAL']:""?>" name="balance_val" id="txt_balance_val" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
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


                <div class="col-md-3" id="div_emp_balance">
                </div>

            </div>

            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) &&  ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'2') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" onclick='javascript:adopt_(2);' class="btn btn-success">اعتماد </button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script>

reBind();

function reBind(){
    $('.sel2:not("[id^=\'s2\']")').select2();
    get_balance();
} // reBind

$('#dp_adopt').select2('readonly',1);

$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var msg= 'هل تريد حفظ السجل ؟!';
    if(confirm(msg)){
        $(this).attr('disabled','disabled');
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


$('#dp_emp_no').change(function(){
    get_balance();
});

$('#txt_year_from').change(function(){
    var year_from= $('#txt_year_from').val();
    if(year_from < 2018){
        danger_msg('تنبيه..','لا يمكن الترحيل من سنوات قديمة');
        return 0;
    }
    $('#txt_year_to').val(parseInt(year_from)+1);
    get_balance();
});

function get_balance(){
    $('#div_emp_balance').text('');
    emp_no= $('#dp_emp_no').select2('val');
    vac_year= $('#txt_year_from').val();
    if(emp_no!='' && vac_year!='' && vac_year >= '2018'){
        get_data('{$get_balance_url}', {emp_no:emp_no, vac_year:vac_year}, function(data){
            $('#div_emp_balance').html(data);
        }, 'html');
    }
}

SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    get_balance();

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}else{ // get or edit
    $scripts = <<<SCRIPT
    {$scripts}

    function adopt_(no){
        var msg= 'لا يمكن التراجع عن هذه العملية بعد الموافقة، هل تريد بالتأكيد اعتماد السجل؟؟';
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
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }

    </script>
SCRIPT;
}
sec_scripts($scripts);
?>
