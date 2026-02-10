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
$manage_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
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

                <div class="form-group col-sm-1">
                    <label class="col-sm-1 control-label">مقر التنفيذ	</label>
                    <div>
                        <select name="branch_exe_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_exe_id" class="form-control">
                            <option></option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>


                            <?php endforeach; ?>

                        </select>

                        <span class="field-validation-valid" data-valmsg-for="branch_exe_id" data-valmsg-replace="true"></span>




                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="col-sm-2 control-label">جهة التنفيذ/ادارة	</label>
                    <div>

                        <select name="manage_exe_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_exe_id" class="form-control">
                            <option></option>
                            <?php foreach($b as $row) :?>
                                <option value="<?= $row['ST_ID'] ?>"><?= $row['ST_NAME'] ?></option>


                            <?php endforeach; ?>


                        </select>

                        <span class="field-validation-valid" data-valmsg-for="manage_exe_id" data-valmsg-replace="true"></span>

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

$('#dp_branch_exe_id').select2().on('change',function(){

          get_data('{$manage_exe_branch}',{no: $(this).val()},function(data){
            $('#dp_manage_exe_id').html('');
              $('#dp_manage_exe_id').append('<option></option>');
              $("#dp_manage_exe_id").select2('val','');
                 //$("#dp_cycle_exe_id").select2('val','');
                 //$("#dp_department_exe_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_manage_exe_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });
        $('#dp_manage_exe_id').select2().on('change',function(){

        });

        $('#dp_from_month').select2().on('change',function(){

        });







});
 reBind_pram(0);


function reBind_pram(cnt){


            $('select[name="from_month[]"]').select2().on('change',function(){

                //  checkBoxChanged();


            });
            $('button#btn_active_'+cnt).on('click',  function (e) {

              alert();






            });


        }



/////////////

</script>

SCRIPT;

sec_scripts($scripts);

?>
