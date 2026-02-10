<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 16/12/14
 * Time: 09:44 ص
 */

$TB_NAME= 'account_branches';
$create_url =base_url('settings/account_branches/create');
$get_url =base_url('settings/account_branches/get_accounts');

echo AntiForgeryToken() ;
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>

        <ul>
            <?php
            if (HaveAccess($create_url))
            echo "<li id='save' style='display: none;'><a onclick='javascript:{$TB_NAME}_save();' href='javascript:;'><i class='glyphicon glyphicon-saved'></i>حفظ</a></li>";
            ?>
        </ul>
    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div class="form-horizontal" >
            <div class="modal-body">
                <div class="form-group col-sm-8">
                    <label class="col-sm-2 control-label">الفروع</label>
                    <div class="col-sm-4">
                        <?=$branches?>
                    </div>
                    <div class="col-sm-2" style="padding-right: 20px;">
                        <input type="checkbox" id="check_all" />
                        <label for="check_all" class="control-label">تحديد الكل</label>
                    </div>

                </div>

            </div>
        </div>

        <div id="container">
            <?php echo modules::run('financial/accounts/public_get_accounts_all',true); ?>
        </div>
    </div>
</div>


<?php

$scripts = <<<SCRIPT
<script>

    $(function () {
        $('#accounts_tree').tree();
        $(':checkbox').prop('checked',false);

        $('#txt_branch').on('change',function(){
            $(':checkbox').prop('checked',false);
            if($('#txt_branch').val()!=''){
                get_data('$get_url',{branch:$('#txt_branch').val()} ,function(data){
                    if(data.length > 0){
                        $.each(data, function(i,item){
                            $('span[data-id="'+item.ACCOUNT+'"] :checkbox').prop('checked',true);
                        });
                    }
                },"json");
                $('li#save').show();
            }else
                $('li#save').hide();
        });

        $('#check_all').on('change',function(){
            var val= false;
            if( $(this).is(':checked') )
                val= true;
            $('#accounts_tree :checkbox').prop('checked',val);
        });
    });

    function {$TB_NAME}_save(){
        if(confirm('هل تريد الحفظ بالتأكيد؟!!')){
            var accounts= [];
            $('#accounts_tree :checkbox').each(function(){
                if( $(this).is(':checked') )
                    accounts.push($(this).val());
            });

            get_data('{$create_url}',
                {'account[]': accounts
                ,__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val()
                ,branch:$('#txt_branch').val()},
                function(data){
                    if(data==1){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    }else{
                        danger_msg('تحذير..',data);
                    }
                }
            );
        }
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
