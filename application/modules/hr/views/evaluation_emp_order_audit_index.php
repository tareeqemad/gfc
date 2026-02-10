<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 17/07/16
 * Time: 11:14 ص
 */
$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_employee_order';
$TB_NAME1= 'evaluation_emp_order';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_url = base_url("$MODULE_NAME/$TB_NAME1/get");

?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form">
            <div class="modal-body inline_form">
                 <div class="form-group col-sm-1">
                    <label class="control-label">مسلسل التقييم</label>
                    <div>
                        <input type="text"  name="EVALUATION_ORDER_SERIAL" id="TXT_EVALUATION_ORDER_SERIAL" class="form-control" tabindex="1" >
                    </div>
                 </div>
                 <div class="form-group col-sm-2">
                    <label class="control-label">نموذج التقييم</label>
                    <div>
                        <select name="EVAL_FORM" id="DP_EVAL_FORM" class="form-control" tabindex="2" >
                        <option value="">----------------</option>
                        <?php foreach($form_row as $row) :?>
                            <option value="<?=$row['EVALUATION_FORM_ID']?>"><?=$row['EVALUATION_FORM_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                 </div>
                 <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="EMP_NO_NAME" id="DP_EMP_NO_NAME" class="form-control" tabindex="2" >
                        <option value="">----------------</option>
                        <?php foreach($employee as $row) :?>
                            <option value="<?=$row['NO']?>"><?=$row['NO'].":".$row['NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                 </div>
                 <div class="form-group col-sm-2">
                    <label class="control-label">مسؤول التقييم</label>
                    <div>
                        <select name="EMP_MANAGER_ID_NAME" id="DP_EMP_MANAGER_ID_NAME" class="form-control" tabindex="3" />
                        <option value="">----------------</option>
                        <?php foreach($manager as $row) :?>
                            <option value="<?=$row['NO']?>"><?=$row['NO'].":".$row['NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                  </div>
                  <div class="form-group col-sm-2">
                    <label class="control-label">أمر التقييم</label>
                    <div>
                        <input type="text"  name="EVALUATION_ORDER_ID" id="TXT_EVALUATION_ORDER_ID" class="form-control" tabindex="4" >
                    </div>
                 </div>
                 <div class="form-group col-sm-2">
                    <label class="control-label">درجة التقييم</label>
                    <div>
                        <select name="EVALUATION_ORDER_MARKS" id="DP_EVALUATION_ORDER_MARKS" class="form-control" tabindex="3" />
                        <option value="">----------------</option>
                        <?php foreach($grad_form as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                  </div>
                  <div class="form-group col-sm-11">
                        <label class="control-label">شروط ومحددات التقييم (سياسة التقييم)</label>
                        <div>
                            <?php echo '<p> ' . nl2br($effective_order) . '</p>'; ?>
                        </div>
                    </div>
            </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="javascript:search();"> إستعلام</button>
            <button type="button" onclick="javascript:$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-warning">اكسل</button>
            <button type="button" class="btn btn-default" onclick="javascript:clear_form();"> تفريغ الحقول</button>
         </div>
         
         <div id="msg_container"></div>
         <div id="container">
            <?=modules::run($get_page_url,$EVALUATION_ORDER_SERIAL, $EMP_NO_NAME, $EMP_MANAGER_ID_NAME, $EVALUATION_ORDER_ID, $EVALUATION_ORDER_MARKS, $EVAL_FORM);?>
         </div>
    </div>
</div>
<?php	
$script =<<<SCRIPT

<script type="text/javascript">
    $(document).ready(function() {
		$('#DP_EMP_NO_NAME, #DP_EMP_MANAGER_ID_NAME, #DP_EVALUATION_ORDER_MARKS , #DP_EVAL_FORM').select2();
    });
	function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }
	function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/audit');
    }
	function search(){
		var EVALUATION_ORDER_SERIAL = $('#TXT_EVALUATION_ORDER_SERIAL').val();
		var EMP_NO_NAME = $('#DP_EMP_NO_NAME').val();
		var EMP_MANAGER_ID_NAME = $('#DP_EMP_MANAGER_ID_NAME').val();
		var EVALUATION_ORDER_ID = $('#TXT_EVALUATION_ORDER_ID').val();
		var EVALUATION_ORDER_MARKS = $('#DP_EVALUATION_ORDER_MARKS').val();
		var EVAL_FORM = $('#DP_EVAL_FORM').val();
		
		
        var values= {EVALUATION_ORDER_SERIAL, EMP_NO_NAME,EMP_MANAGER_ID_NAME, EVALUATION_ORDER_ID, EVALUATION_ORDER_MARKS, EVAL_FORM};
        
		get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }
</script>


SCRIPT;

sec_scripts($script);

?>
 