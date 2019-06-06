<?php
include('../include/config.php');
extract($_POST);
$created_dt = empty($created_at) ?  "NULL" : "CAST('". $created_at ."' AS DATE)";
$presented_dt = empty($presented_at) ?  "NULL" : "CAST('". $presented_at ."' AS DATE)";
$paid_dt = empty($paid_at) ?  "NULL" : "CAST('". $paid_at ."' AS DATE)";
$status = "creado";
$result = mysqli_query($db,"SELECT `address_id` FROM `clients` WHERE `id` ='".$client_id."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
$value = mysqli_fetch_assoc($result);
$address_id = $value['address_id']; // TODO change for real code to geth from object
$budget_id = $id;

if($id){
	$req = mysqli_query($db, "UPDATE `budgets`
		SET `created_at`=$created_dt,
		`presented_at`=$presented_dt,
		`paid_at`=$paid_dt,
		`invoice_number`='$invoice_number',
		`notes`='$notes',
		`total`='$total',
		`vat`='$vat',
		`nett`='$nett',
		`status`='$status',
		`auto_id`='$auto_id',
		`address_id`='$address_id',
		`client_id`='$client_id'
		 WHERE `id`='$id'") or die(mysqli_error($db));
} else {
	$req = mysqli_query($db, "INSERT INTO `budgets`(`created_at`,`presented_at`,`paid_at`,`invoice_number`,`notes`,`total`,`vat`,`auto_id`,`address_id`,`client_id`,`status`)
	VALUES ($created_dt,$presented_dt,$paid_dt,'$invoice_number','$notes','$total','$vat','$auto_id','$address_id','$client_id','$status')") or die(mysqli_error($db));
	$budget_id = mysqli_insert_id($db);
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

// Services
$i = 1;
while($i <= 3) {
	$serviceid = ${"serviceid_" . $i};
	$servicebudgetid = ${"servicebudgetid_" . $i};
	$name = ${"name_" . $i};
	$amount =  empty(${"amount_" . $i}) ? 0.00 : ${"amount_" . $i};
	$description = ${"description_" . $i};
	$discount = empty(${"discount_" . $i}) ? 0.00 : ${"discount_" . $i};
	$price = empty(${"price_" . $i}) ? 0.00 : ${"price_" . $i};
	$total_ser = empty(${"total_" . $i}) ? 0.00 : ${"total_" . $i};
	$totaldiscount = empty(${"totaldiscount_" . $i}) ? 0.00 : ${"totaldiscount_" . $i};
	$i++;

	// If there is serviceid and name just update
	if(!empty($serviceid)){
		$req = mysqli_query($db, "UPDATE `services` SET
			`name`='$name',
			`description`='$description',
			`price`='$price'
			WHERE `id`='$serviceid'") or die(mysqli_error($db));
	} elseif ($name) {
		// when there is name but not id we need to create as new service
		$req = mysqli_query($db, "INSERT INTO `services`(`name`,`description`,`price`)
		VALUES ('$name','$description','$price')") or die(mysqli_error($db));
		$serviceid = mysqli_insert_id($db);
	}
	if($serviceid) {
		if($servicebudgetid){
			$req = mysqli_query($db, "UPDATE `budget_services` SET
				`discount`='$discount',
				`price`='$price',
				`amount`='$amount',
				`service_id`='$serviceid',
				`budget_id`='$id',
				`total`='$total_ser'
				WHERE `id`='$servicebudgetid'") or die(mysqli_error($db));
		} else{
			$req = mysqli_query($db, "INSERT INTO `budget_services`(`discount`,`price`,`amount`,`service_id`,`budget_id`,`total`)
			VALUES ('$discount','$price','$amount','$serviceid','$budget_id','$total_ser')") or die(mysqli_error($db));
		}
	}
}

if (isset($_POST['ajaxed']) && !empty($_POST['ajaxed'])) {
	if ($req) {
		echo '{"msg":"Presupuesto creado."}';
	} else {
		echo '{"msg":"Se ha producido un error."}';
	}
} else {
	header("location:../autos.php");
}

?>
