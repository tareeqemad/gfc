<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'purchases';
$TB_NAME = 'customers_activity';
$MODULE_NAME1 = 'payment';
$TB_NAME1 = 'customers';
$gfc_domain= gh_gfc_domain();
$get_details_url = base_url("$MODULE_NAME1/$TB_NAME1/public_get_activites");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$back_url = base_url("$MODULE_NAME/$TB_NAME/index");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
$report_jasper_url= base_url("JsperReport/showreport?sys=financial/purchases");
$report_sn= report_sn();
if (!isset($result)) $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;
$msg_note='';

echo AntiForgeryToken();
?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <?php //HaveAccess($back_url)
            if (TRUE): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a>
                </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>


    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">

        <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form"
              novalidate="novalidate">
            <div class="modal-body inline_form">
                <fieldset>
                    <legend>المورد</legend>
                    <div class="form-group col-sm-4">
                        <label class="control-label">المورد</label>
                        <div>
                            <select  name="customer_id_name" id="dp_customer_id_name"
                                    class="form-control">
                                <option></option>
                                <?php foreach ($customer_id as $row) : ?>
                                    <option <?=$HaveRs?($rs['CUSTOMER_ID']==$row['CUSTOMER_ID']?'selected':''):''?> value="<?= $row['CUSTOMER_ID'] ?>"><?= $row['CUSTOMER_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>



                </fieldset>
                <hr/>


                <hr/>


                <hr/>
                <fieldset>
                    <legend>الأنشطة</legend>
                    <div style="clear: both" >
                        <?php echo modules::run("$MODULE_NAME1/$TB_NAME1/public_get_activites", (count($rs)>0)?$rs['CUSTOMER_ID']:0); ?>
                    </div>
                </fieldset>

                <hr/>




            </div>

            <div class="modal-footer">
                <?php if ((HaveAccess($create_url) || HaveAccess($edit_url)) ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    $('#dp_customer_id_name').select2();
///////////////////////////////////////////////////////////////////
    reBind_pram(0);
///////////////////////////////////////////////////////////////////
    function reBind_pram(cnt){
    $('table tr td .select2-container').remove();
    $('select[name="activity_no[]"]').select2();
}
////////////////////////////////////////////////////////////////// 
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){

            if(parseInt(data)>=1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
               get_to_link('{$get_url}/'+parseInt(data));
            }else{
                danger_msg('تحذير..',data);
            }

        },"html");
    });
 ///////////////////////////////////////////////////////////////////////
     function deleteactivity(ser){

        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            var url = '{$delete_url}';
            var values= {ser:ser};
            ajax_delete_any(url, values ,function(data){
            if(data==1){
               success_msg('رسالة','تم حذف السجل بنجاح ..');
             get_to_link(window.location.href);
            }
            else{
              danger_msg('تحذير..',data);
             }

            });
        }
    }   

</script>
SCRIPT;
sec_scripts($scripts);
?>
