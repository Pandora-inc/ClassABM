<?php

/**
 * MUY IMPORTANTE!!!! POR FAVOR RECUERDE GRABAR EL ARCHIVO COMO UTF-8 PARA EVITAR ROMPER LOS CARACTERES ESPECIALES
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @todo 22 nov. 2018
 * @lenguage PHP
 * @name funciones_string.php
 * @version 0.1 version inicial del archivo.
 * @package
 * @project
 */

/*
 * Querido programador:
 *
 * Cuando escribi este codigo, solo Dios y yo sabiamos como funcionaba.
 * Ahora, Solo Dios lo sabe!!!
 *
 * Asi que, si esta tratando de 'optimizar' esta rutina y fracasa (seguramente),
 * por favor, incremente el siguiente contador como una advertencia para el
 * siguiente colega:
 *
 * totalHorasPerdidasAqui = 0
 *
 */
/**
 * Clase abstracta que aglutina las funciones que trabajan sobre los string.
 *
 * @author iberlot
 *
 */
abstract class FuncionesString
{

	/**
	 * Conjunto de caracteres latinos.
	 * Normalmente utilizado para funciones de limpieza y saneamiento.
	 *
	 * @var array
	 */
	private static $carateres_latinos = array (
			'¡',
			'¢',
			'£',
			'¤',
			'¥',
			'§',
			'¨',
			'©',
			'ª',
			'«',
			'¬',
			'®',
			'¯',
			'°',
			'±',
			'´',
			'µ',
			'¶',
			'·',
			'¸',
			'º',
			'»',
			'¿',
			'À',
			'Á',
			'Â',
			'Ã',
			'Ä',
			'Å',
			'Æ',
			'Ç',
			'È',
			'É',
			'Ê',
			'Ë',
			'Ì',
			'Í',
			'Î',
			'Ï',
			'Ñ',
			'Ò',
			'Ó',
			'Ô',
			'Õ',
			'Ö',
			'Ø',
			'Ù',
			'Ú',
			'Û',
			'Ü',
			'ß',
			'à',
			'á',
			'â',
			'ã',
			'ä',
			'å',
			'æ',
			'ç',
			'è',
			'é',
			'ê',
			'ë',
			'ì',
			'í',
			'î',
			'ï',
			'ñ',
			'ò',
			'ó',
			'ô',
			'õ',
			'ö',
			'÷',
			'ø',
			'ù',
			'ú',
			'û',
			'ü',
			'ÿ',
			'?'
	);

	/**
	 * Listado de los caracteres latinos que incluye los distintos codigos para evitar que se rompan y su definicion.
	 *
	 * @var array
	 */
	private static $caracteresLatinosAmpliado = array (
			array (
					'¡',
					'00A1',
					'161',
					'&iexcl;',
					'exclamación de apertura'
			),
			array (
					'¢',
					'00A2',
					'162',
					'&cent;',
					'signo de centavo'
			),
			array (
					'£',
					'00A3',
					'163',
					'&pound;',
					'signo de libra'
			),
			array (
					'¤',
					'00A4',
					'164',
					'&curren;',
					'signo internacional de moneda'
			),
			array (
					'¥',
					'00A5',
					'165',
					'&yen;',
					'signo de yen'
			),
			array (
					'§',
					'00A7',
					'167',
					'&sect;',
					'signo de sección'
			),
			array (
					'¨',
					'00A8',
					'168',
					'&uml;',
					'diéresis'
			),
			array (
					'©',
					'00A9',
					'169',
					'&copy;',
					'signo de copyright'
			),
			array (
					'ª',
					'00AA',
					'170',
					'&ordf;',
					'indicador ordinal femenino'
			),
			array (
					'«',
					'00AB',
					'171',
					'&laquo;',
					'comillas anguladas de apertura'
			),
			array (
					'¬',
					'00AC',
					'172',
					'&not;',
					'signo de negación lógica'
			),
			array (
					'®',
					'00AE',
					'174',
					'&reg;',
					'signo de marca registrada'
			),
			array (
					'¯',
					'00AF',
					'175',
					'&macr;',
					'raya alta'
			),
			array (
					'°',
					'00B0',
					'176',
					'&deg;',
					'signo de grado'
			),
			array (
					'±',
					'00B1',
					'177',
					'&plusmn;',
					'signo de más/menos'
			),
			array (
					'´',
					'00B4',
					'180',
					'&acute;',
					'acento agudo'
			),
			array (
					'µ',
					'00B5',
					'181',
					'&micro;',
					'signo de micro'
			),
			array (
					'¶',
					'00B6',
					'182',
					'&para;',
					'signo de fin de parágrafo'
			),
			array (
					'·',
					'00B7',
					'183',
					'&middot;',
					'punto medio (coma georgiana)'
			),
			array (
					'¸',
					'00B8',
					'184',
					'&cedil;',
					'cedilla'
			),
			array (
					'º',
					'00BA',
					'186',
					'&ordm;',
					'indicador ordinal masculino'
			),
			array (
					'»',
					'00BB',
					'187',
					'&raquo;',
					'comillas anguladas de cierre'
			),
			array (
					'¿',
					'00BF',
					'191',
					'&iquest;',
					'signo de interrogación de apertura'
			),
			array (
					'À',
					'00C0',
					'192',
					'&Agrave;',
					'A con acento grave'
			),
			array (
					'Á',
					'00C1',
					'193',
					'&Aacute;',
					'A con acento agudo'
			),
			array (
					'Â',
					'00C2',
					'194',
					'&Acirc;',
					'A con acento circunflejo'
			),
			array (
					'Ã',
					'00C3',
					'195',
					'&Atilde;',
					'A con tilde'
			),
			array (
					'Ä',
					'00C4',
					'196',
					'&Auml;',
					'A con diéresis'
			),
			array (
					'Å',
					'00C5',
					'197',
					'&Aring;',
					'A con anillo'
			),
			array (
					'Æ',
					'00C6',
					'198',
					'&AElig;',
					'Ligadura AE'
			),
			array (
					'Ç',
					'00C7',
					'199',
					'&Ccedil;',
					'C cedilla'
			),
			array (
					'È',
					'00C8',
					'200',
					'&Egrave;',
					'E con acento grave'
			),
			array (
					'É',
					'00C9',
					'201',
					'&Eacute;',
					'E con acento agudo'
			),
			array (
					'Ê',
					'00CA',
					'202',
					'&Ecirc;',
					'E con acento circunflejo'
			),
			array (
					'Ë',
					'00CB',
					'203',
					'&Euml;',
					'E con diéresis'
			),
			array (
					'Ì',
					'00CC',
					'204',
					'&Igrave;',
					'I con acento grave'
			),
			array (
					'Í',
					'00CD',
					'205',
					'&Iacute;',
					'I con acento agudo'
			),
			array (
					'Î',
					'00CE',
					'206',
					'&Icirc;',
					'I con acento circunflejo'
			),
			array (
					'Ï',
					'00CF',
					'207',
					'&Iuml;',
					'I con diéresis'
			),
			array (
					'Ñ',
					'00D1',
					'209',
					'&Ntilde;',
					'N con tilde'
			),
			array (
					'Ò',
					'00D2',
					'210',
					'&Ograve;',
					'O con acento grave'
			),
			array (
					'Ó',
					'00D3',
					'211',
					'&Oacute;',
					'O con acento agudo'
			),
			array (
					'Ô',
					'00D4',
					'212',
					'&Ocirc;',
					'O con acento circunflejo'
			),
			array (
					'Õ',
					'00D5',
					'213',
					'&Otilde;',
					'O con tilde'
			),
			array (
					'Ö',
					'00D6',
					'214',
					'&Ouml;',
					'O con diéresis'
			),
			array (
					'Ø',
					'00D8',
					'216',
					'&Oslash;',
					'O con barra'
			),
			array (
					'Ù',
					'00D9',
					'217',
					'&Ugrave;',
					'U con acento grave'
			),
			array (
					'Ú',
					'00DA',
					'218',
					'&Uacute;',
					'U con acento agudo'
			),
			array (
					'Û',
					'00DB',
					'219',
					'&Ucirc;',
					'U con acento circunflejo'
			),
			array (
					'Ü',
					'00DC',
					'220',
					'&Uuml;',
					'U con diéresis'
			),
			array (
					'ß',
					'00DF',
					'223',
					'&szlig;',
					'doble s (alemán)'
			),
			array (
					'à',
					'0',
					'224',
					'&agrave;',
					'a con acento grave'
			),
			array (
					'á',
					'0',
					'225',
					'&aacute;',
					'a con acento agudo'
			),
			array (
					'â',
					'0',
					'226',
					'&acirc;',
					'a con acento circunflejo'
			),
			array (
					'ã',
					'0',
					'227',
					'&atilde;',
					'a con tilde'
			),
			array (
					'ä',
					'0',
					'228',
					'&auml;',
					'a con diéresis'
			),
			array (
					'å',
					'0',
					'229',
					'&aring;',
					'a con anillo'
			),
			array (
					'æ',
					'0',
					'230',
					'&aelig;',
					'diptongo (ligadura) ae'
			),
			array (
					'ç',
					'0',
					'231',
					'&ccedil;',
					'c cedilla'
			),
			array (
					'è',
					'0',
					'232',
					'&egrave;',
					'e con acento grave'
			),
			array (
					'é',
					'0',
					'233',
					'&eacute;',
					'e con acento agudo i co'
			),
			array (
					'ê',
					'00EA',
					'234',
					'&ecirc;',
					'e con acento circunflejo'
			),
			array (
					'ë',
					'00EB',
					'235',
					'&euml;',
					'e con diéresis'
			),
			array (
					'ì',
					'00EC',
					'236',
					'&igrave;',
					'i con acento grave'
			),
			array (
					'í',
					'00ED',
					'237',
					'&iacute;',
					'i con acento agudo'
			),
			array (
					'î',
					'00EE',
					'238',
					'&icirc;',
					'i con acento circunflejo'
			),
			array (
					'ï',
					'00EF',
					'239',
					'&iuml;',
					'i con diéresis'
			),
			array (
					'ñ',
					'00F1',
					'241',
					'&ntilde;',
					'n con tilde'
			),
			array (
					'ò',
					'00F2',
					'242',
					'&ograve;',
					'o con acento grave'
			),
			array (
					'ó',
					'00F3',
					'243',
					'&oacute;',
					'o con acento agudo'
			),
			array (
					'ô',
					'00F4',
					'244',
					'&ocirc;',
					'o con acento circunflejo'
			),
			array (
					'õ',
					'00F5',
					'245',
					'&otilde;',
					'o con tilde'
			),
			array (
					'ö',
					'00F6',
					'246',
					'&ouml;',
					'o con diéresis'
			),
			array (
					'÷',
					'00F7',
					'247',
					'&divide;',
					'signo de división'
			),
			array (
					'ø',
					'00F8',
					'248',
					'&oslash;',
					'o con barra'
			),
			array (
					'ù',
					'00F9',
					'249',
					'&ugrave;',
					'u con acento grave'
			),
			array (
					'ú',
					'00FA',
					'250',
					'&uacute;',
					'u con acento agudo'
			),
			array (
					'û',
					'00FB',
					'251',
					'&ucirc;',
					'u con acento circunflejo'
			),
			array (
					'ü',
					'00FC',
					'252',
					'&uuml;',
					'u con diéresis'
			),
			array (
					'ÿ',
					'00FF',
					'255',
					'&yuml;',
					'y con diéresis'
			),
			array (
					'?',
					'20AB',
					'20AB',
					'&dong;',
					'dong'
			)
	);

	/**
	 * Convierte de un array todas las entidades HTML para que sea seguro mostrar en pantalla strings ingresados por los usuarios.
	 *
	 * @example $_REQUEST = limpiarEntidadesHTML($_REQUEST, $config);
	 *
	 * @param string[] $param
	 *        	- datos de lo cuales limpiarl las entidades html.
	 * @return array|string - Depende del parametro recibido, un array con los datos remplazados o un String
	 */
	public function limpiarEntidadesHTML($param, $charset)
	{
		return is_array ($param) ? array_map ('limpiarEntidadesHTML', $param) : htmlentities ($param, ENT_QUOTES, $charset);
	}

	/**
	 * Reemplaza todos los acentos por sus equivalentes sin ellos.
	 * Ademas elimina cualquier caracter extrano en el string.
	 *
	 * @param string $string
	 *        	- la cadena a sanear
	 *
	 * @return string $string - saneada
	 */
	public function sanear_string($string)
	{
		$string = trim ($string);

		$string = str_replace (array (
				'à',
				'á',
				'â',
				'ã',
				'ä',
				'À',
				'Á',
				'Â',
				'Ã',
				'Ä'
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
				'è',
				'é',
				'ê',
				'ë',
				'È',
				'É',
				'Ê',
				'Ë'
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
				'ì',
				'í',
				'î',
				'ï',
				'Ì',
				'Í',
				'Î',
				'Ï'
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
				'ò',
				'ó',
				'ô',
				'ö',
				'Ò',
				'Ó',
				'Ô',
				'Ö'
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
				'ù',
				'ú',
				'û',
				'ü',
				'Ù',
				'Ú',
				'Û',
				'Ü'
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
				'ñ',
				'Ñ',
				'ç',
				'Ç'
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
	 * @name convertir_especiales_html
	 *
	 * @version 1.2 - Se remplazan las variables globales por variables de la clase.
	 *
	 * @todo esta funcion usa la propiedad de la clase 'carateres_latinos' si no llegara a estar definida lo hace.
	 *
	 * @param string $str
	 *        	- Texto en el cual remplazar los caracteres especiales.
	 *
	 * @return string - Texto con los caracteres remplazados.
	 */
	public function convertir_especiales_html($str)
	{
		if (!isset ($this->carateres_latinos) or (count ($this->carateres_latinos) == 0))
		{
			$todas = get_html_translation_table (HTML_ENTITIES, ENT_NOQUOTES);
			$etiquetas = get_html_translation_table (HTML_SPECIALCHARS, ENT_NOQUOTES);

			$this->carateres_latinos = array_diff ($todas, $etiquetas);
		}
		$str = strtr ($str, $this->carateres_latinos);

		return $str;
	}

	/**
	 * Alimina cualquier caracter que no sea de la A a la z o numero.
	 *
	 * @param string $texto
	 * @return string
	 */
	public function limpiarString($texto)
	{
		$textoLimpio = preg_replace ('([^A-Za-z0-9])', '', $texto);
		return $textoLimpio;
	}

	/**
	 * Devuelve el tipo de codificacion de los caracteres usados en un string
	 *
	 * @param string $texto
	 * @return string
	 */
	public function codificacion($texto)
	{
		$c = 0;
		$ascii = true;
		for($i = 0; $i < strlen ($texto); $i++)
		{
			$byte = ord ($texto[$i]);
			if ($c > 0)
			{
				if (($byte >> 6) != 0x2)
				{
					return ISO_8859_1;
				}
				else
				{
					$c--;
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

	/**
	 * Codifica un strin en UTF8
	 *
	 * Primero comprueba que este no este ya en utf8 para no romper los caracteres
	 *
	 * @param string $texto
	 * @return string
	 */
	public function utf8_encode_seguro($texto)
	{
		return (codificacion ($texto) == ISO_8859_1) ? utf8_encode ($texto) : $texto;
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
	 * Funcion para sanear los valores recibidos del formulario.
	 * Evita la inyeccion de SQL. Elimina cualquier caracter no numerico.
	 *
	 * @param string $str
	 */
	public function clean($str)
	{
		$str = trim ($str);
		if (get_magic_quotes_gpc ())
		{
			$str = stripslashes ($str);
		}
		// return mysql_real_escape_string($str);
		return ($str);
	}

	/**
	 * Remplaza caracteres latinos.
	 *
	 * @param string $str
	 * @return string
	 * @author Andres Carizza
	 * @version 1.2
	 *
	 */
	public function remplazar_caracteres_latinos($str)
	{
		// Ej: � -> a, � -> n
		$find = array (
				'á',
				'é',
				'í',
				'ó',
				'ú',
				'ñ',
				'ü',
				'ç',
				'à',
				'è',
				'â',
				'Á',
				'É',
				'Í',
				'Ó',
				'Ú',
				'Ñ',
				'Ü',
				'Ç',
				'À',
				'È',
				'Ä'
		);
		$repl = array (
				'a',
				'e',
				'i',
				'o',
				'u',
				'n',
				'u',
				'c',
				'a',
				'e',
				'a',
				'A',
				'E',
				'I',
				'O',
				'U',
				'N',
				'U',
				'C',
				'A',
				'E',
				'A'
		);
		return str_replace ($find, $repl, $str);
	}

	/**
	 * Remove Invisible Characters
	 *
	 * This prevents sandwiching null characters
	 * between ascii characters, like Java\0script.
	 *
	 * @access public
	 * @param
	 *        	string
	 * @return string
	 */
	function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array ();

		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)

		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/'; // url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/'; // url encoded 16-31
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S'; // 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace ($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}

	/**
	 * Lo mismo que utf8_encode() pero aplicado a todo el array
	 *
	 * @param array $array
	 * @return array
	 */
	public function utf8_encode_array($array)
	{
		return is_array ($array) ? array_map ('utf8_encode_array', $array) : utf8_encode ($array);
	}

	/**
	 * Lo mismo que utf8_decode() pero aplicado a todo el array
	 *
	 * @param array $array
	 * @return array
	 */
	public function utf8_decode_array($array)
	{
		return is_array ($array) ? array_map ('utf8_decode_array', $array) : utf8_decode ($array);
	}
}