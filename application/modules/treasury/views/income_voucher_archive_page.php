<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 15/10/14
 * Time: 08:25 ص
 */
$count = $offset;
$cancel_url = base_url('treasury/income_voucher/cancel');
$center_cancel_url = base_url('treasury/income_voucher/center_cancel');
$notes_url = base_url('settings/notes/public_create');
?>
<div class="tbl_container">
    <table class="table" id="voucherTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th >الرقم التسلسلي</th>
            <th > كود الإيصال </th>
            <th  >تاريخ الإيصال </th>
            <th  >نوع القبض</th>
            <th >الحساب</th>
            <th>الصندوق </th>
            <th  class="price"> المبلغ </th>
            <th > العملة </th>

            <th  >قيد رقم</th>
            <th >  المحصل </th>
            <th >  الزبون </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>

        <?php foreach($vouchers as $voucher) :?>
            <tr ondblclick="javascript:get_to_link('<?=  HaveAccess($cancel_url) || $voucher['VOUCHER_CASE'] != 0?( base_url('financial/financial_chains/get/').'/'.$voucher['FINANCIAL_CHAINS_ID'].'/index?type=4'):'' ?>');"   class="case_<?= $voucher['VOUCHER_CASE'] ?>">

                <td><?= $count ?></td>
                <td><?= $voucher['ENTRY_SER'] ?></td>
                <td><?= $voucher['VOUCHER_ID'] ?></td>
                <td><?= $voucher['VOUCHER_DATE'] ?></td>
                <td><?= $voucher['VOUCHER_TYPE_NAME'] ?></td>
                <td><?= $voucher['DEBIT_ACCOUNT_ID'] ?></td>

                <td><?= $voucher['DEBIT_ACCOUNT_ID_NAME'] ?></td>
                <td class="price"><?= $voucher['TOTAL'] ?></td>
                <td  ><?= $voucher['CURRENCY_ID_NAME'] ?></td>
                <td><?= $voucher['FINANCIAL_CHAINS_ID'] ?></td>

                <td><?= $voucher['ENTRY_USER_NAME'] ?></td>
                <td><?= $voucher['CUST_NAME'] ?></td>
                <td>
                    <?php if(  $voucher['VOUCHER_CASE'] > 0 && HaveAccess('print_voucher_archive')):  ?>
                        <?php if($branch == 0): ?>
                            <a onclick="javascript:_showReport('<?=base_url('/reports') ?>?report=MAIN_VI&params[]=<?=$voucher['VOUCHER_ID'] ?>&params[]=<?= $branch ?>');" href="javascript:;"><i class="icon icon-print print-action"></i> </a>
                        <?php else:?>
                        <a onclick="javascript:_showReport('<?=base_url('greport/reports/public_income_voucher/') ?>/<?=$voucher['VOUCHER_ID'] ?>');" href="javascript:;"><i class="icon icon-print print-action"></i> </a>

                    <?php endif;?>
                    <?php else:?>
                    <?= $voucher['CANCEL_DATE'] ?>
                    <?php endif; ?>
                    <?php if( (HaveAccess($cancel_url) && $voucher['VOUCHER_CASE'] > 0 && $voucher['VOUCHER_CASE'] !=2) || (HaveAccess($center_cancel_url) && $voucher['VOUCHER_CASE'] > 0)):  ?>
                        <a href="javascript:;" onclick="javascript:cancel_voucher('<?=$voucher['VOUCHER_ID'] ?>');"><i class="glyphicon glyphicon-ban-circle cancel-action"></i></a>

                    <?php endif; ?>
                </td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="notesModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> الرجاء إدخال سبب الإلغاء </h4>
            </div>
            <div id="msg_container_alt"></div>

            <div class="form-group col-sm-12">

                <div class="">
                    <textarea type="text" data-val="true" rows="5"    id="txt_g_notes" class="form-control "></textarea>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button"  onclick="javascript:apply_action();" class="btn btn-primary">حفظ البيانات</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>

<?php if( HaveAccess($cancel_url)):  ?>

    <script>

        var voucher_id ;
        function cancel_voucher(id){

            voucher_id =id;
            $('#notesModal').modal('show');
        }

        function apply_action(){



            if($('#txt_g_notes').val() =='' ){
                alert('تحذير : لم تذكر سبب الغاء ؟!!');
                return;
            }


            if(confirm('هل تريد إلغاء السند ؟!')){
                get_data('<?= $cancel_url ?>',{id:voucher_id},function(data){

                    if(data =='1'){
                        success_msg('رسالة','تم إلغاء السند بنجاح ..');
                        reload_Page();
                    }

                },'html');
            }


            get_data('<?= $notes_url ?>',{source_id:voucher_id,source:'income_voucher',notes:$('#txt_g_notes').val()},function(data){
                $('#txt_g_notes').val('');
            },'html');

            $('#notesModal').modal('hide');

        }

    </script>


<?php endif; ?>
