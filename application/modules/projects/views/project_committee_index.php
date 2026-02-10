<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 28/07/16
 * Time: 11:09 ص
 */ 

$MODULE_NAME= 'projects';
$TB_NAME= 'project_committee';
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
                        <input type="text"  name="COMMITTEE_SER" id="TXT_COMMITTEE_SER" class="form-control" tabindex="1" >
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
                        <select name="BRANCH" id="DP_BRANCH" class="form-control" tabindex="3" >
                            <option value="">----------------</option>
                            <?php foreach($branches as $key => $value): ?>
                            <option value="<?= $value['NO']; ?>"><?= $value['NAME']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                 </div>
                  <div class="form-group col-sm-2">
                    <label class="control-label">المدخل</label>
                    <div>
                        <select name="ENTRY_USER" id="DP_ENTRY_USER" class="form-control" tabindex="5" />
                        <option value="">----------------</option>
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
            <?=modules::run($get_page_url, $page, $COMMITTEE_SER, $TITLES, $MASTER_TYPE, $BRANCH, $ENTRY_USER );?>
         </div>
    </div>
</div>
<?php	
$script =<<<SCRIPT

<script type="text/javascript">
    $(document).ready(function() {
		$('#DP_THE_TYPE,#DP_BRANCH, #DP_ENTRY_USER').select2();
		
		$('#DP_THE_TYPE').change(function(){
			var No = $(this).val();
			if(No == 1){
				$('.BRANCH').css("display","block");
			}else{
				$('.BRANCH').css("display","none");
				$('#DP_BRANCH').val(0).prop("selected",true);
			}
		})
    });
	function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/edit');
    }
	function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }
	function search(){
		var COMMITTEE_SER = $('#TXT_COMMITTEE_SER').val();
		var TITLES = $('#TXT_TITLES').val();
		var THE_TYPE = $('#DP_THE_TYPE').val();
		var BRANCH = $('#DP_BRANCH').val();
		var ENTRY_USER = $('#DP_ENTRY_USER').val();
		
		
        var values= {page:1, COMMITTEE_SER, TITLES, THE_TYPE, BRANCH, ENTRY_USER};
        
		get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }
	function LoadingData(){
        var COMMITTEE_SER = $('#TXT_COMMITTEE_SER').val();
		var TITLES = $('#TXT_TITLES').val();
		var THE_TYPE = $('#DP_THE_TYPE').val();
		var BRANCH = $('#DP_BRANCH').val()
		var ENTRY_USER = $('#DP_ENTRY_USER').val();
		
		
        var values= {page:1, COMMITTEE_SER, TITLES, THE_TYPE, BRANCH, ENTRY_USER};
        ajax_pager_data('#page_tb > tbody',values);
    }
</script>


SCRIPT;

sec_scripts($script);

?>
