<?php
$count = $offset;
function return_color($id){
    if ($id == 3) {
        return 'danger';
    }elseif ($id == 2){
        return  'success';
    }elseif($id == 1){
        return  'primary';
    }
	$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
}

function get_color_style($id) {
    $colors = [
        1 => 'color: #007bff',  // primary
        2 => 'color: #28a745',  // success
        3 => 'color: #dc3545',  // danger
    ];
    return $colors[$id] ?? 'color: #000000'; // default black
}

$report_url = base_url("JsperReport/showreport?sys=hr/salaries"); 
?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>المقر</th>
            <th> رقم</th>
            <th> الموظف</th>
            <th> البدل</th>
            <th> البند</th>
            <th>القيمة</th>
            <th>من شهر</th>
            <th>الى شهر</th>
            <th>خاضع للضريبة</th>
            <th>عن شهر</th>
            <th>ملاحظات</th>
			<th>خيارات</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php $total_value = 0 ;
				$colors = [ 
                    '#bbdefb', '#b3e5fc', '#b2ebf2', '#b2dfdb', '#c8e6c9',
                    '#dcedc8', '#f0f4c3', '#fff9c4', '#ffecb3', '#ffe0b2',
                    '#ffccbc', '#d7ccc8', '#cfd8dc'
				];
                foreach ($page_rows as $row) :
					$random_color = $colors[array_rand($colors)];
                    $total_value = $total_value + $row['VALUE'];
            ?>
            <tr style="background-color: <?= $random_color ?>;" ondblclick="javascript:show_row_details('<?=$row['EMP_NO']?>', '<?=$row['MONTH']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['BRAN_NAME'] ?></td>
                <td><?= $row['EMP_NO']?></td>
                <td><?= $row['EMP_NAME'] ?></td>
                <td style="<?= get_color_style($row['BADL_TYPE']) ?>"><?= $row['BADL_NAME'] ?></td>
                <td><?= $row['CON_NO_NAME'] ?></td>
                <td class="text-center text-primary"><?= $row['VALUE'] ?></td>
                <td><?= $row['MONTH'] ?></td>
                <td><?= $row['MONTH'] ?></td>
                <td><?php if($row['IS_TAXED'] == 1) { echo "نعم"; }else{ echo "لا";}  ?></td>
                <td><?= $row['MONTH'] ?></td>
                <td><?= $row['NOTES'] ?></td>
				<td>
                    <i class="glyphicon glyphicon-print" onclick="javascript:print_report(<?=$row['EMP_NO']?>, <?=$row['MONTH']?>, <?=$row['STATUS'].$row['Q_NO'].$row['DEGREE']?>);" > </i> 
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="font-size: x-large; font-family: "Tajawal", sans-serif"><?= $total_value; ?></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
			<th></th>
        </tr>
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
	
	function print_report(emp_, month_, prv_){
        var rep_url = '<?=$report_url?>&report_type=pdf'+'&report=salary_form&p_emp_id='+emp_+'&p_salary_month='+month_+'&p_prv='+prv_;
        _showReport(rep_url);
    }
</script>