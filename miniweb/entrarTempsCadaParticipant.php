#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Entrar temps participant</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php
	include 'funcions.php';     
	iniciaSessio();
	connecta($conn);
	$_SESSION['codiCursa'] = $_POST['codi'];
  
	// Busquem nom de la cursa per mostrar
	$consulta="SELECT nom FROM curses WHERE codi=:codiCursa";
	$comanda = oci_parse($conn, $consulta);
	oci_bind_by_name($comanda,":codiCursa",$_POST['codi']);
	$exit = oci_execute($comanda);
	$fila= oci_fetch_array($comanda);
	capcalera("Entrar temps ".$fila['NOM']); 

	// Busquem participants
	$participants = "SELECT p.usuari, p.alias, pc.vehicle 
					FROM participantscurses pc JOIN personatges p ON p.alias = pc.personatge
					WHERE cursa = '".$_POST['codi']."'";
	$comanda = oci_parse($conn, $participants);
	$exit = oci_execute($comanda);
	$fila = oci_fetch_array($comanda);
	if (!$exit){
	  mostraErrorExecucio($comanda);
	}
	else if (empty($fila)) {
      echo "<strong>COMPTE! Cursa sense participants</strong>";
	}
	else {
  ?>
  <h1>Llista de participants</h1>
  <form action="entrarTempsCadaParticipant_BD.php" method="post">
	<table border = "1">
	  <tr>
		<td>Usuari</td>
		<td>Personatge</td>
		<td>Vehicle</td>
		<td>Temps</td>
	  </tr>
	<?php
	  do {
	?>
	<tr>
	  <td><?php echo $fila["USUARI"]; ?></td>
	  <td><?php echo $fila["ALIAS"]; ?></td>
	  <td><?php echo $fila["VEHICLE"]; ?></td>
	  <td><?php echo "<input type=\"number\" min = 0 name=minuts".$fila["ALIAS"].">";?>:
	  <?php echo "<input type=\"number\" max = 59 min = 0 name=segons".$fila["ALIAS"].">";?></td>
	</tr>
	<?php
	  } while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false);
	?>
	</table>
	<p><label>&nbsp;</label><input type = "submit" value="Entrar"></p>
	</form>
  <?php
  } 
  peu("Tornar","entrarTempsParticipants.php");
  ?>
</body>
</html>