<?php
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'withnopresence';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_f");
$branch_query_url = base_url("$MODULE_NAME/$TB_NAME/select_branch_query");
$adopt_finical_url = base_url("$MODULE_NAME/$TB_NAME/adopt_finical");

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


                <div class="form-group col-sm-2">
                    <label>الشهر</label>
                    <div>
                        <input type="text" placeholder="الشهر"
                               name="the_month"
                               id="txt_the_month" class="form-control"  value="<?= date('Ym') ?>" >
                    </div>
                </div>

            </div>
        </form>
        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success">
                <i class="fa fa-search"></i>
                إستعلام
            </button>
            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  إكسل </button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $emp_no,$the_month);?>

        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();


    //    $('#dp_emp_no, #dp_branch_id').select2('readonly',1); // + dp_entry_user
   
  $('#txt_the_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
            
        });

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

     function values_search(add_page){
        var values= {page:1, emp_no:$('#dp_emp_no').val() , the_month:$('#txt_month').val(),branch_id:$('#dp_branch_id').val() };
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
    
    
    function adopt_finical_dis(emp_no,the_month,count_day) {
        get_data('{$adopt_finical_url}', {emp_no:emp_no, the_month:the_month, count_day:count_day} ,function(data){
            if(data==1){
                success_msg('رسالة','تم اعتماد البيانات بنجاح ..');
                $('#td_ser_'+emp_no).html("<i class='fa fa-check' title='معتمد' style='color: #0a8800;font-size: large'></i>");
            }else{
                danger_msg('تحذير..',data);
            }
        },'html'); 
    
    }
   
</script>
SCRIPT;
sec_scripts($scripts);
?>
