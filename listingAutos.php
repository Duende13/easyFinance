<?php
if(isset($_GET['annee'])){
	$annee = $_GET['annee'];
}else{
	$annee = "all";
}
if (isset($_GET['ajaxed']) && $_GET['ajaxed'] == 1) {
	include ('classes/interface.class.php');
	$myInterface = new interface_();
}
$type = "autos";
$selectAutos= mysqli_query($db, "SELECT * FROM `autos` ORDER BY `plate_number`") or trigger_error(mysqli_error($db),E_USER_ERROR);
$search_clients = mysqli_query($db, "SELECT * FROM `clients` GROUP BY `id`");
?>
<form id="listing" action="requetes/traitements.php" method="post">
	<p class="tools">
		<a href="formAuto.php" class="button medium orange" id="boutonAjouterAuto" title="Nuevo Auto">Nuevo Auto</a>
		<!-- <input type="submit" value="Ajouter un client" id="boutonAjouterClient" name="boutonAjouter" title="Ajouter un Client" />
		<input type="submit" class="button medium mediumGrey" value="Eliminar los clientes seleccionados" id="boutonSupprimer" name="boutonSupprimer" />-->
		<input type="hidden" name="type" value="<?php echo $type ?>" id="type" />
		<input type="hidden" value="autos" name="table" />
		<input type="hidden" value="<?php echo $annee ?>" name="annee" />
	</p>

<h3>Autos</h3>

<table>
	<tr class="legende">
		<th></th>
		<th>Id/th>
		<th>Matrícula</th>
		<th>Marca</th>
		<th>Modelo</th>
		<th>Nº de Bastidor</th>
		<th>Neumaticos</th>
	</tr>
	<tbody id="autos">
		<?php
		while($autos = mysqli_fetch_array($selectAutos)){
		?>
		<tr class="facture" id="element_<?php echo $autos['id']; ?>">
			<td>
					<input type="checkbox" name="selectionElements[]" value="<?php echo $autos['id'] ?>" />
				<a class="button small mediumGrey modifier popup" href="formAuto.php?id=<?php echo $autos['id']?>" title="Editar">
					Editar
				</a>
				<a href="formBudget.php?auto_id=<?php echo $autos['id']?>&client_id=<?php echo $autos['client_id']?>" class="button medium orange" id="boutonAjouterBudget" title="Nuevo Presupuesto">Nuevo Presupuesto</a>
			</td>
			<td><?php echo htmlspecialchars($autos['id'])?></td>
			<td><?php echo htmlspecialchars($autos['plate_number'])?></td>
			<td><?php echo htmlspecialchars($autos['brand'])?></td>
			<td><?php echo htmlspecialchars($autos['model'])?></td>
			<td><?php echo htmlspecialchars($autos['chassis_number'])?></td>
			<td><?php echo htmlspecialchars($autos['tires'])?></td>
		</tr>
		<?php }?>
	</tbody>
	<tr class="tot_trimestre">
    <th colspan="6"></th>
</table>
</form>
