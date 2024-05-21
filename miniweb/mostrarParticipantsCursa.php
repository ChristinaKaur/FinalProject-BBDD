#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Consultar participants cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php
	include 'funcions.php';     
	iniciaSessio();
	connecta($conn);
	
	// Obtenir nom de la cursa
	$cursa = "SELECT nom, millortemps FROM curses WHERE codi = '".$_POST['codi']."'";
	$comanda = oci_parse($conn, $cursa);
	$exit = oci_execute($comanda);
	$fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS);
	if ($fila) { // Cursa feta
	  $capcalera = "Classificació de la cursa ".$fila['NOM'];  
	  $participants = "SELECT v.codi, v.descripcio, p.personatge, 
					CASE 
					  WHEN p.temps IS NULL THEN 'Abandonat' 
					  ELSE to_char(p.temps)
					END AS temps
					FROM participantscurses p JOIN vehicles v ON p.vehicle = v.codi 
					WHERE p.cursa = '".$_POST["codi"]."'
					ORDER BY p.temps";
	}
	else { // Cursa no feta
	  $capcalera = "Llistat de participants de la cursa ".$fila['NOM'];
	  $participants="SELECT v.codi, v.descripcio, p.personatge FROM participantscurses p 
					JOIN vehicles v ON p.vehicle = v.codi 
					WHERE p.cursa = '".$_POST["codi"]."'";
	}
	capcalera($capcalera); 
	$comanda = oci_parse($conn, $participants);
	if (!$comanda) { 
      mostraErrorParser($participants);
	}
	$exit = oci_execute($comanda);
	if (!$exit) { 
	  mostraErrorExecucio($comanda);
	} 
	
    $numColumnes = oci_num_fields($comanda);
	// Mostrem les capceleres
	echo "<table>\n";
	echo "  <tr>";
	for ($i=1;$i<=$numColumnes; $i++) {
  	  echo "<th>".htmlentities(oci_field_name($comanda, $i), ENT_QUOTES) . "</th>"; 
	}
	echo "</tr>\n";
	
	// Recorrem les files
	while (($row = oci_fetch_array($comanda, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
	  echo "  <tr>";
 	  foreach ($row as $element) {
		echo "<td>".($element !== null ? 
		htmlentities($element, ENT_QUOTES) : 
		"&nbsp;") . "</td>";
	  }
	  echo "</tr>\n";
	}
    echo "</table>\n";
  
	oci_free_statement($comanda);
	oci_close($conn);
	peu("Tornar al menú principal","menu.php");
  ?>
</body>
</html>
