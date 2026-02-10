<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 28/12/19
 * Time: 11:22 ص
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Pay_order';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$pay_url = base_url("$MODULE_NAME/$TB_NAME/pay");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>


        <div class="form-body">
            <div class="modal-body inline_form">
            </div>


            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <fieldset  class="field_set">
                    <legend >الاستعلام عن الاشتراكات للدفع</legend>

                    <div class="modal-body inline_form">



                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم الاشتراك </label>
                            <div>
                                <input type="text"  placeholder="رقم الاشتراك "
                                       name="sub_no"
                                       id="txt_sub_no" class="form-control"  >
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">اسم الشركة</label>
                            <div>
                                <select name="company_no" id="dp_company_no" class="form-control sel2">
                                    <option></option>
                                    <?php foreach($company_cons as $row) :?>
                                        <option  value="<?= $row['SER'] ?>"><?= $row['SER'].": ".$row['COMPANY_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <?php
                            if($this->user->branch==1)
                            { ?>
                                <label class="control-label"> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($branches as $row) :?>
                                        <?php
                                        if($row['NO']<>1)
                                        { ?> <option value="<?= $row['NO'] ?>" > <?= $row['NAME'] ?> </option>
                                        <?php
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                </select>

                            <?php
                            } else {?>
                                <input type="hidden" value="<?php echo $this->user->branch;?>" name="branch_no" id="txt_branch_no">
                            <?php }?>
                        </div>


                        <br>
                    </div>


                </fieldset>


            </form>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php // echo  modules::run($get_page,$page); ?>

            </div>
        </div>

    </div>

    <!----------------------------------->
    <div id="Details" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="title" class="modal-title">أمر دفع</h3>
                </div>
                <form class="form-horizontal" id="<?= $TB_NAME ?>_form2" method="post" action="<?php echo $pay_url ?>" role="form" >
                    <div class="modal-body">
                        <div class="modal-body">

                            <div class="row">
                                <label class="col-sm-3 control-label"> </label>
                                <div class="col-sm-3">
                                        <div class="radio-list">
                                            <label class="radio-inline">
                                                <input type="radio" name="pay_type" id="pay_type1" value="2" >  نسبة </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="pay_type" checked id="pay_type2" value="1"> كمية </label>
                                        </div>
                                </div>

                            </div>

                            <div class="row">
                                <input type="hidden" name="subs_no" id="h_txt_subs_no">
                                <input type="hidden" name="for_month" id="h_txt_for_month">
                                <input type="hidden" name="net_to_pay" id="h_txt_net_to_pay">
                                <br>
                                <label class="col-sm-3 control-label">قيمة المبلغ</label>
                                <div class="col-sm-3">
                                    <input type="text"
                                           data-val="true"
                                           name="pay_value"
                                           id="txt_pay_value"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <br>
                                <label class="col-sm-3 control-label">التاريخ المسموح بالدفع</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                           data-type="date"  data-date-format="DD/MM/YYYY"
                                           data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                                           data-val="true"
                                           name="pay_date"
                                           id="txt_pay_date"
                                           class="form-control">
                                    <br>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ</button>
                            </div>


                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-------------------------------------------->

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

 $(document).ready(function() {

		$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    $('#Details').modal('hide');

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

</script>

SCRIPT;
sec_scripts($scripts);
?>