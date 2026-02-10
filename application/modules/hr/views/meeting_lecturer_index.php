<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/08/23
 * Time: 14:20
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'Meeting_lecturer';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_adopt_status_url=base_url("$MODULE_NAME/$TB_NAME/get_adopt_status");
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
$report_url = base_url("JsperReport/showreport")."?sys=hr";
?>
<script> var show_page=true; </script>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">النظام الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
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
                    <?php if ( HaveAccess($create_url)  ) : ?>
                        <a class="btn btn-info" href="<?= $create_url ?>"><i class='glyphicon glyphicon-plus'></i>جديد </a>
                    <?php endif; ?>
                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form class="form-vertical" id="<?=$TB_NAME?>_form" >
                    <div class="row">

                        <div class="form-group col-sm-2">
                            <label>رقم الاجتماع</label>
                            <div>
                                <input type="text" name="lecturer_no" id="txt_lecturer_no" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>عنوان الاجتماع</label>
                            <div>
                                <input type="text" name="lecturer_title" id="txt_lecturer_title" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>من تاريخ </label>
                            <div>
                                <input type="text" name="from_date" id="txt_from_date" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>الى تاريخ </label>
                            <div>
                                <input type="text" name="to_date" id="txt_to_date" class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label>تصنيف الاجتماع</label>
                            <div>
                                <select name="meeting_type" id="dp_meeting_type" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($meeting_type as $row) : ?>
                                        <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <?php if (HaveAccess($get_adopt_status_url)  ) : ?>

                            <div class="form-group col-sm-2">
                                <label>حالة المحضر</label>
                                <div>
                                    <select name="adopt_status" id="dp_adopt_status" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($adopt_status as $row) : ?>
                                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>
                </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#meeting_lecturer_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-cyan-light"><i class="fas fa-eraser"></i>تفريغ الحقول</button>
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
    
    $('#txt_from_date ,#txt_to_date').datetimepicker({
        format: 'DD/MM/YYYY',
        minViewMode: "days",
        pickTime: false
    });

    function values_search(add_page){
        var values= {page:1,lecturer_title:$('#txt_lecturer_title').val(),lecturer_no:$('#txt_lecturer_no').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),meeting_type:$('#dp_meeting_type').val(),adopt_status:$('#dp_adopt_status').val()};
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
        ajax_pager_data('#meeting_lecturer_tb > tbody',values);
    }

    function clear_form(){
        $('#dp_emp_no').select2('val',0);
    }
    
    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }
    
    function print_report_pdf(ser) {
        _showReport('{$report_url}&report_type=pdf&report=meeting_minutes&p_id='+ser);
    }
</script>

SCRIPT;
sec_scripts($scripts);

?>
