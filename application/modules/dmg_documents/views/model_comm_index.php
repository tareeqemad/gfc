<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 11/07/19
 * Time: 10:14 ص
 */


$MODULE_NAME= 'dmg_documents';
$TB_NAME= 'documents';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_comm");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">
        <div class="toolbar">
            <div class="caption"><?= $title; ?></div>
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
                <?php echo  modules::run('dmg_documents/documents/get_page_comm',$page); ?>
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