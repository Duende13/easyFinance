<?php
include('../include/config.php');
$redirectPage = "";
$redirectNew = "";
if (isset($_POST['boutonSupprimer']) && $_POST['boutonSupprimer']) {
	$nombreElements = count($_POST["selectionElements"]);
	if ($nombreElements > 0) {
		foreach ($_POST["selectionElements"] as $id) {
			$req = mysqli_query($db, "DELETE FROM `".$_POST['table']."` WHERE `id`=$id") or die(mysqli_error($db));
		}
		if (isset($req) && $req) {
			if (isset($_POST['ajaxed']) && !empty($_POST['ajaxed'])) {
				if (count($_POST["selectionElements"]) == 1) {
					echo '{"msg":"Borrado."}';
				} else {
					echo '{"msg":"'.count($_POST["selectionElements"]).' elementos borrados."}';
				}
				exit();
			}
		} else {
			if (isset($_POST['ajaxed']) && !empty($_POST['ajaxed'])) {
				echo '{"msg":"Se ha producido un error."}';
				exit();
			}
		}
	} else {
		if (isset($_POST['ajaxed']) && !empty($_POST['ajaxed'])) {
			echo '{"msg":"No hay elementos seleccionados."}';
			exit();
		}
	}
	$_POST["selectionElements"] = array();
	$nombreElements = 0;
}

switch ($_POST['table']) {
	case 'contrats':
		$redirectPage = 'contrats.php?type=';
		$redirectNew = 'formContrats.php?type=';
		break;

	case 'autos':
		$redirectPage = 'autos.php';
		$redirectNew = 'formAuto.php';
		break;

	case 'budgets':
		$redirectPage = 'budgets.php';
		$redirectNew = 'formBudget.php';
		break;

	default:
		$redirectPage = 'clients.php';
		$redirectNew = 'formClient.php';
		break;
}
if (isset($_POST['boutonAjouter']) && $_POST['boutonAjouter']) {
	header('location:../'.$redirectNew);
	exit();
} else {
	if (isset($_POST['annee']) && !empty($_POST['annee'])) {
		$annee = $_POST['annee'];
	} else {
		$annee = "";
	}
	if ($redirectPage == 'clients.php') {
		header('location:../'.$redirectPage);
	} else {
		header('location:../'.$redirectPage.'&annee='.$annee);
	}
}
?>
