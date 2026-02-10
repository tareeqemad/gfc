<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/04/21
 * Time: 06:45 ص
 */
$MODULE_NAME= 'issues';
$TB_NAME= 'checks';
$get_url =base_url("$MODULE_NAME/$TB_NAME/public_get_subscribers");
$get_sub_info_url =base_url("$MODULE_NAME/$TB_NAME/public_sub_info");
?>

<div class="form-body"  >

    <div class="modal-body inline_form" >

       <form  id="<?=$TB_NAME?>_form" method="get" action="<?=$get_url?>" role="form" novalidate="novalidate">

            <div class="form-group col-sm-3">
                <label class="control-label">رقم الهوية</label>
                <div>
                
                    <input type="text"  name="id" id="txt_id" placeholder="رقم الهوية" value="<?= $id ?>" class="form-control">
                
                </div>
            </div>

            <div class="form-group col-sm-6">
                <label class="control-label">اسم المشترك</label>
                <div>
                    <input type="text"  name="name"  placeholder="اسم المشترك" value="<?= $name ?>"  id="txt_name" class="form-control">
                </div>
            </div>

        <div style="width:150px; padding-top: 25px; float: left;">
            <button type="button" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success">بحث</button>
            <button type="button" onclick="javascript:<?=$TB_NAME?>_clear();" class="btn btn-default">تفريغ </button>
        </div>

 </form>
    </div>
    <!-- <div>
        <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i class="icon icon-download"></i> إدراج المختار </a>
    </div> -->
    <div id="container">
      <?=modules::run("$MODULE_NAME/$TB_NAME/public_get_subscribers", array('id'=>$id, 'name'=>$name,'page'=>$page)); ?>
    </div>
  <!--  <div>
        <a class="btn-xs btn-danger" onclick="javascript:select_choose();"  href="javascript:;"><i class="icon icon-download"></i> إدراج المختار </a>
    </div> -->
</div>

</div>
       

<?php
$scripts = <<<SCRIPT
<script>
    function {$TB_NAME}_search(){
        var id= $('#txt_id').val();
        var name= $('#txt_name').val();
        if(id==null || id=='')
            id= -1;
        if(name == null || name == '')
            name = -1;
  get_data('{$get_url}',{id:id, name:name},function(data){
          $('#container').html(data);
        },'html');
        
    }
function get_info_sub(no){
get_data('{$get_sub_info_url}',{id:no},function(data){
 //console.log(data[0]);
 var item = data[0];
 if (data.length == 1){
   parent.$("#txt_sub_name").val(item.NAME);
  parent.$("#txt_for_month").val(item.FOR_MONTH);
   parent.$("#txt_net_pay").val(item.NET_TO_PAY);
   parent.$("#txt_type_name").val(item.TYPE_NAME);
   parent.$("#txt_id").val(item.ID);
   parent.$("#txt_address").val(item.ADDRESS);
   parent.$("#txt_type_pa").val(item.TYPE);
 }
 else
 {
 danger_msg('رقم الاشتراك غير صحيح');
  parent.$("#txt_sub_name").val('');
    parent.$("#txt_for_month").val('');
    parent.$("#txt_net_pay").val('');
   parent.$("#txt_type_name").val('');
   parent.$("#txt_id").val('');
   parent.$("#txt_address").val('');
   parent.$("#txt_type_pa").val('');
 }

            });


        }
 function select_class(no){
        parent.$('#txt_sub_no').val(no);
        get_info_sub(no);
        parent.$('#report').modal('hide');
   if (typeof parent.do_after_select == 'function') {
        parent.do_after_select();
   
} 
          

    }

 function select_choose(){

        $('.checkboxes:checked').each(function(i){
            var obj = jQuery.parseJSON($(this).attr('data-val'));
             console.log(obj);
          if(i == 0){
                select_class(obj.NO); 
  
            }
    
        });

    }
</script>
SCRIPT;
sec_scripts($scripts);

?>