<?php
include ('classes/interface.class.php');
$myInterface = new interface_();
$selectCompany = mysqli_query($db, "SELECT * FROM `company`") or trigger_error(mysqli_error($db),E_USER_ERROR);
$company = mysqli_fetch_array($selectCompany);
if (is_array($company)) {
	extract($company);
} else {
	mysqli_query($db, "INSERT INTO `company` (`id`) VALUES (1)") or trigger_error(mysqli_error($db),E_USER_ERROR);
}
$selectAddress = mysqli_query($db, "SELECT * FROM `addresses` where `id`= '$company[address_id]'") or trigger_error(mysqli_error($db),E_USER_ERROR);
$address = mysqli_fetch_array($selectAddress);
if (is_array($address)) {
	extract($address);
} else {
	mysqli_query($db, "INSERT INTO `addresses` (`id`) VALUES (1)") or trigger_error(mysqli_error($db),E_USER_ERROR);
}

if (!isset($_GET['ajaxed'])) {
	$myInterface->set_title("EasyFinance – Empresa");
	$myInterface->get_header();
?>
	<div class="contenu" style="margin-top:70px;">
		<h3>Utilisateur</h3>
<?php } ?>
		<form id="company" method="post" action="requetes/updateCompany.php">
			<fieldset id="firstFieldset">
				<input name="id" type="hidden" value="1" />
				<p>
					<label for="company_name">Nombre de Empresa&nbsp;:</label>
					<input id="company_name" name="company_name" type="text" value="<?php if(isset($company_name))echo $company_name?>" />
				</p>
				<p>
					<label for="responsible_name">Nombre Responsable&nbsp;:</label>
					<input id="responsible_name" name="responsible_name" type="text" value="<?php if(isset($responsible_name))echo $responsible_name?>" />
				</p>
				<p>
					<label for="responsible_surname">Apellido Responsable&nbsp;:</label>
					<input id="responsible_surname" name="responsible_surname" type="text" value="<?php if(isset($responsible_surname))echo $responsible_surname?>" />
				</p>
				<p>
					<label for="login">Usuario&nbsp;:</label>
					<input id="login" name="login" type="text" value="<?php if(isset($login))echo $login?>" />
				</p>
					<p>
						<label for="password">Password&nbsp;:</label>
						<input id="password" name="password" type="password" value="<?php if(isset($password))echo $password?>" />
					</p>
				<p>
					<label for="telephone">Teléfono&nbsp;:</label>
					<input id="telephone" name="telephone" type="text" value="<?php if(isset($telephone))echo $telephone?>" />
				</p>
				<p>
					<label for="email">Email&nbsp;:</label>
					<input id="email" name="email" type="email" value="<?php if(isset($email))echo $email?>" />
				</p>
				<p>
					<label for="site">Website&nbsp;:</label>
					<input id="site" name="site" type="url" value="<?php if(isset($site))echo $site?>" />
				</p>
				<p>
					<label for="company_number">Número de Empresa&nbsp;:</label>
					<input id="company_number" name="company_number" type="text" value="<?php if(isset($company_number))echo $company_number?>" />
				</p>
				<p>
					<label for="bank_account">Cuenta bancaria&nbsp;:</label>
					<input id="bank_account" name="bank_account" type="text" value="<?php if(isset($bank_account))echo $bank_account?>" />
				</p>
				<p>
					<label for="iban">IBAN&nbsp;:</label>
					<input id="iban" name="iban" type="text" value="<?php if(isset($iban))echo $iban?>" />
				</p>
				<p>
					<label for="bic">Code BIC&nbsp;:</label>
					<input id="bic" name="bic" type="text" value="<?php if(isset($bic))echo $bic?>" />
				</p>$
			</fieldset>
			<fieldset>
				 <input type="hidden" id="address_id" name="address_id" value="1">
				<p>
					<label for="address1">Dirección&nbsp;:</label>
					<input id="address1" name="address1" type="text" value="<?php if(isset($address1))echo $address1?>" />
				</p>
				<p class="float">
				<input id="address2" name="address2" type="text" value="<?php if(isset($address2))echo $address2?>" />
				</p>
				<p class="float">
					<input id="address3" name="address3" type="text" value="<?php if(isset($address3))echo $address3?>" />
				</p>
				<p class="float clear">
					<label for="postcode">Código Postal&nbsp;:</label>
					<input id="postcode" name="postcode" type="text" value="<?php if(isset($postcode))echo $postcode?>" />
				</p>
				<p class="float">
					<label for="city">Localidad&nbsp;:</label>
					<input id="city" name="city" type="text" value="<?php if(isset($city))echo $city?>" />
				</p>
				<p>
					<label for="province">Provincia&nbsp;:</label>
					<input id="province" name="province" type="text" value="<?php if(isset($province))echo $province?>" />
				</p>
				<p>
					<label for="fax">Fax&nbsp;:</label>
					<input id="fax" name="fax" type="text" value="<?php if(isset($fax))echo $fax?>" />
				</p>
				<p>
					<label for="company_number">Número de Empresa&nbsp;:</label>
					<input id="company_number" name="fax" type="text" value="<?php if(isset($company_number))echo $company_number?>" />
				</p>
			</fieldset>
			<p>
				<input id="validation" name="validation" type="submit" value="Enregistrer" />
			</p>
		</form>
<?php if (!isset($_GET['ajaxed'])) { ?>
	</div>
<?php } ?>
<?php $myInterface->get_footer(); ?>
