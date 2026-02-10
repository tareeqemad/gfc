<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 01:13 م
 */
$count = 1;
$cancel_url = base_url('financial/financial_mqasah/cancel');
$notes_url = base_url('settings/notes/public_create');
?>
<div class="tbl_container">
    <table class="table" id="chainsTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th > رقم السند </th>
            <th >التاريخ</th>
            <th  > المصدر</th>

            <th > العملة</th>
            <th >سعر العملة</th>
            <th  class="price"> المبلغ   </th>

            <th >   البيان </th>
            <th>رقم القيد</th>
            <th >المدخل</th>
            <td></td>
            <th ></th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($chains as $chain) :?>
            <tr ondblclick="javascript:get_to_link('<?= base_url('financial/financial_mqasah/get/').'/'.$chain['FINANCIAL_CHAINS_ID'].'/'.$action.'?type=12' ?>');"  class="case_<?= $chain['FINANCIAL_CHAINS_CASE'] ?> source_<?= $chain['FIANANCIAL_CHAINS_SOURCE'] ?>">

                <td><?= $count ?></td>
                <td><?= $chain['FINANCIAL_CHAINS_ID'] ?></td>
                <td><?= $chain['FINANCIAL_CHAINS_DATE'] ?></td>
                <td><?= $chain['FIANANCIAL_CHAINS_SOURCE_NAME'] ?></td>

                <td><?= $chain['CURR_ID_NAME'] ?></td>
                <td><?= $chain['CURR_VALUE'] ?></td>

                <td class="price"><?= n_format($chain['DEBIT_SUM']) ?> </td>


                <td><?= $chain['HINTS'] ?></td>
                <td>
                    <?php if($chain['QEED_NUM'] > 0) : ?>
                        <a id="source_url" href="<?= base_url("financial/financial_chains/get/{$chain['QEED_NUM']}/index?type=4") ?>"  target="_blank"><?= $chain['QEED_NUM'] ?></a>
                    <?php endif; ?>
                </td>
                <td><?= $chain['ENTRY_USER_NAME'] ?></td>
                <td>
                    <a href="javascript:;"
                       onclick="javascript:_showReport('<?= base_url("attachments/attachment/public_upload/{$chain['FINANCIAL_CHAINS_ID']}/mqasah") ?>');">
                        <i class="<?= ($chain['ATTACHMENT_COUNT'] > 0?"icon icon-archive":"icon icon-upload delete-action") ?> "></i>
                    </a>
                </td>
                <td>

                    <?php if ( $chain['FINANCIAL_CHAINS_CASE'] >= 3): ?>
                        <a onclick="javascript:_showReport('<?=base_url('/reports') ?>?report=MAQASAH_MAIN&params[]=<?=$chain['FINANCIAL_CHAINS_ID'] ?>&params[]=<?= $chain['BRANCHES']?>');" href="javascript:;"><i class="icon icon-print print-action"></i> </a>
                    <?php   endif; ?>
                    <?php if( (HaveAccess($cancel_url) /*&& $chain['FINANCIAL_CHAINS_CASE'] >= 3*/ & $chain['FINANCIAL_CHAINS_CASE'] !=0) ):  ?>
                        <a href="javascript:;" onclick="javascript:cancel_chain(<?=$chain['FINANCIAL_CHAINS_ID'] ?>);"><i class="glyphicon glyphicon-ban-circle cancel-action"></i></a>
                    <?php endif; ?>
                    <a  href="<?= base_url('financial/financial_mqasah/public_copy/').'/'.$chain['FINANCIAL_CHAINS_ID'].'/index?type='.(isset($type)?$type:'4') ?>"><i class="icon icon-copy print-action"></i> نسخ</a>

                </td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
<?php echo modules::run('settings/notes/index'); ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>

<?php if( HaveAccess($cancel_url)):  ?>

    <script>

        var chain_id ;
        function cancel_chain(id){

            chain_id =id;
            $('#notesModal').modal('show');
        }

        function apply_action(){



            if($('#txt_g_notes').val() =='' ){
                alert('تحذير : لم تذكر سبب الغاء ؟!!');
                return;
            }


            if(confirm('هل تريد إلغاء السند ؟!')){
                get_data('<?= $cancel_url ?>',{id:chain_id},function(data){

                    if(data =='1'){
                        success_msg('رسالة','تم إلغاء السند بنجاح ..');
                        reload_Page();
                    }

                },'html');
            }


            get_data('<?= $notes_url ?>',{source_id:chain_id,source:'mqasah',notes:$('#txt_g_notes').val()},function(data){
                $('#txt_g_notes').val('');
            },'html');

            $('#notesModal').modal('hide');

        }

    </script>


<?php endif; ?>