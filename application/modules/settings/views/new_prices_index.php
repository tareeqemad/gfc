<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/02/19
 * Time: 10:02 ص
 */

$MODULE_NAME= 'settings';
$TB_NAME= 'data_migration';
$update_all_prices_url=base_url("$MODULE_NAME/$TB_NAME/update_all_prices");
$update_class_price_url=base_url("$MODULE_NAME/$TB_NAME/update_class_price");
$update_class_purchasing_price_url=base_url("$MODULE_NAME/$TB_NAME/update_class_purchasing_price");
$update_class_used_percent_url=base_url("$MODULE_NAME/$TB_NAME/update_class_used_percent");
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>
        <ul></ul>

    </div>
<hr> <hr>
        <button type="button" onclick="javascript:update_all_prices();" class="btn btn-primary">تحديث السعر الافتتاحي لجميع الأصناف</button>

<hr>
    <hr>
    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label">  رقم الصنف</label>
                    <div>
                        <input type="text" name="class_id" id="txt_class_id" class="form-control">

                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">  اسم الصنف</label>
                    <div>
                        <input type="text" name="class_id_name" id="txt_class_id_name" class="form-control">

                    </div>
                </div>

            </div>
        </form>


              <button type="button" onclick="javascript:update_class_price();" class="btn btn-primary">  تحديث السعر الافتتاحي لصنف</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
     <hr>


        <div id="msg_container"></div>

        <div id="container">
        </div>

    </div>

 <hr>
    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form1" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label">  رقم الصنف</label>
                    <div>
                        <input type="text" name="class_id1" id="txt_class_id1" class="form-control">

                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">  اسم الصنف</label>
                    <div>
                        <input type="text" name="class_id_name1" id="txt_class_id_name1" class="form-control">

                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> سعر الصنف</label>
                    <div>
                        <input type="text" name="class_purchasing" id="txt_class_purchasing" class="form-control">

                    </div>
                </div>

            </div>
        </form>


            <button type="button" onclick="javascript:update_class_purchasing_price();" class="btn btn-primary">تحديث سعر البطاقة</button>
            <button type="button" onclick="javascript:clear_form1();"  class="btn btn-default"> تفريغ الحقول</button>
<hr> <hr>


        <div id="msg_container"></div>

        <div id="container">
        </div>

    </div>
    <hr>
    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form2" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label">  رقم الصنف</label>
                    <div>
                        <input type="text" name="class_id2" id="txt_class_id2" class="form-control">

                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">نسبة تسعير المستعمل</label>
                    <div>
                        <input type="text" name="used_percent" id="txt_used_percent" class="form-control">

                    </div>
                </div>

            </div>
        </form>


        <button type="button" onclick="javascript:update_class_used_percent();" class="btn btn-primary">تحديث نسبة تسعير المستعمل </button>
        <button type="button" onclick="javascript:clear_form2();"  class="btn btn-default"> تفريغ الحقول</button>
        <hr> <hr>


        <div id="msg_container"></div>

        <div id="container">
        </div>

    </div>
</div>



<?php

$scripts = <<<SCRIPT
<script type="text/javascript">


function clear_form(){
       clearForm($('#{$TB_NAME}_form'));

    }
function clear_form1(){
       clearForm($('#{$TB_NAME}_form1'));

    }

function update_all_prices(){
    if(confirm('هل تريد إتمام العملية ؟')){
      get_data('{$update_all_prices_url}',{},function(data){
      if(data ==1)
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');

}
}


    function update_class_price(){
    var class_id=$('#txt_class_id').val();
      if (class_id==''){
danger_msg('تحذير',' يجب إدخال رقم الصنف ');
}else{
    if(confirm('هل تريد إتمام العملية ؟')){
    get_data('{$update_class_price_url}',{class_id:class_id},function(data){
        alert(data);
      if(data ==1)
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){


                }, 1000);
            },'html');

}
}
    }

    function update_class_purchasing_price(){
    var class_id=$('#txt_class_id1').val();
    var class_purchasing=$('#txt_class_purchasing').val();
      if (class_id =='' || class_purchasing =='' || class_purchasing<=0){
danger_msg(' تحذير',' يجب إدخال رقم الصنف و سعر البطاقة الجديد ');
}else{
    if(confirm('هل تريد إتمام العملية ؟')){
    get_data('{$update_class_purchasing_price_url}',{class_id:class_id,class_purchasing:class_purchasing},function(data){
      if(data ==1)
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){


                }, 1000);
            },'html');

}
}
    }
    
        function update_class_used_percent(){
    var class_id=$('#txt_class_id2').val();
    var used_percent=$('#txt_used_percent').val();
      if (class_id =='' || used_percent =='' || used_percent<=0){
danger_msg(' تحذير',' يجب إدخال رقم الصنف و نسبة تسعير المستعمل الجديد ');
}else{
    if(confirm('هل تريد إتمام العملية ؟')){
    get_data('{$update_class_used_percent_url}',{class_id:class_id,used_percent:used_percent},function(data){
      if(data ==1)
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){


                }, 1000);
            },'html');

}
}
    }



</script>

SCRIPT;

sec_scripts($scripts);

?>
