#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Inscriure participants a cursa, inserció a la base de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php 
	include 'funcions.php';     
	iniciaSessio();
	connecta($conn);
	capcalera("Inserir participants de cursa a la base de dades"); 
  
	$_SESSION['cursaNova'] = 'fals';

	// Busca participant
	$consulta = "SELECT personatge FROM participantscurses 
						  WHERE personatge = :personatge AND cursa = :cursa";
	$comanda = oci_parse($conn, $consulta);
	oci_bind_by_name($comanda, ":cursa", $_SESSION['codiCursa'] );
	oci_bind_by_name($comanda, ":personatge", $_SESSION['aliasPers'] );
	$exit = oci_execute($comanda);
	$fila = oci_fetch_array($comanda); 
  
	if (!$fila){ // no existeix cap participant amb l'usuari rebut 
      // Consulta saldo usuari
	  $consultaSaldo = "SELECT u.saldo, u.alias FROM usuaris u JOIN personatges p ON p.usuari = u.alias 
					  WHERE p.alias = :personatge AND u.saldo >= :inscripcio";
      $comanda = oci_parse($conn, $consultaSaldo);
      oci_bind_by_name($comanda, ":personatge", $_SESSION['aliasPers'] );
      oci_bind_by_name($comanda, ":inscripcio", $_SESSION['inscripcioCursa']);
      $exit = oci_execute($comanda);
      $fila = oci_fetch_array($comanda);
 
      if ($fila) { // Usuari te prou saldo
	    // Incerim participant nou a la cursa
        oci_free_statement($comanda);
		$sentenciaSQL = "INSERT INTO ParticipantsCurses (cursa, vehicle, personatge, temps) 
						VALUES (:cursa, :vehicle, :personatge, NULL)";
		$comanda = oci_parse($conn, $sentenciaSQL);
		oci_bind_by_name($comanda, ":cursa", $_SESSION['codiCursa'] );
		oci_bind_by_name($comanda, ":personatge", $_SESSION['aliasPers'] );
		oci_bind_by_name($comanda, ":vehicle", $_POST["codi"]);
		$exit = oci_execute($comanda); 
		if ($exit) {
          echo "<p>Nou participant amb alias " . $_SESSION['aliasPers'] . " inserit a la base de dades.</p>\n";
		} else {
          mostraErrorExecucio($comanda);
		}
		
		// Modifiquem saldo de l'usuari
		$nouSaldo = $fila['SALDO'] - $_SESSION['inscripcioCursa'];
		$modSaldo = "UPDATE usuaris SET saldo = ".$nouSaldo." WHERE alias = '".$fila['ALIAS']."'";
		$comanda = oci_parse($conn, $modSaldo);
		$exit = oci_execute($comanda);
		if ($exit) {
          echo "<p>Saldo modificat.</p>\n";
		} else {
          mostraErrorExecucio($comanda);
		} 
	  } else {
	    echo "<p>No s'ha pogut inserir participant a cursa.\n
	    Saldo insuficient.</p>";
   	  }
	} else {
	  echo "<p>No s'ha pogut inserir participant a cursa.\n
	  Participant existent.</p>";
    }
	oci_free_statement($comanda);
	oci_close($conn);
  ?>
  <p> <a class="menu" href="afegirACursa.php">Següent</a></p>
  <p> <a class="menu" href="inserirDiaHoraCursa.php">Indicar dia i hora</a></p>
</body>
</html>