#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Inserir dia i hora cursa, inserció a la base de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php 
	include 'funcions.php';     
	iniciaSessio();
	connecta($conn);
	capcalera("Inserir dia i hora cursa a la base de dades"); 

	// Busquem cursa
	$consulta = "SELECT codi, nom FROM curses 
						  WHERE codi = '".$_SESSION['codiCursa']."'";
	$comanda = oci_parse($conn, $consulta);
	oci_bind_by_name($comanda, ":cursa",  );
	$exit = oci_execute($comanda);
	$fila = oci_fetch_array($comanda); 
	$nomCursa = $fila['NOM'];
  
	if ($fila){ // existeix una cursa amb codi rebut 
	  // Modifiquem inici real de la cursa
	  $nouInici = $_POST["data"] ." ".$_POST["hora"];
	  oci_free_statement($comanda);
	  $modInici = "UPDATE curses SET iniciReal = TO_DATE(:nouInici, 'YYYY-MM-DD HH24:MI:SS') WHERE codi = :codi";
	  $comanda = oci_parse($conn, $modInici);
	  oci_bind_by_name($comanda, ":codi", $_SESSION['codiCursa'] );
	  oci_bind_by_name($comanda, ":nouInici", $nouInici );
	  $exit = oci_execute($comanda); 
	  if (!$exit) 
		mostraErrorExecucio($comanda);
	  else 
		echo "<p>S'ha modificat inici real de la cursa anomenada ".$nomCursa."</p>";
    } else {
	  echo "<p>No s'ha pogut modificar inici real de la cursa anomenada ".$nomCursa."</p>";
	}

	oci_free_statement($comanda);
	oci_close($conn);
	peu("Tornar al menú principal","menu.php");;
  ?>
</body>
</html>