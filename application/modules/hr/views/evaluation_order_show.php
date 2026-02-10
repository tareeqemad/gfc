<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani + mtaqia
 * Date: 12/06/16
 * Time: 10:42 ص
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_order';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$level_url= base_url("$MODULE_NAME/$TB_NAME/level_");

$isCreate =isset($order_data) && count($order_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $order_data[0];
$count=3;
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

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
                <!-- mtaqia -->
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم أمر التقييم</label>
                    <div>
                        <input type="text" name="evaluation_order_id" value="<?=$HaveRs?$rs['EVALUATION_ORDER_ID']:''?>" readonly id="txt_evaluation_order_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع أمر التقييم</label>
                    <div>
                        <select name="order_type" id="dp_order_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option value="">_________</option>
                            <?php foreach($order_types as $row) :?>
                                <option <?=$HaveRs?($rs['ORDER_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid"  data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">بداية التقييم </label>
                    <div>
                        <input type="text" name="order_start" value="<?=$HaveRs?$rs['ORDER_START']:''?>" id="txt_order_start" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                        <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نهاية التقييم</label>
                    <div>
                        <input type="text" name="order_end" value="<?=$HaveRs?$rs['ORDER_END']:''?>" id="txt_order_end" <?php if (!$isCreate and $rs['LEVEL_ACTIVE']==2 || $rs['LEVEL_ACTIVE']==4) : ?> disabled <?php endif; ?> data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                        <span class="field-validation-valid" data-valmsg-for="to_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <?php if ( (HaveAccess($adopt_url.'3') and !$isCreate and $rs['STATUS']==2 and $rs['LEVEL_ACTIVE']<3 and $rs['ORDER_END_EXTENTION']=='') or (!$isCreate and $rs['ORDER_END_EXTENTION']!='') ) : ?>
                    <div class="form-group col-sm-2">
                        <label class="control-label">نهاية التقييم بعد التمديد</label>
                        <div>
                            <input type="text" name="order_end_extention" value="<?=$HaveRs?$rs['ORDER_END_EXTENTION']:''?>" id="txt_order_end_extention" <?php if (!$isCreate and$rs['LEVEL_ACTIVE']==4) : ?> disabled <?php endif; ?> data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                            <span class="field-validation-valid" data-valmsg-for="to_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group col-sm-1">
                    <label class="control-label">حالة أمر التقييم</label>
                    <div>
                        <select name="status" id="dp_status" disabled class="form-control"  >
                            <option value="">_________</option>
                            <?php foreach($statuss as $row) :?>
                                <option <?=$HaveRs?($rs['STATUS']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid"  data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">مرحلة التقييم</label>
                    <div>
                        <select name="level_active" id="dp_level_active" disabled class="form-control"  >
                            <option value="">_________</option>
                            <?php foreach($level_actives as $row) :?>
                                <option <?=$HaveRs?($rs['LEVEL_ACTIVE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid"  data-valmsg-replace="true"></span>
                    </div>
                </div>

                <?php if ( HaveAccess($level_url.'5') and !$isCreate and $rs['LEVEL_ACTIVE']>=4 ) : ?>
                    <div class="form-group col-sm-2">
                        <label class="control-label">بداية التظلم</label>
                        <div>
                            <input type="text" name="grievance_start" value="<?=$HaveRs?$rs['GRIEVANCE_START']:''?>" id="txt_grievance_start" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                            <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">نهاية التظلم</label>
                        <div>
                            <input type="text" name="grievance_end" value="<?=$HaveRs?$rs['GRIEVANCE_END']:''?>" id="txt_grievance_end" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                            <span class="field-validation-valid" data-valmsg-for="to_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>
               <?php endif; ?>

                <div style="clear: both"></div>
                <div class="form-group col-sm-10">
                    <label class="control-label">بيان أمر التقييم</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTE']:''?>" name="note" id="txt_note" data-val="false"  data-val-required="حقل مطلوب" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <?php if ( (HaveAccess($adopt_url.'3') and !$isCreate and $rs['STATUS']==2 and $rs['LEVEL_ACTIVE']<3 and $rs['ORDER_END_EXTENTION']=='') or (!$isCreate and $rs['ORDER_END_EXTENTION']!='') ) : ?>
                    <div class="form-group col-sm-10">
                        <label class="control-label">بيان التمديد</label>
                        <div>
                            <input type="text" value="<?=$HaveRs?$rs['EXTENTION_NOTE']:''?>" name="extention_note" id="txt_extention_note" data-val="false"  data-val-required="حقل مطلوب" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group col-sm-10">
                    <label class="control-label">شروط ومحددات التقييم (سياسة التقييم)</label>
                    <div>
                        <textarea rows="5" name="conditions" id="txt_conditions" class="form-control" ><?=$HaveRs?$rs['CONDITIONS']:''?></textarea>
                    </div>
                </div>

                <div class="form-group col-sm-10">
                    <label class="control-label">الجدول الزمني</label>
                    <div>
                        <textarea rows="5" name="time_table" id="txt_time_table" class="form-control" ><?=$HaveRs?$rs['TIME_TABLE']:''?></textarea>
                    </div>
                </div>

                <?php if($HaveRs){ ?>
                    <hr>
                    <div style="clear: both;">
                        <a style="width: 100px; margin-left: 10px" href="javascript:;" onclick="javascript:show_adopts();" class="icon-btn">
                            <i class="glyphicon glyphicon-list"></i>
                            <div> بيانات الإعتماد </div>
                        </a>
                    </div>
                <?php } ?>
                <!-- mtaqia -->
            </div>

            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) && ($isCreate || ( $rs['STATUS']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'2') and !$isCreate and $rs['STATUS']==1 ) : ?>
                    <button type="button" onclick='javascript:adopt(2);' class="btn btn-success"> اعتماد </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'0') and !$isCreate and $rs['STATUS']==2 ) : ?>
                    <button type="button" onclick='javascript:adopt(0);' class="btn btn-danger">إلغاء الإعتماد</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'3') and !$isCreate and $rs['STATUS']==2 and $rs['LEVEL_ACTIVE']<=2 ) : ?>
                    <button type="button" onclick='javascript:adopt(3);' class="btn btn-success">تمديد التقييم</button>
                <?php endif; ?>

                <?php if ( HaveAccess($level_url.'3') and !$isCreate and $rs['LEVEL_ACTIVE']==2 ) : ?>
                    <button type="button" onclick='javascript:level_act(3,"التدقيق");' class="btn btn-success">تدقيق</button>
                <?php endif; ?>

                <?php if ( HaveAccess($level_url.'4') and !$isCreate and $rs['LEVEL_ACTIVE']==3 ) : ?>
                    <button type="button" onclick='javascript:level_act(4,"إعلان النتائج");' class="btn btn-success">إعلان النتائج</button>
                <?php endif; ?>

                <?php if ( HaveAccess($level_url.'5') and !$isCreate and $rs['LEVEL_ACTIVE']==4 ) : ?>
                    <button type="button" onclick='javascript:level_act(5,"فترة التظلم",<?=$rs['STATUS']?>);' class="btn btn-success">فترة التظلم</button>
                <?php endif; ?>

                <?php if ( HaveAccess($level_url.'6') and !$isCreate and $rs['LEVEL_ACTIVE']==5 ) : ?>
                    <button type="button" onclick='javascript:level_act(6,"نتائج التظلمات");' class="btn btn-success">اعتماد نتائج التظلمات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($level_url.'7') and !$isCreate and $rs['LEVEL_ACTIVE']==6 ) : ?>
                    <button type="button" onclick='javascript:level_act(7,"النتائج النهائي");' class="btn btn-success">اعتماد نهائي</button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>

<!-- mtaqia -->

<div class="modal fade" id="adopts_Modal">
    <div class="modal-dialog" style="width: 900px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات الإعتماد</h4>
            </div>
            <div class="modal-body">
                <table class="table" data-container="container">
                    <thead>
                    <tr>
                        <th>م</th>
                        <th>حالة أمر التقييم</th>
                        <th>المستخدم</th>
                        <th>التاريخ</th>
                        <th>البيان</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>إدخال أمر التقييم</td>
                            <td><?=$rs["ENTRY_USER_NAME"]?></td>
                            <td><?=$rs["ENTRY_DATE"]?></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>اعتماد أمر التقييم</td>
                            <td><?=$rs["ADOPT_USER_NAME"]?></td>
                            <td><?=$rs["ADOPT_DATE"]?></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>تمديد أمر التقييم</td>
                            <td><?=$rs["EXTENTION_USER_NAME"]?></td>
                            <td><?=$rs["EXTENION_DATE"]?></td>
                            <td><?=$rs["EXTENTION_NOTE"]?></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table" data-container="container">
                    <thead>
                    <tr>
                        <th>المرحلة</th>
                        <th>مرحلة التقييم</th>
                        <th>المستخدم</th>
                        <th>التاريخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach(range(3, 7) as $i) :?>
                    <tr>
                        <td><?=$count?></td>
                        <td><?=$level_active_all[$i-1]['CON_NAME']?></td>
                        <td><?=$rs["LEVEL_USER_".$i."_NAME"]?></td>
                        <td><?=$rs["LEVEL_DATE_".$i]?></td>
                    </tr>
                    <?php $count++; endforeach; ?>
                    </tbody>
                </table>


            </div> <!-- /.modal-body -->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- mtaqia -->


<?php
$scripts = <<<SCRIPT
<script>

    //$('#dp_section_no').select2();

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

    function show_adopts(){
        $('#adopts_Modal').modal();
    }


    /* re-order date from : day/month/year  TO  year/month/day */
    function reorder_date(val) {
			year = val.slice(6,10);
			month = val.slice(3,5);
			day = val.slice(0,2);
			return Date.parse(year+'/'+month+'/'+day);
	}

    function adopt(no){
            if(no==3){
                order_end_date = reorder_date($('#txt_order_end').val());
                extention_end_date = reorder_date($('#txt_order_end_extention').val());
			}
                if((no==3 && order_end_date>=extention_end_date) || ($('#txt_order_end_extention').val() <= 0 && no==3)){
                    danger_msg('تحذير..','ادخل تاريخ تمديد صحيح');
                }else{
                    if(confirm('هل تريد إجراء العملية ؟!')){
                    var values= {evaluation_order_id: "{$rs['EVALUATION_ORDER_ID']}",
                    extention_note: $('#txt_extention_note').val(),
                    order_end_extention: $('#txt_order_end_extention').val()};
                    get_data('{$adopt_url}'+no, values, function(ret){
                        if(ret==1){
                            success_msg('رسالة','تم إجراء العملية بنجاح ..');
                            $('button').attr('disabled','disabled');
                        }else{
                            danger_msg('تحذير..',data);
                        }
                    }, 'html');
                    }
                }
    }

    function level_act(no,op,status){
        if(no==5){
            grievance_start = reorder_date($('#txt_grievance_start').val());
            grievance_end = reorder_date($('#txt_grievance_end').val());
            order_end_date = reorder_date($('#txt_order_end').val());
                if(status == 3) {
                order_end_date = reorder_date($('#txt_order_end_extention').val());
                }
        }

            if((no==5 && grievance_start>=grievance_end) || ($('#txt_grievance_start').val() <= 0) || ($('#txt_grievance_end').val() <= 0) || (no==5 && order_end_date>grievance_start)){
                danger_msg('تحذير..','ادخل تاريخ فترة تظلم صحيح');
            }else{
                if(confirm('هل تريد اعتماد '+ op + ' ؟')){
                    var values= {evaluation_order_id: "{$rs['EVALUATION_ORDER_ID']}",
                                 grievance_start: $('#txt_grievance_start').val(),
                                 grievance_end: $('#txt_grievance_end').val()};
                    get_data('{$level_url}'+no, values, function(ret){
                        if(ret==1){
                            success_msg('تم اعتماد '+ op + '  بنجاح','رسالة');
                            $('button').attr('disabled','disabled');
                        }else{
                            danger_msg('تحذير..',data);
                        }
                    }, 'html');
                }
            }
    }

    </script>
SCRIPT;
}
sec_scripts($scripts);
?>
<!-- mtaqia -->
