<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 28/09/16
 * Time: 07:01 م
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'gedco_penalty';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");

$isCreate =isset($gedco_penalty_data) && count($gedco_penalty_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $gedco_penalty_data[0];
$count=1;
echo AntiForgeryToken();

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><i class="glyphicon glyphicon-check"></i><?= $title ?></div>
        <ul>
            <?php if(HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
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
                    <label class="control-label">أمر التقييم</label>
                    <div>
                        <select name="evaluation_order_id" id="dp_evaluation_order_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option value="">_________</option>
                            <?php foreach($order_id as $row) :?>
                                <option <?=$HaveRs?($rs['EVALUATION_ORDER_ID']==$row['EVALUATION_ORDER_ID']?'selected':''):''?> value="<?= $row['EVALUATION_ORDER_ID'] ?>"><?= $row['EVALUATION_ORDER_ID'].' - '.substr($row['ORDER_START'],-4).' - '?><?=($row['ORDER_TYPE']==1) ? 'سنوي' : 'نصفي' ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="ser" value="<?=$HaveRs?$rs['SER']:''?>" id="txt_ser" class="form-control" />
                        <span class="field-validation-valid"  data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_id" id="dp_emp_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option value="">_________</option>
                            <?php foreach($employee as $row) :?>
                                <option <?=$HaveRs?($rs['EMP_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid"  data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نسبة الخصم</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['DISCOUNT']:'-10'?>" name="discount" id="txt_discount" data-val="false" readonly data-val-required="حقل مطلوب" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="discount" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-10">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTE']:''?>" name="note" id="txt_note" data-val="false"  data-val-required="حقل مطلوب" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
                    </div>
                </div>
            </div>

                <div class="modal-footer">
                    <?php if ( HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($adopt_url) and !$isCreate and $rs['ADOPT']==1) : ?>
                        <button type="button" onclick='javascript:adopt();' class="btn btn-success"> اعتماد </button>
                    <?php endif; ?>
                </div>
        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    $(document).ready(function() {
		$('#dp_evaluation_order_id, #dp_emp_id').select2();
    });

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

SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    </script>
SCRIPT;

}else{
    $scripts = <<<SCRIPT
{$scripts}

    function adopt(){
        if(confirm('هل تريد إجراء العملية ؟!')){
            var values= {ser: "{$rs['SER']}"};
            get_data('{$adopt_url}', values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم إجراء العملية بنجاح ..');
                    $('button').attr('disabled','disabled');
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
