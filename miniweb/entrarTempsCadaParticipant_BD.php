#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Afegir temps participants, inserció a la base de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php 
	include 'funcions.php';     
	iniciaSessio();
	connecta($conn);
	capcalera("Inserir temps dels participants a la base de dades"); 

	// Busquem personatge participant 
	$consulta = "SELECT personatge FROM participantscurses WHERE cursa=:codi";
	$comanda = oci_parse($conn, $consulta);
	oci_bind_by_name($comanda,":codi",$_SESSION['codiCursa']);
	$exit = oci_execute($comanda);
	$fila = oci_fetch_array($comanda); 
	if ($fila){ // hi ha participants a la cursa 
      // Modifiquem temps de cada participant 
	  $millorTemps = -1;
	  do {
		$temps = $_POST["minuts".$fila["PERSONATGE"]].":".$_POST["segons".$fila["PERSONATGE"]];
	    if ($temps != ":" and ($millorTemps == -1 or ($millorTemps > $temps]))) {
		  $millorTemps = $_POST["temps"];
	    }
	    $sentenciaSQL = "UPDATE participantscurses SET temps = :tempsNou WHERE personatge = :alias";
	    $comanda2 = oci_parse($conn, $sentenciaSQL);
		
	    oci_bind_by_name($comanda2, ":tempsNou", $temps);
	    oci_bind_by_name($comanda2, ":alias", $fila["PERSONATGE"]);
	    $exit = oci_execute($comanda2); 
	  
	    if (!$exit) {
		  mostraErrorExecucio($comanda2);
	    }
	  } while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false);
	  
	  // Modifiquem millor temps cursa 
	  $sentenciaSQL = "UPDATE curses SET millorTemps = :millor WHERE codi = :codi";
	  $comanda = oci_parse($conn, $sentenciaSQL);
	  oci_bind_by_name($comanda, ":millor", $millorTemps);
	  oci_bind_by_name($comanda,":codi",$_SESSION['codiCursa']);
	  $exit = oci_execute($comanda); 
	  if ($exit) {
	    echo "<p>Temps inserits a la base de dades</p>\n";
	  } else {
	    mostraErrorExecucio($comanda);
	  }
    } else {
      echo "<strong>COMPTE! Cursa sense participants</strong>";
    }
    oci_free_statement($comanda);
    oci_close($conn);
    peu("Tornar al menú principal","menu.php");;
  ?>
</body>
</html>