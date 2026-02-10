<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 03/02/2019
 * Time: 05:39 م
 */
$MODULE_NAME= "pledges_t";
$TB_NAME="pledges_cont";
$date_attr= "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$url_get_q = base_url("$MODULE_NAME/$TB_NAME/get_index");
$create_url_get = base_url("$MODULE_NAME/$TB_NAME/index");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
$post_url = base_url("$MODULE_NAME/$TB_NAME/statusupdate");
?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  href="<?= $create_url ?>"><i class=" glyphicon glyphicon-plus"></i>جديد</a> </li>
            <li><a  href="<?= $create_url_get ?>"><i class=" glyphicon glyphicon-plus"></i> استعلام عن عهدة اضافية</a> </li>
            <li><a  href="<?= $url_get_q ?>"><i class=" glyphicon glyphicon-plus"></i> استعلام عن عهدة موجودة</a> </li>
            <li><a  onclick="" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-3">
                        <label class="control-label">حالة العهدة</label>
                        <div>
                            <select name="pledges_exist" id="dp_pledges_exist" class="form-control"">
                            <option value=""></option>
                            <?php foreach($astatus as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php
$scripts = <<<SCRIPT
<script>

    $(document).ready(function() {
       
        $('#dp_emp_id').select2();
              var i=1;  
              
      $('#add').click(function(){  
           i++;  
           $('#morefield').append('<tr id="row'+i+'"><td style="padding-right: 10px;">' +
            '<input type="text" name="class_id[]"  class="form-control " placeholder="رقم الصنف" />' +
             '</td>' +
            '<td style="padding-right: 10px;"><input type="text" name="class_name[]"  class="form-control" placeholder="اسم الصنف" /></td>' +
             '<td style="padding-right: 10px;"><input type="text" name="barcode[]"  class="form-control" placeholder="الباركود" /></td>' +
             '<td style="padding-right: 10px;"><input type="text" name="v_note[]" class="form-control" placeholder="ملاحظات"/></td>'+
             '<td style="padding-right: 10px;"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove"><span class="glyphicon glyphicon-trash"></span></button></td>' + '</tr>');  
            '<br>'
      });
      
        $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });

        //$('#dp_branch').select2();
    });
    
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                      danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });
    
    
  
 
    
</script>
SCRIPT;
sec_scripts($scripts);
?>