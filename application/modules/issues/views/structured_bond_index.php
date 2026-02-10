<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 16/03/19
 * Time: 08:37 ص
 */

$MODULE_NAME= 'issues';
$TB_NAME= 'bonds';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$page=1;
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
echo AntiForgeryToken();
?>
<script> var show_page=true; </script>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title; ?></div>
        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الاشتراك</label>
                    <div>
                        <input type="text" name="sub_no" id="txt_sub_no" class="form-control" dir="rtl">

                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المشترك</label>
                    <div>
                        <input type="text" name="sub_name"  id="txt_sub_name" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الهوية</label>
                    <div>
                        <input type="text" name="id"  id="txt_id" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" name="bond_no"  id="txt_bond_no" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">سنة السند</label>
                    <div>
                        <input type="text" name="bond_year"  id="txt_bond_year" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الاعتماد</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control">
                            <option value="">-</option>
                            <?php foreach($adopt as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مدخل السند</label>
                    <div>
                        <select name="insert_user" id="dp_insert_user" class="form-control">
                            <option value="">-</option>
                            <?php foreach($users as $row) :?>
                                <option data-branch="<?=$row['BRANCH']?>" value="<?= $row['ID'] ?>"><?= $row['EMP_NO'].': '.$row['USER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">معدل على السند</label>
                    <div>
                        <select name="update_user" id="dp_update_user" class="form-control">
                            <option value="">-</option>
                            <?php foreach($users as $row) :?>
                                <option data-branch="<?=$row['BRANCH']?>" value="<?= $row['ID'] ?>"><?= $row['EMP_NO'].': '.$row['USER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2 hidden">
                    <label class="control-label">له مرفق</label>
                    <div>
                        <select name="attach" id="dp_attach" class="form-control">
                            <option value="">-</option>
                            <option value="1">نعم</option>
                            <option value="2">لا</option>

                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ تحرير السند من</label>
                    <div>
                        <input type="text" <?=$date_attr?>  name="from_bond_date" id="txt_from_bond_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الى </label>
                    <div>
                        <input type="text" <?=$date_attr?> name="to_bond_date" id="txt_to_bond_date" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-2">

                    <?php
                    if($this->user->branch==1)
                    {
                    ?>
                    <label class="control-label">الفرع</label>

                    <div>

                        <select name="branch" id="dp_branch" class="form-control">

                            <option value="">-</option>
                            <?php foreach($branches as $row) :?>
                                <?php
                                if($row['NO']<>1)
                                {
                                    ?>

                                    <option value="<?= $row['NO'] ?>"  >
                                        <?= $row['NAME'] ?>
                                    </option>
                                <?php
                                }
                                ?>

                            <?php endforeach; ?>
                        </select>
                        <?php
                        }
                        else
                        {
                        ?>
                        <div>
                            <input type="hidden" value="<?php echo $this->user->branch;?>" name="branch" id="dp_branch">

                            <?php

                            }
                            ?>
                        </div>


                    </div>

                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الاستلام</label>
                    <div>
                        <select name="delivers_status_1" id="dp_delivers_status_1" class="form-control">
                            <option value="">-</option>
                            <?php foreach($delivers_status as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الإستلام من</label>
                    <div>
                        <input type="text" <?=$date_attr?>  name="from_delivers_date" id="txt_from_delivers_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> إلى </label>
                    <div>
                        <input type="text" <?=$date_attr?> name="to_delivers_date" id="txt_to_delivers_date" class="form-control">
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
           <!-- <?php echo '';/* modules::run($get_page_url,$page,$sub_no,$sub_name,$id,$for_month);*/?>

        </div>
    </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

         $('#dp_adopt,#dp_insert_user,#dp_update_user,#dp_attach,#dp_delivers_status_1').select2().on('change',function(){
        });
if ('{$this->user->branch}' ==1)
{
         $('#dp_branch').select2().on('change',function(){
        });
        }
function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }
	
  $('.pagination li').click(function(e){
        e.preventDefault();
    });
       /* function values_search(add_page){
                var values=

                {page:1, sub_no:$('#txt_sub_no').val(), sub_name:$('#txt_sub_name').val(), id:$('#txt_id').val(), bond_no:$('#txt_bond_no').val(), bond_year:$('#txt_bond_year').val(),
                 adopt:$('#dp_adopt').val(),branch:$('#dp_branch').val()};
                if(add_page==0)
                delete values.page;
                return values;
                }

 function search(){
  if($('#txt_sub_no').val() == '' && $('#txt_sub_name').val() == '' && $('#txt_id').val() == ''  && $('#txt_bond_no').val() == '' && $('#txt_bond_year').val() == '' && $('#dp_adopt').val() == '' )
       {
 danger_msg('يتوجب عليك اختيار محدد بحث واحد على الأقل','');

       }
       else
       {
        var values= values_search(1);
        get_data('{ $ get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
        }
    }
    */
       
       reBind();
        function reBind(){

    ajax_pager({
       sub_no:$('#txt_sub_no').val(), sub_name:$('#txt_sub_name').val(), id:$('#txt_id').val(), bond_no:$('#txt_bond_no').val(), bond_year:$('#txt_bond_year').val(),
                 adopt:$('#dp_adopt').val(),branch:$('#dp_branch').val(),insert_user:$('#dp_insert_user').val(),update_user:$('#dp_update_user').val(),attach:$('#dp_attach').val(),from_bond_date:$('#txt_from_bond_date').val(),to_bond_date:$('#txt_to_bond_date').val(),delivers_status:$('#dp_delivers_status_1').val(),from_delivers_date:$('#txt_from_delivers_date').val(),to_delivers_date:$('#txt_to_delivers_date').val()
        });

    }

    function LoadingData(){

    ajax_pager_data('#page_tb > tbody',{
        sub_no:$('#txt_sub_no').val(), sub_name:$('#txt_sub_name').val(), id:$('#txt_id').val(), bond_no:$('#txt_bond_no').val(), bond_year:$('#txt_bond_year').val(),
                 adopt:$('#dp_adopt').val(),branch:$('#dp_branch').val(),insert_user:$('#dp_insert_user').val(),update_user:$('#dp_update_user').val(),attach:$('#dp_attach').val(),from_bond_date:$('#txt_from_bond_date').val(),to_bond_date:$('#txt_to_bond_date').val(),delivers_status:$('#dp_delivers_status_1').val(),from_delivers_date:$('#txt_from_delivers_date').val(),to_delivers_date:$('#txt_to_delivers_date').val()
    });

    }


   function search(){
  if($('#txt_sub_no').val() == '' && $('#txt_sub_name').val() == '' && $('#txt_id').val() == ''  && $('#txt_bond_no').val() == '' && $('#txt_bond_year').val() == '' && $('#dp_adopt').val() == '' && $('#dp_insert_user').val() == ''&& $('#dp_update_user').val() == ''&& $('#dp_attach').val() == '' && $('#txt_from_bond_date').val() == '' && $('#txt_to_bond_date').val() == '' && $('#dp_delivers_status_1').val() == '' && $('#txt_from_delivers_date').val() == '' && $('#txt_to_delivers_date').val() == '')
       {
 danger_msg('يتوجب عليك اختيار محدد بحث واحد على الأقل','');

       }
       else
       {
        get_data('{$get_page_url}',{page: 1,
        sub_no:$('#txt_sub_no').val(), sub_name:$('#txt_sub_name').val(), id:$('#txt_id').val(), bond_no:$('#txt_bond_no').val(), bond_year:$('#txt_bond_year').val(),
                 adopt:$('#dp_adopt').val(),branch:$('#dp_branch').val(),insert_user:$('#dp_insert_user').val(),update_user:$('#dp_update_user').val(),attach:$('#dp_attach').val(),from_bond_date:$('#txt_from_bond_date').val(),to_bond_date:$('#txt_to_bond_date').val(),delivers_status:$('#dp_delivers_status_1').val(),from_delivers_date:$('#txt_from_delivers_date').val(),to_delivers_date:$('#txt_to_delivers_date').val()

         },function(data){
            $('#container').html(data);
            reBind();
        },'html');
        }
    }
</script>

SCRIPT;

sec_scripts($scripts);

?>