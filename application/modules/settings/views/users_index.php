<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('settings/users/delete');
$get_url =base_url('settings/users/get_id');
$edit_url =base_url('settings/users/edit');
$create_url =base_url('settings/users/create');
$get_page_url = base_url('settings/users/get_page');
$get_name_gov = base_url('settings/users/public_get_name_gov');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)): ?><li><a onclick="javascript:user_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif;?>
            <?php if( HaveAccess($delete_url)): ?><li><a  onclick="javascript:user_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li><?php endif;?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label"> الرقم الوظيفي </label>
                    <div>
                        <input type="text"  name="emp_no" id="txt_emp_no" class="form-control">

                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">الاسم كامل</label>
                    <div>
                        <input type="text"  name="user_name"   id="txt_user_name" class="form-control ltr" "="">


                    </div>


                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الفرع</label>
                    <div>
                        <select type="text"   name="branch" id="dp_branch" class="form-control" >
                            <option></option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search_user();" class="btn btn-success"> إستعلام</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('settings/users/get_page',$page,$no,$name,$branch); ?>
        </div>

    </div>

</div>


<div class="modal fade" id="usersModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات المستخدم</h4>
            </div>
            <div id="msg_container_alt"></div>
            <form class="form-horizontal" id="user_from" method="post" action="<?=base_url('settings/users/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> الرقم الوظيفي </label>
                        <div class="col-sm-4">
                            <input type="text" data-val="true"   data-val-required="حقل مطلوب" name="emp_no" id="txt_emp_no" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="emp_no" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> اسم الدخول</label>
                        <div class="col-sm-4">
                            <input type="text" name="user_id"  data-val-regex="الاسم المسموح إنجليزي من 4 الي 15 حرف !!" data-val-regex-pattern="[0-9a-zA-Z._]{4,15}" data-val="true"  data-val-required="حقل مطلوب"  id="txt_user_id" class="form-control ltr">
                            <span class="field-validation-valid" data-valmsg-for="user_id" data-valmsg-replace="true"></span>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الاسم كامل</label>
                        <div class="col-sm-6">
                            <input type="text"  name="user_name"  data-val="true"  data-val-required="حقل مطلوب"   id="txt_user_name" class="form-control ltr" "="">
                            <span class="field-validation-valid" data-valmsg-for="user_name" data-valmsg-replace="true"></span>

                        </div>


                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> الفرع</label>
                        <div class="col-sm-4">
                            <select type="text" data-val="true"  data-val-required="حقل مطلوب" name="branch" id="dp_branch" class="form-control" >
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="branch" data-valmsg-replace="true"></span>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-3 control-label"> القسم</label>
                        <div class="col-sm-9">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="user_position" id="txt_user_position" class="form-control easyui-combotree" data-options="url:'<?= base_url('settings/gcc_structure/public_get_structure_json')?>',method:'get',animate:true,lines:true,required:true">
                            <span class="field-validation-valid" data-valmsg-for="user_position" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">  البريد الالكتروني </label>
                        <div class="col-sm-6">
                            <input type="text" data-val="true"   data-val-required="حقل مطلوب" name="email" id="txt_email" class="form-control ltr">
                            <span class="field-validation-valid" data-valmsg-for="email" data-valmsg-replace="true"></span>
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-3 control-label"> كلمة المرور</label>
                        <div class="col-sm-4">
                            <input type="password" data-val="true" data-val-regex-pattern="^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&-]).*$" data-val-regex=" كلمة المرور يجب أن تكون 8 خانات و رمز و حرف كبير على الأقل "  data-val-required="حقل مطلوب" name="user_pwd" id="txt_user_pwd" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="user_pwd" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> تأكيد كلمة المرور</label>
                        <div class="col-sm-4">
                            <input type="password" data-val="true" data-val-equalto-other="user_pwd" data-val-equalto="كلمة المرور غير متطابقة"  data-val-required="حقل مطلوب" name="user_cpwd" id="txt_user_cpwd" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="user_cpwd" data-valmsg-replace="true"></span>
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


$scripts = <<<SCRIPT

<script>
    $(function () {

        $('#usersModal').on('shown.bs.modal', function () {
            $('#txt_user_name').focus();
        })


    });



    function user_create(){

        $('.textbox-text[data-val]').rules('add', {required: true});
        $('#txt_user_pwd').rules('add', {required: true});
        $('#txt_user_cpwd').rules('add', {required: true});

        clearForm($('#user_from'));

        $('#txt_user_id').prop('readonly',false);

        $('#user_from').attr('action','{$create_url}');
        $('#usersModal').modal();

    }

    function user_delete(){

        if(confirm('هل تريد حذف الحساب ؟!!!')){


            var url = '{$delete_url}';


            var tbl = '#userTbl';

            var container = $('#' + $(tbl).attr('data-container'));

            var val = [];

            $(tbl + ' .checkboxes:checked').each(function (i) {
                val[i] = $(this).val();

            });



            ajax_delete(url, val ,function(data){

                success_msg('رسالة','تم حذف السجلات بنجاح ..');
                container.html(data);

            });
        }

    }

    function user_get(id){

        get_data('{$get_url}',{id:id},function(data){

            $.each(data, function(i,item){
                console.log('',item);
                $('#user_from #txt_user_id').val(item.USER_ID);
                $('#user_from #txt_user_name').val(item.USER_NAME);
                $('#user_from #dp_branch').val( item.BRANCH);
                $('#user_from #txt_emp_no').val( item.EMP_NO);
                $('#user_from #txt_email').val( item.EMAIL);

                $('#user_from input[name="user_position"]').val(item.USER_POSITION);

                $('#user_from #txt_user_id').prop('readonly',true);

                $('#user_from .textbox-text[data-val]').rules('add', {required: true});

                $('#user_from #txt_user_pwd').rules('add', {required: false});
                $('#user_from #txt_user_cpwd').rules('add', {required: false});

                $('#txt_user_position').combotree('setValue', item.USER_POSITION);
                $('#txt_user_position').combotree('setText', $('.tree-title[data-id="'+item.USER_POSITION+'"]').text());

                $('#user_from #txt_user_pwd').val('');
                $('#user_from #txt_user_cpwd').val('');

                $('#user_from').attr('action','{$edit_url}');

                resetValidation($('#user_from'));
                $('#usersModal').modal();

            });
        });
    }

    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();


        var tbl = '#userTbl';

        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');

            container.html(data);
            $('#usersModal').modal('hide');


        },"html");

    });


    function search_user(){

        get_data('{$get_page_url}',{page: 1,no : $('#txt_emp_no').val(),name:$('#txt_user_name').val(),branch:$('#dp_branch').val()},function(data){
            $('#container').html(data);
        },'html');
    }

  function LoadingData(){

    ajax_pager_data('#userTbl > tbody',{no : $('#txt_emp_no').val(),name:$('#txt_user_name').val(),branch:$('#dp_branch').val()});

    }


function get_name_gov(){
return 0;
    var emps= [400047996,908915316];

    $.each(emps, function(i,item){
        get_data('http://moidev.moi.gov.ps/api/default.aspx?job=idquery&idno='+item+'&token=m23K0USM37osap9UD1PIUbMG381ZpTXU',{id:0},function(data){
            get_data('{$get_name_gov}',{id:data.CI_ID_NUM,  n1:data.CI_FIRST_ARB,  n2:data.CI_FATHER_ARB,  n3:data.CI_GRAND_FATHER_ARB,  n4:data.CI_FAMILY_ARB},function(res){
                console.log(i+' '+data.CI_ID_NUM+' '+res);
            },'html');
        },'JSON');
    });

}


</script>
SCRIPT;

sec_scripts($scripts);



?>

