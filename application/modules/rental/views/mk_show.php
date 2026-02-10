<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/08/17
 * Time: 12:45 م
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'NAME__';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url=base_url("$MODULE_NAME/$TB_NAME/POST___");
$HaveRs=0;
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['ID']:''?>" readonly id="txt_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NUM']:''?>" name="num" id="txt_num" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الفرع </label>
                    <div>
                        <select name="branch" id="dp_branch" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="clear: both"></div>


            </div>

            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && ($isCreate || ( $adopt==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();

</script>
SCRIPT;
sec_scripts($scripts);
?>
