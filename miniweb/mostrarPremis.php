#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Mostrar premis</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php 
	include 'funcions.php';     
	iniciaSessio();
	connecta($conn);
	capcalera("Mostra premis d'un usuari"); 
  ?>
  <h3>Usuari</h3>
  <form action="mostrarPremisUsuari.php" method="post">
	<p><label>Usuaris:</label>
      <select name="usuari">
	<?php 
	  // Seleccionem usuari
	  $usuari = "SELECT alias FROM usuaris";
      $comanda = oci_parse($conn, $usuari);
      $exit = oci_execute($comanda);
      if (!$exit) {
        mostraErrorExecucio($comanda);
      }
      while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "<option value=\"" . $fila['ALIAS'] . "\">" . $fila['ALIAS'] . "</option>\n";
      }
    ?>      
	</select></p>
    <p><label>&nbsp;</label><input type = "submit" value="Triar"></p>
  </form>
  <?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>