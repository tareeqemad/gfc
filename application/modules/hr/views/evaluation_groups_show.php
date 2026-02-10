<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 27/06/16
 * Time: 11:51 ص
 */ 
$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_groups';
///////
$return_url= base_url("$MODULE_NAME/$TB_NAME/get");
$get_class_det_url =base_url('$MODULE_NAME/$TB_NAME/public_get_details');
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
//////
$isCreate =isset($result) && count($result)  > 0 ?false:true;
$rs=$isCreate ?array() : $result[0];
?>
<title></title>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>
    <div class="form-body">
          <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
               <div class="modal-body inline_form">
                     
                     <div class="form-group col-sm-1">
                        <label class="control-label">رقم اللجنة</label>
                        <div>
                            <input type="text" id="TXT_EGROUPS_SERIAL" class="form-control" readonly tabindex="0">
                            <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" name="EGROUPS_SERIAL" id="h_EGROUPS_SERIAL">
                            <?php endif; ?>
                        </div>
                     </div>
                     <div class="form-group col-sm-1">
                        <label class="control-label">أمر التقييم</label>
                        <div>
                            <input type="text" readonly id="TXT_EVALUATION_ORDER_ID" name="EVALUATION_ORDER_ID" class="form-control" tabindex="1">
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
                                    id="TXT_EVALUATION_ORDER_DATE" class="form-control " tabindex="4">
                                    <span class="field-validation-valid" data-valmsg-for="class_input_date" data-valmsg-replace="true"></span>
                             </div>
                          </div>
                        <div style="clear: both"></div>
                        <div class="form-group col-sm-3" id="entry_date" style="display: none">
                            <label class="control-label">تاريخ الادخال</label>
                            <div></div>
                        </div>
        
                        <div class="form-group col-sm-3" id="entry_user" style="display: none">
                            <label class="control-label">اسم المدخل</label>
                            <div></div>
                        </div>
               </div>
               <div id="msg_container"></div>
               <div id="container">
					<?= modules::run("$MODULE_NAME/$TB_NAME/$get_class_det_url", (count($rs))?$rs['EGROUPS_SERIAL']:0 ); ?>
               </div>
               <div class="modal-footer">
                   <?php if (  HaveAccess($post_url) && ($isCreate || (isset($can_edit)?$can_edit:false) )  ){ ?>
                   <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                   <?php
				   }
				   ?>
                   <?php if(!$can_edit and $isCreate){ ?>
                   <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                    <?php
				   }
				   ?>
               </div>
          </form>
    </div>
</div>
<?php	
$script =<<<SCRIPT
<script>
        var count = 0;
		var countGroup = new Array();
		var class_manager_json= new Array('نعم','لا');
		var select_manager= '';
		var class_employee_json = {$employee};
		var select_employee= '';
		var class_employ_json = {$employ};
		var select_employ= '';
		var class_management_json = {$gcc_structure};
		var select_management= '';
		
		$.each(class_manager_json,function (i,option) {
			select_manager += "<option value='"+i+"' >"+class_manager_json[i]+"</option>";
			$('select[name="MANAGER[]"]').append("<option value='"+i+"' >"+class_manager_json[i]+"</option>");
		})
		$.each(class_employee_json, function(i,item){
			select_employee += "<option value='"+item.NO+"' >"+item.NAME+"</option>";
		});
		$.each(class_employ_json, function(i,item){
			select_employ += "<option value='"+item.NO+"' >"+item.NAME+"</option>";
		});
		$.each(class_management_json, function(i,item){
			select_management += "<option value='"+item.ST_ID+"' >"+item.ST_NAME+"</option>";
		});
		$('select[name="EMP_ID[]"]').append(select_employee);
		$('select[name="EMP_MANAGER_ID[]"]').append(select_employ);
		$('select[name="MANAGEMENT_NO[]"]').append(select_management);
		
			
		$('select[name="MANAGER[]"]').each(function(){
			$(this).val($(this).attr('data-val'));
		});
		$('select[name="EMP_ID[]"]').each(function(){
			$(this).val($(this).attr('data-val'));
		});
		$('select[name="EMP_MANAGER_ID[]"]').each(function(){
			$(this).val($(this).attr('data-val'));
		});
		$('select[name="MANAGEMENT_NO[]"]').each(function(){
			$(this).val($(this).attr('data-val'));
		});

        $('#DP_EVALUATION_ORDER_ID, #DP_EVALUATION_GROUPS_TYPE, #DP_ENTRY_USER ').select2();
		$("#TXT_MANAGER"+count).select2();
		$("#TXT_EMP_ID"+count).select2();
		$("#TXT_EMP_MANAGER_ID"+count).select2();
		$("#TXT_MANAGEMENT_NO"+count).select2();
	    reBind();
        /////////
        $('button[data-action="submit"]').click(function(e){
			e.preventDefault();
			if(confirm('هل تريد حفظ اللجنة ؟!')){
				var form = $(this).closest('form');
				countGroup.length = 0;
				$('select[name="EMP_ID[]"]').each(function(i){
					var w =$(this).val();
					if(!isNaN(parseInt(w))) {
						countGroup.push(parseInt(w));
					}
				})
				if(countGroup.length >=3){
					ajax_insert_update(form,function(data){
						if(parseInt(data)>1){
							success_msg('رسالة','تم حفظ البيانات بنجاح ..');
							get_to_link('{$return_url}/'+parseInt(data)+'/edit');
						}else if(data==1){
							success_msg('رسالة','تم حفظ البيانات بنجاح ..');
							get_to_link(window.location.href);
						}else{
							danger_msg('تحذير..',data);
						}
					},'html');
				}else{
				   	danger_msg('يجب أن تكون اللجنة المدخلة مكونة من 3 أشخاص على الأقل');
				}
								
			}
			setTimeout(function() {
				$('button[data-action="submit"]').removeAttr('disabled');
			}, 3000);
		});
		/////////
		function addRowsEmp(){
			count = count+1;
			var html = '<tr><td><i class="glyphicon glyphicon-sort" /></i></td><td><input type="hidden" name="SER[]" value="0" /><select name="EMP_ID[]" class="form-control" id="TXT_EMP_ID'+count+'"><option></option></select></td><td><select name="MANAGER[]" class="form-control" id="TXT_MANAGER'+count+'"></select></td><td><input name="EMP_NOTE[]" data-val="true" data-val-required="required" class="form-control" id="TXT_EMP_NOTE'+count+'" /></td></tr>';
			$('#details_tb tbody').append(html);
			 reBind(1);
		}
		/////////
		function addRowsTree(){
			count = count+1;
			var html1 = '<tr><td><i class="glyphicon glyphicon-sort" /></i></td><td><input type="hidden" name="SER1[]" value="0" /><select name="EMP_MANAGER_ID[]" class="form-control" id="TXT_EMP_MANAGER_ID'+count+'"><option></option></select></td><td><select name="MANAGEMENT_NO[]" class="form-control" id="TXT_MANAGEMENT_NO'+count+'"><option></option></select></td><td><input name="TREE_NOTE[]" data-val="true" data-val-required="required" class="form-control" id="TXT_TREE_NOTE'+count+'" /></td></tr>';
			$('#details1_tb tbody').append(html1);
			reBind(1);
		}
		function reBind(s){
			if(s==undefined){s=0;}
			if(s){
				$('select#TXT_MANAGER'+count).append('<option></option>'+select_manager);
				$('select#TXT_EMP_ID'+count).append('<option></option>'+select_employee);
				$('select#TXT_EMP_MANAGER_ID'+count).append('<option></option>'+select_employ);
				$('select#TXT_MANAGEMENT_NO'+count).append('<option></option>'+select_management);
			}
			$('select[id^="TXT_EMP_ID"]').select2();
			$('select[id^="TXT_EMP_MANAGER_ID"]').select2();
			$('select[id^="TXT_MANAGEMENT_NO"]').select2();
			$('select[id^="TXT_MANAGER"]').select2();
		}
		
	
SCRIPT;
	if($isCreate){
	  $script = <<<SCRIPT
	   {$script}
		   
		$(function() {
			$( "#details_tb tbody" ).sortable();
			$( "#details1_tb tbody" ).sortable();
		});
		function clear_form(){
			clearForm($('#{$TB_NAME}_form'));
		}
</script>
SCRIPT;
}else{
$script = <<<SCRIPT
    {$script}
            $('#TXT_EGROUPS_SERIAL').val('{$rs['EGROUPS_SERIAL']}');
            $('#h_EGROUPS_SERIAL').val('{$rs['EGROUPS_SERIAL']}');
            $('#TXT_EVALUATION_ORDER_ID').val('{$rs['EVALUATION_ORDER_ID']}');
            $('#DP_EVALUATION_GROUPS_TYPE').select2('val', '{$rs['EVALUATION_GROUPS_TYPE']}');
            $('#TXT_EMP_MANAGER_ID_S').val('{$rs['EMP_MANAGER_ID']}');
            $('#TXT_EVALUATION_ORDER_DATE').val('{$rs['EVALUATION_ORDER_DATE']}');
            
            $("#entry_date").css("display" ,"block");
			$("#entry_user").css("display" ,"block");
            $('div#entry_date div').text('{$rs['ENTRY_DATE']}');
			$('div#entry_user div').text('{$rs['ENTRY_USER_NAME']}'); 
            
    </script>
SCRIPT;
}
sec_scripts($script);
?>
