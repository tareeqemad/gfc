<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 02/02/2022
 * Time: 12:43 PM
 */

$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';
$update_operation_no= base_url("$MODULE_NAME/$TB_NAME/update_operation_no");

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if( 0 and HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>

<div class="form-body">
    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form"  method="post" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <div class="col-md-12">
                    <div class="form-group col-sm-2">

                        <label class="control-label">الموظف</label>
                        <div>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                                <option value="0">_________</option>
                                <?php foreach($emp_no_cons as $row) :?>
                                    <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم العملية</label>
                        <div>
                            <input type="text" name="operation_no"  id="txt_operation_no" class="form-control">
                        </div>
                    </div>

                    <br>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <?php if(HaveAccess($update_operation_no)){ ?>
                <button type="button" onclick="javascript:updateOperationNo();" class="btn btn-success"> حفظ</button>
                <?php } ?>
            </div>
        </form>
    </div>
</div>