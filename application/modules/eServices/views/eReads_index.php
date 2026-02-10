<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 15/09/18
 * Time: 12:50 م
 */

$MODULE_NAME= 'eServices';
$TB_NAME= 'EReads';
$get_page_url = base_url("eServices/EReads/get_page");
$get_page_url = str_replace('eservices', 'eServices', $get_page_url);
$get_page_url = str_replace('ereads', 'EReads', $get_page_url);

//'https://gsx.gedco.ps/gfc/eServices/EReads/get_page';
//base_url("eServices/EReads/get_page");
//echo $get_page_url;
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title;?></div>
        <ul>

            <?php /*if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"> <i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif*/; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

<div class="form-group col-sm-1" >

                    <label class="control-label">المقر</label>
                    <div>
                        <select  name="branch" id="dp_br"  data-curr="false"  class="form-control"  >

                            <?php
                            if($this->user->branch == -1){
                                ?>
                                <option value="0"> عرض جميع المقرات</option>
                            <?php
                            }?>

                            <?php foreach($branches as $row) :?>

                                <?php if(($row['NO']!='1')) {?>
                                    <?php if(($row['NO']!='8')) {?>
                                        <option value="<?= $row['NO'] ?>">
                                            <?= $row['NAME'] ?>
                                        </option>
                                    <?php }?>
                                <?php }?>


                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>


                    <div class="form-group col-sm-1">
                        <label for="form_control">شهر الفاتورة</label>

                        <div>
                            <input type="text" name="FOR_MONTH"  id="txt_FOR_MONTH"  class="form-control" data-val="true" value="<?php echo date("Ym");?>" placeholder="شهر الفاتورة"  data-val-required="حقل مطلوب" >
                        </div>
                    </div>

                <div class="form-group col-sm-1">
                    <label for="form_control">رقم الإشتراك</label>
                    <div>
                        <input type="text" name="SUBSCRIBER"  id="txt_SUBSCRIBER"  class="form-control"   placeholder="رقم الإشتراك">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label for="form_control">رقم هوية المشترك</label>

                    <div>
                        <input type="text" name="ID"  id="txt_ID"  class="form-control"   placeholder="رقم هوية المشترك">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label for="form_control">اسم المشترك</label>

                    <div>
                        <input type="text" name="NAME"  id="txt_NAME"  class="form-control"   placeholder="اسم المشترك">
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="form_control">اسم المنتفع</label>

                    <div>
                        <input type="text" name="BENIFICARY_NAME"  id="txt_BENIFICARY_NAME"  class="form-control"   placeholder="اسم المنتفع">
                    </div>
                </div>


            </div>


        </form>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:$('#c_eReads_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-warning">اكسل</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php echo '';/*modules::run($get_page_url,$page);*/?>
            </div>
        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
   function clear_form(){
        clearForm($('#{$TB_NAME}_form'));

    }
$('#dp_br').select2();
function values_search(add_page){
                var values=

                {page:1,FOR_MONTH:$('#txt_FOR_MONTH').val(),SUBSCRIBER:$('#txt_SUBSCRIBER').val(),ID:$('#txt_ID').val(),NAME:$('#txt_NAME').val(),BENIFICARY_NAME:$('#txt_BENIFICARY_NAME').val(),BRANCH:$('#dp_br').val()};
                if(add_page==0)
                delete values.page;
                return values;
                }

 function search(){
 if ($('#txt_FOR_MONTH').val()=='' && $('#txt_SUBSCRIBER').val()=='' && $('#txt_ID').val()=='' && $('#txt_NAME').val()=='' && $('#txt_BENIFICARY_NAME').val()=='')
 {
danger_msg('يجب ادخال شهر الفاتورة على الاقل!!');
 }
 else
 {
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }
    }


</script>

SCRIPT;

sec_scripts($scripts);

?>