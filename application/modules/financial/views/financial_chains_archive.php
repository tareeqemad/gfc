<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 08:38 ص
 */

$get_page_url =base_url('financial/financial_chains/get_page_archive');
$copy_url = base_url('financial/financial_chains/copy_chain');
$chain_url = base_url('financial/financial_chains/get/');
?>
<?= AntiForgeryToken() ?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if( HaveAccess($copy_url)):  ?><li id="copy_chain"><a  href="javascript:;" onclick="javascript:copy_chain();"><i class="icon icon-copy"></i>نسخ </a> </li><?php endif; ?>

            </ul>

        </div>

        <div class="form-body">

            <div id="msg_container"></div>

            <fieldset data-allow-sort="true">
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

                    <div class="form-group col-sm-1">
                        <label class="control-label">الشيك </label>
                        <div class="">
                            <input type="text" id="txt_check_id" class="form-control "/>


                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المبلغ</label>
                        <div>
                            <select id="price_op" class="form-control col-sm-1">
                                <option>=</option>
                                <option><=</option>
                                <option>>=</option>
                                <option><</option>
                                <option>></option>
                            </select>

                            <input type="text"  name="price"   id="txt_price" class="form-control col-sm-5">
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> المصدر</label>
                        <div class="">
                            <select    id="dp_source_type" class="form-control">
                                <option></option>
                                <?php foreach($sources as $_row) :?>
                                    <option    value="<?= $_row['CON_NO'] ?>"><?= $_row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

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

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الحساب </label>
                        <div class="">
                            <input type="text" id="txt_account_id" class="form-control "/>


                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">البيان </label>
                        <div class="">
                            <input type="text" id="txt_hints"  class="form-control "/>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المدخل </label>
                        <div class="">
                            <input type="text" id="txt_entry_user_name"  class="form-control "/>


                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                    <button type="button" onclick="javascript:search_data();" class="btn btn-success"> إستعلام</button>

                    <button type="button" onclick="javascript:clearForm_any($('fieldset'));search_data();" class="btn btn-default"> تفريغ الحقول</button>
                </div>
            </fieldset>
            <div class="btn-group">
                <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                <ul class="dropdown-menu " role="menu">
                    <li><a href="#" onclick="$('#chainsTbl').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                    <li><a href="#" onclick="$('#chainsTbl').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                </ul>
            </div>
            <div id="container">
                <?php echo modules::run('financial/financial_chains/get_page_archive',$page); ?>
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

         $('#chainsTbl tr').click(function(){

            if($(this).attr('data-type') != '4')
                $('#copy_chain').hide();
            else $('#copy_chain').show();

        });

    }

    function LoadingData(){

    ajax_pager_data('#chainsTbl > tbody',{id : $('#txt_id').val(),date_from:$('#txt_date_from').val(),date_to:$('#txt_date_to').val(),check_id:$('#txt_check_id').val(),source:$('#dp_source_type').val(),entry_user:$('#txt_entry_user_name').val(),price:$('#txt_price').val(),price_op:$('#price_op').val() , sort_data :$('input[name="sort_data"]').val(),branch:$('#dp_branch').val(),account_id:$('#txt_account_id').val(),hints:$('#txt_hints').val()});

    }


   function search_data(){

        get_data('{$get_page_url}/1/',{page: 1,id : $('#txt_id').val(),date_from:$('#txt_date_from').val(),date_to:$('#txt_date_to').val(),check_id:$('#txt_check_id').val(),source:$('#dp_source_type').val(),entry_user:$('#txt_entry_user_name').val(),price:$('#txt_price').val(),price_op:$('#price_op').val() , sort_data :$('input[name="sort_data"]').val(),branch:$('#dp_branch').val(),account_id:$('#txt_account_id').val(),hints:$('#txt_hints').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
</script>
SCRIPT;

sec_scripts($scripts);



?>

<?php if( HaveAccess($copy_url)):  ?>
    <script>



        function copy_chain(){

            var id = $('tr.selected',$('#chainsTbl')).attr('data-id');

            get_data('<?= $copy_url ?>',{id : id},function(data){

                if(parseInt(data) > 0)
                    window.location ='<?= $chain_url ?>/'+data+'/index?type=4';

            },'html');

        }

    </script>
<?php endif; ?>