<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 24/11/14
 * Time: 10:14 ص
 */
$MODULE_NAME= 'stores';
$TB_NAME= 'classes';
$get_url =base_url("$MODULE_NAME/$TB_NAME/public_get_classes");
$parents_url=base_url("$MODULE_NAME/$TB_NAME/public_get_parents_classes");
?>

<div class="form-body"  >

    <div class="modal-body inline_form" >

        <div style="color: #ff0000"> الاصناف غير المسعرة لا تظهر ضمن القائمة.. </div>

        <form  id="<?=$TB_NAME?>_form" method="get" action="<?=$get_url?>" role="form" novalidate="novalidate">

            <div class="form-group col-sm-1">
                <label class="control-label">رقم الصنف</label>
                <div>
                    <input type="hidden"  name="text"  value="<?= $text ?>"   id="txt_text" class="form-control">
                    <input type="text"  name="id" id="txt_id" value="<?= $id ?>" class="form-control">
                    <input type="hidden"  name="class_status" id="txt_class_status" value="<?= $class_status ?>" class="form-control">
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
            <div class="form-group col-sm-2">
                <label class="control-label">الصنف الجد</label>
                <div>
                    <select name="grand_id" style="width: 100%" id="dp_grand_id" >
                        <option></option>
                        <?php foreach($grands as $row) :?>
                            <option value="<?= $row['PARENT_ID'] ?>"><?= $row['PARENT_ID'].":".$row['CLASS_NAME_AR'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div></div>
            <div class="form-group col-sm-2">
                <label class="control-label">الصنف الأب</label>
                <div  id="txt_parent_id">
                    <select name="parent_id" style="width: 100%" id="dp_parent_id" >
                        <option></option>
                        <?php foreach($class_parent_id as $row) :?>
                            <option value="<?= $row['PARENT_ID'] ?>"><?= $row['PARENT_ID'].":".$row['CLASS_NAME_AR'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div></div>

        <div style="width:150px; padding-top: 25px; float: left;">
            <button type="button" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success">بحث</button>
            <button type="button" onclick="javascript:<?=$TB_NAME?>_clear();" class="btn btn-default">تفريغ </button>
        </div>

<!--
            <div class="form-group col-sm-1">
                <label class="control-label"></label>
                <div>

                </div>
            </div>

-->
        </form>
    </div>
    <div>
        <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i class="icon icon-download"></i> إدراج المختار </a>
    </div>
    <div id="container">
        <?=modules::run("$MODULE_NAME/$TB_NAME/public_get_classes", array('text'=>$text, 'id'=>$id, 'name_ar'=>$name_ar, 'name_en'=>$name_en, 'parent_id'=>$parent_id, 'grand_id'=>$grand_id, 'class_status'=>$class_status ,'page'=>$page)); ?>
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
         var grand_id= $('#dp_grand_id').val()
          var parent_id= $('#dp_parent_id').val();
      var class_status= $('#txt_class_status').val();
        var text= $('#txt_text').val();

        if(id==null || id=='')
            id= -1;

              if(class_status==null || class_status=='')
            class_status= -1;

          //  alert(class_status);
        if(name_ar==null || name_ar=='')
            name_ar= -1;
        if(name_en==null || name_en=='')
            name_en= -1;
            if(grand_id==null || grand_id=='')
            grand_id= -1;
  if(parent_id==null || parent_id=='')
            parent_id= -1;
        parent.$('#h_data_search').val(((class_status != '')?((name_ar != '' || id != null || name_en!= '') && class_status == '' ||  class_status == '-1'  )?'/'+class_status:'':'/-1')+'/'+name_en+'/'+id+'/'+name_ar);

        get_data('{$get_url}',{id:id, name_ar:name_ar, name_en:name_en, parent_id:parent_id,grand_id:grand_id,class_status:class_status,text:text},function(data){
          $('#container').html(data);
        },'html');
    }

    function {$TB_NAME}_clear(){
        $('#{$TB_NAME}_form :input:not("#txt_class_status")').val('');
    }

    function select_class(id,name_ar,unit,price,unit_name,exp_account_cust,exp_account_cust_name,destruction_type_name,destruction_type,
    destruction_percent,destruction_account_id,destruction_account_id_name,average_life_span,average_life_span_type,average_life_span_type_name,calss_description,name_en,section_no
    ,classes_prices_ser ,buy_price,sell_price,used_sell_price,personal_custody_type  ){
        parent.$('#{$text}').val(name_ar);
          parent.$('#en_{$text}').val(name_en);
        parent.$('#h_{$text}').val(id);
        parent.$('#i_{$text}').val(id);
        parent.$('#unit_{$text}').val(unit);
       parent.$('#unit_name_{$text}').val(unit_name);
         if (parent.$.fn.select2) {
         parent.$('#unit_{$text}').select2("val", unit);
         }

        parent.$('#price_{$text}').val(price);
        parent.$('#class_price_{$text}').val(price);
       parent.$('#note_{$text}').val(calss_description);

       parent.$('#exp_account_cust_{$text}').val(exp_account_cust);
       parent.$('#exp_account_cust_name_{$text}').val(exp_account_cust_name);
       parent.$('#destruction_type_name_{$text}').val(destruction_type_name);
       parent.$('#destruction_type_{$text}').val(destruction_type);
       parent.$('#destruction_percent_{$text}').val(destruction_percent);
       parent.$('#destruction_account_id_{$text}').val(destruction_account_id);
       parent.$('#destruction_account_id_name_{$text}').val(destruction_account_id_name);
       parent.$('#average_life_span_{$text}').val(average_life_span);
       parent.$('#average_life_span_type_{$text}').val(average_life_span_type);
       parent.$('#average_life_span_type_name_{$text}').val(average_life_span_type_name);
        parent.$('#personal_custody_type_{$text}').val(personal_custody_type);
  
      parent.$('#{$text}').closest('tr').find('input[name="price[]"]').val(price);
      parent.$('#{$text}').closest('tr').find('input[name="note[]"]').val(calss_description);
      parent.$('#{$text}').closest('tr').find('input[name="section_no[]"]').val(section_no);
      parent.$('#{$text}').closest('tr').find('input[name="classes_prices_ser[]"]').val(classes_prices_ser);
      parent.$('#{$text}').closest('tr').find('input[name="buy_price[]"]').val(buy_price);
      parent.$('#{$text}').closest('tr').find('input[name="sell_price[]"]').val(sell_price);
      parent.$('#{$text}').closest('tr').find('input[name="used_sell_price[]"]').val(used_sell_price);
      parent.$('#{$text}').closest('tr').find('input[name="personal_custody_type[]"]').val(personal_custody_type);
        parent.$('#report').modal('hide');
   if (typeof parent.do_after_select == 'function') {
        parent.do_after_select();
} 
          parent.$('#{$text}').closest('tr').find('input[name="amount[]"]').focus();
          parent.$('#{$text}').closest('tr').find('input[name="request_amount[]"]').focus();
          parent.$('#{$text}').closest('tr').find('input[name="customer_price[]"]').focus();

    }
 function select_choose(){

        $('.checkboxes:checked').each(function(i){
            var obj = jQuery.parseJSON($(this).attr('data-val'));
            if(i == 0){
                select_class(obj.CLASS_ID,obj.CLASS_NAME_AR,obj.CLASS_UNIT_SUB,obj.CLASS_PURCHASING,obj.UNIT_NAME
                ,obj.EXP_ACCOUNT_CUST,obj.EXP_ACCOUNT_CUST_NAME,obj.DESTRUCTION_TYPE_NAME,obj.DESTRUCTION_TYPE,
    obj.DESTRUCTION_PERCENT,obj.DESTRUCTION_ACCOUNT_ID,obj.DESTRUCTION_ACCOUNT_ID_NAME,obj.AVERAGE_LIFE_SPAN
    ,obj.AVERAGE_LIFE_SPAN_TYPE,obj.AVERAGE_LIFE_SPAN_TYPE_NAME,obj.CALSS_DESCRIPTION,obj.CLASS_NAME_EN,obj.SECTION_NO
    ,obj.CLASSES_PRICES_SER ,obj.BUY_PRICE,obj.SELL_PRICE,obj.USED_SELL_PRICE,obj.PERSONAL_CUSTODY_TYPE); 
  
            }else{

             if (typeof parent.AddRowWithData == 'function') {
               parent.AddRowWithData(obj.CLASS_ID,obj.CLASS_NAME_AR,obj.CLASS_UNIT_SUB,obj.CLASS_PURCHASING,obj.UNIT_NAME,obj.EXP_ACCOUNT_CUST,obj.EXP_ACCOUNT_CUST_NAME,obj.DESTRUCTION_TYPE_NAME,obj.DESTRUCTION_TYPE,
                    obj.DESTRUCTION_PERCENT,obj.DESTRUCTION_ACCOUNT_ID,obj.DESTRUCTION_ACCOUNT_ID_NAME,obj.AVERAGE_LIFE_SPAN
                    ,obj.AVERAGE_LIFE_SPAN_TYPE,obj.AVERAGE_LIFE_SPAN_TYPE_NAME,obj.CALSS_DESCRIPTION,obj.CLASS_NAME_EN,obj.SECTION_NO );

             }

                 if (typeof parent.AddRowWithDataWork == 'function') {
                         parent.AddRowWithDataWork(obj.CLASS_ID,obj.CLASS_NAME_AR,obj.CLASS_PURCHASING,obj.SALE_PRICE,obj.CLASS_UNIT_SUB,obj.UNIT_NAME);
                  }
                   if (typeof parent.AddRowWithDataPurchase == 'function') {
                         parent.AddRowWithDataPurchase(obj.CLASS_ID,obj.CLASS_NAME_AR,obj.CLASS_UNIT_SUB,obj.UNIT_NAME,obj.CLASS_PURCHASING,obj.CLASSES_PRICES_SER ,obj.BUY_PRICE);
                  }

            }
        });


    }
</script>
SCRIPT;
sec_scripts($scripts);

?>
