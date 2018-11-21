<?php

/**
 * Archivo principal de la clase Generica.
 *
 * @name class_sitio.php
 * @author iberlot <@> iberlot@usal.edu.ar
 *
 */

/**
 * Clase generica que aglutina las variables basicas de configuracion y un conjunto de funciones utiles para cualquier desarrollo.
 *
 * @name class_sitio
 * @author iberlot <@> iberlot@usal.edu.ar
 * @author Andres Carizza
 *
 * @version 1.0.6 - Se eliminan la mayoria de las variables de secion remplazadas por propiedades de la clase.
 * @version 1.0.5 - Se agrega la suma de funciones comunes y genericas que se pueden llegar a utilizar en cualquier sitio.
 * @version 1.0.5 - Correcciones de codigo optimizacion y comnetado.
 *
 */
class class_sitio
{
	/**
	 * titulo o nombre del sitio
	 *
	 * @var string
	 */
	public $nombre;

	/**
	 * url del sitio
	 *
	 * @var string
	 */
	public $url;

	/**
	 * url del sitio corta para otros propositos.
	 * Ej: MiSitio.com
	 *
	 * @var string
	 */
	public $urlCorta;

	/**
	 * El path completo del sitio
	 *
	 * @var string
	 */
	public $pathBase;

	/**
	 * email de donde salen los envios para los usuarios
	 *
	 * @var string
	 */
	public $emailEnvios;

	/**
	 * from del email de donde salen los envios para los usuarios
	 *
	 * @var string
	 */
	public $emailEnviosFrom;

	/**
	 * email del webmaster
	 *
	 * @var string
	 */
	public $emailWebmaster;

	/**
	 * codigo de idioma por defecto del sitio
	 *
	 * @var string
	 */
	public $idiomaPorDefecto = "es";

	/**
	 * idioma actualmente seleccionado
	 *
	 * @var string
	 */
	public $idioma = "es";

	/**
	 * extension que agrega a las url SEO amigables
	 */
	public $extension;

	/**
	 * Juego de caracteres del sitio
	 *
	 * @var string
	 */
	public $charset;

	/**
	 * Ip o nombre del servidor al que se va a conectar la base de datos.
	 *
	 * @var string
	 */
	public $dbSever;

	/**
	 * Puerto de coneccion a la DB.
	 *
	 * @var int
	 */
	public $dbPort;
	public $dbDSN;

	/**
	 * Usuario de conexion a la base
	 *
	 * @var string
	 */
	public $dbUser;

	/**
	 * Contrase�a de conexion a la base
	 *
	 * @var string
	 */
	public $dbPass;

	/**
	 * Base a la cual conectarse
	 *
	 * @var string
	 */
	public $dbBase;

	/**
	 * Juego de caracteres de la conexion
	 *
	 * @var string
	 */
	public $dbCharset;

	/**
	 * El tipo de DB (mysql, oracle o mssql)
	 *
	 * @var string
	 */
	public $dbTipo;

	/**
	 * Usar die() si hay un error.
	 * Esto es util para etapa de desarrollo *
	 *
	 * @var boolean
	 */
	public $dieOnError = false;

	/**
	 * Muestra por pantalla diferentes codigos para facilitar el debug
	 *
	 * @var boolean
	 */
	public $debug = false;

	/**
	 * Habilita la muestra de mensajes de error.
	 *
	 * @var boolean
	 */
	public $mostrarErrores = false;

	/**
	 * Aca se puede asignar un email para enviar aviso cuando hay errores sql *
	 */
	public $emailAvisoErrorSql;

	/**
	 * Graba log con todas las consultas realizadas (solo usar en casos puntuales para debugear) *
	 */
	public $grabarArchivoLogQuery = false;

	/**
	 * Graba log con los errores de BD *
	 *
	 * @var boolean
	 */
	public $grabarArchivoLogError = false;

	/**
	 * Conjunto de caracteres latinos.
	 * Normalmente utilizado para funciones de limpieza y saneamiento.
	 *
	 * @var array
	 */
	public $carateres_latinos = array ();

	/**
	 * Titulo para usar en las notificaciones.
	 *
	 * Es la redefinicion de $_SESSION['_sitio_notTit']
	 *
	 * @var string
	 */
	private $sitio_notTit = "";

	/**
	 * Mensaje a mostrar en las notificaciones.
	 *
	 * Es la redefinicion de $_SESSION['_sitio_notMsg']
	 *
	 * @var string
	 */
	private $sitio_notMsg = "";

	/**
	 * Cantidad de segundos para que aparezcan las notificaciones.
	 *
	 * Es la redefinicion de $_SESSION['_sitio_notSeg']
	 *
	 * @var integer - Por defecto tiene el valor de 5
	 */
	private $sitio_notSeg = 5;

	/**
	 * Mensaje a mostrar en los mensajes.
	 *
	 * Es la redefinicion de $_SESSION['_sitio_msg']
	 *
	 * @var string
	 */
	private $sitio_msg = "";

	/**
	 * Clase de mensaje a mostrar, puede tomar loa valorea Class para el mensaje info, tip, error, atencion
	 *
	 * Es la redefinicion de $_SESSION['_sitio_msgClass']
	 *
	 * @var string - por defecto tiene el valor info
	 */
	private $sitio_msgClass = "info";

	/**
	 * Define si mostrar una sola vez el mensaje
	 *
	 * Es la redefinicion de $_SESSION['_sitio_msgMostrarUnaSolaVez']
	 *
	 * @var boolean - por defecto tiene el valor true
	 */
	private $sitio_msgMostrarUnaSolaVez = true;

	/*
	 * **********************************************
	 * FUNCIONES
	 * **********************************************
	 */
	/**
	 * Esto es para setear un mensaje que sera mostrado en las paginas con la funcion showMsg().
	 * Ejemplo, para notificaciones de acciones realizadas
	 *
	 * @param string $msg
	 * @param string $class
	 *        	Class para el mensaje (info, tip, error, atencion )
	 * @param string $mostrarUnaSolaVez
	 */
	public function setMsg($msg, $class = 'info', $mostrarUnaSolaVez = true)
	{
		$this->sitio_sitio_msg = $msg;
		$this->sitio_msgClass = $class;
		$this->sitio_msgMostrarUnaSolaVez = $mostrarUnaSolaVez;
	}

	/**
	 * Imprime, si es que hay, un mensaje asignado por setMsg()
	 */
	public function showMsg()
	{
		if ($this->sitio_sitio_msg != '')
		{
			echo "<div class='" . $this->sitio_msgClass . "'>" . $this->sitio_sitio_msg . "</div>";

			if ($this->sitio_msgMostrarUnaSolaVez)
			{
				unset ($this->sitio_sitio_msg);
				unset ($this->sitio_msgClass);
				unset ($this->sitio_msgMostrarUnaSolaVez);
			}
		}
	}

	/**
	 * Esto es para setear una notificacion emergente que sera mostrado en las paginas con la funcion showNotif().
	 * Ejemplo, para notificaciones de acciones realizadas
	 *
	 * @param string $titulo
	 * @param string $msg
	 * @param number $segundos
	 */
	public function setNotif($titulo, $msg, $segundos = 5)
	{
		$this->sitio_notTit = $titulo;
		$this->sitio_notMsg = $msg;
		$this->sitio_notSeg = $segundos;
	}

	/**
	 * Imprime, si es que hay, un mensaje asignado por setMsg()
	 */
	public function showNotif()
	{
		if ($this->sitio_notTit != '')
		{
			?>
			<script type="text/javascript">
				$(function(){
					$.gritter.add({
						title: '<?=$this->sitio_notTit?>',
						text: '<?=$this->sitio_notMsg?>',
						time: <?=($this->sitio_notSeg * 1000)?>
					});
				});
			</script>
			<?php
			unset ($this->sitio_notTit);
			unset ($this->sitio_notMsg);
			unset ($this->sitio_notSeg);
		}
	}

	/**
	 * Lee de la BD una configuracion del sitio
	 *
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @param string $parametro
	 * @param string $valorPorDefecto
	 * @return string
	 */
	public function getConfig($db, $parametro, $valorPorDefecto = "")
	{
		$valor = $db->getValue ("config", "valor", $parametro, "parametro");

		if ($valor === false)
		{
			return $valorPorDefecto;
		}
		else
		{
			return $valor;
		}
	}

	/**
	 * Formatea y retorna la url agregando el path del sitio y la extension, para usar en los formularios, links del html del sitio
	 *
	 * @param string $url
	 * @param boolean $agregarExtension
	 *        	agrega la extension de archivo
	 * @param array $arrQS
	 *        	array de variables del query string ej: array("nombre"=>"juan")
	 * @return string
	 */
	public function link($url, $agregarExtension = true, $arrQS = "")
	{
		if (is_array ($arrQS))
		{
			$qs = "?" . http_build_query ($arrQS);
		}
		if ($agregarExtension)
		{
			return $this->pathBase . $url . $this->extension . $qs;
		}
		else
		{
			return $this->pathBase . $url . $qs;
		}
	}

	/**
	 * Convierte de un array todas las entidades HTML para que sea seguro mostrar en pantalla strings ingresados por los usuarios.
	 *
	 * @example $_REQUEST = limpiarEntidadesHTML($_REQUEST, $config);
	 *
	 * @param string[] $param
	 *        	- datos de lo cuales limpiarl las entidades html.
	 * @return array|string - Depende del parametro recibido, un array con los datos remplazados o un String
	 */
	public function limpiarEntidadesHTML($param)
	{
		return is_array ($param) ? array_map ('limpiarEntidadesHTML', $param) : htmlentities ($param, ENT_QUOTES, $this->charset);
	}

	/**
	 * Comprueba que la direccion de mail no tenga caracteres extranos.
	 *
	 * @param string $str
	 *        	- email a verificar
	 * @return bool - Devuelve true o false dependiendo de si es o no un mail valido.
	 */
	public function validarEmail($str)
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
	public function limpiarParaSql($param, $db)
	{
		// global $db;
		return is_array ($param) ? array_map ('limpiarParaSql', $param) : mysqli_real_escape_string ($db->con, $param);
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
	 * Retorna true si el valor es un numero.
	 * A diferencia de las funciones de PHP esta solo va a retornar TRUE si en el valor hay unicamente numeros.
	 *
	 * @name es_numerico
	 *
	 * @param mixed $valor
	 *        	- Dato que queremos comprobar
	 * @return boolean
	 */
	public function es_numerico($valor)
	{
		if ($valor != "" and ereg ("^[0-9]+$", $valor))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Funcion para sanear los valores recibidos del formulario.
	 * Evita la inyeccion de SQL. Elimina cualquier caracter no numerico.
	 *
	 * @param string $str
	 * @return int
	 */
	public function clean_numeric($str)
	{
		$str = $this->clean ($str);
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
	public function quitar($mensaje, $nopermitidos = "")
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
	public function removeNulls($string)
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
	 * @return string - String con los option de un campo select html basandose en una consulta a la DB.
	 */
	public function generarInputSelect($db, $tabla, $campoSelec, $campoTexto = NULL, $seleccionado = NULL, $textoMayuscula = true, $mostrarValor = false)
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

		$sql = "SELECT " . $campos . " FROM " . $tabla;

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

	/*
	 * ****************************************************************************
	 * Funciones de Fechas
	 * ****************************************************************************
	 */

	/**
	 * Formatea la fecha de la forma dd/mm/yyyy
	 *
	 * @param string $fecha_inicio
	 * @return string
	 */
	public function fecha_DD_MM_YYYY_Oracle($fecha_inicio)
	{
		$fecha_inicio = str_replace ('-', '', $fecha_inicio);
		$fecha_inicio = str_replace ('/', '', $fecha_inicio);
		// $dato_post_fecha_recibo_usal = preg_replace('([^0-9])', '', $dato_post_valorcuota);
		$dd = substr ($fecha_inicio, -2);
		$mm = substr ($fecha_inicio, 4, 2);
		$yyyy = substr ($fecha_inicio, 0, 4);

		if ($fecha_inicio)
		{
			$fecha_inicio = $dd . "/" . $mm . "/" . $yyyy;
		}
		return $fecha_inicio;
	}

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
	public function formatear_fecha_Oracle($fecha_inicio, $separador = "/")
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

	function fecha_oracle($fecha)
	{
		$fecha = formatear_fecha_Oracle ($fecha);

		$fecha = "TO_DATE('$fecha', 'DD-MM-YYYY HH24:MI:SS')";

		return $fecha;
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
	public function invertirFecha($fecha)
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
	public function nombreDiacorto($fecha)
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
	 * Pasandosele un numero devielve el nombre del mes que le corresponde.
	 *
	 * @param int $numMes
	 * @return string
	 */
	public function getNombreMes($numMes)
	{
		switch ($numMes)
		{
			case 1 :
				return "Enero";
				break;
			case 2 :
				return "Febrero";
				break;
			case 3 :
				return "Marzo";
				break;
			case 4 :
				return "Abril";
				break;
			case 5 :
				return "Mayo";
				break;
			case 6 :
				return "Junio";
				break;
			case 7 :
				return "Julio";
				break;
			case 8 :
				return "Agosto";
				break;
			case 9 :
				return "Septiembre";
				break;
			case 10 :
				return "Octubre";
				break;
			case 11 :
				return "Noviembre";
				break;
			case 12 :
				return "Diciembre";
				break;

			default :
				break;
		}
	}

	/**
	 * Suma una cantidad X de dias a una fecha.
	 *
	 * @param string $fecha
	 *        	- fecha con el formato ano-mes-dia.
	 * @param int $dia
	 *        	- numero de dias a sumar.
	 * @return string - fecha con los dias sumados.
	 */
	public function sumaDia($fecha, $dia)
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
	public function diferenciaDias($fecha2, $fecha1)
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
	public function fechaCorrecta($d, $m, $a)
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
	public function calcularMminutosExcedentes($hora1, $hora2)
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
	public function difHoras($inicio, $fin)
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
	public function sumaHoras($hora1, $hora2)
	{
		$hora1 = strtotime ($hora1);
		$hora2 = strtotime ($hora2);
		$horaSum = $hora2 + $hora1;
		$sum = date ("H:i", strtotime ("00:00") + $horaSum);

		return $sum;
	}

	/**
	 * Resibe un int con la cantidad de meses y retorna un string con la cantidad de años y meses.
	 *
	 * @param int $meses
	 *        	Cantidad de meses
	 * @return string - XxXx años y XxXx meses.
	 */
	public function mesesAnios($meses)
	{
		$restoMeses = $meses % 12;
		$anios = ($meses - $restoMeses) / 12;

		return $anios . " a&ntilde;os" . (($restoMeses > 0) ? " y " . $restoMeses . " meses." : ".");
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
	 * Copia el contenido de un directorio a otro del servidor.
	 *
	 * @param string $inPath
	 *        	Directorio a copiar
	 * @param string $outPath
	 *        	Directorio de destino.
	 *
	 * @throws Exception Devuelve un mensaje de error en caso de no poder realizar la copia.
	 * @return string
	 */
	public function save_image($inPath, $outPath)
	{
		// Download images from remote server
		$in = fopen ($inPath, "rb");
		$out = fopen ($outPath, "wb");

		while ($chunk = fread ($in, 8192))
		{
			if (!fwrite ($out, $chunk, 8192))
			{
				throw new Exception ('ERROR: No se pudo grabar el archivo.');
			}
		}
		fclose ($in);
		fclose ($out);
	}

	/**
	 * inserta un script de redireccione a Mantenimiento.php
	 */
	public function mantenimiento()
	{
		echo '<script language="javascript" type="text/javascript">
	window.location.href="Mantenimiento.php?backurl="+window.location.href;
					</script>';

		exit ();
	}

	// *****************************************************************************************************************//
	// FUNCIONES DE TEXTO
	// *****************************************************************************************************************//

	/**
	 * Devuelve los bytes en un gormato leible para los homans (Bits, Kilos, Megas, Gigas, Teras, Peta)
	 *
	 * @param number $bytes
	 *        	valos a convertir
	 * @param number $decimals
	 *        	cantidad de decimales a mostrar
	 *
	 * @return string
	 */
	public function human_filesize($bytes, $decimals = 2)
	{
		$sz = 'BKMGTP';
		$factor = floor ((strlen ($bytes) - 1) / 3);

		return sprintf ("%.{$decimals}f", $bytes / pow (1024, $factor)) . @$sz[$factor];
	}

	/**
	 * Obtiene el ID de video de una url de Youtube
	 *
	 * @param string $youtubeVideoLink
	 * @return string
	 */
	public function getYoutubeVideoId($youtubeVideoLink)
	{
		if (stripos ($youtubeVideoLink, "youtube") > 0)
		{
			$url = parse_url ($youtubeVideoLink);
			$queryUrl = $url['query'];
			$queryUrl = parse_str ($queryUrl);

			// FIXME hy que revisarlo porque creo que esto no es correcto
			return $v;
		}
		elseif (stripos ($youtubeVideoLink, "youtu.be") > 0)
		{
			preg_match ('/youtu.be\/([a-zA-Z0-9]*)/i', $youtubeVideoLink, $matches);
			return $matches[1];
		}
	}

	/**
	 * Encripta un texto.
	 *
	 * @param string $value
	 *        	- Dato a encriptar.
	 * @param string $key
	 *        	- Clave de la encriptacion
	 * @return string
	 */
	public function encryptData($value, $key)
	{
		$iv_size = mcrypt_get_iv_size (MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv ($iv_size, MCRYPT_RAND);
		$crypttext = mcrypt_encrypt (MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, $iv);

		return $crypttext;
	}

	/**
	 * Des-encripta un texto.
	 *
	 * @param string $value
	 *        	- Dato a Des-encriptar.
	 * @param string $key
	 *        	- Clave de la encriptacion
	 * @return string
	 */
	public function decryptData($value, $key)
	{
		$iv_size = mcrypt_get_iv_size (MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv ($iv_size, MCRYPT_RAND);
		$decrypttext = mcrypt_decrypt (MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, $iv);
		return trim ($decrypttext);
	}

	/**
	 * Agrega el prefijo http:// a un string url si es que no lo tiene
	 *
	 * @param string $string
	 *        	- Texto al que agregarle el prefijo http
	 * @return string
	 */
	public function agregarHTTP($string)
	{
		if (!preg_match ('/^(https?:\/\/)/i', $string))
		{
			$string = 'http://' . $string;
		}
		return $string;
	}

	/**
	 * Remplaza links y emails agregandole los links
	 *
	 * @param string $str
	 * @param string $target
	 *
	 * @return string
	 */
	public function agregarLinksEmail($str, $target = "_blank")
	{
		$str = preg_replace ('/([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})/i', '<a href="mailto:\\1">\\1</a>', $str);
		return $str;
	}

	/**
	 * Formatear un texto para remplazar URLs por links, tambi�n para remplazar URLs largas por
	 * sus versi�nes resumidas solo para fines est�ticos Ej: http://www.somesite.com/with/a/really/long/url/link por http://www.some...url/link
	 *
	 * @param string $str
	 *        	texto donde hay que remplazar los links
	 * @param string $target
	 *        	En que ventana se va a abrir el link.
	 *        	- Por defecto _blank
	 * @param int $maxLen
	 *        	largo m�ximo
	 * @param string $mid
	 *        	Caracteres ... de "continuacion"
	 * @return string
	 */
	public function formatearLinks($str, $target = '_blank', $maxLen = 50, $mid = '...')
	{
		$left = ceil (0.6666 * $maxLen);
		$right = $maxLen - $left;
		preg_match_all ('/(?<!=|\]|\/)((https?|ftps?|irc):\/\/|' . '(www([0-9]{1,3})?|ftp)\.)([0-9a-z-]{1,25}' . '[0-9a-z]{1}\.)([^\s&\[\{\}\]]+)/ims', $str, $matches);
		foreach ($matches[0] as $key => $value)
		{
			$temp = $value;
			if (strlen ($value) > ($maxLen + strlen ($mid) + 2))
			{
				$value = substr ($value, 0, $left) . $mid . substr ($value, (-1 * $right));
			}
			$temp = !preg_match ('/:\/\//', $temp) ? (substr ($temp, 0, 3) === 'ftp' ? 'ftp://' . $temp : 'http://' . $temp) : $temp;
			$temp = $temp === $matches[0][$key] && $value === $matches[0][$key] ? '' : '=' . $temp;
			$str = str_replace ($matches[0][$key], '[url' . $temp . ']' . $value . '[/url]', $str);
		}
		$str = preg_replace ('/\[url=(?!http|ftp|irc)/ims', '[url=http://', $str);
		$str = preg_replace ('/\[url\](.+?)\[\/url\]/ims', '<a href="$1" target="' . $target . '" title="$1">$1</a>', $str);
		$str = preg_replace ('/\[url=(.+?)\](.+?)\[\/url\]/ims', '<a href="$1" target="' . $target . '" title="$1">$2</a>', $str);
		return $str;
	}

	/**
	 * formatMoney(1050); # 1,050
	 * formatMoney(1321435.4, true); # 1,321,435.40
	 * formatMoney(10059240.42941, true); # 10,059,240.43
	 * formatMoney(13245); # 13,245
	 */
	public function formatMoney($number, $fractional = false)
	{
		if ($fractional)
		{
			$number = sprintf ('%.2f', $number);
		}
		while (true)
		{
			$replaced = preg_replace ('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
			if ($replaced != $number)
			{
				$number = $replaced;
			}
			else
			{
				break;
			}
		}
		return $number;
	}

	/**
	 * Remplaza links y emails agregandole los links
	 */
	public function remplazarEmailyWWW($str, $target = "_blank", $maxLen = 50, $mid = '...')
	{
		$str = agregarLinksEmail ($str, $target);
		$str = formatearLinks ($str, $target, $maxLen, $mid);
		return $str;
	}

	/**
	 * Corta un string del largo de caracteres especificado sin cortar palabras a la mitad.
	 *
	 * @param
	 *        	$str
	 * @param Integer $len
	 * @param String $txt_continua
	 *        	Texto que se agrega al final del string si es cortado (Ej: Mi perro se llam(continua...) )
	 * @return String
	 */
	public function cortar_str($str, $len, $txt_continua = "...")
	{
		if (strlen ($str) > $len)
		{
			// Cortamos la cadena por los espacios
			$arrayTexto = split (' ', $str);

			$texto = '';
			$contador = 0;

			// Reconstruimos la cadena
			while ($len >= strlen ($texto) + strlen ($arrayTexto[$contador]))
			{
				$texto .= ' ' . $arrayTexto[$contador];
				$contador++;
			}

			return $texto . $txt_continua;
		}
		else
		{
			return $str;
		}
	}

	/**
	 * Saca los saltos de linea ASCII de un string
	 *
	 * @param string $str
	 * @return string
	 */
	public function strip_saltos($str)
	{
		// FIXME como se actualizo el ereg remplase hay que comprobar que funcione correctamente esta funcion
		$str = preg_replace (chr (13), "", $str);
		$str = preg_replace (chr (10), "", $str);
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
	public function url_amigable($str)
	{
		global $sitio; // de mi framework

		$url = mb_strtolower ($str, $sitio->charset);

		$url = remplazar_caracteres_latinos ($url);

		// A�adimos los guiones
		$find = array (
				' ',
				'&',
				'\r\n',
				'\n',
				'+'
		);
		$url = str_replace ($find, '-', $url);

		// Eliminamos y Reemplazamos dem�s caracteres especiales
		$find = array (
				'/[^a-z0-9\-<>]/',
				'/[\-]+/',
				'/<[^>]*>/'
		);
		$repl = array (
				'',
				'-',
				''
		);
		$url = preg_replace ($find, $repl, $url);

		return $url;
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
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�',
				'�'
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
	 * Formatea un string para que corresponda con un nombre v�lido de archivo
	 *
	 * @param string $str
	 * @return string
	 * @author Andres Carizza
	 * @version 1.1
	 */
	public function format_valid_filename($str, $remplazarCaracteresLatinos = true, $conservarEspacios = false)
	{
		// Eliminamos y Reemplazamos caracteres especiales
		$str = str_replace ('\\', '', $str);
		$str = str_replace ('/', '', $str);
		$str = str_replace ('*', '', $str);
		$str = str_replace (':', '', $str);
		$str = str_replace ('?', '', $str);
		$str = str_replace ('"', '', $str);
		$str = str_replace ('<', '', $str);
		$str = str_replace ('>', '', $str);
		$str = str_replace ('|', '', $str);

		if ($remplazarCaracteresLatinos)
			$str = remplazar_caracteres_latinos ($str);
		if ($conservarEspacios)
			$str = str_replace (" ", "-", $str);

		return $str;
	}

	/**
	 * Limpia un string para se usado en una busqueda
	 *
	 * @param string $valor
	 * @return string
	 */
	public function limpiarParaBusquedaSql($valor)
	{
		$valor = str_ireplace ("%", "", $valor);
		$valor = str_ireplace ("--", "", $valor);
		$valor = str_ireplace ("^", "", $valor);
		$valor = str_ireplace ("[", "", $valor);
		$valor = str_ireplace ("]", "", $valor);
		$valor = str_ireplace ("\\", "", $valor);
		$valor = str_ireplace ("!", "", $valor);
		$valor = str_ireplace ("�", "", $valor);
		$valor = str_ireplace ("?", "", $valor);
		$valor = str_ireplace ("=", "", $valor);
		$valor = str_ireplace ("&", "", $valor);
		$valor = str_ireplace ("'", "", $valor);
		$valor = str_ireplace ('"', "", $valor);
		return $valor;
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

	// *****************************************************************************************************************//
	// FUNCIONES DE FECHA
	// *****************************************************************************************************************//

	/**
	 * Formatea la fecha que usa el MySQL (YYYY-MM-DD) o (YYYY-MM-DD HH:MM:SS) a un formato de fecha m�s claro
	 * En caso de que falle el formateo retorna FALSE
	 *
	 * @param String $mysqldate
	 *        	La fecha en formato YYYY-MM-DD o YYYY-MM-DD HH:MM:SS
	 * @param Boolean $conHora
	 *        	True si se quiere dejar la hora o false si se quiere quitar
	 * @return String La fecha formateada
	 * @version 1.1
	 *
	 */
	public function mysql2date($mysqldate, $conHora = false)
	{
		$fecha_orig = $mysqldate;

		if (strlen ($fecha_orig) > 10)
		{ // si es formato YYYY-MM-DD HH:MM:SS
			$hora = substr ($mysqldate, 11, strlen ($mysqldate));
			$mysqldate = substr ($mysqldate, 0, 10);
		}

		$datearray = explode ("-", $mysqldate);

		if (count ($datearray) != 3)
			return ""; // en caso de que no sean tres bloques de numeros falla

		$yyyy = $datearray[0];

		$mm = $datearray[1];

		$dd = $datearray[2];

		if (strlen ($fecha_orig) > 10 and $conHora)
		{ // si es formato YYYY-MM-DD HH:MM:SS
			return "$dd/$mm/$yyyy $hora";
		}
		else
		{
			return "$dd/$mm/$yyyy";
		}
	}

	/**
	 * Convierte el formato de fecha (DD/MM/YYYY) al que usa el MySQL (YYYY-MM-DD)
	 * Se pueden enviar dias y meses con un digito (ej: 3/2/1851) o as� (ej: 03/02/1851)
	 * La fecha tiene que enviarse en el orden dia/mes/ano
	 * En caso de que falle el formateo retorna FALSE
	 *
	 * @param String $date
	 *        	La fecha en formato DD/MM/YYYY o D/M/YYYY
	 * @return String La fecha formateada o FALSE si el formato es invalido
	 * @version 1.3
	 *
	 */
	public function date2mysql($date)
	{
		if (!ereg ('^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$', $date))
		{
			return false;
		}
		$datearray = explode ("/", $date);

		$dd = $datearray[0];
		if ($dd > 0 and $dd <= 31)
		{
			$dd = sprintf ("%02d", $dd);
		}
		else
		{
			return false;
		} // un minimo chequeo del dia

		$mm = $datearray[1];
		if ($mm > 0 and $mm <= 12)
		{
			$mm = sprintf ("%02d", $mm);
		}
		else
		{
			return false;
		} // un minimo chequeo del mes

		$yyyy = $datearray[2];
		if ($yyyy > 0 and $yyyy <= 9999)
		{
			$yyyy = sprintf ("%04d", $yyyy);
		}
		else
		{
			return false;
		} // un minimo chequeo del a�o

		return "$yyyy-$mm-$dd";
	}

	/**
	 * Retorna la representacion de una fecha (por ejemplo: Hace 3 dias.
	 * o Ayer)
	 * Para usar entre 0 minutos de diferencia hasta semanas
	 *
	 * @param Integer $ts
	 *        	Timestamp
	 * @param String $formatoFecha
	 *        	El formato de fecha a mostrar para cuando es mayor a 31 d�as
	 * @return String
	 * @version 1.2
	 */
	public function mysql2preety($ts, $formatoFecha = "d/m/Y")
	{
		if (!ctype_digit ($ts))
		{
			$ts = strtotime ($ts);
		}
		$diff = time () - $ts;
		$day_diff = floor ($diff / 86400);

		if ($day_diff < 0)
		{
			return date ($formatoFecha, $ts); // fecha futura! no deberia pasar..
		}
		if ($day_diff == 0)
		{
			if ($diff < 60)
			{
				return "Recien";
			}
			if ($diff < 120)
			{
				return "Hace un minuto";
			}
			if ($diff < 3600)
			{
				return "Hace " . floor ($diff / 60) . " minutos";
			}
			if ($diff < 7200)
			{
				return "Hace una hora";
			}
			if ($diff < 86400)
			{
				return "Hace " . floor ($diff / 3600) . " horas";
			}
		}

		if ($day_diff == 1)
		{
			return "Ayer";
		}
		if ($day_diff < 7)
		{
			return "Hace " . $day_diff . " dias";
		}
		if ($day_diff < 31)
		{
			return "Hace " . ceil ($day_diff / 7) . " semanas";
		}
		return date ($formatoFecha, $ts);
	}

	// *****************************************************************************************************************//
	// FUNCIONES DE EMAIL
	// *****************************************************************************************************************//

	/**
	 * Funcion mail extendida
	 *
	 * @param String $para
	 *        	Email del destinatario o en formato: "Juan Perez" <juan@hotmail.com>
	 * @param String $asunto
	 * @param String $mensaje
	 * @param String $deEmail
	 *        	Email del remitente
	 * @param String $deNombre
	 *        	Nombre del remitente
	 * @param Boolean $html
	 *        	True si es en formato HTML (por defecto) o FALSE si es texto plano
	 * @param String $prioridad
	 *        	Alta o Baja (por defecto es Normal)
	 * @param String $xmailer
	 *        	X-Mailer ej: Mi Sistema 1.0.2
	 * @param String $notificacion_lectura_a
	 *        	Email donde se envia la notificacion de lectura del mensaje en formato: "Juan Perez" <juan@hotmail.com>
	 * @return Boolean
	 * @version 1.1
	 */
	public function enviar_mail($para, $asunto, $mensaje, $deEmail, $deNombre, $html = true, $prioridad = "Normal", $xmailer = "", $notificacion_lectura_a = "")
	{
		$headers = "MIME-Version: 1.0 \n";
		if ($html)
		{
			$headers .= "Content-Type:text/html;charset=ISO-8859-1 \n";
		}
		else
		{
			$headers .= "Content-Type:text/plain;charset=ISO-8859-1 \n";
		}
		$headers .= "From: \"$deNombre\" <$deEmail> \n";

		if (strtolower ($prioridad) == "alta")
		{
			$headers .= "X-Priority: 1 \n";
		}
		elseif (strtolower ($prioridad) == "baja")
		{
			$headers .= "X-Priority: 5 \n";
		}

		if ($xmailer != "")
			$headers .= "X-Mailer: $xmailer \n";

		if ($notificacion_lectura_a != "")
			$headers .= "Disposition-Notification-To: $notificacion_lectura_a \n";

		if (@mail ($para, $asunto, $mensaje, $headers))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Funcion mail extendida con phpmailer
	 *
	 * @param array $para
	 *        	Destinatario/s array(array("email@serv.com","Juan")) o array(array("email@serv.com","Juan") , array("otro@serv.com","Pablo"))
	 * @param String $asunto
	 * @param String $mensaje
	 * @param String $deEmail
	 *        	Email del remitente
	 * @param String $deNombre
	 *        	Nombre del remitente
	 * @param Boolean $html
	 *        	True si es en formato HTML (por defecto) o FALSE si es texto plano
	 * @param array $adjuntos
	 *        	Archivos adjuntos en el email. array(array($_FILES['archivo']['tmp_name'] , $_FILES['archivo']['name']))
	 * @param string $charSet
	 * @param string $mailer
	 *        	"mail" o "sendmail" o "smtp"
	 * @version 1.0
	 */
	public function mail_ext($para, $asunto, $mensaje, $deEmail, $deNombre, $html = true, $adjuntos = "", $charSet = "iso-8859-1", $mailer = "mail", $sendmail = "/usr/sbin/sendmail", $smtpHost = "localhost", $smtpPort = 25, $smtpHelo = "localhost.localdomain", $smtpTimeOut = 10, $mail)
	{
		// $mail = new PHPMailer ();
		$mail->IsHTML ($html);
		$mail->Host = "localhost";
		$mail->From = $deEmail;
		$mail->FromName = $deNombre;
		$mail->Subject = $asunto;
		$mail->Body = $mensaje;
		$mail->CharSet = $charSet;

		foreach ($para as $item)
		{
			if (!is_array ($item) or count ($item) != 2)
			{
				throw new Exception ('Parametro $para no es un array con el formato correcto en enviar_mail()');
			}

			$mail->AddAddress ($item[0], $item[1]);
		}

		if (is_array ($adjuntos))
		{
			foreach ($adjuntos as $adjunto)
			{
				$mail->AddAttachment ($adjunto[0], $adjunto[1]);
			}
		}

		if ($mailer == "sendmail")
		{
			$mail->sendmail = $sendmail;
		}
		elseif ($mailer == "smtp")
		{
			$mail->Host = $smtpHost;
			$mail->Port = $smtpPort;
			$mail->Helo = $smtpHelo;
			$mail->Timeout = $smtpTimeOut;
		}

		$mail->Send ();
	}

	// *****************************************************************************************************************//
	// FUNCIONES VARIAS
	// *****************************************************************************************************************//

	/**
	 * Imprime el META de HTML y hace Exit para redireccionar al usuario a $url
	 * Esta funci�n es util para cuando no se pueden mandar headers por haber impreso antes.
	 *
	 * @param String $url
	 * @param Integer $segundos
	 *        	Tiempo en segundos antes de hacer la redireccion
	 * @param String $mensaje
	 *        	Un mensaje opcional a imprimir en pantalla
	 * @version 1.0
	 */
	public function redirect_http($url, $segundos = 0, $mensaje = "")
	{
		echo "<HTML><HEAD>";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$segundos; URL=$url\">";
		if ($mensaje != "")
		{
			echo $mensaje;
		}
		echo "</HEAD></HTML>";
		exit ();
	}

	/**
	 * Imprime el header Location y hace Exit para redireccionar al usuario a $url
	 * Nota: en el caso de que la variable $debug esta seteada a TRUE en vez de mandar
	 * el header llama a la funcion redirect_http() porque al estar debugueando el header no
	 * se podria mandar por haber mandado contenido antes.
	 *
	 * @param string $url
	 *        	-Direccion a la que redireccionar.
	 * @version 1.2 - Se remplazaron las variables globales por parametros y otras de la clase.
	 * @version 1.0
	 */
	public function redirect($url, $start_time)
	{
		if ($this->debug)
		{
			redirect_http ($url, 120, "<i>Transcurrieron " . (microtime () - $start_time) . " segundos</i><br><a href='$url'>Haga click para continuar a: $url</a>");
		}
		else
		{
			header ("Location:$url");
			exit ();
		}
	}

	/**
	 * Borra un directorio con todos sus archivos y sub directorios
	 *
	 * @name delTree
	 *
	 * @param string $dir
	 *        	- direccion del directorio a eliminar.
	 * @return boolean
	 */
	public function delTree($dir)
	{
		if (is_dir ($dir))
		{
			$objects = scandir ($dir);
			foreach ($objects as $object)
			{
				if ($object != "." && $object != "..")
				{
					if (filetype ($dir . "/" . $object) == "dir")
					{
						delTree ($dir . "/" . $object);
					}
					else
					{
						unlink ($dir . "/" . $object);
					}
				}
			}
			reset ($objects);

			if (rmdir ($dir))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 * Crea los tags <OPTION> numericos para un <SELECT>
	 *
	 * @name crear_opciones_select
	 *
	 * @param number $desde
	 *        	- desde que numero
	 * @param number $hasta
	 *        	- hasta que numero
	 * @param number $incremento
	 *        	- de a cuanto incrementa
	 * @param string $selected
	 *        	- cual tiene que estar seleccionado (ninguno = "")
	 *
	 * @return string
	 */
	public function crear_opciones_select($desde, $hasta, $incremento = 1, $selected = "")
	{
		$options = "";

		for($i = $desde; $i <= $hasta; $i = $i + $incremento)
		{
			if ($i == $selected)
			{
				$options .= "<option value=$i selected='selected'>$i</option>\n";
			}
			else
			{
				$options .= "<option value=$i>$i</option>\n";
			}
		}
		return $options;
	}

	/**
	 * Obtiene el hostname de una url.
	 *
	 * @example http://www.google.com/adsense?u=232 retorna: google.com
	 *
	 * @name extractHostPart
	 *
	 * @param string $url
	 * @param bool $stripWww
	 *        	- Opcional.
	 *        	- En caso de no pasarse valores se definira como true.
	 *        	- true se quiere extraer el www.
	 *        	- false si se quiere dejar el www
	 * @return string
	 */
	public function extractHostPart($url, $stripWww = true)
	{
		$partes = parse_url ($url);

		if ($partes === false)
		{
			return false;
		}
		else
		{
			$hostName = $partes['host'];
			if ($stripWww)
			{
				$hostName = preg_replace ("/www./i", "", $partes['host']);
			}
			return $hostName;
		}
	}

	/**
	 * Obtiene el hostname de un email.
	 * ej: ejemplo@gmail.com retorna: gmail.com
	 *
	 * @name getHostNameEmail
	 *
	 * @param string $email
	 * @return string|bool hostname o FALSE si no es un email valido.
	 */
	public function getHostNameEmail($email)
	{
		// FIXME Hay que probar la funcion para verificar su correcto funcionamiento
		if (preg_match ('/^([_a-z0-9+-]+)(\.[_a-z0-9+-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $email, $matches))
		{
			// preg_match ('/^([_a-z0-9+-]+)(\.[_a-z0-9+-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $email, $matches);

			return $matches[3] . $matches[5];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Genera un dump de una base de datos a un archivo en el servidor o para bajar directamente sin usar comandos externos
	 *
	 * @name backup_db
	 *
	 * @param object $db
	 *        	- Objeto encargado de la administracion de la base de datos.
	 * @param string $tables
	 *        	- Opcional.
	 *        	- En caso de no pasarse valores se tomaran todos.
	 * @param string $fileName
	 *        	Nombre del archivo donde se guarde el backup.
	 *        	- Opcional.
	 *        	- En caso de no pasarse valores se usara bkp.
	 * @param boolean $download
	 *        	Establece si descargar de forma automatica el archivo o no.
	 * @return string
	 */
	public function backup_db($db, $tables = '*', $fileName = 'bkp', $download = true)
	{

		// FIXME Hay que probar la funcion para verificar su correcto funcionamiento
		// Recuperamos todas las tablas
		if ($tables == '*')
		{
			$tables = array ();
			$result = $db->query ('SHOW TABLES');

			while ($row = $db->fetch_row ($result))
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array ($tables) ? $tables : explode (',', $tables);
		}

		foreach ($tables as $table)
		{
			$result = $db->query ('SELECT * FROM ' . $table);
			$num_fields = $db->num_fields ($result);

			$return .= 'DROP TABLE IF EXISTS ' . $table . ';';
			$row2 = $db->fetch_row ($db->query ('SHOW CREATE TABLE ' . $table));
			$return .= "\n\n" . $row2[1] . ";\n\n";

			for($i = 0; $i < $num_fields; $i++)
			{
				while ($row = $db->fetch_row ($result))
				{
					$return .= 'INSERT INTO ' . $table . ' VALUES(';

					for($j = 0; $j < $num_fields; $j++)
					{
						$row[$j] = addslashes ($row[$j]);

						$row[$j] = preg_replace ("\n", "\\n", $row[$j]);

						if (isset ($row[$j]))
						{
							$return .= '"' . $row[$j] . '"';
						}
						else
						{
							$return .= '""';
						}

						if ($j < ($num_fields - 1))
						{
							$return .= ',';
						}
					}
					$return .= ");\n";
				}
			}
			$return .= "\n\n\n";
		}

		if ($download)
		{
			header ("Content-Type: text/plain");
			header ("Content-Disposition: attachment; filename=" . $fileName);
			header ("Content-Length: " . strlen ($return)); // it is needed for the progress bar of the browser
			echo $return;
		}
		else
		{

			$handle = fopen ($fileName, 'w+');
			fwrite ($handle, $return);
			fclose ($handle);
		}

		$return = "";
	}

	/**
	 * Retorna true si el dominio del $email pertenece a un dominio de emails temporales anti spam.
	 *
	 * @name dominioEmailBaneado
	 *
	 * @param string $email
	 * @param string[] $hostNoValidosParaEmail
	 *        	- Array con el listado de dominios baneados, si no se espesifica usa el listado por defecto.
	 * @return boolean
	 */
	public function dominioEmailBaneado($email, $hostNoValidosParaEmail = "")
	{
		if ($hostNoValidosParaEmail == "")
		{
			// Dominios de email baneados
			$hostNoValidosParaEmail = array (
					"mailinator.com",
					"binkmail.com",
					"suremail.info",
					"bobmail.info",
					"anonymbox.com",
					"deadaddress.com",
					"spamcero.com",
					"zippymail.info",
					"sogetthis.com",
					"safetymail.info",
					"thisisnotmyrealemail.com",
					"tradermail.info",
					"nepwk.com",
					"sharklasers.com",
					"tempemail.net",
					"temporaryemail.net",
					"trashymail.com",
					"maileater.com",
					"spambox.us",
					"spamhole.com",
					"pookmail.com",
					"mailslite.com",
					"20minutemail.com",
					"nwldx.com",
					"makemetheking.com"
			);
		}

		if (in_array (getHostNameEmail (strtolower ($email)), strtolower ($hostNoValidosParaEmail)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Verifica que una peticion de AJAX
	 *
	 * @name is_ajax_request
	 *
	 * @return boolean
	 */
	public function is_ajax_request()
	{
		if (!empty ($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower ($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*
	 * **************************************************************
	 * FUNCIONES DE ARCHIVOS Y DIRECTORIOS
	 * **************************************************************
	 */

	/**
	 * Abrir un directorio y listarlo recursivo.
	 *
	 * @param string $ruta
	 *        	- ruta desde la que queremos realizar la busqueda
	 * @param string[] $excepcion
	 *        	Listado de directorios/archivos a omitir.
	 *        	- Opcional.
	 *        	- Puede o no ser un array.
	 *
	 * @throws Exception
	 * @return string - Listado de directorios y archivos.
	 */
	public function listar_directorios_ruta($ruta, $excepcion = "")
	{
		$lista = "";

		if (is_dir ($ruta))
		{
			if ($dh = opendir ($ruta))
			{
				while (($file = readdir ($dh)) !== false)
				{
					// esta l�nea la utilizar�amos si queremos listar todo lo que hay en el directorio
					// mostrar�a tanto archivos como directorios
					// echo "<br>Nombre de archivo: $file : Es un: " . filetype($ruta . $file);

					if ((is_dir ($ruta . $file) && $file != "." && $file != "..") and (isset ($excepcion) and $excepcion != $file) or (is_array ($excepcion) and in_array ($file, $excepcion)))
					{
						// solo si el archivo es un directorio, distinto que "." y ".."

						if (is_dir ($ruta . $file))
						{
							$this->listar_directorios_ruta ($ruta . "/" . $file . "/");
						}
						else
						{
							$lista .= "<br>Directorio: " . $ruta . "/" . $file;
						}
					}
				}
				closedir ($dh);
			}
			return $lista;
		}
		else
		{
			throw new Exception ('No es ruta valida');
		}
	}

	public function listar($directorio)
	{
		$out = array ();
		$dir = opendir ($directorio);
		while (false !== ($file = readdir ($dir)))
		{
			if (($file != '.') && ($file != '..'))
			{
				if (is_file ($directorio . '/' . $file))
				{
					$out[] = $file;
				}
				elseif (is_dir ($directorio . '/' . $file))
				{
					foreach ($this->listar ($directorio . '/' . $file) as $one)
					{
						$out[] = $file . '/' . $one;
					}
				}
			}
		}
		closedir ($dir);
		return $out;
	}

	/**
	 * Comprueba que la variable contenga algun dato no nulo.
	 *
	 * @param mixed $v
	 * @return boolean
	 */
	public function is_empty_or_null($v)
	{
		if (!isset ($v) or empty ($v) or (strlen ($v) == 0) or ($v == NULL) or ($v == 'NULL') or ($v == 'null'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna un array con todos los archivos de una direccion X.
	 *
	 * @param String $path
	 *        	Direccion del directorio a recorrer
	 * @param boolean $recusivo
	 * @return array
	 */
	public function filesToArray($path, $recusivo = false)
	{
		$dir = opendir ($path);
		$files = array ();
		while ($current = readdir ($dir))
		{
			if ($current != "." && $current != "..")
			{
				if (!is_dir ($path . $current))
				{
					$files[] = $current;
				}
				elseif ($recusivo == true)
				{
					$files = array_merge ($files, filesToArray ($path . $current, $recusivo));
				}
			}
		}

		return $files;
	}

	public function manejoDeErrores($e)
	{
		if ($this->debug == true)
		{
			return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage ();
		}
		else
		{
			return $e->getMessage ();
		}

		if ($this->dieOnError == true)
		{
			exit ();
		}
	}

	// ///////////////////////////////////////////////////////////////////////////////////////////////////////////
}

?>