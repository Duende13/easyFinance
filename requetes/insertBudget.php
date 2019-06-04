<?php
include('../include/config.php');
extract($_POST);
$created_dt = empty($created_at) ?  "NULL" : "CAST('". $created_at ."' AS DATE)";
$presented_dt = empty($presented_at) ?  "NULL" : "CAST('". $presented_at ."' AS DATE)";
$paid_dt = empty($paid_at) ?  "NULL" : "CAST('". $paid_at ."' AS DATE)";
$result = mysqli_query($db,"SELECT `address_id` FROM `clients` WHERE `id` ='".$client_id."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
$value = mysqli_fetch_object($result);
$address_id = 11; // TODO change for real code to geth from object


if($id){
	$req = mysqli_query($db, "UPDATE `budgets`
		SET `created_at`=$created_dt,
		`presented_at`=$presented_dt,
		`paid_at`=$paid_dt,
		`invoice_number`='$invoice_number',
		`notes`='$notes',
		`total`='$total',
		`vat`='$vat',
		`auto_id`='$auto_id',
		`address_id`='$address_id',
		`client_id`='$client_id'
		 WHERE `id`='$id'") or die(mysqli_error($db));
} else {
	$req = mysqli_query($db, "INSERT INTO `budgets`(`created_at`,`presented_at`,`paid_at`,`invoice_number`,`notes`,`total`,`vat`,`auto_id`,`address_id`,`client_id`)
	VALUES ($created_dt,$presented_dt,$paid_dt,'$invoice_number','$notes','$total','$vat','$auto_id','$address_id','$client_id')") or die(mysqli_error($db));
	$id = mysqli_insert_id($db);
}

// ITV LOGS
// $next_date = '';
// if(!empty($last_date) && !empty($frequency)){
// 	$last_date_st = strtotime($last_date);
// 	$next_date = date('Y-m-d', strtotime($frequency, $last_date_st));
// 	$last_date = date("Y-m-d", $last_date_st);
// 	$today = date('Y-m-d');
// 	if ($last_date == $next_date) {
// 		$status = 'Exento ITV';
// 	} elseif ($next_date > $today) {
// 		$status = 'ITV Valida';
// 	} elseif ($next_date > $today) {
// 		$status = 'ITV Vencida';
// 	}
// } else {
// 	$status = 'NO ITV INFO';
// }

// CLIENT ADDRESS
$i = 1;
while($i <= 2) {
	$serviceid = $serviceid_1;//'serviceid_$i';//$serviceid+"_"+i;
	echo "<script>alert($serviceid);</script>";
	if($serviceid && $name){
		$req = mysqli_query($db, "UPDATE `services` SET
			`name`='$name',
			`description`='$description',
			`price`='$price',
			WHERE `id`='$serviceid'") or die(mysqli_error($db));
	} elseif ($name) {
		$req = mysqli_query($db, "INSERT INTO `budget_services`(`discount`,`price`,`amount`,`service_id`,`budget_id`)
		VALUES ('$address1','$address2','$address3','$city','$province','$postcode')") or die(mysqli_error($db));
		$address_id = mysqli_insert_id($db);
		$req = mysqli_query($db, "UPDATE `clients` SET `address_id`='$address_id'
			WHERE `id`='$client_id'") or die(mysqli_error($db));
	}
	// TODO
	if($servicebudgetid){

	} else{
		$req = mysqli_query($db, "INSERT INTO `budget_services`(`address1`,`address2`,`address3`,`city`,`province`,`postcode`)
		VALUES ('$address1','$address2','$address3','$city','$province','$postcode')") or die(mysqli_error($db));
		$i++;
	}
}

if (isset($_POST['ajaxed']) && !empty($_POST['ajaxed'])) {
	if ($req) {
		echo '{"msg":"Auto creado."}';
	} else {
		echo '{"msg":"Se ha producido un error."}';
	}
} else {
	header("location:../autos.php");
}
?>
