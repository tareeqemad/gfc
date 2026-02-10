<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/01/18
 * Time: 09:16 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_follow");
$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_evaluate");
$adopt_all_url= base_url("$MODULE_NAME/$TB_NAME/adopt_all_evaluate");
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_evaluate");
$page=1;
echo AntiForgeryToken();
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
                <div class="form-group col-sm-1 hidden" >

                    <label class="control-label">الفرع</label>
                    <div>
                        <select  name="branch" id="dp_branch"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==$branch_user){ echo " selected"; } ?>>
                                    <?= $row['NAME'] ?>
                                </option>


                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

                <div class="form-group col-sm-1">

                    <label class="control-label">من  شهر التنفيذ</label>
                    <div>
                        <select  name="from_month" id="dp_from_month"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php for($i = 1; $i <= 12 ;$i++) :?>
                                <!-- <option   value="<?= $i ?>"><?= months($i) ?></option> -->
                                <option <?PHP if ($i==date('m')){ echo " selected"; } ?>  value="<?= $i ?>"><?= months($i) ?></option>
                            <?php endfor; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="type" data-valmsg-replace="true">
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>

                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?=modules::run($get_page_url,$page);?>
            </div>
        </form>



    </div>

</div>



<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


 $('.pagination li').click(function(e){
        e.preventDefault();
    });

//search();


 function clear_form(){


     $("#dp_branch").select2('val','');
     $("#dp_from_month").select2('val','');

          }
          function search(){


       var values= {page:1,branch:$('#dp_branch').val(),from_month:$('#dp_from_month').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
     var values= {page:1,branch:$('#dp_branch').val(),from_month:$('#dp_from_month').val()};
          ajax_pager_data('#page_tb > tbody',values);
    }

 $(document).ready(function() {


        $('#dp_from_month').select2().on('change',function(){

        });




});







/////////////

</script>

SCRIPT;

sec_scripts($scripts);

?>
