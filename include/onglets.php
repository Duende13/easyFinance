<?php
function checkActif($page){
	if (strstr($_SERVER['PHP_SELF'], $page)){
		echo 'class="actif"';
	}
}
?>
		<ul id="onglets">
			<li <?php checkActif('clients.php'); ?>><a href="clients.php<?php if(isset($annee)){ ?>?annee=<?php echo $annee ?><?php } ?>">Clientes</a></li>
			<li <?php checkActif('autos.php'); ?>><a href="autos.php<?php if(isset($annee)){ ?>?annee=<?php echo $annee ?><?php } ?>">Autos</a></li>
			<li <?php checkActif('budgets.php'); ?>><a href="budgets.php<?php if(isset($annee)){ ?>?annee=<?php echo $annee ?><?php } ?>">Presupuestos</a></li>
			<li <?php checkActif('invoices.php'); ?>><a href="invoices.php<?php if(isset($annee)){ ?>?annee=<?php echo $annee ?><?php } ?>">Facturas</a></li>
		</ul>
