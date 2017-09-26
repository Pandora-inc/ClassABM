<?php

/**
 *
 * @author iberlot
 * @version 20151223
 * @package Mytthos
 * @category Config
 *          
 *           Listado con todas las funciones utilizadas por el sistema
 */
function codificacion($texto)
{
	$c = 0;
	$ascii = true;
	for($i = 0; $i < strlen ( $texto ); $i ++)
	{
		$byte = ord ( $texto [$i] );
		if ($c > 0)
		{
			if (($byte >> 6) != 0x2)
			{
				return ISO_8859_1;
			}
			else
			{
				$c --;
			}
		}
		elseif ($byte & 0x80)
		{
			$ascii = false;
			if (($byte >> 5) == 0x6)
			{
				$c = 1;
			}
			elseif (($byte >> 4) == 0xE)
			{
				$c = 2;
			}
			elseif (($byte >> 3) == 0x1E)
			{
				$c = 3;
			}
			else
			{
				return ISO_8859_1;
			}
		}
	}
	return ($ascii) ? ASCII : UTF_8;
}

function utf8_encode_seguro($texto)
{
	return (codificacion ( $texto ) == ISO_8859_1) ? utf8_encode ( $texto ) : $texto;
}

/*
 * function errores($numero, $texto, $istru, $linea)
 * {
 * $ddf = fopen ('/web/logs/errorRequerimientos.log', 'a');
 * fwrite ($ddf, "[" . date ("r") . "] Error $numero:$texto *** var_export($istru) *** en linea $linea\r\n");
 * fclose ($ddf);
 * }
 * set_error_handler ('error');
 */
/**
 * Devuelve el usuario encerrado entre parentesis
 *
 * @param string $cadena
 *        	Cadena en la que se encuetra el texto encerrado entre parentesis a extraer
 * @return string $final Texto que se encuentra entre parentesis extraido para su uso
 *        
 */
function usuario($cadena)
{
	$maximo = strlen ( $cadena );
	$ide = "(";
	$ide2 = ")";
	$total = strpos ( $cadena, $ide );
	$total2 = stripos ( $cadena, $ide2 );
	$total3 = ($maximo - $total2 - 1);
	$final = substr ( $cadena, $total + 1, - 1 );
	
	return $final;
}

/**
 * Devuelve los datos del usuario Referente
 *
 * @param int $anio
 *        	aï¿½o del requerimiento del que se quiere saber el referente
 * @param int $reque
 *        	Numero del requerimiento del que se quiere saber el referente
 *        	
 * @return string $final Texto que se encuentra entre parentesis extraido para su uso
 */
function usrRefernt($anio, $reque)
{
	include ("config.php");
	include ("conexion.php");
	
	$sqlUsrRefernet = "SELECT NRO_DOC, TIPO_DOC FROM PORTAL.USRREFERENT Where  ANIO = '" . $anio . "' and REQUERIMIENTO = '" . $reque . "'";
	$stmtRefer = oci_parse ( $linkOracle, $sqlUsrRefernet );
	oci_execute ( $stmtRefer ) or die ( ' Error en sqlUsrRefernet ' . var_dump ( $sqlUsrRefernet ) . ' en linea ' . __LINE__ );
	$refer = oci_fetch_array ( $stmtRefer, OCI_ASSOC + OCI_RETURN_NULLS );
	
	$referNumDoc = $refer ['NRO_DOC'];
	$referTipDoc = $refer ['TIPO_DOC'];
	
	list ( $nuevoReqRefer, $referentCuenta, $EmailRefernt ) = datosPersona ( $refer ['NRO_DOC'], $refer ['TIPO_DOC'] );
	
	return array (
			$nuevoReqRefer,
			$referentCuenta,
			$EmailRefernt 
	);
}

function validarEmail($str)
{
	// $string = "first.last@domain.co.uk";
	if (preg_match ( '/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $str ))
	{
		return 1;
	}
	return 0;
}

function fecha_DD_MM_YYYY_Oracle($fecha_inicio)
{
	$fecha_inicio = str_replace ( '-', '', $fecha_inicio );
	$fecha_inicio = str_replace ( '/', '', $fecha_inicio );
	// $dato_post_fecha_recibo_usal = preg_replace('([^0-9])', '', $dato_post_valorcuota);
	$dd = substr ( $fecha_inicio, - 2 );
	$mm = substr ( $fecha_inicio, 4, 2 );
	$yyyy = substr ( $fecha_inicio, 0, 4 );
	if ($fecha_inicio)
	{
		$fecha_inicio = $dd . "/" . $mm . "/" . $yyyy;
	}
	return $fecha_inicio;
}

/**
 * invierte el orden de la fecha para que quede en el formato dia-mes-aï¿½o
 *
 * @param date $fecha
 *        	fecha con el formato ano-mes-dia
 * @return string $aux
 */
function invertirFecha($fecha)
{
	list ( $ano, $mes, $dia ) = explode ( '-', $fecha );
	$aux = $dia . "-" . $mes . "-" . $ano;
	
	return $aux;
}

/**
 * devuelve el dia correspondiente de la semana en formato de tres letras
 *
 * @param date $fecha
 *        	fecha con el formato ano-mes-dia
 * @return string $dias
 */
function nombreDiacorto($fecha)
{
	list ( $ano, $mes, $dia ) = explode ( '-', $fecha );
	$dias = array (
			'Dom',
			'Lun',
			'Mar',
			'Mie',
			'Jue',
			'Vie',
			'Sab',
			'86776' 
	);
	
	return $dias [date ( "w", mktime ( 0, 0, 0, $mes, $dia, $ano ) )];
}

/**
 * devuelve la suma de dias
 *
 * @param date $fecha
 *        	fecha con el formato ano-mes-dia
 * @param int $dia
 *        	numero de dias a sumar
 * @return date fecha con los dias sumados
 */
function sumaDia($fecha, $dia)
{
	list ( $year, $mon, $day ) = explode ( '-', $fecha );
	
	return date ( 'Y-m-d', mktime ( 0, 0, 0, $mon, $day + $dia, $year ) );
}

/**
 * Diferencia de dï¿½as - Fecha mayor, Fecha menor
 *
 * @param array $fecha2
 *        	fecha mayor con el formato ano-mes-dia
 * @param array $fecha1
 *        	fecha menor con el formato ano-mes-dia
 * @return date $dias_diferencia fecha con los dias restados
 */
function diferenciaDias($fecha2, $fecha1)
{
	list ( $ano2, $mes2, $dia2 ) = explode ( '-', $fecha1 );
	list ( $ano1, $mes1, $dia1 ) = explode ( '-', $fecha2 );
	
	// calculo timestam de las dos fechas
	$timestamp1 = mktime ( 0, 0, 0, $mes1, $dia1, $ano1 );
	$timestamp2 = mktime ( 0, 0, 0, $mes2, $dia2, $ano2 );
	
	// resto a una fecha la otra
	$segundos_diferencia = $timestamp1 - $timestamp2;
	
	// convierto segundos en dï¿½as
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
	
	return round ( $dias_diferencia );
}

/**
 * Chequea que la fecha ingresada sea correcta
 *
 * @param int $d
 *        	El dï¿½a que estï¿½ dentro del nï¿½mero de dï¿½as del mes m dado. Los aï¿½os a bisiestos son tomados en consideraciï¿½n.
 * @param int $m
 *        	El mes entre 1 y 12 inclusive.
 * @param int $a
 *        	El aï¿½o entre 1 y 32767 inclusive.
 *        	
 * @return bool puede ser 0 o 1 dependiendo si la fecha es correcta o no
 */
function fechaCorrecta($d, $m, $a)
{
	$c = checkdate ( $m, $d, $a );
	if ($c)
		return 1;
	else
		return 0;
}

/**
 */
function calcularMminutosExcedentes($hora1, $hora2)
{
	$separar [1] = explode ( ':', $hora1 );
	$separar [2] = explode ( ':', $hora2 );
	
	$total_minutos_trasncurridos [1] = ($separar [1] [0] * 60) + $separar [1] [1];
	$total_minutos_trasncurridos [2] = ($separar [2] [0] * 60) + $separar [2] [1];
	$total_minutos_trasncurridos = $total_minutos_trasncurridos [1] - $total_minutos_trasncurridos [2];
	
	return ($total_minutos_trasncurridos);
}

// Function to sanitize values received from the form. Prevents SQL injection
function clean($str)
{
	$str = @trim ( $str );
	if (get_magic_quotes_gpc ())
	{
		$str = stripslashes ( $str );
	}
	// return mysql_real_escape_string($str);
	return ($str);
}

function save_image($inPath, $outPath)
{ // Download images from remote server
	$in = fopen ( $inPath, "rb" );
	$out = fopen ( $outPath, "wb" );
	while ( $chunk = fread ( $in, 8192 ) )
	{
		fwrite ( $out, $chunk, 8192 );
	}
	fclose ( $in );
	fclose ( $out );
}

function mantenimiento()
{
	echo '<script language="javascript" type="text/javascript">
	window.location.href="Mantenimiento.php?backurl="+window.location.href;
					</script>';
	
	exit ();
}


function convertir_especiales_html($str)
{
	if (! isset ( $GLOBALS ["carateres_latinos"] ))
	{
		$todas = get_html_translation_table ( HTML_ENTITIES, ENT_NOQUOTES );
		$etiquetas = get_html_translation_table ( HTML_SPECIALCHARS, ENT_NOQUOTES );
		$GLOBALS ["carateres_latinos"] = array_diff ( $todas, $etiquetas );
	}
	$str = strtr ( $str, $GLOBALS ["carateres_latinos"] );
	return $str;
}

function limpiarString($texto)
{
	$textoLimpio = preg_replace ( '([^A-Za-z0-9])', '', $texto );
	return $textoLimpio;
}

/**
 * Genera un combo select en base a una tabla x y un campo dando la posibilidad de elegir un valor para que aparesca preseleccionado
 *
 * @param unknown $tabla
 *        	Cabla que se incluira en la consulta de los valores del select
 * @param unknown $seleccionado
 *        	Valor a comparar para decidir si una opcion esta seleccionada o no
 * @param unknown $campoSelec
 *        	Campo que tendra el valor del select
 * @param unknown $campoTexto
 *        	Campo que tendra el texto a mostrar en caso de ser diferente del de el valor
 * @param unknown $mostrarValor
 *        	En caso de querer mostrar entre parentesis el valor del option
 */
function generarInputSelect($tabla, $campoSelec, $campoTexto = NULL, $seleccionado = NULL, $textoMayuscula = true, $mostrarValor = false)
{
	include ("Config/config.php");
	
	if ($campoTexto == NULL)
	{
		$campoTexto = $campoSelec;
	}

	$sql = "SELECT * FROM " . $tabla;

$result = $db->query ($sql);

	while ( $row = $db->fetch_array ($result))
	{
		$combobit .= ' <option value=' . $row [$campoSelec];
		
		if (isset ( $seleccionado ) and ($seleccionado == $row [$campoSelec]))
		{
			$combobit .= ' selected="selected"';
		}
		
		$combobit .= '>';
		
		if (isset ( $mostrarValor ) and ($mostrarValor == true))
		{
			$combobit .= ' (' . $row [$campoSelec] . ') ';
		}
		
		if ($textoMayuscula == true)
		{
			$combobit .= substr ( $row [$campoTexto], 0, 50 ) . ' </option>';
		}
		else
		{
			$combobit .= ucwords ( strtolower ( substr ( $row [$campoTexto], 0, 50 ) ) ) . ' </option>';
		}
	}
	echo $combobit;
}

//*****************************************************************************************************************//
//*****************************************************************************************************************//
//                                  Funciones comunes, la mayoría no especificas del sitio
//*****************************************************************************************************************//
//*****************************************************************************************************************//


//*****************************************************************************************************************//
// FUNCIONES DE TEXTO
//*****************************************************************************************************************//

function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function getNombreMes($numMes){
	switch ($numMes) {
		case 1:
			return "Enero";
			break;
		case 2:
			return "Febrero";
			break;
		case 3:
			return "Marzo";
			break;
		case 4:
			return "Abril";
			break;
		case 5:
			return "Mayo";
			break;
		case 6:
			return "Junio";
			break;
		case 7:
			return "Julio";
			break;
		case 8:
			return "Agosto";
			break;
		case 9:
			return "Septiembre";
			break;
		case 10:
			return "Octubre";
			break;
		case 11:
			return "Noviembre";
			break;
		case 12:
			return "Diciembre";
			break;
	
		default:
			break;
	}
}

/**
 * Obtiene el ID de video de una url de Youtube
 */
function getYoutubeVideoId($youtubeVideoLink){
	if(stripos($youtubeVideoLink, "youtube") > 0){
		$url = parse_url($youtubeVideoLink);
		$queryUrl = $url['query'];
		$queryUrl = parse_str($queryUrl);
		return $v;
	}elseif(stripos($youtubeVideoLink, "youtu.be") > 0){
		preg_match('/youtu.be\/([a-zA-Z0-9]*)/i', $youtubeVideoLink, $matches);
		return $matches[1];
	}
}

function encryptData($value, $key){ 
   $text = $value; 
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
   $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
    return $crypttext; 
} 

function decryptData($value, $key){ 
   $crypttext = $value; 
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
    return trim($decrypttext); 
} 

/**
 * Agrega el prefijo http:// a un string url si es que no lo tiene
 */
function agregarHTTP($string){
	if(!preg_match('/^(https?:\/\/)/i', $string)){
		$string = 'http://'.$string;
	}
	return $string;
}

/**
 * Remplaza links y emails agregandole los links
 */
function agregarLinksEmail($str, $target = "_blank"){
	$str = preg_replace('/([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})/i','<a href="mailto:\\1">\\1</a>',$str);
	return $str;
}

/**
 * Formatear un texto para remplazar URLs por links, también para remplazar URLs largas por 
 * sus versiónes resumidas solo para fines estéticos Ej: http://www.somesite.com/with/a/really/long/url/link   por   http://www.some...url/link
 * 
 * @param string $str texto donde hay que remplazar los links
 * @param int $len largo máximo
 * @param string $mid Caracteres ... de "continuacion"
 * @return string
 */
function formatearLinks($str, $target='_blank', $maxLen=50, $mid='...'){
	$left = ceil(0.6666 * $maxLen);
	$right = $maxLen - $left;
	preg_match_all('/(?<!=|\]|\/)((https?|ftps?|irc):\/\/|' . '(www([0-9]{1,3})?|ftp)\.)([0-9a-z-]{1,25}' . '[0-9a-z]{1}\.)([^\s&\[\{\}\]]+)/ims', $str, $matches);
	foreach($matches[0] as $key=>$value){
		$temp = $value;
		if(strlen($value) > ($maxLen + strlen($mid) + 2)){
			$value = substr($value, 0, $left) . $mid . substr($value,(-1 * $right));
		}
		$temp = !preg_match('/:\/\//', $temp) ? (substr($temp, 0, 3) === 'ftp' ? 'ftp://' . $temp : 'http://' . $temp) : $temp;
		$temp = $temp === $matches[0][$key] && $value === $matches[0][$key] ? '' : '=' . $temp;
		$str = str_replace($matches[0][$key],'[url' . $temp . ']' . $value . '[/url]', $str);
	}
	$str = preg_replace('/\[url=(?!http|ftp|irc)/ims', '[url=http://', $str);
	$str = preg_replace('/\[url\](.+?)\[\/url\]/ims','<a href="$1" target="'.$target.'" title="$1">$1</a>',$str);
	$str = preg_replace('/\[url=(.+?)\](.+?)\[\/url\]/ims', '<a href="$1" target="'.$target.'" title="$1">$2</a>', $str);
	return $str;
}

/**
formatMoney(1050); # 1,050 
formatMoney(1321435.4, true); # 1,321,435.40 
formatMoney(10059240.42941, true); # 10,059,240.43 
formatMoney(13245); # 13,245 
*/
function formatMoney($number, $fractional=false) { 
    if ($fractional) { 
        $number = sprintf('%.2f', $number); 
    } 
    while (true) { 
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
        if ($replaced != $number) { 
            $number = $replaced; 
        } else { 
            break; 
        } 
    } 
    return $number; 
} 

/**
 * Remplaza links y emails agregandole los links
 */
function remplazarEmailyWWW($str, $target = "_blank", $maxLen=50, $mid='...'){
	$str = agregarLinksEmail($str, $target);
	$str = formatearLinks($str, $target, $maxLen, $mid);
	return $str;
}

/**
 *  Corta un string del largo de caracteres especificado sin cortar palabras a la mitad.
 *
 * @param $str
 * @param Integer $len
 * @param String $txt_continua Texto que se agrega al final del string si es cortado (Ej: Mi perro se llam(continua...) )
 * @return String
 */
function cortar_str($str,$len,$txt_continua="..."){
	if(strlen($str)>$len){
		// Cortamos la cadena por los espacios
		$arrayTexto = split(' ',$str);
		
		$texto = '';
		$contador = 0;
		 
		// Reconstruimos la cadena
		while($len >= strlen($texto) + strlen($arrayTexto[$contador])){
		    $texto .= ' '.$arrayTexto[$contador];
		    $contador++;
		}
		
		return $texto.$txt_continua;
	}else{
		return $str;
	}
}

/**
 * Saca los saltos de linea ASCII de un string
 */
function strip_saltos($str){
	$str = ereg_replace( chr(13) , "" , $str );
	$str = ereg_replace( chr(10) , "" , $str );
	return $str;
}

/**
 * Formatea un string para crear una cadena para usar como url amigable para los buscadores
 *
 * @param string $str
 * @return string
 * @author Andres Carizza
 * @version 1.2
 */
function url_amigable($str){
	global $sitio; //de mi framework
	
	$url = mb_strtolower($str, $sitio->charset);

	$url = remplazar_caracteres_latinos ($url); 

	// Añadimos los guiones 
	$find = array(' ', '&', '\r\n', '\n', '+'); 
	$url = str_replace ($find, '-', $url); 

	// Eliminamos y Reemplazamos demás caracteres especiales 
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/'); 
	$repl = array('', '-', ''); 
	$url = preg_replace ($find, $repl, $url); 

	return $url; 
}

/**
 * Remplaza caracteres latinos. Ej: á -> a, ñ -> n
 * @param string $str
 * @return string
 * @author Andres Carizza
 * @version 1.2
 * */
function remplazar_caracteres_latinos($str){
	$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü', 'ç', 'ã', 'ê', 'à'   ,   'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'Ü', 'Ç', 'Ã', 'Ê', 'À'); 
	$repl = array('a', 'e', 'i', 'o', 'u', 'n', 'u', 'c', 'a', 'e', 'a'   ,   'A', 'E', 'I', 'O', 'U', 'N', 'U', 'C', 'A', 'E', 'A'); 
	return str_replace ($find, $repl, $str);
}

/**
 * Formatea un string para que corresponda con un nombre válido de archivo
 *
 * @param string $str
 * @return string
 * @author Andres Carizza
 * @version 1.1
 */
function format_valid_filename($str, $remplazarCaracteresLatinos=true, $conservarEspacios=false){
	// Eliminamos y Reemplazamos caracteres especiales 
	
	$str = str_replace('\\', '', $str);
	$str = str_replace('/', '', $str);
	$str = str_replace('*', '', $str);
	$str = str_replace(':', '', $str);
	$str = str_replace('?', '', $str);
	$str = str_replace('"', '', $str);
	$str = str_replace('<', '', $str);
	$str = str_replace('>', '', $str);
	$str = str_replace('|', '', $str);
	
	if($remplazarCaracteresLatinos) $str = remplazar_caracteres_latinos($str);
	if($conservarEspacios) $str = str_replace(" ", "-", $str);

	return $str; 
}

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

/**
 * Limpia un string para se usado en una busqueda 
 *
 * @param string $valor
 * @return string
 */
function limpiarParaBusquedaSql($valor){
	$valor = str_ireplace("%","",$valor);
	$valor = str_ireplace("--","",$valor);
	$valor = str_ireplace("^","",$valor);
	$valor = str_ireplace("[","",$valor);
	$valor = str_ireplace("]","",$valor);
	$valor = str_ireplace("\\","",$valor);
	$valor = str_ireplace("!","",$valor);
	$valor = str_ireplace("¡","",$valor);
	$valor = str_ireplace("?","",$valor);
	$valor = str_ireplace("=","",$valor);
	$valor = str_ireplace("&","",$valor);
	$valor = str_ireplace("'","",$valor);
	$valor = str_ireplace('"',"",$valor);
	return $valor;
}

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('remove_invisible_characters'))
{
	function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();
		
		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)
		
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}
		
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
}

/**
 * Lo mismo que utf8_encode() pero aplicado a todo el array
 *
 * @param array $array
 * @return array
 */
function utf8_encode_array($array){
	return is_array($array) ? array_map('utf8_encode_array', $array) : utf8_encode($array);
}

/**
 * Lo mismo que utf8_decode() pero aplicado a todo el array
 *
 * @param array $array
 * @return array
 */
function utf8_decode_array($array){
	return is_array($array) ? array_map('utf8_decode_array', $array) : utf8_decode($array);
}

//*****************************************************************************************************************//
// FUNCIONES DE FECHA
//*****************************************************************************************************************//

/**
* Formatea la fecha que usa el MySQL (YYYY-MM-DD) o (YYYY-MM-DD HH:MM:SS) a un formato de fecha más claro
* En caso de que falle el formateo retorna FALSE
* 
* @param String $mysqldate La fecha en formato YYYY-MM-DD o YYYY-MM-DD HH:MM:SS
* @param Boolean $conHora True si se quiere dejar la hora o false si se quiere quitar
* @return String La fecha formateada
* @version 1.1
**/
function mysql2date($mysqldate, $conHora=false){
	$fecha_orig = $mysqldate;

	if(strlen($fecha_orig) > 10){ //si es formato YYYY-MM-DD HH:MM:SS
		$hora = substr($mysqldate,11,strlen($mysqldate));
		$mysqldate = substr($mysqldate,0,10);
	}

	$datearray = explode("-", $mysqldate);

	if(count($datearray) != 3) return ""; //en caso de que no sean tres bloques de numeros falla

	$yyyy = $datearray[0];

	$mm = $datearray[1];

	$dd = $datearray[2];

	if(strlen($fecha_orig) > 10 and $conHora){ //si es formato YYYY-MM-DD HH:MM:SS
		return "$dd/$mm/$yyyy $hora";
	}else{
		return "$dd/$mm/$yyyy";
	}
}

/**
* Convierte el formato de fecha (DD/MM/YYYY) al que usa el MySQL (YYYY-MM-DD)
* Se pueden enviar dias y meses con un digito (ej: 3/2/1851) o así (ej: 03/02/1851)
* La fecha tiene que enviarse en el orden dia/mes/año 
* En caso de que falle el formateo retorna FALSE
* 
* @param String $date La fecha en formato DD/MM/YYYY o  D/M/YYYY
* @return String La fecha formateada o FALSE si el formato es invalido
* @version 1.3
**/
function date2mysql($date){
	if(!ereg('^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$', $date)) return false;
	
	$datearray = explode("/", $date);

	$dd = $datearray[0];
	if($dd > 0 and $dd <= 31){ $dd = sprintf("%02d",$dd); }else{ return false; } //un minimo chequeo del dia

	$mm = $datearray[1];
	if($mm > 0 and $mm <= 12){ $mm = sprintf("%02d",$mm); }else{ return false; } //un minimo chequeo del mes

	$yyyy = $datearray[2];
	if($yyyy > 0 and $yyyy <= 9999){ $yyyy = sprintf("%04d",$yyyy); }else{ return false; } //un minimo chequeo del año

	return "$yyyy-$mm-$dd";
}

/**
 * Retorna la representacion de una fecha (por ejemplo: Hace 3 días. o Ayer)
 * Para usar entre 0 minutos de diferencia hasta semanas
 *
 * @param Integer $ts Timestamp
 * @param String $formatoFecha El formato de fecha a mostrar para cuando es mayor a 31 días
 * @return String
 * @version 1.2
 */
function mysql2preety($ts, $formatoFecha="d/m/Y"){
	if(!ctype_digit($ts))
		$ts = strtotime($ts);

	$diff = time() - $ts;
	$day_diff = floor($diff / 86400);

	if($day_diff < 0) return date($formatoFecha, $ts); //fecha futura! no deberia pasar..

	if($day_diff == 0)
	{
		if($diff < 60) return "Recién";
		if($diff < 120) return "Hace un minuto";
		if($diff < 3600) return "Hace " . floor($diff / 60) . " minutos";
		if($diff < 7200) return "Hace una hora";
		if($diff < 86400) return "Hace " . floor($diff / 3600) . " horas";
	}
	
	if($day_diff == 1) return "Ayer";
	
	if($day_diff < 7) return "Hace " . $day_diff . " días";
	
	if($day_diff < 31) return "Hace " . ceil($day_diff / 7) . " semanas";

	return date($formatoFecha, $ts);
}


//*****************************************************************************************************************//
// FUNCIONES DE EMAIL
//*****************************************************************************************************************//

/**
 * Funcion mail extendida
 *
 * @param String $para Email del destinatario o en formato: "Juan Perez" <juan@hotmail.com>
 * @param String $asunto
 * @param String $mensaje
 * @param String $deEmail Email del remitente
 * @param String $deNombre Nombre del remitente
 * @param Boolean $html True si es en formato HTML (por defecto) o FALSE si es texto plano
 * @param String $prioridad Alta o Baja (por defecto es Normal)
 * @param String $xmailer X-Mailer ej: Mi Sistema 1.0.2
 * @param String $notificacion_lectura_a Email donde se envia la notificacion de lectura del mensaje en formato: "Juan Perez" <juan@hotmail.com>
 * @return Boolean
 * @version 1.1
 */
function enviar_mail($para, $asunto, $mensaje, $deEmail, $deNombre, $html=true, $prioridad="Normal", $xmailer="", $notificacion_lectura_a=""){
	$headers = "MIME-Version: 1.0 \n" ;
	if ($html) {
		$headers .= "Content-Type:text/html;charset=ISO-8859-1 \n";
	}else{
		$headers .= "Content-Type:text/plain;charset=ISO-8859-1 \n";
	}
	$headers .= "From: \"$deNombre\" <$deEmail> \n";
	
	if (strtolower($prioridad) == "alta") {
		$headers .= "X-Priority: 1 \n";
	}elseif (strtolower($prioridad) == "baja") {
		$headers .= "X-Priority: 5 \n";
	}
	
	if($xmailer != "") $headers .= "X-Mailer: $xmailer \n";
	
	if($notificacion_lectura_a != "") $headers .= "Disposition-Notification-To: $notificacion_lectura_a \n";

	if(@mail($para, $asunto, $mensaje, $headers)){
		return true;
	}else{
		return false;
	}	
}

/**
 * Funcion mail extendida con phpmailer
 *
 * @param array $para Destinatario/s  array(array("email@serv.com","Juan")) o array(array("email@serv.com","Juan") , array("otro@serv.com","Pablo"))
 * @param String $asunto
 * @param String $mensaje
 * @param String $deEmail Email del remitente
 * @param String $deNombre Nombre del remitente
 * @param Boolean $html True si es en formato HTML (por defecto) o FALSE si es texto plano
 * @param array $adjuntos Archivos adjuntos en el email. array(array($_FILES['archivo']['tmp_name'] , $_FILES['archivo']['name']))
 * @param string $charSet
 * @param string $mailer "mail" o "sendmail" o "smtp"
 * @return nada
 * @version 1.0
 */
function mail_ext($para, $asunto, $mensaje, $deEmail, $deNombre, $html=true, $adjuntos="", $charSet="iso-8859-1", $mailer="mail", $sendmail="/usr/sbin/sendmail", $smtpHost="localhost", $smtpPort=25, $smtpHelo="localhost.localdomain", $smtpTimeOut=10){
	$mail = new PHPMailer();
	$mail->IsHTML($html);
	$mail->Host = "localhost";
	$mail->From = $deEmail;
	$mail->FromName = $deNombre;
	$mail->Subject = $asunto;
	$mail->Body = $mensaje;
	$mail->CharSet = $charSet;
	
	foreach ($para as $item) {
		if(!is_array($item) or count($item) != 2) 
			throw new Exception('Parametro $para no es un array con el formato correcto en enviar_mail()');
		
		$mail->AddAddress($item[0], $item[1]);
	}

	if (is_array($adjuntos)) {
		foreach ($adjuntos as $adjunto) {
			$mail->AddAttachment($adjunto[0], $adjunto[1]);
		}
	}
	
	if ($mailer == "sendmail") {
		$mail->sendmail = $sendmail;
		
	}elseif ($mailer == "smtp") {
		$mail->Host = $smtpHost;
		$mail->Port = $smtpPort;
		$mail->Helo = $smtpHelo;
		$mail->Timeout = $smtpTimeOut;
	}
	
	$mail->Send();
}

//*****************************************************************************************************************//
// FUNCIONES VARIAS
//*****************************************************************************************************************//

/**
 * Imprime el META de HTML y hace Exit para redireccionar al usuario a $url
 * Esta función es util para cuando no se pueden mandar headers por haber impreso antes
 *
 * @param String $url
 * @param Integer $segundos Tiempo en segundos antes de hacer la redireccion
 * @param String $mensaje Un mensaje opcional a imprimir en pantalla
 * @version 1.0
 */
function redirect_http($url, $segundos=0, $mensaje=""){
	echo "<HTML><HEAD>";
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$segundos; URL=$url\">";
	if ($mensaje!="") echo $mensaje;
	echo "</HEAD></HTML>";
	exit;
}

/**
 * Imprime el header Location y hace Exit para redireccionar al usuario a $url
 * Nota: en el caso de que la variable $debug esta seteada a TRUE en vez de mandar 
 * el header llama a la funcion redirect_http() porque al estar debugueando el header no 
 * se podria mandar por haber mandado contenido antes.
 *
 * @param String $url
 * @version 1.0
 */
function redirect($url){
	global $debug, $start_time;
	
	if ($debug) {
		redirect_http($url, 120, "<i>Transcurrieron ".(microtime()-$start_time)." segundos</i><br><a href='$url'>Haga click para continuar a: $url</a>");
	}else{
		header("Location:$url");
		exit();
	}
}

/**
 * Borra un directorio con todos sus archivos y sub directorios
 *
 * @param string $dir
 */
function delTree($dir) { 
  if (is_dir($dir)) { 
		$objects = scandir($dir); 
		foreach ($objects as $object) { 
		 if ($object != "." && $object != "..") { 
		   if (filetype($dir."/".$object) == "dir") delTree($dir."/".$object); else unlink($dir."/".$object); 
		 } 
		} 
		reset($objects); 
		rmdir($dir); 
	}  
}

/**
 * Retorna true si el valor es un numero. 
 * A diferencia de las funciones de PHP esta solo va a retornar TRUE si en el valor hay unicamente numeros.
 *
 * @param $valor
 * @return boolean
 */
function es_numerico($valor){
	if ($valor != "" and ereg( "^[0-9]+$", $valor ) ) {
	    return true;
	} else {
	    return false;
	}
}

/**
* Crea los tags <OPTION> numericos para un <SELECT>
* parametros: 
* $desde - desde que numero
* $hasta - hasta que numero
* $incremento - de a cuanto incrementa
* $selected - cual tiene que estar seleccionado (ninguno = "")
**/
function crear_opciones_select($desde,$hasta,$incremento=1,$selected=""){
	for($i=$desde; $i<=$hasta; $i=$i+$incremento){
		if($i==$selected){
			echo "<option value=$i selected='selected'>$i</option>\n";
		}else{
			echo "<option value=$i>$i</option>\n";
		}
	}
}


/**
 * Obtiene el hostname de una url. ej: http://www.google.com/adsense?u=232 retorna: google.com
 *
 * @param string $url
 * @param bool $stripWww
 * @return string
 */
function extractHostPart($url, $stripWww=true){
	$partes = parse_url($url);
  
  if ($partes === false) {
  	return false;
  }else{
  	$hostName = $partes['host'];
  	if($stripWww) $hostName = preg_replace("/www./i", "", $partes['host']);
  	return $hostName;
  }
}

/**
 * Obtiene el hostname de un email. ej: ejemplo@gmail.com retorna: gmail.com
 *
 * @param string $email
 * @return string o FALSE si no es un email valido
 */
function getHostNameEmail($email){
	if(eregi("^([_a-z0-9+-]+)(\.[_a-z0-9+-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)){
		preg_match('/^([_a-z0-9+-]+)(\.[_a-z0-9+-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $email, $matches);
		return $matches[3].$matches[5];
	}else{
		return false;
	}
}

/**
 * Genera un dump de una base de datos a un archivo en el servidor o para bajar directamente sin usar comandos externos
 *
 * @param string $host DB host
 * @param string $user DB user
 * @param string $pass DB pass
 * @param string $dbname DB name
 * @param string $tables Puede ser * para que sean todas las tablas, o las tablas separadas por comas o un array
 * @param string $fileName Nombre del archivo de destino, si $download = true es el nombre del archivo que baja, sino es el archivo que genera en el servidor
 * @param boolean $download True para que baje el archivo .sql generado
 */
function backup_db($host, $user, $pass, $dbname, $tables = '*', $fileName, $download=true){
	
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($dbname,$link);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	
	if ($download) {
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename=".$fileName);		
		header("Content-Length: ".strlen($return)); //it is needed for the progress bar of the browser
		echo $return;
		
	}else{
		
		$handle = fopen($fileName, 'w+');
		fwrite($handle, $return);
		fclose($handle);
	}
	
	$return = "";
	mysql_close($link);
}


/**
 * Retorna true si el dominio del $email pertenece a un dominio de emails temporales anti spam
 *
 * @param string $email
 * @return boolean
 */
function dominioEmailBaneado($email){
	
	//Dominios de email baneados
	$hostNoValidosParaEmail = array("mailinator.com","binkmail.com","suremail.info","bobmail.info","anonymbox.com","deadaddress.com","spamcero.com","zippymail.info","sogetthis.com","safetymail.info","thisisnotmyrealemail.com","tradermail.info","nepwk.com","sharklasers.com","tempemail.net","temporaryemail.net","trashymail.com","maileater.com","spambox.us","spamhole.com","pookmail.com","mailslite.com","20minutemail.com","nwldx.com","makemetheking.com");
	
	if(in_array(getHostNameEmail(strtolower($email)), $hostNoValidosParaEmail)){
		return true;
	}else{
		return false;
	}
}

function is_ajax_request(){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	}else{
		return false;
	}
}

/**
 * Para el admin, imprime el link para abrir el ligthbox para subir y recortar la foto de $idRegistro, $tabla que se llame $tipoFoto
 * Opcionalmente puede imprimir la imagen al lado del link
 * @param boolean $incluirImagen Imprime la imagen ademas del link
 * @param string $textoLink El texto del link para editar la imagen
 * @idRegistro int El id del registro al que pertenece la imagen (ver class_archivos)
 * @tabla string El nombre de la tabla al que pertenece el registro (ver class_archivos)
 * @tipoFoto string El identificador de la foto, que se usa para guardar y recuperar las diferentes imagenes de un registro (ver class_archivos)
 * */
function linkEditarFoto($incluirImagen, $textoLink, $tituloLigthbox="", $idRegistro, $tabla, $tipoFoto, $wMax, $hMax, $wMin, $hMin, $aspectRatio=0){
	global $cl_archivos, $db, $sitio;
	
	$rnd = rand(1,9999999);
	
	if($incluirImagen){
		$foto = $cl_archivos->getArchivoPrincipal($tabla, $idRegistro, $tipoFoto);
		
		//para el ancho o alto del <img> de vista previa
		if(is_array($foto)){
			$imgSize = @getimagesize(dirname(dirname(__FILE__))."/archivos/".$foto[archivo]);
			if(is_array($imgSize)){
				if($imgSize[1] > $imgSize[0]){
					$widthHeight = "height";
				}else{
					$widthHeight = "width";
				} 
			}
		}
		
		echo "<img $widthHeight='150' id='img$rnd' src='".(is_array($foto) ? $sitio->pathBase."archivos/".$foto[archivo] : "")."' style='".(is_array($foto) ? "" : "display:none")."' /><br/>";
	}
	?>
	<script>
	$(document).ready(function(){
		$(".lnk<?=$rnd?>").colorbox({iframe:true, rel:'<?=$rnd?>', width:"90%", height:"90%",
		onClosed: function(){
			<?if($incluirImagen){?>
				//actualiza la imagen por ajax
				$.ajax({
				   url: "ajax.php?getImgSrc=1",
				   data: "idRegistro=<?=$idRegistro?>&tabla=<?=$tabla?>&tipoFoto=<?=$tipoFoto?>",
				   success: function(r){
				   	 if(r != ''){
				     	$('#img<?=$rnd?>').css('display', 'inline');
				     	$('#img<?=$rnd?>').attr('src', '../archivos/'+r+'?'+Math.random());
			     	 }else{
			     		$('#img<?=$rnd?>').css('display', 'none');
			     	 }
				   }
				 });
			<?}?>
		}
		});	
	});
	</script>
	<a class='lnk<?=$rnd?>' href="editar_foto.php?idRegistro=<?=$idRegistro?>&tabla=<?=$tabla?>&tipoFoto=<?=$tipoFoto?>&wMax=<?=$wMax?>&hMax=<?=$hMax?>&wMin=<?=$wMin?>&hMin=<?=$hMin?>&aspectRatio=<?=$aspectRatio?>" rel="colorbox" title="<?=$tituloLigthbox?>"><?=$textoLink?></a>
	<?
}

?>