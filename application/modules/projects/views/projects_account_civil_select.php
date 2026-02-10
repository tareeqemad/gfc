<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$get_url =base_url('projects/projects/projects_account_select_civil_page');
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div class="modal-body inline_form">
            <form  id="project_accounts_form" method="get" action="<?=$get_url?>" role="form" novalidate="novalidate">

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم المشروع</label>
                    <div>
                        <input type="text"  name="project_ser" id="txt_project_ser"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الحساب </label>
                    <div>
                        <input type="text"  name="project_id" id="txt_project_id"   class="form-control">
                    </div>
                </div>



                <div class="form-group col-sm-3">
                    <label class="control-label"> اسم الحساب</label>
                    <div>
                        <input type="text"  name="project_account_name"    id="txt_project_account_name" class="form-control">
                    </div>
                </div>



                <div class="form-group col-sm-3">
                    <label class="control-label">&nbsp;</label>
                    <div>
                        <button type="button" onclick="javascript:project_accounts_search();" class="btn btn-success">بحث</button>

                        <button type="button" onclick="javascript:clearForm_any($('#project_accounts_form'));project_accounts_search();" class="btn btn-default">تفريغ الحقول</button>
                    </div>
                </div>
            </form>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('projects/projects/public_select_project_civil_accounts_page',$page,$type); ?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT

<script>

     $(function(){
        reBind();
     });


    function reBind(){

     ajax_pager({type : '{$type}',id : $('#txt_project_id').val(),name:$('#txt_project_account_name').val(),project_ser:$('#txt_project_ser').val()});

    }

    function LoadingData(){

         ajax_pager_data('#accountsTbl > tbody',{type : '{$type}',id : $('#txt_project_id').val(),name:$('#txt_project_account_name').val(),project_ser:$('#txt_project_ser').val()});

    }


   function project_accounts_search(){

        get_data('{$get_url}',{page: 1,type : '{$type}',id : $('#txt_project_id').val(),name:$('#txt_project_account_name').val(),project_ser:$('#txt_project_ser').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }

    function account_select(name,id,project_tec_code){
        var str='{$txt}';
  
if(isNaN(str.charAt(str.length - 1)))
    {
         parent.$('#txt_civil_customer_accounts_name').val(name);
         parent.$('#h_civil_customer_accounts_name').val(id);
         parent.$('#txt_civil_project_tec_code').val(project_tec_code);
    }
else
       {
         parent.$('#txt_civil_customer_accounts_name'+{$txt}).val(name);
         parent.$('#h_civil_customer_accounts_name'+{$txt}).val(id);
         parent.$('#txt_civil_project_tec_code'+{$txt}).val(project_tec_code);
         }
          if (typeof parent.project_order_id == 'function') {
        parent.project_order_id(project_tec_code);
}
         parent.$('#report').modal('hide');

    }

</script>

SCRIPT;

sec_scripts($scripts);



?>

