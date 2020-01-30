<?php

/**
 * Proyecto de archivo generico de insercion.
 *
 * La idea esta en convertirlo en un especie de framework.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 *
 * @deprecated Estas funciones se movieron a la clase Sitio, se recomienda utilizarlas desde ahi.
 */
// require ("class_sitio.php");
// require ("class_abm.php");
// require ("class_db.php");
// require ("class_paginado.php");
// require ("class_orderby.php");

// // conexion a la bd
// $db = new class_db ($servidor, $usr, $pass, $db, "utf8");
// $db->mostrarErrores = true;
// $db->connect ();

// // utilidades de sitio
// $sitio = new class_sitio ();

/**
 * Convierte de un array todas las entidades HTML para que sea seguro mostrar en pantalla strings ingresados por los usuarios.
 *
 * @example $_REQUEST = limpiarEntidadesHTML($_REQUEST, $config);
 *         
 * @param string[] $param
 *        	- datos de lo cuales limpiarl las entidades html.
 * @param object $sitio
 *        	- Objeto encargado de la administracion de la configuracion del sitio.
 * @return array|string - Depende del parametro recibido, un array con los datos remplazados o un String
 */
function limpiarEntidadesHTML($param, $sitio)
{
	return is_array ($param) ? array_map ('limpiarEntidadesHTML', $param) : htmlentities ($param, ENT_QUOTES, $sitio->charset);
}

/**
 * Comprueba que la direccion de mail no tenga caracteres extra�os.
 *
 * @param string $str
 *        	- email a verificar
 * @return bool - Devuelve true o false dependiendo de si es o no un mail valido.
 */
function validarEmail($str)
{
	if (preg_match ('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $str))
	{
		return true;
	}
	return false;
}

/**
 * Escapa de un array todos los caracteres especiales de una cadena para su uso en una sentencia SQL
 *
 * @example $_REQUEST = limpiarParaSql($_REQUEST);
 *         
 * @deprecated - Esta funcion es remplazada por real_escape_string de la clase db.
 *            
 * @param string[] $param
 * @param object $db
 *        	- Objeto encargado de la administracion de la base de datos.
 * @return string[] - Depende del parametro recibido, un array con los datos remplazados o un String
 */
function limpiarParaSql($param, $db)
{
	// global $db;
	return is_array ($param) ? array_map ('limpiarParaSql', $param) : mysqli_real_escape_string ($db->con, $param);
}

/**
 * Reemplaza todos los acentos por sus equivalentes sin ellos.
 * Ademas elimina cualquier caracter extra�o en el string.
 *
 * @param string $string
 *        	-
 *        	la cadena a sanear
 *        	
 * @return string $string - saneada
 */
function sanear_string($string)
{
	$string = trim ($string);

	$string = str_replace (array (
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�'
	), array (
			'a',
			'a',
			'a',
			'a',
			'a',
			'A',
			'A',
			'A',
			'A'
	), $string);

	$string = str_replace (array (
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�'
	), array (
			'e',
			'e',
			'e',
			'e',
			'E',
			'E',
			'E',
			'E'
	), $string);

	$string = str_replace (array (
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�'
	), array (
			'i',
			'i',
			'i',
			'i',
			'I',
			'I',
			'I',
			'I'
	), $string);

	$string = str_replace (array (
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�'
	), array (
			'o',
			'o',
			'o',
			'o',
			'O',
			'O',
			'O',
			'O'
	), $string);

	$string = str_replace (array (
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�',
			'�'
	), array (
			'u',
			'u',
			'u',
			'u',
			'U',
			'U',
			'U',
			'U'
	), $string);

	$string = str_replace (array (
			'�',
			'�',
			'�',
			'�'
	), array (
			'n',
			'N',
			'c',
			'C'
	), $string);

	// Esta parte se encarga de eliminar cualquier caracter extra�o
	$string = str_replace (array (
			"\\",
			"�",
			"�",
			"-",
			"~",
			"#",
			"@",
			"|",
			"!",
			"\"",
			"�",
			"$",
			"%",
			"&",
			"/",
			"(",
			")",
			"?",
			"'",
			"�",
			"�",
			"[",
			"^",
			"<code>",
			"]",
			"+",
			"}",
			"{",
			"�",
			"�",
			">",
			"< ",
			";",
			",",
			":",
			".",
			"�"
	), '', $string);

	return $string;
}

/**
 * Remplaza los caracteres especiales por sus etiquetas html.
 *
 * @global string[] $GLOBALS - Array de variables gobales en caso de no existir usa get_html_translation_table.
 * @param string $str
 *        	- Texto en el cual remplazar los caracteres especiales.
 *        	
 * @return string - Texto con los caracteres remplazados.
 */
function convertir_especiales_html($str)
{
	// global $GLOBALS;
	if (!isset ($GLOBALS["carateres_latinos"]))
	{
		$todas = get_html_translation_table (HTML_ENTITIES, ENT_NOQUOTES);
		$etiquetas = get_html_translation_table (HTML_SPECIALCHARS, ENT_NOQUOTES);

		$GLOBALS["carateres_latinos"] = array_diff ($todas, $etiquetas);
	}
	$str = strtr ($str, $GLOBALS["carateres_latinos"]);
	return $str;
}

/**
 * Alimina cualquier caracter que no sea de la A a la z o numero.
 *
 * @param string $texto
 * @return string
 */
function limpiarString($texto)
{
	$textoLimpio = preg_replace ('([^A-Za-z0-9])', '', $texto);
	return $textoLimpio;
}

/**
 * Funci�n para sanear los valores recibidos del formulario.
 * Evita la inyecci�n de SQL. Elimina cualquier caracter no numerico.
 *
 * @param string $str
 * @return int
 */
function clean_numeric($str)
{
	$str = trim ($str);
	if (get_magic_quotes_gpc ())
	{
		$str = stripslashes ($str);
	}
	// Elimino los espacios
	$str = str_replace (" ", "", $str);
	// Elimino todo lo que no sea numerico
	$str = ereg_replace ("[^0-9]", "", $str);

	return $str;
}

/**
 * Devuelve el mensaje dado limpio de caracteres especiales
 *
 * @param string $mensaje
 *        	- Mensaje a limpiar
 * @param string $nopermitidos
 *        	- Caracteres a eliminar del string, ya establecidos por defecto pero con posibilidad de modificarlos.
 * @return string - Mensaje ya sin dichos caracteres
 */
function quitar($mensaje, $nopermitidos = "")
{
	if (!isset ($nopermitidos) or $nopermitidos == "")
	{
		$nopermitidos = array (
				"'",
				'\\',
				'<',
				'>',
				"\""
		);
	}

	$mensaje = str_replace ($nopermitidos, "", $mensaje);
	return $mensaje;
}

/**
 * Remueve los NULLs (\0) de un string.
 *
 * @param string $string
 * @return string
 */
function removeNulls($string)
{
	$line = str_replace ("\0", "", $string);
	return $line;
}

/**
 * Genera los objetos de un SELECT de html.
 *
 * @param object $db
 *        	- Objeto encargado de la administracion de la base de datos.
 * @param string $tabla
 *        	- Tabla de la cual recuperar los datos para armar el < select>.
 * @param string $campoSelec
 *        	- Campo de donde se saca el index de la tabla.
 * @param string $campoTexto
 *        	- Campo con el texto a mostrar en el select. En caso de ser null usaria el valor del $campoSelec
 * @param string $seleccionado
 *        	- Define el valor que deveria aparecer como seleccionado por defecto.
 * @param boolean $textoMayuscula
 *        	- En caso de ser true muestra el texto en mayusculas.
 * @param boolean $mostrarValor
 *        	- Si es true al texto del select le agrega entre parentesis el index.
 * @param string $specialWhere
 *        	- Define datos adicionales para la consulta, debe comenzar con AND.
 * @return string - String con los option de un campo select html basandose en una consulta a la DB.
 */
function generarInputSelect($db, $tabla, $campoSelec, $campoTexto = NULL, $seleccionado = NULL, $textoMayuscula = true, $mostrarValor = false, $specialWhere = "")
{
	try
	{
		if ($campoTexto == NULL)
		{
			$campoTexto = $campoSelec;
		}

		$campos = "DISTINCT(" . $campoSelec . ")";

		if (isset ($campoTexto) and $campoTexto != "")
		{
			$campos = $campos . ", " . $campoTexto;
		}

		$sql = "SELECT " . $campos . " FROM " . $tabla . " WHERE 1=1 " . $specialWhere;

		$result = $db->query ($sql);

		while ($row = $db->fetch_array ($result))
		{
			if (!isset ($combobit))
			{
				$combobit = "";
			}

			$combobit .= ' <option value=' . $row[$campoSelec];

			if (isset ($seleccionado) and ($seleccionado == $row[$campoSelec]))
			{
				$combobit .= ' selected="selected"';
			}

			$combobit .= '>';

			if (isset ($mostrarValor) and ($mostrarValor == true))
			{
				$combobit .= ' (' . $row[$campoSelec] . ') ';
			}

			if ($textoMayuscula == true)
			{
				$combobit .= substr ($row[$campoTexto], 0, 50) . ' </option>';
			}
			else
			{
				$combobit .= ucwords (strtolower (substr ($row[$campoTexto], 0, 50))) . ' </option>';
			}
		}
		return $combobit;
	}
	catch (Exception $e)
	{
		if ($db->debug == true)
		{
			return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage ();
			// echo __LINE__ . " - " . __FILE__ . " - " . $e->getMessage ();
		}
		else
		{
			return $e->getMessage ();
			// echo $e->getMessage ();
		}

		if ($db->dieOnError == true)
		{
			exit ();
		}
	}
}

/*
 * ****************************************************************************
 * Funciones de Fechas
 * ****************************************************************************
 */

/**
 * Agarra caulquier fecha con el format inicial YYYY MM DD y la formatea para oracle.
 *
 * @param string $fecha_inicio
 *        	- Fecha con el formato YYYY-MM-DD o YYYY/MM/DD.
 * @param string $separador
 *        	- Caracter con el cual se va a separar la fecha, por defecto /.
 * @throws Exception - retorna un error si la cantidad de digitos numericos de $fecha_inicio es menor que 8.
 *        
 * @return string - retorna la fecha con el formato DD MM YYYY separado por el caracter separador.
 */
function formatear_fecha_Oracle($fecha_inicio, $separador = "/")
{
	try
	{
		// $fecha_inicio = str_replace ('-', '', $fecha_inicio);
		// $fecha_inicio = str_replace ('/', '', $fecha_inicio);
		$fecha_inicio = preg_replace ('([^0-9])', '', $fecha_inicio);

		if (strlen ($fecha_inicio) == 8)
		{
			$dd = substr ($fecha_inicio, -2);
			$mm = substr ($fecha_inicio, 4, 2);
			$yyyy = substr ($fecha_inicio, 0, 4);

			$fecha_inicio = $dd . $separador . $mm . $separador . $yyyy;

			return $fecha_inicio;
		}
		else
		{
			throw new Exception ('ERROR: El formato de fecha es incorrecto.');
		}
	}
	catch (Exception $e)
	{
		if ($db->debug == true)
		{
			return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage ();
		}
		else

		{
			return $e->getMessage ();
		}

		if ($db->dieOnError == true)
		{
			exit ();
		}
	}
}

/**
 * invierte el orden de la fecha para que quede en el formato dia-mes-a�o
 *
 * @deprecated - Conviene utilizar formatear_fecha_Oracle.
 *            
 * @param DateTime $fecha
 *        	fecha con el formato ano-mes-dia
 * @return string $aux
 */
function invertirFecha($fecha)
{
	list ($ano, $mes, $dia) = explode ('-', $fecha);
	$aux = $dia . "-" . $mes . "-" . $ano;

	return $aux;
}

/**
 * Devuelve el dia correspondiente de la semana en formato de tres letras.
 *
 * @param string $fecha
 *        	- fecha con el formato ano-mes-dia
 * @return string $dias
 */
function nombreDiacorto($fecha)
{
	list ($ano, $mes, $dia) = explode ('-', $fecha);
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

	return $dias[date ("w", mktime (0, 0, 0, $mes, $dia, $ano))];
}

/**
 * Suma una cantidad X de dias a una fecha.
 *
 * @param string $fecha
 *        	- fecha con el formato ano-mes-dia.
 * @param int $dia
 *        	- numero de dias a sumar.
 * @return DateTime - fecha con los dias sumados.
 */
function sumaDia($fecha, $dia)
{
	list ($year, $mon, $day) = explode ('-', $fecha);

	return date ('Y-m-d', mktime (0, 0, 0, $mon, $day + $dia, $year));
}

/**
 * Diferencia de Dias - Fecha mayor, Fecha menor
 *
 * @param string $fecha2
 *        	- Fecha mayor con el formato ano-mes-dia
 * @param string $fecha1
 *        	- fecha menor con el formato ano-mes-dia
 * @return string $dias_diferencia - Cantidad de dias que hay entre las dos fechas
 */
function diferenciaDias($fecha2, $fecha1)
{
	list ($ano2, $mes2, $dia2) = explode ('-', $fecha1);
	list ($ano1, $mes1, $dia1) = explode ('-', $fecha2);

	// calculo timestam de las dos fechas
	$timestamp1 = mktime (0, 0, 0, $mes1, $dia1, $ano1);
	$timestamp2 = mktime (0, 0, 0, $mes2, $dia2, $ano2);

	// resto a una fecha la otra
	$segundos_diferencia = $timestamp1 - $timestamp2;

	// convierto segundos en D&iacute;as
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

	return round ($dias_diferencia);
}

/**
 * Chequea que la fecha ingresada sea correcta
 *
 * @deprecated conviene usar checkdate directamente.
 *            
 * @param int $d
 *        	- El Dia que esta dentro del Numero de Duas del mes m dado. Los anos a bisiestos son tomados en consideracion.
 * @param int $m
 *        	- El mes entre 1 y 12 inclusive.
 * @param int $a
 *        	- El ano entre 1 y 32767 inclusive.
 *        	
 * @return bool puede ser true o false dependiendo si la fecha es correcta o no
 */
function fechaCorrecta($d, $m, $a)
{
	if (checkdate ($m, $d, $a))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * Se le pasan dos horas y realiza la diferencia entre ambas.
 *
 * @deprecated Por su mayor presicion se recomienda la utilizacion de difHoras.
 *            
 * @param string $hora1
 *        	- Hora base con el formato hh:mm.
 * @param string $hora2
 *        	- Hora a restar con el formato hh:mm.
 *        	
 * @return number - Cantidad de minutos de diferencia entre horas.
 */
function calcularMminutosExcedentes($hora1, $hora2)
{
	$separar[1] = explode (':', $hora1);
	$separar[2] = explode (':', $hora2);

	$total_minutos_trasncurridos[1] = ($separar[1][0] * 60) + $separar[1][1];
	$total_minutos_trasncurridos[2] = ($separar[2][0] * 60) + $separar[2][1];
	$total_minutos_trasncurridos = $total_minutos_trasncurridos[1] - $total_minutos_trasncurridos[2];

	return ($total_minutos_trasncurridos);
}

/**
 * Diferencia de horas - Hora mayor, Hora menor
 *
 * @param string $inicio
 *        	- Hora mayor con el formato H:i:s
 * @param string $fin
 *        	- Hora menor con el formato H:i:s
 * @return string - Hora con el valor de la resta
 */
function difHoras($inicio, $fin)
{
	$inicio = strtotime ($inicio);
	$fin = strtotime ($fin);
	$dife = $fin - $inicio;
	$dif = date ("H:i", strtotime ("00:00") + $dife);

	return $dif;
}

/**
 * Suma de horas
 *
 * @param string $hora1
 *        	- Primer valor a sumar con el formato H:i:s
 * @param string $hora2
 *        	- Segundo valor a sumar con el formato H:i:s
 * @return string - resultado de la suma de horas
 */
function sumaHoras($hora1, $hora2)
{
	$hora1 = strtotime ($hora1);
	$hora2 = strtotime ($hora2);
	$horaSum = $hora2 + $hora1;
	$sum = date ("H:i", strtotime ("00:00") + $horaSum);

	return $sum;
}

// FIXME estas dos funciones hay que revisarlas porque no paresen correctas.
// /**
// * Convierte un valor en segundos a horas.
// *
// * @param string $hora - Valor con el formato H:m:s
// * @return string - resultado de la suma de horas
// */
// function segundos_a_hora($hora)
// {
// list ($h, $m, $s) = explode (':', $hora);
// return ($h * 3600) + ($m * 60) + $s;
// }

// /* De hora a segundos */
// function hora_a_segundos($segundos)
// {
// $h = floor ($segundos / 3600);
// $m = floor (($segundos % 3600) / 60);
// $s = $segundos - ($h * 3600) - ($m * 60);
// return sprintf ('%02d:%02d', $h, $m);
// }

?>