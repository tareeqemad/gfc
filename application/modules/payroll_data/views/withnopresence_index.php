<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 03/05/2020
 * Time: 9:41 ص
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'withnopresence';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$branch_query_url = base_url("$MODULE_NAME/$TB_NAME/select_branch_query");
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
echo AntiForgeryToken();
?>
<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label>الموظف</label>
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
                    <label>التاريخ من </label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=($entry_day!=-1)?$entry_day:date('d/m/Y')?>" name="entry_day" id="txt_entry_day" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>الى</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=($entry_day_2!=-1)?$entry_day_2:''?>" name="entry_day_2" id="txt_entry_day_2" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label> الحركة</label>
                    <select name="status" id="dp_status" class="form-control sel2">
                        <option value="">_________</option>
                        <option value="1">حضور</option>
                        <option value="4">انصراف</option>
                    </select>
                </div>

                <?php if(HaveAccess ($branch_query_url)){ ?>
                    <div class="form-group col-sm-2">
                        <label> المقر</label>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                            <option value="">_______</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?= $row['NO'] ?>" > <?= $row['NAME'] ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php } elseif( ! HaveAccess ($branch_query_url)){ ?>
                    <input type="hidden" name="branch_id" id="dp_branch_id" value="<?= $this->user->branch ?>">
                <?php } ?>


            </div>
        </form>
        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"><i class="fa fa-search"></i> إستعلام
            </button>
            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  إكسل
                <i class="fa fa-file-excel-o"></i>
            </button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $emp_no , $entry_day, $entry_day_2,$status);?>

        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();


 
    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

     function values_search(add_page){
        var values= {page:1, emp_no:$('#dp_emp_no').val() , branch_id:$('#dp_branch_id').val(), entry_day:$('#txt_entry_day').val(), entry_day_2:$('#txt_entry_day_2').val(),status:$('#dp_status').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        var values= values_search(1);
       // alert(values);
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
