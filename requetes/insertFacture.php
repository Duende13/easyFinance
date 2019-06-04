<?php
include('../include/config.php');
foreach ($_POST as $key => $value) {
	$$key = preg_replace("/'/", '’', $value);
}
$date = $lannee."-".$mois."-".$jour;
$pourcent_tva = strtr($pourcent_tva, ",", ".");
$amount_paid = strtr($amount_paid, ",", ".");
if (ASSUJETTI_A_LA_TVA) {
	$montant = strtr($montant, ",", ".");
	if(isset($htva) && $htva == "non"){
		$montant = $montant/($pourcent_tva/100+1);
	}
	$montant_tvac = $montant*($pourcent_tva/100+1);
} else {
	$montant_tvac = strtr($montant_tvac, ",", ".");
	$montant = $montant_tvac/($pourcent_tva/100+1);
}
$montant_tva = $montant*$pourcent_tva/100;

// Gestion Client pour facture sortante
$db = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE) or trigger_error(mysqli_error($db),E_USER_ERROR);
if (isset($type) && $type == 'sortantes') {
	$selectClient = mysqli_query($db,"SELECT `id_client` FROM `clients` WHERE `denomination`='".$denomination."' LIMIT 1") or trigger_error(mysqli_error($db),E_USER_ERROR);
	$f = mysqli_fetch_array($selectClient);
	if(!empty($f['id_client'])) {
		$id_client = $f['id_client'];
	} else {
		$req = mysqli_query($db,"INSERT INTO `clients`(`denomination`) VALUES ('$denomination')") or die(mysqli_error($db));
		$id_client = mysqli_insert_id($req);
	}
}

if(isset($id) && !empty($id)){ // update
	if (isset($type) && $type == 'entrantes') {
		$req = mysqli_query($db,"UPDATE `facturesEntrantes` SET `date`='$date',`denomination`='$denomination',`objet`='$objet',`montant`='$montant',`pourcent_tva`='$pourcent_tva',`montant_tva`='$montant_tva',`montant_tvac`='$montant_tvac' WHERE `id`='$id'") or die(mysqli_error($db));
	} else {
		$req = mysqli_query($db,"UPDATE `facturesSortantes` SET `id_usr`='$id_usr',`id_client`='$id_client',`date`='$date',`numero`='$numero',`objet`='$objet',`montant`='$montant',`pourcent_tva`='$pourcent_tva',`montant_tva`='$montant_tva',`montant_tvac`='$montant_tvac',`amount_paid`='$amount_paid' WHERE `id`='$id'");
	}
} else {
	if ($type == 'entrantes') { //insert
		$req = mysqli_query($db,"INSERT INTO `facturesEntrantes`(`date`,`denomination`,`objet`,`montant`,`pourcent_tva`,`montant_tva`,`montant_tvac`) VALUES ('$date','$denomination','$objet','$montant','$pourcent_tva','$montant_tva','$montant_tvac')") or die(mysqli_error($db));
	} else {
		$req = mysqli_query($db,"INSERT INTO `facturesSortantes`(`id_usr`,`id_client`,`date`,`numero`,`objet`,`montant`,`pourcent_tva`,`montant_tva`,`montant_tvac`,`amount_paid`) VALUES ('$id_usr','$id_client','$date','$numero','$objet','$montant','$pourcent_tva','$montant_tva','$montant_tvac','$amount_paid')");
	}
}
if (isset($_POST['ajaxed']) && !empty($_POST['ajaxed'])) {
	if ($req) {
		echo '{"msg":"Facture enregistrée."}';
	} else {
		echo '{"msg":"Une erreur s’est produite."}';
	}
}else{
	header('location:../factures.php?type='.$type.'&annee='.$lannee.'#bottom');
}
?>
