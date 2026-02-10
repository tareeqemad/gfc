<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url = base_url('technical/Fast_workorder/delete');
$get_url = base_url('technical/Fast_workorder/get_id');
$edit_url = base_url('technical/Fast_workorder/edit');
$create_url = base_url('technical/Fast_workorder/create');
$get_page_url = (isset($isArchive) && $isArchive) ? base_url('technical/Fast_workorder/get_page_archive') : base_url('technical/Fast_workorder/get_page');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>

            <div class="modal-body inline_form">





                <div class="form-group col-sm-2">
                    <label class="control-label">من تاريخ</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"
                               id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الي تاريخ</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="t_date" id="txt_to_date"
                               class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> البيان</label>

                    <div>
                        <input type="text" name="title" id="txt_title" class="form-control">
                    </div>
                </div>


            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle"
                            onclick="$('#projectTbl').tableExport({type:'excel',escape:'false'});"
                            data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير
                    </button>
                </div>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();"
                        class="btn btn-default">تفريغ الحقول
                </button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">

            <?php echo modules::run('technical/Fast_workorder/get_page', $page, $action); ?>

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

    ajax_pager({action :'$action' ,title:$('#txt_title').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val()   });

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{action :'$action',title:$('#txt_title').val() ,from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val() });

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,action :'$action' ,title:$('#txt_title').val() , from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val() },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }


</script>
SCRIPT;

sec_scripts($scripts);



?>
