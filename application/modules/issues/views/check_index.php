<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 16/03/19
 * Time: 08:37 ص
 */

$MODULE_NAME= 'issues';
$TB_NAME= 'checks';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_check");
$page=1;
echo AntiForgeryToken();
?>


    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title; ?></div>
            <ul>
             
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

            </ul>

        </div>



        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الشيك</label>
                        <div>
                            <input type="text" name="check_no"  id="txt_check_no" class="form-control ">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم صاحب الشيك</label>
                        <div>
                            <input type="text" name="check_customer"  id="txt_check_customer" class="form-control ">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">تاريخ الشيك</label>
                        <div>
                            <input type="text"  data-val="true" data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"  name="check_date" id="txt_check_date" class="form-control">
                        </div>
                    </div>





                    <div class="form-group col-sm-2">
                        <label class="control-label">قيمة الشيك</label>
                        <div>
                            <input type="text" name="check_value" id="txt_check_value" class="form-control">
                        </div>
                    </div>



                    <div class="form-group col-sm-2">
                        <label class="control-label">البنك</label>
                        <div>
                            <select name="bank_name" id="dp_bank_name" class="form-control">
                                <option value="">-</option>
                                <?php foreach($banks as $row) :?>
                                    <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>





                </div>


            </form>


            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php echo ''; //modules::run($get_page_url,$page,$sub_no,$sub_name,$id,$for_month);?>
            </div>
        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

        function values_search(add_page){

                var values=

                {page:1, check_no:$('#txt_check_no').val(), check_date:$('#txt_check_date').val(), check_value:$('#txt_check_value').val(),
                 bank_name:$('#dp_bank_name').val(),check_customer:$('#txt_check_customer').val()};
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

     $('#dp_bank_name').select2().on('change',function(){
        });
</script>

SCRIPT;

sec_scripts($scripts);

?>