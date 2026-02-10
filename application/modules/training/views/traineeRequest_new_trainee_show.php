<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 27/07/20
 * Time: 10:57 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'traineeRequest';
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_trainee");

$count=0;
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
            <?php endif; ?>
        </ul>
    </div>

</div>
<form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?php echo $post_url ?>" role="form"
      novalidate="novalidate">
<div class="modal-body inline_form">
    <fieldset class="field_set">
        <legend>بيانات المدرب</legend>
        <br>

        <div class="form-group">
            <label class="col-sm-1 control-label">رقم المسلسل</label>
            <div class="col-sm-2">
                <input type="text" readonly name="ser"
                       value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                       id="txt_ser" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">رقم الهوية</label>
            <div class="col-sm-2">
                <input type="text"  name="id"
                       value="<?= $HaveRs ? $rs['ID'] : "" ?>"
                       id="txt_id" class="form-control">
            </div>

            <label class="col-sm-1 control-label">اسم المدرب / اللغة العربية</label>
            <div class="col-sm-2">
                <input type="text" readonly  name="name"
                       value="<?= $HaveRs ? $rs['NAME'] : "" ?>"
                       id="txt_name" class="form-control">
            </div>

            <label class="col-sm-1 control-label">اسم المدرب/ اللغة الانجليزية</label>
            <div class="col-sm-2">
                <input type="text" readonly name="name_eng"
                       value="<?= $HaveRs ? $rs['NAME_ENG'] : "" ?>"
                       id="txt_name_eng" class="form-control">
            </div>

        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">التخصص الجامعي</label>
            <div class="col-sm-2">
                <input type="text"  name="spec"
                       value="<?= $HaveRs ? $rs['SPEC'] : "" ?>"
                       id="txt_spec" class="form-control">
            </div>

            <label class="col-sm-1 control-label">تاريخ الميلاد </label>
            <div class="col-sm-2">
                <input type="text"  name="birth_date" ata-val-required="حقل مطلوب" data-type="date"
                       data-date-format="DD/MM/YYYY"
                       value="<?= $HaveRs ? $rs['BIRTH_DATE'] : "" ?>"
                       id="txt_birth_date" class="form-control">
            </div>

            <label class="col-sm-1 control-label">رقم الهاتف</label>
            <div class="col-sm-2">
                <input type="text"  name="mobile"
                       value="<?= $HaveRs ? $rs['MOBILE'] : "" ?>"
                       id="txt_mobile" class="form-control">
            </div>

        </div>

        <div class="form-group">
            <label class="col-sm-1 control-label">البريد الالكتروني</label>
            <div class="col-sm-2">
                <input type="text"  name="email"
                       value="<?= $HaveRs ? $rs['EMAIL'] : "" ?>"
                       id="txt_email" class="form-control">
            </div>

        </div>

        <br><br>
    </fieldset>

    <div class="modal-footer">
        <?php
        if (HaveAccess($post_url)) : ?>
            <button type="button" id="save_trainee_gedco" class="btn btn-primary">حفظ البيانات</button>
        <?php endif; ?>
    </div>


</div>

</form>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
       $('#save_trainee_gedco').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    var d = 5;
                    get_to_link('{$get_url}/'+parseInt(data));

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