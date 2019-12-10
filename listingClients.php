<script>
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search_text");
  filter = input.value.toUpperCase();
  table = document.getElementById("clientsTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td_dni = tr[i].getElementsByTagName("td")[1];
    td_name = tr[i].getElementsByTagName("td")[2];
    td_surname = tr[i].getElementsByTagName("td")[3];
    if (td_dni) {//or (td_name) or (td_surname)) {
      txtValue_dni = td_dni.textContent || td_dni.innerText;
      txtValue_name = td_name.textContent || td_name.innerText;
      txtValue_surname = td_surname.textContent || td_surname.innerText;
      if ((txtValue_dni.toUpperCase().indexOf(filter) > -1) || (txtValue_name.toUpperCase().indexOf(filter) > -1) || (txtValue_surname.toUpperCase().indexOf(filter) > -1))
			{
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
<?php
$selectClients = "SELECT * FROM `clients` ORDER BY `surname`";
if (isset($_GET['ajaxed']) && $_GET['ajaxed'] == 1) {
	include ('classes/interface.class.php');
	$myInterface = new interface_();
}
$type = "clients";
$selectClients = mysqli_query($db, $selectClients) or trigger_error(mysqli_error($db),E_USER_ERROR);
?>
<form id="listing" action="requetes/traitements.php" method="post">
	<p class="tools">
		<a href="formClient.php" class="button medium orange" id="boutonAjouterClient" title="Nuevo Cliente">Nuevo Cliente</a>
		<input type="submit" class="button medium mediumGrey" value="Eliminar los clientes seleccionados" id="boutonSupprimer" name="boutonSupprimer" />
		<input type="text" class="search_box" id="search_text" onkeyup="myFunction()" placeholder="Search for names..">
		<input type="hidden" name="type" value="<?php echo $type ?>" id="type" />
		<input type="hidden" value="clients" name="table" />
	</p>

<h3>Clientes</h3>

<table id="clientsTable">
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
		<tr class="client" id="element_<?php echo $clients['id']; ?>">
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
