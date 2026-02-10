<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/1/14
 * Time: 1:25 PM
 */

$create_url =base_url('settings/usermenus/create');
$get_menus_url =base_url('settings/usermenus/get_menus');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>

        <ul>

            <?php if( HaveAccess($create_url,$get_menus_url)): ?>  <li><a  onclick="javascript:system_menu_get();" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>حفظ</a> </li><?php endif;?>


            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div class="form-horizontal" >
            <div class="modal-body">
                <div class="form-group col-sm-5">
                    <label class="col-sm-2 control-label"> المستخدم</label>
                    <div class="col-sm-3">
                        <select name="user_no" style="width: 350px" id="dp_user_no">
                            <option>_________</option>
                            <?php foreach($users as $row) :?>
                                <option data-branch="<?=$row['BRANCH']?>" value="<?= $row['ID'] ?>"><?= $row['EMP_NO'].': '.$row['USER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="col-sm-1 control-label"> المقر </label>
                    <div class="col-sm-2">
                        <select disabled id='dp_branch' class='form-control'>
                            <option></option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

            </div>
        </div>


        <div id="container">
            <?php echo modules::run('settings/usermenus/get_menus'); ?>
        </div>


    </div>

</div>

<?php




$scripts = <<<SCRIPT
<script>
    $(function () {

 $('#dp_user_no').select2().on('change',function(){
 
	$('#dp_branch').val( $('#dp_user_no option:selected').attr('data-branch') );

         get_data('$get_menus_url',{user:$('#dp_user_no').val()} ,function(data){

              $('#container').html(data);

        },"html");

        });


        $('#user_menus').tree();
        $.fn.tree.expandAll();


      checkBoxChanged();



    });

function checkBoxChanged(){
  $('li > span > input[type="checkbox"]', $('#user_menus')).on('change',function(e){
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


        var tbl = '#user_menus';

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


