<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 25/02/15
 * Time: 09:58 ص
 */

$MODULE_NAME= 'budget';
$TB_NAME= 'exp_rev_new';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_chapter_url = base_url("$MODULE_NAME/$TB_NAME/public_chapter");
$get_section_url = base_url("$MODULE_NAME/$TB_NAME/public_section");
$get_department_url = base_url("$MODULE_NAME/$TB_NAME/public_department");
$post_data= base_url("$MODULE_NAME/$TB_NAME/edit");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$attachment_data_url= base_url("$MODULE_NAME/$TB_NAME/attachment_get");

if(!isset($all_branches))
    die('Error all branches');

echo AntiForgeryToken();
?>

<style>
    .ccount,.price{display:inline-block }
</style>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>

        <ul>
        </ul>

    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_data?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label"> النوع </label>
                    <div>
                        <select name='exp_rev_type' id='dp_exp_rev_type' class='form-control'>
                            <?php foreach($consts as $row) :?>
                                <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php if($all_branches){ ?>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> الفرع </label>
                        <div>
                            <select name='branch' id='dp_branch' class='form-control'>
                                <option selected value="0">---</option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2" id="departments" style="display: none">
                        <label class="control-label">الادارة</label>
                        <div>
                            <select name="department_no" id="dp_department_no" class="form-control" />
                            </select>
                        </div>
                    </div>

                <?php } else {?>
                    <select id="dp_branch" style="display: none"/></select>
                    <select id="dp_department_no" style="display: none"/></select>
                <?php } ?>

                <div class="form-group col-sm-2">
                    <label class="control-label">الباب</label>
                    <div>
                        <select name="chapter" id="dp_chapter" class="form-control" />
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الفصل</label>
                    <div>
                        <select name="section" id="dp_section" class="form-control" />
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الاجمالي قبل التنسيب</label>
                    <div>
                        <input type="text" readonly id="txt_total" class="form-control" >
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الاجمالي المعتمد </label>
                    <div>
                        <input type="text" readonly id="txt_total_update" class="form-control" >
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <?php if( HaveAccess($get_page_url) ) { ?>
                    <button type="button" onclick="javascript:search();" class="btn btn-default"> إستعلام</button>
                <?php } ?>

                <?php if( HaveAccess($post_data) ) { ?>
                    <button onclick="javascript:save();" type="button" class="btn btn-primary">حفظ البيانات</button>
                <?php } ?>

                <?php if( HaveAccess($adopt_url.'2') ) { ?>
                    <button onclick="javascript:adopt(2);" type="button" class="btn btn-success">اعتماد الادارة/ الفرع</button>
                <?php } ?>

                <?php if( HaveAccess($adopt_url.'3') ) { ?>
                    <button onclick="javascript:adopt(3);" type="button" class="btn btn-success">اعتماد الجهة المختصة </button>
                <?php } ?>

                <?php if( HaveAccess($adopt_url.'1') ) { ?>
                    <button onclick="javascript:adopt(1);" type="button" class="btn btn-danger">ارجاع</button>
                <?php } ?>
            </div>

            <input type="hidden" id="h_total_update" value="0" />
            <div id="container"></div>
        </form>

        <div id="msg_container"></div>

    </div>

</div>

<div class="modal fade" id="uploadModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">المرفقات</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    $(document).ready(function() {

        $('#dp_exp_rev_type, #dp_branch, #dp_department_no, #dp_chapter, #dp_section').select2();

        var all_branches= {$all_branches};

        if(!all_branches)
            get_chapter();

        $('#dp_exp_rev_type').change(function(){
            $('#dp_chapter, #dp_section, #dp_department_no').html('<option value="0"></option>').select2('val',0);
            $('#dp_branch').select2('val',0);
            $('div#departments').hide(300);
            $('.a_attachment').hide();
            if(!all_branches)
                get_chapter();
        });

        $('#dp_branch').change(function(){
            var branch_no= $('#dp_branch').select2('val');
            if(branch_no==1){
                $('#dp_department_no').html('<option value="0"></option>').select2('val',0);
                get_department();
                $('div#departments').show(300);
            }else if(branch_no!=0){
                get_chapter();
                $('div#departments').hide(300);
            }
            $('#dp_chapter, #dp_section').html('<option value="0"></option>').select2('val',0);
        });

        $('#dp_department_no').change(function(){
            $('#dp_chapter, #dp_section').html('<option value="0"></option>').select2('val',0);
            get_chapter();
        });

        $('#dp_chapter').change(function(){
            $('#dp_section').html('<option value="0"></option>').select2('val',0);
            get_section();
        });

    });


    function get_department(){
        var values= {exp_rev_type: $('#dp_exp_rev_type').select2('val')};
        var select_department= '<option value="0" ></option>';
        get_data('{$get_department_url}', values, function(ret){
            if(ret.length > 0){
                $.each(ret, function(i,item){
                    select_department += "<option value='"+item.DEPARTMENT_NO+"' >"+item.DEPARTMENT_NAME+"</option>";
                });
                $('#dp_department_no').html(select_department);
            }
        });
    }

    function get_chapter(){
        var values= {exp_rev_type: $('#dp_exp_rev_type').select2('val'), branch: $('#dp_branch').select2('val') , department_no: $('#dp_department_no').select2('val')};
        var select_chapter= '<option value="0" ></option>';
        get_data('{$get_chapter_url}', values, function(ret){
            if(ret.length > 0){
                $.each(ret, function(i,item){
                    select_chapter += "<option value='"+item.CHAPTER_NO+"' >"+item.CHAPTER_NAME+"</option>";
                });
                $('#dp_chapter').html(select_chapter);
            }
        });
    }

    function get_section(){
        if( $('#dp_chapter').select2('val')==0 )
            return false;
        var values= {chapter: $('#dp_chapter').select2('val'), branch: $('#dp_branch').select2('val') , department_no: $('#dp_department_no').select2('val')};
        var select_section= '';
        get_data('{$get_section_url}', values, function(ret){
            if(ret.length > 0){
                $.each(ret, function(i,item){
                    select_section += "<option value='"+item.SECTION_NO+"' >"+item.SECTION_NAME+"</option>";
                });
                $('#dp_section').html(select_section).select2('val',ret[0].SECTION_NO);
            }
        });
    }

    function search(){
        if( parseInt( $('#dp_section').select2('val')) > 0){
            var values= {exp_rev_type: $('#dp_exp_rev_type').select2('val'), section: $('#dp_section').select2('val'), branch: $('#dp_branch').select2('val') , department_no: $('#dp_department_no').select2('val')};
            get_data('{$get_page_url}',values ,function(data){
                $('#container').html(data);
            },'html');
        }
    }

    function save(){
        if(validation('page_tb')){
            if(confirm('هل تريد بالتأكيد حفظ جميع السجلات')){
                var form = $("#{$TB_NAME}_form");
                ajax_insert_update(form,function(data){
                    if(data > 0){
                        success_msg('رسالة','تم حفظ البيانات ('+data+' سجلات) بنجاح ..');
                    }else{
                        danger_msg('تحذير..','لم يتم حفظ البيانات');
                    }
                },"html");
            }
        }
    }

    function validation(tb){
        var ret= true;
        if( $('#'+tb+' tfoot .total').text() =='NaN'){
            alert('ادخل قيم رقمية');
            ret= false;
        }else{
            $('#'+tb+' tbody tr').each(function() {
                var ccount= $(this).find('.ccount').val();
                var price= $(this).find('.price').val();
                if( price < 0 || (price== 0 && price!='' ) || ( (ccount!='' && ccount > 0 ) && (price='' || price <= 0 || isNaN(price))) ){
                    alert('يجب ان يكون السعر اكبر من صفر');
                    $(this).find('.price').focus();
                    ret= false;
                    return false;
                }else if( (price!='' && price > 0 ) && (ccount='' || ccount <= 0 || isNaN(ccount))){
                    alert('يجب ان تكون الكمية اكبر من صفر');
                    $(this).find('.ccount').focus();
                    ret= false;
                    return false;
                }
            });
        }

        var total_update= parseFloat($('#h_total_update').val());
        var total_crnt= parseFloat($('tfoot #h_total').val());
        total_crnt= (total_crnt).toFixed(4);

        if(ret && total_update > 0 && total_crnt > 0 && total_update >= total_crnt ){
            //alert('true');
        }else{
            ret= false;
            warning_msg('تنبيه','الاجمالي المدخل اكبر من الاجمالي المنسب..');
        }
        return ret;
    }

    function attachment_get(item_no,department_no){
        if( item_no !=0 && item_no != null && department_no !=0 && department_no != null  ){
            var values= {exp_rev_type:$('#dp_exp_rev_type').val(), branch: $('#dp_branch').select2('val'), item_no:item_no, department_no:department_no};
            get_data("{$attachment_data_url}", values, function(ret){
                $('#uploadModal .modal-body').html(ret);
                $('#uploadModal').modal();
            }, 'html');
        }
    }

    function adopt(no){
        if(no!=1){
            var total_update= parseFloat($('#h_total_update').val());
            var total_crnt= parseFloat($('tfoot #h_total').val());
            total_crnt= (total_crnt).toFixed(4);

            if(no && total_update >= total_crnt ){
                //alert('true');
            }else{
                warning_msg('تنبيه','الاجمالي المدخل اكبر من الاجمالي المنسب..');
                return false;
            }
        }
        var msg= '';
        if(no==1)
             msg= 'هل تريد ارجاع الفصل ؟!';
        else
             msg= 'هل تريد اعتماد الفصل ؟!';

        if(confirm(msg)){
            var adopt_url= '{$adopt_url}'+no;
            var values= { exp_rev_type:$('#dp_exp_rev_type').val(), branch: $('#dp_branch').select2('val'), department_no: $('#dp_department_no').select2('val'), section: $('#dp_section').select2('val') };
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    if(no==1){
                        success_msg('رسالة','تم ارجاع الفصل بنجاح ..');
                    }else{
                        success_msg('رسالة','تم اعتماد الفصل بنجاح ..');
                    }
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }


</script>
SCRIPT;
sec_scripts($scripts);
?>
