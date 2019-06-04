<?php
if (isset($_GET['type']) && !empty($_GET['type'])) {
	$type = $_GET['type'];
	if ($type == 'contrats') {
		include_once('listingContrats.php');
	} else if($type == 'clients') {
		include_once('listingClients.php');
	} else if($type == 'autos') {
		include_once('listingAutos.php');
	} else {
		include_once('listingBudgets.php');
	}
}
?>
