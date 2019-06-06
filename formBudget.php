<?php
include ('classes/interface.class.php');
$myInterface = new interface_();
$id = !empty($_GET['id']) ? $_GET['id'] : '';
$auto_id = !empty($_GET['auto_id']) ? $_GET['auto_id'] : '';
$client_id = !empty($_GET['client_id']) ? $_GET['client_id'] : '';
$selectBudget = mysqli_query($db,"SELECT * FROM `budgets` WHERE `id` ='".$id."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
$budget = mysqli_fetch_array($selectBudget);
if (is_array($budget)) {
	extract($budget);
	$selectBudgetServices = mysqli_query($db,"SELECT `budget_services`.*, `services`.`name`, `services`.`description` FROM `budget_services` INNER JOIN `services`
		ON `service_id` = `services`.`id` WHERE `budget_id`='".$id."'") or trigger_error(mysqli_error($db),E_USER_ERROR);
} else {
	$id = '';
  $created_at = '';
  $presented_at = '';
  $paid_at = '';
  $invoice_number = '';
  $notes = '';
  $total = 0.0;
  $vat = 0.0;
  $nett = 0.0;
  $status_id = '';
	$budget_services =[];
	$service_budget_id  ='';
}
if (!isset($_GET['ajaxed'])) {
	$myInterface->set_title("EasyFinance – Presupuesto");
	$myInterface->get_header();
}
?>
<script>
$(document).ready(function () {
// we used jQuery 'keyup' to trigger the computation as the user type
$("input.amount, input.price, input.discount").keyup(function () {
	var id = this.id;
	var splitid = id.split('_');
	var index = splitid[1];
	var amount = parseFloat($("#amount_"+index).val())|| 0;
	var price = parseFloat($("#price_"+index).val()) || 0;
	var subtotal = amount * price;
	var discount = parseFloat($("#discount_"+index).val()) || 0;
	var final_discount = (discount/100) * subtotal;
	$("#total_"+index).val(parseFloat(subtotal - final_discount).toFixed(2));
  var sum = 0;
  var i = 0;
	while (i < 15) {
      sum += parseFloat($("#total_"+i).val()) || 0;;
      i++;
  }
	$('#nett').val(parseFloat(sum).toFixed(2));
	var vat_calculation = (21/100) * sum;
	$('#vat').val(parseFloat(vat_calculation).toFixed(2));
  // set the computed value to 'total' textbox
  $('#total').val(parseFloat(sum + vat_calculation).toFixed(2));

});
});
</script>
<script type="text/javascript">

// $(document).ready(function(){
 //$(".service_name").keydown(function(){
 $("input.service_name").live("keydown.autocomplete", function() {
	var id = this.id;
  var splitid = id.split('_');
  var index = splitid[1];
  $( '#'+id ).autocomplete({
      source: function( request, response ) {
          $.ajax({
              url: "requetes/getDetails.php",
              type: 'post',
              dataType: "json",
              data: {
                  search: request.term,request:1
              },
              success: function( data ) {
                  response( data );
              }
          });
      },
      select: function (event, ui) {
				$(this).val(ui.item.label); // display the selected text
				var serviceid = ui.item.value; // selected id to input

        // AJAX
        $.ajax({
          url: 'requetes/getDetails.php',
          type: 'post',
          data: {serviceid:serviceid,request:2},
          dataType: 'json',
          success:function(response) {
						var len = response.length;
            if(len > 0){
              var service_id = response[0]['serviceid'];
              var service_name = response[0]['name'];
              var service_description = response[0]['description'];
              var service_price = response[0]['price'];

              document.getElementById('name_'+index).value = service_name;
              document.getElementById('description_'+index).value = service_description;
              document.getElementById('price_'+index).value = service_price;
              document.getElementById('serviceid_'+index).value = service_id;
						}
					}
				});
				return false;
			}
		});
	});
	</script>
		<form id="budget" method="post" action="requetes/insertBudget.php">
			<fieldset>
				<input type="hidden" id="id" name="id" value="<?php echo $id?>">
				<input type="hidden" id="client_id" name="client_id" value="<?php echo $client_id?>">
				<input type="hidden" id="auto_id" name="auto_id" value="<?php echo $auto_id?>">
					<div class="container">
			<table border='1' style='border-collapse: collapse;'>
			 <thead>
				<tr>
				 <th>Creado en</th>
				 <th>Presentado en</th>
				 <th>Pagado en</th>
				 <th>Numero de Factura</th>
				 <th colspan="3">Observaciones</th>
				</tr>
			 </thead>
			 <tbody>
				<tr>
				 <td><input name="created_at" type="date" value="<?php echo ($created_at)?>" id="created_at" /></td>
				 <td><input name="presented_at" type="date" value="<?php echo ($presented_at)?>" id="presented_at" /></td>
				 <td><input name="paid_at" type="date" value="<?php echo ($paid_at)?>" id="paid_at" /></td>
			   <td><input name="invoice_number" type="text" value="<?php echo htmlspecialchars($invoice_number)?>" id="invoice_number"/></td>
				 <td colspan="3"><textarea value="<?php echo ($notes)?>" id='notes' name='notes'></textarea></td>
				</tr>
			  </tbody>
			 </table>
			 <table border='1' style='border-collapse: collapse;'>
			  <thead>
			   <tr>
			    <th>Codigo</th>
			    <th>Descripcion</th>
			    <th>Unid.</th>
			    <th>Precio</th>
			    <th>Dcto</th>
			    <th>Total</th>
			    <th>Total Dcto</th>
			   </tr>
			  </thead>
			  <tbody>
					<?php $i = 1;
					while(($budget_service = mysqli_fetch_array($selectBudgetServices)) || ($i <= 12)){
					?>
			   <tr class='tr_input'>
			    <td><input type='text' class='service_name' id='name_<?php echo ($i)?>' name='name_<?php echo ($i)?>' placeholder='' value="<?php if(isset($budget_service['name']))echo $budget_service['name']?>"/></td>
			    <td><input type='text' class='name' id='description_<?php echo ($i)?>' name='description_<?php echo ($i)?>' value="<?php if(isset($budget_service['description']))echo $budget_service['description']?>"/></td>
			    <td><input type='text' class='smallinput amount' id='amount_<?php echo ($i)?>' name='amount_<?php echo ($i)?>' value="<?php if(isset($budget_service['amount']))echo $budget_service['amount']?>"/></td>
			    <td><input type='text' class='smallinput price' id='price_<?php echo ($i)?>'  name='price_<?php echo ($i)?>' value="<?php if(isset($budget_service['price']))echo $budget_service['price']?>"/>€</td>
			    <td>-<input type='text' class='smallinput discount' id='discount_<?php echo ($i)?>' name='discount_<?php echo ($i)?>' value="<?php if(isset($budget_service['discount']))echo $budget_service['discount']?>"/>%</td>
			    <td><input type='text' class='smallinput total' id='total_<?php echo ($i)?>' name='total_<?php echo ($i)?>'  value="<?php if(isset($budget_service['total']))echo $budget_service['total']?>"/>€</td>
			    <td><input type='text' class='smallinput' id='totaldiscount_<?php echo ($i)?>' name='totaldiscount_<?php echo ($i)?>' value="<?php if(isset($budget_service['total_discount']))echo $budget_service['total_discount']?>"/>€</td>
					<input type="hidden" id="serviceid_<?php echo ($i)?>" name="serviceid_<?php echo ($i)?>" value="<?php if(isset($budget_service['service_id']))echo $budget_service['service_id']?>"/>
					<input type="hidden" id="servicebudgetid_<?php echo ($i)?>" name="servicebudgetid_<?php echo ($i)?>" value="<?php if(isset($budget_service['id']))echo $budget_service['id']?>"/>
			   </tr>
			 <?php
				 $i++;
		 		}?>
				 <tr >
					<td colspan="4"></td>
					<td>
						<b>Total Neto :</b> <input name="nett" type="text" value="<?php echo htmlspecialchars($nett)?>" id="nett"/>€
					</td>
					<td>
						<b>Vat (21%) :</b> <input name="vat" type="text" value="<?php echo htmlspecialchars($vat)?>" id="vat"/>€
					</td>
					<td>
						<b>Total :</b> <input name="total" type="text" value="<?php echo htmlspecialchars($total)?>" id="total"/>€
					</td>
				 </tr>
			  </tbody>
			 </table>
			 <br>
			 <!-- <input type='button' value='Add more' id='addmore'> -->
			</div>
			</fieldset>
			<p>
				<input type="submit" type="text" value="Guardar" id="validation" />
			</p>
		</form>
<?php $myInterface->get_footer(); ?>
