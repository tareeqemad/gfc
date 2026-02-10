<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 28/09/16
 * Time: 06:32 م
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'gedco_penalty';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page");

echo AntiForgeryToken();
?>
<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><i class="glyphicon glyphicon-check"></i><?= $title ?></div>
        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">أمر التقييم</label>
                    <div>
                        <select name="evaluation_order_id" id="dp_evaluation_order_id" class="form-control" >
                            <option value=""></option>
                            <?php foreach($order_id as $row) :?>
                                <option value="<?= $row['EVALUATION_ORDER_ID'] ?>"><?= $row['EVALUATION_ORDER_ID'].' - '.substr($row['ORDER_START'],-4).' - '?><?=($row['ORDER_TYPE']==1) ? 'سنوي' : 'نصفي' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_id" id="dp_emp_id" class="form-control" >
                            <option value=""></option>
                            <?php foreach($employee as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div> <!-- /.modal-body inline_form -->
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success">استعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>
        
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $evaluation_order_id, $emp_id);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $(document).ready(function() {
		$('#dp_evaluation_order_id, #dp_emp_id').select2();
    });

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function clear_form(){
        $('#dp_evaluation_order_id, #dp_emp_id').select2('val','');
        clearForm($('#{$TB_NAME}_form'));
    }

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/edit');
    }

    function search(){
        var values= {page:1, evaluation_order_id:$('#dp_evaluation_order_id').val(), emp_id:$('#dp_emp_id').val()};
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {evaluation_order_id:$('#dp_evaluation_order_id').val(), emp_id:$('#dp_emp_id').val()};
        ajax_pager_data('#page_tb > tbody',values);
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>


