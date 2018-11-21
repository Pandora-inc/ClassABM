<?php
/**
 * Este archivo se encargara de contener e inicializar todas las variables de configuracion que va a utilizar el sistema
 */
if (!isset ($_SESSION))
{
	session_start ();
}

date_default_timezone_set ('America/Buenos_Aires');

// header('Content-Type: text/html; charset=ISO-8859-1');

/**
 * Datos del mail
 */

// Defino la direcci�n de correo a la que se env�a el mensaje
// $para = 'mvaraldo@usal.edu.ar';
// $para = 'iberlot@usal.edu.ar';
// $paraNombre = 'Servicio Tecnico';
$titulo = '$anio/$numReque: $Titulo';
$fromMail = 'desaplic@salvador.edu.ar';
$fromName = 'Constancia de Requerimiento';
$replyMail = 'desaplic@salvador.edu.ar';
$replyName = 'Sueldos';

$cabeceras = 'From: ' . $fromMail . "\r\n" . 'Reply-To: ' . $replyMail . "\r\n" . 'X-Mailer: PHP/' . phpversion ();

/**
 * Datos Base de Datos
 */

// Datos para la coneccion a la base mediante el usuario appgral
$UsuarioOracle = 'appadmusu'; // root
$PasswordOracle = 'appadmusu';
$ServidorOracle = 'PADUA.DESAPOSE'; // localhost
$ServidorOracle2 = 'PADUA.DESASE'; // localhost

$directorioAplicacion = "classes/ValidarUsuario";
// /echo "***nln***";
$_SESSION['aplicacion'] = $directorioAplicacion;

// Enviar Mail al Usuario? y/n
$eMailUsr = "iberlot@usal.edu.ar";
$ambiente = "DESARROLLO";

$_SESSION['ambiente'] = $ambiente;
$servidor = "http://roma2.usal.edu.ar";

$serv = "DESARROLLO";

$Titulo = "Validacion de usuario";
echo "<title>$Titulo</title>";
$_SESSION['title'] = $Titulo;

$IDAPLICACION = $_SESSION['IDAPLICACION'];
$IDMODULO = $_SESSION['IDMODULO'];
$IDROL = $_SESSION['IDROL'];

/**
 * **********************************************
 * ATTENTION: Fill in these values! Make sure
 * the redirect URI is to this page, e.g:
 * http://localhost:8080/user-example.php
 * **********************************************
 */
/*
 * $client_id = '751074584240-5stuurp2a0gi3rcciorsgjthuruf03ve.apps.googleusercontent.com';
 * $client_secret = '78TOx_RqOYdSMs-dIxtmd9IO';
 * $redirect_uri = 'http://roma2.usal.edu.ar/classes/ValidarUsuario/login.php';
 */
$client_id = '931073439286-vda29h0ntkadm54dmpgbst0vsrq4g874.apps.googleusercontent.com';
$client_secret = 'UvPIRRzrI82i56cmGXry-LFW';
// $redirect_uri = $directorio . 'login.php';
$redirect_uri = 'http://roma2.usal.edu.ar/classes/ValidarUsuario/login.php';

?>