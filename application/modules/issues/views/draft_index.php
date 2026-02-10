<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 08/08/20
 * Time: 09:01 ص
 */
$TB_NAME= 'draft';
$MODULE_NAME= 'issues';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page");
echo AntiForgeryToken();
?>

<div class="row">
<div class="toolbar">

    <div class="caption"><?=$title;?></div>
    <ul>


        <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

    </ul>

</div>



<div class="form-body">
    <div class="modal-body inline_form">
    </div>

    <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
        <div class="modal-body inline_form">



            <div class="form-group col-sm-1">
                <label class="control-label">رقم الطلب</label>
                <div>
                    <input type="text" name="request_app_serial"  id="txt_request_app_serial"  class="form-control" value="<?=$Req?>" >
                </div>
            </div>

            <div class="form-group col-sm-2">
                <label class="control-label">اسم مقدم الطلب</label>
                <div>
                    <input type="text" name="applicant_name"  id="txt_applicant_name"  class="form-control" >
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">رقم الاشتراك</label>
                <div>
                    <input type="text" name="subscriber"  id="txt_subscriber"  class="form-control" >
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">رقم الهوية</label>
                <div>
                    <input type="text" name="applicant_id"  id="txt_applicant_id"  class="form-control" >
                </div>
            </div>
 <?php
                 if($this->user->branch==1)
                 {
                 ?>
   <div class="form-group col-sm-1">
                <label class="control-label">المحافظة</label>
                <div>

                    <select name="branch" data-val="true"  id="dp_branch" class="form-control select2">
                        <option></option>
                        <?php foreach($branches as $row) :?>
                            <?php
                            if($row['NO']<>1)
                             {
                                 ?>

                                 <option value="<?= $row['NO'] ?>"?>
                                     <?= $row['NAME'] ?>
                                 </option>
                             <?php
                             }
                             ?>
                        <?php endforeach; ?>
                       </select>





                </div>
<?php
                     }
          ?>

            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">نوع المستند</label>
                <div>

                    <select name="service_type" data-val="true"  id="dp_service_type" class="form-control select2">
                       <!-- <option></option> -->
                        <?php foreach($service_type as $row) :
                         if($row['CON_NO'] == '1')
                            {
                        ?>
                            <option  value="<?= $row['CON_NO'] ?>" <?php if ($type == $row['CON_NO']){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                        <?php 
                           }
                       endforeach; ?>
                       </select>





                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">حالة الدفع</label>
                <div>

                    <select name="paid" data-val="true"  id="dp_paid" class="form-control select2">
                        <option></option>
                        <option  value="1" >غير مدفوع</option>
                        <option  value="2" >مدفوع كلي</option>
                        <option  value="3" >مدفوع جزئي</option>

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
        <?php echo '';/*modules::run($get_page_url,$page);*/?>
    </div>
</div>

</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">



if($('#txt_request_app_serial').val() !='' && $('#dp_service_type').val()!='')
{
search();
}


function search(){


       var values= {page:1,request_app_serial:$('#txt_request_app_serial').val(),applicant_name:$('#txt_applicant_name').val(),subscriber:$('#txt_subscriber').val(),applicant_id:$('#txt_applicant_id').val(),service_type:$('#dp_service_type').val(),branch:$('#dp_branch').val(),paid:$('#dp_paid').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
   var values= {page:1,request_app_serial:$('#txt_request_app_serial').val(),applicant_name:$('#txt_applicant_name').val(),subscriber:$('#txt_subscriber').val(),applicant_id:$('#txt_applicant_id').val(),service_type:$('#dp_service_type').val(),branch:$('#dp_branch').val(),paid:$('#dp_paid').val()};
              ajax_pager_data('#page_tb > tbody',values);
    }

 $(document).ready(function() {

 $('#dp_branch,#dp_service_type,#dp_paid').select2().on('change',function(){



        });




});

</script>

SCRIPT;

sec_scripts($scripts);

?>