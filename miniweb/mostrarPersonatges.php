#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Mostrar personatges</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Mostrar personatges"); 
    // Busca personatges
	$personatges = "SELECT p.alias, p.despesamensual, p.datacreacio, u.nom || ' ' || u.cognoms AS nomcognoms, t.nom AS nom_tipus, t.descripcio AS descripcio_tipus
				FROM personatges p JOIN tipuspersonatges t ON p.tipuspersonatge = t.nom 
				JOIN usuaris u ON p.usuari = u.alias
				ORDER BY u.alias, p.alias";
	$comanda = oci_parse($conn, $personatges);
	if (!$comanda) { 
	  mostraErrorParser($personatges); // mostrem error
	} 
	$exit = oci_execute($comanda);
	if (!$exit) { 
	  mostraErrorExecucio($comanda); // mostrem error 
	}
	
	// Mostrar
	$numColumnes = oci_num_fields($comanda);
	// Mostrem les capceleres
	echo "<table>\n";
	echo "  <tr>";
	for ($i=1;$i<=$numColumnes; $i++) {
  	  echo "<th>".htmlentities(oci_field_name($comanda, $i), ENT_QUOTES) . "</th>"; 
	}
	echo "</tr>\n";
	
	// Recorrem les files
	while (($fila = oci_fetch_array($comanda, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
	  echo "  <tr>";
 	  foreach ($fila as $element) {
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
