<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 10/08/20
 * Time: 09:23 ص
 */
$MODULE_NAME= 'issues';
$TB_NAME= 'draft';
$count = $offset;

$report_url =base_url("JsperReport/showreport?sys=Trading/other");//'https://gsx.gedco.ps/gfc/jsperreport/showreport?sys=Trading/other';
$report_url = str_replace('trading', 'Trading', $report_url);
//base_url("JsperReport/showreport?sys=Trading/other");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$unadopt_url = base_url("$MODULE_NAME/$TB_NAME/unadopt");
$print_url = base_url("$MODULE_NAME/$TB_NAME/print");
//https://stackoverflow.com/questions/303956/select-a-which-href-ends-with-some-string
echo AntiForgeryToken();
?>
<input type="hidden" id="adoptaction" value="<?=$adopt_url?>">
<input type="hidden" id="unadoptaction" value="<?=$unadopt_url?>">
<input type="hidden" id="report_url" value="<?=$report_url?>">
<div class="tbl_container">
    <table class="table" id="draft_page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الطلب</th>
            <th>اسم مقدم الطلب</th>
            <th>اسم المشترك</th>
            <th>رقم الاشتراك</th>
            <th>رقم الهوية</th>
            <th>تاريخ اعتماد المساهمة</th>
            <th>اجمالي المساهمة</th>
            <th>عدد الأقساط</th>
            <th>قيمة القسط</th>
			<th>إجمالي المتبقي</th>
            <th>(مدفوع/غير مدفوع)حالة الدفع</th>
            <?php if(HaveAccess($print_url)) {?>
            <th>طباعة كمبيالة</th>
            <?php } ?>
            <th>ارفاق كمبيالة</th>
            <?php if(HaveAccess($adopt_url)) {?>
            <th>اعتماد</th>
            <?php } ?>
            <?php if(HaveAccess($unadopt_url)) {?>
            <th>الغاء الإعتماد</th>
            <?php } ?>

        </tr>
        </thead>
        <tbody>
        <?php foreach($page_rows as $row) :

        ?>
		<tr>
       <td><?=$count?></td>
            <td><?=@$row['REQUEST_APP_SERIAL']?></td>
            <td><?=@$row['APPLICANT_NAME']?></td>
            <td><?=@$row['SUBSCRIBER_NAME']?></td>
            <td><?=@$row['REQUEST_SUBSCRIBER']?></td>
            <td><?=@$row['APPLICANT_ID']?></td>
            <td><?=@$row['ENTRY_DATE']?></td>
            <td><?=@$row['THE_VALUE']?></td>
            <td><?=@$row['INSTALL_CNT']?></td>
            <td><?=@$row['CALC']?></td>
			<td><?=@$row['TOTALPAID']?></td>
            <td><?=@$row['IS_PAID_NAME']?></td>
            <?php if(HaveAccess($print_url)) {?>
            <td><button type="button" id="btn_print_draft_<?= $count ?>" class="btn btn-success" data-ser1='<?=@$row['REQUEST_APP_SERIAL']?>'  name="print_draft[]">طباعة</button></td>
            <?php } 
             ?>
            <td>
                 <?php
                  if(@$row['ISADOPT'] ==0 ){
                  if(HaveAccess($adopt_url)) {
                  echo modules::run('attachments/attachment/indexInline',@$row['REQUEST_APP_SERIAL'],'draft',1);
                   }
                  else
                   {
                  echo modules::run('attachments/attachment/indexInline',@$row['REQUEST_APP_SERIAL'],'draft',0);
                    }
                    
                 }
                 else if(@$row['ISADOPT'] ==1 ){ 
                echo modules::run('attachments/attachment/indexInline',@$row['REQUEST_APP_SERIAL'],'draft',0); 
                 }?>
                         
               </td>
<?php if(HaveAccess($adopt_url)) {?>
            <td><?php if(@$row['ISADOPT'] ==0 ){?><button type="button" id="btn_adopt_draft_<?= $count; ?>" class="btn btn-warning" data-ser1='<?=@$row['SER']?>'  name="adopt_draft[]">اعتماد</button><?php }?> </td><?php }?>
<?php if(HaveAccess($unadopt_url)) {?>
            <td><?php if(@$row['ISADOPT'] !=0 ){?><button type="button" id="btn_unadopt_draft_<?= $count; ?>" class="btn btn-danger" data-ser2='<?=@$row['SER']?>'  name="unadopt_draft[]">الغاء الإعتماد</button><?php }?> </td><?php }?>


            <?php $count++ ?>
        </tr>
        <?php endforeach;?>



        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>


<script>





    $(document).ready(function() {

        reBind_pram(0);


        function reBind_pram(cnt){

            $('button[name="print_draft[]').on('click',  function (e) {
          
	 var rep_url = $("#report_url").val()+'&report_type=pdf&report=bill_of_exchange&p_request_app_serial='+$(this).attr('data-ser1')+'';
        _showReport(rep_url);
            });


$('button[name="adopt_draft[]').on('click',  function (e) {

                //alert($("#report_url").val());
                var redirect=$("#adoptaction").val();
                var currentRow=$(this).closest("tr");
                var SUB_NO=currentRow.find("td:eq(4)").text(); 
                var SUB_NAME=currentRow.find("td:eq(3)").text(); 
                var ID =currentRow.find("td:eq(5)").text(); 
                var SER_ACTION=currentRow.find("td:eq(1)").text(); 
                     


               $.ajax({
                      method: "POST",
                      url: redirect,
                      data: { SUB_NO: SUB_NO, SUB_NAME:SUB_NAME , ID:ID , SER_ACTION:SER_ACTION },
                      success: function( data ) {
              
if(isNaN(data))

danger_msg('لم يتم الاعتماد عدد الأقساط غير مساوي لعدد المرفقات!!');

else
{


                    // $(this).parent().find('.btn-warning').hide(); 
                     // $(this).parent().find('a').removeAttr("href");
                   //   e.preventDefault();
                     currentRow.find("td:eq(12)").css('visibility', 'hidden');
                     currentRow.find("td:eq(13)").css('visibility', 'hidden');
                      success_msg('رسالة','تم حفظ البيانات بنجاح ..');
}
                        },
                    error: function( data ) {
                    danger_msg('حدث خطأ!!');

                     }
            });               




            });


$('button[name="unadopt_draft[]').on('click',  function (e) {

                //alert($("#unadoptaction").val());
                var redirect=$("#unadoptaction").val();
                var currentRow=$(this).closest("tr");
                var SUB_NO=currentRow.find("td:eq(4)").text(); 
                var SUB_NAME=currentRow.find("td:eq(3)").text(); 
                var ID =currentRow.find("td:eq(5)").text(); 
                var SER_ACTION=currentRow.find("td:eq(1)").text(); 
                     currentRow.find("td:eq(12)").css('visibility', 'hidden');
                     currentRow.find("td:eq(13)").css('visibility', 'hidden');
                     currentRow.find("td:eq(14)").css('visibility', 'hidden');

               $.ajax({
                      method: "POST",
                      url: redirect,
                      data: { SUB_NO: SUB_NO, SUB_NAME:SUB_NAME , ID:ID , SER_ACTION:SER_ACTION },
                      success: function( data ) {
                      $(this).parent().find('.btn-danger').hide(); 
                      $(this).parent().find('a').removeAttr("href");
                      e.preventDefault();
                     
                      success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                        },
                    error: function( data ) {
                    danger_msg('حدث خطأ!!');

                     }
            });               




            });


$('a[href$="javascript:;"]').on('click',  function (e) {
//alert();
 //$('table#attachTbl tfoot').remove();
//$("table#attachTbl tfoot").css("display", "none");
//$("table#attachTbl tfoot").hide();
e.preventDefault();
$(this).addClass('disabled');

          });


        }


    });

</script>

