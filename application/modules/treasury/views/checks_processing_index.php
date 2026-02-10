<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:35 ص
 */
$create_url=base_url('payment/checks_portfolio/index?type=1');
$delete_url=base_url('treasury/checks_processing/delete');
$get_page_url = base_url('treasury/checks_processing/get_page');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php  if( HaveAccess($delete_url)):  ?><li><a  onclick="javascript:user_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li> <?php endif; ?>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الشيك</label>
                    <div class="">
                        <input type="text" id="txt_check_id"  class="form-control "/>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">  البنك  </label>
                    <div class="">
                        <select name="check_bank_id" id="dp_check_bank_id" class="form-control">
                            <option></option>
                            <?php foreach($banks as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success"> إستعلام</button>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default"> تفريغ الحقول</button>
            </div>
        </fieldset>

        <div id="container">
            <?php echo modules::run('treasury/checks_processing/get_page',$page); ?>
        </div>

    </div>

</div>
<?php

$scripts = <<<SCRIPT

<script>

    $(function(){
        reBind();
    });

    function reBind(){

        ajax_pager({check_id : $('#txt_check_id').val(),bank:$('#dp_check_bank_id').val()});

    }

    function LoadingData(){

        ajax_pager_data('#voucherTbl > tbody',{check_id : $('#txt_check_id').val(),bank:$('#dp_check_bank_id').val()});

    }


    function do_search(){

        get_data('{$get_page_url}/1',{page: 1 ,check_id : $('#txt_check_id').val(),bank:$('#dp_check_bank_id').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
</script>
SCRIPT;

sec_scripts($scripts);



?>

