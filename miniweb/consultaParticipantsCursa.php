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
	capcalera("Consultar els participants d’una cursa"); 
  ?>
  <h3>Cursa</h3>
  <form action="mostrarParticipantsCursa.php" method="post">
	<p><label>Curses tancades:</label>
      <select name="codi">
	<?php 
	  // Seleccionem cursa
	  $cursa = "SELECT codi, nom FROM curses WHERE inicireal IS NOT NULL";
      $comanda = oci_parse($conn, $cursa);
      $exit = oci_execute($comanda);
      if (!$exit) {
        mostraErrorExecucio($comanda);
      }
      while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "<option value=\"" . $fila['CODI'] . "\">" . $fila['NOM'] . "</option>\n";
      }
    ?>      
	</select></p>
    <p><label>&nbsp;</label><input type = "submit" value="Triar"></p>
  </form>
  <?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>