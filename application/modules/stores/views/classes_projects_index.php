<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 17/09/15
 * Time: 10:36 ص
 */
$MODULE_NAME= 'stores';
$TB_NAME= 'classes';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_project_classes");
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


    <br>
    <div id="container">
        <?=modules::run($get_url, array('text'=>$text, 'id'=>$id, 'name_ar'=>$name_ar, 'name_en'=>$name_en, 'parent_id'=>$parent_id, 'grand_id'=>$grand_id, 'page'=>$page)); ?>
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

        get_data('{$get_url}',{id:id, name_ar:name_ar, name_en:name_en, parent_id:parent_id,grand_id:grand_id,text:text,page:'{$page}'},function(data){
      $('#container').html(data);

        },'html');
    }

    function {$TB_NAME}_clear(){
        $('#{$TB_NAME}_form :input').val('');
    }





</script>
SCRIPT;
sec_scripts($scripts);

?>
