<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:35 ص
 */
$create_url=base_url('treasury/income_voucher/'.($type=='service'?'create':$type));
$delete_url=base_url('treasury/income_voucher/delete');
$adopt_url =base_url('treasury/income_voucher/adopt')
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php  if( HaveAccess($adopt_url)):  ?><li><a  onclick="javascript:adopt_vouchers();" href="javascript:;"><i class="icon icon-save"></i>إغلاق الصندوق</a> </li> <?php endif; ?>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div>

            <table class="table" style="width: 430px">
                <thead>
                <tr>
                    <th> العملة </th>
                    <th> إجمالي المقبوضات الغير مغلقة </th>
                    <th> إجمالي المقبوضات المغلقة </th>
                </tr>
                </thead>
                <tbody>
              <?php  foreach ($sums as $row): ?>
                <tr>
                    <td><?= $row['CURRENCY_ID_NAME'] ?></td>
                    <td><?= $row['SM1'] ?></td>
                    <td><?= $row['SM2'] ?></td>
                </tr>
              <?php endforeach; ?>
                </tbody>
            </table>



        </div>

        <div id="container" class="clearfix">
            <?php echo modules::run('treasury/income_voucher/get_page',$page,$type); ?>
        </div>

    </div>

</div>
<?php





$scripts = <<<SCRIPT
<script>

    function adopt_vouchers(){

        if(confirm('هل تريد إغلاق السندات المختارة ')){
            var url = '{$adopt_url}';
            var tbl = '#voucherTbl';
            var val = [];

            $(tbl + ' .checkboxes:checked').each(function (i) {
                val[i] = $(this).val();

            });

            get_data('{$adopt_url}',{'voucher_id[]':val},function(data){
                if(data =='1')
                    success_msg('رسالة','تم إغلاق السند بنجاح ..');
                setTimeout(function(){

                    window.location.reload();

                }, 1000);
            },'html');

        }
    }

</script>

SCRIPT;

sec_scripts($scripts);



?>

