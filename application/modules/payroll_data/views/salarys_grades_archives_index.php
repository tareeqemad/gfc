<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/03/22
 * Time: 13:10 ص
 */

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Salarys_grades';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>
<script> var show_page=true; </script>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page">ارشيف - سلم الرواتب</li>
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
            </div><!-- end card header -->
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">

                        <div class="form-group col-md-1">
                            <label>الرقم التسلسلي</label>
                            <div>
                                <input type="text" placeholder="الرقم التسلسلي" name="ser_arch" id="txt_ser_arch" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label>الدرجة</label>
                            <div>
                                <input type="text" placeholder="الدرجة" name="gradesn_name_arch" id="txt_gradesn_name_arch" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label>الراتب الاساسي</label>
                            <div>
                                <input type="text" placeholder="الراتب الاساسي" name="basic_salary_arch" id="txt_basic_salary_arch" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label> نوع الموظف</label>
                            <select name="emp_type_arch" id="dp_emp_type_arch" class="form-select sel2">
                                <option value="">_______</option>
                                <?php foreach ($emp_type_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"> <?= $row['CON_NAME'] ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-sm-2">
                            <label>من تاريخ</label>
                            <div>
                                <input type="text" <?=$date_attr?> name="start_arch_date" id="txt_start_arch_date" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>الى تاريخ</label>
                            <div>
                                <input type="text" <?=$date_attr?> name="end_arch_date" id="txt_end_arch_date" class="form-control" />
                            </div>
                        </div>

                    </div>

                    <div class="row">

                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#salarys_grades_archives_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fa fa-file-excel-o"></i>إكسل</button>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-cyan-light"><i class="fa fa-eraser"></i>تفريغ الحقول</button>
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
    
    function values_search(add_page){
        var values= {page:1,ser_arch:$('#txt_ser_arch').val(), gradesn_name_arch:$('#txt_gradesn_name_arch').val(), basic_salary_arch:$('#txt_basic_salary_arch').val() ,emp_type_arch:$('#dp_emp_type_arch').val(), start_arch_date:$('#txt_start_arch_date').val(), end_arch_date:$('#txt_end_arch_date').val()};
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
        ajax_pager_data('#salarys_grades_archives_tb > tbody',values);
    }

    function clear_form(){
        $('#txt_ser_arch,#txt_gradesn_name_arch,#txt_basic_salary_arch').val('');
        $('#dp_emp_type_arch').select2('val',0);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
