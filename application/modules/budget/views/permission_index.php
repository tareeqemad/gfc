<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/1/14
 * Time: 1:25 PM
 */
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>

        <!--  <ul>

              <li><a  onclick="javascript:save_permission();" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>حفظ</a> </li>

          </ul>

          -->

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
            <?php echo modules::run('budget/budget/get_budget'); ?>
        </div>


    </div>

</div>

<div class="modal fade" id="user_permissonModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">  صلاحيات إضافية</h4>
            </div>
            <div id="msg_container_alt"></div>
            <form class="form-horizontal" id="user_from" method="post" action="<?=base_url('budget/permission/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-4 control-label">تعديل البيانات</label>
                        <div class="col-sm-8">
                            <div class="radio">
                                <label> <input type="radio" checked name="can_edit" id="rd_can_edit_1" value="1">
                                    نعم
                                </label>

                                <label>
                                    <input type="radio" name="can_edit" id="rd_can_edit_0" value="0">
                                    لا
                                </label>


                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">اعتماد مدير الدائرة</label>
                        <div class="col-sm-8">
                            <div class="radio">
                                <label> <input type="radio" name="dept_direct_adopt" id="rd_dept_direct_adopt_1" value="1">
                                    نعم
                                </label>

                                <label>
                                    <input type="radio" checked name="dept_direct_adopt" id="rd_dept_direct_adopt_0" value="0">
                                    لا
                                </label>


                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">اعتماد مدير المقر</label>
                        <div class="col-sm-8">
                            <div class="radio">
                                <label> <input type="radio" name="branch_direct_adopt" id="rd_branch_direct_adopt_1" value="1">
                                    نعم
                                </label>

                                <label>
                                    <input type="radio" checked name="branch_direct_adopt" id="rd_branch_direct_adopt_0" value="0">
                                    لا
                                </label>


                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-4 control-label">اعتماد المقر الرئيسي</label>
                        <div class="col-sm-8">
                            <div class="radio">
                                <label> <input type="radio" name="main_direct_adopt" id="rd_main_direct_adopt_1" value="1">
                                    نعم
                                </label>

                                <label>
                                    <input type="radio" checked name="main_direct_adopt" id="rd_main_direct_adopt_0" value="0">
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php


$create_url =base_url('budget/permission/create');
$get_budget_url =base_url('budget/budget/get_budget');
$get_id_url =base_url('budget/permission/get_id');
$delete_url =base_url('budget/permission/delete');

$scripts = <<<SCRIPT
<script>
    $(function () {

        $('#dp_user_no').select2().on('change',function(){
            get_data('$get_budget_url',{user:$('#dp_user_no').val()} ,function(data){
                $('#container').html(data);
            },"html");
        });
        $('#budget').tree();
        $.fn.tree.expandAll();





        checkBoxChanged();
    });



    var isChapter = false;
    var checkedBox;


     function budget_permission(id,a){

        isChapter = false;
        checkedBox =$(a).find('input');

        get_data('{$get_id_url}',{sec_no:id,user:$('#dp_user_no').val()},function(data){

            $.each(data, function(i,item){



                $('#rd_dept_direct_adopt_'+item.DEPT_DIRECT_ADOPT).prop('checked',true).attr('checked',true);
                 $('#rd_branch_direct_adopt_'+item.BRANCH_DIRECT_ADOPT).prop('checked',true);
                  $('#rd_main_direct_adopt_'+item.MAIN_DIRECT_ADOPT).prop('checked',true);
                   $('#rd_can_edit_'+item.CAN_EDIT).prop('checked',true);
                    $('#user_permissonModal').modal();


            });


        });


    }

    function checkBoxChanged(){

        $('input:checked').parents('li').children('span').children('input').prop('checked', true);
        $('input:checked').parents('li').children('span').children('input').attr('checked',true);

        $('li > span > input[type="checkbox"]', $('#budget')).on('change',function(e){
            e.preventDefault();
            var checkbox = $(this);
            checkedBox= checkbox;
            var is_checked = $(checkbox).is(':checked');

            if(checkbox.attr('data-type') == 'chapter'){
                isChapter = true;

                   $('#rd_dept_direct_adopt_0').prop('checked',true).attr('checked',true);
                 $('#rd_branch_direct_adopt_0').prop('checked',true);
                  $('#rd_main_direct_adopt_0').prop('checked',true);
                   $('#rd_can_edit_1').prop('checked',true);

            } else {isChapter = false;

            }

            if(is_checked) {
if($('#dp_user_no').val() !=''){
                $(checkbox).parents('li').children('span').children('input').prop('checked', true);
                $(checkbox).parents('li').children('span').children('input').attr('checked',true);
                $(checkbox).closest('li').find('span>input').prop("checked", true);
                $(checkbox).closest('li').find('span>input').attr("checked", true);





                $('#user_permissonModal').modal();}
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

        if(isChapter)
            $(checkedBox).closest('li').find(' ul > li > span > input[type=checkbox]').each(function (i) {
                val[i] = $(this).val();

            });
        else
            val[0]= checkedBox.parent('span').attr('data-id');


        console.log('',checkedBox);

        ajax_delete_any('{$delete_url}', {'sec_no[]': val,__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val(),user_id:$('#dp_user_no').val()} ,function(data){

            success_msg('رسالة','تم حذف السجلات بنجاح ..');


        });


    }


    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();


        var form = $(this).closest('form');



        var val = [];

        if(isChapter)
            $(checkedBox).closest('li').find(' ul > li > span > input[type=checkbox]:checked').each(function (i) {
                val[i] = $(this).val();

            });
        else
            val[0]= checkedBox.parent('span').attr('data-id');

        var dept_direct_adopt= $('input[name="dept_direct_adopt"]:checked').val();
        var branch_direct_adopt= $('input[name="branch_direct_adopt"]:checked').val();
        var main_direct_adopt= $('input[name="main_direct_adopt"]:checked').val();
        var can_edit= $('input[name="can_edit"]:checked').val();

        get_data('$create_url',{'sec_no[]': val,__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val(),user_id:$('#dp_user_no').val(),dept_direct_adopt:dept_direct_adopt,branch_direct_adopt:branch_direct_adopt,main_direct_adopt:main_direct_adopt,can_edit:can_edit} ,function(data){

            try{
                var obj = jQuery.parseJSON(data);
                if(obj.msg=='1')
                    success_msg('رسالة','تم حفظ السجلات بنجاح ..');
            }catch(e){
            }
         $('#user_permissonModal').modal('hide');
        },"html");

    });


</script>

SCRIPT;

sec_scripts($scripts);



?>

