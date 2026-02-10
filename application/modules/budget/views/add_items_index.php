<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/09/15
 * Time: 09:54 ص
 */

$get_classes_url =base_url('budget/add_items/get_classes');
$create_url =base_url('budget/add_items/create');
$parents_url=base_url("stores/classes/public_get_parents_classes");
echo AntiForgeryToken();

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?> </div>
        <ul>
            <?php if( HaveAccess($create_url)): ?>  <li><a  onclick="javascript:create_items();" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>حفظ</a> </li><?php endif;?>
        </ul>
    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div class="form-horizontal" >
            <div class="modal-body">

                <div class="form-group col-sm-3">
                    <label class="control-label">الصنف الجد</label>
                    <div>
                        <select name="grand_id" style="width: 250px" id="dp_grand_id" >
                            <option></option>
                            <?php foreach($grands as $row) :?>
                                <option value="<?= $row['PARENT_ID'] ?>"><?= $row['PARENT_ID'].":".$row['CLASS_NAME_AR'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">الصنف الأب</label>
                    <div>
                        <select name="parent_id" style="width: 250px" id="dp_parent_id" >
                            <option></option>
                            <?php foreach($class_parent_id as $row) :?>
                                <option value="<?= $row['PARENT_ID'] ?>"><?= $row['PARENT_ID'].":".$row['CLASS_NAME_AR'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label"> الفصل</label>
                    <div>
                        <select style="width: 250px" id="dp_section_no">
                            <option></option>
                            <?php foreach($sections as $row) :?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <div id="container">

        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('#dp_section_no').select2().on('change',function(){
        get_data('$get_classes_url',{section_no:$('#dp_section_no').val(), grand_id:$('#dp_grand_id').val(), parent_id:$('#dp_parent_id').val()} ,function(data){
            $('#container').html(data);
        },"html");
    });

    $('#dp_grand_id').select2().on('change',function(){
        $('#dp_parent_id').text('');
        get_data('$parents_url', {grand_id:$('#dp_grand_id').val()}, function(ret){
            $('#dp_parent_id').html(ret).select2('val','');
        }, 'html');
    });

    $('#dp_parent_id').select2();

    function create_items(){
        var val = [];
        $('#page_tb .checkboxes:checked:not(:disabled)').each(function (i) {
            val[i] = $(this).val();
        });

        if(val.length<=0)
            return 0;

        get_data('$create_url',{classes:val, section_no:$('#dp_section_no').val()} ,function(data){
            if(parseInt(data)>=1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                $('#page_tb .checkboxes:checked').prop('disabled',1);
            }else{
                danger_msg('تحذير..',data);
            }
        },"html");
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
