<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 10/12/17
 * Time: 09:30 ص
 */

$delete_url = base_url('technical/Energy/delete');
$get_url = base_url('technical/Energy/get_id');
$edit_url = base_url('technical/Energy/edit');
$create_url = base_url('technical/Energy/create');
$get_page_url = base_url('technical/Energy/get_page');
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url . '/' ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a>
                </li><?php endif; ?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>

            <div class="modal-body inline_form">



                <div class="form-group col-sm-2">
                    <label class="control-label"> من شهر</label>

                    <div>
                        <input type="text"
                               name="from_date"
                               name="month"
                               data-date-format="YYYYMM"
                               data-date-viewmode="years"
                               data-date-minviewmode="months"
                               data-val-required="required"
                               data-val="true"
                               data-type="date"
                               id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">إلى شهر</label>

                    <div>
                        <input type="text"
                               name="t_date"
                               name="month"
                               data-date-format="YYYYMM"
                               data-date-viewmode="years"
                               data-date-minviewmode="months"
                               data-val-required="required"
                               data-val="true"
                               data-type="date"
                               id="txt_to_date"
                               class="form-control">
                    </div>
                </div>


            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">Search</button>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();"
                        class="btn btn-default"> Reset
                </button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">

            <?php echo modules::run('technical/Energy/get_page', $page, $action); ?>

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

    ajax_pager({action :'$action' ,from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val()   });

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{action :'$action' ,from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val() });

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,action :'$action'  ,from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val() },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }


</script>

SCRIPT;

sec_scripts($scripts);



?>
