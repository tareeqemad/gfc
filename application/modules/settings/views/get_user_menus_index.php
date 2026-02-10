<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 04/12/22
 * Time: 12:00 م
 */

$MODULE_NAME= 'settings';
$TB_NAME= 'Usermenus';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_user_menus");

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">


                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="user_no" id="dp_user_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($users as $row) :?>
                                <option data-branch="<?=$row['BRANCH']?>" data-status="<?=$row['STATUS']?>" value="<?= $row['ID'] ?>"><?= $row['EMP_NO'].': '.$row['USER_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">النظام</label>
                    <div>
                        <select  name="system_id" id="dp_system_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($systems as $row) :?>
                                <option value="<?=$row['ID']?>"><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">المقر</label>
                    <div>
                        <select disabled name="branch" id="dp_branch" class="form-control" >
                            <option value=""></option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الحالة</label>
                    <div>
                        <select disabled name="status" id="dp_status" class="form-control" >
                            <option value=""></option>
                            <option value="1">فعال</option>
                            <option value="0">غير فعال</option>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button style="display: none" type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">

        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();

    $(function () {
    
        $('#dp_user_no').select2().on('change',function(){
            $('#dp_branch').val( $('#dp_user_no option:selected').attr('data-branch') );
            $('#dp_status').val( $('#dp_user_no option:selected').attr('data-status') );
            if ( $('#dp_status').val()==1 ){
                $('#dp_status').css("background-color","#7bbba0");
            }else {
                $('#dp_status').css("background-color","#ff9290");
            }
        });
    
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function search(){
        var user_no= $('#dp_user_no').val();
        var system_id= $('#dp_system_id').val();
        var values= {user_no:user_no, system_id:system_id };
        get_data('{$get_page_url}/'+user_no+'/'+system_id,values ,function(data){
            $('#container').html(data);
        },'html');
    }

    </script>
SCRIPT;
sec_scripts($scripts);
?>
