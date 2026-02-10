<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 01/03/20
 * Time: 12:50 م
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Procmangment';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$page=1;
echo AntiForgeryToken();
?>
    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>

                <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>
        </div>

        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-2">
                        <label class="control-label">مكتب التحصيل</label>
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
                        <label class="control-label">رقم الاشتراك</label>
                        <div>
                            <input type="text"
                                   name="sub_no"
                                   id="txt_sub_no"
                                   class="form-control ">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">شهر الفاتورة</label>
                        <div>
                            <input type="text" placeholder="202003"
                                   name="for_month"
                                   id="txt_for_month"
                                   class="form-control ">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">نوع الاجراء</label>
                        <div>
                            <select name="proc_type" id="dp_proc_type" class="form-control sel2">
                                <?php foreach($pro_type as $row) :?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <br>
                </div>
            </form>


            <div class="modal-footer">
                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            </div>
            <div id="msg_container"></div>

            <div id="container">
            </div>
        </div>

    </div>


    <div class="modal fade" id="myModal">
        <div id="myModal" role="dialog">
            <div class="modal-dialog">

                <div id="SSS" class="modal-content">
                    <div class="modal-header">
                        <h3 id="title" class="modal-title">الشيكات</h3>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                                <div class="row" id="div_details">

                                </div>

                                <div class="modal-footer">
                                    <?php
                                    //if (  HaveAccess($post_url)   ) : ?>
                                    <?php //endif; ?>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>

                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<?php


$scripts = <<<SCRIPT

<script>



</script>

SCRIPT;

sec_scripts($scripts);
?>