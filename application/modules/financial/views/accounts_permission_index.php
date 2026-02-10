<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/1/14
 * Time: 1:25 PM
 */

$create_url =base_url('financial/accounts_permission/create');
$get_financial_url =base_url('financial/accounts/get_accounts');
$get_id_url=base_url('financial/accounts_permission/get_id');
$delete_url =base_url('financial/accounts_permission/delete');
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>

        <ul>

            <!--     <li><a  onclick="javascript:save_accounts_permission();" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>حفظ</a> </li>
     -->
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
            <?php echo modules::run('financial/accounts/get_accounts',true); ?>
        </div>


    </div>

</div>

<div class="modal fade" id="account_permissionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">  صلاحيات إضافية</h4>
            </div>
            <div id="msg_container_alt"></div>


            <form class="form-horizontal" id="user_from" method="post" action="<?=base_url('financial/accounts_permission/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-4 control-label"> نوع القبض</label>
                        <div class="col-sm-8">
                            <select name="user_no" style="width: 250px" id="income_type">
                                <option></option>
                                <?php foreach($types as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
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

        $('#dp_user_no').select2().on('change',function(){
            get_data('$get_financial_url',{user:$('#dp_user_no').val(),click:true} ,function(data){
                $('#container').html(data);
            },"html");
        });
        $('#accounts_tree').tree();
        checkBoxChanged();
    });

    var checkedBox;

    function checkBoxChanged(){
        $.fn.tree.expandAll();
        $('li > span > input[type="checkbox"]', $('#accounts_tree')).on('change',function(e){

            var checkbox = $(this);
            checkedBox= checkbox;
            var is_checked = $(checkbox).is(':checked');

            if($('#dp_user_no').val()==''){
                $(checkbox).prop("checked", false);
            }else if(is_checked){
                accounts_permission_get($(this).val());
            }else{
                ajax_delete_any('{$delete_url}', {'accounts[]': $(this).val(),__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val(),user_id:$('#dp_user_no').val()} ,function(data){




                });

            }
        });
    }
    function account_get(id,a){
         checkedBox= $(a).find('input[type=checkbox]');
         $(checkedBox).prop("checked", true);
        accounts_permission_get(id);
    }

    function accounts_permission_get(id){
     $('#income_type').val('');
        get_data('{$get_id_url}',{id:id,user:$('#dp_user_no').val()},function(data){

            $.each(data, function(i,item){
                $('#income_type').val(item.INCOME_TYPE);
            });

            $('#account_permissionModal').modal();
        });
    }

    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();

        var form = $(this).closest('form');
        var val = [];
        val[0]= $(checkedBox).val();
        get_data('$create_url',{'accounts[]': val
            ,__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val()
            ,user_id:$('#dp_user_no').val()
            ,income_type:$('#income_type').val()} ,function(data){

            try{
                var obj = jQuery.parseJSON(data);
                if(obj.msg=='1')
                    success_msg('رسالة','تم حفظ السجلات بنجاح ..');
            }catch(e){
            }
            $('#account_permissionModal').modal('hide');
        });

    });


</script>
SCRIPT;

sec_scripts($scripts);



?>




