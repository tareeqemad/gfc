<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 20/02/19
 * Time: 09:19 ص
 */

$MODULE_NAME= 'gis';
$TB_NAME="User_Controller";
$rs=($isCreate)? array(): count($users_data) > 0 ? $users_data[0] : array() ;
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));

?>
<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>

            <li><a  href="<?= @$back_url ?>"><i class="icon icon-reply"></i> </a> </li>
        </ul>

    </div>
</div>
<div class="form-body">
    <div id="msg_container"></div>
    <div id="container">

        <form class="form-vertical" id="<?=@$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <!------------------------------------------------------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label">ID</label>
                    <div>
                        <input type="text" name="ID"   readonly placeholder="USER_ID" id="txt_ID" class="form-control"
                               value="<?php echo $rs['ID'] ;?>">
                    </div>
                </div>
                <!------------------------------------------------------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label"> USER_ID</label>
                    <div>
                        <input type="text" name="USER_ID"   readonly placeholder="USER_ID" id="txt_USER_ID" class="form-control"
                               value="<?php echo $rs['USER_ID'] ;?>">
                    </div>
                </div>
                <!------------------------------------------------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label">UserName</label>
                    <div>
                        <input type="text"  name="USER_NAME"   placeholder="username" readonly id="txt_UserName" class="form-control"
                               value="<?php echo $rs['USER_NAME'] ;?>"/>
                    </div>
                </div>


                <!------------------------------------------------------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label"> PassWord</label>
                    <div>
                        <input type="text"  name="PASS_WORD"placeholder="password" id="txt_PassWord" class="form-control"
                               value="<?php echo $rs['PASS_WORD'] ;?>">
                    </div>
                </div>


            </div>

            <div class="modal-footer">

                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
            </div>

            <!-------------------------------------------------------->



            <!------------------------------------------------------------------------>
        </form>
        <div id="msg_container"> </div>
        <div id="container">

        </div>
        <!-------------------------------------------------------------------------->
    </div>
    <?php
    $scripts = <<<SCRIPT
<script type="text/javascript">



    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
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


