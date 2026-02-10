<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 06/11/18
 * Time: 11:16 ص
 */
$MODULE_NAME= 'hr_attendance';
$TB_NAME="official_vacations";
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>

        <ul>
            <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
        </ul>

    </div>
</div>
<div class="form-body">
    <div id="msg_container"></div>
    <div id="container">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند </label>
                    <div>
                        <input type="text" value="" readonly id="txt_ser" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الاجازة</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="" name="v_date" id="txt_v_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">بيان الاجازة</label>
                    <div>
                        <input type="text" value="" name="v_note"  id="v_note" class="form-control" />
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
            </div>

        </form>
    </div>
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
