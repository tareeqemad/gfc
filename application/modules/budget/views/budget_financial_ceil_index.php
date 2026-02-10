<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 28/08/16
 * Time: 11:57 ص
 */
$MODULE_NAME= 'budget';
$TB_NAME= 'budget_financial_ceil';
$post_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$adopt2_url= base_url("$MODULE_NAME/$TB_NAME/adopt2");
$adopt1_url= base_url("$MODULE_NAME/$TB_NAME/adopt1");
$get_page_url=base_url("$MODULE_NAME/$TB_NAME/get_page");
$section_url= base_url("$MODULE_NAME/$TB_NAME/public_select_section");
$adopt2_url= base_url("$MODULE_NAME/$TB_NAME/adopt2");
$adopt1_url= base_url("$MODULE_NAME/$TB_NAME/adopt1");
echo AntiForgeryToken();
?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?> <?=$year?></div>

        <ul>


        </ul>

    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <div id="branch" class="col-sm-3"><?=$select_branch?></div>
                <div id="type" class="col-sm-3">
                    <select name='type' id='txt_type' class='form-control'>
                        <?php foreach($consts as $row) :?>
                            <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
               <div id="section" class="col-sm-3"><?=$select_section?></div>

            </div>

            <div class="modal-footer">

                <?php if( HaveAccess($get_page_url) ) { ?>
                    <button type="button" onclick="javascript:search();" class="btn btn-default"> إستعلام</button>
                <?php } ?>

                <?php if( HaveAccess($post_url) ) { ?>
                    <button  type="button" onclick="javascript:save(this);" class="btn btn-primary">حفظ البيانات</button>
                <?php } ?>

                <?php if( HaveAccess($adopt2_url) ) { ?>
                    <button onclick="javascript:adopt2(this);" type="button" class="btn btn-success">اعتماد  </button>
                <?php } ?>

                <?php if( HaveAccess($adopt1_url) ) { ?>
                    <button onclick="javascript:adopt1(this);" type="button" class="btn btn-danger">إلغاء الاعتماد</button>
                <?php } ?>
            </div>

            <div id="container"></div>
        </form>

        <div id="msg_container"></div>

    </div>

</div>
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
$(document).ready(function() {

        $('#txt_section').select2();

  $('#txt_type').change(function(){
            $('#section').text('');

                get_data('$section_url', {type:$('#txt_type').val()}, function(ret){ $('#section').html(ret);
                   $('#txt_section').select2();
                 }, 'html');

    });

        $('#txt_section').change(function(){
            disable_save();
        });

    });



   function save(obj){
            if(confirm('هل تريد بالتأكيد حفظ جميع السجلات')){

var tbl = '#{$TB_NAME}_tb';
                 var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(obj).closest('form');
    $(form).attr('action','{$post_url}');
                ajax_insert_update(form,function(data){
              if(data==1){
               success_msg('رسالة','تم حفظ البيانات بنجاح ..');
               search();
                }else{
                 search();
              danger_msg('تحذير..',data);
                     }
                },"html");

            }
        }


    function disable_save(){

        $('li#save').hide();

        $('#container #data').text('');
    }

    function search(){

        if(( parseInt( $('#txt_section').select2('val')) > 0)){

            var values= { section: $('#txt_section').select2('val'), branch: $('#txt_branch').val() };
            get_data('{$get_page_url}',values ,function(data){

                $('#container').html(data);
                sum_val();
            },'html');
        }
     }

function sum_val(){

 $('.balance').change(function(){
  var sum=0;
    //var a=$(this).closest('tr').find('.balance');
    $('.balance').each(function(i,j){
        sum+=parseFloat(   $(this).val()  );
    });
  $('#txt_sum_ccount').text(sum);

 });
}





function adopt2(obj){

   if(confirm('هل تريد إتمام العملية ؟')){
 var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(obj).closest('form');
    $(form).attr('action','{$adopt2_url}');
    ajax_insert_update(form,function(data){
   if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            search();
        }else{
            danger_msg('تحذير..',data);
        }
    },"html");

}
}

function adopt1(obj){
   if(confirm('هل تريد إتمام العملية ؟')){
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(obj).closest('form');

    $(form).attr('action','{$adopt1_url}');
    ajax_insert_update(form,function(data){
   if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            search();
        }else{ // search();
            danger_msg('تحذير..',data);
 //setTimeout(function(){}, 1000);

        }
    },"html");

}
}

$('.balance').change(function(){
//alert('a');
    var a=$(this).closest('tr').find('.balance');
    var sum=0;
    a.each(function(i,j){
 sum=sum+parseFloat($(this).val());
    });
$('#txt_sum_ccount').text(sum);

});


</script>

SCRIPT;

sec_scripts($scripts);
?>