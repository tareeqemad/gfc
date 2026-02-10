<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 08:38 ص
 */
$create_url=base_url('financial/financial_mqasah/create?type='.$type);
$get_page_url =base_url('financial/financial_mqasah/get_page');
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>

        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">الرقم</label>
                    <div class="">
                        <input type="text" id="txt_id"  class="form-control "/>


                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">من تاريخ</label>
                    <div class="">
                        <input type="text" data-type="date"  data-date-format="DD/MM/YYYY"  id="txt_date_from" class="form-control "/>


                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">الي تاريخ</label>
                    <div class="">
                        <input type="text" data-type="date"  data-date-format="DD/MM/YYYY"  id="txt_date_to"  class="form-control "/>


                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الحساب </label>
                    <div class="">
                        <input type="text" id="txt_account_id" class="form-control "/>


                    </div>
                </div>



                <div class="form-group col-sm-2">
                    <label class="control-label">المدخل </label>
                    <div class="">
                        <input type="text" id="txt_entry_user_name"  class="form-control "/>


                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">البيان </label>
                    <div class="">
                        <input type="text" id="txt_hints"  class="form-control "/>


                    </div>
                </div>


            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:search_data();" class="btn btn-success"> إستعلام</button>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));search_data();" class="btn btn-default"> تفريغ الحقول</button>
            </div>
        </fieldset>
        <div id="msg_container"></div>

        <div class="btn-group">
            <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
            <ul class="dropdown-menu " role="menu">
                <li><a href="#" onclick="$('#chainsTbl').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                <li><a href="#" onclick="$('#chainsTbl').tableExport({type:'doc',escape:'false'});">  Word</a></li>
            </ul>
        </div>

        <div id="container">
            <?php echo modules::run('financial/financial_mqasah/get_page',$page,$case,$type); ?>
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

    ajax_pager({});

    }

    function LoadingData(){

        ajax_pager_data('#chainsTbl > tbody',{case :{$case},action:'{$action}',id : $('#txt_id').val(),date_from:$('#txt_date_from').val(),date_to:$('#txt_date_to').val(),account_id:$('#txt_account_id').val(),source:$('#dp_source_type').val(),entry_user:$('#txt_entry_user_name').val(),hints:$('#txt_hints').val()});

    }


   function search_data(){

        get_data('{$get_page_url}',{page: 1,case :{$case},action:'{$action}',id : $('#txt_id').val(),date_from:$('#txt_date_from').val(),date_to:$('#txt_date_to').val(),account_id:$('#txt_account_id').val(),source:$('#dp_source_type').val(),entry_user:$('#txt_entry_user_name').val(),hints:$('#txt_hints').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
</script>
SCRIPT;

sec_scripts($scripts);



?>
