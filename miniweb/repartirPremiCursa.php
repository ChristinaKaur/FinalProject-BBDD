#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Repartir premi</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php 
	include 'funcions.php';     
	iniciaSessio();
	connecta($conn);
	capcalera("Repartir premis"); 

	// Busquem cursa
	$consulta = "SELECT codi, nom FROM curses 
						  WHERE codi = '".$_POST['cursa']."'";
	$comanda = oci_parse($conn, $consulta);
	$exit = oci_execute($comanda);
	$fila = oci_fetch_array($comanda); 
  	  
	// Busquem el numero de participants de la cursa
	$participants = "SELECT COUNT(personatge) FROM participantscurses WHERE cursa ='".$_POST['cursa']."'";
	$comanda = oci_parse($conn, $participants);
	$exit = oci_execute($comanda);
    if (!$exit) {
	  mostraErrorExecucio($comanda);
    }
	
	$numP = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)['COUNT(PERSONATGE)'];
  
    // Busquem personatge/s amb millor temps de la cursa
    $personatges = "SELECT u.nom || ' ' || u.cognoms AS nomcog, u.saldo, p.alias, c.premi, pc.temps, c.inicireal, pc.vehicle 
				FROM usuaris u JOIN personatges p ON p.usuari = u.alias JOIN participantsCurses pc ON p.alias = pc.personatge 
				JOIN curses c ON c.codi = pc.cursa 
				WHERE pc.cursa = '".$_POST['cursa']."' 
				AND c.millortemps = pc.temps";
    $comanda = oci_parse($conn, $personatges);
    $exit = oci_execute($comanda);
    if (!$exit) {
	  mostraErrorExecucio($comanda);
    }
    $fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS);
    $premi = $fila['PREMI']/oci_num_fields($comanda);
	  
    echo "<p>S'ha donat el premi al/s guanyador/s de la cursa anomenada ".$_POST['cursa'].": </p>";	 	  
    do {
	  // Modifiquem taula premis
	  $modPremi = "INSERT INTO Premis (personatge, cursa, vehicle, inici_real, temps, premi, n_participants) VALUES (:personatge, :cursa, :vehicle, :inici, :temps, '".$premi."', '".$numP."')";
	  $comanda2 = oci_parse($conn, $modPremi);
	  oci_bind_by_name($comanda2, ":personatge", $fila['ALIAS']);
	  oci_bind_by_name($comanda2, ":cursa", $_POST['cursa']);
	  oci_bind_by_name($comanda2, ":vehicle", $fila['VEHICLE']);
	  oci_bind_by_name($comanda2, ":inici", $fila['INICIREAL']);
	  oci_bind_by_name($comanda2, ":temps", $fila['TEMPS']);
	  $exit = oci_execute($comanda2); 
	  if (!$exit) 
	    mostraErrorExecucio($comanda2);

	  // Modifiquem taula d'usuaris	
	  $nouSaldo = $fila['SALDO']+$premi;

	  $usuaris = "UPDATE usuaris SET saldo = '".$nouSaldo."' WHERE alias = '".$fila['ALIAS']."'";
	  $comanda3 = oci_parse($conn, $usuaris);
	  $exit = oci_execute($comanda3); 
	  if (!$exit) 
	    mostraErrorExecucio($comanda3);	  
	  echo $fila['NOMCOG']." ";	
	} while (($fila = oci_fetch_array($comanda, OCI_ASSOC+OCI_RETURN_NULLS)) != false);

	oci_free_statement($comanda);
	oci_close($conn);
	peu("Tornar al menú principal","menu.php");;
  ?>
</body>
</html>