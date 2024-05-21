#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Mostrar premis usuari</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  
	$usuari = "SELECT nom, cognoms FROM usuaris WHERE alias = '".$_POST['usuari']."'";
	$comanda_usuari = oci_parse($conn, $usuari);
	if (!$comanda_usuari) { 
	  mostraErrorParser($usuari); // mostrem error
	} 
	$exit = oci_execute($comanda_usuari);
	if (!$exit) { 
	  mostraErrorExecucio($comanda_usuari); // mostrem error 
	}
	
	$fila = oci_fetch_array($comanda_usuari, OCI_ASSOC + OCI_RETURN_NULLS);
  
	capcalera("Vehicles premiats de l’usuari ".$fila['NOM']." ".$fila['COGNOMS']); 
    
	// Busca premis
	$premis = "SELECT c.codi AS codi_cursa, c.nom AS nom_cursa, c.inicireal, v.codi AS codi_vehicle, 
	v.descripcio AS descripcio_vehicle, pre.personatge, pre.temps, pre.premi 
	FROM premis pre
	JOIN curses c ON c.codi = pre.cursa 
	JOIN vehicles v ON v.codi = pre.vehicle 
	WHERE v.propietari = '".$_POST['usuari']."'
	ORDER BY pre.inici_real";
	$comanda_premis = oci_parse($conn, $premis);
	if (!$comanda_premis) { 
	  mostraErrorParser($premis); // mostrem error
	} 
	$exit = oci_execute($comanda_premis);
	if (!$exit) { 
	  mostraErrorExecucio($comanda_premis); // mostrem error 
	}
	
	$fila = oci_fetch_array($comanda_premis, OCI_ASSOC+OCI_RETURN_NULLS);
	// Mostrar
	if ($fila) {
		// Mostrem les capceleres
		echo "<table>\n";
		echo "  <tr>";
		$numColumnes = oci_num_fields($comanda_premis);
		for ($i=1;$i<=$numColumnes; $i++) {
		  echo "<th>".htmlentities(oci_field_name($comanda_premis, $i), ENT_QUOTES) . "</th>"; 
		}
		echo "</tr>\n";
		
		// Recorrem les files
		do {
		  echo "  <tr>";
		  foreach ($fila as $element) {
			echo "<td>".($element !== null ? 
			htmlentities($element, ENT_QUOTES) : 
			"&nbsp;") . "</td>";
		  }
		  echo "</tr>\n";
		} while (($fila = oci_fetch_array($comanda_premis, OCI_ASSOC+OCI_RETURN_NULLS)) != false);
		echo "</table>\n";
	}
	else {
		echo "Encara no ha guanyat cap cursa";
	}
	
	oci_free_statement($comanda_premis);
	oci_close($conn);
    peu("Tornar al menú principal","menu.php");
?>
</body>
</html>
