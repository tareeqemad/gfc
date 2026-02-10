<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 27/06/16
 * Time: 11:50 ص
 */ 
$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_groups';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form">
            <div class="modal-body inline_form">
                 <div class="form-group col-sm-1">
                    <label class="control-label">رقم اللجنة</label>
                    <div>
                        <input type="text"  name="EGROUPS_SERIAL" id="TXT_EGROUPS_SERIAL" class="form-control" tabindex="0" >
                    </div>
                 </div>
                 <div class="form-group col-sm-2">
                    <label class="control-label">أمر التقييم</label>
                    <div>
                        <input type="text" id="DP_EVALUATION_ORDER_ID" name="EVALUATION_ORDER_ID" class="form-control" tabindex="1">
                    </div>
                 </div>
                 <div class="form-group col-sm-2">
                    <label class="control-label">نوع اللجنة</label>
                    <div>
                        <select name="EVALUATION_GROUPS_TYPE" id="DP_EVALUATION_GROUPS_TYPE" class="form-control" tabindex="2" >
                        <option value="">----------------</option>
                        <?php foreach($eval as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                 </div>
                  <div class="form-group col-sm-3">
                    <label class="control-label">بيان تشكيل اللجنة </label>
                    <div>
                        <input type="text" name="EMP_MANAGER_ID_S" id="TXT_EMP_MANAGER_ID_S" class="form-control" tabindex="3">
                    </div>
                  </div>
                  <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ تشكيل اللجنة</label>
                    <div>
                            <input type="text" name="EVALUATION_ORDER_DATE"  data-type="date"  data-date-format="DD/MM/YYYY"  
                            data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  
                            id="TXT_EVALUATION_ORDER_DATE" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="class_input_date" data-valmsg-replace="true"></span>
                     </div>
                  </div>
                  <div class="form-group col-sm-2">
                    <label class="control-label">المدخل</label>
                    <div>
                        <select name="ENTRY_USER" id="DP_ENTRY_USER" class="form-control" tabindex="5" />
                        <option></option>
                        <?php foreach($entry_user_all as $row) :?>
                            <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                  </div>
            </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="javascript:search();"> إستعلام</button>
            <button type="button" class="btn btn-default" onclick="javascript:clear_form();"> تفريغ الحقول</button>
         </div>
         <div id="msg_container"></div>
         <div id="container">
            <?=modules::run($get_page_url, $page, $EVALUATION_ORDER_ID, $EGROUPS_SERIAL, $EVALUATION_GROUPS_TYPE, $EMP_MANAGER_ID_S, $EVALUATION_ORDER_DATE, $ENTRY_USER );?>
         </div>
    </div>
</div>
<?php	
$script =<<<SCRIPT

<script type="text/javascript">
    $(document).ready(function() {
		$('#DP_EVALUATION_GROUPS_TYPE, #DP_ENTRY_USER').select2();
    });
	function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/edit');
    }
	function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }
	function search(){
		var EVALUATION_ORDER_ID = $('#DP_EVALUATION_ORDER_ID').val();
		var EGROUPS_SERIAL = $('#TXT_EGROUPS_SERIAL').val();
		var EVALUATION_GROUPS_TYPE = $('#DP_EVALUATION_GROUPS_TYPE').val();
		var EMP_MANAGER_ID = $('#TXT_EMP_MANAGER_ID').val()
		var EVALUATION_ORDER_DATE = $('#TXT_EVALUATION_ORDER_DATE').val();
		var ENTRY_USER = $('#DP_ENTRY_USER').val();
		
		
        var values= {page:1, EGROUPS_SERIAL, EVALUATION_GROUPS_TYPE,EMP_MANAGER_ID, EVALUATION_ORDER_DATE,ENTRY_USER};
        
		get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }
	function LoadingData(){
        var EVALUATION_ORDER_ID = $('#DP_EVALUATION_ORDER_ID').val();
		var EGROUPS_SERIAL = $('#TXT_EGROUPS_SERIAL').val();
		var EVALUATION_GROUPS_TYPE = $('#DP_EVALUATION_GROUPS_TYPE').val();
		var EMP_MANAGER_ID = $('#TXT_EMP_MANAGER_ID').val()
		var EVALUATION_ORDER_DATE = $('#TXT_EVALUATION_ORDER_DATE').val();
		var ENTRY_USER = $('#DP_ENTRY_USER').val();


        var values= {page:1, EVALUATION_ORDER_ID, EGROUPS_SERIAL, EVALUATION_GROUPS_TYPE,EMP_MANAGER_ID, EVALUATION_ORDER_DATE,ENTRY_USER};
        ajax_pager_data('#page_tb > tbody',values);
    }
</script>


SCRIPT;

sec_scripts($script);

?>
