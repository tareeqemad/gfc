<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/12/19
 * Time: 09:37 ص
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Branchs_adopt';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
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


            <form class="form-vertical" id="<?=$TB_NAME?>_form"  >
                <fieldset  class="field_set">
                    <legend >بيانات المشتركين</legend>



                    <div class="modal-body inline_form">



                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم الكشف</label>
                            <div>
                                <input type="text"  placeholder="رقم الكشف "
                                       name="disclosure_ser"
                                       id="txt_disclosure_ser" class="form-control"  >
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
                <?php //echo  modules::run($get_page,$page); ?>
            </div>


        </div>

    </div>

    <!----------------------------------->
    <div id="modalSub" class="modal fade"  role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="title" class="modal-title">الاشتراكات</h3>
                </div>
                <div id="container_sub">
                </div>

                </div>
        </div>
    </div>
    <!-------------------------------------------->



<?php


$scripts = <<<SCRIPT

<script>



</script>

SCRIPT;

sec_scripts($scripts);
?>