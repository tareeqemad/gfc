<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/12/19
 * Time: 09:59 ص
 */

$MODULE_NAME= 'pledges';
$TB_NAME= 'rooms_structure_tree';

$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$edit_room_url= base_url("$MODULE_NAME/$TB_NAME/edit_room");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
$get_print_url = base_url("$MODULE_NAME/$TB_NAME/get_to_print");

$get_emps_url= base_url("$MODULE_NAME/$TB_NAME/get_emps");
$save_emps_url= base_url("$MODULE_NAME/$TB_NAME/save_emps");
$search_url= base_url("$MODULE_NAME/$TB_NAME/public_get_room"); // search by emp to get room

echo AntiForgeryToken();

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>
            <?php if( HaveAccess($create_url)): ?> <li><a onclick="javascript:_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>اضافة طابق / غرفة </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_url)): ?><li><a  onclick="javascript:get_();" href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li> <?php endif;?>
            <?php if(0 and HaveAccess($edit_url)): ?><li><a  onclick="javascript:_edit();" href="javascript:;"><i class="glyphicon glyphicon-transfer"></i>نقل </a> </li> <?php endif;?>
            <?php if( HaveAccess($delete_url)): ?><li><a  onclick="javascript:_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_print_url)): ?> <li><a onclick="javascript:_print();" href="javascript:;"><i class="glyphicon glyphicon-print"></i>طباعة اكسل</a> </li> <?php endif;?>
            <?php if( HaveAccess($get_emps_url)): ?><li><a  onclick="javascript:get_emps($.fn.tree.selected().attr('data-no'));" href="javascript:;"><i class="glyphicon glyphicon-list"></i> الموظفين على الغرفة </a> </li> <?php endif;?>
            <?php if(1): ?><li><a  onclick="javascript:search_emps();" href="javascript:;"><i class="glyphicon glyphicon-search"></i> البحث عن موظف </a> </li> <?php endif;?>
            <li><a  onclick="$.fn.tree.expandAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a> </li>
            <li><a  onclick="$.fn.tree.collapseAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a> </li>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div class="input-group col-sm-4">
            <fieldset>
هيكلة الغرف
            </fieldset>
        </div>

        <div class="form-group">
            <div class="input-group col-sm-2">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="بحث">
            </div>
        </div>

        <?= $tree ?>
    </div>

</div>


<!-- Add Edit room Modal -->
<div class="modal fade" id="room_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> - </h4>
            </div>
            <form class="form-horizontal" id="room_form" method="post" action="<?=$create_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الهيكل الاب</label>
                        <div class="col-sm-7">
                            <input type="text" readonly name="room_parent_2" id="txt_room_parent" class="form-control" />

                            <input type="hidden" name="room_parent" id="h_room_parent" />
                            <input type="hidden" name="ser" id="h_ser" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">رقم الهيكل</label>
                        <div class="col-sm-7">
                            <input type="text" readonly name="room_id" id="txt_room_id" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">اسم الهيكل</label>
                        <div class="col-sm-7">
                            <input type="text" name="room_name" id="txt_room_name" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">نوع الغرفة </label>
                        <div class="col-sm-7">
                            <select name="room_type" id="dp_room_type" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($room_type_cons as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <?php if ( HaveAccess($create_url) or HaveAccess($edit_room_url) ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Add Edit room Modal -->




<div class="modal fade" id="emps_modal">
    <div class="modal-dialog">
        <div class="modal-content _750">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">  الموظفين على الغرفة </h4>
            </div>
            <form class="form-vertical" id="emps_form" method="post" action="<?=$save_emps_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <input type="hidden" name="room_id" id="h_room_id" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">القسم</label>
                        <div class="col-sm-3">
                            <input type="text" readonly id="txt_emp_position" class="form-control" />
                        </div>
                        <div class="col-sm-5">
                            <input type="text" readonly id="txt_structure_name" class="form-control" />
                        </div>
                    </div>

                    <table class="table" id="emps_details_tb" data-container="container">
                        <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 50%">الموظف</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>
                                <?php if ( HaveAccess($save_emps_url) ) { ?>
                                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                                <?php } ?>
                            </th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
                <div class="modal-footer">
                    <?php if ( HaveAccess($save_emps_url) ) : ?>
                        <button type="submit" style="display: none" id="btn_save_emps" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="search_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> البحث عن موظف في الغرف </h4>
            </div>
            <form class="form-horizontal" id="search_form" method="post" action="<?=$search_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الموظف</label>
                        <div class="col-sm-7">
                            <select name="employees" id="dp_employees" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach(json_decode($emps_all,1) as $row) :?>
                                    <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الغرفة</label>
                        <div class="col-sm-2">
                            <input type="text" readonly id="txt_emp_room_id" class="form-control" />
                        </div>
                        <div class="col-sm-5">
                            <input type="text" readonly id="txt_emp_room_name" class="form-control" />
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" onclick="javascript:search_room();" class="btn btn-primary"> بحث</button>
                    <button type="button" onclick="javascript:search_show_room();" class="btn btn-success"> عرض</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php

$scripts = <<<SCRIPT

<script>

    $(function () {
        $('#rooms_structure_tree').tree();
        $('.sel2:not("[id^=\'s2\']")').select2();
        $('.checkboxes:first').hide();
    });

    var emps_json= {$emps_all};
    var select_emps= '<option value=""> _________ </option>';

    $.each(emps_json, function(i,item){
        select_emps += "<option value='"+item.EMP_NO+"' >"+item.EMP_NO+': '+item.EMP_NAME+"</option>";
    });

    function get_(){
        if($.fn.tree.level() == 1){
            alert('اختر ما تريد عرض بياناته');
            return 0;
        }
        $('#room_modal .modal-title').text('تعديل');
        var elem =$.fn.tree.selected();
        var id = elem.attr('data-id');
        $('#h_ser').val(id);
        get_data('{$get_url}',{ser:id},function(item){
            $('#room_form').attr('action','{$edit_room_url}');
            $('#txt_room_parent').val(item.ROOM_PARENT);
            $('#txt_room_id').val(item.ROOM_ID);
            $('#txt_room_name').val(item.ROOM_NAME);
            $('#dp_room_type').select2('val',item.ROOM_TYPE);
            //$('#dp_room_type').select2('readonly',1);
            $('#room_modal').modal();
        });
    }

    function _create(){
        $('#dp_room_type').select2('val','');
        clearForm( $('#room_form')  );
        var this_level = $.fn.tree.level();

        if( this_level!= 2  && this_level!= 3  && this_level!= 4 ){
            warning_msg('تحذير ! ','يرجى تحديد مقر او مبنى او طابق');
            return;
        }

        var room_lvl= '';

        if(this_level==2){
            room_lvl= 'مبنى';
        }else if(this_level==3){
            room_lvl= 'طابق';
        }else if(this_level==4){
            room_lvl= 'غرفة';
        }

        $('#room_modal .modal-title').text('اضافة '+room_lvl);
        $('#room_modal #txt_room_name').attr('placeholder','ادخل اسم ال'+room_lvl);

        var parentId =$.fn.tree.selected().attr('data-no');
        //var parentName = $('.tree li > span.selected').text();

        $('#room_form #h_room_parent, #room_form #txt_room_parent').val(parentId);

        $('#room_form').attr('action','{$create_url}');
        $('#room_modal').modal();
    }



    $('#room_form button[data-action="submit"]').click(function(e){
        e.preventDefault();

        if( $('#txt_room_name').val() == '' )
            return 0;

        var form = $(this).closest('form');
        var isCreate = form.attr('action').indexOf('create') >= 0;
        var room_text = $('#txt_room_name').val();

        ajax_insert_update(form,function(data){

            if(isCreate){
                var obj = jQuery.parseJSON(data);
                $.fn.tree.add( ( obj.no +': '+ room_text ),obj.id,"javascript:get_('"+obj.id+"');");
                $('span[data-id="'+obj.id+'"]').attr('data-no',obj.no);
            }else{
                if(data == '1'){
                    $.fn.tree.update( form.find('input[name="room_id"]').val() +': '+ form.find('input[name="room_name"]').val() );
                }
            }
            $('#room_modal').modal('hide');
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");
    });


    function get_emps(id){
        if($.fn.tree.level() != 5){
            warning_msg('تحذير ! ','اختر الغرفة ..');
            return 0;
        }

        var cnt=0;
        $('#emps_form #h_room_id').val(id);

        // اظهار زر حفظ الموظفين في حال كانت الغرفة تتبع لمقر المستخدم
        if( id.substr(0,1) == '{$user_branch}' ){
            $('#emps_form #btn_save_emps').show();
        }else{
            $('#emps_form #btn_save_emps').hide();
        }

        get_data('{$get_emps_url}',{id:id},function(data){
            $('#emps_details_tb tbody').html('');
            $('#txt_emp_position, #txt_structure_name').val('');

            if(data.length > 0){
                $('#txt_emp_position').val(data[0].EMP_POSITION);
                $('#txt_structure_name').val(data[0].STRUCTURE_NAME);
            }

            $.each(data, function(i,item){
                cnt++;
                if(item.EMP_NO== null) item.EMP_NO= '';
                var row_html= '<tr> <td>'+cnt+'</td> <td><input type="hidden" name="ser_d[]" id="h_ser_d_'+cnt+'" value="'+item.SER_D+'" /><select name="emp_no[]" class="form-control" id="dp_emp_no_'+cnt+'" data-val="'+item.EMP_NO+'" ></select></td>';
                $('#emps_details_tb tbody').append(row_html);
            });
            count= cnt;

            $('select[name="emp_no[]"]').append(select_emps);
            $('select[name="emp_no[]"]').each(function(){
                $(this).val($(this).attr('data-val'));
            });
            $('select[name="emp_no[]"]').select2();

            $('#emps_modal').modal();
        });
    }

    function _delete(){
        if($.fn.tree.level() == 1){
            return 0;
        }
        if(1){
            danger_msg('تنبيه','خاصية الحذف معطلة !!');
            return 0;
        }
        var elem =$.fn.tree.selected();
        var id = elem.attr('data-id');
        var no = elem.attr('data-no');
        var name = elem.text();

        if(confirm('هل تريد بالتأكيد حذف '+name)){
            get_data('{$delete_url}', {id:id, no:no} , function(data){
                if(data == '1'){
                    $.fn.tree.removeElem(elem);
                    success_msg('رسالة','تم الحذف بنجاح ..');
                }else {
                    danger_msg('تحذير','لم يتم الحذف !!<br>'+data);
                }
            });
        }
    }

    function addRow(){
        count = count+1;
        var html= '<tr> <td>'+count+'</td> <td><input type="hidden" name="ser_d[]" id="h_ser_d_'+count+'" value="0" /><select name="emp_no[]" class="form-control" id="dp_emp_no_'+count+'"  ></select></td>';
        $('#emps_details_tb tbody').append(html);
        reBind();
    }

    function reBind(){
        $('select#dp_emp_no_'+count).append(select_emps).select2();
    }

    $('#emps_form button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                get_emps( $('#emps_form #h_room_id').val() );
            }else{
                danger_msg('تحذير..',data);
            }
        },"html");
    });

    function search_emps(){
        $('#txt_emp_room_id, #txt_emp_room_name').val('');
        $('#search_modal').modal();
    }

    function search_room(){
        $('#txt_emp_room_id, #txt_emp_room_name').val('');
        var emp_no= $('#search_modal #dp_employees').val();
        if(emp_no=='')
            return 0;
        get_data('{$search_url}', {emp_no:emp_no} , function(data){
            if(data.length == 1){
                $('#txt_emp_room_id').val(data[0].ROOM_ID);
                $('#txt_emp_room_name').val(data[0].ROOM_NAME);
            }else {
                danger_msg('تحذير','الموظف غير مسكن<br>'+data);
            }
        });
    }

    function search_show_room(){
        var emp_room_id= $('#txt_emp_room_id').val();
        if( emp_room_id == '' ){
            alert('الموظف غير مسكن');
            return 0;
        }
        $('.form-body #search-tree').val( emp_room_id );
        $('.form-body #search-tree').keyup();
        $('#search_modal').modal('hide');
    }


</script>
SCRIPT;

sec_scripts($scripts);

?>
