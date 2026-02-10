<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */

$get_page_url = base_url('projects/projects/public_select_project_page');
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div class="modal-body inline_form">
            <form  id="project_accounts_form" method="get" action="<?=$get_page_url?>" role="form" novalidate="novalidate">
                <fieldset>
                    <legend>بحـث</legend>
                    <div class="form-group col-sm-1">
                        <label class="control-label">الرقم الفني</label>
                        <div>
                            <input type="text"  name="tec_num" id="txt_tec_num"   class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم المشروع</label>
                        <div>
                            <input type="text"  name="project_name" id="txt_project_name"   class="form-control">
                        </div>
                    </div>



                    <div class="form-group col-sm-2">
                        <label class="control-label"> التاريخ من</label>
                        <div>
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"    id="txt_from_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> التاريخ الي</label>
                        <div>
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_date"    id="txt_to_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label">الفرع </label>
                        <div>

                            <select type="text"   name="branch" id="dp_branch" class="form-control" >
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option  value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group  col-sm-2">
                        <label class="control-label"> التصنيف الفنى للمشروع

                        </label>
                        <div>
                            <select class="form-control" name="project_tec_type" id="dp_project_tec_type">
                                <option></option>
                                <?php foreach($project_tec_type as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>" data-tec="<?= $row['ACCOUNT_ID'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>



                    <div class="form-group col-sm-3">
                        <label class="control-label">&nbsp;</label>
                        <div>
                            <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>

                            <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('projects/projects/public_select_project_page',$page,$type); ?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT

<script>

     $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({case:{$case},action:'{$action}',project_tec_type:$('#dp_project_tec_type').val(),tec_num : $('#txt_tec_num').val(),project_name:$('#txt_project_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),branch:$('#dp_branch').val()});

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{case:{$case},action:'{$action}',project_tec_type:$('#dp_project_tec_type').val(),tec_num : $('#txt_tec_num').val(),project_name:$('#txt_project_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),branch:$('#dp_branch').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,case:{$case},action:'{$action}',project_tec_type:$('#dp_project_tec_type').val(),tec_num : $('#txt_tec_num').val(),project_name:$('#txt_project_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),branch:$('#dp_branch').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }


    function account_select(name,id,project_serial){


         parent.$('#$txt').val(name);
         parent.$('#h_$txt').val(id);
         parent.$('#IDh_$txt').val(project_serial);

         parent.$('#report').modal('hide');

    }

</script>

SCRIPT;

sec_scripts($scripts);



?>

