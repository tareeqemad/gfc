<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/03/22
 * Time: 10:00 ص
 */

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Add_and_ded_items';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
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
            <li class="breadcrumb-item active" aria-current="page">بنود الإستحقاق والإستقطاع</li>
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
                    <a class="btn btn-info" href="<?= $create_url ?>"><i class='glyphicon glyphicon-plus'></i>جديد </a>
                </div>
            </div><!-- end card header -->
            <div class="card-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="row">

                    <div class="form-group col-sm-1">
                        <label>رقم البند</label>
                        <div>
                            <input type="text" value="" name="ser" id="txt_ser" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label>اسم البند</label>
                        <div>
                            <input type="text" value="" name="item_name" id="txt_item_name" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label>الفئة</label>
                        <div>
                            <select type="text" name="special" id="dp_special" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($special as $row) : ?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label>نوع البند</label>
                        <div>
                            <select type="text" name="item_type" id="dp_item_type" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($item_type as $row) : ?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label>ثابت</label>
                        <div>
                            <select type="text" name="constant" id="dp_constant" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($constant as $row) : ?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label>المجموعة</label>
                        <div>
                            <select type="text" name="con_group" id="dp_con_group" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($con_group as $row) : ?>
                                    <option  value="<?= $row['C_NO'] ?>"><?= $row['C_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label>خصم الاجازة</label>
                        <div>
                            <select type="text" name="vacancy_ded" id="dp_vacancy_ded" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($vacancy_ded as $row) : ?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label>خصم الغياب</label>
                        <div>
                            <select type="text" name="absence_ded" id="dp_absence_ded" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($absence_ded as $row) : ?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label>حالة البند</label>
                        <div>
                            <select type="text" name="case_item" id="dp_case_item" class="form-control sel2" >
                                <option value="">__________</option>
                                <?php foreach ($case_item as $row) : ?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


            </div>
        </form>
                <br>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#add_and_ded_items_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fa fa-file-excel-o"></i>إكسل</button>
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
        var values= {page:1,ser:$('#txt_ser').val(), item_name:$('#txt_item_name').val(), special:$('#dp_special').val() ,item_type:$('#dp_item_type').val() ,constant:$('#dp_constant').val() ,con_group:$('#dp_con_group').val() ,vacancy_ded:$('#dp_vacancy_ded').val() ,absence_ded:$('#dp_absence_ded').val(),case_item :$('#dp_case_item').val()};
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
        ajax_pager_data('#add_and_ded_items_tb > tbody',values);
    }

    function clear_form(){
        $('#txt_ser,#txt_item_name').val('');
        $('#dp_special,#dp_item_type,#dp_constant,#dp_con_group,#dp_vacancy_ded,#dp_absence_ded,#dp_case_item').select2('val',0);
    }
    
    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }
    
</script>

SCRIPT;
sec_scripts($scripts);

?>
