<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 26/01/20
 * Time: 01:12 م
 */
$MODULE_NAME = 'training';
$TB_NAME = 'employeeTraining';
$save_emp_url =  base_url("$MODULE_NAME/$TB_NAME/saveEmp");
$get_url = base_url("$MODULE_NAME/$TB_NAME/public_get_trainee_courses_trainee");
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a href="#" onclick="javascript:saveEmps()" ><i class="glyphicon glyphicon-plus"></i>اضافة</a> </li>


        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">

            <div class="input-group col-sm-4">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>
                <input type="text" id="search-tbl" data-set="accountsTbl" class="form-control" placeholder="بحث">

            </div>
        </div>
            <div id="container">
                <form class="form-horizontal" id="emps_form" method="post" action="<?= $save_emp_url ?>" role="form" >
                    <table class="table selected-red" id="accountsTbl" data-container="container">
                        <input type="hidden" name="emps_ser" id="h_txt_emps_ser">
                        <input type="hidden" name="course_ser" value="<?= $this->input->get('id'); ?>" id="h_txt_course_ser">
                        <thead>
                        <tr>
                            <th></th>
                            <th>الرقم الوظيفي</th>
                            <th>الاسم</th>
                            <th>الادارة</th>
                            <th>المقر</th>

                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($rows as $row) : ?>
                            <tr  id="tr_<?=$row['EMP_NO']?>"  >
                                <td><input  onclick="javascript:select_account(<?=$row['EMP_NO']?>);"type='checkbox' name="case" class='checkboxes' value='<?=$row['EMP_NO']?>'></td>
                                <td><?= $row['EMP_NO'] ?></td>
                                <td><?= $row['USER_NAME'] ?></td>
                                <td><?= $row['STRUCTURE_NAME'] ?></td>
                                <td><?= $row['BRANCH_NAME'] ?></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>
                </form>


            </div>



    </div>
</div>



<?php
$scripts = <<<SCRIPT

<script>


      $(function () {

        $('#accountsModal').on('shown.bs.modal', function () {
            $('#txt_acount_name').focus();
        });


    });

     function select_account(id){
            var x=0;
            var tbl = '#accountsTbl';
            var container = $('#' + $(tbl).attr('data-container'));
            var val = [];

            $(tbl + ' .checkboxes:checked').each(function (i) {
                x = x+','+$(this).val();

            });

            $('#h_txt_emps_ser').val(x);
    }



    function saveEmps(){
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $('#emps_form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    parent.$('#report').modal('hide');
                    reload_div($('#h_txt_course_ser').val());
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
    }

    function reload_div(id){
        get_data('{$get_url}/{$this->input->get('id')}',{},function(data){
            parent.$('#container_emp').html(data);
        },'html');
    }


</script>

SCRIPT;

sec_scripts($scripts);



?>

