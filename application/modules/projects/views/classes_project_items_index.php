<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 09/03/16
 * Time: 11:25 ص
 */

?>

<div class="form-body">

    <div class="modal-body inline_form">

        <div style="color: #ff0000"> الاصناف غير المسعرة لا تظهر ضمن القائمة.. </div>


    </div>

    <div>
        <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i class="icon icon-download"></i> إدراج المختار </a>
    </div>
    <br>
    <div id="container">
        <?=modules::run("projects/projects/public_get_project_items", array('text'=>$text, 'project_serial'=>$project_serial)); ?>
    </div>
    <div>
        <a class="btn-xs btn-danger" onclick="javascript:select_choose();"  href="javascript:;"><i class="icon icon-download"></i> إدراج المختار </a>
    </div>
</div>

</div>

<?php
$scripts = <<<SCRIPT
<script>



    function select_class(id,name_ar,price,sale_price,unit,unit_name,count){

        var curr_val =isNaNVal( parent.$('#txt_curr_value').val());

        parent.$('#{$text}').val(id+': '+name_ar);
        parent.$('#h_{$text}').val(id);$('#h_{$text}').attr('value',id);


        parent.$('#h_unit_{$text}').val(unit);
        parent.$('#unit_{$text}').val(unit_name);
        parent.$('#unit_{$text}').val(unit);
        parent.$('#unit_name_{$text}').val(unit_name);

        parent.$('#price_{$text}').val(price);
        parent.$('#amount_{$text}').val(1);
        parent.$('#price_{$text}').attr('data-sale',sale_price);
        parent.$('#bu_price_{$text}').val(sale_price);
        parent.$('#sal_price_{$text}').val(isNaNVal(sale_price / curr_val));

        parent.$('#count_{$text}').val(count);

        if (typeof parent.currency_value == 'function') {
             parent.currency_value();
        }
    }


    function select_choose(){

        $('.checkboxes:checked').each(function(i){
            var obj = jQuery.parseJSON($(this).attr('data-val'));
            if($('#{$text}').val() == ''){
                select_class(obj.CLASS_ID,obj.CLASS_ID_NAME,obj.SAL_PRICE,obj.SAL_PRICE,obj.CLASS_UNIT,obj.UNIT_NAME,obj.LEFT_AMOUNT);
            }else{
              if (typeof parent.AddRowWithData == 'function') {
               parent.AddRowWithData(obj.CLASS_ID,obj.CLASS_ID_NAME,obj.SAL_PRICE,obj.SAL_PRICE,obj.CLASS_UNIT,obj.UNIT_NAME,obj.LEFT_AMOUNT);
               }

                 if (typeof parent.AddRowWithDataWork == 'function') {
                         parent.AddRowWithDataWork(obj.CLASS_ID,obj.CLASS_ID_NAME,obj.SAL_PRICE,obj.SAL_PRICE,obj.CLASS_UNIT,obj.UNIT_NAME,obj.LEFT_AMOUNT);
                  }

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
