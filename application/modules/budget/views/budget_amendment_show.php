<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/06/15
 * Time: 10:20 ص
 */

$MODULE_NAME= 'budget';
$TB_NAME= 'budget_amendment';

$back_url=base_url("$MODULE_NAME/$TB_NAME/index"); //$action

$isCreate =isset($amendment_data) && count($amendment_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $amendment_data[0];

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$det_page_url= base_url("$MODULE_NAME/$TB_NAME/public_get_details");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");

$js_amendment_id=0;
$js_adopt=1;
if($HaveRs){
    $js_amendment_id= $rs['AMENDMENT_ID'];
    $js_adopt= $rs['ADOPT'];
}

//$print_url ='https://itdev.gedco.ps/gfc.aspx';
$print_url = base_url("JsperReport/showreport?sys=financial");
$gfc_domain= gh_gfc_domain();
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title.' موازنة عام '.$year ?></div>

        <ul>
            <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
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
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['AMENDMENT_ID']:''?>" id="txt_amendment_id" class="form-control">
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" name="amendment_id" value="<?=$HaveRs?$rs['AMENDMENT_ID']:''?>" id="h_amendment_id">
                        <?php endif; ?>
                    </div>
                </div>

            <?php if(0){ ?>
                <div class="form-group col-sm-1">
                    <label class="control-label">المقر</label>
                    <div>
                        <select id="dp_branch" name="branch" class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1" id="to_branch" style="display: none">
                    <label class="control-label">المقر المنقول اليه</label>
                    <div>
                        <select id="dp_to_branch" name="to_branch" class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['TO_BRANCH']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php } ?>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع السند </label>
                    <div>
                        <select name="amendment_type" id="dp_amendment_type" class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($amendment_types as $row) :?>
                                <option <?=$HaveRs?($rs['AMENDMENT_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">السنة المالية</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['YEAR']:''?>" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
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

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الادخال</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['ENTRY_DATE']:''?>" id="txt_entry_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTE']:''?>" name="note" id="txt_note" class="form-control" />
                    </div>
                </div>

                <?php if ($HaveRs and 0) : ?>
                    <div class="form-group col-sm-9">
                        <label class="control-label">ملاحظة الاعتماد / الارجاع</label>
                        <div>
                            <input type="text" name="adopt_note" id="txt_adopt_note" class="form-control" />
                        </div>
                    </div>
                <?php endif; ?>

                <div style="clear: both"></div>

                <div id="details_page">
                    <?php if($HaveRs){
                        echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", (count($rs))?$rs['AMENDMENT_ID']:0, (count($rs))?$rs['ADOPT']:0, (count($rs))?$rs['AMENDMENT_TYPE']:0 );
                    }
                    ?>
                </div>

            </div>

            <div class="modal-footer">
                <?php if ( !$isCreate  and $rs['ADOPT']>=6 ) : ?>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>

                <?php if ( !$isCreate  and $rs['ADOPT']>=1 and $rs['ADOPT']<=5 ) : ?>
                    <button type="button" id="print_rep_ba" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> التقرير قبل الاعتماد </button>
                <?php endif; ?>

                <?php if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <button type="button" onclick="$('#details_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-info">  اكسل </button>

                <?php if ( HaveAccess($adopt_url.'2') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" onclick='javascript:adopt(2);' class="btn btn-success" id="btn_adopt_2">اعتماد الموازنة</button>
                <?php endif; ?>

                <?php if ( false and HaveAccess($adopt_url.'1') and !$isCreate and $rs['ADOPT']==2 ) : ?>
                    <button type="button" onclick='javascript:cancel_adopt(1);' class="btn btn-danger">إلغاء اعتماد الموازنة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'3') and !$isCreate and $rs['ADOPT']==2 ) : ?>
                    <button type="button" onclick='javascript:adopt(3);' class="btn btn-success" id="btn_adopt_3">اعتماد الادارة المالية</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'4') and !$isCreate and $rs['ADOPT']==3 ) : ?>
                    <button type="button" onclick='javascript:adopt(4);' class="btn btn-success" id="btn_adopt_4">اعتماد الرقابة الداخلية</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'5') and !$isCreate and $rs['ADOPT']== 4 ) : ?>
                    <button type="button" onclick='javascript:adopt(5);' class="btn btn-success" id="btn_adopt_5">اعتماد المدير العام</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'6') and !$isCreate and $rs['ADOPT']== 5 ) : ?>
                    <button type="button" onclick='javascript:adopt(6);' class="btn btn-success" id="btn_adopt_6">اعتماد  رئيس هيئة المديرين</button>
                <?php endif; ?>

                <?php if ( !$isCreate and $rs['ADOPT']!=0 ) : ?>
                    <span><?php echo modules::run('attachments/attachment/index',$rs['AMENDMENT_ID'],'budget_amendment'); ?></span>
                <?php endif; ?>

                <a style="width: 100px; margin-left: 10px" href="javascript:;" onclick="javascript:show_adopts();" class="icon-btn">
                    <i class="glyphicon glyphicon-list"></i>
                    <div> بيانات الاعتمادات </div>
                </a>

            </div>

        </form>
    </div>

    <!------------------------>
    <div class="modal fade" id="adopts_Modal">
        <div class="modal-dialog" style="width: 900px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">بيانات الاعتمادات</h4>
                </div>
                <div class="modal-body">
                    <table class="table" data-container="container">
                        <thead>
                        <tr>
                            <th>م</th>
                            <th>حالة الاعتماد</th>
                            <th>المستخدم</th>
                            <th>التاريخ</th>
                            <th>الملاحظة</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for($i=2;$i<=count($adopts);$i++){
                            $adopt_no=$adopts[$i-1]['CON_NO'];
                            echo"
                        <tr>
                            <td>".$adopt_no."</td>
                            <td>".$adopts[$i-1]['CON_NAME']."</td>
                            <td>".$rs["ADOPT_USER_".$adopt_no."_NAME"]."</td>
                            <td>".$rs["ADOPT_DATE_$adopt_no"]."</td>
                            <td style='max-width: 300px'>".$rs["ADOPT_NOTE_$adopt_no"]."</td>
                        </tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
        <!------------------>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    var count = 0;

    var sections_json= {$sections};
    var select_sections= '';

    var branches_json= {$branches};
    var select_branches= '';

    $.each(sections_json, function(i,item){
        select_sections += "<option value='"+item.NO+"' >"+item.NAME+"</option>";
    });

    $.each(branches_json, function(i,item){
        select_branches += "<option value='"+item.NO+"' >"+item.NAME+"</option>";
    });

    reBind();

    $('select[name="section_add[]"], select[name="section_remove[]"]').append(select_sections);

    $('select[name="section_add[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="section_remove[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="section_add[]"], select[name="section_remove[]"], select[name="to_branch[]"], select[name="from_branch[]"]').select2();

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
        var _type= $('#dp_amendment_type').val();
        var html = '';
        if(_type==1){
            html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><select name="to_branch[]" class="form-control" id="dp_to_branch_'+count+'" /></select></td> <td></td> <td><input type="hidden" name="ser[]" value="0" /> <select name="section_add[]" class="form-control" id="dp_section_add_'+count+'" /></select></td> <td><input name="amount_add[]" class="form-control" id="txt_amount_add_'+count+'" /></td> </tr>';
        }else if(_type==2){
            html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><select name="from_branch[]" class="form-control" id="dp_from_branch_'+count+'" /></select></td> <td></td> <td><input type="hidden" name="ser[]" value="0" /> <select name="section_remove[]" class="form-control" id="dp_section_remove_'+count+'" /></select></td> <td><input name="amount_remove[]" class="form-control" id="txt_amount_remove_'+count+'" /></td> </tr>';
        }else if(_type==3){
            html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><select name="to_branch[]" class="form-control" id="dp_to_branch_'+count+'" /></select></td> <td></td> <td><input type="hidden" name="ser[]" value="0" /> <select name="section_add[]" class="form-control" id="dp_section_add_'+count+'" /></select></td> <td><input name="amount_add[]" class="form-control" id="txt_amount_add_'+count+'" /></td> <td><select name="from_branch[]" class="form-control" id="dp_from_branch_'+count+'" /></select></td> <td></td> <td><select name="section_remove[]" class="form-control" id="dp_section_remove_'+count+'" /></select></td> <td><input name="amount_remove[]" class="form-control" id="txt_amount_remove_'+count+'" /></td> </tr>';
        }
        $('#details_tb tbody').append(html);

        reBind(1);
    }

    function reBind(s){
        if(s==undefined)
            s=0;

        check_amount();

        if(s){
            $('select#dp_section_add_'+count+', select#dp_section_remove_'+count).append('<option></option>'+select_sections).select2();
            $('select#dp_from_branch_'+count+', select#dp_to_branch_'+count).append('<option></option>'+select_branches).select2();
        }
    }

    $('#dp_amendment_type').change(function(){
        if($(this).val()==3)
            $('div#to_branch').show();
        else
            $('div#to_branch').hide();

        get_data('{$det_page_url}', {id:{$js_amendment_id}, adopt:{$js_adopt}, amendment_type:$('#dp_amendment_type').val()}, function(ret){
            $('div#details_page').html(ret);
            $('select[name="section_add[]"], select[name="section_remove[]"]').append(select_sections);
            $('select[name="section_add[]"]').each(function(){
                $(this).val($(this).attr('data-val'));
            });
            $('select[name="section_remove[]"]').each(function(){
                $(this).val($(this).attr('data-val'));
            });
            $('select[name="section_add[]"], select[name="section_remove[]"], select[name="to_branch[]"], select[name="from_branch[]"]').select2();
            check_amount();
            $( "#details_tb tbody" ).sortable();
        }, 'html');
    });

    function check_amount(){
        $('input[name="amount_add[]"]').change(function(){
            if($('#dp_amendment_type').val()==3){
                var tr = $(this).closest('tr');
                var add= tr.find('input[name="amount_add[]"]').val();
                tr.find('input[name="amount_remove[]"]').val(add);
            }
        });

        $('input[name="amount_remove[]"]').change(function(){
            if($('#dp_amendment_type').val()==3){
                var tr = $(this).closest('tr');
                var remove= tr.find('input[name="amount_remove[]"]').val();
                tr.find('input[name="amount_add[]"]').val(remove);
            }
        });
    }


SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}else{
    $scripts = <<<SCRIPT
    {$scripts}

    count = {$rs['COUNT_DET']} -1;
    $('#dp_amendment_type').select2().select2('readonly',1);


    var btn__= '';
    $('#btn_adopt_2,#btn_adopt_3,#btn_adopt_4,#btn_adopt_5,#btn_adopt_6').click( function(){
       btn__ = $(this);
    });
    function adopt(no){
        var msg= 'هل تريد اعتماد السند ؟!';
        if(confirm(msg)){
            var values= {amendment_id: "{$rs['AMENDMENT_ID']}" , adopt_note: $('#txt_adopt_note').val()};
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم اعتماد السند بنجاح ..');
                    $('button').attr('disabled','disabled');
                    var sub= 'اعتماد تعديل موازنة {$rs['AMENDMENT_ID']}';
                    var text= 'يرجى اعتماد تعديل الموازنة رقم {$rs['AMENDMENT_ID']}';
                    text+= '<br>{$rs['NOTES']}';
                    text+= '<br>للاطلاع افتح الرابط';
                    text+= ' <br>{$gfc_domain}{$get_url}/{$rs['AMENDMENT_ID']} ';
                    _send_mail(btn__,'{$next_adopt_email}',sub,text);
                    btn__ = '';
                    //get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

  function cancel_adopt(no){
        var msg= 'هل تريد  إلغاء اعتماد السند ؟!';
        if(confirm(msg)){
            var values= {amendment_id: "{$rs['AMENDMENT_ID']}" , adopt_note: $('#txt_adopt_note').val()};
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم إلغاء اعتماد السند بنجاح ..');
                    $('button').attr('disabled','disabled');
                    //get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    $('#print_rep').click(function(){
        _showReport('$print_url&report_type=pdf&report=budget_admnet&p_amendment_id={$rs['AMENDMENT_ID']}');
    });
    
    $('#print_rep_ba').click(function(){
        _showReport('$print_url&report_type=pdf&report=budget_admnet_before_adopt&p_amendment_id={$rs['AMENDMENT_ID']}');
    });
        
     function show_adopts(){
        $('#adopts_Modal').modal();
     }

 </script>
SCRIPT;
}

sec_scripts($scripts);

?>
