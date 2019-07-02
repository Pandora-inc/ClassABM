<?php

/**
 * Archivo principal de la clase Generica.
 *
 * @name class_sitio.php
 * @author iberlot <@> iberlot@usal.edu.ar
 *
 */
require_once 'funciones.php';
require_once 'class_validar.php';
require_once 'funciones_string.php';
require_once 'class_fechas.php';

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
class Sitios
{
	/**
	 * titulo o nombre del sitio
	 *
	 * @var string
	 */
	private static $nombre;

	/**
	 * url del sitio
	 *
	 * @var string
	 */
	private static $url;

	/**
	 * url del sitio corta para otros propositos.
	 * Ej: MiSitio.com
	 *
	 * @var string
	 */
	private static $urlCorta;

	/**
	 * El path completo del sitio
	 *
	 * @var string
	 */
	private static $pathBase;

	/**
	 * email de donde salen los envios para los usuarios
	 *
	 * @var string
	 */
	private static $emailEnvios;

	/**
	 * from del email de donde salen los envios para los usuarios
	 *
	 * @var string
	 */
	private static $emailEnviosFrom;

	/**
	 * email del webmaster
	 *
	 * @var string
	 */
	private static $emailWebmaster;

	/**
	 * codigo de idioma por defecto del sitio
	 *
	 * @var string
	 */
	private static $idiomaPorDefecto = "es";

	/**
	 * idioma actualmente seleccionado
	 *
	 * @var string
	 */
	private static $idioma = "es";

	/**
	 * extension que agrega a las url SEO amigables
	 */
	private static $extension;

	/**
	 * Juego de caracteres del sitio
	 *
	 * @var string
	 */
	private static $charset;

	/**
	 *
	 * @var object
	 */
	private static $db;

	/**
	 * Ip o nombre del servidor al que se va a conectar la base de datos.
	 *
	 * @var string
	 */
	private static $dbSever;

	/**
	 * Puerto de coneccion a la DB.
	 *
	 * @var int
	 */
	private static $dbPort;
	private static $dbDSN;

	/**
	 * Usuario de conexion a la base
	 *
	 * @var string
	 */
	private static $dbUser;

	/**
	 * Contrase�a de conexion a la base
	 *
	 * @var string
	 */
	private static $dbPass;

	/**
	 * Base a la cual conectarse
	 *
	 * @var string
	 */
	private static $dbBase;

	/**
	 * Juego de caracteres de la conexion
	 *
	 * @var string
	 */
	private static $dbCharset;

	/**
	 * El tipo de DB (mysql, oracle o mssql)
	 *
	 * @var string
	 */
	private static $dbTipo;

	/**
	 * Usar die() si hay un error.
	 * Esto es util para etapa de desarrollo *
	 *
	 * @var boolean
	 */
	private static $dieOnError = false;

	/**
	 * Muestra por pantalla diferentes codigos para facilitar el debug
	 *
	 * @var boolean
	 */
	private static $debug = false;

	/**
	 * Habilita la muestra de mensajes de error.
	 *
	 * @var boolean
	 */
	private static $mostrarErrores = false;

	/**
	 * Aca se puede asignar un email para enviar aviso cuando hay errores sql *
	 */
	private static $emailAvisoErrorSql;

	/**
	 * Graba log con todas las consultas realizadas (solo usar en casos puntuales para debugear) *
	 */
	private static $grabarArchivoLogQuery = false;

	/**
	 * Graba log con los errores de BD *
	 *
	 * @var boolean
	 */
	private static $grabarArchivoLogError = false;

	/**
	 * Conjunto de caracteres latinos.
	 * Normalmente utilizado para funciones de limpieza y saneamiento.
	 *
	 * @var array
	 */
	private static $carateres_latinos = array ();

	/**
	 * Titulo para usar en las notificaciones.
	 *
	 * Es la redefinicion de $_SESSION['_sitio_notTit']
	 *
	 * @var string
	 */
	private static $sitio_notTit = "";

	/**
	 * Mensaje a mostrar en las notificaciones.
	 *
	 * Es la redefinicion de $_SESSION['_sitio_notMsg']
	 *
	 * @var string
	 */
	private static $sitio_notMsg = "";

	/**
	 * Cantidad de segundos para que aparezcan las notificaciones.
	 *
	 * Es la redefinicion de $_SESSION['_sitio_notSeg']
	 *
	 * @var integer - Por defecto tiene el valor de 5
	 */
	private static $sitio_notSeg = 5;

	/**
	 * Mensaje a mostrar en los mensajes.
	 *
	 * Es la redefinicion de $_SESSION['_sitio_msg']
	 *
	 * @var string
	 */
	private static $sitio_msg = "";

	/**
	 * Clase de mensaje a mostrar, puede tomar loa valorea Class para el mensaje info, tip, error, atencion
	 *
	 * Es la redefinicion de $_SESSION['_sitio_msgClass']
	 *
	 * @var string - por defecto tiene el valor info
	 */
	private static $sitio_msgClass = "info";

	/**
	 * Define si mostrar una sola vez el mensaje
	 *
	 * Es la redefinicion de $_SESSION['_sitio_msgMostrarUnaSolaVez']
	 *
	 * @var boolean - por defecto tiene el valor true
	 */
	private static $sitio_msgMostrarUnaSolaVez = true;

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
	public static function setMsg($msg, $class = 'info', $mostrarUnaSolaVez = true)
	{
		$this->sitio_sitio_msg = $msg;
		$this->sitio_msgClass = $class;
		$this->sitio_msgMostrarUnaSolaVez = $mostrarUnaSolaVez;
	}

	/**
	 * Imprime, si es que hay, un mensaje asignado por setMsg()
	 */
	public static function showMsg()
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
	public static function setNotif($titulo, $msg, $segundos = 5)
	{
		$this->sitio_notTit = $titulo;
		$this->sitio_notMsg = $msg;
		$this->sitio_notSeg = $segundos;
	}

	/**
	 * Imprime, si es que hay, un mensaje asignado por setMsg()
	 */
	public static function showNotif()
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
	public static function getConfig($db, $parametro, $valorPorDefecto = "")
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
	public static function link($url, $agregarExtension = true, $arrQS = "")
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
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @example $_REQUEST = limpiarEntidadesHTML($_REQUEST, $config);
	 *
	 * @param string[] $param
	 *        	- datos de lo cuales limpiarl las entidades html.
	 * @return array|string - Depende del parametro recibido, un array con los datos remplazados o un String
	 */
	public static function limpiarEntidadesHTML($param)
	{
		return FuncionesString::limpiarEntidadesHTML ($param, $this->charset);
	}

	/**
	 * Comprueba que la direccion de mail no tenga caracteres extranos.
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase validar
	 *
	 * @param string $str
	 *        	- email a verificar
	 * @return bool - Devuelve true o false dependiendo de si es o no un mail valido.
	 */
	public static function validarEmail($str)
	{
		return validar::esEmail ($str);
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
	public static function limpiarParaSql($param, $db)
	{
		return is_array ($param) ? array_map ('limpiarParaSql', $param) : mysqli_real_escape_string ($db->con, $param);
	}

	/**
	 * Reemplaza todos los acentos por sus equivalentes sin ellos.
	 * Ademas elimina cualquier caracter extrano en el string.
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @param string $string
	 *        	- la cadena a sanear
	 *
	 * @return string $string - saneada
	 */
	public static function sanear_string($string)
	{
		return FuncionesString::sanear_string ($string);
	}

	/**
	 * Remplaza los caracteres especiales por sus etiquetas html.
	 *
	 * @name convertir_especiales_html
	 *
	 * @version 1.2 - Se remplazan las variables globales por variables de la clase.
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @todo esta funcion usa la propiedad de la clase 'carateres_latinos' si no llegara a estar definida lo hace.
	 *
	 * @param string $str
	 *        	- Texto en el cual remplazar los caracteres especiales.
	 *
	 * @return string - Texto con los caracteres remplazados.
	 */
	public static function convertir_especiales_html($str)
	{
		return FuncionesString::convertir_especiales_html ($str);
	}

	/**
	 * Alimina cualquier caracter que no sea de la A a la z o numero.
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @param string $texto
	 * @return string
	 */
	public static function limpiarString($texto)
	{
		return FuncionesString::impiarString ($texto);
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
	public static function es_numerico($valor)
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
	public static function clean_numeric($str)
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
	public static function quitar($mensaje, $nopermitidos = "")
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
	public static function removeNulls($string)
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
	public static function generarInputSelect($db, $tabla, $campoSelec, $campoTexto = NULL, $seleccionado = NULL, $textoMayuscula = true, $mostrarValor = false)
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
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param string $fecha_inicio
	 * @return string
	 */
	public static function fecha_DD_MM_YYYY_Oracle($fecha_inicio)
	{
		return Fechas::fecha_DD_MM_YYYY_Oracle ($fecha_inicio);
	}

	/**
	 * Agarra caulquier fecha con el format inicial YYYY MM DD y la formatea para oracle.
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param string $fecha_inicio
	 *        	- Fecha con el formato YYYY-MM-DD o YYYY/MM/DD.
	 * @param string $separador
	 *        	- Caracter con el cual se va a separar la fecha, por defecto /.
	 * @throws Exception - retorna un error si la cantidad de digitos numericos de $fecha_inicio es menor que 8.
	 *
	 * @return string - retorna la fecha con el formato DD MM YYYY separado por el caracter separador.
	 */
	public static function formatear_fecha_Oracle($fecha_inicio, $separador = "/")
	{
		return Fechas::formatear_fecha_Oracle ($fecha_inicio, $separador);
	}

	public static function fecha_oracle($fecha)
	{
		//
		$retorno = Fechas::fecha_oracle ($fecha);

		// $fecha = $this->formatear_fecha_Oracle ($fecha, "-");

		// $fecha = "TO_DATE('$fecha', 'DD-MM-YYYY')";

		return $retorno;
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
	public static function invertirFecha($fecha)
	{
		list ($ano, $mes, $dia) = explode ('-', $fecha);
		$aux = $dia . "-" . $mes . "-" . $ano;

		return $aux;
	}

	/**
	 * Devuelve el dia correspondiente de la semana en formato de tres letras.
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param string $fecha
	 *        	- fecha con el formato ano-mes-dia
	 * @return string $dias
	 */
	public static function nombreDiacorto($fecha)
	{
		return Fechas::nombreDiacorto ($fecha);
	}

	/**
	 * Pasandosele un numero devielve el nombre del mes que le corresponde.
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param int $numMes
	 * @return string
	 */
	public static function getNombreMes($numMes)
	{
		return Fechas::getNombreMes ($numMes);
	}

	/**
	 * Suma una cantidad X de dias a una fecha.
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param string $fecha
	 *        	- fecha con el formato ano-mes-dia.
	 * @param int $dia
	 *        	- numero de dias a sumar.
	 * @return string - fecha con los dias sumados.
	 */
	public static function sumaDia($fecha, $dia)
	{
		return Fechas::sumaDia ($fecha, $dia);
	}

	/**
	 * Diferencia de Dias - Fecha mayor, Fecha menor
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param string $fecha2
	 *        	- Fecha mayor con el formato ano-mes-dia
	 * @param string $fecha1
	 *        	- fecha menor con el formato ano-mes-dia
	 * @return string $dias_diferencia - Cantidad de dias que hay entre las dos fechas
	 */
	public static function diferenciaDias($fecha2, $fecha1)
	{
		return Fechas::diferenciaDias ($fecha2, $fecha1);
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
	public static function fechaCorrecta($d, $m, $a)
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
	public static function calcularMminutosExcedentes($hora1, $hora2)
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
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param string $inicio
	 *        	- Hora mayor con el formato H:i:s
	 * @param string $fin
	 *        	- Hora menor con el formato H:i:s
	 * @return string - Hora con el valor de la resta
	 */
	public static function difHoras($inicio, $fin)
	{
		return Fechas::difHoras ($inicio, $fin);
	}

	/**
	 * Suma de horas
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param string $hora1
	 *        	- Primer valor a sumar con el formato H:i:s
	 * @param string $hora2
	 *        	- Segundo valor a sumar con el formato H:i:s
	 * @return string - resultado de la suma de horas
	 */
	public static function sumaHoras($hora1, $hora2)
	{
		return Fechas::sumaHoras ($hora1, $hora2);
	}

	/**
	 * Resibe un int con la cantidad de meses y retorna un string con la cantidad de años y meses.
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param int $meses
	 *        	Cantidad de meses
	 * @return string - XxXx años y XxXx meses.
	 */
	public static function mesesAnios($meses)
	{
		return Fechas::mesesAnios ($meses);
	}

	/**
	 * Devuelve el tipo de codificacion de los caracteres usados en un string
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param string $texto
	 * @return string
	 */
	public static function codificacion($texto)
	{
		return FuncionesString::codificacion ($texto);
	}

	/**
	 * Codifica un strin en UTF8
	 *
	 * Primero comprueba que este no este ya en utf8 para no romper los caracteres
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @param string $texto
	 * @return string
	 */
	public static function utf8_encode_seguro($texto)
	{
		return FuncionesString::utf8_encode_seguro ($texto);
	}

	/**
	 * Funcion para sanear los valores recibidos del formulario.
	 * Evita la inyeccion de SQL. Elimina cualquier caracter no numerico.
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @param string $str
	 */
	public static function clean($str)
	{
		return FuncionesString::clean ($str);
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
	public static function save_image($inPath, $outPath)
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
	public static function mantenimiento()
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
	public static function human_filesize($bytes, $decimals = 2)
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
	public static function getYoutubeVideoId($youtubeVideoLink)
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
	public static function encryptData($value, $key)
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
	public static function decryptData($value, $key)
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
	public static function agregarHTTP($string)
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
	public static function agregarLinksEmail($str, $target = "_blank")
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
	public static function formatearLinks($str, $target = '_blank', $maxLen = 50, $mid = '...')
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
	public static function formatMoney($number, $fractional = false)
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
	public static function remplazarEmailyWWW($str, $target = "_blank", $maxLen = 50, $mid = '...')
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
	public static function cortar_str($str, $len, $txt_continua = "...")
	{
		if (strlen ($str) > $len)
		{
			// Cortamos la cadena por los espacios
			$arrayTexto = explode (' ', $str);

			$texto = '';
			$contador = 0;

			// Reconstruimos la cadena
			while ($len >= strlen ($texto) + strlen ($arrayTexto[$contador]))
			{
				$texto .= ' ' . $arrayTexto[$contador];
				$contador ++;
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
	public static function strip_saltos($str)
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
	public static function url_amigable($str)
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
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @param string $str
	 * @return string
	 * @author Andres Carizza
	 * @version 1.2
	 *
	 */
	public static function remplazar_caracteres_latinos($str)
	{
		return FuncionesString::remplazar_caracteres_latinos ($str);
	}

	/**
	 * Formatea un string para que corresponda con un nombre v�lido de archivo
	 *
	 * @param string $str
	 * @return string
	 * @author Andres Carizza
	 * @version 1.1
	 */
	public static function format_valid_filename($str, $remplazarCaracteresLatinos = true, $conservarEspacios = false)
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
	public static function limpiarParaBusquedaSql($valor)
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
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @access public
	 * @param
	 *        	string
	 * @return string
	 */
	function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		return FuncionesString::remove_invisible_characters ($str, $url_encoded);
	}

	/**
	 * Lo mismo que utf8_encode() pero aplicado a todo el array
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @param array $array
	 * @return array
	 */
	public static function utf8_encode_array($array)
	{
		return FuncionesString::utf8_encode_array ($array);
	}

	/**
	 * Lo mismo que utf8_decode() pero aplicado a todo el array
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase FuncionesString
	 *
	 * @param array $array
	 * @return array
	 */
	public static function utf8_decode_array($array)
	{
		return FuncionesString::utf8_decode_array ($array);
	}

	// *****************************************************************************************************************//
	// FUNCIONES DE FECHA
	// *****************************************************************************************************************//

	/**
	 * Formatea la fecha que usa el MySQL (YYYY-MM-DD) o (YYYY-MM-DD HH:MM:SS) a un formato de fecha m�s claro
	 * En caso de que falle el formateo retorna FALSE
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param String $mysqldate
	 *        	La fecha en formato YYYY-MM-DD o YYYY-MM-DD HH:MM:SS
	 * @param Boolean $conHora
	 *        	True si se quiere dejar la hora o false si se quiere quitar
	 * @return String La fecha formateada
	 * @version 1.1
	 *
	 */
	public static function mysql2date($mysqldate, $conHora = false)
	{
		return Fechas::mysql2date ($mysqldate, $conHora);
	}

	/**
	 * Convierte el formato de fecha (DD/MM/YYYY) al que usa el MySQL (YYYY-MM-DD)
	 * Se pueden enviar dias y meses con un digito (ej: 3/2/1851) o as� (ej: 03/02/1851)
	 * La fecha tiene que enviarse en el orden dia/mes/ano
	 * En caso de que falle el formateo retorna FALSE
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param String $date
	 *        	La fecha en formato DD/MM/YYYY o D/M/YYYY
	 * @return String La fecha formateada o FALSE si el formato es invalido
	 * @version 1.3
	 *
	 */
	public static function date2mysql($date)
	{
		return Fechas::date2mysql ($date);
	}

	/**
	 * Retorna la representacion de una fecha (por ejemplo: Hace 3 dias.
	 * o Ayer)
	 * Para usar entre 0 minutos de diferencia hasta semanas
	 *
	 * @deprecated Se recomienda utilizar directamente la funcion de la clase Fechas
	 *
	 * @param Integer $ts
	 *        	Timestamp
	 * @param String $formatoFecha
	 *        	El formato de fecha a mostrar para cuando es mayor a 31 d�as
	 * @return String
	 * @version 1.2
	 */
	public static function mysql2preety($ts, $formatoFecha = "d/m/Y")
	{
		return Fechas::mysql2preety ($ts, $formatoFecha);
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
	public static function enviar_mail($para, $asunto, $mensaje, $deEmail, $deNombre, $html = true, $prioridad = "Normal", $xmailer = "", $notificacion_lectura_a = "")
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
	public static function mail_ext($para, $asunto, $mensaje, $deEmail, $deNombre, $html = true, $adjuntos = "", $charSet = "iso-8859-1", $mailer = "mail", $sendmail = "/usr/sbin/sendmail", $smtpHost = "localhost", $smtpPort = 25, $smtpHelo = "localhost.localdomain", $smtpTimeOut = 10, $mail)
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
	public static function redirect_http($url, $segundos = 0, $mensaje = "")
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
	public static function redirect($url, $start_time)
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
	public static function delTree($dir)
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
	public static function crear_opciones_select($desde, $hasta, $incremento = 1, $selected = "")
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
	public static function extractHostPart($url, $stripWww = true)
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
	public static function getHostNameEmail($email)
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
	public static function backup_db($db, $tables = '*', $fileName = 'bkp', $download = true)
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

			for($i = 0; $i < $num_fields; $i ++)
			{
				while ($row = $db->fetch_row ($result))
				{
					$return .= 'INSERT INTO ' . $table . ' VALUES(';

					for($j = 0; $j < $num_fields; $j ++)
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
	public static function dominioEmailBaneado($email, $hostNoValidosParaEmail = "")
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
	public static function is_ajax_request()
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
	public static function listar_directorios_ruta($ruta, $excepcion = "")
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

	public static function listar($directorio)
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
	public static function is_empty_or_null($v)
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
	 * @since 20/11/2018 - Se modifica para que se puedan obviar los archivos ocultos
	 *
	 * @param String $path
	 *        	Direccion del directorio a recorrer
	 * @param boolean $recusivo
	 * @return array
	 */
	public static function filesToArray($path, $recusivo = false, $ocultos = false)
	{
		$dir = opendir ($path);
		$files = array ();
		while ($current = readdir ($dir))
		{
			if ($current != "." && $current != "..")
			{
				if (($ocultos == FALSE and (substr ($current, 0, 1) != ".")) or $ocultos == TRUE)
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
		}

		return $files;
	}

	/**
	 * Retorna un mensaje de error depndiendo de lo capturado.
	 *
	 * @param object $e
	 * @return string
	 */
	public static function manejoDeErrores($e)
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

	/**
	 * Retorna el valor de $nombre
	 *
	 * @return string
	 */
	public static function getNombre()
	{
		return Sitios::$nombre;
	}

	/**
	 * Setea $nombre con el parametro dado.
	 *
	 * @param string $nombre
	 */
	public static function setNombre($nombre)
	{
		Sitios::$nombre = $nombre;
	}

	/**
	 * Retorna el valor de $url
	 *
	 * @return string
	 */
	public static function getUrl()
	{
		return Sitios::$url;
	}

	/**
	 * Setea $url con el parametro dado.
	 *
	 * @param string $url
	 */
	public static function setUrl($url)
	{
		Sitios::$url = $url;
	}

	/**
	 * Retorna el valor de $urlCorta
	 *
	 * @return string
	 */
	public static function getUrlCorta()
	{
		return Sitios::$urlCorta;
	}

	/**
	 * Setea $urlCorta con el parametro dado.
	 *
	 * @param string $urlCorta
	 */
	public static function setUrlCorta($urlCorta)
	{
		Sitios::$urlCorta = $urlCorta;
	}

	/**
	 * Retorna el valor de $pathBase
	 *
	 * @return string
	 */
	public static function getPathBase()
	{
		return Sitios::$pathBase;
	}

	/**
	 * Setea $pathBase con el parametro dado.
	 *
	 * @param string $pathBase
	 */
	public static function setPathBase($pathBase)
	{
		Sitios::$pathBase = $pathBase;
	}

	/**
	 * Retorna el valor de $emailEnvios
	 *
	 * @return string
	 */
	public static function getEmailEnvios()
	{
		return Sitios::$emailEnvios;
	}

	/**
	 * Setea $emailEnvios con el parametro dado.
	 *
	 * @param string $emailEnvios
	 */
	public static function setEmailEnvios($emailEnvios)
	{
		Sitios::$emailEnvios = $emailEnvios;
	}

	/**
	 * Retorna el valor de $emailEnviosFrom
	 *
	 * @return string
	 */
	public static function getEmailEnviosFrom()
	{
		return Sitios::$emailEnviosFrom;
	}

	/**
	 * Setea $emailEnviosFrom con el parametro dado.
	 *
	 * @param string $emailEnviosFrom
	 */
	public static function setEmailEnviosFrom($emailEnviosFrom)
	{
		Sitios::$emailEnviosFrom = $emailEnviosFrom;
	}

	/**
	 * Retorna el valor de $emailWebmaster
	 *
	 * @return string
	 */
	public static function getEmailWebmaster()
	{
		return Sitios::$emailWebmaster;
	}

	/**
	 * Setea $emailWebmaster con el parametro dado.
	 *
	 * @param string $emailWebmaster
	 */
	public static function setEmailWebmaster($emailWebmaster)
	{
		Sitios::$emailWebmaster = $emailWebmaster;
	}

	/**
	 * Retorna el valor de $idiomaPorDefecto
	 *
	 * @return string
	 */
	public static function getIdiomaPorDefecto()
	{
		return Sitios::$idiomaPorDefecto;
	}

	/**
	 * Setea $idiomaPorDefecto con el parametro dado.
	 *
	 * @param string $idiomaPorDefecto
	 */
	public static function setIdiomaPorDefecto($idiomaPorDefecto)
	{
		Sitios::$idiomaPorDefecto = $idiomaPorDefecto;
	}

	/**
	 * Retorna el valor de $idioma
	 *
	 * @return string
	 */
	public static function getIdioma()
	{
		return Sitios::$idioma;
	}

	/**
	 * Setea $idioma con el parametro dado.
	 *
	 * @param string $idioma
	 */
	public static function setIdioma($idioma)
	{
		Sitios::$idioma = $idioma;
	}

	/**
	 * Retorna el valor de $extension
	 *
	 * @return mixed
	 */
	public static function getExtension()
	{
		return Sitios::$extension;
	}

	/**
	 * Setea $extension con el parametro dado.
	 *
	 * @param mixed $extension
	 */
	public static function setExtension($extension)
	{
		Sitios::$extension = $extension;
	}

	/**
	 * Retorna el valor de $charset
	 *
	 * @return string
	 */
	public static function getCharset()
	{
		return Sitios::$charset;
	}

	/**
	 * Setea $charset con el parametro dado.
	 *
	 * @param string $charset
	 */
	public static function setCharset($charset)
	{
		Sitios::$charset = $charset;
	}

	/**
	 * Retorna el valor de $dbSever
	 *
	 * @return string
	 */
	public static function getDbSever()
	{
		return Sitios::$dbSever;
	}

	/**
	 * Setea $dbSever con el parametro dado.
	 *
	 * @param string $dbSever
	 */
	public static function setDbSever($dbSever)
	{
		Sitios::$dbSever = $dbSever;
	}

	/**
	 * Retorna el valor de $dbPort
	 *
	 * @return number
	 */
	public static function getDbPort()
	{
		return Sitios::$dbPort;
	}

	/**
	 * Setea $dbPort con el parametro dado.
	 *
	 * @param number $dbPort
	 */
	public static function setDbPort($dbPort)
	{
		Sitios::$dbPort = $dbPort;
	}

	/**
	 * Retorna el valor de $dbDSN
	 *
	 * @return mixed
	 */
	public static function getDbDSN()
	{
		return Sitios::$dbDSN;
	}

	/**
	 * Setea $dbDSN con el parametro dado.
	 *
	 * @param mixed $dbDSN
	 */
	public static function setDbDSN($dbDSN)
	{
		Sitios::$dbDSN = $dbDSN;
	}

	/**
	 * Retorna el valor de $dbUser
	 *
	 * @return string
	 */
	public static function getDbUser()
	{
		return Sitios::$dbUser;
	}

	/**
	 * Setea $dbUser con el parametro dado.
	 *
	 * @param string $dbUser
	 */
	public static function setDbUser($dbUser)
	{
		Sitios::$dbUser = $dbUser;
	}

	/**
	 * Retorna el valor de $dbPass
	 *
	 * @return string
	 */
	public static function getDbPass()
	{
		return Sitios::$dbPass;
	}

	/**
	 * Setea $dbPass con el parametro dado.
	 *
	 * @param string $dbPass
	 */
	public static function setDbPass($dbPass)
	{
		Sitios::$dbPass = $dbPass;
	}

	/**
	 * Retorna el valor de $dbBase
	 *
	 * @return string
	 */
	public static function getDbBase()
	{
		return Sitios::$dbBase;
	}

	/**
	 * Setea $dbBase con el parametro dado.
	 *
	 * @param string $dbBase
	 */
	public static function setDbBase($dbBase)
	{
		Sitios::$dbBase = $dbBase;
	}

	/**
	 * Retorna el valor de $dbCharset
	 *
	 * @return string
	 */
	public static function getDbCharset()
	{
		return Sitios::$dbCharset;
	}

	/**
	 * Setea $dbCharset con el parametro dado.
	 *
	 * @param string $dbCharset
	 */
	public static function setDbCharset($dbCharset)
	{
		Sitios::$dbCharset = $dbCharset;
	}

	/**
	 * Retorna el valor de $dbTipo
	 *
	 * @return string
	 */
	public static function getDbTipo()
	{
		return Sitios::$dbTipo;
	}

	/**
	 * Setea $dbTipo con el parametro dado.
	 *
	 * @param string $dbTipo
	 */
	public static function setDbTipo($dbTipo)
	{
		Sitios::$dbTipo = $dbTipo;
	}

	/**
	 * Retorna el valor de $dieOnError
	 *
	 * @return boolean
	 */
	public static function isDieOnError()
	{
		return Sitios::$dieOnError;
	}

	/**
	 * Setea $dieOnError con el parametro dado.
	 *
	 * @param boolean $dieOnError
	 */
	public static function setDieOnError($dieOnError)
	{
		Sitios::$dieOnError = $dieOnError;
	}

	/**
	 * Retorna el valor de $debug
	 *
	 * @return boolean
	 */
	public static function isDebug()
	{
		return Sitios::$debug;
	}

	/**
	 * Setea $debug con el parametro dado.
	 *
	 * @param boolean $debug
	 */
	public static function setDebug($debug)
	{
		Sitios::$debug = $debug;
	}

	/**
	 * Retorna el valor de $mostrarErrores
	 *
	 * @return boolean
	 */
	public static function isMostrarErrores()
	{
		return Sitios::$mostrarErrores;
	}

	/**
	 * Setea $mostrarErrores con el parametro dado.
	 *
	 * @param boolean $mostrarErrores
	 */
	public static function setMostrarErrores($mostrarErrores)
	{
		Sitios::$mostrarErrores = $mostrarErrores;
	}

	/**
	 * Retorna el valor de $emailAvisoErrorSql
	 *
	 * @return mixed
	 */
	public static function getEmailAvisoErrorSql()
	{
		return Sitios::$emailAvisoErrorSql;
	}

	/**
	 * Setea $emailAvisoErrorSql con el parametro dado.
	 *
	 * @param mixed $emailAvisoErrorSql
	 */
	public static function setEmailAvisoErrorSql($emailAvisoErrorSql)
	{
		Sitios::$emailAvisoErrorSql = $emailAvisoErrorSql;
	}

	/**
	 * Retorna el valor de $grabarArchivoLogQuery
	 *
	 * @return boolean
	 */
	public static function getGrabarArchivoLogQuery()
	{
		return Sitios::$grabarArchivoLogQuery;
	}

	/**
	 * Setea $grabarArchivoLogQuery con el parametro dado.
	 *
	 * @param boolean $grabarArchivoLogQuery
	 */
	public static function setGrabarArchivoLogQuery($grabarArchivoLogQuery)
	{
		Sitios::$grabarArchivoLogQuery = $grabarArchivoLogQuery;
	}

	/**
	 * Retorna el valor de $grabarArchivoLogError
	 *
	 * @return boolean
	 */
	public static function isGrabarArchivoLogError()
	{
		return Sitios::$grabarArchivoLogError;
	}

	/**
	 * Setea $grabarArchivoLogError con el parametro dado.
	 *
	 * @param boolean $grabarArchivoLogError
	 */
	public static function setGrabarArchivoLogError($grabarArchivoLogError)
	{
		Sitios::$grabarArchivoLogError = $grabarArchivoLogError;
	}

	/**
	 * Retorna el valor de $carateres_latinos
	 *
	 * @return array
	 */
	public static function getCarateres_latinos()
	{
		return Sitios::$carateres_latinos;
	}

	/**
	 * Setea $carateres_latinos con el parametro dado.
	 *
	 * @param array $carateres_latinos
	 */
	public static function setCarateres_latinos($carateres_latinos)
	{
		Sitios::$carateres_latinos = $carateres_latinos;
	}

	/**
	 * Retorna el valor de $sitio_notTit
	 *
	 * @return string
	 */
	public static function getSitio_notTit()
	{
		return $this->sitio_notTit;
	}

	/**
	 * Setea $sitio_notTit con el parametro dado.
	 *
	 * @param string $sitio_notTit
	 */
	public static function setSitio_notTit($sitio_notTit)
	{
		$this->sitio_notTit = $sitio_notTit;
	}

	/**
	 * Retorna el valor de $sitio_notMsg
	 *
	 * @return string
	 */
	public static function getSitio_notMsg()
	{
		return $this->sitio_notMsg;
	}

	/**
	 * Setea $sitio_notMsg con el parametro dado.
	 *
	 * @param string $sitio_notMsg
	 */
	public static function setSitio_notMsg($sitio_notMsg)
	{
		$this->sitio_notMsg = $sitio_notMsg;
	}

	/**
	 * Retorna el valor de $sitio_notSeg
	 *
	 * @return number
	 */
	public static function getSitio_notSeg()
	{
		return $this->sitio_notSeg;
	}

	/**
	 * Setea $sitio_notSeg con el parametro dado.
	 *
	 * @param number $sitio_notSeg
	 */
	public static function setSitio_notSeg($sitio_notSeg)
	{
		$this->sitio_notSeg = $sitio_notSeg;
	}

	/**
	 * Retorna el valor de $sitio_msg
	 *
	 * @return string
	 */
	public static function getSitio_msg()
	{
		return $this->sitio_msg;
	}

	/**
	 * Setea $sitio_msg con el parametro dado.
	 *
	 * @param string $sitio_msg
	 */
	public static function setSitio_msg($sitio_msg)
	{
		$this->sitio_msg = $sitio_msg;
	}

	/**
	 * Retorna el valor de $sitio_msgClass
	 *
	 * @return string
	 */
	public static function getSitio_msgClass()
	{
		return $this->sitio_msgClass;
	}

	/**
	 * Setea $sitio_msgClass con el parametro dado.
	 *
	 * @param string $sitio_msgClass
	 */
	public static function setSitio_msgClass($sitio_msgClass)
	{
		$this->sitio_msgClass = $sitio_msgClass;
	}

	/**
	 * Retorna el valor de $sitio_msgMostrarUnaSolaVez
	 *
	 * @return boolean
	 */
	public static function isSitio_msgMostrarUnaSolaVez()
	{
		return $this->sitio_msgMostrarUnaSolaVez;
	}

	/**
	 * Setea $sitio_msgMostrarUnaSolaVez con el parametro dado.
	 *
	 * @param boolean $sitio_msgMostrarUnaSolaVez
	 */
	public static function setSitio_msgMostrarUnaSolaVez($sitio_msgMostrarUnaSolaVez)
	{
		$this->sitio_msgMostrarUnaSolaVez = $sitio_msgMostrarUnaSolaVez;
	}

	/**
	 * Si estan asignados los parametros de coneccion a la base de datos establece una nueva y la setea en el atributo $db de la clase
	 *
	 *
	 * @throws Exception
	 */
	public static function openConnection()
	{
		if ((Sitios::getDbSever () != "") and (Sitios::getDbUser () != "") and (Sitios::getDbPass () != "") and (Sitios::getDbBase () != "") and (Sitios::getDbCharset () != "") and (Sitios::getDbTipo () != ""))
		{
			$this->db = new class_db (Sitios::getDbSever (), Sitios::getDbUser (), Sitios::getDbPass (), Sitios::getDbBase (), Sitios::getDbCharset (), Sitios::getDbTipo ());

			$this->db->connect ();

			$this->db->dieOnError = $this->dieOnError;
			$this->db->mostrarErrores = $this->mostrarErrores;
			$this->db->debug = $this->debug; // True si quiero que muestre el Query en por pantalla
		}
	}

	/**
	 * Comprueba que el string pasado sea un json valido
	 *
	 * @param String $str
	 * @return boolean
	 */
	public function isValidJSON($str)
	{
		json_decode ($str);
		return json_last_error () == JSON_ERROR_NONE;
	}
}
?>