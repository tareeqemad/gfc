<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 13/06/16
 * Time: 11:14 ص
 */ 
$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_extra_axes';
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
                    <label class="control-label">رقم التقييم</label>
                    <div>
                        <input type="text"  name="eextra_id" id="txt_eextra_id" class="form-control" tabindex="0" readonly>
                    </div>
                 </div>
                 <div class="form-group col-sm-1">
                    <label class="control-label">نوع التقييم</label>
                    <div>
                        <select name="eextra_form_id" id="dp_eextra_form_id" class="form-control" tabindex="1" >
                        <option value="">-----------------</option>
                        <?php foreach($extra_form as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                 </div>
                 <div class="form-group col-sm-1">
                    <label class="control-label">المسمى الإشرافي</label>
                    <div>
                        <select name="eextra_name" id="dp_eextra_name" class="form-control" tabindex="2" >
                        <option value="">----------------</option>
                        <?php foreach($extra_name as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                 </div>
                  <div class="form-group col-sm-1">
                    <label class="control-label">الوزن النسبي </label>
                    <div>
                        <input type="text" name="eextra_relative_weight" id="txt_eextra_relative_weight" class="form-control" tabindex="3">
                    </div>
                  </div>
                  <div class="form-group col-sm-2">
                    <label class="control-label">الوزن النسبي الإشرافي </label>
                    <div>
                        <input type="text" name="eextra_supervision_weight" id="txt_eextra_supervision_weight" class="form-control" tabindex="4">
                    </div>
                  </div>
                  <div class="form-group col-sm-2">
                    <label class="control-label">المدخل</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control" tabindex="5" />
                        <option></option>
                        <?php foreach($entry_user_all as $row) :?>
                            <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                  </div>
                  <div class="form-group col-sm-4">
                    <label class="control-label"> بيان محور التقييم </label>
                    <div>
                        <input type="text" name="note" id="txt_note" class="form-control" tabindex="6">
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
            <?=modules::run($get_page_url, $page, $eextra_id, $eextra_form_id, $note, $eextra_name, $eextra_relative_weight, $eextra_supervision_weight, $entry_user );?>
         </div>
    </div>
</div>
<?php	
$script =<<<SCRIPT

<script type="text/javascript">
    $(document).ready(function() {
		$('#dp_eextra_form_id, #dp_eextra_name, #dp_entry_user').select2();
    });
	function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/edit');
    }
	function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }
	function search(){
		var eextra_id = $('#txt_eextra_id').val();
		var eextra_form_id = $('#dp_eextra_form_id').val();
		var note = $('#txt_note').val();
		var eextra_name = $('#dp_eextra_name').val()
		var eextra_relative_weight = $('#txt_eextra_relative_weight').val();
		var eextra_supervision_weight = $('#txt_eextra_supervision_weight').val();
		var entry_user = $('#dp_entry_user').val();
		
		
        var values= {page:1, eextra_id, eextra_form_id, note,eextra_name, eextra_relative_weight, eextra_supervision_weight, entry_user};
		get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }
	function LoadingData(){
        var eextra_id = $('#txt_eextra_id').val();
		var eextra_form_id = $('#dp_eextra_form_id').val();
		var note = $('#txt_note').val();
		var eextra_name = $('#dp_eextra_name').val()
		var eextra_relative_weight = $('#txt_eextra_relative_weight').val();
		var eextra_supervision_weight = $('#txt_eextra_supervision_weight').val();
		var entry_user = $('#dp_entry_user').val();
		
		
        var values= {page:1, eextra_id, eextra_form_id, note,eextra_name, eextra_relative_weight, eextra_supervision_weight, entry_user};
        ajax_pager_data('#page_tb > tbody',values);
    }
</script>


SCRIPT;

sec_scripts($script);

?>
