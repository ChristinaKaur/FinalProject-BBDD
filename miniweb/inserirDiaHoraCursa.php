#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Inserir dia i hora cursa</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
  <?php
	include 'funcions.php';     
	iniciaSessio();
	connecta($conn);
	capcalera("Inscriure sia i hora cursa"); 
  ?>
  <form action="inserirDiaHoraCursa_BD.php" method="post">
	<h3>Dia i hora</h3>
	<p><label>Data: </label><input type="date" name="data" value="18/DES/2023" required> </p>
	<p><label>Hora: </label><input type="time" name="hora" value="00:00" required> </p> 
	<p><label>&nbsp;</label><input type = "submit" value="Seleccionar"></p>
  </form>
  <?php peu("Tornar","afegirVehicle.php");?>
</body>
</html>