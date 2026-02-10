<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 29/01/2019
 * Time: 12:00 م
 */
$date_attr= "data-type='date' data-date-format='DD/MM/YYYY' data-val='true'
                  data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
$MODULE_NAME= 'pledges_t';
$TB_NAME="pledges_cont";
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url_get = base_url("$MODULE_NAME/$TB_NAME/index");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");


?>
<style>
    table td {
        padding: 0px 5px;
    }

</style>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a  href="<?= $create_url ?>"><i class=" glyphicon glyphicon-plus"></i>جديد</a> </li>
            <li><a  href="<?= $create_url_get ?>"><i class="glyphicon glyphicon-zoom-in"></i>استعلام</a> </li>
        </ul>
    </div>



    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-8">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب">
                            <option value=""></option>
                            <?php foreach($employee as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
<br>

           <div class="form-group">
                <div class="form-group  col-sm-2">
                    <label class="control-label">رقم الصنف
                    </label>
                    <div>
                        <input name="class_id[]" type="text" value=""
                               id="txt_item_no" class="form-control" data-val="true"  data-val-required="حقل مطلوب">
                    </div>
                </div>

                <div class="form-group  col-sm-2">
                    <label class="control-label">الصنف
                    </label>
                    <div>
                        <input name="class_name[]" type="text" value=""
                               id="txt_item_name" class="form-control" data-val="true"  data-val-required="حقل مطلوب">
                    </div>
            </div>

                <div class="form-group  col-sm-2">
                    <label class="control-label">الباركود
                    </label>
                    <div>
                        <input name="barcode[]" type="text" value=""
                               id="txt_barc_no" class="form-control" data-val="true"  data-val-required="حقل مطلوب">
                    </div>
                </div>

                <div class="form-group  col-sm-2">
                    <label class="control-label">ملاحظات
                    </label>
                    <div>
                        <input name="v_note[]" type="text" value=""
                               id="txt_v_note" class="form-control">
                    </div>
                </div>



                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الجرد</label>
                    <div>
                        <input type="text"  <?=$date_attr?>   name="pp_date" id="txt_pp_date" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">اضافة حقل</label>
                    <div>
                        <button type="button" name="add" id="add" class="btn btn-success">+</button>
                    </div>
                </div>
              <div id="morefield" class="form-group col-sm-8 col-md-8">
                  <div class ="row">

                  </div>
              </div>

            </div>

            </div>

                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>

                </div>
        </form>
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
    
    
    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
         $('#btnReset').click(function() {
    $("#dp_emp_id").val(null).trigger("change"); 
});         
    }
 
    
</script>
SCRIPT;
sec_scripts($scripts);
?>