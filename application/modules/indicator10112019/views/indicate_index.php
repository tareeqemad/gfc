<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/08/18
 * Time: 11:21 ص
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indicatior';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");

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

                <div class="form-group col-sm-2">
                    <label class="col-sm-1 control-label">القطاع</label>
                    <div>
                        <select name="sector" id="dp_sector" class="form-control">
                            <option></option>
                            <?php foreach($sector as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>


                            <?php endforeach; ?>

                        </select>

                        <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>




                    </div>
                </div>



                <div class="form-group col-sm-1">
                    <label class="control-label">الشهر</label>
                    <div>
                        <input type="text" name="for_month"  id="txt_for_month" value="<?php echo date("Ym");?>" class="form-control" data-val="true"  data-val-required="حقل مطلوب" >
                    </div>
                </div>




            </div>


        </form>


        <div class="modal-footer">

            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url,$page);?>
        </div>
    </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
function search(){


       var values= {page:1,sector:$('#dp_sector').val(),for_month:$('#txt_for_month').val(),branch:$('#dp_branch').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
  var values= {page:1,sector:$('#dp_sector').val(),for_month:$('#txt_for_month').val(),branch:$('#dp_branch').val()};
              ajax_pager_data('#page_tb > tbody',values);
    }

$(document).ready(function() {
 $('#dp_branch').select2().on('change',function(){

        });

$('#dp_sector').select2().on('change',function(){

        });



});



</script>

SCRIPT;

sec_scripts($scripts);

?>