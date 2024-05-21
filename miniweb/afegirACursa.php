#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Inscriure participants</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php
	include 'funcions.php';     
	iniciaSessio();
	connecta($conn);
	capcalera("Inscriure participants"); 
  ?>
  <form action="afegirVehicle.php" method="post">
	<h3>Participants</h3>  
	<?php
	  // Guardem cursa i mostrem inscripico
	  if ($_SESSION['cursaNova'] == 'cert')
		$_SESSION['codiCursa'] = $_POST['codi'];
  
	  // Obtenir nom i preu inscripció de la cursa
	  $preu = "SELECT inscripcio, nom FROM curses WHERE codi = '".$_POST['codi']."'";
	  $comanda = oci_parse($conn, $preu);
	  $exit = oci_execute($comanda);
	  $fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS);
	  $_SESSION['inscripcioCursa'] = $fila['INSCRIPCIO'];
	  $nomCursa = $fila['NOM'];
      echo "Inscripcio de la cursa " . $nomCursa . ": " . $_SESSION['inscripcioCursa'];
	?>
	
	<p><label>Personatge</label>
	<select name="personatge">
	<?php // Triem personatge
	  // Seleccionem personatge
      $personatges = "SELECT p.alias, u.saldo FROM personatges p JOIN usuaris u ON p.usuari = u.alias";
      $comanda = oci_parse($conn, $personatges);
	
      $exit = oci_execute($comanda);
      if (!$exit){
        mostraErrorExecucio($comanda);
      }
      while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "<option value=\"" . $fila['ALIAS'] . "\">" . $fila['ALIAS'] . " (";
		if ($fila['SALDO'] != 0)
		  echo $fila['SALDO'];
		else 
		  echo "0";
		echo "€)</option>\n";
      }
    ?>   
	</select></p>
    <p><label>&nbsp;</label><input type = "submit" value="Afegir"></p>
  </form>
  <?php 
    peu("Tornar","inscriureCursa.php");;
  ?>
</body>
</html>