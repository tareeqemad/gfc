<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 28/07/16
 * Time: 11:10 ص
 */ 
$MODULE_NAME= 'projects';
$TB_NAME= 'project_committee';
///////
$return_url= base_url("$MODULE_NAME/$TB_NAME/get");
$get_class_det_url =base_url('$MODULE_NAME/$TB_NAME/public_get_details');
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
//////
$isCreate =isset($result) && count($result)  > 0 ?false:true;
$rs=$isCreate ?array() : $result[0];
?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                 <div class="form-group col-sm-1">
                    <label class="control-label">رقم اللجنة</label>
                    <div>
                        <input type="text" id="TXT_COMMITTEE_SER" class="form-control" tabindex="1" readonly >
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" name="COMMITTEE_SER" id="H_COMMITTEE_SER">
                        <?php endif; ?>
                    </div>
                 </div>
                 <div class="form-group col-sm-2">
                    <label class="control-label">مسمى اللجنة</label>
                    <div>
                        <input type="text" id="TXT_TITLES" name="TITLES" class="form-control" tabindex="2">
                    </div>
                 </div>
                 <div class="form-group col-sm-2">
                    <label class="control-label">نوع اللجنة</label>
                    <div>
                        <select name="THE_TYPE" id="DP_THE_TYPE" class="form-control" tabindex="3" >
                            <option value="">----------------</option>
                            <option value="1">مقر</option>
                            <option value="2">مركزي</option>
                        </select>
                    </div>
                 </div>
				 <div class="form-group col-sm-2 BRANCH" style="display:none;">
                    <label class="control-label">المقر</label>
                    <div>
                        <select name="BRANCH" id="DP_BRANCH" class="form-control" tabindex="4" >
                            <option value="">----------------</option>
                            <?php foreach($branches as $key => $value): ?>
                            <option value="<?= $value['NO']; ?>"><?= $value['NAME']; ?></option>
                            <?php endforeach ?>
                        </select>
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
            <?= modules::run("$MODULE_NAME/$TB_NAME/$get_class_det_url", (count($rs))?$rs['COMMITTEE_SER']:0 ); ?>
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
		var class_manager_json= new Array('رئيس','عضو');
		var select_manager= '';
		var class_employee_json = {$employee};
		var select_employee= '';
		
		$.each(class_manager_json,function (i,option) {
			select_manager += "<option value='"+i+"' >"+class_manager_json[i]+"</option>";
			$('select[name="THE_TYPEE[]"]').append("<option value='"+i+"' >"+class_manager_json[i]+"</option>");
		})
		$.each(class_employee_json, function(i,item){
			select_employee += "<option value='"+item.NO+"' >"+item.NAME+"</option>";
		});
		$('select[name="EMP_ID[]"]').append(select_employee);
		$('select[name="THE_TYPEE[]"]').each(function(){
			$(this).val($(this).attr('data-val'));
		});
		$('select[name="EMP_ID[]"]').each(function(){
			$(this).val($(this).attr('data-val'));
		});
		$("#TXT_EMP_ID"+count).select2();
		$("#TXT_THE_TYPEE"+count).select2();
		///
        $('#DP_THE_TYPE,#DP_BRANCH, #DP_ENTRY_USER').select2();
		/////////
		reBind();
		$('#DP_THE_TYPE').change(function(){
			var No = $(this).val();
			if(No == 1){
				$('.BRANCH').css("display","block");
			}else{
				$('.BRANCH').css("display","none");
				('#DP_BRANCH').val(0);
			}
		})
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
		});
		/////////
		function addRows(){
			count = count+1;
			var html = '<tr><td><i class="glyphicon glyphicon-sort" /></i></td><td><input type="hidden" name="SER[]" value="0" /><select name="EMP_ID[]" class="form-control" id="TXT_EMP_ID'+count+'" ><option></option></select></td><td><select name="THE_TYPEE[]" class="form-control" id="TXT_THE_TYPEE'+count+'"></select></td></tr>';
			$('#details_tb tbody').append(html);
			reBind(1);
		}
		
		////////
		function reBind(s){
			if(s==undefined){s=0;}
			if(s){
				$('select#TXT_THE_TYPEE'+count).append('<option></option>'+select_manager);
				$('select#TXT_EMP_ID'+count).append('<option></option>'+select_employee);
			}
			$('select[id^="TXT_EMP_ID"]').select2();
			$('select[id^="TXT_THE_TYPEE"]').select2();
		}
	
SCRIPT;
	if($isCreate){
	  $script = <<<SCRIPT
	   {$script}
		   
		$(function() {
			$( "#details_tb tbody" ).sortable();
		});
		function clear_form(){
			clearForm($('#{$TB_NAME}_form'));
		}
</script>
SCRIPT;
}else{
$script = <<<SCRIPT
    {$script}
            
            $('#TXT_COMMITTEE_SER').val('{$rs['COMMITTEE_SER']}');
            $('#H_COMMITTEE_SER').val('{$rs['COMMITTEE_SER']}');
            $('#DP_THE_TYPE').select2('val', '{$rs['THE_TYPE']}');
            $('#DP_BRANCH').select2('val', '{$rs['BRANCH']}');
            $('#TXT_TITLES').val('{$rs['TITLES']}');
            
            $("#entry_date").css("display" ,"block");
			$("#entry_user").css("display" ,"block");
            $('div#entry_date div').text('{$rs['ENRTY_DATE']}');
			$('div#entry_user div').text('{$rs['ENTRY_USER_NAME']}');
            
            if({$rs['THE_TYPE']}==1){
                $('.BRANCH').css("display","block");
            }else{
                $('.BRANCH').css("display","none");
				('#DP_BRANCH').val(0);
            }
            
    </script>
SCRIPT;
}
sec_scripts($script);
?>
