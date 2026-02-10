<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('projects/adapter/delete');
$get_url =base_url('projects/adapter/get_id');
$edit_url =base_url('projects/adapter/edit');
$create_url =base_url('projects/adapter/create');
$get_page_url = base_url('projects/adapter/get_page');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>


        <ul>
            <?php if(!$show_only and HaveAccess($create_url)): ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif;?>
            <?php if(!$show_only and HaveAccess($delete_url)): ?><li><a  onclick="javascript:adapter_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li><?php endif;?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label"> الرقم  </label>
                    <div>
                        <input type="text"  name="adapter_serial" id="txt_adapter_serial" class="form-control">

                    </div>
                </div>


                <div class="form-group col-sm-3">
                    <label class="control-label">الاسم المحول</label>
                    <div>
                        <input type="text"  name="adapter_name"   id="txt_adapter_name" class="form-control ltr" "="">


                    </div>


                </div>
                <?php if(get_curr_user()->branch == 1): ?>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> الفرع</label>
                        <div>
                            <select type="text"   name="branch" id="dp_branch" class="form-control" >
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group col-sm-1">
                    <label class="control-label"> نسبة المحول  </label>
                    <div>
                        <select id="adapter_load_percent_op" class="form-control col-sm-3">
                            <option>=</option>
                            <option>&lt;=</option>
                            <option>&gt;=</option>
                            <option>&lt;</option>
                            <option>&gt;</option>
                        </select>
                        <input type="text"  name="adapter_load_percent" id="txt_adapter_load_percent" class="form-control col-sm-8">

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label"> قدرة المحول  </label>
                    <div>
                        <input type="text"  name="power_adapter" id="txt_power_adapter" class="form-control">

                    </div>
                </div>


            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search_adapter();" class="btn btn-success"> إستعلام</button>

                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#adapterTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                </div>
            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('projects/adapter/get_page',$page, $show_only); ?>
        </div>

    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>
    $(function () {

        $('#adapterModal').on('shown.bs.modal', function () {
            $('#txt_power_adapter').focus();
        })


    });



    function adapter_create(){
 

        clearForm($('#adapter_from'));

        $('#txt_adapter_name').prop('readonly',false);

        $('#adapter_from').attr('action','{$create_url}');
        $('#adapterModal').modal();

    }

    function adapter_delete(){

        if(confirm('هل تريد حذف الحساب ؟!!!')){


            var url = '{$delete_url}';


            var tbl = '#adapterTbl';

            var container = $('#' + $(tbl).attr('data-container'));

            var val = [];

            $(tbl + ' .checkboxes:checked').each(function (i) {
                val[i] = $(this).val();

            });



            ajax_delete(url, val ,function(data){

                success_msg('رسالة','تم حذف السجلات بنجاح ..');
                container.html(data);

            });
        }

    }

    function adapter_get(id){

        get_data('{$get_url}',{id:id},function(data){

            $.each(data, function(i,item){
   
                $('#adapter_from #txt_adapter_name').val(item.ADAPTER_NAME);
                $('#adapter_from #txt_power_adapter').val(item.POWER_ADAPTER);

                $('#adapter_from #txt_adapter_serial').val( item.ADAPTER_SERIAL);
                $('#adapter_from #txt_power_adapter_sc').val( item.POWER_ADAPTER_SC);

                $('#adapter_from').attr('action','{$edit_url}');

                resetValidation($('#adapter_from'));
                $('#adapterModal').modal();

            });
        });
    }

    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();


        var tbl = '#adapterTbl';

        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');

            container.html(data);
            $('#adapterModal').modal('hide');


        },"html");

    });


    function search_adapter(){

        get_data('{$get_page_url}',{page: 1,power_adapter:$('#txt_power_adapter').val(),adapter_load_percent_op:$('#adapter_load_percent_op').val(),adapter_load_percent:$('#txt_adapter_load_percent').val(),show_only:{$show_only}, no : $('#txt_adapter_serial').val(),name:$('#txt_adapter_name').val(),branch:$('#dp_branch').val()},function(data){
            $('#container').html(data);
        },'html');
    }

  function LoadingData(){

    ajax_pager_data('#adapterTbl > tbody',{power_adapter:$('#txt_power_adapter').val(),adapter_load_percent_op:$('#adapter_load_percent_op').val(),adapter_load_percent:$('#txt_adapter_load_percent').val(),show_only:{$show_only}, no : $('#txt_adapter_serial').val(),name:$('#txt_adapter_name').val(),branch:$('#dp_branch').val()});

    }


</script>
SCRIPT;

sec_scripts($scripts);



?>

