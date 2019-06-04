<?php
include ('classes/interface.class.php');
$myInterface = new interface_(false);
$loginOK = false;
if ( isset($_POST) && (!empty($_POST['login'])) && (!empty($_POST['pass'])) ){
	extract($_POST);
	$pass = md5($pass);
	$req = mysqli_query($db,"SELECT * FROM `company` WHERE `login`='$login'") or die('Erreur MySQL');
	$data = mysqli_fetch_assoc($req);
	if ($pass == $data['password']){
		$loginOK = true;
	}
}
if ($loginOK){
	$_SESSION['login'] = $login;
	header("location:./");
} else {
	$_SESSION['erreur'] = true;
	header("location:login.php");
}
?>
