<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/01/15
 * Time: 08:32 ص
 */
$count = 0;
?>
<?= AntiForgeryToken() ?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>

            </ul>

        </div>

        <div class="form-body">
            <fieldset>
                <legend>بحـث</legend>
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم الصنف  </label>
                        <div>
                            <input type="text"  id="txt_id"   class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> اسم الصنف</label>
                        <div>
                            <input type="text"     id="txt_name" class="form-control">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">

                    <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>

                    <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

                </div>
            </fieldset>


            <div id="msg_container"></div>


            <div class="tbl_container">
                <form class="form-vertical" id="project_items_from" method="post" action="<?=base_url('projects/projects/prices')?>" role="form" novalidate="novalidate">
                    <div id="container">
                        <?php echo modules::run('projects/projects/get_items_page',$page); ?>
                    </div>
                    <div class="modal-footer">
                        <?php if(HaveAccess(base_url('projects/projects/prices/create'))): ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif;?>

                    </div>
                </form>

            </div>

        </div>

    </div>
<?php
$exp = float_format_exp();
$store_item_url = base_url('stores/classes/public_index');
$delete_url = base_url('projects/projects/delete_price');
$get_page_url = base_url('projects/projects/get_items_page');
$shared_js = <<<SCRIPT

        var count = 1;

       $(function(){

          count = $('input[name="class_id[]"]').length;
          count=count+1;
            reBind();
        });

       function reBind(){

            delete_action();

            $('input[name="class_id_name[]"]').click(function(){
                 _showReport('$store_item_url/'+$(this).attr('id') );
            });


       }

   function do_search(){

        get_data('{$get_page_url}',{page: 1,id : $('#txt_id').val(),name:$('#txt_name').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }


  $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
         if($('input:text:visible',$('#projectTbl')).filter(function() { return this.value == ""; }).length <= 0){
      if(confirm('هل تريد حفظ البيانات ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                        setTimeout(function(){

                window.location.reload();

                }, 1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        }
    });

    function addRow(){

    if($('input:text:visible',$('#projectTbl')).filter(function() { return this.value == ""; }).length <= 0){

        var tr = $('#projectTbl > tbody tr:first');
        var html  = tr.html();

        html =replaceAll('_0','_'+count,html);
        $('#projectTbl tbody').append('<tr>'+html+'</tr>');


        tr = $('#projectTbl > tbody tr:last');
        $('input',$(tr)).val('');
        $('.v_balance',$(tr)).text('');
        $('input[name="ser[]"]',$(tr)).val(0);
        $('td:last',$(tr)).html('<a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>');
        $('td:first',$(tr)).html(replaceAll('1',count,$('td:first',$(tr)).html()));
        reBind();
        count++;
        }
    }


SCRIPT;



$edit_script = <<<SCRIPT
    <script>
 {$shared_js}
     function delete_details(a,id){
     if(confirm('هل تريد حذف البند ؟!')){

          get_data('{$delete_url}',{id:id},function(data){
                     if(data == '1'){
                        $(a).closest('tr').remove();

                       }else{
                             danger_msg( 'تحذير','فشل في العملية');
                       }
                });
         }
     }

  </script>
SCRIPT;

sec_scripts($edit_script);



?>