<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/12/19
 * Time: 09:15 ص
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Collection_companies';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$page=1;
echo AntiForgeryToken();
?>
    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>

                <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
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
                        <input type="hidden" value="<?php echo $this->user->branch;?>" name="branch_no" id="txt_branch_no">

                        <label class="control-label">رقم الترخيص</label>
                        <div>
                            <input type="text"
                                   name="license_no"
                                   id="txt_license_no"
                                   class="form-control ">
                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم الشركة</label>
                        <div>
                            <input type="text"
                                   name="company_name"
                                   id="txt_company_name"
                                   class="form-control ">
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
                    <h3 id="title" class="modal-title">العاملين في مكتب التحصيل</h3>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <form id="test_form" method="post" action="">

                            <div class="row" id="div_employees">

                            </div>

                            <div class="modal-footer">

                            </div>


                        </form>

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