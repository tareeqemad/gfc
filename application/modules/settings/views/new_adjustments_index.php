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

$add_url =base_url("$MODULE_NAME/$TB_NAME/add_new_adjustments");
$update_url =base_url("$MODULE_NAME/$TB_NAME/update_new_adjustments");
$update_donation_url=base_url("$MODULE_NAME/$TB_NAME/update_donation_balance");
$update_orders_url=base_url("$MODULE_NAME/$TB_NAME/update_order_detail_balance");
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>
        <ul></ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                  <div class="form-group col-sm-3">
                    <label class="control-label">  المخزن</label>
                    <div>

                        <select name="store_id"  class="form-control"  id="dp_store_id">
                            <option></option>
                            <?php foreach ($stores as $row) : ?>
                                <option value="<?= $row['STORE_ID'] ?>"><?= $row['STORE_NO'] . ":" . $row['STORE_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                                <span class="field-validation-valid" data-valmsg-for="store_id"  data-valmsg-replace="true"></span>

                     </div>
                </div>



                <div class="form-group col-sm-3">
                    <label class="control-label"> ملاحظات </label>
                    <div>
                        <input type="text" name="notes" id="txt_notes" class="form-control">
                    </div>
                </div>



            </div>
        </form>


            <button type="button" onclick="javascript:add();" class="btn btn-primary"> سحب الرصيد الافتتاحي</button>
            <button type="button" onclick="javascript:update();" class="btn btn-primary"> تحديث الرصيد الافتتاحي</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>



        <div id="msg_container"></div>

        <div id="container">
             </div>

    </div>
<hr>

        <button type="button" onclick="javascript:update_donation();" class="btn btn-primary"> تحديث الرصيد الافتتاحي للمنح</button>

<hr>

        <button type="button" onclick="javascript:update_orders();" class="btn btn-primary">تحديث الرصيد الافتتاحي للتوريدات</button>


    <hr>
</div>



<?php

$scripts = <<<SCRIPT
<script type="text/javascript">

  $('select[name="store_id"]').select2();

function clear_form(){
       clearForm($('#{$TB_NAME}_form'));

    }

    function add(){
    var store_id=$('#dp_store_id').val()
      if (store_id==''){
danger_msg('تحذير',' يجب اختيار المخزن ');
}else{
    if(confirm('هل تريد إتمام العملية ؟')){
    var notes= $('#txt_notes').val();
    get_data('{$add_url}',{store_id:store_id,notes:notes},function(data){
      if(data ==1)
    //  console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

              //    window.location.reload();

                }, 1000);
            },'html');

}
}
    }
function update(){
      var store_id=$('#dp_store_id').val()
      if (store_id==''){
danger_msg('تحذير',' يجب اختيار المخزن ');
}else{
    if(confirm('هل تريد إتمام العملية ؟')){
    var notes= $('#txt_notes').val();
    get_data('{$update_url}',{store_id:store_id,notes:notes},function(data){
      if(data ==1)
     // console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                //  window.location.reload();

                }, 1000);
            },'html');

}
}
    }

function update_donation(){
    if(confirm('هل تريد إتمام العملية ؟')){
      get_data('{$update_donation_url}',{},function(data){
      if(data == 1)
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                //  window.location.reload();

                }, 1000);
            },'html');

}
}

function update_orders(){
    if(confirm('هل تريد إتمام العملية ؟')){
      get_data('{$update_orders_url}',{},function(data){
      if(data ==1)
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                //  window.location.reload();

                }, 1000);
            },'html');

}
}
</script>

SCRIPT;

sec_scripts($scripts);

?>
