<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('technical/Breakers/delete');
$get_url =base_url('technical/Breakers/get_id');
$edit_url =base_url('technical/Breakers/edit');
$create_url =base_url('technical/Breakers/create');
$get_page_url = base_url('technical/Breakers/get_page');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>


        <ul>
            <?php if(!$show_only and HaveAccess($create_url)): ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif;?>
            <?php if(!$show_only and HaveAccess($delete_url)): ?><li><a  onclick="javascript:Breakers_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li><?php endif;?>

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
                        <input type="text"  name="Breakers_serial" id="txt_Breakers_serial" class="form-control">

                    </div>
                </div>


                <div class="form-group col-sm-3">
                    <label class="control-label">الاسم القاطع</label>
                    <div>
                        <input type="text"  name="Breakers_name"   id="txt_Breakers_name" class="form-control ltr" "="">


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

               

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search_Breakers();" class="btn btn-success"> إستعلام</button>

                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#BreakersTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                </div>
            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('technical/Breakers/get_page',$page, $show_only); ?>
        </div>

    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>
    $(function () {

        $('#BreakersModal').on('shown.bs.modal', function () {
            $('#txt_power_Breakers').focus();
        })


    });



    function Breakers_create(){
 

        clearForm($('#Breakers_from'));

        $('#txt_Breakers_name').prop('readonly',false);

        $('#Breakers_from').attr('action','{$create_url}');
        $('#BreakersModal').modal();

    }

    function Breakers_delete(){

        if(confirm('هل تريد حذف الحساب ؟!!!')){


            var url = '{$delete_url}';


            var tbl = '#BreakersTbl';

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

    function Breakers_get(id){

        get_data('{$get_url}',{id:id},function(data){

            $.each(data, function(i,item){
   
                $('#Breakers_from #txt_Breakers_name').val(item.Breakers_NAME);
                $('#Breakers_from #txt_power_Breakers').val(item.POWER_Breakers);

                $('#Breakers_from #txt_Breakers_serial').val( item.Breakers_SERIAL);
                $('#Breakers_from #txt_power_Breakers_sc').val( item.POWER_Breakers_SC);

                $('#Breakers_from').attr('action','{$edit_url}');

                resetValidation($('#Breakers_from'));
                $('#BreakersModal').modal();

            });
        });
    }

    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();


        var tbl = '#BreakersTbl';

        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');

            container.html(data);
            $('#BreakersModal').modal('hide');


        },"html");

    });


    function search_Breakers(){

        get_data('{$get_page_url}',{page: 1,power_Breakers:$('#txt_power_Breakers').val(),Breakers_load_percent_op:$('#Breakers_load_percent_op').val(),Breakers_load_percent:$('#txt_Breakers_load_percent').val(),show_only:{$show_only}, no : $('#txt_Breakers_serial').val(),name:$('#txt_Breakers_name').val(),branch:$('#dp_branch').val()},function(data){
            $('#container').html(data);
        },'html');
    }

  function LoadingData(){

    ajax_pager_data('#BreakersTbl > tbody',{power_Breakers:$('#txt_power_Breakers').val(),Breakers_load_percent_op:$('#Breakers_load_percent_op').val(),Breakers_load_percent:$('#txt_Breakers_load_percent').val(),show_only:{$show_only}, no : $('#txt_Breakers_serial').val(),name:$('#txt_Breakers_name').val(),branch:$('#dp_branch').val()});

    }


</script>
SCRIPT;

sec_scripts($scripts);



?>

