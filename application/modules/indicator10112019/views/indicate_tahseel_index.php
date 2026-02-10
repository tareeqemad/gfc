<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 15/09/18
 * Time: 12:50 م
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_target';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_tahseel_res");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create_tahseel_target");

$page=1;
echo AntiForgeryToken();
?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?=$title;?></div>
            <ul>

                <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

            </ul>

        </div>



        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <div class="modal-body inline_form">


<!--
                    <div class="form-group col-sm-1" >

                        <label class="control-label">المقر</label>
                        <div>
                            <select  name="branch" id="dp_branch"  data-curr="false"  class="form-control"  >
                                <option value="">-</option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>">
                                        <?= $row['NAME'] ?>
                                    </option>


                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
-->
                   <div class="form-group col-sm-1">
                        <label class="control-label">السنة</label>
                        <div>
                            <input type="text" name="year"  id="txt_year" value="<?php echo date("Y");?>" class="form-control" data-val="true"  data-val-required="حقل مطلوب" >
                        </div>
                    </div>
					<div class="form-group col-sm-1">
                        <label class="control-label">الشهر</label>
                        <div>
                            <input type="text" name="for_month"  id="txt_for_month" value="<?php echo date("Ym",strtotime("-1 month"));?>" class="form-control">
                        </div>
                    </div>

<!--

                    <div class="form-group col-sm-1">
                        <label class="control-label">الشهر</label>
                        <div>
                            <input type="text" name="for_month"  id="txt_for_month" value="<?php echo date("Ym");?>" class="form-control" data-val="true"  data-val-required="حقل مطلوب" >
                        </div>
                    </div>


-->

                </div>


            </form>


            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
            
            </div>
        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
function search(){


       var values= {page:1,year:$('#txt_year').val(),for_month:$('#txt_for_month').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
  var values= {page:1,year:$('#txt_year').val(),for_month:$('#txt_for_month').val()};
              ajax_pager_data('#page_tb > tbody',values);
    }





</script>

SCRIPT;

sec_scripts($scripts);

?>