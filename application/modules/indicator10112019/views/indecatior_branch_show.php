<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 17/09/18
 * Time: 01:03 م
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_target';
$TB_NAME2= 'indicatior';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_display");
$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_sector= base_url("$MODULE_NAME/$TB_NAME/public_get_sector");
$adopt= base_url("$MODULE_NAME/$TB_NAME/adopt");
$unadopt= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$get_is_adopt_url= base_url("$MODULE_NAME/$TB_NAME/public_get_is_adopt");
$indicator=base_url("$MODULE_NAME/$TB_NAME2/public_get_sector");
$report_url = base_url("JsperReport/showreport?sys=planning/indicators_performance");

?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>

                <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

            </ul>

        </div>



        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label">الشهر</label>
                        <div>
                            <input type="number" name="for_month"  id="txt_for_month" value="<?php echo date("Ym");?>" class="form-control" data-val="true"  data-val-required="حقل مطلوب" >
                        </div>
                    </div>

                     <div class="form-group col-sm-1" >

                        <label class="control-label">المقر</label>
                        <div>
                            <select  name="branch" id="dp_br"  data-curr="false"  class="form-control"  >
                                <option></option>
                                <?php
                                if($this->user->branch == -1){
                                ?>
                                <option value="0"> عرض جميع المقرات</option>
                                    <?php
                                }?>

                                 <?php foreach($branches as $row) :?>

                                    <?php if(($row['NO']!='1')) {?>
									 <?php if(($row['NO']!='8')) {?>
                                        <option value="<?= $row['NO'] ?>">
                                            <?= $row['NAME'] ?>
                                        </option>
                                    <?php }?>
<?php }?>


                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="col-sm-1 control-label">القطاع</label>
                        <div>
                            <select name="sector" data-val="true"  data-val-required="حقل مطلوب"  id="dp_sector" class="form-control">
                                <option></option>
                               <!-- <option value="">عرض جميع القطاعات</option> -->
                                <?php foreach($sector as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>




                        </div>
                    </div>
					<div class="form-group col-sm-2">
        <label class="col-sm-1 control-label">التصنيف</label>
        <div>
            <select name="class"   id="dp_class" class="form-control">
                <option></option>
                <?php if (count($rs)>0){?>
                    <?php foreach($class as $row) :?>
                        <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                <?php } ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>

                    <div class="form-group col-sm-2 hidden">
                        <label class="col-sm-1 control-label">الاعتماد</label>
                        <div>
                            <select name="adopt" data-val="true"  data-val-required="حقل مطلوب"  id="dp_adopt" class="form-control">
                                <option></option>
                                <?php foreach($adopt_const as $row1) :?>
                                    <option value="<?= $row1['CON_NO'] ?>" ><?= $row1['CON_NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="adopt" data-valmsg-replace="true"></span>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">
	                    <div>
	                        <input type="radio"  name="rep_type" value="pdf" checked="checked">
	                        <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
	                        &nbsp;&nbsp;&nbsp;&nbsp;
	                        <input type="radio"  name="rep_type" value="xls">
	                        <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
	                        &nbsp;&nbsp;&nbsp;&nbsp;
	                        <input type="radio"  name="rep_type" value="doc">
	                        <i class="fa fa-file-word-o" style="font-size:28px;color:#2a5696"></i>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<button type="button" onclick="javascript:print_report('1');" class="btn btn-success">تقرير حسب المقر<span class="glyphicon glyphicon-print"></span></button>
							<button type="button" onclick="javascript:print_report('2');" class="btn btn-success">تقرير مقارنة أداء المقرات<span class="glyphicon glyphicon-print"></span></button>
	                    </div>
                </div>
				
                <div id="msg_container"></div>

                <div id="container">

                </div>
            </form>



        </div>

    </div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">





 function search(){


       var values= {page:1,sector:$('#dp_sector').val(),class:$('#dp_class').val(),txt_for_month:$('#txt_for_month').val(),branch:$('#dp_br').val(),adopts:$('#dp_adopt').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
     var values= {page:1,sector:$('#dp_sector').val(),class:$('#dp_class').val(),txt_for_month:$('#txt_for_month').val(),adopts:$('#dp_adopt').val()};
          ajax_pager_data('#page_tb > tbody',values);
    }
 $(document).ready(function() {


 $('#dp_sector').select2().on('change',function(){
 if($("#dp_br").val() == '' )
 danger_msg('عليك اختيار المقر','');
 else
 {
get_data('{$indicator}',{no: $('#dp_sector').val()},function(data){
            $('#dp_class').html('');
              $('#dp_class').append('<option></option>');
              $("#dp_class").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_class').append('<option value=' + item.CON_NO + '>' + item.CON_NAME + '</option>');
            });
            });
search();
}



        });
				 $('#dp_class').select2().on('change',function(){
search();



        });
 $('#dp_adopt').select2().on('change',function(){
search();



        });

 $('#txt_for_month').on('change',function(){
  if($('#txt_for_month').val().substring(4, 6)=='13')
 {

 $('#txt_for_month').val((Number($('#txt_for_month').val().substring(0, 4))+1).toString()+'01')
 }
  if($('#txt_for_month').val().substring(4, 6)=='00')
 {

 $('#txt_for_month').val((Number($('#txt_for_month').val().substring(0, 4))-1).toString()+'12')
 }
  if($("#dp_br").val() == '' )
 danger_msg('عليك اختيار المقر','');
 else
search();



        });

        });


 $(document).ready(function() {

 $('#dp_br').select2().on('change',function(){
search();


        });




});

    function getReportUrl(rep_id){
        var for_month =$('#txt_for_month').val();
        var branch = $('#dp_br').val();
		var rep_type = $('input[name=rep_type]:checked').val();
		
		switch(branch) {
            case "2":
				branch_name = 'gaza';
  				break;
            case "3":
     			branch_name = 'north';
				break;
            case "4":
          		branch_name = 'middle';
		    	break;
            case "6":
          		branch_name = 'khan';
		    	break;
            case "7":
          		branch_name = 'rafah';
		    	break;
        }
		
		if( rep_type == 'pdf' || rep_type == 'doc' ) { rep_t = ''; } else { rep_t = rep_type}
   
        var repUrl;
		
        switch(rep_id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report='+branch_name+'_indicators_performance'+rep_t+'&p_from_month='+for_month+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=comparison_of_headquarters_performance'+rep_t+'&p_from_month='+for_month+'';
                break;
        }
        return repUrl;
    }

    function print_report(rep_id){
            var rep_url = getReportUrl(rep_id);
            _showReport(rep_url);
    }







</script>

SCRIPT;

sec_scripts($scripts);

?>