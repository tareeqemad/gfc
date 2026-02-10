<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/06/18
 * Time: 11:56 ص
 */

$create_url =base_url('indicator/manage_user_tree/create');
$get_menus_url =base_url('indicator/manage_user_tree/get_tree');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>

        <ul>

            <?php if( HaveAccess($create_url,$get_menus_url)): ?>  <li><a  onclick="system_menu_get();" href="javascript:"><i class="glyphicon glyphicon-saved"></i>حفظ</a> </li><?php endif;?>


            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div class="form-horizontal" >
            <div class="modal-body">
                <div class="form-group col-sm-8">
                    <label class="col-sm-2 control-label"> المستخدم</label>
                    <div class="col-sm-5">
                        <select name="user_no" style="width: 250px" id="dp_user_no">
                            <option></option>
                            <?php foreach($users as $row) :?>
                                <option value="<?= $row['ID'] ?>"><?= $row['USER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
            </div>
        </div>


        <div id="container">
            <?php echo modules::run('indicator/manage_user_tree/get_tree'); ?>
        </div>


    </div>

</div>

<?php




$scripts = <<<SCRIPT
<script>
    $(function () {

 $('#dp_user_no').select2().on('change',function(){

         get_data('$get_menus_url',{user:$('#dp_user_no').val()} ,function(data){

              $('#container').html(data);

        },"html");

        });


        $('#indicator_tree').tree();
        $.fn.tree.expandAll();


      checkBoxChanged();



    });

function checkBoxChanged(){
  $('li > span > input[type="checkbox"]', $('#indicator_tree')).on('change',function(e){
            e.preventDefault();
            var checkbox = $(this);
            var is_checked = $(checkbox).is(':checked');

            if(is_checked) {
                $(checkbox).parents('li').children('span').children('input').prop('checked', true);
                $(checkbox).parents('li').children('span').children('input').attr('checked',true);


            } else {
               $(checkbox).closest('li').find('span>input').prop("checked", false);
                $(checkbox).closest('li').find('span>input').attr("checked", false);



            }


        });

}


    function system_menu_get(){


        var tbl = '#indicator_tree';

        var val = '0';
        var valDel = '0';
 
        $(tbl + ' input[type=checkbox]:checked').not('input[data-checked]').each(function (i) {
            val  =  val +','+$(this).val();

        });
        
        $(tbl + ' input[data-checked]:checkbox:not(:checked)').each(function (i) {
            valDel  =  valDel +','+$(this).val();

        });
 
        
       get_data('$create_url',{menu_no: val, deleted : valDel,__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val(),user_id:$('#dp_user_no').val()} ,function(data){

            if(data =='1')
                success_msg('رسالة','تم حفظ السجلات بنجاح ..');
            reload_Page();
        },"html");


    }



</script>
SCRIPT;

sec_scripts($scripts);



?>


