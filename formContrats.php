<?php
$id_client = '';
$numero = 1;
include ('classes/interface.class.php');
$myInterface = new interface_();
if(isset($_GET['annee']) && $_GET['annee']){
	$annee = $_GET['annee'];
}
if(isset($_GET['id']) && $_GET['id']){
	$id = $_GET['id'];
	$selectContrats = mysqli_query($db,"SELECT * FROM `contrats` WHERE `id`='$id'") or trigger_error(mysqli_error($db),E_USER_ERROR);
	$f = mysqli_fetch_array($selectContrats);
	extract($f);
	$dateArray = explode("-",$date);
} else {
	$anneeEnCours = date('Y');
	$queryForNumero = "SELECT `numero` FROM `contrats` WHERE `date`>='".$anneeEnCours."-01-01' ORDER BY `date` DESC, `numero` DESC LIMIT 1";
	$select_no = mysqli_query($db,$queryForNumero) or trigger_error(mysqli_error($db),E_USER_ERROR);
	if(mysqli_num_rows($select_no) > 0){
		$data = mysqli_fetch_array($select_no);
		$numero = $data['numero']+1;
	}
}
if(isset($_GET['id_client']) && $_GET['id_client']){
	$id_client = $_GET['id_client'];
}
$selectClients = mysqli_query($db,"SELECT `denomination` FROM `clients` WHERE `id_client`='".$id_client."' LIMIT 1") or trigger_error(mysqli_error($db),E_USER_ERROR);
if (!isset($_GET['ajaxed'])) {
	$myInterface->set_title("EasyFinance – Auto");
	$myInterface->get_header();
}
?>
	<form method="post" action="requetes/insertContrat.php" <?php if(isset($_GET['setAmountPaid']) && $_GET['setAmountPaid']) { echo 'class="setAmountPaid"'; } ?>>
		<input name="id" type="hidden" value="<?php if(isset($id)){echo $id;}?>" />
		<input name="id_usr" type="hidden" value="<?php if(isset($id_usr)){echo $id_usr;} else {echo "2";}?>" />
		<input name="type" type="hidden" value="contrats" />
		<input name="annee" type="hidden" value="<?php if(isset($annee)){echo $annee;}?>" />
		<p>
			<label for="numero">Numéro : </label>
			<input name="numero" id="numero" type="number" size="4" value="<?php echo $numero;?>" />
		</p>
		<p>
			<label>Date :</label>
			<input type="number" name="jour" value="<?php if(isset($date) && $date){echo $dateArray[2];}else{echo date("d");}?>" maxlength="2" size="2" />
			<input type="number" name="mois" value="<?php if(isset($date) && $date){echo $dateArray[1];}else{echo date("m");}?>" maxlength="2" size="2" />
			<input type="number" name="lannee" value="<?php if(isset($date) && $date){echo $dateArray[0];}else{echo date("Y");}?>" maxlength="4" size="4" />
		</p>
		<p>
<?php $c = mysqli_fetch_array($selectClients); ?>
			<label for="denomination">Client : </label>
			<input type="text" list="mesClients" name="denomination" id="denomination" value="<?php if(isset($c['denomination']))echo htmlspecialchars($c['denomination']) ?>" />
			<datalist id="mesClients">
<?php
$selectC = mysqli_query($db,"SELECT DISTINCT `denomination` FROM `clients`") or trigger_error(mysqli_error($db),E_USER_ERROR);
while($f = mysqli_fetch_array($selectC)){
?>
				<option label="" value="<?php echo htmlspecialchars($f['denomination']) ?>">
<?php
}
?>
			</datalist>
		</p>
		<p>
			<label for="objet">Objet : </label>
			<textarea rows="12" cols="50" name="objet" id="objet"><?php if(isset($objet))echo htmlspecialchars($objet)?></textarea>
		</p>
		<p>
			<label for="montant">Montant : </label>
<?php if (ASSUJETTI_A_LA_TVA): ?>
			<input type="text" name="montant" value="<?php if(isset($montant))echo $montant = strtr($montant, ".", ",");?>" id="montant" required="required" />
<?php else: ?>
			<input type="text" name="montant_tvac" value="<?php if(isset($montant_tvac))echo $montant_tvac = strtr($montant_tvac, ".", ",");?>" id="montant_tvac" required="required" />
<?php endif ?>
<?php if (ASSUJETTI_A_LA_TVA): ?>
			<input type="radio" name="htva" value="oui" checked="checked" /> HTVA
			<input type="radio" name="htva" value="non" /> TVAC
<?php else: ?>
			<input type="hidden" name="htva" value="non" />
<?php endif ?>
		</p>
		<p class="setAmountPaidField">
			<label for="amount_paid">Montant payé : </label>
			<input type="text" name="amount_paid" id="amount_paid"<?php if(isset($_GET['setAmountPaid'])) { echo ' autofocus="autofocus"'; } ?> value="<?php if(isset($amount_paid)){echo $amount_paid = strtr($amount_paid, ".", ",");}?>" /> / <span id="totalAmount"><?php if(isset($montant_tvac)){echo strtr($montant_tvac, ".", ",");} ?></span> €
		</p>
<?php if (ASSUJETTI_A_LA_TVA): ?>
		<p>
			<label for="pourcent_tva">TVA (%) : </label>
			<input type="text" name="pourcent_tva" size="5" value="<?php if(isset($pourcent_tva) && $pourcent_tva){echo strtr($pourcent_tva, ".", ",");}else{echo DEFAULT_TVA;}?>" id="pourcent_tva" />
		</p>
<?php else: ?>
			<input type="hidden" name="pourcent_tva" value="<?php if(isset($pourcent_tva) && $pourcent_tva){echo strtr($pourcent_tva, ".", ",");}else{echo DEFAULT_TVA;}?>" />
<?php endif ?>
			<input type="hidden" name="deductibilite" value="1" />
		<p class="submitButton">
			<input type="submit" value="Enregistrer" id="validation" />
		</p>
	</form>
<?php $myInterface->get_footer(); ?>
