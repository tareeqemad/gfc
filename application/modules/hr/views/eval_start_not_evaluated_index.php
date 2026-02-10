<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 07/08/16
 * Time: 10:16 ص
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_emp_order';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_not_evaluate_page");
$insert_url = base_url("$MODULE_NAME/$TB_NAME/insert_not_evaluate");

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if( HaveAccess($insert_url) ): ?><li><a  onclick="javascript:insert_not_evaluate();" href="javascript:;"><i class="glyphicon glyphicon-cog"></i> تقييم من لم يتم تقييمهم </a> </li> <?php endif;?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>

<div class="form-body">
    <form class="form-vertical" id="<?=$TB_NAME?>_form" >
        <div class="modal-body inline_form">

            <div class="form-group col-sm-2">
                <label class="control-label">الموظف</label>
                <div>

                    <select name="emp_no" id="dp_emp_no" class="form-control" tabindex="2" >
                        <option value="">----------------</option>
                        <?php foreach($employee as $row) :?>
                            <option value="<?=$row['NO']?>"><?=$row['NO'].":".$row['NAME']?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>

        </div>
    </form>

    <div class="modal-footer">
        <button type="button" onclick="javascript:search();" class="btn btn-success">استعلام</button>
        <button type="button" onclick="javascript:$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-warning">اكسل</button>
        <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
    </div>

    <div id="msg_container"></div>
    <div id="container">
        <?=modules::run($get_page_url);?>
    </div>

</div>
</div>

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">
    $(document).ready(function() {
		$('#dp_emp_no').select2();
        $('#page_tb').dataTable({
            "lengthMenu": [ [50,100,200,300, -1], [50,100,200,300, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

	function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

	function search(){
		var emp_no = $('#dp_emp_no').val();
        var values= {emp_no};
		get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function insert_not_evaluate(){
        if(confirm('هل تريد بالتأكيد تنفيذ العملية؟!! لا يمكن التراجع عن العملية,قد تستغرق العملية عدة دقائق..')){
            get_data('{$insert_url}', {}, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح ...');
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>

