
<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/01/18
 * Time: 09:16 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_refresh");
$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_refresh");
$adopt_all_url= base_url("$MODULE_NAME/$TB_NAME/adopt_all_refresh");
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_refresh");
$page=1;
echo AntiForgeryToken();
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

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <?php  if( HaveAccess($save_all_url)):  ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php  endif; ?>
                <?php  if( HaveAccess($adopt_all_url)):  ?>
                    <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-danger">اعتماد و تحديث الخطة الشهرية</button>
                <?php  endif; ?>



            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?=modules::run($get_page_url,$page);?>
            </div>
        </form>



    </div>

</div>



<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


 $('.pagination li').click(function(e){
        e.preventDefault();
    });

//search();



          function search(){


       var values= {page:1,branch:$('#dp_branch').val(),from_month:$('#dp_from_month').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
     var values= {page:1,branch:$('#dp_branch').val(),from_month:$('#dp_from_month').val()};
          ajax_pager_data('#page_tb > tbody',values);
    }
    reBind();
 $(document).ready(function() {

 $('#dp_branch').select2().on('change',function(){

        });
        $('#dp_finance').select2().on('change',function(){

        });

            $('#dp_type').select2().on('change',function(){

        });
              $('#dp_class_name').select2().on('change',function(){

        });
        $('#dp_from_month').select2().on('change',function(){

        });

                       $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(parseInt(data)>=1){
                   success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                  // alert(data);

                      get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);

                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);

});


});



  function reBind(){


            $('select[name="from_month[]"]').select2().on('change',function(){

                //  checkBoxChanged();


            });


        }

        function return_adopt(type) {

    if (type == 1) {

    var url = '{$adopt_all_url}';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
         var val = [];
         var ser_plan=[];
         var for_month=[];
        $('input[name="ischeck[]"]').each(function (i) {
        if($(this).attr('data-check')=='1')
        {

           val[i]=$(this).attr('data-ser');
           ser_plan[i]=$(this).attr('ser_plan');
           for_month[i]=$(this).attr('for_month');
        }


    });


        if(val.length > 0){
            if(confirm('سوف يتم اعتماد جميع السجلات المدخلة هل ترغب بالاعتماد؟!')){

              get_data(url,{ser: val, ser_plan: ser_plan,for_month:for_month},function(data){
                 success_msg('رسالة','تم عملية الاعتماد بنجاح ..');
               window.location.reload();
            });

            }
        }else
            alert('لايوجد سجلات ممكن اعتمادها');



}


}
/////////////
   /* $('.group-checkable').click(function () {
      $('input[name="ischeck[]"]').each(function (i) {
        if($(this).attr('data-check')=='1')
        {
 //$('input:checkbox').prop('checked', this.checked);
        $(this).prop('checked',true);


        }
        else
         $(this).prop('checked',false);


    });

    });
*/
</script>

SCRIPT;

sec_scripts($scripts);

?>
