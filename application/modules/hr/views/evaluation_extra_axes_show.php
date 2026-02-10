<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 13/06/16
 * Time: 11:15 ص
 */ 
$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_extra_axes';
///////
$return_url= base_url("$MODULE_NAME/$TB_NAME/get");
$get_class_det_url =base_url('$MODULE_NAME/$TB_NAME/public_get_details');
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$change_url = base_url("$MODULE_NAME/$TB_NAME/change_status");
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
                        <label class="control-label">رقم التقييم</label>
                        <div>
                            <input type="text" id="txt_eextra_id" class="form-control" readonly tabindex="0">
                            <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" name="eextra_id" id="h_eextra_id">
                            <?php endif; ?>
                        </div>
                     </div>
                     <div class="form-group col-sm-2">
                        <label class="control-label">نوع التقييم</label>
                        <div>
                            <select  name="eextra_form_id" id="dp_eextra_form_id" class="form-control" tabindex="1" 
							<?php if($rs['EEXTRA_FORM_ID'] !=0){ ?> disabled <?php } ?> >
                            <option value="">-----------------</option>
                            <?php  foreach($extra_form as $rows) :?>
                                <option value="<?=$rows['CON_NO']?>"><?=$rows['CON_NAME']?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                     </div>
                     <div class="form-group col-sm-2 EExtraName" style="display:none;">
                        <label class="control-label">المسمى الإشرافي</label>
                        <div>
                            <select name="eextra_name" id="dp_eextra_name" class="form-control" tabindex="2" 
							<?php if($rs['SUPERVISION'] !=0){ ?> disabled  <?php } ?> >
                            <option value="">-----------------</option>
                            <?php foreach($extra_name as $rowd) :?>
                                <option value="<?=$rowd['CON_NO']?>"><?=$rowd['CON_NAME']?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                     </div>
                     <div class="form-group col-sm-3">
                        <label class="control-label"> بيان محور التقييم </label>
                        <div>
                            <input type="text" name="note" id="txt_note" class="form-control" tabindex="3">
                        </div>
                      </div>
                      <div class="form-group col-sm-2 EExtraSuper" style="display:none;" >
                        <label class="control-label">الوزن النسبي </label>
                        <div>
                            <input type="text" name="eextra_relative_weight" id="txt_eextra_relative_weight" class="form-control" tabindex="4">
                        </div>
                      </div>
                      <div class="form-group col-sm-2">
                        <label class="control-label">الوزن النسبي الإشرافي </label>
                        <div>
                            <input type="text" name="eextra_supervision_weight" id="txt_eextra_supervision_weight" class="form-control" tabindex="5">
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
					<?= modules::run("$MODULE_NAME/$TB_NAME/$get_class_det_url", (count($rs))?$rs['EEXTRA_ID']:0 ); ?>
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
                   <?php if($can_edit and !$isCreate){ ?>
                   <button type="button" onclick='javascript:change(0);' class="btn btn-danger">حذف</button>
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
        $('#dp_eextra_form_id, #dp_eextra_name, #dp_entry_user').select2();
		/////////
		$('#dp_eextra_form_id').change(function(){
		   var No = $(this).val();
		   if(No==1){
			   $('.EExtraName').css("display","block");
			   $('.EExtraSuper').css("display","none");
			   $('#txt_eextra_relative_weight').val(0);
		   }else if(No==3){
                $('.EExtraName').css("display","none");
				$('.EExtraSuper').css("display","block");
				$('#dp_eextra_name').val(0);
		   }else{
			   $('.EExtraName').css("display","none");
			   $('.EExtraSuper').css("display","none");
				$('#dp_eextra_name').val(0);
				$('#txt_eextra_relative_weight').val(0);
		   }
		})
        /////////
        $('button[data-action="submit"]').click(function(e){
			e.preventDefault();
			if(confirm('هل تريد حفظ التقييم ؟!')){
				var form = $(this).closest('form');
				var total = 0 ;
				$('input[name="element_weight[]"]').each(function(i){
					var w =$(this).val();
					if(!isNaN(parseInt(w))) {
					  total = parseInt(total)+ parseInt(w);
					}
				})
				if(total == 100){
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
				}else if(total < 100){
					danger_msg('مجموع الوزن النسبي لا يساوي 100 عليك التأكد من الإدخال');
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
					danger_msg('مجموع الوزن النسبي أكبر من 100 عليك التأكد من الإدخال');
				}
				
			}
			setTimeout(function() {
				$('button[data-action="submit"]').removeAttr('disabled');
			}, 3000);
		});
		/////////
		function addRows(){
			count = count+1;
			var html = '<tr><td><i class="glyphicon glyphicon-sort" /></i></td><td><input name="element_order[]"  data-val="true" data-val-required="required" class="form-control"  id="txt_element_order'+count+'" /></td><td><input name="element_name[]"  data-val="true" data-val-required="required" class="form-control"  id="txt_element_name'+count+'" /></td><td><input name="element_weight[]" data-val="true" data-val-required="required" class="form-control" id="txt_element_weight'+count+'" /></td></tr>';
			$('#details_tb tbody').append(html);
		}
		/////////
		function addRowse(){
			count = count+1;
			var html = '<tr><td class="mah"><input type="checkbox" name="c[]" id="c" /></td><td><i class="glyphicon glyphicon-sort" /></i></td><td><input name="element_order[]"  data-val="true" data-val-required="required" class="form-control"  id="txt_element_order'+count+'" /><input  type="hidden" name="elem_id[]" value="0" id="h_txt_elem_id'+count+'" ></td><td><input name="element_name[]"  data-val="true" data-val-required="required" class="form-control"  id="txt_element_name'+count+'" /></td><td><input name="element_weight[]" data-val="true" data-val-required="required" class="form-control" id="txt_element_weight'+count+'" /></td></tr>';
			$('#details_tb tbody').append(html);
		}
		////////
	
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

            //$('#dp_eextra_form_id, #dp_eextra_name').select2('readonly',true);
            
            $('#txt_eextra_id').val('{$rs['EEXTRA_ID']}');
            $('#h_eextra_id').val('{$rs['EEXTRA_ID']}');
            $('#dp_eextra_form_id').select2('val', '{$rs['EEXTRA_FORM_ID']}');
            $('#dp_eextra_name').select2('val', '{$rs['SUPERVISION']}');
            $('#txt_note').val('{$rs['NOTE']}');
            $('#txt_eextra_relative_weight').val('{$rs['RELATIVE_WEIGHT']}');
            $('#txt_eextra_supervision_weight').val('{$rs['SUPERVISION_WEIGHT']}');
            
            $("#entry_date").css("display" ,"block");
			$("#entry_user").css("display" ,"block");
            $('div#entry_date div').text('{$rs['ENTRY_DATE']}');
			$('div#entry_user div').text('{$rs['ENTRY_USER_NAME']}');
            
            $('input#txt_note').attr("readonly",true);
            $('input#txt_eextra_relative_weight').attr("readonly",true);
            $('input#txt_eextra_supervision_weight').attr("readonly",true);
            
            if({$rs['EEXTRA_FORM_ID']}==1){
                $('.EExtraName').css("display","block");
			    $('.EExtraSuper').css("display","none");
            }else if({$rs['EEXTRA_FORM_ID']}==3){
                $('.EExtraName').css("display","none");
				$('.EExtraSuper').css("display","block");
            }else{
                $('.EExtraName').css("display","none");
			    $('.EExtraSuper').css("display","none");
            }
            
            $(".mah").css("display" ,"block");
            
               checked=false;
               $("#All").click(function(){ 
                toStatus = $(this).prop("checked");
                    $("input[name='c[]']").each(function(index, element) {
                        $(this).prop("checked",toStatus);	
                    });
               })
               function change(no){
                    var msg= '';
                    if(no==1){
                        msg= 'هل تريد تفعيل عناصر التقييم المختارة ؟!';
                        if(confirm(msg)){
                            $.ajax({ 
                               type : "post",
                               data : $('#{$TB_NAME}_form').serializeArray(),
                               url : '{$change_url}/'+no, 
                            }).success(function(data){ 
                                 success_msg('رسالة','تم تفعيل عناصر التقييم المختارة ..');
                            })
                        }
                     }else{
                         if($('#c').length == $('#c').filter(":checked").length){
                                danger_msg('عليك ترك سؤال واحد ع الأقل..');
                          }else{
                             msg= 'هل تريد تعطيل عناصر التقييم المختارة ؟!';
                             if(confirm(msg)){
                                $.ajax({ 
                                   type : "post",
                                   data : $('#{$TB_NAME}_form').serializeArray(),
                                   url : '{$change_url}/'+no, 
                                }).success(function(data){ 
                                     success_msg('رسالة','تم حذف عناصر الأسئلة المختارة ..');
                                     var total = 0 ;
                                     $('input[name="element_weight[]"]').each(function(i){
                                        var w =$(this).val();
                                        total = parseInt(total)+ parseInt(w); 
                                     })
                                     if(total < 100){
                                        danger_msg('مجموع الوزن النسبي لا يساوي 100 عليك التأكد من المدخلات');
                                     }
                                    setTimeout(function() {
                                        get_to_link(window.location.href);
                                    }, 3000);
                                })
                             }
                          }
                          
                     }
                }
            
    </script>
SCRIPT;
}
sec_scripts($script);
?>
