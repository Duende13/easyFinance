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
$type = "clients";
$selectClients = mysqli_query($db, "SELECT * FROM `clients` ORDER BY `surname`") or trigger_error(mysqli_error($db),E_USER_ERROR);
?>
<form id="listing" action="requetes/traitements.php" method="post">
	<p class="tools">
		<a href="formClient.php" class="button medium orange" id="boutonAjouterClient" title="Nuevo Cliente">Nuevo Cliente</a>
		<!-- <input type="submit" value="Ajouter un client" id="boutonAjouterClient" name="boutonAjouter" title="Ajouter un Client" />
		<input type="submit" class="button medium mediumGrey" value="Eliminar los clientes seleccionados" id="boutonSupprimer" name="boutonSupprimer" />-->
		<input type="hidden" name="type" value="<?php echo $type ?>" id="type" />
		<input type="hidden" value="clients" name="table" />
		<input type="hidden" value="<?php echo $annee ?>" name="annee" />
	</p>

<h3>Clientes</h3>

<table>
	<tr class="legende">
		<th></th>
		<th>DNI/CIF/NIF</th>
		<th>Nombre</th>
		<th>Apellidos</th>
	</tr>
	<tbody id="clients">
		<?php
		while($clients = mysqli_fetch_array($selectClients)){
		?>
		<tr class="facture" id="element_<?php echo $clients['id']; ?>">
			<td>
					<input type="checkbox" name="selectionElements[]" value="<?php echo $clients['id'] ?>" />
				<a class="button small mediumGrey modifier popup" href="formClient.php?id=<?php echo $clients['id']?>" title="Editar">
					Editar
				</a>
			</td>
			<td><?php echo htmlspecialchars($clients['dni'])?></td>
			<td><?php echo htmlspecialchars($clients['name'])?></td>
			<td><?php echo htmlspecialchars($clients['surname'])?></td>
		</tr>
		<?php }?>
	</tbody>
	<tr class="tot_trimestre">
    <th colspan="6"></th>
</table>
</form>
