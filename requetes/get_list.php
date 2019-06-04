<?php
include('../include/config.php');
$table = $_GET['table'];
$champ = $_GET['champ'];
$return = array();
$select = mysqli_query($db, "SELECT DISTINCT `".$champ."` FROM `".$table."`") or trigger_error(mysqli_error($db),E_USER_ERROR);
while($f = mysqli_fetch_array($select)){
	$return[] = htmlspecialchars($f[$champ]);
}
echo '["'.implode('","',$return).'"]';
?>
