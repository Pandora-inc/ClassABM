<?php 
require("class_sitio.php");
require("class_abm.php");
require("class_db.php");
require("class_paginado.php");
require("class_orderby.php");

//conexión a la bd
$db = new class_db($servidor, $usr, $pass, $db, "utf8");
$db->mostrarErrores = true;
$db->connect();

//utilidades de sitio
$sitio = new class_sitio();


/**
 * Convierte de un array todas las entidades HTML para que sea seguro mostrar en pantalla strings ingresados por los usuarios
 * Ejemplo: $_REQUEST = limpiarEntidadesHTML($_REQUEST);
 *
 * @param Array o String $param Un array o un String
 * @return Depende del parametro recibido, un array con los datos remplazados o un String
 */
function limpiarEntidadesHTML($param) {
	global $sitio; //de mi framework 
    return is_array($param) ? array_map('limpiarEntidadesHTML', $param) : htmlentities($param, ENT_QUOTES, $sitio->charset);
}

/**
 * Escapa de un array todos los caracteres especiales de una cadena para su uso en una sentencia SQL
 * Ejemplo: $_REQUEST = limpiarParaSql($_REQUEST);
 *
 * @param Array o String $param Un array o un String
 * @return Depende del parametro recibido, un array con los datos remplazados o un String
 */
function limpiarParaSql($param){
	global $db;
	return is_array($param) ? array_map('limpiarParaSql', $param) : mysqli_real_escape_string($db->con, $param);
}
?>