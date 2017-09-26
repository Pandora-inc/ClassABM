<?php
	
	/**
	 * @author iberlot
	 * @version 1.0
	 *
	 * Este archivo se encargara de contener todos los includes
	 * que va a utilizar el sistema
	 */
	
	include_once("config/variables.php"); //incluimos el archivo que contiene las variables
	include_once("config/conexion.php"); //incluimos el archivo de conexion a la base de datos
//	include_once("configs/funciones.php"); //incluimos el archivo que va a contener todas las funciones del sistem

	include_once("/web/html/inc/header.php"); //incluimos el archivo que contiene la cabezera
	//include_once("menu.php"); //incluimos el archivo que contiene la cabezera
	include ("/web/html/inc/menuPrueba.php");
	
	include_once("config/requeridos.php");

	require_once("/u00/cgi-bin/clases/phpmailer/class.phpmailer.php");

	
	// Los siguientes archivos no tienen este include 
	// (en caso de haber modificaciones hay que realizar los cambios correspondientes en ellos):
	// archModulo.php
	// dinaAgrup.php
	// dinaModulo.php
	// dinaUsrSist.php
	// procesa2.php
?>

