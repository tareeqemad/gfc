<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 15/07/19
 * Time: 01:06 م
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'carRequest';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$post_url= base_url("$MODULE_NAME/$TB_NAME/update");
$page=1;
$select_url= base_url('payment/cars/public_select_car');
$driver_url = base_url('payment/carMovements/public_select_driver') ;

echo AntiForgeryToken();
?>

    <div class="row" xmlns="http://www.w3.org/1999/html">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
                <li><a onclick="javascript:update_request();" href="javascript:;" ><i class="glyphicon glyphicon-plus"></i>اسناد لسائق</a> </li>

            </ul>

        </div>



        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <div id="container">
                <?php echo  modules::run('payment/carRequest/get_list',$page); ?>
            </div>


            </div>
        <!------------------طلب سيارة----------------->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="title" class="modal-title">اسناد لسائق</h3>
                    </div>
                    <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form" >

                    <div class="modal-body">

                        <div class="row">
                            <input type="hidden" name="assign_work_no" id="h_txt_assign_work_no">
                            <br>
                            <label class="col-sm-1 control-label">صاحب العهدة </label>
                            <div class="col-sm-9">

                                <input type="hidden"
                                       name="car_id" id="h_txt_car_id_name"
                                       class="form-control">

                                <input type="text"
                                       data-val="true"
                                       name="car_owner"
                                       readonly
                                       id="txt_car_id_name"
                                       class="form-control">

                            </div>

                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label"> السائق</label>
                            <div class="col-sm-9">
                                <input type="hidden"
                                       name="driver_id"
                                       id="h_txt_driver_name" class="form-control">

                                <input type="text"
                                       data-val="true"
                                       readonly
                                       id="txt_driver_name"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">نوع الحركة</label>
                            <div class="col-sm-9">

                                <select
                                    data-val="true"
                                    name="movment_type"
                                    id="dp_movement_type"
                                    class="form-control">
                                    <option></option>
                                    <?php foreach ($movement_type as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="row">
                            <br>
                            <label class="col-sm-1 control-label">ملاحظات</label>
                            <div class="col-sm-9">
                                <textarea data-val-required="حقل مطلوب"
                                          class="form-control" name="notes" rows="7"
                                          id="txt_notes"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        </div>



                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-------------------------------------------->

        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    function update_request(){
        $('#myModal').modal();
        var x='0';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];

        $(tbl + ' .checkboxes:checked').each(function (i) {
            x = x+','+$(this).val();
        });

        $('#h_txt_assign_work_no').val(x);
    }


   $('#txt_car_id_name').click(function (e) {
          _showReport('{$select_url}/'+$(this).attr('id')+'/');
    });


    $('#txt_driver_name').click(function (e) {
            _showReport('{$driver_url}/'+$(this).attr('id')+'/');

    });

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
            console.log(data);
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                }else{
                    danger_msg('تحذير..',data);
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