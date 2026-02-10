<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 15/07/19
 * Time: 01:06 م
 */

$MODULE_NAME= 'dmg_documents';
$create_url_doc= base_url("$MODULE_NAME/documents/create");
$create_url_mail= base_url("$MODULE_NAME/mails/create");
$create_url_var_doc= base_url("$MODULE_NAME/various_doc/create");
$create_url_bond = base_url("$MODULE_NAME/bond/create");
$create_url_res_dis = base_url("$MODULE_NAME/receipts_disbursements/create");
$create_url_input_doc = base_url("$MODULE_NAME/input_doc/create");

$TB_NAME= 'mails';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">

            <div class="toolbar">

                <div class="caption"><?= $title ?></div>

                <ul>
                    <?php if( HaveAccess($create_url_bond)):  ?><li><a  href="<?= $create_url_bond ?>"><i class="glyphicon glyphicon-plus"></i>قيود</a> </li><?php endif; ?>
                    <?php if( HaveAccess($create_url_doc)):  ?><li><a  href="<?= $create_url_doc ?>"><i class="glyphicon glyphicon-plus"></i>دفاتر</a> </li><?php endif; ?>
                    <?php if( HaveAccess($create_url_mail)):  ?><li><a  href="<?= $create_url_mail ?>"><i class="glyphicon glyphicon-plus"></i>بريد</a> </li><?php endif; ?>
                    <?php if( HaveAccess($create_url_res_dis)):  ?><li><a  href="<?= $create_url_res_dis ?>"><i class="glyphicon glyphicon-plus"></i>سندات</a> </li><?php endif; ?>
                    <?php if( HaveAccess($create_url_var_doc)):  ?><li><a  href="<?= $create_url_var_doc ?>"><i class="glyphicon glyphicon-plus"></i>مستندات متنوعة</a> </li><?php endif; ?>
                    <?php if( HaveAccess($create_url_input_doc)):  ?><li><a  href="<?= $create_url_input_doc ?>"><i class="glyphicon glyphicon-plus"></i>سندات ادخال</a> </li><?php endif; ?>

                    <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
                </ul>

            </div>



        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <div class="modal-body inline_form">


                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم النموذج</label>
                        <div>
                            <input type="text" data-val-required="حقل مطلوب"
                                   name="ser"
                                   id="txt_ser" class="form-control"
                                >
                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label">تاريخ النموذج</label>
                        <div>
                            <input type="text" data-val-required="حقل مطلوب" data-type="date"
                                   data-date-format="DD/MM/YYYY" name="model_date"
                                   id="txt_model_date" class="form-control"
                                >
                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label">نوع النموذج</label>
                        <div>
                            <select name="model_type" id="dp_model_type" class="form-control sel2">
                                <option></option>
                                <?php foreach($model_type as $row) :?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?php echo $row['CON_NAME']  ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>


                    <div id="document_type_div" <?=$hide?> >
                        <div  class="form-group col-sm-2">
                            <label class="control-label">سنة الدفتر</label>
                            <div>
                                <input type="text"
                                       name="document_year"
                                       id="txt_document_year"
                                       data-val="true"
                                       class="form-control ">
                            </div>
                        </div>

                        <div  class="form-group col-sm-2">
                            <label class="control-label">نوع الدفتر</label>
                            <div>
                                <select name="document_type" id="dp_document_type" class="form-control sel2">
                                    <option></option>
                                    <?php foreach($document_type as $row) :?>
                                        <option  value="<?= $row['CON_NO'] ?>"><?php echo $row['CON_NAME']  ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <br>

                </div>


            </form>


            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php echo  modules::run('dmg_documents/mails/get_page',$page); ?>
            </div>
        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">



$('.sel2').select2();
function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

        function values_search(add_page){
                var values=
                {page:1, ser:$('#txt_ser').val(), model_date:$('#txt_model_date').val(), model_type:$('#dp_model_type').val()};
                if(add_page==0)
                delete values.page;
                return values;
        }


 function search(){
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }



</script>

SCRIPT;

sec_scripts($scripts);

?>