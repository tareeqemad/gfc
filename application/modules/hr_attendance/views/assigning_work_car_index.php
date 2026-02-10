<?php

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'Assigning_work_car';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];


echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>


    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" name="ser" id="txt_ser" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :?>
                                <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ التكليف من</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=date('d/m/Y',strtotime('-0 day'))?>" name="ass_start_date" id="txt_ass_start_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input type="text" <?=$date_attr?> name="ass_end_date" id="txt_ass_end_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة التكليف</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($adopt_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الحركة</label>
                    <div>
                        <select name="car_adopt" id="dp_car_adopt" class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($car_adopt_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>



                <div class="form-group col-sm-2">
                    <label class="control-label">مدخل الطلب</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($entry_user_all as $row) :?>
                                <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">القسم/الدائرة</label>
                    <div>
                        <select name="emp_department" id="dp_emp_department" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_department as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


            </div>
        </form>


        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>

            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>


        <div id="msg_container"></div>

        <div id="container">

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();

    if ('{$emp_branch_selected}' != 1){
        $('#dp_branch_id').select2('val','{$emp_branch_selected}');
        $('#dp_branch_id').select2('readonly','{$emp_branch_selected}');
    }else { 
        $('#dp_branch_id').select2('val','{$emp_branch_selected}');
    }
    
    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function values_search(add_page){
        var values= {page:1,  ser:$('#txt_ser').val(), emp_no:$('#dp_emp_no').val(), ass_start_date:$('#txt_ass_start_date').val(), ass_end_date:$('#txt_ass_end_date').val(), adopt:$('#dp_adopt').val(),car_adopt:$('#dp_car_adopt').val(),entry_user:$('#dp_entry_user').val(), branch_id:$('#dp_branch_id').val(),emp_department:$('#dp_emp_department').val()};
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

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }

    </script>
SCRIPT;
sec_scripts($scripts);
?>
