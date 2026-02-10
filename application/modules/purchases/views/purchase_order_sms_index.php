<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/10/18
 * Time: 08:54 ص
 */
$MODULE_NAME= 'purchases';
$TB_NAME= 'purchase_order';
$get_page_url= base_url("$MODULE_NAME/$TB_NAME/get_page_sms");
$send_sms_url=base_url("$MODULE_NAME/$TB_NAME/sms");
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

<ul>
    <?php if( HaveAccess($send_sms_url)):  ?><li><a  href="javascript:;" onclick="javascript:send_sms(this);"><i class="glyphicon glyphicon-plus"></i>إرسال SMS </a> </li><?php endif; ?>

    <!--href="<? //= $send_sms_url ?>" <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>-->
</ul>

</div>

<div class="form-body">

    <form class="form-vertical" id="<?=$TB_NAME?>_form" >
        <div class="modal-body inline_form">


            <div class="form-group col-sm-4">
                <label class="control-label">نوع الطلب</label
                <div>
                    <select name="purchase_type" id="dp_purchase_type" class="form-control"  multiple>
                        <option value="0">--</option>
                        <?php foreach($purchase_order_class_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-4"  style="color: #a21005;bold;border-bottom: 5px;border: 5px solid red; ">
                <br>

                <label class="control-label">نص الرسالة </label
                <div>
                   <textarea name="sms_text" id="sms_text" style="margin: 0px; width: 673px; height: 52px;"></textarea>
                </div>
            </div>

        </div>
    </form>

    <div class="modal-footer">
        <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
        <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
    </div>


    <div id="msg_container"></div>

    <div id="container">
        <?=modules::run($get_page_url, $page ,$purchase_type );?>
    </div>

</div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.pagination li').click(function(e){
        e.preventDefault();
    });



    function search(){
               var values= {page:1,purchase_type:$('#dp_purchase_type').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
           var values= {page:1,purchase_type:$('#dp_purchase_type').val() };
           ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
         clearForm($('#{$TB_NAME}_form'));

    }

     function send_sms(obj){
var m='';
 var c=$('#counterx').val();
  for (i=1;i< c;i++){
if($('#mobile_no_'+i).is(':checked')){
 m=m+','+$('#mobile_no_'+i).val();
}
   }
    if(confirm('هل تريد إتمام العملية ؟')){
        get_data('{$send_sms_url}',{sms_text:$('#sms_text').val(),mobile:m},function(data){
                if(data=='1001') success_msg('تم الإرسال بنجاح');
                else danger_msg('تحذير','لم يتم إرسال الرسالة...حاول مرة أخرى');
                },'html');

    }
    }

    function checks(obj){


    var  x=$('input[name="checkall"]:checked').val();
    var c=$('#counterx').val();
    if(x==1){
    for (i=1;i< c;i++){
    $('#mobile_no_'+i).prop('checked',true);
   // m=$('#mobile_no_'+i).val();
//alert(m);
  }
    }else {
   for (i=1;i< c;i++){
  $('#mobile_no_'+i).prop('checked',false);

    }

    }

    }
</script>
SCRIPT;
sec_scripts($scripts);
?>
