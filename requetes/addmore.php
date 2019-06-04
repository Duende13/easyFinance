<?php
include('../include/config.php');
extract($_POST);

	if(!empty($_POST["service_name"])){


		foreach ($_POST["service_name"] as $key => $value) {
			$sql = "INSERT INTO budget_services(name) VALUES ('".$value."')";
			$mysqli->query($sql);
		}
		echo json_encode(['success'=>'Service Inserted successfully.']);
	}


?>
