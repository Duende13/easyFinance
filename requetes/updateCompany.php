<?php
include('../include/config.php');
extract($_POST);
if($id){
	$req = mysqli_query($db, "UPDATE `company` SET
		`company_name`='$company_name',
		`responsible_surname`='$responsible_surname',
		`responsible_name`='$responsible_name',
		`login`='$login',
		`telephone`='$telephone',
		`email`='$email',
		`site`='$site',
		`bank_account`='$bank_account',
		`iban`='$iban',
		`fax`='$fax',
		`bic`='$bic',
		`company_number`='$company_number',
		`address_id`='$address_id'
		WHERE `id`='$id'") or die(mysqli_error($db));
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
}

if (!empty($password)) {
	$password = md5($password);
	$req = mysqli_query($db, "UPDATE `company` SET
		`password`='$password'
		WHERE `id`='$id'") or die(mysqli_error($db));
}
if (isset($_POST['ajaxed']) && !empty($_POST['ajaxed'])) {
	if ($req) {
		echo '{"msg":"Cambios salvados."}';
	} else {
		echo '{"msg":"Se ha producido un error!"}';
	}
}else{
	header("location:../formCompany.php");
}
?>
