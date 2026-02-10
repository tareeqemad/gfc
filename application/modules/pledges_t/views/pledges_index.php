<?php

$MODULE_NAME= "pledges_t";
$TB_NAME="pledges_cont";
$date_attr= "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$url_get_q = base_url("$MODULE_NAME/$TB_NAME/get_index");
$create_url_get = base_url("$MODULE_NAME/$TB_NAME/index");
$delete_url= base_url("$MODULE_NAME/$TB_NAME/delete");
?>

<script> var show_page=true; </script>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>
            <ul>
                <li><a  href="<?= $create_url ?>"><i class=" glyphicon glyphicon-plus"></i>جديد</a> </li>
                <li><a  href="<?= $create_url_get ?>"><i class="glyphicon glyphicon-zoom-out"></i> استعلام عن عهدة اضافية</a> </li>
                <li><a  href="<?= $url_get_q ?>"><i class="glyphicon glyphicon-zoom-in"></i> استعلام عن عهدة موجودة</a> </li>
            </ul>
        </div>

        <div class="form-body">
            <form class="form-vertical" id="vacancey_query" method="post">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-3">
                        <label class="control-label">الموظف</label>
                        <div>
                            <select name="emp_no" id="dp_emp_id" class="form-control"">
                                <option value=""></option>
                                <?php foreach($employee as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NO']." : ".$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

            </form>


            <div class="modal-footer">
                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" id="btn_clear" onclick="clear_form()" class="btn btn-default"> تفريغ الحقول</button>
            </div>
            <div id="msg_container"></div>
            <div id="container">
                <!---رسم الجدول--->
      <?=modules::run($get_page_url,$page,$seri,$emp_no,$class_id,$class_name ,$barcode ,$v_note,$pp_date  );?>

            </div>

        </div>
    </div>

<?php
$scripts = <<<SCRIPT
<script>
  $(document).ready(function() {
        $('#dp_emp_id').select2();
        //$('#dp_branch').select2();
    });
  
function values_search(add_page){
        var values=
        {page:1, emp_no:$('#dp_emp_id').val(), class_id:$('#txt_item_no').val(),class_name:$('#txt_item_name').val(),class_name:$('#txt_item_name').val(), barcode:$('#txt_barc_no').val(),pp_date:$('#txt_pp_date').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }


 function search(){
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

      function clear_form(){
        clearForm($('#vacancey_query'));
         $('#btn_clear').click(function() {
    $("#dp_emp_id").val(null).trigger("change"); 
});         
    }
    
</script>
SCRIPT;
sec_scripts($scripts);
?>