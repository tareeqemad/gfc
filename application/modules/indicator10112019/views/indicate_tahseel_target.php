<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 24/07/18
 * Time: 09:43 ص
 */
$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_target';
$backs_url=base_url("$MODULE_NAME/$TB_NAME/tahseel_target_index"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_target_tahseel");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_tahseel");
$rs=($isCreate)? array() : $page_rows[0];
//$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_target_tahseel");


/*if(date("m")-1 == 0)
{
$for_month=(date("Y")-1).'12';
}
else
$for_month=date("Ym")-1;
*/
if( $page_rows[0]['FOR_MONTH'] == '')
$for_month=date("Ym",strtotime("-1 month"));
else
$for_month=$page_rows[0]['FOR_MONTH'];

?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

<ul>

    <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
    <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

</ul>

</div>



<div class="form-body">
    <div class="modal-body inline_form">
    </div>

    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
        <div class="modal-body inline_form">

            <div class="form-group col-sm-1">
                <label class="control-label">الشهر</label>
                <div>
                    <input type="number" name="for_month"  id="txt_for_month" value="<?php echo $for_month;?>" class="form-control" data-val="true"  data-val-required="حقل مطلوب" readonly >
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">المبلغ المطلوب</label>
                <div>
                    <input type="number" name="val"  id="txt_val" value="<?php echo @$page_rows[0]['VAL'];?>" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                </div>
            </div>

           


        </div>

        <div class="modal-footer">

         



        </div>
        <div id="msg_container"></div>

        <div id="container">
<?=modules::run($get_page_url,$for_month,$isCreate);?>
        </div>
    </form>



</div>

</div>


<?php
$month=date("Ym");
$scripts = <<<SCRIPT

<script type="text/javascript">

   $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(parseInt(data)>=1){


                    var values= {page:1,};


  

success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                   $('#save_btn_show').removeClass("hidden");
                    $('#adopt_btn').removeClass("hidden");
                   $('#unadopt_btn').addClass("hidden");

                    get_to_link(window.location.href);
                }else{
                     danger_msg('لم يتم الحفظ');
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);

});
 




</script>

SCRIPT;

sec_scripts($scripts);

?>