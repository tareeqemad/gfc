<?php

set_time_limit(50); //sec

if( isset($_GET['no']) and isset($_GET['min']) ){
	$no= intval($_GET['no']);
	$min= intval($_GET['min']);
	$today= date('d/m/Y');  // 15/01/2022 -  TO_DATE( '22/08/2018 17:25:22' ,'DD/MM/YYYY HH24:MI:SS')
	
	if($no==0 or $min==0){
		die('param_0');
	}
	
	echo $today.' - '.$no.'<br>';
	
	///////////////////////////
	
	$conn_1 = oci_new_connect("Data", "SALDATA2022", "192.168.0.77:1521/HR","AL32UTF8");
	if (!$conn_1) {
		echo 'Conn Error'; die;
	}
	
	$sql_1 = " 
	update attendance 
	set ttime = ttime - INTERVAL '{$min}' MINUTE 
	where tdate = '{$today}' 
	and employeeno = {$no} 
	and status= 1
	";
	
	echo $sql_1.'<br>';
	
	$stmt_1 = oci_parse($conn_1, $sql_1);

	$result_1 = oci_execute($stmt_1, OCI_DEFAULT);
	if (!$result_1) {
	  echo 'Error '.oci_error();
	}else{
		oci_commit($conn_1);
		echo 'Done '.$result_1;
	}
	
	oci_close($conn_1);
	

	echo "<br>--------<br>";

	if ($result_1) {

		$conn_2 = oci_new_connect("user_clock", "user_clock", "192.168.0.77:1521/HR", "AL32UTF8");
		if (!$conn_2) {
			echo 'Conn Error';
			die;
		}

		$sql_2 = " 
		update clock_data_tb 
		set entrydate = entrydate - INTERVAL '{$min}' MINUTE 
		where TRUNC(entrydate) = '{$today}' 
		and userid = {$no} 
		and functionkey= 1
		";

		echo $sql_2.'<br>';

		$stmt_2 = oci_parse($conn_2, $sql_2);

		$result_2 = oci_execute($stmt_2, OCI_DEFAULT);
		if (!$result_2) {
			echo 'Error ' . oci_error();
		} else {
			oci_commit($conn_2);
			echo 'Done ' . $result_2;
		}

		oci_close($conn_2);
	}
	
	die('<br><br>END' );
}else{
	die("no param");
}

?>
