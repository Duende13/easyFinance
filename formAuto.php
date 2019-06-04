<?php
include ('classes/interface.class.php');
$myInterface = new interface_();
$id = !empty($_GET['id']) ? $_GET['id'] : '';
$selectAuto = mysqli_query($db,"SELECT * FROM `autos` WHERE `id` ='".$id."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
$auto = mysqli_fetch_array($selectAuto);
if (is_array($auto)) {
	extract($auto);
	$selectItv = mysqli_query($db,"SELECT * FROM `itv_logs` WHERE `auto_id` ='".$id."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
	$itv = mysqli_fetch_array($selectItv);
	extract($itv);
	$itv_id = $itv['id'];
} else {
	$id = '';
	$plate_number = '';
	$brand = '';
	$model = '';
	$motor = '';
	$chassis_number = '';
	$registration_year = '';
	$kws = '';
	$kilometers = '';
	$tires = '';
	$client_id = '';
	$notes = '';
	$itv_id = '';
}
if (!empty($client_id)) {
	$selectClient = mysqli_query($db,"SELECT * FROM `clients` WHERE `id` ='".$client_id."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
	$client = mysqli_fetch_array($selectClient);
	extract($client);
	$selectAddress = mysqli_query($db,"SELECT * FROM `addresses` WHERE `id` ='".$client['address_id']."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
	$address = mysqli_fetch_array($selectAddress);
	extract($address);
} else {
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
$id = !empty($auto) ? $auto['id'] : '';


if (!isset($_GET['ajaxed'])) {
	$myInterface->set_title("EasyFinance – Auto");
	$myInterface->get_header();
}
?>
		<form id="auto" method="post" action="requetes/insertAuto.php">
			<fieldset id="firstFieldset">
				<input type="hidden" id="id" name="id" value="<?php echo $id?>">
				<p>
					<label for="plate_number">Matrícula : </label>
					<input name="plate_number" type="text" value="<?php echo htmlspecialchars($plate_number)?>" id="plate_number" autofocus="autofocus" />
				</p>
				<p>
					<label for="brand">Marca : </label>
					<input name="brand" type="text" value="<?php echo htmlspecialchars($brand)?>" id="brand" />
				</p>
				<p>
					<label for="model">Modelo: </label>
					<input name="model" type="text" value="<?php echo htmlspecialchars($model)?>" id="model"/>
				</p>
				<p>
					<label for="motor">Motor : </label>
					<input name="motor" type="text" value="<?php echo htmlspecialchars($motor)?>" id="motor" />
				</p>
				<p>
					<label for="chassis_number">Bastidor : </label>
					<input name="chassis_number" type="text" value="<?php echo htmlspecialchars($chassis_number)?>" id="chassis_number" />
				</p>
				<p>
					<label for="registration_year">Año matriculación : </label>
					<input name="registration_year" type="text" value="<?php echo htmlspecialchars($registration_year)?>" id="registration_year" />
				</p>
				<p>
					<label for="kws">KWS : </label>
					<input name="kws" type="kws" value="<?php echo $kws?>" id="kws" />
				</p>
				<p>
					<label for="kilometers">Kilómetros : </label>
					<input name="kilometers" type="kilometers" value="<?php echo $kilometers?>" id="kilometers" />
				</p>
				<p>
					<label for="tires">Neumáticos : </label>
					<input name="tires" type="tires" value="<?php echo $tires?>" id="tires" />
				</p>
				<p>
					<label for="notes">Observaciones : </label>
					<textarea name="notes" type="notes" value="<?php echo $notes?>" id="notes" />
				</p>
			</fieldset>
			<fieldset id="firstFieldset" name="itv">
				 <p>
					 <label for="itv_fields">ITV&nbsp;:</label>
					 <input type="hidden" id="itv_id" name="itv_id" value="<?php if(isset($itv_id))echo $itv_id?>">
				 </p>
				 	<p>
				 		<label for="last_date">Fecha ultima ITV&nbsp;:</label>
				 		<input id="last_date" name="last_date" type="date" value="<?php if(isset($last_date))echo $last_date?>" />
				 	</p>
				 		<label for="frequency">Periodicidad&nbsp;:</label>
						<select  id="frequency" name="frequency"  >
							<option value="" selected="true"></option>
						  <option value="+0months">Exento</option>
						  <option value="+6months" >6 meses</option>
						  <option value="+12months">1 año</option>
						  <option value="+24months">2 años</option>
						</select>
				 	</p>
			</fieldset>
			<fieldset name="client">
				 <p>
					 <label for="client_fields">Cliente&nbsp;:</label>
					 <input type="hidden" id="client_id" name="client_id" value="<?php echo $client_id?>">
	 	 				<input type="hidden" id="ordre" name="ordre" value="2019">
	 				</p>
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
				 		<label for="address1">Dirección&nbsp;:</label>
 					  <input type="hidden" id="address_id" name="address_id" value="<?php echo $address_id?>">
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
