<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('technical/Argent_Maintenance/delete');
$get_url =base_url('technical/Argent_Maintenance/get_id');
$edit_url =base_url('technical/Argent_Maintenance/edit');
$create_url =base_url('technical/Argent_Maintenance/create');
$get_page_url = base_url('technical/Argent_Maintenance/get_page');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)): ?><li><a   href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif;?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>

            <div class="modal-body inline_form">
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

                <div class="form-group col-sm-3">
                    <label class="control-label">وصف المشكلة</label>
                    <div>
                        <input type="text"  name="problem_description" id="txt_problem_description"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المواطن</label>
                    <div>
                        <input type="text"  name="customer_name" id="txt_customer_name"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع العطل</label>
                    <div>
                        <select class="form-control" name="problem_type" id="dp_problem_type">
                            <option></option>
                            <?php foreach($PROBLEM_TYPE as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">من تاريخ</label>
                    <div class="">
                        <input type="text" data-type="date"  data-date-format="DD/MM/YYYY"  id="txt_date_from" class="form-control "/>


                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">من تاريخ</label>
                    <div class="">
                        <input type="text" data-type="date"  data-date-format="DD/MM/YYYY"  id="txt_date_to"  class="form-control "/>


                    </div>
                </div>



            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#projectTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                </div>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('technical/Argent_Maintenance/get_page',$page); ?>
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

    ajax_pager({  branch : $('#dp_branch').val() ,  problem_description : $('#txt_problem_description').val(),  customer_name : $('#txt_customer_name').val(),  problem_type : $('#dp_problem_type').val(),  date_from : $('#txt_date_from').val(),  date_to : $('#txt_date_to').val() });

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{branch : $('#dp_branch').val() ,  problem_description : $('#txt_problem_description').val(),  customer_name : $('#txt_customer_name').val(),  problem_type : $('#dp_problem_type').val(),  date_from : $('#txt_date_from').val(),  date_to : $('#txt_date_to').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,branch : $('#dp_branch').val() ,  problem_description : $('#txt_problem_description').val(),  customer_name : $('#txt_customer_name').val(),  problem_type : $('#dp_problem_type').val(),  date_from : $('#txt_date_from').val(),  date_to : $('#txt_date_to').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }

    function delete_project(a,id){
        if(confirm('هل تريد حذف المشروع ؟')){
           get_data('{$delete_url}',{id:id},function(data){
                $('#container').html(data);
                if(data == '1'){
                    success_msg('رسالة','تم حذف المشروع بنجاح ..');
                    $(a).closest('tr').remove();
                }

            },'html');
        }
    }
</script>
SCRIPT;

sec_scripts($scripts);



?>
