<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/10/15
 * Time: 10:19 ص
 */
$MODULE_NAME= 'purchases';
$TB_NAME= 'purchase_class';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">مسلسل الطلب</label>
                    <div>
                        <input type="text" name="ser"    id="txt_ser" class="form-control ">

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label"> اسم الصنف  </label>
                    <div>
                        <input type="text" name="class_name_ar"   id="txt_class_name_ar" class="form-control ">

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">وصف الصنف  </label>
                    <div>
                        <input type="text" name="calss_description"   id="txt_calss_description" class="form-control ">
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">الاسم من المشتريات </label>
                    <div >
                        <input type="text" name="purchase_class_name"   id="txt_purchase_class_name" class="form-control ">
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label class="control-label">وصف المشتريات</label>
                    <div>
                        <input type="text" name="purchase_notes"   id="txt_purchase_notes" class="form-control ">

                    </div></div>


                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الصنف الفعلي </label>
                    <div >
                        <input type="text" name="class_id"   id="txt_class_id" class="form-control ">

                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">حالة الطلب </label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control" />
                        <option></option>
                        <?php foreach($adopt_all as $row) : //99 ?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
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

            <?=modules::run($get_page_url,$page, $ser ,$class_name_ar,$calss_description,$purchase_class_name,$purchase_notes,$class_id,$adopt);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.pagination li').click(function(e){
        e.preventDefault();
    });


    function show_row_details(id){

            get_to_link('{$get_url}/'+id);
           }

    function search(){
       var values= {page:1,ser:$('#txt_ser').val(), class_name_ar:$('#txt_class_name_ar').val(),  calss_description:$('#txt_calss_description').val(), purchase_class_name:$('#txt_purchase_class_name').val(), purchase_notes:$('#txt_purchase_notes').val(), class_id:$('#txt_class_id').val(), adopt:$('#dp_adopt').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
       var values= {page:1,ser:$('#txt_ser').val(), class_name_ar:$('#txt_class_name_ar').val(),  calss_description:$('#txt_calss_description').val(), purchase_class_name:$('#txt_purchase_class_name').val(), purchase_notes:$('#txt_purchase_notes').val(), class_id:$('#txt_class_id').val(), adopt:$('#dp_adopt').val() };
         ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
      clearForm($('#{$TB_NAME}_form'));
          }

</script>
SCRIPT;
sec_scripts($scripts);
?>

