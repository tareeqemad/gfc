<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 20/04/21
 * Time: 11:56 ص
 */

$MODULE_NAME= 'Covid';
$TB_NAME= 'Covid';


$back_url = base_url("$MODULE_NAME/$TB_NAME");
$creates_url = base_url("$MODULE_NAME/$TB_NAME/create");


?>


    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>
            <ul>
                <?php if (HaveAccess($creates_url)): ?>
                <li><a href="<?= $creates_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
                <?php endif; ?>
            </ul>
        </div>

    </div>


    <div class="form-body">

        <div id="container">
            <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?php echo $creates_url ?>" role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">

                    <fieldset class="field_set">
                        <legend>زائر جديد</legend>
						<br>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">رقم الهوية</label>
                            <div class="col-sm-2">
                                <input type="text" name="id"  id="txt_id" class="form-control">
                            </div>
							
							<label class="col-sm-1 control-label">الاسم</label>
                            <div class="col-sm-2">
                                <input type="text" readonly name="name"  id="txt_name" class="form-control">
                            </div>
							
                            <label class="col-sm-1 control-label">رقم المحمول</label>
                            <div class="col-sm-2">
								<input type="text" name="mobile" placeholder="0599000000" id="txt_mobile" class="form-control">
                            </div>
							
							<div id="result">
								<label class="col-sm-1 control-label">النتيجة</label>
								<div class="col-sm-2">
									<span id="true" class="badge badge-4">مصاب / مخالط</span>
									<span id="false" class="badge badge-2">سليم</span>
								</div>
                            </div>
                        </div>

                        <div class="modal-footer">
						 <?php
                                if (  HaveAccess($creates_url)  ) : ?>
                                    <button type="submit" data-action="submit" class="btn btn-primary">استعلام</button>
                                <?php endif; ?>
                        </div>
                    </fieldset>






                </div>

            </form>

            <?php
            //echo modules::run("$MODULE_NAME/documents/public_get_group_receipt",$ser);
            ?>


        </div>
    </div>






<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

</script>

SCRIPT;
sec_scripts($scripts);
?>