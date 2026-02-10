<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url = base_url('settings/usermenus/user_systems');

$create_url = base_url('settings/usermenus/user_systems');


?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a onclick="javascript:user_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد
                    </a></li><?php endif; ?>


            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div id="container">
            <table class="table">
                <thead>
                <tr>
                    <th style="width: 20px;">#</th>
                    <th>المستخدم</th>
                    <th>النظام</th>
                    <th style="width: 50px;"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td></td>
                        <td><?= $row['USER_ID_NAME'] ?></td>
                        <td><?= $row['NAME'] ?></td>
                        <td>

                            <a onclick="javascript:user_system_delete(this,<?=  $row['SER'] ?>);" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>


<div class="modal fade" id="usersModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات المستخدم</h4>
            </div>
            <div id="msg_container_alt"></div>
            <form class="form-horizontal" id="user_from" method="post" action="<?= base_url('settings/usermenus/user_systems') ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label"> النظام </label>

                        <div class="col-sm-8">
                            <select type="text" name="id_system" id="dp_id_system" class="form-control">
                                <option></option>
                                <?php foreach ($systems as $row) : ?>
                                    <option value="<?= $row['ID'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> المستخدم </label>
                        <select name="user_no" class="col-sm-8"  id="dp_user_no">
                            <option></option>
                            <?php foreach ($users as $row) : ?>
                                <option value="<?= $row['ID'] ?>"><?= $row['USER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php


$scripts = <<<SCRIPT

<script>
    $(function () {

        $('#dp_user_no').select2().on('change',function(){});

    });



    function user_create(){


        clearForm($('#user_from'));



        $('#user_from').attr('action','{$create_url}');
        $('#usersModal').modal();

    }

    function user_system_delete(a,id){

        var url = '{$delete_url}';
        if(confirm('هل تريد حذف البند ؟!')){

                  get_data(url,{id:id,action:'delete'},function(data){
                             if(data == '1'){

                                $(a).closest('tr').remove();

                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                 }

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

            reload_Page();


        },"html");

    });



</script>
SCRIPT;

sec_scripts($scripts);



?>

