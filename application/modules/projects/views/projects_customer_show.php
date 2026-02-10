<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 28/09/2020
 * Time: 10:00 ص
 */
$back_url = base_url('projects/projects');
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>


    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-form-vertical" id="projects_form" method="post"
                  action="<?= base_url('projects/projects/customeritem') ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> الرقم </label>
                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_SERIAL'] : "" ?>" readonly
                                   name="project_serial" id="txt_project_serial" class="form-control">

                        </div>
                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label"> التصنيف الفنى للمشروع

                        </label>
                        <div>
                            <select disabled class="form-control" name="project_tec_type" id="dp_project_tec_type">
                                <?php foreach ($project_tec_type as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['PROJECT_TEC_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"
                                            data-tec="<?= $row['ACCOUNT_ID'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label"> الرقم الفني </label>
                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_TEC_CODE'] : "" ?>" readonly
                                   name="project_tec_code" id="txt_project_tec_code" class="form-control">

                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label"> نوع المشروع
                        </label>
                        <div>
                            <select disabled class="form-control" name="project_type" id="dp_project_type">
                                <?php foreach ($project_type as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['PROJECT_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label">حساب المستفيد
                        </label>
                        <div>
                            <input readonly name="customer_id" type="text"
                                   value="<?= $HaveRs ? $rs['CUSTOMER_ID'] : "" ?>"
                                   id="h_txt_customer_id" class="form-control">


                        </div>
                    </div>
                    <div class="form-group  col-sm-3">
                        <label class="control-label"> اسم المشروع </label>
                        <div>
                            <input readonly type="text" name="project_name"
                                   value="<?= $HaveRs ? $rs['PROJECT_NAME'] : "" ?>"
                                   data-val="true" data-val-required="حقل مطلوب" id="txt_project_name"
                                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="project_name"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <hr>
                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    </div>
                    <div style="clear: both;">
                        <?php echo modules::run('projects/projects/public_get_customer_details', $HaveRs ? $rs['PROJECT_SERIAL'] : 0); ?>
                    </div>
                    <hr>
                    <div class="modal-footer">

                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>
<?php
$script = <<<SCRIPT
<script>
    $('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    
    
      var form = $(this).closest('form');
                ajax_insert_update(form,function(data){
                    if(data==1){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
    
                        reload_Page();
    
                    }else{
                        danger_msg('تحذير..',data);
                    }
                },'html');
          
        });

</script>
SCRIPT;

sec_scripts($script);
?>


