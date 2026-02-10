<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url = base_url('technical/Adapter_address/delete');
$get_url = base_url('technical/Adapter_address/get_id');
$edit_url = base_url('technical/Adapter_address/edit');
$create_url = base_url('technical/Adapter_address/create');
$get_page_url = base_url('technical/Adapter_address/get_page');

?>
<?= AntiForgeryToken() ?>
<div class="row">


    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>

            <div class="modal-body inline_form">
                <div class="form-group  col-sm-1">
                    <label class="control-label">الفرع </label>

                    <div>

                        <select type="text" name="branch" id="dp_branch" class="form-control">
                            <option></option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">المكان</label>

                    <div>
                        <input type="text" name="address" id="txt_address" class="form-control">
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
            <?php echo modules::run('technical/Adapter_address/get_page', $page, true); ?>
        </div>

    </div>

</div>




<?php


$scripts = <<<SCRIPT

<script>


   function address_select(id , name ,x,y){
            parent.$('#$txt').val(name);
            parent.$('#h_$txt').val(id);
            parent.$('#txt_gis_x').val(x);
            parent.$('#txt_gis_y').val(y);


            parent.$('#report').modal('hide');
    }


    $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({branch:$('#dp_branch').val() , address:$('#txt_address').val()  });

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{branch:$('#dp_branch').val() , address:$('#txt_address').val() });

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,isPublic : true,branch:$('#dp_branch').val() , address:$('#txt_address').val() },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }


</script>
SCRIPT;

sec_scripts($scripts);



?>
