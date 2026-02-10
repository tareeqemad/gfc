<?php

$MODULE_NAME= 'stores';
$TB_NAME= 'class_init';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

if(HaveAccess($edit_url))
    $edit= 'edit';
else
    $edit= '';

?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الطلب</label>
                    <div>
                        <input type="text" name="ser" id="txt_ser" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الصنف الأب</label>
                    <div>
                        <select name="class_parent_id" id="dp_class_parent_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($class_parent_id_cons as $row) :?>
                                <option value="<?=$row['CLASS_ID']?>"><?=$row['CLASS_ID'].': '.$row['CLASS_NAME_AR']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم الصنف بالعربية</label>
                    <div>
                        <input type="text" name="class_name_ar" id="txt_class_name_ar" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع العهدة</label>
                    <div>
                        <select name="custody_type" id="dp_custody_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($custody_type_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">سعر الشراء / السوق</label>
                    <div>
                        <input type="text" name="buy_price" id="txt_buy_price" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الحالة</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($adopt_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">المدخل</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control sel2" >
                            <option selected value="">الكل</option>
                            <option value="1">أنا</option>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $ser, $class_parent_id, $class_name_ar, $custody_type, $buy_price, $adopt, $entry_user);?>
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function values_search(add_page){
        var values= {page:1, ser:$('#txt_ser').val(), class_parent_id:$('#dp_class_parent_id').val(), class_name_ar:$('#txt_class_name_ar').val(), custody_type:$('#dp_custody_type').val(), buy_price:$('#txt_buy_price').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val() };
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

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }

    </script>
SCRIPT;
sec_scripts($scripts);
?>
