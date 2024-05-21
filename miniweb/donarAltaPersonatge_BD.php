#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
  <title>Donar d'alta un personatge, inserció a la base de dades</title>
  <link rel="stylesheet" href="exemple.css" type="text/css"> 
</head>
<body>
<?php 
  include 'funcions.php';     
  iniciaSessio();
  connecta($conn);
  capcalera("Inserir personatge a la base de dades"); 
  
  // Crea alias
  $alias = substr($_POST["propietari"], 0, 5);
  $consulta="SELECT alias FROM personatges WHERE alias=:alias";
  $comanda = oci_parse($conn, $consulta);
  oci_bind_by_name($comanda, ":alias", $alias);
  $exit = oci_execute($comanda);
  $fila = oci_fetch_array($comanda); 
  while ($fila) { // alias invalid (ja existeix)
	$alias = substr($_POST["propietari"], 0, 5) . strval(rand(0, 10000));
	$consulta="SELECT alias FROM personatges WHERE alias=:alias";
    $comanda = oci_parse($conn, $consulta);
	oci_bind_by_name($comanda, ":alias", $alias);
    $exit = oci_execute($comanda);
    $fila=oci_fetch_array($comanda); // no fem control d'errors 
  }
  
  // Inserir a la BD
  $sentenciaSQL = "INSERT INTO personatges (alias, despesamensual, datacreacio, usuari, tipuspersonatge) VALUES (:alias, :despesa, TO_DATE(:data, 'YYYY-MM-DD'), :usuari, :tipus)";
  
  $comanda = oci_parse($conn, $sentenciaSQL);
  oci_bind_by_name($comanda, ":despesa", $_POST["despesa"]);
  oci_bind_by_name($comanda, ":data", $_POST["data"]);
  oci_bind_by_name($comanda, ":usuari", $_POST["propietari"]);
  oci_bind_by_name($comanda, ":tipus", $_POST["tipus"]);
  oci_bind_by_name($comanda, ":alias", $alias);
  
  // Mostra resultat
  $exit = oci_execute($comanda); 
  if ($exit) {
    echo "<p>Nou personatge amb alias " . $alias . " inserit a la base de dades</p>\n";
  } else {
    mostraErrorExecucio($comanda);
  }
  oci_free_statement($comanda);
  oci_close($conn);
  peu("Tornar al menú principal","menu.php");
?>
</body>
</html>