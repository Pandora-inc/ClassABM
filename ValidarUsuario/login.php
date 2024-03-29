<?php
/**
 * Login de Requerimientos
 *
 * Codigo para conectarse a las apis de google
 *
 * @author by Marcexl
 * @version 24112015
 *
 */
session_start ();

require_once ("config/config.php");
require_once ("config/variables.php");
require_once ("config/conexion.php");
require_once ("/web/html/google-api-php-client/src/Google/autoload.php");

/**
 * **********************************************
 * Make an API request on behalf of a user.
 * In
 * this case we need to have a valid OAuth 2.0
 * token for the user, so we need to send them
 * through a login flow. To do this we need some
 * information from our API console project.
 * **********************************************
 */

$client = new Google_Client ();
$client->setClientId ($client_id);
$client->setClientSecret ($client_secret);
$client->setRedirectUri ($redirect_uri);

$client->setScopes (array (
		"https://www.googleapis.com/auth/plus.login",
		"https://www.googleapis.com/auth/userinfo.email",
		"https://www.googleapis.com/auth/userinfo.profile",
		"https://www.googleapis.com/auth/plus.me"
));

/**
 * **********************************************
 * Se crea el objeto $oauth2Service
 * **********************************************
 */
$oauth2Service = new Google_Service_Oauth2 ($client);

/**
 * **********************************************
 * Se realiza la solicitud de un acces token
 * si es que no esta la sesion iniciada
 * **********************************************
 */
if (isset ($_REQUEST['logout']))
{
	unset ($_SESSION['access_token']);
}
if (isset ($_GET['code']))
{
	$client->authenticate ($_GET['code']);
	$_SESSION['access_token'] = $client->getAccessToken ();
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	header ('Location: ' . filter_var ($redirect, FILTER_SANITIZE_URL));
	// header ('Location: www.google.com.ar');
}
/**
 * **********************************************
 * Una vez oauth te devuelve un token lo guardamos
 * como variable de session.
 * **********************************************
 */

if (isset ($_SESSION['access_token']) && $_SESSION['access_token'])
{
	$client->setAccessToken ($_SESSION['access_token']);
}
else
{
	$authUrl = $client->createAuthUrl ();
}

/**
 * **********************************************
 * Aca comienza el codigo de la pagina para
 * logearse
 * ***********************************************
 */

if (isset ($authUrl))
{

	include ("/web/html/inc/header.php");
	include ("/web/html/inc/menu.php"); // incluimos el menu general

	echo '<div id="content">
			<div id="cuerpo" align="center">
				<div id="separadorh"></div>
				<h3>' . $Titulo . '</h3>
				<div id="separadorh"></div>
				<fieldset>';
	include ("/web/html/inc/version.php");
	echo '</fieldset>
			</div>
		</div>';

	include ("/web/html/inc/footer.php");
}
else
{

	/**
	 * **********************************************
	 * Aca ya ingresamos!!!
	 * **********************************************
	 */

	/* Creamos el objeto */

	$oauth2Service = new Google_Service_Oauth2 ($client);
	$userinfo = $oauth2Service->userinfo;
	$getUser_info = $userinfo->get ();

	/* obtenemos los datos de la cuenta */

	$email = $getUser_info['email'];
	$nombreApellido = $getUser_info['name'];
	$foto = $getUser_info['picture'];

	/* primero nos fijamos si tiene @usal.edu.ar */

	$haveit = '@usal.edu.ar';
	$pos = strpos ($email, $haveit);

	if ($pos === false)
	{
		header ("Location:error.php");
	}

	/* si es valida eliminamos el @usal.edu.ar */

	$cortar = '@';
	$pos2 = strpos ($email, $cortar);
	$cuenta = substr ($email, 0, $pos2);

	/* validamos el usuario */

	include ("/web/html/classes/ValidarUsuario/validar_usuario.php");
}

?>