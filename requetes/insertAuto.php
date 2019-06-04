<?php
include('../include/config.php');
extract($_POST);
if($id){
	$req = mysqli_query($db, "UPDATE `autos`
		SET `plate_number`='$plate_number',
		`brand`='$brand',
		`model`='$model',
		`motor`='$motor',
		`chassis_number`='$chassis_number',
		`registration_year`='$registration_year',
		`kws`='$kws',
		`kilometers`='$kilometers',
		`tires`='$tires'
		 WHERE `id`='$id'") or die(mysqli_error($db));
} else {
	$req = mysqli_query($db, "INSERT INTO `autos`(`plate_number`,`brand`,`model`,`motor`,`chassis_number`,`registration_year`,`kws`,`kilometers`,`tires`)
	VALUES ('$plate_number','$brand','$model','$motor','$chassis_number','$registration_year','$kws','$kilometers','$tires')") or die(mysqli_error($db));
	$id = mysqli_insert_id($db);
}

// ITV LOGS
$next_date = '';
if(!empty($last_date) && !empty($frequency)){
	$last_date_st = strtotime($last_date);
	$next_date = date('Y-m-d', strtotime($frequency, $last_date_st));
	$last_date = date("Y-m-d", $last_date_st);
	$today = date('Y-m-d');
	if ($last_date == $next_date) {
		$status = 'Exento ITV';
	} elseif ($next_date > $today) {
		$status = 'ITV Valida';
	} elseif ($next_date > $today) {
		$status = 'ITV Vencida';
	}
} else {
	$status = 'NO ITV INFO';
}
$last_date_dt = empty($last_date) ?  "NULL" : "CAST('". $last_date ."' AS DATE)";
$next_date_dt = empty($next_date) ?  "NULL" : "CAST('". $next_date ."' AS DATE)";
if($itv_id){
	$req = mysqli_query($db, "UPDATE `itv_logs`
		SET `last_date`=$last_date_dt,
		`frequency`='$frequency',
		`next_date`=$next_date_dt,
		`status`='$status',
		`auto_id`='$id'
		 WHERE `id`='$itv_id'") or die(mysqli_error($db));
} else {
	$req = mysqli_query($db, "INSERT INTO `itv_logs`(`last_date`,`frequency`,`next_date`,`status`,`auto_id`)
	VALUES ($last_date_dt,'$frequency',$next_date_dt,'$status','$id')") or die(mysqli_error($db));
}

// CLIENT
if($client_id){
	$req = mysqli_query($db, "UPDATE `clients`
		SET `company_name`='$company_name',
		`name`='$name',
		`surname`='$surname',
		`tel`='$tel',
		`email`='$email',
		`dni`='$dni',
		`ordre`='$ordre'
		 WHERE `id`='$client_id'") or die(mysqli_error($db));
} else {
	$req = mysqli_query($db, "INSERT INTO `clients`(`company_name`,`name`,`surname`,`tel`,`email`,`dni`,`ordre`)
	VALUES ('$company_name','$name','$surname','$tel','$email','$dni','$ordre')") or die(mysqli_error($db));
	$client_id = mysqli_insert_id($db);
	$req = mysqli_query($db, "UPDATE `autos` SET `client_id`='$client_id'
		WHERE `id`='$id'") or die(mysqli_error($db));
}
// CLIENT ADDRESS
if($address_id){
	$req = mysqli_query($db, "UPDATE `addresses` SET
		`address1`='$address1',
		`address2`='$address2',
		`address3`='$address3',
		`city`='$city',
		`province`='$province',
		`postcode`='$postcode'
		WHERE `id`='$address_id'") or die(mysqli_error($db));
		$req = mysqli_query($db, "UPDATE `clients` SET `address_id`='$address_id'
			WHERE `id`='$client_id'") or die(mysqli_error($db));
} else {
	$req = mysqli_query($db, "INSERT INTO `addresses`(`address1`,`address2`,`address3`,`city`,`province`,`postcode`)
	VALUES ('$address1','$address2','$address3','$city','$province','$postcode')") or die(mysqli_error($db));
	$address_id = mysqli_insert_id($db);
	$req = mysqli_query($db, "UPDATE `clients` SET `address_id`='$address_id'
		WHERE `id`='$client_id'") or die(mysqli_error($db));
}

if (isset($_POST['ajaxed']) && !empty($_POST['ajaxed'])) {
	if ($req) {
		echo '{"msg":"Auto guardado."}';
	} else {
		echo '{"msg":"Se ha producido un error."}';
	}
} else {
	header("location:../autos.php");
}
?>
