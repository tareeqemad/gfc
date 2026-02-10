<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 24/11/14
 * Time: 10:14 ص
 */
$MODULE_NAME= 'stores';
$TB_NAME= 'classes';
$get_url =base_url("$MODULE_NAME/$TB_NAME/public_get_project_classes");
$parents_url=base_url("$MODULE_NAME/$TB_NAME/public_get_parents");
?>

<div class="form-body">

    <div class="modal-body inline_form">

        <div style="color: #ff0000"> الاصناف غير المسعرة لا تظهر ضمن القائمة.. </div>

        <form  id="<?=$TB_NAME?>_form" method="get" action="<?=$get_url?>" role="form" novalidate="novalidate">

            <div class="form-group col-sm-1">
                <label class="control-label">رقم الصنف</label>
                <div>
                    <input type="hidden"  name="text"  value="<?= $text ?>"   id="txt_text" class="form-control">
                    <input type="text"  name="id" id="txt_id" value="<?= $id ?>" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-2">
                <label class="control-label">اسم الصنف-عربي</label>
                <div>
                    <input type="text"  name="name_ar"  value="<?= $name_ar ?>"  id="txt_name_ar" class="form-control">
                </div>
            </div>


            <div class="form-group col-sm-2">
                <label class="control-label"> اسم الصنف-انجليزي </label>
                <div>
                    <input type="text"  name="name_en"  value="<?= $name_en ?>"  id="txt_name_en" class="form-control">
                </div>
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label">الصنف الجد</label>
                <div>
                    <select name="grand_id" style="width: 200px" id="dp_grand_id" >
                        <option></option>
                        <?php foreach($grands as $row) :?>
                            <option value="<?= $row['PARENT_ID'] ?>"><?= $row['PARENT_ID'].":".$row['CLASS_NAME_AR'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div></div>

            <div class="form-group col-sm-3">
                <label class="control-label">الصنف الأب</label>
                <div id="txt_parent_id">
                    <select name="parent_id" style="width: 200px" id="dp_parent_id" >
                        <option></option>
                        <?php foreach($class_parent_id as $row) {
                            echo "<option value='{$row['PARENT_ID']}'>"."{$row['PARENT_ID']}:{$row['CLASS_NAME_AR']}"."</option>";
                        } ?>
                    </select>
                </div></div>

            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>
                <div>
                    <button type="button" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success">بحث</button>
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>
                <div>
                    <button type="button" onclick="javascript:<?=$TB_NAME?>_clear();" class="btn btn-success">تفريغ الحقول</button>
                </div>
            </div>
        </form>
    </div>

    <div>
        <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i class="icon icon-download"></i> إدراج المختار </a>
    </div>
    <br>
    <div id="container">
        <?=modules::run("$MODULE_NAME/$TB_NAME/public_get_project_classes", array('text'=>$text, 'id'=>$id, 'name_ar'=>$name_ar, 'name_en'=>$name_en, 'parent_id'=>$parent_id, 'grand_id'=>$grand_id, 'page'=>$page)); ?>
    </div>
    <div>
        <a class="btn-xs btn-danger" onclick="javascript:select_choose();"  href="javascript:;"><i class="icon icon-download"></i> إدراج المختار </a>
    </div>
</div>

</div>

<?php
$scripts = <<<SCRIPT
<script>
     $(function(){
        $('#dp_parent_id').select2().on('change',function(){

        //     checkBoxChanged();
    });
      $('#dp_grand_id').select2().on('change',function(){

         //    checkBoxChanged();

         $('#dp_parent_id').text('');
          get_data('$parents_url', {grand_id:$('#dp_grand_id').val()}, function(ret){ $('#dp_parent_id').html(ret); }, 'html');

    });
tab_click();

        $('input[type="text"],body').bind('keydown', 'f3', function() {

{$TB_NAME}_search();
            return false;
        });

        $('input[type="text"],body').bind('keydown', 'f2', function() {
            {$TB_NAME}_clear();

            return false;
        });


    });

    function {$TB_NAME}_search(){
        var id= $('#txt_id').val();
        var name_ar= $('#txt_name_ar').val();
         var name_en= $('#txt_name_en').val();
           var parent_id= $('#dp_parent_id').val();
             var grand_id= $('#dp_grand_id').val();
                  var text= $('#txt_text').val();
        get_data('{$get_url}',{id:id, name_ar:name_ar, name_en:name_en, parent_id:parent_id,grand_id:grand_id,text:text},function(data){
            $('#container').html(data);
        },'html');
    }

    function {$TB_NAME}_clear(){
        $('#{$TB_NAME}_form :input').val('');
    }

    function select_class(id,name_ar,price,sale_price,unit,unit_name,used_price,used_buy_price){

        var curr_val =isNaNVal( parent.$('#txt_curr_value').val());

        parent.$('#{$text}').val(id+': '+name_ar);
        parent.$('#h_{$text}').val(id);$('#h_{$text}').attr('value',id);
        parent.$('#h_unit_{$text}').val(unit);
        parent.$('#unit_{$text}').val(unit_name);
        parent.$('#price_{$text}').val(price);
        parent.$('#amount_{$text}').val(1);
        parent.$('#price_{$text}').attr('data-buy',price);
        parent.$('#price_{$text}').attr('data-sale',sale_price);
        parent.$('#price_{$text}').attr('data-used-buy',used_buy_price);
        parent.$('#bu_price_{$text}').val(sale_price);
        parent.$('#bu_buy_price_{$text}').val(price);
        parent.$('#used_price_{$text}').val(used_buy_price);
        parent.$('#used_buy_price_{$text}').val(used_buy_price);
        parent.$('#sal_price_{$text}').val(isNaNVal(sale_price / curr_val));

        if (typeof parent.currency_value == 'function') {
             parent.currency_value();
        }
    }


    function select_choose(){

        $('.checkboxes:checked').each(function(i){
            var obj = jQuery.parseJSON($(this).attr('data-val'));
            if($('#{$text}').val() == ''){
                select_class(obj.CLASS_ID,obj.CLASS_NAME_AR,obj.CLASS_PURCHASING,obj.SALE_PRICE,obj.CLASS_UNIT_SUB,obj.UNIT_NAME,obj.USED_SELL_PRICE,obj.USED_BUY_PRICE);
            }else{
               parent.AddRowWithData(obj.CLASS_ID,obj.CLASS_NAME_AR,obj.CLASS_PURCHASING,obj.SALE_PRICE,obj.CLASS_UNIT_SUB,obj.UNIT_NAME,obj.USED_SELL_PRICE,obj.USED_BUY_PRICE);
            }
        });

        $('.checkboxes:checked').prop('checked',false);

         if (typeof parent.currency_value == 'function') {
             parent.currency_value();
        }
    }

</script>
SCRIPT;
sec_scripts($scripts);

?>
