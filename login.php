<?php
include ('classes/interface.class.php');
$myInterface = new interface_(false);
if (!NEED_LOGIN) {
	header('location:./');
}
$myInterface->set_title("Authentification");
$myInterface->get_header('login');
?>
<form class="loginForm" method="post" action="verifierLogin.php">
<p><label>Usuario</label><input class="autowidth" type="text" name="login" autofocus="autofocus" /></p>
<p><label>Password</label><input class="autowidth" type="password" name="pass" /></p>
<?php
if(isset($_SESSION['erreur'])){
	unset($_SESSION['erreur']);
	echo '<span class="error">Usuario y/o password son incorrectas.</span>';
}
?>
<p><label>&nbsp;</label><input class="button green large" type="submit" name="submit" value="login" /></p>
</form>
<?php $myInterface->get_footer(); ?>
