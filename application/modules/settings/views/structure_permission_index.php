<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/17/14
 * Time: 9:32 AM
 */

$create_url=base_url('settings/structure_permission/create');
$get_structure_url=base_url('settings/structure_permission/get_structure');
$get_id_url =base_url('settings/structure_permission/get_id');
$delete_url =base_url('settings/structure_permission/delete');
?>


<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>

        <ul>

            <?php if( HaveAccess($create_url,$get_structure_url)): ?>  <li><a  onclick="javascript:system_get_structure();" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>حفظ</a> </li><?php endif;?>


            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div class="form-horizontal" >
            <div class="modal-body">
                <div class="form-group col-sm-8">
                    <label class="col-sm-2 control-label"> المستخدم</label>
                    <div class="col-sm-5">
                        <select name="user_no" style="width: 250px" id="dp_user_no">
                            <option></option>
                            <?php foreach($users as $row) :?>
                                <option value="<?= $row['ID'] ?>"><?= $row['USER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
            </div>
        </div>


        <div id="container">
            <?php echo modules::run('settings/structure_permission/get_structure'); ?>
        </div>


    </div>

</div>

<div class="modal fade" id="structure_permissionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">  صلاحيات إضافية</h4>
            </div>
            <div id="msg_container_alt"></div>

            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                <li class="active"><a href="#overtime-data" data-toggle="tab"> العمل الإضافي</a></li>


            </ul>
            <div id="my-tab-content" class="tab-content">
                <div class="tab-pane active" id="overtime-data">
                    <form class="form-horizontal" id="user_from" method="post" action="<?=base_url('budget/permission/create')?>" role="form" novalidate="novalidate">
                        <div class="modal-body">

                            <div class="form-group">
                                <label class="col-sm-4 control-label">تعديل البيانات</label>
                                <div class="col-sm-8">
                                    <div class="radio">
                                        <label> <input type="radio" checked name="over_time_can_edit" id="rd_over_time_can_edit_1" value="1">
                                            نعم
                                        </label>

                                        <label>
                                            <input type="radio" name="over_time_can_edit" id="rd_over_time_can_edit_0" value="0">
                                            لا
                                        </label>


                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">اعتماد مدير الدائرة</label>
                                <div class="col-sm-8">
                                    <div class="radio">
                                        <label> <input type="radio" name="over_time_dept_direct_adopt" id="rd_over_time_dept_direct_adopt_1" value="1">
                                            نعم
                                        </label>

                                        <label>
                                            <input type="radio" checked name="over_time_dept_direct_adopt" id="rd_over_time_dept_direct_adopt_0" value="0">
                                            لا
                                        </label>


                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">اعتماد مدير المقر</label>
                                <div class="col-sm-8">
                                    <div class="radio">
                                        <label> <input type="radio" name="over_time_branch_direct_adopt" id="rd_over_time_branch_direct_adopt_1" value="1">
                                            نعم
                                        </label>

                                        <label>
                                            <input type="radio" checked name="over_time_branch_direct_adopt" id="rd_over_time_branch_direct_adopt_0" value="0">
                                            لا
                                        </label>


                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4 control-label">اعتماد المقر الرئيسي</label>
                                <div class="col-sm-8">
                                    <div class="radio">
                                        <label> <input type="radio" name="over_time_main_direct_adopt" id="rd_over_time_main_direct_adopt_1" value="1">
                                            نعم
                                        </label>

                                        <label>
                                            <input type="radio" checked name="over_time_main_direct_adopt" id="rd_over_time_main_direct_adopt_0" value="0">
                                            لا
                                        </label>


                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                        </div>
                    </form>
                </div></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php





$scripts = <<<SCRIPT
<script>
    $(function () {

        $('#dp_user_no').select2().on('change',function(){
            get_data('$get_structure_url',{user:$('#dp_user_no').val()} ,function(data){
                $('#container').html(data);
            },"html");
        });
        $('#structure_permission').tree();
        $.fn.tree.expandAll();





        checkBoxChanged();
    });

    var checkedBox;


    function structure_permission_get(id,a){



        get_data('{$get_id_url}',{st_id:id,user:$('#dp_user_no').val()},function(data){

            $.each(data, function(i,item){
                $('#rd_over_time_dept_direct_adopt_'+item.over_time_dept_direct_adopt).prop('checked',true).attr('checked',true);
                $('#rd_over_time_branch_direct_adopt_'+item.over_time_branch_direct_adopt).prop('checked',true);
                $('#rd_over_time_main_direct_adopt_'+item.over_time_main_direct_adopt).prop('checked',true);
                $('#rd_over_time_can_edit_'+item.over_time_can_edit).prop('checked',true);
                $('#structure_permissionModal').modal();


            });


        });


    }

    function checkBoxChanged(){

        $('input:checked').parents('li').children('span').children('input').prop('checked', true);
        $('input:checked').parents('li').children('span').children('input').attr('checked',true);

        $('li > span > input[type="checkbox"]', $('#structure_permission')).on('change',function(e){
            e.preventDefault();
            var checkbox = $(this);
            checkedBox= checkbox;
            var is_checked = $(checkbox).is(':checked');


            $('#rd_over_time_dept_direct_adopt_0').prop('checked',true).attr('checked',true);
            $('#rd_over_time_branch_direct_adopt_0').prop('checked',true);
            $('#rd_over_time_main_direct_adopt_0').prop('checked',true);
            $('#rd_over_time_can_edit_1').prop('checked',true);



            if(is_checked) {
                if($('#dp_user_no').val() !=''){
                   /* $(checkbox).parents('li').children('span').children('input').prop('checked', true);
                    $(checkbox).parents('li').children('span').children('input').attr('checked',true);
                    $(checkbox).closest('li').find('span>input').prop("checked", true);
                    $(checkbox).closest('li').find('span>input').attr("checked", true);
                    */




                    $('#structure_permissionModal').modal();

                    }
                else{
                    $(checkbox).prop("checked", false);
                    $(checkbox).attr("checked", false);
                }


            } else {

                if(confirm('هل تريد إلغاء الصلاحية ؟!!')){
                    $(checkbox).closest('li').find('span>input').prop("checked", false);
                    $(checkbox).closest('li').find('span>input').attr("checked", false);
                    permission_delete();
                }else{
                    $(checkbox).prop("checked", true);
                    $(checkbox).attr("checked", true);
                }
            }

        });

    }


    function permission_delete(){


        var val = [];


        val[0]= $(checkedBox).val();
       /* $(checkedBox).closest('li').find(' ul > li > span > input[type=checkbox]').each(function (i)  {
            val[i+1] = $(this).val();

        });*/

        ajax_delete_any('{$delete_url}', {'st_id[]': val,__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val(),user_id:$('#dp_user_no').val()} ,function(data){

            success_msg('رسالة','تم حذف السجلات بنجاح ..');


        });


    }


    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();


        var form = $(this).closest('form');



        var val = [];

       /* $('#structure_permission').find('span > input[type=checkbox]:checked').each(function (i) {
            val[i] = $(this).val();

        });*/


        val[0]= $(checkedBox).val();

        var over_time_dept_direct_adopt= $('input[name="over_time_dept_direct_adopt"]:checked').val();
        var over_time_branch_direct_adopt= $('input[name="over_time_branch_direct_adopt"]:checked').val();
        var over_time_main_direct_adopt= $('input[name="over_time_main_direct_adopt"]:checked').val();
        var over_time_can_edit= $('input[name="over_time_can_edit"]:checked').val();

        get_data('$create_url',{'st_id[]': val,__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val(),user_id:$('#dp_user_no').val(),over_time_dept_direct_adopt:over_time_dept_direct_adopt,over_time_branch_direct_adopt:over_time_branch_direct_adopt,over_time_main_direct_adopt:over_time_main_direct_adopt,over_time_can_edit:over_time_can_edit} ,function(data){

            try{
                var obj = jQuery.parseJSON(data);
                if(obj.msg=='1')
                    success_msg('رسالة','تم حفظ السجلات بنجاح ..');
            }catch(e){
            }
            $('#structure_permissionModal').modal('hide');
        });

    });


</script>

SCRIPT;

sec_scripts($scripts);



?>


