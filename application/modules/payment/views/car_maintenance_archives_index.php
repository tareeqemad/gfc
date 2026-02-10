<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/10/22
 * Time: 14:00 ص
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'Car_maintenance_archives';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>
<script> var show_page=true; </script>
<div class="row">

    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="col-sm-12">

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم الطلب</label>
                        <div>
                            <input type="text" value="" name="maintain_no" id="txt_maintain_no" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم السيارة</label>
                        <div>
                            <input type="text" value="" name="car_id" id="txt_car_id" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">تاريخ الطلب من</label>
                        <div>
                            <input type="text" <?=$date_attr?> name="req_start_date" id="txt_req_start_date" class="form-control" value="">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الى</label>
                        <div>
                            <input type="text" <?=$date_attr?> name="req_end_date" id="txt_req_end_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">المقر </label>
                        <div>
                            <select name="branch_id" id="dp_branch_id" class="form-control sel2"  >
                                <option value="">_________</option>
                                <?php foreach($branches as $row) :?>
                                    <option  value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">مدخل الطلب</label>
                        <div>
                            <select name="entry_user" id="dp_entry_user" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php foreach($emp_no_cons as $row) :?>
                                    <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" onclick="javascript:search();" class="btn btn-success">إستعلام</button>
                <button type="button" onclick="$('#car_maintenance_archives_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            </div>

        </form>
    </div>

    <div id="msg_container"></div>

    <div id="container"></div>

</div>

</div>

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    $('.sel2').select2();
    
    if ('{$emp_branch_selected}' != 1){
        $('#dp_branch_id').select2('val','{$emp_branch_selected}');
        $('#dp_branch_id').prop('readonly', true);
        $('#dp_branch_id').attr('readonly', true);
    }else {
        $('#dp_branch_id').select2('val','{$emp_branch_selected}');
    }
    
    function values_search(add_page){
        var values= {page:1,maintain_no:$('#txt_maintain_no').val(), car_id:$('#txt_car_id').val(), req_start_date:$('#txt_req_start_date').val() ,req_end_date:$('#txt_req_end_date').val()  ,entry_user:$('#dp_entry_user').val() ,branch_id:$('#dp_branch_id').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#car_maintenance_archives_tb > tbody',values);
    }

    function clear_form(){
        $('#txt_maintain_no').val('');
        $('#txt_car_id').val('');
        $('#txt_req_start_date').val('');
        $('#txt_req_end_date').val('');
        $('#dp_entry_user').select2('val','',);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
