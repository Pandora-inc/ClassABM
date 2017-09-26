<?php

/**
 *
 * @author iberlot
 *         Validar usuario
 *         mixed by Marcexl
 * @version 23112015
 *          Consulta a la BD si tiene persmisos
 *         
 */

/* FILTRO1 averiguamos si la cuenta esta en la tabla */
$sqlUsrs = "SELECT login, person FROM appadmusu.usuario WHERE UPPER(login) = :cuenta";
$stmt = oci_parse ($linkOracle2, $sqlUsrs);

oci_bind_by_name ($stmt, ":cuenta", strtoupper ($cuenta));

oci_execute ($stmt) or die (' Error en sqlUsrs ' . var_dump ($sqlUsrs) . ' en linea ' . __LINE__);

$filtro1 = false;

while ($row = oci_fetch_array ($stmt))
{
	if ($cuenta == $row ["LOGIN"])
	{
		$person = $row ["PERSON"];
		$filtro1 = true;
	}
}

if ($filtro1 == true)
{
	
	$filtro2 = false;
	
	/* FILTRO 2 si esta en la tabla ahora verificamos si tiene permisos en dicha aplicacion */
	
	$sqlRoles = "SELECT idrol FROM appadmusu.moduloxrol WHERE idaplicacion = :IDAPLICACION AND IDMODULO = :modulo";
	$stmt = oci_parse ($linkOracle2, $sqlRoles);
	
	oci_bind_by_name ($stmt, ":modulo", $IDMODULO);
	oci_bind_by_name ($stmt, ":IDAPLICACION", $IDAPLICACION);
	
	oci_execute ($stmt) or die (' Error en sqlRoles ' . var_dump ($sqlRoles) . ' en linea ' . __LINE__);
	
	while ($rowRoles = oci_fetch_array ($stmt))
	{
		$IDROL = $rowRoles ['IDROL'];
		
		$sqlUsrs2 = "SELECT DISTINCT person FROM appadmusu.rolxusuario ru, appadmusu.moduloxrol mr WHERE ru.idrol = :IDROL AND ru.person = :person AND mr.idrol = ru.idrol AND mr.idaplicacion = :IDAPLICACION";
		// $sqlUsrs2 = "SELECT PERSON FROM appadmusu.RolxUsuario where IDROL = ".$IDROL." and PERSON = ".$person;
		$stmt2 = oci_parse ($linkOracle2, $sqlUsrs2);
		oci_bind_by_name ($stmt2, ":IDROL", $IDROL);
		oci_bind_by_name ($stmt2, ":person", $person);
		oci_bind_by_name ($stmt2, ":IDAPLICACION", $IDAPLICACION);
		
		// $IDROL .=$sqlUsrs2;
		exit($sqlUsrs2);
		oci_execute ($stmt2) or die (' Error en sqlUsrs ' . var_dump ($sqlUsrs2) . ' en linea ' . __LINE__);
		
		while ($row2 = oci_fetch_array ($stmt2))
		{
			if ($person == $row2 ["PERSON"])
			{
				$filtro2 = true;
			}
		}
	}
}
else
{
	/* si es invalido alguno de los filtros */
	header ("Location:error.php?msg=SINAPPICACION");
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
	
	$_SESSION ['usuario'] = $cuenta;
	$_SESSION ['person'] = $person;
	$_SESSION ['foto'] = $foto;
	$_SESSION ['app'] = $app;
	
	header ("Location:index.php");
}
else
{
	/* si es invalido alguno de los filtros */
	
	header ("Location:error.php?msg=SINPERMISOS&IDROL=$IDROL&person=$person&IDAPLICACION=$IDAPLICACION");
}

?>