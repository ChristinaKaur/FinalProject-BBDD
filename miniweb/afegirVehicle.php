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
  <form action="inscriureCursa_BD.php" method="post">
    <h3>Vehicles</h3>
	<p><label>Vehicle</label>
	<select name="codi">
      <?php // Triem vehicle
	    // Guardem personatge
	    $_SESSION['aliasPers'] = $_POST["personatge"];
	
		// Seleccionem vehicle 
		$vehicles = "SELECT v.codi, v.descripcio
					FROM vehicles v JOIN usuaris u ON v.propietari = u.alias
					JOIN personatges p ON p.usuari = u.alias
					WHERE p.alias = '".$_POST["personatge"]."'";
		$comanda = oci_parse($conn, $vehicles);
		
		$exit = oci_execute($comanda);
		if (!$exit){
          mostraErrorExecucio($comanda);
		}
		while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
          echo "<option value=\"" . $fila['CODI'] . "\">" . $fila['DESCRIPCIO'] . "</option>\n";
		}
      ?>  
	</select></p>
    <p><label>&nbsp;</label><input type = "submit" value="Afegir"></p>
  </form>
  <?php peu("Tornar","afegirACursa.php");?>
</body>
</html>