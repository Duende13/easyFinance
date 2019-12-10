<?php
include ('classes/interface.class.php');
$myInterface = new interface_();
$id = !empty($_GET['id']) ? $_GET['id'] : '';
$selectClient = mysqli_query($db,"SELECT * FROM `clients` WHERE `id` ='".$id."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
$client = mysqli_fetch_array($selectClient);
if (is_array($client)) {
	extract($client);
	$selectAddress = mysqli_query($db,"SELECT * FROM `addresses` WHERE `id` ='".$address_id."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
	$address = mysqli_fetch_array($selectAddress);
	if (is_array($address)) {
		extract($address);
	}
} else {
	$id = '';
	$dni = '';
	$company_name = '';
	$name = '';
	$surname = '';
	$tel = '';
	$email = '';
	$notes = '';
	$ordre = '';
	$address_id = '';
}

if (!isset($_GET['ajaxed'])) {
	$myInterface->set_title("EasyFinance – Cliente");
	$myInterface->get_header();
}
?>
		<form id="client" method="post" action="requetes/insertClient.php">
			<fieldset id="firstFieldset">
				<input type="hidden" id="id" name="id" value="<?php echo $id?>">
	 			<input type="hidden" id="ordre" name="ordre" value="2019">
				<p>
					<label for="dni">DNI/NIF/CIF : </label>
					<input name="dni" type="text" value="<?php echo htmlspecialchars($dni)?>" id="dni" />
				</p>
				<p>
					<label for="company_name">Nombre de Empresa: </label>
					<input name="company_name" type="text" value="<?php echo htmlspecialchars($company_name)?>" id="company_name" autofocus="autofocus"/>
				</p>
				<p>
					<label for="name">Nombre : </label>
					<input name="name" type="text" value="<?php echo htmlspecialchars($name)?>" id="name" />
				</p>
				<p>
					<label for="surname">Apellidos : </label>
					<input name="surname" type="text" value="<?php echo htmlspecialchars($surname)?>" id="surname" />
				</p>
				<p>
					<label for="tel">Teléfono : </label>
					<input name="tel" type="text" value="<?php echo htmlspecialchars($tel)?>" id="tel" />
				</p>
				<p>
					<label for="email">Email : </label>
					<input name="email" type="email" value="<?php echo $email?>" id="email" />
				</p>
				<p>
					<label for="notes">Observaciones : </label>
					<textarea name="notes" id="notes" rows="4" cols="50"><?php echo $notes?></textarea>
				</p>
			</fieldset>
			<fieldset>
				 <input type="hidden" id="address_id" name="address_id" value="<?php if(isset($address_id))echo $address_id?>">
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
			</fieldset>
			<p>
				<input type="submit" type="text" value="Guardar" id="validation" />
			</p>
		</form>
<?php $myInterface->get_footer(); ?>
