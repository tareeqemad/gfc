<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 28/09/16
 * Time: 11:48 ص
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'gedco_execute_gpolicy';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");

$isCreate =isset($execute_gpolicy_data) && count($execute_gpolicy_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $execute_gpolicy_data[0];
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
                        <span class="field-validation-valid"  data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الإدارة</label>
                    <div>
                        <select name="admin_id" id="dp_admin_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option value="">_________</option>
                            <?php foreach($gcc_structure as $row) :?>
                                <option <?=$HaveRs?($rs['ADMIN_ID']==$row['ST_ID']?'selected':''):''?> value="<?=$row['ST_ID']?>"><?=$row['ST_ID']." : ".$row['ST_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid"  data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مدير الدائرة</label>
                    <div>
                        <select name="dept_manager_no" id="dp_dept_manager_no" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option value="">_________</option>
                            <?php foreach($employee as $row) :?>
                                <option <?=$HaveRs?($rs['DEPT_MANAGER_NO']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid"  data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الدائرة</label>
                    <div>
                        <select name="dept_id" id="dp_dept_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option value="">_________</option>
                            <?php foreach($gcc_structure_dep as $row) :?>
                                <option <?=$HaveRs?($rs['DEPT_ID']==$row['ST_ID']?'selected':''):''?> value="<?=$row['ST_ID']?>"><?=$row['ST_ID']." : ".$row['ST_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid"  data-valmsg-replace="true"></span>
                        <input type="hidden" name="ser" value="<?=$HaveRs?$rs['SER']:''?>" id="txt_ser" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">التقدير</label>
                    <div>
                        <select name="degree_no" id="dp_degree_no" class="form-control" >
                            <option value=""></option>
                            <?php foreach($degree as $row) :?>
                                <option <?=$HaveRs?($rs['RANGE_ORDER']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>"><?=$row['CON_NO']." : ".$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">العدد المسموح به للدائرة</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['RATING_NUMBER']:''?>" name="rating_number" id="txt_rating_number" data-val="false"  data-val-required="حقل مطلوب" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-11">
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
		$('#dp_evaluation_order_id, #dp_admin_id, #dp_dept_manager_no, #dp_dept_id, #dp_degree_no').select2();
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
