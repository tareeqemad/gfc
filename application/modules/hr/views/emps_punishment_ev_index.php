<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/01/21
 * Time: 09:50 ص
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'Emps_punishment_ev';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$post_url= base_url("$MODULE_NAME/$TB_NAME/create");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
//$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if ( HaveAccess($post_url) ) :  ?>
                <li><a onclick="javascript:show_modal();" href="javascript:;" ><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >

            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :?>
                                <option value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع العقوبة</label>
                    <div>
                        <select name="punishment_type" id="dp_punishment_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($punishment_type as $row) :?>
                                <option  value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الطلب</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($adopt as $row) :?>
                                <option  value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">سنة العقوبة</label>
                    <div>
                        <input  type="text" name="punishment_year"  id="txt_punishment_year" class="form-control" value="<?=date('Y')?>" min="2000" max="2200">
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success">إستعلام</button>
            <button type="button" onclick="$('#emps_punishment_ev_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <div id="container">
            </div>
        </div>

    </div>

    <!------------------modal----------------->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="title" class="modal-title">اضافة عقوبة</h3>
                </div>
                <form class="form-horizontal" id="<?= $TB_NAME ?>_form_modal" method="post" action="<?= $post_url ?>" role="form" >

                    <div class="modal-body">
                        <input type="hidden" name="ser" id="h_ser">
                        <input type="hidden" name="adopt" id="h_adopt">

                        <div class="row">
                            <br>
                            <label class="col-sm-2 control-label">الموظف</label>
                            <div class="col-sm-9">

                                <select  data-val="true" name="emp_no" id="dp_emp" class="form-control sel2" required >
                                    <option value="">_________</option>
                                    <?php foreach($emp_no_cons as $row) :?>
                                        <option value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-2 control-label">نوع العقوبة</label>
                            <div class="col-sm-9">

                                <select data-val="true" name="punishment_type" id="dp_punishment_type_no" class="form-control sel2" required >
                                    <option value="">_________</option>
                                    <?php foreach($punishment_type as $row) :?>
                                        <option  value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-2 control-label ">سنة العقوبة</label>
                            <div class="col-sm-9">
                                <input  type="text" name="punishment_year"  id="txt_punishment_year" class="form-control" value="<?=date('Y')?>" min="2000" max="2200" required>
                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-2 control-label">قيمة الخصم في التقييم</label>
                            <div class="col-sm-9">
                                <input type="text" name="evaluation_discount"  id="txt_evaluation_discount"  class="form-control " maxlength="3" required>
                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-2 control-label">ملاحظات</label>
                            <div class="col-sm-9">
                                <textarea type="text" name="notes"  id="txt_notes"  class="form-control " rows="1" ></textarea>
                            </div>
                        </div>
                        <br>

                        <div class="modal-footer">
                            <?php if ( HaveAccess($post_url) ) :  ?>
                                <button type="submit" data-action="submit" id="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-------------------------------------------->

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();

    function values_search(add_page){
        var values= {emp_no:$('#dp_emp_no').val(), punishment_type:$('#dp_punishment_type').val(), punishment_year:$('#txt_punishment_year').val() ,adopt:$('#dp_adopt').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','',);
    }

    function show_modal(){
        $('#myModal').modal();
        $('#Emps_punishment_ev_form_modal').attr('action', '{$post_url}');
        clear();
        $('#submit').show();
    }

    function clear() {
        $('#txt_evaluation_discount ,#txt_notes, #txt_punishment_year').val(''); 
        $('#dp_emp,#dp_punishment_type_no').select2('val','');
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الطلب ؟!';
        var modal_form= 'Emps_punishment_ev_form_modal';
        
        if(confirm(msg)){
            
            if ($('#'+modal_form+' #dp_emp').val() == '' || $('#'+modal_form+' #dp_punishment_type_no').val() == '' || $('#'+modal_form+' #txt_punishment_year').val() == '' || $('#'+modal_form+' #txt_evaluation_discount').val() == '') {
                danger_msg('رسالة','الرجاء ادخال جميع البيانات ..');
                return;
            }
                     
            if ($('#'+modal_form+' #txt_evaluation_discount').val() < 0 || $('#'+modal_form+' #txt_evaluation_discount').val() > 100   ) {
                warning_msg('رسالة','يجب ان تكون قيمة الخصم من 0 الى 100 ..');
                return;
            }
            
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح');
                     $('#myModal').modal('hide');
                      get_to_link(window.location.href);
                      clear();
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    $('#myModal').modal('hide');
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

    </script>
SCRIPT;
sec_scripts($scripts);
?>