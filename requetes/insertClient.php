<?php
include('../include/config.php');
extract($_POST);
if($id){
	$req = mysqli_query($db, "UPDATE `clients`
		SET `company_name`='$company_name',
		`name`='$name',
		`surname`='$surname',
		`tel`='$tel',
		`email`='$email',
		`dni`='$dni',
		`ordre`='$ordre',
		`notes`='$notes'
		 WHERE `id`='$id'") or die(mysqli_error($db));
} else {
	$req = mysqli_query($db, "INSERT INTO `clients`(`company_name`,`name`,`surname`,`tel`,`email`,`dni`,`ordre`,`notes`)
	VALUES ('$company_name','$name','$surname','$tel','$email','$dni','$ordre','$notes')") or die(mysqli_error($db));
	$id = mysqli_insert_id($db);
}

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
			WHERE `id`='$id'") or die(mysqli_error($db));
} else {
	$req = mysqli_query($db, "INSERT INTO `addresses`(`address1`,`address2`,`address3`,`city`,`province`,`postcode`)
	VALUES ('$address1','$address2','$address3','$city','$province','$postcode')") or die(mysqli_error($db));
	$address_id = mysqli_insert_id($db);
	$req = mysqli_query($db, "UPDATE `clients` SET `address_id`='$address_id'
		WHERE `id`='$id'") or die(mysqli_error($db));
}
if (isset($_POST['ajaxed']) && !empty($_POST['ajaxed'])) {
	if ($req) {
		echo '{"msg":"Cliente creado."}';
	} else {
		echo '{"msg":"Se ha producido un error AQUIIII"}';
	}
} else {
	header("location:../clients.php");
}
?>
