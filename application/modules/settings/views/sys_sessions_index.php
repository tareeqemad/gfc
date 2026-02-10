<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 31/01/15
 * Time: 05:19 م
 */

$MODULE_NAME= 'settings';
$TB_NAME= 'sys_sessions';
$get_page_url =base_url("$MODULE_NAME/$TB_NAME/get_page");
$status_url =base_url("$MODULE_NAME/$TB_NAME/edit_status");

echo AntiForgeryToken();
?>

<style>
    .color_green{color: #009900}
    .color_red{color: #E50000}
</style>

<div class="row">
    <div class="toolbar">
        <div class="caption">المتواجدين الان</div>
    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">المستخدم</label>
                    <div>
                        <select name="user_id" id="dp_user_id" class="form-control"  >
                            <option value="0">جميع المستخدمين</option>
                            <?php foreach($all_users as $row) :?>
                                <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المقر </label>
                    <div>
                        <input class="form-control" name="branch" id="txt_branch" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> اخر نشاط من </label>
                    <div>
                        <input class="form-control" name="date_from" id="txt_date_from" value="<?=date('d/m/Y')?>" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الى </label>
                    <div>
                        <input class="form-control" name="date_to" id="txt_date_to" value="<?=date('d/m/Y')?>" />
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url);?>
        </div>

    </div>

</div>

<?php

$scripts = <<<SCRIPT
<script type="text/javascript">

    $(document).ready(function() {
        $('#page_tb').dataTable({
            "lengthMenu": [ [-1,50], ["الكل","50"] ]
        });

        $('.user_agent').click(function(){
            $(this).find('div').toggle();
        });

        $('.user_agent').dblclick(function(){
            $('.user_agent div').toggle();
        });
    });

    $('#dp_user_id').select2();

    function search(){
        $('#container').text('');
        var values= {user_id: $('#dp_user_id').select2('val'), branch: $('#txt_branch').val(), date_from: $('#txt_date_from').val(), date_to: $('#txt_date_to').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_user_id').select2('val',0);
    }

    function edit_status(id, s){
        var msg='';
        var class_name='';
        if(s==0){
            msg='تفعيل';
            class_name='glyphicon glyphicon-eye-open color_green';
            s=1;
        }else if(s==1){
            msg='تعطيل';
            class_name='glyphicon glyphicon-eye-close color_red';
            s=0;
        }
        var values= {user_id: id, status: s };
        if(confirm('هل تريد '+msg+' المستخدم؟!')){
            get_data('{$status_url}', values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم '+msg+' المستخدم بنجاح ..');
                    $('#td'+id+' i').attr('class',class_name);
                    $('#td'+id+' a').attr('onclick','javascript:edit_status('+id+', '+s+');');
                    $('#td'+id+' div').text(s);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }


</script>

SCRIPT;

sec_scripts($scripts);

?>
