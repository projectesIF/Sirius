<?php


include_once('config/config.php');

$siriusdb['dbhost']       	   = $sirius['dbhost'];                                   // Servidor de BBDD
$siriusdb['dbuname']       	   = $sirius['dbuname'];;                            	      // Nom d'usuari a la BBDD
$siriusdb['dbpass']      		= $sirius['dbpass'];                       	      // Contrasenya de l'usuari de BBDD
$siriusdb['dbname']       	   = $sirius['dbname'];                              	      // Base de dades

$server = mysql_connect($siriusdb['dbhost'], $siriusdb['dbuname'], $siriusdb['dbpass']) or die(mysql_error());

$connection = mysql_select_db($siriusdb['dbname'], $server);

?>