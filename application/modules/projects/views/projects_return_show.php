<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 28/09/2020
 * Time: 10:00 ص
 */
$back_url = base_url('projects/projects');
$store_item_url = base_url('stores/classes/public_project_index');
$price_url = base_url('stores/Classes/project_item_price');
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;
$report_url = "https://gs.gedco.ps/gfc/jsperreport/showreport?sys=technical";
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
                  action="<?= base_url('projects/projects/returnitems') ?>"
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
                        <?php if(HaveAccess($price_url)){ ?>
                            <button type="button" onclick='javascript:update_price_return();' class="btn btn-danger"> تحديث
                                الأسعار
                            </button>
                        <?php }?>
                        <button type="button"
                                onclick="javascript:_showReport('<?= $report_url ?>&report=project_return_items_pdf&p_project_ser=<?= $rs['PROJECT_SERIAL'] ?>');"
                                class="btn btn-secondary"> المواد المرجعة pdf
                        </button>

                        <button type="button"
                                onclick="javascript:_showReport('<?= $report_url ?>&report_type=xls&report=project_return_items_xls&p_project_ser=<?= $rs['PROJECT_SERIAL'] ?>');"
                                class="btn btn-secondary"> المواد المرجعة xls
                        </button>
                    </div>
                    <div style="clear: both;">
                        <?php echo modules::run('projects/projects/public_get_return_items', $HaveRs ? $rs['PROJECT_SERIAL'] : 0); ?>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php if(HaveAccess($price_url)){ ?>
                        <button type="button" onclick='javascript:update_price_return();' class="btn btn-danger"> تحديث
                            الأسعار
                        </button>
                        <?php }?>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>
<?php

$delete_url = base_url('projects/projects/delete_return_item_details');
$script = <<<SCRIPT
<script>


reBind();

function reBind(){
       $('input[name="class_id_name[]"]').click(function(){
            _showReport('$store_item_url/'+$(this).attr('id')+'/');
        });
}

function currency_value(){
    $('#report').modal('hide');
}

function delete_return_details(a,id,p){
    
    var ser = $(a).closest('tr').find('input[name="SER[]"]').val();
   
    if(id == 0 || isNaNVal(ser) <= 0 || ser == undefined){
        if($('tr', $(a).closest('table')).length > 1)
        $(a).closest('tr').remove();
    } else {
        
           get_data('{$delete_url}',{id:id,proj:p},function(data){
                             if(isNaNVal(data) >= 0){
                                $(a).closest('tr').remove();
                                $('#txt_return_cost').val(data);
                                }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                
    }
}

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


function  update_price_return(){
        $('#return_items_detailsTbl tbody tr input[name="class_id[]"]').each(function(i){
                var obj =$(this);
            get_data('{$price_url}',{id:$(this).val()},function(data){
                 if(data.length > 0){
                 //console.log('',data);
                     //$(obj).closest('tr').find('input[name="price[]"]').val(data[0].USED_SELL_PRICE);
                     $(obj).closest('tr').find('input[name="price[]"]').val(data[0].USED_BUY_PRICE);
                 }
            });
        });
    }
</script>
SCRIPT;

sec_scripts($script);
?>


