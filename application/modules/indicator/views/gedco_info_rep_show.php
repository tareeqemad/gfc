<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 27/02/19
 * Time: 11:16 ص
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_target';
$TB_NAME2= 'indicatior';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_all_info_gedco_page");
$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_target");
$get_sector= base_url("$MODULE_NAME/$TB_NAME/public_get_sector");
$adopt= base_url("$MODULE_NAME/$TB_NAME/adopt");
$unadopt= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$get_is_adopt_url= base_url("$MODULE_NAME/$TB_NAME/public_get_is_adopt");
$indicator=base_url("indicator/indecator_info/public_get_sectors");
$report_url = base_url("JsperReport/showreport?sys=indecator");
$Refreash=base_url("indicator/indecator_info/Refreash");
$page=1;
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

                    <div class="form-group col-sm-1 hidden" >

                        <label class="control-label">المقر</label>
                        <div>
                            <select  name="branch" id="dp_br"  data-curr="false"  class="form-control"  >
                                <option></option>
                                <?php
                                if($this->user->branch == 1){
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

 <div class="form-group col-sm-2 hidden">
                        <label class="col-sm-1 control-label">القطاع</label>
                        <div>
                            <select name="sector" data-val="true"  data-val-required="حقل مطلوب"  id="dp_sector" class="form-control">
                                <option></option>
								 <option value="-2">عرض جميع القطاعات</option>
                              <?php foreach($sector as $row) :?>
                                     <option value="<?= $row['ID'] ?>" >
                                    <?= $row['ID_NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>




                        </div>
                    </div>
			<div class="form-group col-sm-2 hidden">
        <label class="col-sm-1 control-label">التصنيف الاول</label>
        <div>
            <select name="class"   id="dp_class" class="form-control">
                <option value=""></option>
                <?php if (count($rs)>0){?>
                    <?php foreach($class as $row) :?>
                        <option value="<?= $row['ID'] ?>" ><?= $row['ID_NAME'] ?></option>
                    <?php endforeach; ?>
                <?php } ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>
	 <div class="form-group col-sm-2 hidden">
                    <label class="col-sm-1 control-label">التصنيف الثاني</label>
                    <div>
                        <select name="second_class"   id="dp_second_class" class="form-control select2">
                            <option value=""></option>

                            <?php foreach($second_class_list as $row) :?>
                                <option value="<?= $row['ID'] ?>" ><?= $row['ID_NAME'] ?></option>
                            <?php endforeach; ?>

                        </select>
                        <span class="field-validation-valid" data-valmsg-for="second_class" data-valmsg-replace="true"></span>




                    </div>
                </div>
                    

                   



                  
                    </div>


                </div>

               <div class="modal-footer">
	                    <div>
	                        <button type="button" onclick="javascript:print_report('1');" class="btn btn-success">تقرير عمليات الشركة الشهرية<span class="glyphicon glyphicon-print"></span></button>
<?php  if( HaveAccess($Refreash)):  ?>
                                <button type="button" onclick="javascript:print_report('2');" class="btn btn-danger">تحديث التقرير لشهر الحالي<span class="glyphicon glyphicon-refresh"></span></button>
<?php  endif; ?>
                                                        
							
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

function getReportUrl(rep_id){
        var for_month =$('#txt_for_month').val();
       
        var repUrl;
		
        switch(rep_id) {
            case "1":
                repUrl = '{$report_url}&report_type=pdf&report=gedco_monthly_report&p_month='+for_month+'';
                break;
            case "2":
                  get_data('{$Refreash}',{},function(data){
                   success_msg('رسالة', 'تم العملية بنجاح ..');
            });
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