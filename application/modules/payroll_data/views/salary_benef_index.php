<?php
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'Salary_benef';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_badl_type = base_url("$MODULE_NAME/$TB_NAME/public_get_badl_type");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    استعلام
                </div>
                <div class="flex-shrink-0">
                    <?php if ( HaveAccess($create_url) ) : ?>
                        <a class="btn btn-info" href="<?= $create_url ?>"><i class='glyphicon glyphicon-plus'></i>طلب </a>
                    <?php endif; ?>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <form class="form-vertical" id="<?= $TB_NAME ?>_form">
                    <div class="row">

                        <div class="form-group col-md-1">
                            <label>رقم الطلب</label>
                            <input type="text" class="form-control" id="txt_ser" name="ser" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                        </div>


                        <div class="form-group col-md-2">
                            <label>نوع البدل</label>
                            <select name="badl_typ" id="dp_badl_typ" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($badl_typ_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>البند</label>
                            <select name="con_no" id="dp_con_no" class="form-control">
                                <option value="">_________</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>خاضع للضريبة</label>
                            <select name="is_taxed" id="dp_is_taxed" class="form-control">
                                <option value="">_________</option>
                                <?php foreach ($is_taxed_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-md-2">
                            <label>حالةالطلب</label>
                            <select name="adopt" id="dp_adopt" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($adopt_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"> <?= $row['CON_NAME'] ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:searchs();" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> إستعلام</button>
                        <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fa fa-file-excel-o"></i>إكسل</button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i class="fa fa-eraser"></i>تفريغ الحقول</button>
                    </div>
                </form>
                <hr>
                <div id="container">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="DetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">بيانات الاعتماد</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="public-modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

   $('.sel2:not("[id^=\'s2\']")').select2();

    $(function(){
        reBind();
    });

    $('select[name="con_no"]').attr('readonly','readonly');
        $('#txt_from_month,#txt_to_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: 'months',
        pickTime: false,
    });

    $('#dp_badl_typ').change(function(){
        change_con_no()
    });

    function change_con_no(){
        $('select[name="con_no"]').empty();
        $('select[name="con_no"]').removeAttr('readonly');
        $('select[name="con_no"]').select2();
        var badl_typ =  $('#dp_badl_typ').val();
        if (badl_typ == '') {
            return -1;
        } else
        get_data('{$get_badl_type}', {badl_typ: badl_typ}, function (data) {
            $('select[name="con_no"]').append($('<option/>').attr("value", '').text('_______'));
            $.each(data, function (i, option) {
            var options = '';
            options += '<option value="' + option.NO + '">' + option.NO + ": " +option.NAME + '</option>';
            $('select[name="con_no"]').append(options);
            });
        });
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val',0);
    }

    function reBind(){
         ajax_pager({
             ser:$('#txt_ser').val(),con_no:$('#dp_con_no').val()
            ,badl_typ:$('#dp_badl_typ').val(),op:$('#dl_op').val()
            , is_taxed:$('#dp_is_taxed').val() ,adopt:$('#dp_adopt').val()
         });
    }

    function LoadingData(){
         ajax_pager_data('#page_tb > tbody',{
             ser:$('#txt_ser').val(),con_no:$('#dp_con_no').val()
            ,badl_typ:$('#dp_badl_typ').val(),op:$('#dl_op').val(),
            is_taxed:$('#dp_is_taxed').val() ,adopt:$('#dp_adopt').val()
         });
    }

    function searchs(){

        if ($('#dl_op').val()  == '' && $('#txt_value').val()  > 0 || $('#txt_value').val()  < 0 ) {
            danger_msg('رسالة','الرجاء اختيار النسبة  ..');
            return 0;
        }
        
        if ($('#dl_op').val()  != '' && $('#txt_value').val()  <= 0 ) {
            danger_msg('رسالة','الرجاء ادخال المبلغ   ..');
            return 0;
        } 
        get_data('{$get_page_url}',{ page: 1,
             ser:$('#txt_ser').val(),con_no:$('#dp_con_no').val()
            ,badl_typ:$('#dp_badl_typ').val(),op:$('#dl_op').val(),
             is_taxed:$('#dp_is_taxed').val() ,adopt:$('#dp_adopt').val()
        },function(data){
            $('#container').html(data);
            reBind();
        },'html');
    }

    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }

    function show_detail_row(ser){
        $('#DetailModal').modal('show');
        $.ajax({
            url: '{$adopt_detail_url}',
            type: 'post',
            data: {ser : ser},
            success: function(response){
                $('#public-modal-body').html(response);
            }
        });
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
