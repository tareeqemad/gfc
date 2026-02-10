<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/12/19
 * Time: 10:26 ص
 */
$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Sub_notifications';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$status_url =  base_url('collection_offices/Sub_notifications/changeStatus');
$page=1;

$report_url = base_url("JsperReport/showreport?sys=financial/collection_offices");
echo AntiForgeryToken();
?>

    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>


        <div class="form-body">
            <div class="modal-body inline_form">
            </div>


            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <fieldset  class="field_set">
                    <legend >ارسال الاخطارات</legend>


                    <div class="modal-body inline_form">
                        <input type="hidden" value="<?php //echo $this->user->branch;?>" name="branch_no" id="txt_branch_no">

                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم الكشف</label>
                            <div>
                                <input type="text" data-val="true"  placeholder="رقم الكشف "
                                       data-val-required="حقل مطلوب"  name="disclosure_ser"
                                       id="txt_disclosure_ser" class="form-control"  >
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم الاشتراك</label>
                            <div>
                                <input type="text" data-val="true"  placeholder="رقم الاشتراك"
                                       name="sub_no"
                                       id="txt_sub_no" class="form-control"  >
                            </div>
                        </div>

                        <br>
                    </div>


                </fieldset>


            </form>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php // echo  modules::run($get_page,$page); ?>

            </div>
        </div>







    </div>

<?php


$scripts = <<<SCRIPT

<script>


function print_report(id) {
		
		
		var rep_url = '{$report_url}&report_type=pdf&report=outSourceReport&p_ser='+id+'';
        _showReport(rep_url);
		
    }


		



</script>

SCRIPT;

sec_scripts($scripts);
?>