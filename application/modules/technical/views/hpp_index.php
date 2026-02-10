<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('technical/HighPowerPartition/delete');
$get_url =base_url('technical/HighPowerPartition/get_id');
$edit_url =base_url('technical/HighPowerPartition/edit');
$create_url =base_url('technical/HighPowerPartition/create');
$get_page_url = base_url('technical/HighPowerPartition/get_page');

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
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text"  name="notes" id="txt_notes"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الخط المغذي </label>
                    <div>

                        <select   name="feeder_line" id="txt_feeder_line" class="form-control">
                            <option></option>
                            <?php foreach($FEEDER_LINE as $row) :?>
                                <option   value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="col-sm-12 control-label">الموقع (خريطة) في محيط كيلو</label>
                    <div class="col-sm-6">
                        <input type="text"    name="x" id="txt_x" class="form-control">

                    </div>
                    <div class="col-sm-6">
                        <input type="text"      name="y" id="txt_y" class="form-control">

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="col-sm-12 control-label" style="height: 25px;">  </label>
                    <button  type="button" class="btn green" onclick="javascript:_showReport('<?=base_url("maps/public_map/txt_x/txt_y") ?>');">
                        <i class="icon icon-map-marker"></i>
                    </button>

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
            <?php echo modules::run('technical/HighPowerPartition/get_page',$page); ?>
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

    ajax_pager({branch:$('#dp_branch').val() , notes:$('#txt_notes').val() , feeder_line: $('#txt_feeder_line').val() , x:$('#txt_x').val() , y:$('#txt_y').val() });

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{branch:$('#dp_branch').val() , notes:$('#txt_notes').val() , feeder_line: $('#txt_feeder_line').val() , x:$('#txt_x').val() , y:$('#txt_y').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,branch:$('#dp_branch').val() , notes:$('#txt_notes').val() , feeder_line: $('#txt_feeder_line').val() , x:$('#txt_x').val() , y:$('#txt_y').val()},function(data){
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
