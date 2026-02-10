<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 12/16/21
 * Time: 6:02 AM
 */
$MODULE_NAME = 'salary';
$TB_NAME     = 'Financial_advance';

$get_url       = base_url("$MODULE_NAME/$TB_NAME/get");
$create_url    = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url      = base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url  = base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_all_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");

if ($this->uri->segment(5)=='my')
{
    $hidden='hidden';
}
else
{
    $hidden='';
}
echo AntiForgeryToken();
?>

    <script> var show_page=true; </script>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php
                if (HaveAccess($create_url)):
                    ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php
                endif;
                ?>
            </ul>

        </div>

        <div class="form-body">

            <form class="form-vertical" id="<?= $TB_NAME ?>_form" >
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم السند</label>
                        <div>
                            <input type="text" name="ser" id="txt_ser" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2 <?=$hidden;?>">
                        <label class="control-label">الموظف</label>
                        <div>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php
                                foreach ($sponsor_no_cons as $row):
                                    ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2 <?=$hidden;?>">
                        <label class="control-label">مقر الموظف</label>
                        <div>
                            <select name="branch" id="dp_branch" class="form-control sel2">
                                <option value="">_________</option>
                                <?php
                                foreach ($bran_cons as $row):
                                    ?>
                                    <option  value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-2 <?=$hidden;?>">
                        <label class="control-label">نوع التعيين</label>
                        <div>
                            <select name="emp_type" id="dp_emp_type" class="form-control sel2">
                                <option value="">_________</option>
                                <?php
                                foreach ($emp_type_cons as $row):
                                    ?>
                                    <option  value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>

                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">عدد الأقساط</label>
                        <div>
                            <select name="installments_no" id="dp_installments_no" class="form-control sel2" >
                                <option value="">_________</option>
                                <option  value="12">12 شهر</option>
                                <option  value="18">18 شهر</option>

                            </select>
                        </div>
                    </div>



                    <div class="form-group col-sm-1">
                        <label class="control-label">طبيعة السلفة</label>
                        <div>
                            <select name="advance_type" id="dp_advance_type" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php
                                foreach ($advance_type_cons as $row):
                                    ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group col-sm-1">
                        <label class="control-label">سبب السلفة</label>
                        <div>
                            <select name="reason_id" id="dp_reason_id" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php
                                foreach ($reason_id_cons as $row):
                                    ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group col-sm-1">
                        <label class="control-label">رصيد  السلفة</label>
                        <div>
                            <select name="advance_balance_allow" id="dp_advance_balance_allow" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php
                                foreach ($advance_balance_allow_cons as $row):
                                    ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">حالة الإعتماد</label>
                        <div>
                            <select name="adopt" id="dp_adopt" class="form-control sel2" >
                                <option value="">_________</option>
                                <?php
                                foreach ($adopt_cons as $row):
                                    ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php
                                endforeach;
                                ?>
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
			 <?=modules::run($get_page_url, $page, $this->uri->segment(5) );?> 
            </div>

        </div>

    </div>


<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();
    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function values_search(add_page){
        var values= {page:1,page_act: '{$page_act}', ser:$('#txt_ser').val(), emp_no:$('#dp_emp_no').val(), branch:$('#dp_branch').val(),emp_type:$('#dp_emp_type').val(), installments_no:$('#dp_installments_no').val(), advance_type:$('#dp_advance_type').val(),reason_id:$('#dp_reason_id').val(), advance_balance_allow:$('#dp_advance_balance_allow').val(), adopt:$('#dp_adopt').val() };
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

        function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }

    </script>
SCRIPT;
sec_scripts($scripts);
?>