<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url = base_url('technical/Requests/delete');
$get_url = base_url('technical/Requests/get_id');
$edit_url = base_url('technical/Requests/edit');
$create_url = base_url('technical/Requests/create');
$get_page_url =   base_url('technical/Requests/public_get_page');

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
                        <select name="branch" id="dp_branch" class="form-control">
                            <option></option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>



                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الطلب</label>

                    <div>
                        <input type="text" name="request_code" id="txt_request_code" class="form-control">
                    </div>
                </div>

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
                    <label class="control-label"> المواطن</label>

                    <div>
                        <input type="text" name="citizen_name" id="txt_citizen_name" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> البيان</label>

                    <div>
                        <input type="text" name="purpose_description" id="txt_purpose_description" class="form-control">
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

            <?php echo modules::run('technical/requests/public_get_page',$txt, $page, $type); ?>

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

    ajax_pager({action :'$action' ,type : '{$type}' ,branch : $('#dp_branch').val() ,requests_type:$('#dp_requests_type').val() ,from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val() , citizen_name:$('#txt_citizen_name').val() , purpose_description : $('#txt_purpose_description').val() ,request_code : $('#txt_request_code').val()  });

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{action :'$action' ,type : '{$type}',branch : $('#dp_branch').val() ,requests_type:$('#dp_requests_type').val() ,from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(), citizen_name:$('#txt_citizen_name').val() , purpose_description : $('#txt_purpose_description').val() ,request_code : $('#txt_request_code').val() });

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,action :'$action'  ,type : '{$type}',branch : $('#dp_branch').val(),requests_type:$('#dp_requests_type').val() , from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(), citizen_name:$('#txt_citizen_name').val() , purpose_description : $('#txt_purpose_description').val(),request_code : $('#txt_request_code').val()  },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }

    function select_request(id,name){

        parent.$('#$txt').val(name);
        parent.$('#h_$txt').val(id);
        parent.$('#h_$txt').attr('value',id);

        parent.$('#report').modal('hide');

    }
</script>
SCRIPT;

sec_scripts($scripts);



?>
