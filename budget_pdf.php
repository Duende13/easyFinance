<?php
	require('plugins/fpdf181/fpdf.php');
  include('include/config.php');
  $x_init = 15;
  $y_init = 45;
  // define('FPDF_FONTPATH','fonts/ubuntu/');
  // include('fonts/ubuntu');

	// le mettre au debut car plante si on declare $mysqli avant !
	$pdf = new FPDF( 'P', 'mm', 'A4' );
  // $pdf->AddFont('Ubuntu','B');
  $pdf->AddFont('Ubuntu','','Ubuntu-R.php');
  $pdf->AddFont('Ubuntu-Bold','','Ubuntu-B.php');
	$id = $_GET['id'];

	// on sup les 2 cm en bas
	$pdf->SetAutoPagebreak(False);
	$pdf->SetMargins(10,0,0);

	$pdf->AddPage();

	// logo : 80 de largeur et 55 de hauteur
	$pdf->Image('images/logo.png',10,6,190);

	// Datos de la factura
	$select = 'select created_at,invoice_number,vat,nett,subtotal,total,auto_id,client_id FROM budgets where id =' .$id;
	$result = mysqli_query($db, $select)  or die ('Erreur SQL : ' .$select .mysqli_connect_error() );
	$budget = mysqli_fetch_row($result);
	mysqli_free_result($result);

	// nom du fichier final TODO: NUMERO DE ARCHIVO IGUAL QUE LA FACTURA?
	$nom_file = "fact_" . $budget[1] .'-' . str_pad($budget[1], 4, '0', STR_PAD_LEFT) . ".pdf";

	//Fecha de factura
	$champ_date = date_create($budget[0]);
  $date_fact = date_format($champ_date, 'd/m/Y');
	$pdf->SetFillColor(153); $pdf->Rect($x_init, $y_init+2, 40, 6, "F");
	$pdf->SetXY( $x_init, $y_init );
  $pdf->SetFont( "Ubuntu-Bold", "U", 12 );
  $pdf->SetTextColor(255);
  $pdf->Cell($x_init, 10, "FECHA:", 0, 0, 'L');
	$pdf->SetXY( 55, $y_init );
  $pdf->SetFont( "Ubuntu",'', 12 );
  $pdf->SetTextColor(0);
  $pdf->Cell($x_init, 10, $date_fact, 0, 0, 'L');

	//Numero de factura
	$num_fact = str_pad($budget[1], 6, '0', STR_PAD_LEFT);
	$pdf->SetFillColor(153); $pdf->Rect(120, $y_init+2, 40, 6, "F");
	$pdf->SetXY( 120, $y_init );
  $pdf->SetFont( "Ubuntu-Bold", "U", 12 );
  $pdf->SetTextColor(255);
  $pdf->Cell(120, 10, "FACTURA:", 0, 0, 'L');
	$pdf->SetXY( 160, $y_init );
  $pdf->SetFont( "Ubuntu",'', 12 );
  $pdf->SetTextColor(0);
  $pdf->Cell(120, 10, $num_fact, 0, 0, 'L');

	// Datos  cliente
	$selectClient = 'select name,surname, company_name, tel, email, dni, address_id FROM clients where id =' .$budget[7];
	$resultClient = mysqli_query($db, $selectClient)  or die ('Erreur SQL : ' .$selectClient .mysqli_connect_error() );
	$client = mysqli_fetch_row($resultClient);
	// Datos  direccion
	$selectAddress = 'select address1,address2,address3, city,postcode  FROM addresses where id =' .$client[6];
	$resultAddress = mysqli_query($db, $selectAddress)  or die ('Erreur SQL : ' .$selectAddress .mysqli_connect_error() );
	$address = mysqli_fetch_row($resultAddress);
	// Datos  auto
	$selectAuto = 'select plate_number,brand,model,motor,tires,chassis_number,registration_year,kws,kilometers  FROM autos where id =' .$budget[6];
	$resultAuto = mysqli_query($db, $selectAuto)  or die ('Erreur SQL : ' .$selectAuto .mysqli_connect_error() );
	$auto = mysqli_fetch_row($resultAuto);

  //cliente header
	$pdf->SetFillColor(153); $pdf->Rect($x_init, 62, 180, 6, "F");
  $pdf->SetLineWidth(0.1);$pdf->Rect($x_init, 62, 180, 45, "D");
	$pdf->SetXY( $x_init, 60 ); $pdf->SetFont( "Ubuntu-Bold", "U", 11 );
  $pdf->SetTextColor(255); $pdf->Cell($x_init, 10, "Datos del Cliente:", 0, 0, 'L');
  //Nombre
  $pdf->SetXY( $x_init, 67 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init, 10, "Nombre:", 0, 0, 'L');
  $pdf->SetXY( $x_init + 25, 67 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $client[0] . ' ' . $client[1], 0, 0, 'L');
  //Marca
  $pdf->SetXY($x_init+100, 67 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init+100, 10, "Marca:", 0, 0, 'L');
  $pdf->SetXY( $x_init + 120, 67 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $auto[1], 0, 0, 'L');
  //Año
  $pdf->SetXY($x_init+148, 67 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init+148, 10, utf8_decode("Año:"), 0, 0, 'L');
  $pdf->SetXY( $x_init + 160, 67 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $auto[6], 0, 0, 'L');
  //Direccion
  $pdf->SetXY( $x_init, 74 );$pdf->SetFont( "Ubuntu-Bold", "", 11 );$pdf->SetTextColor(0);
  $pdf->Cell($x_init, 10, utf8_decode("Dirección:"), 0, 0, 'L');
  $pdf->SetXY( $x_init + 25, 74 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $address[0] . ' ' . $address[1] . ' ' . $address[2] . ', ' . $address[4] . ' - ' . $address[3], 0, 0, 'L');
  //Modelo
  $pdf->SetXY($x_init+100, 74 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init+100, 10, "Modelo:", 0, 0, 'L');
  $pdf->SetXY( $x_init + 120, 74 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $auto[2], 0, 0, 'L');
  //Telefono
  $pdf->SetXY( $x_init, 81 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init, 10, utf8_decode("Teléfono:"), 0, 0, 'L');
  $pdf->SetXY( $x_init + 25, 81 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $client[3], 0, 0, 'L');
  //Motor
  $pdf->SetXY($x_init+100, 81 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init+100, 10, "Motor:", 0, 0, 'L');
  $pdf->SetXY( $x_init + 120, 81 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $auto[3], 0, 0, 'L');
  //kws
  $pdf->SetXY($x_init+148, 81 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init+148, 10, utf8_decode("Kws:"), 0, 0, 'L');
  $pdf->SetXY( $x_init + 160, 81 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $auto[7], 0, 0, 'L');
  //Email
  $pdf->SetXY( $x_init, 88 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init, 10, "E-mail:", 0, 0, 'L');
  $pdf->SetXY( $x_init + 25, 88 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $client[4], 0, 0, 'L');
  //Bastidor
  $pdf->SetXY($x_init+100, 88 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init+100, 10, "Bastidor:", 0, 0, 'L');
  $pdf->SetXY( $x_init + 120, 88 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $auto[5], 0, 0, 'L');
  //DNI
  $pdf->SetXY( $x_init, 95 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init, 10, "DNI:", 0, 0, 'L');
  $pdf->SetXY( $x_init + 25, 95 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $client[5], 0, 0, 'L');
  //Matricula
  $pdf->SetXY($x_init+100, 95 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init+100, 10, utf8_decode("Matrícula:"), 0, 0, 'L');
  $pdf->SetXY( $x_init + 120, 95 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $auto[0], 0, 0, 'L');
  //kilometers
  $pdf->SetXY($x_init+148, 95 ); $pdf->SetFont( "Ubuntu-Bold", "", 11 ); $pdf->SetTextColor(0);
  $pdf->Cell($x_init+148, 10, utf8_decode("Kms:"), 0, 0, 'L');
  $pdf->SetXY( $x_init + 160, 95 ); $pdf->SetFont( "Ubuntu", "", 11 );
  $pdf->Cell($x_init, 10, $auto[8], 0, 0, 'L');

  //tabla header
	$pdf->SetFillColor(153); $pdf->Rect($x_init, 112, 180, 6, "F");
  $pdf->SetLineWidth(0.1);$pdf->Rect($x_init, 112, 180, 105, "D");
	$pdf->SetXY($x_init+5, 110 ); $pdf->SetFont( "Ubuntu-Bold", "U", 11 );
  $pdf->SetTextColor(255); $pdf->Cell($x_init, 10, utf8_decode("CÓDIGO"), 0, 0, 'L');
  $pdf->SetXY($x_init+30, 110 );  $pdf->Cell($x_init, 10, utf8_decode("DESCRIPCIÓN"), 0, 0, 'L');
  $pdf->SetXY($x_init+75, 110 );$pdf->Cell($x_init, 10, "UND", 0, 0, 'L');
  $pdf->SetXY($x_init+90, 110 );$pdf->Cell($x_init, 10, "PRECIO", 0, 0, 'L');
  $pdf->SetXY($x_init+110, 110 );$pdf->Cell($x_init, 10, "DCTO", 0, 0, 'L');
  $pdf->SetXY($x_init+125, 110 );$pdf->Cell($x_init, 10, "DTO TOTAL", 0, 0, 'L');
  $pdf->SetXY($x_init+150, 110 ); $pdf->Cell($x_init, 10, "TOTAL", 0, 0, 'L');

  $sqlBudgetServ = 'SELECT bs.discount,bs.price, bs.amount,bs.total, bs.total_dcto, services.name, services.description FROM budget_services as bs INNER JOIN services ON service_id = services.id WHERE budget_id='.$id;
  $resultBudgetServ = mysqli_query($db, $sqlBudgetServ)  or die ('Erreur SQL : ' .$sqlBudgetServ .mysqli_connect_error() );


// 	$sql .= ' LIMIT ' . $limit_inf . ',' . $limit_sup;
// 	$res = mysqli_query($mysqli, $sql)  or die ('Erreur SQL : ' .$sql .mysqli_connect_error() );
$y=110;
 $pdf->SetFont( "Ubuntu", "", 10 );$pdf->SetTextColor(0);
  while ($budget_service =  mysqli_fetch_assoc($resultBudgetServ))
	{
		// libelle
		$pdf->SetXY( $x_init+5, $y+9 ); $pdf->Cell( 140, 5, $budget_service['name'], 0, 0, 'L');
		$pdf->SetXY( $x_init+30, $y+9 ); $pdf->Cell( 13, 5, strrev(wordwrap(strrev($budget_service['description']), 3, ' ', true)), 0, 0, 'L');
		$pdf->SetXY( $x_init+75, $y+9 ); $pdf->Cell( 18, 5, $budget_service['amount'], 0, 0, 'L');
		$pdf->SetXY( $x_init+90, $y+9 ); $pdf->Cell( 10, 5, $budget_service['price'].utf8_decode("€"), 0, 0, 'R');
		$pdf->SetXY( $x_init+110, $y+9 ); $pdf->Cell( 18, 5, $budget_service['discount'].utf8_decode("%"), 0, 0, 'R');
		$pdf->SetXY( $x_init+125, $y+9 ); $pdf->Cell( 18, 5, $budget_service['total_dcto'].utf8_decode("€"), 0, 0, 'R');
    $pdf->SetXY( $x_init+150, $y+9 ); $pdf->Cell( 18, 5, $budget_service['total'].utf8_decode("€"), 0, 0, 'R');
		$pdf->Line($x_init, $y+14, 195, $y+14);
		$y += 6;
	}
// 	mysqli_free_result($res);

		// // date facture
		// $champ_date = date_create($row[0]); $date_fact = date_format($champ_date, 'd/m/Y');
		// $pdf->SetFont('Arial','',11); $pdf->SetXY( 122, 30 );
		// $pdf->Cell( 120, 8, $date_fact, 0, 0, '');
		// $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
		// $pdf->SetXY( 120, 65 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');
// }
		// si derniere page alors afficher total
	// 	if ($num_page == $nb_page)
	// 	{
	// 		// les totaux, on n'affiche que le HT. le cadre apr�s les lignes, demarre a 213
	// 		$pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(5, 213, 90, 8, "DF");
	// 		// HT, la TVA et TTC sont calcul�s apr�s
	// 		$total_budget_neto = "TOTAL NETO : " . number_format($row[3], 2, ',', ' ') . "€";
	// 		$pdf->SetFont('Arial','',10); $pdf->SetXY( 95, 213 ); $pdf->Cell( 63, 8, $total_budget_neto, 0, 0, 'C');
	// 		// en bas � droite
	// 		$pdf->SetFont('Arial','B',8); $pdf->SetXY( 181, 227 ); $pdf->Cell( 24, 6, number_format($row[3], 2, ',', ' '), 0, 0, 'R');
	//
	// 		// trait vertical cadre totaux, 8 de hauteur -> 213 + 8 = 221
	// 		$pdf->Rect(5, 213, 200, 8, "D"); $pdf->Line(95, 213, 95, 221); $pdf->Line(158, 213, 158, 221);
	//
	// 		// reglement
	// 		$pdf->SetXY( 0, 225 ); $pdf->Cell( 38, 5, "FORMA DE PAGO :", 0, 0, 'R');
	// 		//$pdf->Cell( 55, 5, $row[6], 0, 0, 'L');
	// 		// echeance
	// 		// $champ_date = date_create($row[7]); $date_ech = date_format($champ_date, 'd/m/Y');
	// 		// $pdf->SetXY( 5, 230 ); $pdf->Cell( 38, 5, "Date Ech�ance :", 0, 0, 'R'); $pdf->Cell( 38, 5, $date_ech, 0, 0, 'L');
	// 	}
	//
	// 	// observations
	// 	$pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 75 ) ; $pdf->Cell($pdf->GetStringWidth("Observations"), 0, "Observations", 0, "L");
	// 	$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 5, 78 ) ; $pdf->MultiCell(190, 4, $row[5], 0, "L");
	//
	// 	// adr fact du client
	// 	$select = "select nom,adr1,adr2,adr3,cp,ville,num_tva from client c join facture f on c.id_client=f.id_client where id_facture=" . $id;
	// 	$result = mysqli_query($mysqli, $select)  or die ('Erreur SQL : ' .$select .mysqli_connect_error() );
	// 	$row_client = mysqli_fetch_row($result);
	// 	mysqli_free_result($result);
	// 	$pdf->SetFont('Arial','B',11); $x = 110 ; $y = 50;
	// 	$pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[0], 0, 0, ''); $y += 4;
	// 	if ($row_client[1]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[1], 0, 0, ''); $y += 4;}
	// 	if ($row_client[2]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[2], 0, 0, ''); $y += 4;}
	// 	if ($row_client[3]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[3], 0, 0, ''); $y += 4;}
	// 	if ($row_client[4] || $row_client[5]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[4] . ' ' .$row_client[5] , 0, 0, ''); $y += 4;}
	// 	if ($row_client[6]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, 'N� TVA Intra : ' . $row_client[6], 0, 0, '');}
	//
	// 	// ***********************
	// 	// le cadre des articles
	// 	// ***********************
	// 	// cadre avec 18 lignes max ! et 118 de hauteur --> 95 + 118 = 213 pour les traits verticaux
	// 	$pdf->SetLineWidth(0.1); $pdf->Rect(5, 95, 200, 118, "D");
	// 	// cadre titre des colonnes
	// 	$pdf->Line(5, 105, 205, 105);
	// 	// les traits verticaux colonnes
	// 	$pdf->Line(145, 95, 145, 213); $pdf->Line(158, 95, 158, 213); $pdf->Line(176, 95, 176, 213); $pdf->Line(187, 95, 187, 213);
	// 	// titre colonne
	// 	$pdf->SetXY( 1, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 140, 8, "Libell�", 0, 0, 'C');
	// 	$pdf->SetXY( 145, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 13, 8, "Qt�", 0, 0, 'C');
	// 	$pdf->SetXY( 156, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, "PU HT", 0, 0, 'C');
	// 	$pdf->SetXY( 177, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 10, 8, "TVA", 0, 0, 'C');
	// 	$pdf->SetXY( 185, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, "TOTAL HT", 0, 0, 'C');
	//
	// 	// les articles
	// 	$pdf->SetFont('Arial','',8);
	// 	$y = 97;
	// 	// 1ere page = LIMIT 0,18 ;  2eme page = LIMIT 18,36 etc...
	// 	$sql = 'select libelle,qte,pu,taux_tva FROM ligne_facture where id_facture=' .$id . ' order by libelle';
	// 	$sql .= ' LIMIT ' . $limit_inf . ',' . $limit_sup;
	// 	$res = mysqli_query($mysqli, $sql)  or die ('Erreur SQL : ' .$sql .mysqli_connect_error() );
	// 	while ($data =  mysqli_fetch_assoc($res))
	// 	{
	// 		// libelle
	// 		$pdf->SetXY( 7, $y+9 ); $pdf->Cell( 140, 5, $data['libelle'], 0, 0, 'L');
	// 		// qte
	// 		$pdf->SetXY( 145, $y+9 ); $pdf->Cell( 13, 5, strrev(wordwrap(strrev($data['qte']), 3, ' ', true)), 0, 0, 'R');
	// 		// PU
	// 		$nombre_format_francais = number_format($data['pu'], 2, ',', ' ');
	// 		$pdf->SetXY( 158, $y+9 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');
	// 		// Taux
	// 		$nombre_format_francais = number_format($data['taux_tva'], 2, ',', ' ');
	// 		$pdf->SetXY( 177, $y+9 ); $pdf->Cell( 10, 5, $nombre_format_francais, 0, 0, 'R');
	// 		// total
	// 		$nombre_format_francais = number_format($data['pu']*$data['qte'], 2, ',', ' ');
	// 		$pdf->SetXY( 187, $y+9 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');
	//
	// 		$pdf->Line(5, $y+14, 205, $y+14);
	//
	// 		$y += 6;
	// 	}
	// 	mysqli_free_result($res);
	//
	// 	// si derniere page alors afficher cadre des TVA
	// 	if ($num_page == $nb_page)
	// 	{
	// 		// le detail des totaux, demarre a 221 apr�s le cadre des totaux
	// 		$pdf->SetLineWidth(0.1); $pdf->Rect(130, 221, 75, 24, "D");
	// 		// les traits verticaux
	// 		$pdf->Line(147, 221, 147, 245); $pdf->Line(164, 221, 164, 245); $pdf->Line(181, 221, 181, 245);
	// 		// les traits horizontaux pas de 6 et demarre a 221
	// 		$pdf->Line(130, 227, 205, 227); $pdf->Line(130, 233, 205, 233); $pdf->Line(130, 239, 205, 239);
	// 		// les titres
	// 		$pdf->SetFont('Arial','B',8); $pdf->SetXY( 181, 221 ); $pdf->Cell( 24, 6, "TOTAL", 0, 0, 'C');
	// 		$pdf->SetFont('Arial','',8);
	// 		$pdf->SetXY( 105, 221 ); $pdf->Cell( 25, 6, "Taux TVA", 0, 0, 'R');
	// 		$pdf->SetXY( 105, 227 ); $pdf->Cell( 25, 6, "Total HT", 0, 0, 'R');
	// 		$pdf->SetXY( 105, 233 ); $pdf->Cell( 25, 6, "Total TVA", 0, 0, 'R');
	// 		$pdf->SetXY( 105, 239 ); $pdf->Cell( 25, 6, "Total TTC", 0, 0, 'R');
	//
	// 		// les taux de tva et HT et TTC
	// 		$col_ht = 0; $col_tva = 0; $col_ttc = 0;
	// 		$taux = 0; $tot_tva = 0; $tot_ttc = 0;
	// 		$x = 130;
	// 		$sql = 'select taux_tva,sum( round(pu * qte,2) ) tot_ht FROM ligne_facture where id_facture=' .$id . ' group by taux_tva order by taux_tva';
	// 		$res = mysqli_query($mysqli, $sql)  or die ('Erreur SQL : ' .$sql .mysqli_connect_error() );
	// 		while ($data =  mysqli_fetch_assoc($res))
	// 		{
	// 			$pdf->SetXY( $x, 221 ); $pdf->Cell( 17, 6, $data['taux_tva'] . ' %', 0, 0, 'C');
	// 			$taux = $data['taux_tva'];
	//
	// 			$nombre_format_francais = number_format($data['tot_ht'], 2, ',', ' ');
	// 			$pdf->SetXY( $x, 227 ); $pdf->Cell( 17, 6, $nombre_format_francais, 0, 0, 'R');
	// 			$col_ht = $data['tot_ht'];
	//
	// 			$col_tva = $col_ht - ($col_ht * (1-($taux/100)));
	// 			$nombre_format_francais = number_format($col_tva, 2, ',', ' ');
	// 			$pdf->SetXY( $x, 233 ); $pdf->Cell( 17, 6, $nombre_format_francais, 0, 0, 'R');
	//
	// 			$col_ttc = $col_ht + $col_tva;
	// 			$nombre_format_francais = number_format($col_ttc, 2, ',', ' ');
	// 			$pdf->SetXY( $x, 239 ); $pdf->Cell( 17, 6, $nombre_format_francais, 0, 0, 'R');
	//
	// 			$tot_tva += $col_tva ; $tot_ttc += $col_ttc;
	//
	// 			$x += 17;
	// 		}
	// 		mysqli_free_result($res);
	//
	// 		$nombre_format_francais = "Net � payer TTC : " . number_format($tot_ttc, 2, ',', ' ') . " �";
	// 		$pdf->SetFont('Arial','B',12); $pdf->SetXY( 5, 213 ); $pdf->Cell( 90, 8, $nombre_format_francais, 0, 0, 'C');
	// 		// en bas � droite
	// 		$pdf->SetFont('Arial','B',8); $pdf->SetXY( 181, 239 ); $pdf->Cell( 24, 6, number_format($tot_ttc, 2, ',', ' '), 0, 0, 'R');
	// 		// TVA
	// 		$nombre_format_francais = "Total TVA : " . number_format($tot_tva, 2, ',', ' ') . " �";
	// 		$pdf->SetFont('Arial','',10); $pdf->SetXY( 158, 213 ); $pdf->Cell( 47, 8, $nombre_format_francais, 0, 0, 'C');
	// 		// en bas � droite
	// 		$pdf->SetFont('Arial','B',8); $pdf->SetXY( 181, 233 ); $pdf->Cell( 24, 6, number_format($tot_tva, 2, ',', ' '), 0, 0, 'R');
	// 	}
	//
	// 	// **************************
	// 	// pied de page
	// 	// **************************
	// 	$pdf->SetLineWidth(0.1); $pdf->Rect(5, 260, 200, 6, "D");
	// 	$pdf->SetXY( 1, 260 ); $pdf->SetFont('Arial','',7);
	// 	$pdf->Cell( $pdf->GetPageWidth(), 7, "Clause de r�serve de propri�t� (loi 80.335 du 12 mai 1980) : Les marchandises vendues demeurent notre propri�t� jusqu'au paiement int�gral de celles-ci.", 0, 0, 'C');
	//
	// 	$y1 = 270;
	// 	//Positionnement en bas et tout centrer
	// 	$pdf->SetXY( 1, $y1 ); $pdf->SetFont('Arial','B',10);
	// 	$pdf->Cell( $pdf->GetPageWidth(), 5, "REF BANCAIRE : FR76 xxx - BIC : xxxx", 0, 0, 'C');
	//
	// 	$pdf->SetFont('Arial','',10);
	//
	// 	$pdf->SetXY( 1, $y1 + 4 );
	// 	$pdf->Cell( $pdf->GetPageWidth(), 5, "NOM SOCIETE", 0, 0, 'C');
	//
	// 	$pdf->SetXY( 1, $y1 + 8 );
	// 	$pdf->Cell( $pdf->GetPageWidth(), 5, "ADRESSE 1 + CP + VILLE", 0, 0, 'C');
	//
	// 	$pdf->SetXY( 1, $y1 + 12 );
	// 	$pdf->Cell( $pdf->GetPageWidth(), 5, "Tel + Mail + SIRET", 0, 0, 'C');
	//
	// 	$pdf->SetXY( 1, $y1 + 16 );
	// 	$pdf->Cell( $pdf->GetPageWidth(), 5, "Adresse web", 0, 0, 'C');
	//
	// 	// par page de 18 lignes
	// 	$num_page++; $limit_inf += 18; $limit_sup += 18;
	// }

	$pdf->Output("I", $nom_file);
?>
