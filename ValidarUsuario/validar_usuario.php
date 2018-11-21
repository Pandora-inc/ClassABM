<?php
/**
 * Realiza la comprovacion de permisos en la base para el usuario y la aplicacion.
 *
 * Consulta a la BD si tiene persmisos
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 *         @date 23 nov. 2015
 *         @lenguage PHP
 * @name validar_usuario.php
 *
 * @version 0.1 - Version inicial del archivo.
 * @version 1.0 - Mixed by Marcexl
 * @version 1.2 - Parametrizacion total de las consultas. Utilizacion de la clase db para los accesos a la base.
 *
 * @package ValidacionDeUsuarios
 *
 * @link configs/includes.php - Archivo con todos los includes del sistema
 */
ob_start ();

include_once ("includes.php");

if (!isset ($db))
{
	$db = new class_db ($dbSever, $dbUser, $dbPass, $dbBase, $dbCharset, $dbTipo);
	$db->connect ();
}

$filtro1 = false;
$parametros = array ();

$sqlUsrs = "SELECT login, person FROM appadmusu.usuario WHERE UPPER(login) = UPPER(:cuenta)";

$parametros[] = $cuenta;

$result = $db->query ($sql);

while ($row = $db->fetch_array ($result))
{
	if ($cuenta == $row["LOGIN"])
	{
		$person = $row["PERSON"];
		$filtro1 = true;
	}
}

if ($filtro1 == true)
{

	$filtro2 = false;

	/* FILTRO 2 si esta en la tabla ahora verificamos si tiene permisos en dicha aplicacion */

	$sqlRoles = "SELECT idrol FROM appadmusu.moduloxrol WHERE idaplicacion = :idaplicacion AND idmodulo = :modulo";

	$parametros = array ();

	$parametros[] = $IDAPLICACION;
	$parametros[] = $IDMODULO;

	$result = $db->query ($sqlRoles);

	while ($rowRoles = $db->fetch_array ($result))
	{
		$sqlUsrs2 = "SELECT DISTINCT person FROM appadmusu.rolxusuario ru, appadmusu.moduloxrol mr WHERE ru.idrol = :idrol AND ru.person = :person AND mr.idrol = ru.idrol AND mr.idaplicacion = :idaplicacion";

		$parametros = array ();

		$parametros[] = $rowRoles['IDROL'];
		$parametros[] = $person;
		$parametros[] = $IDAPLICACION;

		$result = $db->query ($sqlUsrs2);

		while ($row2 = $db->fetch_array ($result))
		{
			if ($person == $row2["PERSON"])
			{
				$filtro2 = true;
			}
		}
	}
}
else
{
	/* si es invalido alguno de los filtros */
	header ("location:" . $raiz . "/error.php?msg=SINAPPICACION");
}

if ($filtro2 == true)
{

	/*
	 * Si pasa los dos filtros creamos las variables de sesion de:
	 * - usuario
	 * - foto
	 * - app (aplicacion a la que estamos logeados)
	 * - y el array de roles que en este caso queda grabado en $personApss
	 */

	/* generamos el array con las aplicaciones que tiene permisos */
	$sql = "SELECT idrol FROM appadmusu.RolxUsuario WHERE person = :person";

	$parametros = array ();

	$parametros[] = $person;

	$result = $db->query ($sql);

	while ($fila = $db->fetch_array ($result))
	{
		foreach ($fila as $elemento)
		{
			$personApps[] = $elemento;
		}
	}

	$_SESSION['personApps'] = $personApps;

	$_SESSION['usuario'] = $cuenta;
	$_SESSION['person'] = $person;
	$_SESSION['foto'] = $foto;
	$_SESSION['estado'] = 'Iniciada';

	header ("Location:index.php");
}
else
{
	/* si es invalido alguno de los filtros */

	header ("Location:error.php?msg=SINPERMISOS&IDROL=$IDROL&person=$person&IDAPLICACION=$IDAPLICACION");
}
ob_end_flush ();
?>