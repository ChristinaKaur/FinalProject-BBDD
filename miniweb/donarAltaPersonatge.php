#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Donar d'alta un personatge, entrada de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php 
    include 'funcions.php';     
    iniciaSessio();
    connecta($conn);
    capcalera("Donar d'alta un personatge"); 
  ?>
  <h3>Personatge</h3>
  <form action="donarAltaPersonatge_BD.php" method="post">
    <p><label>Despesa mensual: </label><input type="number" min = 0 name="despesa" required> </p>
    <p><label>Data de creació: </label><input type="date" name="data" required> </p>
	<p><label>Propietari: </label>
	<select name="propietari" required>
	<?php 
      $usuari = "SELECT alias FROM usuaris";
      $comanda = oci_parse($conn, $usuari);
      $exit = oci_execute($comanda);
      if (!$exit){
        mostraErrorExecucio($comanda);
      }
      while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "<option value=\"" . $fila['ALIAS'] . "\">" . $fila['ALIAS'] . "</option>\n";
      }
    ?> 
    </select></p>     
    <p><label>Tipus personatge:</label>
      <select name="tipus" required>
    <?php 
      $tipus = "SELECT nom FROM tipuspersonatges";
      $comanda = oci_parse($conn, $tipus);
      $exit = oci_execute($comanda);
      if (!$exit){
        mostraErrorExecucio($comanda);
      }
      while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
        echo "<option value=\"" . $fila['NOM'] . "\">" . $fila['NOM'] . "</option>\n";
      }
    ?>
    </select></p>
    <p><label>&nbsp;</label><input type = "submit" value="Afegir"></p>
  </form>
  <?php peu("Tornar al menú principal","menu.php");?>
</body>
</html>
