<?php
$MODULE_NAME = 'contracts_services';
$TB_NAME = 'Contract_gcc';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_all_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
echo AntiForgeryToken();
?>
<script> var show_page = true; </script>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a></li>
            <?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?= $TB_NAME ?>_form">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم التعاقد</label>
                    <div>
                        <input type="text" name="ser" id="txt_ser" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الجهة الطالبة</label>
                    <div>
                        <select name="gcc_no" id="dp_gcc_no" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($gcc_id_parent as $row) : ?>
                                <option value="<?= $row['GCC_ID'] ?>"><?= $row['CONTRACT_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المورد</label>
                    <div>
                        <select name="customer_id" id="dp_cust_id" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($customers as $row) : ?>
                                <option value="<?= $row['CUSTOMER_ID'] ?>"><?= $row['CUSTOMER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ بداية العقد </label>
                    <div>
                        <input type="text" <?= $date_attr ?> name="contract_start" id="txt_contract_start"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ نهاية العقد</label>
                    <div>
                        <input type="text" <?= $date_attr ?> name="CONTRACT_END" id="txt_contract_end"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الاعتماد</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($adopt_cons as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالات العقد</label>
                    <div>
                        <select name="status" id="dp_status" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($status_cons as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="clear: both"></div>


            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});"
                    class="btn btn-success"> اكسل
            </button>
            <button type="button" onclick="javascript:clear_form();" class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?= modules::run($get_page_url, $page, $ser, $gcc_no, $contract_start, $contract_end, $customer_id, $branch_id, $adopt, $status); ?>
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

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function values_search(add_page){
        var values= {page:1, ser:$('#txt_ser').val(), gcc_no:$('#dp_gcc_no').val(), contract_start:$('#txt_contract_start').val(), contract_end:$('#txt_contract_end').val(),customer_id:$('#dp_cust_id').val(),branch_id:$('#dp_branch_id').val() ,adopt:$('#dp_adopt').val(),status:$('#dp_status').val() };
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
