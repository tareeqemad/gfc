<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 15/09/15
 * Time: 01:27 م
 */

$get_page_url =base_url('budget/exp_rev_side_adopt/get_page');
$adopt_url =base_url('budget/exp_rev_side_adopt/adopt');
echo AntiForgeryToken();

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?> </div>
        <ul>
            <?php if( HaveAccess($adopt_url)): ?>  <li><a  onclick="javascript:adopt(1);" href="javascript:;"><i class="glyphicon glyphicon-ok-circle"></i>اعتماد</a> </li><?php endif;?>
            <?php if( HaveAccess($adopt_url)): ?>  <li><a  onclick="javascript:adopt(0);" href="javascript:;"><i class="glyphicon glyphicon-remove-circle"></i>ارجاع</a> </li><?php endif;?>
        </ul>
    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div class="form-vertical inline_form" >
            <div class="modal-body">

                <div class="form-group col-sm-2">
                    <label class="control-label">المقر</label>
                    <div>
                        <select id="dp_branch" class="form-control" >
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label"> الفصل</label>
                    <div>
                        <select id="dp_section_no" class="form-control" >
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

    $('#dp_section_no,#dp_branch').select2().on('change',function(){
        if( $('#dp_section_no').val()=='' || $('#dp_branch').val()=='' ) return 0;
        get_data('$get_page_url',{section_no:$('#dp_section_no').val(), branch:$('#dp_branch').val() } ,function(data){
            $('#container').html(data);
        },"html");
    });

    function adopt(_case){
        if(_case==0 ){
            if( (confirm('هل تريد بالتأكيد ارجاع كافة البنود لهذا الفصل ؟!')) ){
                $('#page_tb .checkboxes').prop('checked',1);
            }else return 0;
        }
        var val = [];
        $('#page_tb .checkboxes:checked:not(:disabled)').each(function (i) {
            val[i] = $(this).val();
        });

        if(val.length<=0)
            return 0;

        get_data('$adopt_url',{items:val, branch:$('#dp_branch').val(),adopt:_case} ,function(data){
            if(parseInt(data)>=1){
                success_msg('رسالة','تمت العملية بنجاح ..');
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
