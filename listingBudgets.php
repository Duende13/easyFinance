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
$type = "budgets";
$selectBudgets= mysqli_query($db, "SELECT id, created_at, status_id, total, client_id, auto_id FROM `budgets` ORDER BY `id`") or trigger_error(mysqli_error($db),E_USER_ERROR);
?>
<form id="listing" action="requetes/traitements.php" method="post">
	<p class="tools">
		<a href="formBudget.php" class="button medium orange" id="boutonAjouterBudget" title="Nuevo Presupuesto">Nuevo Presupuesto</a>
		<!-- <input type="submit" value="Ajouter un client" id="boutonAjouterClient" name="boutonAjouter" title="Ajouter un Client" />
		<input type="submit" class="button medium mediumGrey" value="Eliminar los clientes seleccionados" id="boutonSupprimer" name="boutonSupprimer" />-->
		<input type="hidden" name="type" value="<?php echo $type ?>" id="type" />
		<input type="hidden" value="budgets" name="table" />
		<input type="hidden" value="<?php echo $annee ?>" name="annee" />
	</p>

<h3>Presupuestos</h3>

<table>
	<tr class="legende">
		<th></th>
		<th>Id</th>
		<th>Fecha Presupuesto</th>
		<th>Status</th>
		<th>Total</th>
		<th>Nombre Cliente</th>
		<th>Auto</th>
	</tr>
	<tbody id="autos">
		<?php
		while($budgets = mysqli_fetch_array($selectBudgets)){
		?>
		<tr class="budget" id="element_<?php echo $budgets['id']; ?>">
			<td>
					<input type="checkbox" name="selectionElements[]" value="<?php echo $autos['id'] ?>" />
					<a class="button small mediumGrey modifier popup" href="formBudget.php?id=<?php echo $budgets['id']?>" title="Editar">
						Editar
					</a>
			</td>
			<td><?php echo htmlspecialchars($budgets['id'])?></td>
			<td><?php echo htmlspecialchars($budgets['created_at'])?></td>
			<td><?php echo htmlspecialchars($budgets['status_id'])?></td>
			<td><?php echo htmlspecialchars($budgets['total'])?></td>
			<td><?php echo htmlspecialchars($budgets['client_id'])?></td>
			<td><?php echo htmlspecialchars($budgets['auto_id'])?></td>
		</tr>
		<?php }?>
	</tbody>
	<tr class="tot_trimestre">
    <th colspan="6"></th>
</table>
</form>
