<?php
$MODULE_NAME= 'salary';
$TB_NAME= 'data_admin';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

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


                <div class="form-group col-sm-2 my_hide">
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

                <div class="form-group col-sm-1">
                    <label class="control-label">من شهر</label>
                    <div>
                        <input type="text" name="month_1" id="txt_month_1" value="<?=date('Y').'01'?>" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">حتى شهر</label>
                    <div>
                        <input type="text" name="month_2" id="txt_month_2" value="<?=date('Ym')?>" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع التعيين</label>
                    <div>
                        <select name="emp_type" id="dp_emp_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_type_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-1 my_hide">
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-1">
                    <label class="control-label">صافي الراتب ≈</label>
                    <div>
                        <input type="text" placeholder="تقريبي" name="net_salary" id="txt_net_salary" class="form-control">
                    </div>
                </div>

                <?php if($page_act=='hr_admin'){ ?>
                <div class="form-group col-sm-2">
                    <label class="control-label">الاضافات والخصومات</label>
                    <div>
                        <select name="sal_con" id="dp_sal_con" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($sal_con_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php } ?>


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

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('#txt_month_1,#txt_month_2').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });

    $('.sel2').select2();

    if('{$page_act}'=='my'){
        $('#dp_emp_no, #dp_branch_id').select2('readonly',1);
        $('.my_hide').hide();
    }
    
    if('{$page_act}'=='hr_branch'){
        $('#dp_branch_id').select2('readonly',1);
    }

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(emp_no,month){
        get_to_link('{$get_url}/'+emp_no+'/'+month+'/{$page_act}');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }
    
    var search_data= sessionStorage.getItem("search_data");
    var search_data_json= JSON.parse(search_data);
    
    /*
    if( search_data != null && '{ $ page_act}'=='hr_admin'){
        info_msg('يرجى الانتظار', 'جار اعادة عرض السجلات السابقة');
        get_data('{ $ get_page_url }', search_data_json ,function(data){
            $('#container').html(data);
        },'html');
    }
    */

    if ('{$page_act}'=='hr_admin'){ // save last search
        
        $('.inline_form').find("input[id^='txt_']").each( function( key, value ) {
            $(this).val( search_data_json[ $(value).attr('name') ] );
        });
        
        $('.inline_form').find("select[id^='dp_']").each( function( key, value ) {
            $(this).select2('val', search_data_json[ $(value).attr('name') ] );
        });
        
        auto_restart_search();
        //window.localStorage.removeItem('search_data');
    }   

    function values_search(add_page){
        var values= {page:1, page_act: '{$page_act}', emp_no:$('#dp_emp_no').val(), month_1:$('#txt_month_1').val(), month_2:$('#txt_month_2').val(), emp_type:$('#dp_emp_type').val(), branch_id:$('#dp_branch_id').val(), net_salary:$('#txt_net_salary').val(), sal_con:$('#dp_sal_con').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        if('{$page_act}'=='hr_admin'){
            sessionStorage.setItem("search_data", JSON.stringify(values) );
        }
        get_data('{$get_page_url}', values ,function(data){
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
