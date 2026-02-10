<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/16/14
 * Time: 10:00 AM
 */
$create_url =base_url('settings/staff/create');
$public_get_staff_url = base_url('settings/staff/public_get_staff');
$delete_url =base_url('settings/staff/delete');
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php  if( HaveAccess($create_url)): ?><li><a onclick="javascript:staff_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php  endif; ?>
             <?php  if( HaveAccess($delete_url)):  ?><li><a  onclick="javascript:staff_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>إلغاء</a> </li> <?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="input-group col-sm-10">
                    <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                    <input type="text" id="search-tree" class="form-control" placeholder="بحث">
                </div>
            </div>
            <?php echo modules::run('settings/gcc_structure/public_get_structure_tree'); ?>
        </div>

        <div class="col-sm-8"  id="container">

            <?php echo modules::run('settings/staff/public_get_staff','0'); ?>

        </div>

    </div>

</div>

<div class="modal fade" id="staffModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> إختيار الموظف</h4>
            </div>
            <div id="msg_container_alt"></div>
            <form class="form-horizontal" id="staff_form" method="post" action="<?=base_url('settings/staff/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">


                    <div class="form-group">
                        <label class="col-sm-3 control-label">الموظف</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="txt_st_no" name="st_id">
                            <select  data-val="true"   data-val-required="حقل مطلوب" name="emp_no" id="dp_emp_no" class="form-control">
                                <option value=""></option>
                                <?php foreach($employees as $emp) :?>
                                    <option value="<?= $emp['NO']?>"><?=$emp['NO'].': '.$emp['NAME']?></option>
                                <?php endforeach;?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="emp_no" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">طبيعة العمل</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="work_desc" id="txt_work_desc" maxlength="100" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">توضيح الاسم </label>
                        <div class="col-sm-6">
                            <input type="checkbox" class="form-control" name="flag" id="chk_flag" value="1" />
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php

$scripts = <<<SCRIPT
<script>
    $(function () {

        $('#gcc_structureModal').on('shown.bs.modal', function () {

        })

        $('#gcc_structure').tree();

         $('#dp_emp_no').select2();
    });

function staff_create(){

    if($.fn.tree.selected().attr('data-id').trim() !=''){
        $('#dp_emp_no').select2('val','');
        $('#staffModal').modal();
    }

}

function gcc_structure_get(st_id){
    get_data('$public_get_staff_url',{st_no:st_id} ,function(data){
        $('#container').html(data);
    },"html");
}

function staff_delete(){
    if(confirm('هل إلغاء تسكين الموظف ؟!!!')){
            var tbl = '#staffTbl';

            var val = [];

            $(tbl + ' .checkboxes:checked').each(function (i) {
                val[i] = $(this).val();
            });

             ajax_delete_any('$delete_url',{'id[]':val,st_no:$.fn.tree.selected().attr('data-id')} ,function(data){

              $('#container').html(data);

        });
    }

}


  $('button[data-action="submit"]').click(function(e){

        e.preventDefault();

        var form =$('#staff_form');

        var container = $('#container');
        $('#txt_st_no').val($.fn.tree.selected().attr('data-id'));


        ajax_insert_update(form,function(data){

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');

            container.html(data);
            $('#staffModal').modal('hide');

        },"html");

    });


</script>
SCRIPT;

sec_scripts($scripts);

?>
