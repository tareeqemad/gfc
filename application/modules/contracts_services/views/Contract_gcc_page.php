<?php

$MODULE_NAME = 'contracts_services';
$TB_NAME = 'Contract_gcc';
$count = $offset;
?>
    <style>
        .ST_1 {
            background-color: #38db57;
        }

        .ST_2 {
            background-color: #db430a;
        }

        .ST_3 {
            background-color: #dba828;
        }
    </style>
    <div class="tbl_container">
        <table class="table" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th>#</th>
                <th>رقم التعاقد</th>
                <th>الجهة الطالبة</th>
                <th>الاجراء</th>
                <!--<th>التعاقد</th>-->
                <th>من تاريخ</th>
                <th>الى تاريخ</th>
                <th>مدة العقد</th>
                <th>المتبقي</th>
                <th>الاعتماد</th>
                <th>حالة العقد</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr>
                    <td colspan="8" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
                </tr>
            <?php endif; ?>
            <?php
            foreach ($page_rows as $row) :?>
                <tr id="tr_<?= $row['SER'] ?>"
                    ondblclick="javascript:get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/{$row['SER']}") ?>');">
                    <td><?= $count ?></td>
                    <td><?= $row['SER'] ?></td>
                    <td><?= $row['GCC_NAME'] ?></td>
                    <td><?= $row['CONTRACT_PRO_NAME'] ?></td>
                    <!--<td><?= $row['CONTRACT_PRO'] ?></td>-->
                    <td><?= $row['CONTRACT_START'] ?></td>
                    <td><?= $row['CONTRACT_END'] ?></td>
                    <td><?= $row['DURATION_DAY'] ?></td>
                    <td>


                        <?php // per_days_diff('2011-12-12','2011-12-29');
                        $exp_date = str_replace('/', '-', $row['CONTRACT_END']);
                        $today_date = date('d-m-Y');
                        echo time_diff_string($today_date, $exp_date)
                        // echo $exp_date;
                        //echo  $today_date
                        // if (  (strtotime($exp_date) < strtotime(date('d-m-Y')) ) ) :
                        ?>
                    </td>
                    <td><?= $row['ADOPT_NAME'] ?></td>
                    <td class="ST_<?= $row['STATUS'] ?>">
                        <?= $row['STATUS_NAME'] ?>
                    </td>
                    <?php
                    $count++; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php echo $this->pagination->create_links(); ?>
    <script>
        if (typeof initFunctions == 'function') {
            initFunctions();
        }
        if (typeof ajax_pager == 'function') {
            ajax_pager();
        }
        if (typeof show_page == 'undefined') {
            document.getElementById("page_tb").style.display = "none";
            document.getElementsByClassName("pagination")[0].style.display = "none";
        }
    </script>
<?php

function time_diff_string($from, $to, $full = false)
{
    $from = new DateTime($from);
    $to = new DateTime($to);
    $diff = $to->diff($from);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'سنة',
        'm' => 'شهر',
        'w' => 'اسبوع',
        'd' => 'يوم',
        'h' => 'ساعة',
        'i' => 'دقيقة',
        's' => 'ثانية',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' متبقي' : 'just now';
}

?>