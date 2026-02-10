<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/07/23
 * Time: 11:00 ص
 */

$MODULE_NAME= 'ratio_emp_lost';
$TB_NAME= 'Ratio_emp_salary';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");

?>
<script> var show_page=true; </script>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">مشروع الفاقد</a></li>
            <li class="breadcrumb-item active" aria-current="page">رواتب موظفي الفاقد</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">

                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form class="form-vertical" id="<?=$TB_NAME?>_form" >
                    <div class="row">

                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>

                        <div class="form-group col-sm-1">
                            <label>الشهر</label>
                            <div>
                                <input type="text" value="<?=date('Ym', strtotime('now'))?>" name="the_month" id="txt_the_month" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label>رقم الموظف</label>
                            <input type="text"  id="txt_emp_no" name="emp_no" class="form-control" />
                        </div>

                        <div class="form-group col-md-2">
                            <label>نوع الموظف</label>
                            <select name="emp_type" id="dp_emp_type" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($emp_type as $row) : ?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-sm-2">
                            <label>الفرقة</label>
                            <div>
                                <select type="text" name="team" id="dp_team" class="form-control sel2" >
                                    <option value="">__________</option>
                                    <?php foreach ($get_teams as $row) : ?>
                                        <option value="<?= $row['TECHNICAL_TEAM_ID']?>"><?= $row['TECHNICAL_TEAM_ID'] . ': '.$row['TECHNICAL_TEAM_MANAGER_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#ratio_emp_salary_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-cyan-light"><i class="fas fa-eraser"></i>تفريغ الحقول</button>

                    <?php if (HaveAccess($adopt_url.'4')) : ?>
                        <button type="button" id="btn_adopt_4" onclick='javascript:adopt_(4);' class="btn btn-success">اعتماد شؤون الموظفين</button>
                    <?php endif; ?>

                    <?php if (HaveAccess($adopt_url.'5')) : ?>
                        <button type="button" id="btn_adopt_5" onclick='javascript:adopt_(5);' class="btn btn-success">اعتماد الرقابة</button>
                    <?php endif; ?>

                </div>
                <hr>
                <div id="container">

                </div>
            </div><!--end card body-->
        </div><!--end card --->
    </div><!--end col lg-12--->
</div><!--end row--->

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    $('.sel2').select2();
    
    $('#txt_the_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });
    
    function values_search(add_page){
        var values= {page:1,branch_no:$('#dp_branch_no').val(),the_month:$('#txt_the_month').val(),emp_type:$('#dp_emp_type').val(),emp_no:$('#txt_emp_no').val(),team:$('#dp_team').val()};
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
        ajax_pager_data('#ratio_emp_salary_tb > tbody',values);
    }

    function clear_form(){
        $('#dp_emp_type ,#dp_team ').select2('val',0);
    }

</script>
SCRIPT;
    $scripts = <<<SCRIPT
    {$scripts}
<script type="text/javascript">

    function adopt_(no){
        
        if ($('#dp_branch_no').val() == 0){
            danger_msg('يجب اختيار المقر');
            return 0;
        }else if ($('#txt_the_month').val() == '') {
            danger_msg('يجب اختيار الشهر');
            return 0;
        }else{

         var month_ = $('#txt_the_month').val();
         var branch_ = $('#dp_branch_no option:selected').text();
         
         var msg= 'هل تريد اعتماد رواتب  ' +branch_+ ' لشهر ' +month_+' ؟!' ;
         
        if(confirm(msg)){

            var values= { branch_no:$('#dp_branch_no').val() ,the_month:month_};
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');

                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }       
            }
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
