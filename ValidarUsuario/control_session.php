<?php

/**
 * Verificamos que haya una ya sesion iniciada y si no la hay la iniciamos.
 *
 * Cargamos el conjunto de variables de la sesion.
 *
 * @version 0.1
 * @name control_session.php
 *
 * @filesource
 */

/* por ultimo generamos el array con las aplicaciones a la que el usuario tiene acceso */

/**
 * funcion para ver el estado de la sesion
 *
 * @return boolean
 */
function is_session_started()
{
	if (php_sapi_name () !== 'cli') // Devuelve el tipo de interfaz que hay entre PHP y el servidor
	{
		if (version_compare (phpversion (), '5.4.0', '>=')) // Comparamos la vercion de php
		{
			return session_status () === PHP_SESSION_ACTIVE ? TRUE : FALSE;
		}
		else
		{
			return session_id () === '' ? FALSE : TRUE;
		}
	}
	return FALSE;
}

// require_once("consultas.php");
require_once ("config/config.php");

if (!isset ($_SESSION['usuario']))
{
	$_SESSION['url'] = $_SERVER['PHP_SELF'];

	// si la sesion no fue iniciada lo devolvemos para la pagina anterior
	header ("location:/classes/ValidarUsuario/login.php");
	echo "no guardo el inicio de la sesion";
	return; // Este return evita que el codigo continue ejecutandose
}
else
{
	$cuenta = $_SESSION['usuario'];

	$sqlUsrs = "SELECT login, person FROM appadmusu.usuario WHERE UPPER(login) = :cuenta";
	$stmt = oci_parse ($linkOracle2, $sqlUsrs);

	oci_bind_by_name ($stmt, ":cuenta", strtoupper ($cuenta));

	oci_execute ($stmt) or die (' Error en sqlUsrs ' . var_dump ($sqlUsrs) . ' en linea ' . __LINE__);

	while ($row = oci_fetch_array ($stmt))
	{
		if ($cuenta == $row["LOGIN"])
		{
			$person = $row["PERSON"];
		}
	}

	/* generamos el array con las aplicaciones que tiene permisos */
	$stid = oci_parse ($linkOracle2, 'SELECT IDROL FROM appadmusu.RolxUsuario where person =' . $person . '');
	if (!$stid)
	{
		$e = oci_error ($linkOracle2);
		trigger_error (htmlentities ($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$r = oci_execute ($stid);
	if (!$r)
	{
		$e = oci_error ($stid);
		trigger_error (htmlentities ($e['message'], ENT_QUOTES), E_USER_ERROR);
	}

	$personApps = '';
	while ($fila = oci_fetch_array ($stid, OCI_ASSOC + OCI_RETURN_NULLS))
	{
		foreach ($fila as $elemento)
		{
			$personApps[] = $elemento;
		}
	}
}

if (in_array ($IDROL, $personApps))
{
	echo "";
}
else
{
	header ("location:" . $raiz . "error.php"); // En este caso no destruimos la sesion pero el sistema le dira que no tiene permisos.
}

// si la sesion no esta iniciada lo hace

if (is_session_started () === FALSE)
{
	session_start ();
}

// Si llega a esta parte del código es porque la veriable de sesion si existe */
// echo "Bienvenido a la p&aacute;gina de sesiones";

// Es importante tener en cuenta que la sesion estara viva mientras no se haya cerrado
// el browser. En el momento que cerramos el browser la sesion es matada

/* Registro el acceso del usuario en el appadmusu.aplicationlog */
$ip = '';

if (!empty ($_SERVER['HTTP_CLIENT_IP']))
{
	$ip = $_SERVER['HTTP_CLIENT_IP'];
}
elseif (!empty ($_SERVER['HTTP_X_FORWARDED_FOR']))
{
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
	$ip = $_SERVER['REMOTE_ADDR'];
}

$actual_link = "$_SERVER[REQUEST_URI]";

$sqlUsrs2 = "insert into APPADMUSU.APLICACIONLOG (IDAPLICACION,IDMODULO,PERSON,ACCESO,IP,DATA) VALUES (:IDAPLICACION,:IDMODULO,:PERSON,sysdate,:IP,:DATA)";
// $sqlUsrs2 = "SELECT PERSON FROM appadmusu.RolxUsuario where IDROL = ".$IDROL." and PERSON = ".$person;
$stmt2 = oci_parse ($linkOracle2, $sqlUsrs2);
oci_bind_by_name ($stmt2, ":IDAPLICACION", $IDAPLICACION);
oci_bind_by_name ($stmt2, ":IDMODULO", $IDMODULO);
oci_bind_by_name ($stmt2, ":PERSON", $person);
oci_bind_by_name ($stmt2, ":IP", $ip);
oci_bind_by_name ($stmt2, ":DATA", substr ($actual_link, 0, 511));

// $IDROL .=$sqlUsrs2;

oci_execute ($stmt2) or die (' Error en sqlUsrs ' . var_dump ($sqlUsrs2) . ' en linea ' . __LINE__);

?>